<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller {

    public function index(Request $request) {
        $pageTitle     = 'All Subcategories';
        $subcategories = Subcategory::searchable(['name', 'category:name'])->with('category')->latest()->paginate(getPaginate());
        $allCategory   = Category::where('status', Status::ENABLE)->get();
        return view('admin.subcategory.index', compact('pageTitle', 'subcategories', 'allCategory'));
    }

    public function store(Request $request, $id = 0) {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name'        => 'required|string|max:40',
        ]);

        if ($id) {
            $subcategory         = Subcategory::findOrFail($id);
            $subcategory->status = $request->status ? 1 : 0;
            $notification        = 'Subcategory updated successfully';
        } else {
            $subcategory  = new Subcategory();
            $notification = 'Subcategory created successfully.';
        }

        $subcategory->name        = $request->name;
        $subcategory->category_id = $request->category_id;
        $subcategory->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}
