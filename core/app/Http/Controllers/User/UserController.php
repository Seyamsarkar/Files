<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\Cart;
use App\Models\CommissionLog;
use App\Models\Deposit;
use App\Models\Form;
use App\Models\Product;
use App\Models\Referral;
use App\Models\Sell;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle                 = 'Dashboard';
        $user                      = auth()->user();
        $data['total_product']     = Product::available()->where('user_id', $user->id)->count();
        $data['total_purchased']   = Sell::approved()->where('user_id', $user->id)->count();
        $data['total_transaction'] = Transaction::where('user_id', $user->id)->count();
        $data['total_sell']        = Sell::approved()->where('author_id', $user->id)->count();
        $data['monthly_released']  = $user->products()->whereMonth('created_at', now())->where('status', Status::PRODUCT_APPROVE)->count();
        $data['monthly_purchased'] = $user->buy()->whereMonth('created_at', now())->where('status', Status::SELL_APPROVED)->count();
        $data['total_deposited']   = Deposit::successful()->where('user_id', $user->id)->sum('amount');
        $data['total_withdrawn']   = Withdrawal::approved()->where('user_id', $user->id)->sum('amount');

        $sellChart = Sell::select('id', 'created_at', 'product_price')
            ->where('author_id', $user->id)->where('status', Status::SELL_APPROVED)->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('m');
            });

        $sellCount = [];
        $sellArr   = [];
        $sumPrice  = [];

        foreach ($sellChart as $key => $value) {
            $iniTPrice = 0;

            foreach ($value as $price) {
                $iniTPrice += $price->product_price;
            }

            $sellCount[(int) $key] = count($value);
            $sumPrice[(int) $key]  = $iniTPrice;
        }

        $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        for ($i = 1; $i <= 12; $i++) {

            if (!empty($sellCount[$i])) {
                $sellArr[$i]['count']         = $sellCount[$i];
                $sellArr[$i]['product_price'] = $sumPrice[$i];
            } else {
                $sellArr[$i]['count']         = 0;
                $sellArr[$i]['product_price'] = 0;
            }

            $sellArr[$i]['month'] = $month[$i - 1];
        }

        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'user', 'data', 'sellArr'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits  = auth()->user()->deposits()->notPayment()->where('method_code', '!=', 0)->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $general   = gs();
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view($this->activeTemplate . 'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);

        if ($response) {
            $user->tsc = $request->key;
            $user->ts  = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user     = auth()->user();
        $response = verifyG2fa($user, $request->code);

        if ($response) {
            $user->tsc = null;
            $user->ts  = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }

        return back()->withNotify($notify);
    }

    public function transactions(Request $request)
    {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {

        if (auth()->user()->kv == 2) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }

        if (auth()->user()->kv == 1) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }

        $pageTitle = 'KYC Form';
        $form      = Form::where('act', 'kyc')->first();
        return view($this->activeTemplate . 'user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user      = auth()->user();
        $pageTitle = 'KYC Data';
        return view($this->activeTemplate . 'user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form           = Form::where('act', 'kyc')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData       = $formProcessor->processFormData($request, $formData);
        $user           = auth()->user();
        $user->kyc_data = $userData;
        $user->kv       = 2;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function attachmentDownload($fileHash)
    {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general   = gs();
        $title     = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype  = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }

        $pageTitle = 'User Data';
        return view($this->activeTemplate . 'user.user_data', compact('pageTitle', 'user'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();

        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }

        $request->validate([
            'firstname' => 'required',
            'lastname'  => 'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->address   = [
            'country' => @$user->address->country,
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'city'    => $request->city,
        ];
        $user->profile_complete = 1;
        $user->save();

        if (session()->has('order_number')) {
            Cart::active()->where('order_number', session()->get('order_number'))->update(['order_number' => $user->id]);
            session()->forget('order_number');
        }

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function sellHistory(Request $request)
    {
        $pageTitle = 'Sell History';
        $sells     = Sell::approved()->where('author_id', auth()->id())->searchable(['code', 'product:name'])->with('product')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.product.sell_history', compact('pageTitle', 'sells'));
    }

    public function referral()
    {
        $pageTitle = "My referral Users";
        $user      = auth()->user();
        $users     = User::where('ref_by', $user->id)->paginate(getPaginate());
        $maxLevel  = Referral::max('level');
        return view($this->activeTemplate . 'user.referral.index', compact('pageTitle', 'users', 'user', 'maxLevel'));
    }

    public function commissionLogs()
    {
        $pageTitle = "Referral Commission";
        $logs      = CommissionLog::where('type', 'deposit_commission')->where('to_id', auth()->id())->with('user', 'byWho')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.referral.logs', compact('pageTitle', 'logs'));
    }

    public function emailAuthor(Request $request)
    {
        $request->validate([
            'author'  => 'required',
            'message' => 'required',
        ]);
        $author = User::active()->where('username', $request->author)->firstOrFail();

        notify($author, 'MAIL_TO_ATHOR', [
            'reply_to' => auth()->user()->email,
            'message'  => $request->message,
        ]);

        $notify[] = ['success', 'You have successfully sent your message.'];
        return back()->withNotify($notify);
    }
}
