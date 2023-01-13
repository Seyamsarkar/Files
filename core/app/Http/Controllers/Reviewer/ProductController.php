<?php

namespace App\Http\Controllers\Reviewer;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ReviewHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function pending()
    {
        $pageTitle = 'Pending Products';
        $products  = $this->getProducts('pending');
        return view('reviewer.product.index', compact('pageTitle', 'products'));
    }

    public function approved()
    {
        $pageTitle = 'Approved Products';
        $products  = $this->getProducts('approved');
        return view('reviewer.product.index', compact('pageTitle', 'products'));
    }

    public function softRejected()
    {
        $pageTitle = 'Soft Rejected Products';
        $products  = $this->getProducts('softRejected');
        return view('reviewer.product.index', compact('pageTitle', 'products'));
    }

    public function hardRejected()
    {
        $pageTitle = 'Hard Rejected Products';
        $products  = $this->getProducts('hardRejected');
        return view('reviewer.product.index', compact('pageTitle', 'products'));
    }

    public function resubmitted()
    {
        $pageTitle = 'Resubmitted Products';
        $products  = $this->getProducts('resubmitted');
        return view('reviewer.product.index', compact('pageTitle', 'products'));
    }
    public function reviewedByMe()
    {
        $pageTitle = 'Resubmitted Products';
        $products  = $this->getProducts('reviewedByMe');
        return view('reviewer.product.index', compact('pageTitle', 'products'));
    }

    protected function getProducts($scope = null)
    {
        $products = Product::$scope()->searchable(['name', 'category:name', 'subcategory:name'])->latest()->with(['category', 'subcategory'])->paginate(getPaginate());
        return $products;
    }

    public function detail($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            abort(404);
        }
        $product   = Product::with(['category', 'subcategory'])->findOrFail($id);
        $pageTitle = 'Product Detail - ' . $product->name;
        return view('reviewer.product.detail', compact('pageTitle', 'product'));
    }

    public function approveProduct($id)
    {

        $product              = Product::with('user')->findOrFail($id);
        $product->status      = Status::PRODUCT_APPROVE;
        $product->reviewer_id = auth()->guard('reviewer')->id();
        $product->admin_id    = 0;
        $product->save();

        $reviewHistory              = new ReviewHistory();
        $reviewHistory->product_id  = $product->id;
        $reviewHistory->reviewer_id = authReviewerId();
        $reviewHistory->status      = Status::PRODUCT_APPROVE;
        $reviewHistory->save();

        notify($product->user, 'PRODUCT_APPROVED', [
            'product_name' => $product->name,
        ]);

        $notify[] = ['success', 'Product approved successfully'];
        return back()->withNotify($notify);
    }

    public function softRejectProduct(Request $request, $id)
    {

        $request->validate([
            'message' => 'required|string',
        ]);

        $product              = Product::with('user')->findOrFail($id);
        $product->status      = Status::PRODUCT_SOFT_REJECT;
        $product->reviewer_id = auth()->guard('reviewer')->id();
        $product->admin_id = 0;
        $product->reason      = $request->message;
        $product->save();

        $reviewHistory              = new ReviewHistory();
        $reviewHistory->product_id  = $product->id;
        $reviewHistory->reviewer_id = authReviewerId();
        $reviewHistory->status      = Status::PRODUCT_SOFT_REJECT;
        $reviewHistory->save();

        notify($product->user, 'PRODUCT_SOFT_REJECT', [
            'product_name' => $product->name,
            'reason'       => $product->reason,
        ]);

        $notify[] = ['success', 'Product soft rejected successfully'];
        return back()->withNotify($notify);
    }

    public function hardRejectProduct(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $product              = Product::with('user')->findOrFail($id);
        $product->status      = Status::PRODUCT_HARD_REJECT;
        $product->reviewer_id = auth()->guard('reviewer')->id();
        $product->admin_id    = 0;
        $product->reason      = $request->message;
        $product->save();

        $reviewHistory              = new ReviewHistory();
        $reviewHistory->product_id  = $product->id;
        $reviewHistory->reviewer_id = authReviewerId();
        $reviewHistory->status      = Status::PRODUCT_HARD_REJECT;
        $reviewHistory->save();

        notify($product->user, 'PRODUCT_HARD_REJECT', [
            'product_name' => $product->name,
            'reason'       => $product->reason,
        ]);

        $notify[] = ['success', 'Product rejected successfully'];
        return back()->withNotify($notify);
    }

    public function download($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            abort(404);
        }
        $product = Product::findOrFail($id);
        $file    = $product->file;
        $general = gs();

        if ($product->server == 0) {
            $fullPath = getImage(getFilePath('productFile') . '/' . $file);
        } else {
            $fullPath = $general->ftp->domain . '/' . Storage::disk('custom-ftp')->url($product->file);
        }

        $title = str_replace(' ', '_', strtolower($product->name));
        $ext   = pathinfo($file, PATHINFO_EXTENSION);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $ext);
        return readfile($fullPath);
    }
}
