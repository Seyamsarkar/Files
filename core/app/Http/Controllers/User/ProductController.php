<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Reply;
use App\Models\Review;
use App\Models\TempProduct;
use App\Rules\FileTypeValidate;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {
        $pageTitle = 'My Products';
        $products  = Product::available()->where('status', '!=', Status::PRODUCT_DELETE)->where('user_id', auth()->id())->searchable(['name', 'category:name', 'subcategory:name'])->with(['category', 'subcategory'])->withCount('reviews')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.product.index', compact('pageTitle', 'products'));
    }
    public function hidden()
    {
        $pageTitle = 'Hidden Products';
        $products  = Product::hidden()->where('status', '!=', Status::PRODUCT_DELETE)->where('user_id', auth()->id())->searchable(['name', 'category:name', 'subcategory:name'])->with(['category', 'subcategory'])->withCount('reviews')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.product.index', compact('pageTitle', 'products'));
    }

    public function add()
    {
        $pageTitle = 'Add New Product';
        $route     = route('user.product.store');
        return view($this->activeTemplate . 'user.product.form', compact('pageTitle', 'route'));
    }

    public function store(Request $request)
    {
        $supportValidation = $request->support == Status::YES ? 'required' : 'nullable';
        $validation_rule   = [
            'category_id'      => 'required|integer|exists:categories,id',
            'subcategory_id'   => 'nullable|integer|exists:subcategories,id',
            'regular_price'    => 'required|numeric|gt:0',
            'extended_price'   => 'required|numeric|gt:0',
            'support'          => 'required|integer|in:1,0',
            'support_discount' => "$supportValidation|numeric|max:100",
            'support_charge'   => "$supportValidation|numeric|max:100",
            'name'             => 'required|string|max:255',
            'featured_image'   => ['required', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'file'             => ['required', 'mimes:zip', new FileTypeValidate(['zip'])],
            'screenshots'      => 'required|array',
            'screenshots.*'    => ['required', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'demo_link'        => 'required|url|max:255',
            'description'      => 'required',
            'message'          => 'nullable|string|max:255',
            'tag.*'            => 'required|max:255',
            'c_details'        => 'nullable|array',
        ];
        $category             = Category::active()->with(['subcategories:id,category_id,name', 'categoryFeature'])->findOrFail($request->category_id);
        $categoryFeature      = $category->categoryFeature;
        $categoryFeatureInput = [];

        foreach ($categoryFeature->pluck('name') as $item) {
            $featureName                        = str_replace(' ', '_', strtolower($item));
            $featureKey                         = 'c_details.' . $featureName;
            $validation_rule[$featureKey]       = 'required';
            $categoryFeatureInput[$featureName] = @$request->$featureKey;
        }

        $request->validate($validation_rule, [
            'tag.*.required' => 'Add at least one tag',
            'tag.*.max'      => 'Total options should not be more than 40 characters',
        ]);

        if ($request->subcategory_id) {
            $subcategoryId = $category->subcategories->pluck('id')->toArray();
            if (!in_array($request->subcategory_id, $subcategoryId)) {
                $notify[] = ['error', 'Subcategory is invalid'];
                return back()->withNotify($notify);
            }
        }

        $minPrice = $category->buyer_fee + (($category->buyer_fee * auth()->user()->level->product_charge) / 100);
        $general  = gs();

        if (($request->regular_price < $minPrice) || ($request->extended_price < $minPrice)) {
            $notify[] = ['error', 'Minimum price is ' . $minPrice . $general->cur_text];
            return back()->withNotify($notify);
        }

        $product = new Product();
        $this->uploadFile($product, 'product');
        $product  = $this->productInsert($product, $category, $categoryFeatureInput);
        $notify[] = ['success', 'Product submit successfully'];
        return to_route('user.hidden.product')->withNotify($notify);
    }

    public function resubmit($id)
    {
        $pageTitle   = 'Resubmit Product';
        $product     = Product::softRejected()->where('user_id', auth()->id())->findOrFail($id);
        $screenshots = [];
        $route       = route('user.product.resubmit.store', $product->id);
        foreach ($product->screenshots as $image) {
            $img['id']     = $product->id;
            $img['src']    = getImage(getFilePath('product') . '/' . $image);
            $screenshots[] = $img;
        }
        return view($this->activeTemplate . 'user.product.form', compact('pageTitle', 'product', 'screenshots', 'route'));
    }

    public function resubmitStore(Request $request, $id)
    {
        $supportValidation = $request->support == Status::YES ? 'required' : 'nullable';
        $validation_rule   = [
            'category_id'      => 'required|integer|exists:categories,id',
            'subcategory_id'   => 'nullable|integer|exists:subcategories,id',
            'regular_price'    => 'required|numeric|gt:0',
            'extended_price'   => 'required|numeric|gt:0',
            'support'          => 'required|integer|in:1,0',
            'support_discount' => "$supportValidation|numeric|max:100",
            'support_charge'   => "$supportValidation|numeric|max:100",
            'name'             => 'required|string|max:255',
            'featured_image'   => ['nullable', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'file'             => ['required', 'mimes:zip', new FileTypeValidate(['zip'])],
            'screenshots'      => 'nullable|array',
            'screenshots.*'    => ['nullable', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'demo_link'        => 'required|url|max:255',
            'description'      => 'required',
            'message'          => 'nullable|max:255',
            'tag.*'            => 'required|max:255',
            'c_details'        => 'nullable|array',
        ];
        $category        = Category::active()->with(['subcategories:id,category_id,name', 'categoryFeature'])->findOrFail($request->category_id);
        $categoryFeature = $category->categoryFeature;

        foreach ($categoryFeature->pluck('name') as $item) {
            $validation_rule['c_details.' . str_replace(' ', '_', strtolower($item))] = 'required';
        }

        $request->validate($validation_rule, [
            'tag.*.required' => 'Add at least one tag',
            'tag.*.max'      => 'Total options should not be more than 40 characters',
        ]);

        if ($request->subcategory_id) {
            $subcategoryId = $category->subcategories->pluck('id')->toArray();

            if (!in_array($request->subcategory_id, $subcategoryId)) {
                $notify[] = ['error', 'Subcategory is invalid'];
                return back()->withNotify($notify);
            }
        }
        $categoryFeatureInput = $request['c_details'] ?? [];

        if (count($categoryFeatureInput) != count($categoryFeature)) {
            $notify[] = ['error', 'All feature is required'];
            return back()->withNotify($notify);
        }

        $minPrice = $category->buyer_fee + (($category->buyer_fee * auth()->user()->level->product_charge) / 100);

        $general = gs();

        if (($request->regular_price < $minPrice) || ($request->extended_price < $minPrice)) {
            $notify[] = ['error', 'Minimum price is ' . $minPrice . $general->cur_text];
            return back()->withNotify($notify);
        }

        $product = Product::softRejected()->where('user_id', auth()->id())->findOrFail($id);
        $this->uploadFile($product, 'product');
        $product = $this->productInsert($product, $category, $categoryFeatureInput);

        $notify[] = ['success', 'Product resubmitted successfully'];
        return to_route('user.product.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle   = 'Update Product';
        $product     = Product::approved()->where('user_id', auth()->id())->findOrFail($id);
        $route       = route('user.product.update', $product->id);
        $screenshots = [];
        foreach ($product->screenshots as $screenshot) {
            $img['src']    = getImage(getFilePath('product') . '/' . $screenshot);
            $screenshots[] = $img;
        }
        return view($this->activeTemplate . 'user.product.form', compact('pageTitle', 'product', 'screenshots', 'route'));
    }

    public function update(Request $request, $id)
    {
        $supportValidation = $request->support == Status::YES ? 'required' : 'nullable';
        $validation_rule   = [
            'regular_price'    => 'required|numeric|gt:0',
            'extended_price'   => 'required|numeric|gt:0',
            'support'          => 'required|integer|in:1,0',
            'support_discount' => "$supportValidation|numeric|max:100",
            'support_charge'   => "$supportValidation|numeric|max:100",
            'name'             => 'required|string|max:255',
            'featured_image'   => ['nullable', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'file'             => ['nullable', 'mimes:zip', new FileTypeValidate(['zip'])],
            'screenshots'      => 'nullable|array',
            'screenshots.*'    => ['nullable', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'demo_link'        => 'required|url|max:255',
            'description'      => 'required',
            'message'          => 'nullable|max:255',
            'tag.*'            => 'required|max:255',
            'c_details'        => 'nullable|array',
        ];

        $checkProduct = TempProduct::where('user_id', auth()->id())->where('product_id', $id)->where('type', 2)->first();

        if ($checkProduct) {
            $notify[] = ['error', 'Previous update is pending for this product'];
            return back()->withNotify($notify);
        }

        $product              = Product::approved()->where('user_id', auth()->id())->with('category', 'category.categoryFeature')->findOrFail($id);
        $category             = $product->category;
        $categoryFeature      = $product->category->categoryFeature;
        $categoryFeatureInput = $request['c_details'] ?? [];

        if (count($categoryFeatureInput) != count($categoryFeature)) {
            $notify[] = ['error', 'Something goes wrong.'];
            return back()->withNotify($notify);
        }

        foreach ($categoryFeature->pluck('name') as $item) {
            $validation_rule['c_details.' . str_replace(' ', '_', strtolower($item))] = 'required';
        }

        $request->validate($validation_rule, [
            'tag.*.required' => 'Add at least one tag',
            'tag.*.max'      => 'Total options should not be more than 191 characters',
        ]);

        $minPrice = $category->buyer_fee + (($category->buyer_fee * auth()->user()->level->product_charge) / 100);

        if (($request->regular_price < $minPrice) || ($request->extended_price < $minPrice)) {
            $notify[] = ['error', 'Minimum price is ' . $minPrice];
            return back()->withNotify($notify);
        }

        $product->update_status = Status::PRODUCT_UPDATE_PENDING;
        $product->save();

        $tempProduct = new TempProduct();
        $this->uploadFile($tempProduct, 'tempProduct');
        $tempProduct = $this->productInsert($tempProduct, $category, $categoryFeatureInput);

        $tempProduct->product_id     = $product->id;
        $tempProduct->category_id    = $product->category_id;
        $tempProduct->subcategory_id = $product->subcategory_id;
        $tempProduct->save();

        $notify[] = ['success', 'Your action is in the process wait for the approval'];
        return to_route('user.product.index')->withNotify($notify);
    }

    protected function productInsert($product, $category, $categoryFeatureInput)
    {
        $request = request();

        $product->user_id          = auth()->id();
        $product->category_id      = $request->category_id;
        $product->subcategory_id   = $request->subcategory_id ?? 0;
        $product->regular_price    = $request->regular_price + $category->buyer_fee;
        $product->extended_price   = $request->extended_price + $category->buyer_fee;
        $product->support          = $request->support;
        $product->support_charge   = $request->support_charge ?? 0;
        $product->support_discount = $request->support_discount ?? 0;
        $product->name             = $request->name;
        $product->demo_link        = $request->demo_link;
        $product->description      = $request->description;
        $product->tag              = array_values($request->tag);
        $product->message          = $request->message;
        $product->category_details = $categoryFeatureInput;

        if ($product->status == Status::PRODUCT_SOFT_REJECT) {
            $product->status = Status::PRODUCT_RESUBMIT;
        }

        $product->save();
        return $product;
    }

    protected function uploadFile($product, $path)
    {
        $request = request();
        $general = gs();

        if ($request->hasFile('file')) {
            $date = date('Y') . '/' . date('m') . '/' . date('d');

            if ($general->server_name == 'current') {
                try {
                    $oldFile       = $product->file ?? null;
                    $product->file = fileUploader($request->file, getFilePath($path . 'File'), null, $oldFile);
                } catch (\Exception $e) {
                    $notify[] = ['error', 'Image could not be uploaded'];
                    return back()->withNotify($notify);
                }
                $product->server = 0;
            } else {
                try {

                    $fileExtension = $request->file('file')->getClientOriginalExtension();
                    $file          = File::get($request->file);
                    $location      = 'FILES/' . $date;

                    $responseValue = uploadRemoteFile($file, $location, $fileExtension);

                    if ($responseValue[0] == 'error') {
                        return response()->json(['errors' => $responseValue[1]]);
                    } else {
                        $product->file = $responseValue[1];
                    }
                } catch (\Exception $e) {
                    return response()->json(['errors' => 'Could not upload the file']);
                }

                $product->server = 1;
            }
        }

        if ($request->hasFile('screenshots')) {

            foreach ($request->screenshots as $screenshot) {
                try {
                    $productScreenshot[] = fileUploader($screenshot, getFilePath($path));
                } catch (\Exception $e) {
                    $notify[] = ['error', 'Image could not be uploaded'];
                    return back()->withNotify($notify);
                }
            }

            if ($product->screenshots && $path == 'product') {
                foreach ($product->screenshots as $oldScreenshot) {
                    $location = getFilePath($path);
                    removeFile($location . '/' . $oldScreenshot);
                }
            }

            $product->screenshots = array_values($productScreenshot);
        }

        if ($request->hasFile('featured_image')) {
            try {
                $product->featured_image = fileUploader($request->featured_image, getFilePath($path), getFileSize($path), $product->featured_image, getFileThumb($path));
            } catch (\Exception $e) {
                $notify[] = ['error', 'Image could not be uploaded'];
                return back()->withNotify($notify);
            }
        }
        if ($path == 'tempProduct') {
            $product->type = 2;
        }

        $product->save();
    }

    public function detail($id)
    {
        $pageTitle = 'Product Detail';
        $data      = Product::where('user_id', auth()->id())->findOrFail($id);
        $path      = 'product';
        $oldImage  = $data->featured_image;
        if ($data->update_status == Status::PRODUCT_UPDATE_PENDING) {
            $data     = TempProduct::where('product_id', $id)->where('type', 2)->where('user_id', auth()->id())->with('category', 'subcategory')->first();
            $path     = 'tempProduct';
            $newImage = $data->featured_image;
        }
        $featuredPath  = @$newImage ? 'tempProduct' : 'product';
        $featuredImage = @$newImage ?? $oldImage;
        return view($this->activeTemplate . 'user.product.detail', compact('pageTitle', 'data', 'path', 'featuredImage', 'featuredPath'));
    }

    public function download($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            abort(404);
        }
        $product  = Product::where('user_id', auth()->id())->findOrFail($id);
        $file     = $product->file;
        $fullPath = getFilePath('productFile') . '/' . $file;
        $title    = str_replace(' ', '_', strtolower($product->name));
        $ext      = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($fullPath);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($fullPath);
    }

    public function comment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $product             = Product::available()->findOrFail($id);
        $comment             = new Comment();
        $comment->product_id = $product->id;
        $comment->user_id    = auth()->id();
        $comment->comment    = $request->comment;
        $comment->save();
        return view($this->activeTemplate . 'product.comment_card', compact('comment'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required',
        ]);

        $comment = Comment::findOrFail($id);

        $reply             = new Reply();
        $reply->comment_id = $comment->id;
        $reply->user_id    = auth()->id();
        $reply->reply      = $request->reply;
        $reply->save();

        $notify[] = ['success', 'Your reply added successfully'];
        return back()->withNotify($notify);
    }

    public function reviews($id)
    {
        $pageTitle = 'Product Reviews';
        $product   = Product::where('user_id', auth()->id())->findOrFail($id);
        $reviews   = $product->reviews()->with('user')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.product.reviews', compact('pageTitle', 'reviews', 'product'));
    }

    public function report(Request $request, $id, $productId)
    {
        $request->validate([
            'report_message' => 'required|string',
        ]);

        $product                = Product::where('user_id', auth()->id())->findOrFail($productId);
        $review                 = Review::where('product_id', $product->id)->findOrFail($id);
        $review->report_message = $request->report_message;
        $review->status         = Status::REVIEW_REPORTED;
        $review->save();

        $notify[] = ['success', 'Review reported successfully'];
        return back()->withNotify($notify);
    }
}
