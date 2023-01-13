<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Level;
use App\Models\Sell;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'wallet_type' => 'required|in:own,online',
        ]);
        $user = User::active()->findOrFail(auth()->id());

        $carts      = Cart::active()->where('order_number', $user->id)->with('product')->get();
        $totalPrice = $carts->sum('total_price');

        if ($request->wallet_type == 'own' && $totalPrice > $user->balance) {
            $notify[] = ['error', 'Insufficient balance.'];
            return back()->withNotify($notify);
        }

        $general = gs();

        $productList     = '';
        $allSales        = [];
        $allTransactions = [];
        $levels          = Level::get();

        $soldTrx = getTrx();
        foreach ($carts as $cart) {
            $author               = $cart->author;
            $product              = $cart->product;

            $now             = now();
            $sell            = [];
            $sell['code']          = $cart->code;
            $sell['author_id']     = $cart->author_id;
            $sell['user_id']       = $user->id;
            $sell['product_id']    = $cart->product_id;
            $sell['license']       = $cart->license;
            $sell['support']       = $cart->support;
            $sell['support_time']  = $cart->support_time;
            $sell['support_fee']   = $cart->support_fee;
            $sell['product_price'] = $cart->product_price;
            $sell['total_price']   = $cart->total_price;
            $sell['status']        = ($request->wallet_type == 'online') ? Status::SELL_INITIATE : Status::SELL_APPROVED;
            $sell['trx']           = $soldTrx;
            $sell['created_at']    = $now;
            $sell['updated_at']    = $now;
            $sell['buyer_fee']     = $product->category->buyer_fee;
            $sell['level_charge']  = (($cart->product_price * $author->level->product_charge) / 100);
            $allSales[]                = $sell;

            if ($request->wallet_type == 'own') {

                $product->total_sell += 1;
                $product->save();

                $charge  = $sell['buyer_fee'] + $sell['level_charge'];
                $earning = $sell['total_price'] - $charge;

                $author->earning += $earning;
                $author->balance += $sell['total_price'];

                if (($author->earning > $author->level->earning) && ($author->earning <= $levels->max('earning'))) {
                    updateAuthorLevel($author);
                }

                $transaction                 = [];
                $transaction['user_id']      = $author->id;
                $transaction['amount']       = $sell['total_price'];
                $transaction['post_balance'] = $author->balance;
                $transaction['charge']       = 0;
                $transaction['trx_type']     = '+';
                $transaction['details']      = 'Balance added for selling product';
                $transaction['trx']          = $soldTrx;
                $transaction['remark']       = 'sell_product';
                $transaction['created_at']   = $now;
                $transaction['updated_at']   = $now;
                $allTransactions[]           = $transaction;

                $author->balance  -= $charge;
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

                $licenseType = $cart->license == Status::REGULAR_LICENSE ? 'Regular' : 'Extended';
                $productList .= '# ' . $cart->product->name . '<br>';

                notify($author, 'PRODUCT_SOLD', [
                    'product_name'   => $cart->product->name,
                    'license'        => $licenseType,
                    'currency'       => $general->cur_text,
                    'product_amount' => showAmount($cart->product_price),
                    'support_fee'    => showAmount($cart->support_fee),
                    'support_time'   => $cart->support_time ? $cart->support_time : 'No support',
                    'trx'            => $soldTrx,
                    'purchase_code'  => $cart->code,
                    'post_balance'   => $author->balance,
                    'buyer_fee'      => $charge,
                    'amount'         => $earning,
                ]);
            }

            $cart->delete();
        }

        Sell::insert($allSales);

        if ($request->wallet_type == 'online') {
            session()->put('trx', $soldTrx);
            return to_route('user.deposit.payment');
        }

        if ($request->wallet_type == 'own') {

            Transaction::insert($allTransactions);
            $user->balance = $user->balance - $totalPrice;
            $user->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $totalPrice;
            $transaction->post_balance = $user->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '-';
            $transaction->details      = 'Balance subtracted for purchasing products';
            $transaction->remark       = 'product_purchased';
            $transaction->trx          = getTrx();
            $transaction->save();

            notify($user, 'PRODUCT_PURCHASED', [
                'method_name'  => 'Own Wallet',
                'currency'     => $general->cur_text,
                'total_amount' => showAmount($totalPrice),
                'post_balance' => $user->balance,
                'product_list' => $productList,
            ]);

            $notify[] = ['success', 'Product purchased successfully'];
            return to_route('user.purchased.history')->withNotify($notify);
        }
    }
}
