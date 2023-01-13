<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $pageTitle   = 'All Categories';
        $allCategory = Category::searchable(['name'])->latest()->paginate(getPaginate());
        return view('admin.category.index', compact('pageTitle', 'allCategory'));
    }

    public function store(Request $request, $id = 0)
    {
        $imageValidate = $id ? 'nullable' : 'required';
        $validate      = [
            'name'      => 'required|max: 40|unique:categories,name,' . $id,
            'buyer_fee' => 'required|numeric|gte:0',
            'image'     => [$imageValidate, new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ];

        $request->validate($validate);

        if ($id == 0) {
            $category     = new Category();
            $notification = 'Category added successfully.';
        } else {
            $category         = Category::findOrFail($id);
            $category->status = $request->status == 'on' ? 1 : 0;
            $notification     = 'Category updated successfully';
        }

        if ($request->hasFile('image')) {
            $oldImage = $category->image;
            try {
                $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), $oldImage);
            } catch (\Exception $e) {
                $notify[] = ['error', 'Image could not be uploaded'];
                return back()->withNotify($notify);
            }
        }

        $category->name      = $request->name;
        $category->buyer_fee = $request->buyer_fee;
        $category->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function featured($id)
    {
        $category           = Category::findOrFail($id);
        $category->featured = !$category->featured;
        $category->save();

        $message = $category->featured == 1 ? 'featured' : 'unfeatured';

        $notify[] = ['success', 'Category ' . $message . ' successfully'];
        return back()->withNotify($notify);
    }
}
