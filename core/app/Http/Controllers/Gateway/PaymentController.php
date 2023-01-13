<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Level;
use App\Models\Sell;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function deposit()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Deposit Methods';
        $amount    = null;
        session()->forget('trx');
        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle', 'amount'));
    }

    public function payment()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Payment Methods';
        $amount    = $this->paymentAbleSells()->sum('total_price');
        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle', 'amount'));
    }

    public function paymentAbleSells()
    {
        return Sell::where('trx', session()->get('trx'))->where('user_id', auth()->id())->get();
    }

    public function depositInsert(Request $request)
    {
        $purchaseAmount = null;
        $sells          = [];
        if (session()->get('trx')) {
            $sells = $this->paymentAbleSells();
            $purchaseAmount = $sells->sum('total_price');
        }
        $amountValidate = $purchaseAmount ? 'nullable' : 'required';

        $request->validate([
            'amount'      => "$amountValidate|numeric|gt:0",
            'method_code' => 'required',
            'currency'    => 'required',
        ]);

        $user = auth()->user();
        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();

        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        $amount = $request->amount ?? $purchaseAmount;

        if ($gate->min_amount > $amount || $gate->max_amount < $amount) {
            $notify[] = ['error', 'Please follow deposit limit'];
            return back()->withNotify($notify);
        }
        $data = new Deposit();
        $charge   = $gate->fixed_charge + ($amount * $gate->percent_charge / 100);
        $payable  = $amount + $charge;
        $finalAmo = $payable * $gate->rate;

        $data->user_id         = $user->id;
        $data->method_code     = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount          = $amount;
        $data->charge          = $charge;
        $data->rate            = $gate->rate;
        $data->final_amo       = $finalAmo;
        $data->btc_amo         = 0;
        $data->btc_wallet      = "";
        $data->trx             = $purchaseAmount ? session()->get('trx') : getTrx();
        $data->save();
        foreach ($sells as $sell) {
            $sell->deposit_id = $data->id;
            $sell->save();
        }
        session()->forget('trx');
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }

    public function appDepositConfirm($hash)
    {
        try {
            $id = decrypt($hash);
        } catch (\Exception $ex) {
            return "Sorry, invalid URL.";
        }

        $data = Deposit::where('id', $id)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->firstOrFail();
        $user = User::findOrFail($data->user_id);
        auth()->login($user);
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }

    public function depositConfirm()
    {
        $track   = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }

        $dirName = $deposit->gateway->alias;
        $new     = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);

        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }

        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        if (@$data->session) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Payment Confirm';
        return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit'));
    }

    public static function userDataUpdate($deposit, $isManual = null)
    {

        if ($deposit->status == Status::PAYMENT_INITIATE || $deposit->status == Status::PAYMENT_PENDING) {
            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();

            $user = User::find($deposit->user_id);
            $user->balance += $deposit->amount;
            $user->save();
            $sellProducts = Sell::where('trx', $deposit->trx)->where('user_id', $deposit->user_id)->where(function ($q) {
                $q->where('status', Status::SELL_INITIATE)->orWhere('status', Status::SELL_PENDING);
            })->with(['product', 'author'])->get();

            $transaction               = new Transaction();
            $transaction->user_id      = $deposit->user_id;
            $transaction->amount       = $deposit->amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge       = $deposit->charge;
            $transaction->trx_type     = '+';
            $transaction->details      = 'Deposit Via ' . $deposit->gatewayCurrency()->name . ($sellProducts->count() ? ' for payments' : '');
            $transaction->trx          = $deposit->trx;
            $transaction->remark       = 'deposit';
            $transaction->save();

            if (!$isManual) {
                $adminNotification            = new AdminNotification();
                $adminNotification->user_id   = $user->id;
                $adminNotification->title     = 'Deposit successful via ' . $deposit->gatewayCurrency()->name;
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }
            $general = gs();



            if ($general->rb && $sellProducts->count() == 0) {
                levelCommission($user->id, $deposit->amount);
            }

            notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                'method_name'     => $deposit->gatewayCurrency()->name,
                'method_currency' => $deposit->method_currency,
                'method_amount'   => showAmount($deposit->final_amo),
                'amount'          => showAmount($deposit->amount),
                'charge'          => showAmount($deposit->charge),
                'rate'            => showAmount($deposit->rate),
                'trx'             => $deposit->trx,
                'post_balance'    => showAmount($user->balance),
            ]);

            // online payment
            if ($sellProducts->count()) {
                $soldTrx         = $deposit->trx;
                $levels          = Level::get();
                $productList     = '';
                $allTransactions = [];
                foreach ($sellProducts as $sell) {
                    $sell->deposit_id = $deposit->id;
                    $sell->status     = Status::SELL_APPROVED;
                    $sell->save();

                    $product = $sell->product;
                    $product->total_sell += 1;
                    $product->save();

                    $author  = $sell->author;
                    $charge  = $sell->buyer_fee + $sell->level_charge;
                    $earning = $sell->product_price - $charge;

                    $author->earning += $earning;
                    $author->balance += $sell->total_price;

                    if (($author->earning > $author->level->earning) && ($author->earning <= $levels->max('earning'))) {
                        updateAuthorLevel($author);
                    }

                    $now                         = now();
                    $transaction                 = [];
                    $transaction['user_id']      = $author->id;
                    $transaction['amount']       = $sell->total_price;
                    $transaction['post_balance'] = $author->balance;
                    $transaction['charge']       = 0;
                    $transaction['trx_type']     = '+';
                    $transaction['details']      = 'Balance added for selling product';
                    $transaction['trx']          = $soldTrx;
                    $transaction['remark']       = 'sell_product';
                    $transaction['created_at']   = $now;
                    $transaction['updated_at']   = $now;
                    $allTransactions[]           = $transaction;

                    $author->balance -= $charge;
                    $author->save();

                    $transaction                 = [];
                    $transaction['user_id']      = $author->id;
                    $transaction['amount']       = $charge;
                    $transaction['post_balance'] = $author->balance;
                    $transaction['charge']       = 0;
                    $transaction['trx_type']     = '-';
                    $transaction['details']      = $charge . ' ' . $general->cur_text . ' charged for selling a product';
                    $transaction['trx']          = $soldTrx;
                    $transaction['remark']       = 'selling_charge';
                    $transaction['created_at']   = $now;
                    $transaction['updated_at']   = $now;
                    $allTransactions[]           = $transaction;

                    $licenseType = ($sell->license == Status::REGULAR_LICENSE) ? 'Regular' : 'Extended';
                    $productList .= '# ' . $sell->product->name . '<br>';

                    notify($author, 'PRODUCT_SOLD', [
                        'product_name'   => $sell->product->name,
                        'license'        => $licenseType,
                        'currency'       => $general->cur_text,
                        'product_amount' => showAmount($sell->product_price),
                        'support_fee'    => showAmount($sell->support_fee),
                        'support_time'   => $sell->support_time ? $sell->support_time : 'No support',
                        'trx'            => $soldTrx,
                        'purchase_code'  => $sell->code,
                        'post_balance'   => $author->balance,
                        'buyer_fee'      => showAmount($author->level->product_charge),
                        'amount'         => $earning,
                    ]);
                }

                Transaction::insert($allTransactions);
                $user->balance = $user->balance - $deposit->amount;
                $user->save();

                $transaction               = new Transaction();
                $transaction->user_id      = $user->id;
                $transaction->amount       = $deposit->amount;
                $transaction->post_balance = $user->balance;
                $transaction->charge       = 0;
                $transaction->trx_type     = '-';
                $transaction->details      = 'Balance subtracted for purchasing products';
                $transaction->remark       = 'product_purchased';
                $transaction->trx          = $deposit->trx;
                $transaction->save();

                notify($user, 'PRODUCT_PURCHASED', [
                    'method_name'  => $deposit->gateway->name,
                    'currency'     => $general->cur_text,
                    'total_amount' => showAmount($deposit->amount),
                    'post_balance' => $user->balance,
                    'product_list' => $productList,
                ]);
            }
        }
    }

    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data  = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();

        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }

        if ($data->method_code > 999) {
            $pageTitle = 'Deposit Confirm';
            $method    = $data->gatewayCurrency();
            $gateway   = $method->method;
            return view($this->activeTemplate . 'user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data  = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();

        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }

        $gatewayCurrency = $data->gatewayCurrency();
        $gateway         = $gatewayCurrency->method;
        $formData        = $gateway->form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $data->detail = $userData;
        $data->status = Status::PAYMENT_PENDING;
        $data->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $data->user->id;
        $adminNotification->title     = 'Deposit request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        $user = $data->user;
        $sellProducts = Sell::where('trx', $data->trx)->where('user_id', $data->user_id)->where('status', Status::SELL_INITIATE)->get();
        if ($sellProducts->count()) {
            $productList = '';
            foreach ($sellProducts as $sell) {
                $sell->deposit_id = $data->id;
                $sell->status = Status::SELL_PENDING;
                $sell->save();
                $productList .= '# ' . $sell->product->name . '<br>';
            }

            notify($user, 'PAYMENT_REQUEST', [
                'method_name'  => $data->gatewayCurrency()->name,
                'amount'       => showAmount($data->amount),
                'trx'          => $data->trx,
                'product_list' => $productList,
                'currency'     => $data->method_currency,
            ]);
            $notification  = 'You have payment request has been taken';
            $redirectRoute = 'user.purchased.history';
        } else {
            $notification = 'You have deposit request has been taken';
            notify($user, 'DEPOSIT_REQUEST', [
                'method_name'     => $data->gatewayCurrency()->name,
                'method_currency' => $data->method_currency,
                'method_amount'   => showAmount($data->final_amo),
                'amount'          => showAmount($data->amount),
                'charge'          => showAmount($data->charge),
                'rate'            => showAmount($data->rate),
                'trx'             => $data->trx,
            ]);
            $redirectRoute = 'user.deposit.history';
        }

        $notify[] = ['success', $notification];
        return to_route($redirectRoute)->withNotify($notify);
    }
}
