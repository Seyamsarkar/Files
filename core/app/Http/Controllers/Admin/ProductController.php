<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ReviewHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function all()
    {
        $pageTitle = 'All Products';
        $products  = $this->getProducts();
        return view('admin.product.index', compact('pageTitle', 'products'));
    }
    public function pending()
    {
        $pageTitle = 'Pending Products';
        $products  = $this->getProducts('pending');
        return view('admin.product.index', compact('pageTitle', 'products'));
    }

    public function approved()
    {
        $pageTitle = 'Approved Products';
        $products  = $this->getProducts('approved');
        return view('admin.product.index', compact('pageTitle', 'products'));
    }

    public function softRejected()
    {
        $pageTitle = 'Soft Rejected Products';
        $products  = $this->getProducts('softRejected');
        return view('admin.product.index', compact('pageTitle', 'products'));
    }

    public function hardRejected()
    {
        $pageTitle = 'Hard Rejected Products';
        $products  = $this->getProducts('hardRejected');
        return view('admin.product.index', compact('pageTitle', 'products'));
    }

    public function resubmitted()
    {
        $pageTitle = 'Resubmitted Products';
        $products  = $this->getProducts('resubmitted');
        return view('admin.product.index', compact('pageTitle', 'products'));
    }

    protected function getProducts($scope = null)
    {
        return Product::when($scope, function ($q) use ($scope) {
            $q->$scope();
        })->searchable(['name', 'category:name', 'subcategory:name'])->latest()->with(['category', 'subcategory'])->paginate(getPaginate());
    }

    public function detail($id)
    {
        $product   = Product::with(['category', 'subcategory'])->findOrFail($id);
        $url       = url()->previous();
        $pageTitle = 'Product Detail - ' . $product->name;
        return view('admin.product.detail', compact('pageTitle', 'product', 'url'));
    }
    public function reviewHistory($id)
    {
        $product   = Product::findOrFail($id);
        $histories = $product->reviewHistories()->with(['admin', 'reviewer'])->latest('id')->paginate(getPaginate());
        $pageTitle = 'Review History: ' . $product->name;
        return view('admin.product.review_history', compact('pageTitle', 'product', 'histories'));
    }

    public function featured($id)
    {
        $product           = Product::approved()->findOrFail($id);
        $product->featured = !$product->featured;
        $product->save();
        $message = ($product->featured == 1) ? 'featured' : 'unfeatured';

        $notify[] = ['success', 'Product ' . $message . ' successfully'];
        return back()->withNotify($notify);
    }

    public function approveProduct($id)
    {

        $product              = Product::where('status', Status::PRODUCT_PENDING)->orWhere('status', Status::PRODUCT_SOFT_REJECT)->with('user')->findOrFail($id);
        $product->status      = Status::PRODUCT_APPROVE;
        $product->admin_id    = auth()->guard('admin')->id();
        $product->reviewer_id = 0;
        $product->save();

        $reviewHistory             = new ReviewHistory();
        $reviewHistory->product_id = $product->id;
        $reviewHistory->admin_id   = auth()->guard('admin')->id();
        $reviewHistory->status     = Status::PRODUCT_APPROVE;
        $reviewHistory->save();

        notify($product->user, 'PRODUCT_APPROVED', [
            'product_name' => $product->name,
        ]);

        $notify[] = ['success', 'Product approved successfully'];
        return to_route('admin.product.pending')->withNotify($notify);
    }

    public function softRejectProduct(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $product           = Product::with('user')->findOrFail($id);
        $product->status   = Status::PRODUCT_SOFT_REJECT;
        $product->admin_id = auth()->guard('admin')->id();
        $product->reviewer_id = 0;
        $product->reason   = $request->message;
        $product->save();

        $reviewHistory              = new ReviewHistory();
        $reviewHistory->product_id  = $product->id;
        $reviewHistory->admin_id = auth()->guard('admin')->id();
        $reviewHistory->status      = Status::PRODUCT_SOFT_REJECT;
        $reviewHistory->save();


        notify($product->user, 'PRODUCT_SOFT_REJECT', [
            'product_name' => $product->name,
            'reason'       => $product->reason,
        ]);

        $notify[] = ['success', 'Product soft rejected successfully'];
        return to_route('admin.product.soft.rejected')->withNotify($notify);
    }

    public function hardRejectProduct(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $product              = Product::with('user')->findOrFail($id);
        $product->status      = Status::PRODUCT_HARD_REJECT;
        $product->admin_id    = auth()->guard('admin')->id();
        $product->reviewer_id = 0;
        $product->reason      = $request->message;
        $product->save();

        $reviewHistory             = new ReviewHistory();
        $reviewHistory->product_id = $product->id;
        $reviewHistory->admin_id   = auth()->guard('admin')->id();
        $reviewHistory->status     = Status::PRODUCT_HARD_REJECT;
        $reviewHistory->save();

        notify($product->user, 'PRODUCT_HARD_REJECT', [
            'product_name' => $product->name,
            'reason'       => $product->reason,
        ]);

        $notify[] = ['success', 'Product rejected successfully'];
        return to_route('admin.product.hard.rejected')->withNotify($notify);
    }

    public function download($id)
    {
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
