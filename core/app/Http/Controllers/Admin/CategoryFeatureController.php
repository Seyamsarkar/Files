<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryFeature;
use Illuminate\Http\Request;

class CategoryFeatureController extends Controller
{

    public function index(Request $request)
    {
        $pageTitle   = 'Category Features';
        $features    = CategoryFeature::searchable(['name', 'category:name'])->with('category')->latest()->paginate(getPaginate());
        $allCategory = Category::where('status', Status::ENABLE)->get();
        return view('admin.category.features', compact('pageTitle', 'features', 'allCategory'));
    }

    public function store(Request $request, $id = 0)
    {
        $this->validate($request, [
            'category_id' => 'required|integer|exists:categories,id',
            'name'        => 'required|string|max:255',
            'type'        => 'required|integer|in:1,2',
            'options'     => 'array',
            'options.*'   => 'required|string|max:255',
        ], [
            'options.*.required' => 'All option is required',
            'options.*.string'   => 'All option must be string type',
            'options.*.required' => 'All option must be max 255 character',
        ]);

        if ($id) {
            $feature         = CategoryFeature::findOrFail($id);
            $notification    = 'Feature updated successfully';
            $feature->status = $request->status ? 1 : 0;
        } else {
            $feature      = new CategoryFeature();
            $notification = 'Feature created successfully';
        }

        $feature->category_id = $request->category_id;
        $feature->name        = $request->name;
        $feature->type        = $request->type;
        $feature->options     = array_values($request->options);
        $feature->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}
