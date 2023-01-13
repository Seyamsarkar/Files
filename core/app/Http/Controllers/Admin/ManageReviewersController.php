<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Reviewer;
use App\Models\ReviewHistory;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManageReviewersController extends Controller
{

    public function add()
    {
        $pageTitle  = 'Add Reviewer';
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        return view('admin.reviewer.add', compact('pageTitle', 'countries', 'mobileCode'));
    }

    public function store(Request $request, $id = 0)
    {
        $countryData = $this->validation($request, $id);
        $general     = gs();

        if ($id) {
            $reviewer     = Reviewer::findOrFail($id);
            $notification = 'updated';
        } else {
            $reviewer           = new Reviewer();
            $reviewer->password = Hash::make($request->password);
            $reviewer->username = $request->username;

            if ($request->hasFile('image')) {
                try {
                    $reviewer->image = fileUploader($request->image, getFilePath('reviewer'), getFileSize('reviewer'));
                } catch (\Exception $exp) {
                    return back()->withNotify(['error', 'Could not upload the image.']);
                }
            }
            $notification = 'added';
            notify($reviewer, 'ADD_REVIEWER', [
                'username' => $reviewer->username,
                'password' => $request->password,
            ]);
        }
        $reviewer->firstname    = $request->firstname;
        $reviewer->email        = $request->email;
        $reviewer->lastname     = $request->lastname;
        $reviewer->mobile       = $countryData->dial_code . $request->mobile;
        $reviewer->country_code = $request->country;
        $reviewer->address      = [
            'address' => $request->address,
            'city'    => $request->city,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'country' => $countryData->country,
        ];

        $reviewer->ev = $general->reviewer_ev ? Status::VERIFIED : ($request->ev ? Status::VERIFIED : Status::UNVERIFIED);
        $reviewer->sv = $general->reviewer_sv ? Status::VERIFIED : ($request->ts ? Status::VERIFIED : Status::UNVERIFIED);
        $reviewer->ts = $request->ts ? Status::VERIFIED : Status::UNVERIFIED;
        $reviewer->tv = 1;
        $reviewer->save();


        $notify[] = ['success', 'Reviewer ' . $notification . ' successfully'];
        return to_route('admin.reviewers.all')->withNotify($notify);
    }

    private function validation($request, $id)
    {
        $countryData        = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray       = (array) $countryData;
        $countries          = implode(',', array_keys($countryArray));
        $requiredValidation = $id ? 'nullable' : 'required';
        $request->validate([
            'image'     => ['nullable', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'firstname' => 'sometimes|required|string|max:40',
            'lastname'  => 'sometimes|required|string|max:40',
            'email'     => 'required|string|email|max:40|unique:reviewers,email,' . $id,
            'mobile'    => 'required|string|max:40|unique:reviewers,mobile,' . $id,
            'password'  => "$requiredValidation|string|min:6|max:255|confirmed",
            'username'  => "$requiredValidation|alpha_num|unique:reviewers|min:6|max:40",
            'country'   => 'required|in:' . $countries,
        ]);

        $requestCountry = collect($countryData);
        $country        = $requestCountry[$request->country];
        return $country;
    }

    public function check(Request $request)
    {
        $exist['data'] = false;
        $exist['type'] = null;

        if ($request->email) {
            $exist['data'] = Reviewer::where('email', $request->email)->exists();
            $exist['type'] = 'email';
        }

        if ($request->mobile) {
            $exist['data'] = Reviewer::where('mobile', $request->mobile)->exists();
            $exist['type'] = 'mobile';
        }

        if ($request->username) {
            $exist['data'] = Reviewer::where('username', $request->username)->exists();
            $exist['type'] = 'username';
        }

        return response($exist);
    }

    public function all()
    {
        $pageTitle = 'All Reviewers';
        $reviewers = $this->reviewerData();
        return view('admin.reviewer.list', compact('pageTitle', 'reviewers'));
    }

    public function active()
    {
        $pageTitle = 'Active Reviewers';
        $reviewers = $this->reviewerData('active');
        return view('admin.reviewer.list', compact('pageTitle', 'reviewers'));
    }

    public function banned()
    {
        $pageTitle = 'Banned Reviewers';
        $reviewers = $this->reviewerData('banned');
        return view('admin.reviewer.list', compact('pageTitle', 'reviewers'));
    }

    public function emailUnverified()
    {
        $pageTitle = 'Email Unverified Reviewers';
        $reviewers = $this->reviewerData('emailUnverified');
        return view('admin.reviewer.list', compact('pageTitle', 'reviewers'));
    }

    public function mobileUnverified()
    {
        $pageTitle = 'Mobile Unverified Reviewers';
        $reviewers = $this->reviewerData('mobileUnverified');
        return view('admin.reviewer.list', compact('pageTitle', 'reviewers'));
    }

    protected function reviewerData($scope = null)
    {

        $reviewers = Reviewer::query();
        if ($scope) {
            $reviewers = Reviewer::$scope();
        }

        return $reviewers->searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function detail($id)
    {
        $reviewer  = Reviewer::withCount(['totalReviewed', 'totalApproved', 'totalSoftReject', 'totalHardReject'])->findOrFail($id);
        $pageTitle = 'Reviewer Detail - ' . $reviewer->username;
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $widget['total_approved'] = ReviewHistory::get();
        return view('admin.reviewer.detail', compact('pageTitle', 'reviewer', 'countries'));
    }

    public function status(Request $request, $id)
    {

        $reviewer = Reviewer::findOrFail($id);

        if ($reviewer->status == Status::REVIEWER_ACTIVE) {
            $request->validate([
                'reason' => 'required|string|max:255',
            ]);
            $reviewer->status     = Status::REVIEWER_BAN;
            $reviewer->ban_reason = $request->reason;
            $notify[]             = ['success', 'Reviewer banned successfully'];
        } else {
            $reviewer->status     = Status::REVIEWER_ACTIVE;
            $reviewer->ban_reason = null;
            $notify[]             = ['success', 'Reviewer unbanned successfully'];
        }

        $reviewer->save();
        return back()->withNotify($notify);
    }

    public function login($id)
    {
        Auth::guard('reviewer')->loginUsingId($id);
        return to_route('reviewer.home');
    }

    public function showNotificationSingleForm($id)
    {
        $reviewer = Reviewer::findOrFail($id);
        $general  = gs();

        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.users.detail', $reviewer->id)->withNotify($notify);
        }

        $pageTitle = 'Send Notification to ' . $reviewer->username;
        return view('admin.reviewer.notification_single', compact('pageTitle', 'reviewer'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
        ]);

        $reviewer = Reviewer::findOrFail($id);
        notify($reviewer, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm()
    {
        $general = gs();

        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $reviewers = Reviewer::active()->count();
        $pageTitle = 'Notification to Verified Reviewers';
        return view('admin.reviewer.notification_all', compact('pageTitle', 'reviewers'));
    }

    public function sendNotificationAll(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $reviewer = Reviewer::active()->skip($request->skip)->first();

        notify($reviewer, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json([
            'success'    => 'message sent',
            'total_sent' => $request->skip + 1,
        ]);
    }

    public function products($id, $scope = null)
    {
        $reviewer = Reviewer::findOrFail($id);
        if ($scope) {
            $products  = Product::$scope();
            $pageTitle = 'Total ' . $scope . ' Products';
        } else {
            $products  = Product::query();
            $pageTitle = 'Total Reviewed Products';
        }
        $products = $products->where('reviewer_id', $reviewer->id)->searchable(['name', 'category:name', 'subcategory:name'])->latest()->with(['category', 'subcategory'])->paginate(getPaginate());
        return view('admin.product.index', compact('pageTitle', 'products'));
    }
}
