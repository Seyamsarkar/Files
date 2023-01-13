<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class LevelController extends Controller {

    public function index(Request $request) {
        $pageTitle = 'All levels';
        $levels    = Level::searchable(['name'])->latest()->paginate(getPaginate());
        return view('admin.level.index', compact('pageTitle', 'levels'));
    }

    public function store(Request $request, $id = 0) {
        $imageValidate = $id ? 'nullable' : 'required';
        $request->validate([
            'name'           => 'required|string|max:40',
            'earning'        => 'required|numeric|gte:0',
            'product_charge' => 'required|numeric|gte:0',
            'image'          => [$imageValidate, new FileTypeValidate(['jpeg', 'jpg', 'png'])],
        ]);

        if ($id) {
            $level        = Level::findOrFail($id);
            $notification = 'Level updated successfully';
            $oldImage     = $level->image;
        } else {
            $level        = new Level();
            $notification = 'Level created successfully';
            $oldImage     = null;
        }

        if ($request->hasFile('image')) {
            try {
                $level->image = fileUploader($request->image, getFilePath('level'), getFileSize('level'), $oldImage);
            } catch (\Exception$exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $level->name           = $request->name;
        $level->earning        = $request->earning;
        $level->product_charge = $request->product_charge;
        $level->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

}
