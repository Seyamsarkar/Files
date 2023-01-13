<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller {
    public function index(Request $request) {
        $pageTitle = 'All Review';
        $reviews   = $this->getReviews();
        return view('admin.review.index', compact('pageTitle', 'reviews'));
    }

    public function reported() {
        $pageTitle = 'Reported Reviews';
        $reviews   = $this->getReviews('reported');
        return view('admin.review.index', compact('pageTitle', 'reviews'));
    }

    protected function getReviews($scope = null) {
        if ($scope) {
            $reviews = Review::$scope()->with(['product:id,name', 'user', 'sell:id,code']);
        } else {
            $reviews = Review::with(['product:id,name', 'user', 'sell:id,code']);
        }

        return $reviews->searchable(['user:username', 'product:name', 'sell:code'])->latest()->paginate(getPaginate());
    }

    public function delete($id) {

        $review = Review::with('product.user')->findOrFail($id);

        $product = $review->product;

        $totalRatingProduct   = $product->total_review - $review->rating;
        $totalResponseProduct = $product->total_response - 1;
        if ($totalRatingProduct != 0) {
            $avgRatingProduct = $totalRatingProduct / $totalResponseProduct;
        } else {
            $avgRatingProduct = 0;
        }

        $product->total_review   = $totalRatingProduct;
        $product->total_response = $totalResponseProduct;
        $product->avg_rating     = $avgRatingProduct;
        $product->save();

        $user = $review->product->user;

        $totalRatingAuthor   = $user->total_review - $review->rating;
        $totalResponseAuthor = $user->total_response - 1;

        if ($totalRatingAuthor != 0) {
            $avgRatingAuthor = $totalRatingAuthor / $totalResponseAuthor;
        } else {
            $avgRatingAuthor = 0;
        }

        $user->total_review   = $totalRatingAuthor;
        $user->total_response = $totalResponseAuthor;
        $user->avg_rating     = $avgRatingAuthor;
        $user->save();

        $review->delete();

        $notify[] = ['success', 'Review removed successfully'];
        return back()->withNotify($notify);
    }

    public function reject($id) {
        $review         = Review::findOrFail($id);
        $review->status = 1;
        $review->save();

        $notify[] = ['success', 'Reported request rejected successfully'];
        return back()->withNotify($notify);
    }
}