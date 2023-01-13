<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Sell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PurchasedController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->activeTemplate = activeTemplate();
    }

    public function purchasedHistory(Request $request)
    {
        $pageTitle = "Purchased History";
        $sells     = Sell::where('user_id', auth()->id())->where('status', '!=', Status::SELL_INITIATE)->searchable(['code', 'product:name'])->with('product:id,name,status', 'review', 'deposit.gateway')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.product.purchased', compact('pageTitle', 'sells'));
    }

    public function download($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            abort(404);
        }
        $sell     = Sell::approved()->where('id', $id)->where('user_id', auth()->id())->with('product')->firstOrFail();
        $product  = $sell->product;
        $file     = $product->file;
        $fullPath = getFilePath('productFile') . '/' . $file;
        $title    = str_replace(' ', '_', strtolower($product->name));
        $ext      = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($fullPath);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($fullPath);
    }

    public function invoice($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            abort(404);
        }
        $pageTitle = 'Product Invoice';
        $sell      = Sell::approved()->where('id', $id)->where('user_id', auth()->id())->with('product')->firstOrFail();
        $fileName  = strtolower(str_replace(' ', '_', $sell->product->name));
        return view($this->activeTemplate . 'user.product.invoice', compact('pageTitle', 'sell', 'fileName'));
    }

    public function review(Request $request, $id)
    {

        $request->validate([
            'rating' => 'required|integer|gt:0|max:5',
            'review' => 'required|string',
        ]);

        $user    = auth()->user();
        $sell    = Sell::approved()->where('id', $id)->where('user_id', $user->id)->with('product.user')->firstOrFail();
        $product = $sell->product;
        $author = $product->user;

        $review = Review::where('product_id', $product->id)->where('user_id', $user->id)->first();
        if (!$review) {
            $review                  = new Review();
            $product->total_response = $product->total_response + 1;
            $author->total_response  = $author->total_response + 1;
        }

        $totalRatingProduct = $product->total_review - $review->rating + $request->rating;
        $totalRatingAuthor  = $author->total_review - $review->rating + $request->rating;

        $review->sell_id    = $sell->id;
        $review->product_id = $product->id;
        $review->user_id    = $user->id;
        $review->rating     = $request->rating;
        $review->review     = $request->review;
        $review->save();


        $totalResponseProduct = $product->total_response;
        $avgRatingProduct     = $totalRatingProduct / $totalResponseProduct;

        $product->total_review   = $totalRatingProduct;
        $product->total_response = $totalResponseProduct;
        $product->avg_rating     = $avgRatingProduct;
        $product->save();


        $totalResponseAuthor = $author->total_response;
        $avgRatingAuthor     = $totalRatingAuthor / $totalResponseAuthor;

        $author->total_review   = $totalRatingAuthor;
        $author->total_response = $totalResponseAuthor;
        $author->avg_rating     = $avgRatingAuthor;
        $author->save();

        $notify[] = ['success', 'Thanks for your review'];
        return back()->withNotify($notify);
    }
}
