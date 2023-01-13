<?php

namespace App\Http\Controllers\Reviewer;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\GoogleAuthenticator;
use App\Models\Product;
use App\Models\TempProduct;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ReviewerController extends Controller
{

    public function home()
    {
        $pageTitle               = 'Reviewer Dashboard';
        $widget['pending']       = Product::pending()->count();
        $widget['approved']      = Product::approved()->count();
        $widget['softRejected']  = Product::softRejected()->count();
        $widget['hardRejected']  = Product::hardRejected()->count();
        $widget['updatePending'] = TempProduct::where('type', Status::TEMP_PRODUCT_UPDATE)->count();
        $widget['resubmitted']   = TempProduct::where('type', Status::TEMP_PRODUCT_RESUBMIT)->count();

        $pendingProducts       = Product::where('status', Status::PRODUCT_PENDING)->latest()->with(['category'])->latest()->limit(5)->get();
        $updateProducts = TempProduct::where('type', Status::TEMP_PRODUCT_UPDATE)->with('product:id,update_status', 'category')->latest()->limit(5)->get();

        return view('reviewer.dashboard', compact('pageTitle', 'widget', 'pendingProducts', 'updateProducts'));
    }

    public function profile()
    {
        $pageTitle = 'Profile Setting';
        $reviewer  = auth()->guard('reviewer')->user();
        return view('reviewer.profile', compact('pageTitle', 'reviewer'));
    }

    public function profileUpdate(Request $request)
    {

        $this->validate($request, [
            'image'     => ['nullable', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'firstname' => 'required|string|max:40',
            'lastname'  => 'required|string|max:40',
            'address'   => "sometimes|required",
            'state'     => 'sometimes|required',
            'zip'       => 'sometimes|required',
            'city'      => 'sometimes|required',
        ], [
            'firstname.required' => 'First Name Field is required',
            'lastname.required'  => 'Last Name Field is required',
        ]);

        $in['firstname'] = $request->firstname;
        $in['lastname']  = $request->lastname;

        $in['address'] = [
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'country' => $request->country,
            'city'    => $request->city,
        ];

        $reviewer = auth()->guard('reviewer')->user();

        if ($request->hasFile('image')) {
            try {
                $reviewerImage = fileUploader($request->image, getFilePath('reviewer'), getFileSize('reviewer'), $reviewer->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }

            $in['image'] = $reviewerImage;
        }

        $reviewer->fill($in)->save();

        $notify[] = ['success', 'profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $reviewer  = auth()->guard('reviewer')->user();
        return view('reviewer.password', compact('pageTitle', 'reviewer'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password'     => 'required|min:6|confirmed',
        ]);

        $reviewer = auth()->guard('reviewer')->user();

        if (!Hash::check($request->old_password, $reviewer->password)) {
            $notify[] = ['error', 'Password doesn\'t match!!'];
            return back()->withNotify($notify);
        }

        $reviewer->password = Hash::make($request->password);
        $reviewer->save();

        $notify[] = ['success', 'Password Changed Successfully.'];
        return back()->withNotify($notify);
    }

    public function show2faForm()
    {
        $general   = gs();
        $ga        = new GoogleAuthenticator();
        $reviewer  = auth()->guard('reviewer')->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($reviewer->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view('reviewer.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {

        $this->validate($request, [
            'key'  => 'required',
            'code' => 'required',
        ]);
        $reviewer = auth()->guard('reviewer')->user();
        $response = verifyG2fa($reviewer, $request->code, $request->key);

        if ($response) {
            $reviewer->tsc = $request->key;
            $reviewer->ts  = 1;
            $reviewer->save();

            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $reviewer = auth()->guard('reviewer')->user();
        $response = verifyG2fa($reviewer, $request->code);

        if ($response) {
            $reviewer->tsc = null;
            $reviewer->ts  = 0;
            $reviewer->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }

        return back()->withNotify($notify);
    }
}
