<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Cart;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CartController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function addToCart(Request $request, $id)
    {
        $request->validate([
            'license'          => 'required|numeric|in:1,2',
            'extended_support' => 'nullable|in:on',
        ]);

        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            abort(404);
        }

        $user    = auth()->user();
        $product = Product::available()->where('user_id', '!=', @$user->id)->findOrFail($id);

        $orderNumber = @$user->id;
        if (!$orderNumber) {
            $orderNumber = session()->get('order_number');
            if (!$orderNumber) {
                $orderNumber = getTrx(8);
                session()->put('order_number', $orderNumber);
            }
        }
        $productPrice = ($request->license == Status::REGULAR_LICENSE) ? $product->regular_price : $product->extended_price;
        $totalPrice   = $productPrice;
        $general      = gs();
        $supportFee   = 0;
        if ($product->support == Status::YES && $request->extended_support) {
            $supportTime = Carbon::now()->addMonths($general->extended)->format('Y-m-d');
            $data        = $this->supportDiscount($product, $request->license);
            $totalPrice  = $data[0];
            $supportFee  = $data[1];
        } elseif ($product->support == Status::YES) {
            $supportTime = Carbon::now()->addMonths($general->regular)->format('Y-m-d');
        } else {
            $supportTime = null;
        }

        $cartExists = Cart::where('order_number', $orderNumber)->where('product_id', $product->id)->first();
        if ($cartExists) {
            $cartExists->delete();
        }

        $cart                = new Cart();
        $cart->order_number  = $orderNumber;
        $cart->code          = getTrx();
        $cart->author_id     = $product->user_id;
        $cart->product_id    = $product->id;
        $cart->license       = $request->license;
        $cart->support       = $product->support;
        $cart->support_time  = $supportTime;
        $cart->support_fee   = $supportFee;
        $cart->product_price = $productPrice;
        $cart->total_price   = $totalPrice;
        $cart->save();

        $notify[] = ['success', 'Product added to cart successfully'];
        return back()->withNotify($notify);
    }

    protected function supportDiscount($product, $license)
    {
        $price                = ($license == Status::REGULAR_LICENSE) ? $product->regular_price : $product->extended_price;
        $tempCharge           = ($price * $product->support_charge) / 100;
        $totalSupportDiscount = $product->support_discount ? (($tempCharge * $product->support_discount) / 100) : 0;
        $supportFee           = $tempCharge - $totalSupportDiscount;
        $totalPrice           = $price + $supportFee;
        $price                = [$totalPrice, $supportFee];

        return $price;
    }

    public function myCart()
    {
        $pageTitle    = 'My Cart';
        $user         = auth()->user();
        $order_number = $user ? $user->id : session()->get('order_number');
        $orders       = Cart::active()->where('order_number', $order_number)->with(['product.user', 'author'])->get();
        return view($this->activeTemplate . 'cart', compact('pageTitle', 'orders'));
    }

    public function removeCart($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            abort(404);
        }
        $order = Cart::findOrFail($id);
        $order->delete();
        $notify[] = ['success', 'Product successfully removed from cart'];
        return back()->withNotify($notify);
    }
}
