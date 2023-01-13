<?php

namespace App\Http\Controllers\Reviewer;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TempProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class UpdateProductController extends Controller {

    public function pending() {
        $pageTitle = 'Update Pending Products';
        $products  = TempProduct::where('type', Status::TEMP_PRODUCT_UPDATE)->latest()->with('product:id,update_status', 'category', 'subcategory')->paginate(getPaginate());
        return view('reviewer.product.update.index', compact('pageTitle', 'products'));
    }

    public function download($id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable$th) {
            abort(404);
        }
        $tempProduct = TempProduct::findOrFail($id);
        $file        = $tempProduct->file;

        if (!$file) {
            $notify[] = ['error', 'User didn\'t upload file'];
            return back()->withNotify($notify);
        }

        $general = gs();

        if ($tempProduct->server == 0) {
            $fullPath = getImage(getFilePath('tempProductFile') . '/' . $file);
        } else {
            $fullPath = $general->ftp->domain . '/' . Storage::disk('custom-ftp')->url($file);
        }

        $title = str_replace(' ', '_', strtolower($tempProduct->name));
        $ext   = pathinfo($file, PATHINFO_EXTENSION);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $ext);
        return readfile($fullPath);
    }

    public function detail($id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable$th) {
            abort(404);
        }
        $tempProduct = TempProduct::where('type', Status::TEMP_PRODUCT_UPDATE)->with('product')->findOrFail($id);
        $product     = Product::approved()->where('update_status', Status::PRODUCT_UPDATE_PENDING)->where('id', $tempProduct->product_id)->firstOrFail();
        $pageTitle   = 'Product Detail';
        return view('reviewer.product.update.detail', compact('pageTitle', 'product', 'tempProduct'));
    }

    public function approve($id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable$th) {
            abort(404);
        }
        $tempProduct = TempProduct::where('type', Status::TEMP_PRODUCT_UPDATE)->with('product')->findOrFail($id);
        $mainProduct = $tempProduct->product;

        if ($tempProduct->featured_image) {
            $tempProductLocation = getFilePath('tempProduct');
            $mainProductLocation = getFilePath('product');

            rename($tempProductLocation . '/' . $tempProduct->featured_image, $mainProductLocation . '/' . $tempProduct->featured_image);
            rename($tempProductLocation . '/thumb_' . $tempProduct->featured_image, $mainProductLocation . '/thumb_' . $tempProduct->featured_image);
            removeFile($mainProductLocation . '/' . $mainProduct->featured_image);
            removeFile($mainProductLocation . '/thumb_' . $mainProduct->featured_image);
            $mainProduct->featured_image = $tempProduct->featured_image;
        }

        if ($tempProduct->file) {
            if ($mainProduct->server == 1) {
                $general = gs();
                $disk    = $general->server;
                removeRemoteFile($mainProduct->file, $disk);
            } else {
                $tempProductLocation = getFilePath('tempProductFile');
                $mainProductLocation = getFilePath('productFile');
                rename($tempProductLocation . '/' . $tempProduct->file, $mainProductLocation . '/' . $tempProduct->file);
                removeFile($mainProductLocation . '/' . $mainProduct->file);
            }

            $mainProduct->file = $tempProduct->file;
        }

        if ($tempProduct->screenshots) {
            $tempProductLocation = getFilePath('tempProduct');
            $mainProductLocation = getFilePath('product');

            foreach ($tempProduct->screenshots as $item) {
                rename($tempProductLocation . '/' . $item, $mainProductLocation . '/' . $item);
            }

            foreach ($mainProduct->screenshots as $item) {
                removeFile($mainProductLocation . '/' . $item);
            }
            $screenshots = $tempProduct->screenshots;

        } else {
            $screenshots = $mainProduct->screenshots;
        }

        $mainProduct->update_status    = Status::PRODUCT_UPDATE_APPROVED;
        $mainProduct->regular_price    = $tempProduct->regular_price;
        $mainProduct->extended_price   = $tempProduct->extended_price;
        $mainProduct->support          = $tempProduct->support;
        $mainProduct->support_charge   = $tempProduct->support_charge;
        $mainProduct->support_discount = $tempProduct->support_discount;
        $mainProduct->name             = $tempProduct->name;
        $mainProduct->screenshots      = $screenshots;
        $mainProduct->demo_link        = $tempProduct->demo_link;
        $mainProduct->description      = $tempProduct->description;
        $mainProduct->tag              = $tempProduct->tag;
        $mainProduct->message          = $tempProduct->message;
        $mainProduct->category_details = $tempProduct->category_details;
        $mainProduct->save();

        $tempProduct->delete();

        notify($mainProduct->user, 'PRODUCT_UPDATE_APPROVED', [
            'product_name' => $mainProduct->name,
        ]);

        $notify[] = ['success', 'Update product approved successfully'];
        return to_route('reviewer.update.product.pending')->withNotify($notify);
    }

    public function reject(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable$th) {
            abort(404);
        }
        $tempProduct                = TempProduct::where('type', Status::TEMP_PRODUCT_UPDATE)->with('product')->findOrFail($id);
        $mainProduct                = $tempProduct->product;
        $mainProduct->update_status = Status::PRODUCT_UPDATE_REJECTED;
        $mainProduct->update_reject = $request->message;
        $mainProduct->save();

        if ($tempProduct->image) {
            $tempProductLocation = getFilePath('tempProduct');
            removeFile($tempProductLocation . '/' . $tempProduct->image);
            removeFile($tempProductLocation . '/thumb_' . $tempProduct->image);
        }

        if ($tempProduct->file) {
            $tempProductLocation = getFilePath('tempProductFile');
            removeFile($tempProductLocation . '/' . $tempProduct->file);
        }

        if ($tempProduct->screenshots) {
            $tempProductLocation = getFilePath('tempProduct');

            foreach ($tempProduct->screenshots as $item) {
                removeFile($tempProductLocation . '/' . $item);
            }

        }

        $tempProduct->delete();
        notify($mainProduct->user, 'PRODUCT_UPDATE_REJECTED', [
            'product_name' => $mainProduct->name,
        ]);

        $notify[] = ['success', 'Update product rejected successfully'];
        return to_route('reviewer.update.product.pending')->withNotify($notify);
    }

}
