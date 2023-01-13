<?php

use App\Models\GeneralSetting;
use App\Models\Sell;
use App\Models\User;
use Faker\Guesser\Name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::namespace('Api')->name('api.')->group(function () {

    Route::middleware('verify.purchase.code.api')->post('verify-purchase-code', function (Request $request) {
        $ip     = $request->ip();
        $status = 'error';
        $purchase_info = [];
        $notify = '';

        $user   = User::where('username', $request->app_secret)->where('api_key', $request->api_key)->with('ips')->first();

        if (!$user) {
            $notify = 'Invalid api key or app secret';
            return response()->json([
                'status'  => $status,
                'message' => $notify,
                'info' =>  $purchase_info,
            ]);
        }
        $whiteListedIp = $user->ips->where('ip', $ip)->first();
        if (!$whiteListedIp) {
            $notify = 'Please whitelist your IP first';
            return response()->json([
                'status'  => $status,
                'message' => $notify,
                'info' =>  $purchase_info,
            ]);
        }
        $sale = Sell::approved()->where('author_id', $user->id)->where('code', $request->purchase_code)->with(['product', 'user'])->first();
        if (!$sale) {
            $notify = 'Invalid purchased code.';
            return response()->json([
                'status'  => $status,
                'message' => $notify,
                'info' =>  $purchase_info,
            ]);
        }
        $status = 'success';
        $notify = 'Purchase information found';
        $purchase_info['name'] = $sale->product->name;
        $purchase_info['code'] = $sale->code;
        $purchase_info['license'] = $sale->license == 1 ? 'Regular' : 'Extended';
        $purchase_info['has_support'] = $sale->support == 1 ? 'Yes' : 'No';
        $purchase_info['sold_at'] = $sale->created_at;
        if ($sale->support) $purchase_info['support_time'] = $sale->support_time;
        if ($sale->user) {
            $purchase_info['buyer']['name'] = $sale->user->fullname;
            $purchase_info['buyer']['email '] = $sale->user->email;
            $purchase_info['buyer']['mobile '] = $sale->user->mobile;
        }
        return response()->json([
            'status'  => $status,
            'message' => $notify,
            'info' =>  $purchase_info,
        ]);
    })->name('verify.purchase.code');
    Route::get('general-setting', function () {
        $general  = gs();
        $notify[] = 'General setting data';
        return response()->json([
            'remark'  => 'general_setting',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'general_setting' => $general,
            ],
        ]);
    });

    Route::get('get-countries', function () {
        $c        = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $notify[] = 'General setting data';
        foreach ($c as $k => $country) {
            $countries[] = [
                'country'      => $country->country,
                'dial_code'    => $country->dial_code,
                'country_code' => $k,
            ];
        }
        return response()->json([
            'remark'  => 'country_data',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'countries' => $countries,
            ],
        ]);
    });

    Route::namespace('Auth')->group(function () {
        Route::post('login', 'LoginController@login');
        Route::post('register', 'RegisterController@register');

        Route::controller('ForgotPasswordController')->group(function () {
            Route::post('password/email', 'sendResetCodeEmail')->name('password.email');
            Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
            Route::post('password/reset', 'reset')->name('password.update');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {

        //authorization
        Route::controller('AuthorizationController')->group(function () {
            Route::get('authorization', 'authorization')->name('authorization');
            Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
            Route::post('verify-email', 'emailVerification')->name('verify.email');
            Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
            Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
        });

        Route::middleware(['check.status'])->group(function () {
            Route::post('user-data-submit', 'UserController@userDataSubmit')->name('data.submit');

            Route::middleware('registration.complete')->group(function () {
                Route::get('dashboard', function () {
                    return auth()->user();
                });

                Route::get('user-info', function () {
                    $notify[] = 'User information';
                    return response()->json([
                        'remark'  => 'user_info',
                        'status'  => 'success',
                        'message' => ['success' => $notify],
                        'data'    => [
                            'user' => auth()->user(),
                        ],
                    ]);
                });

                Route::controller('UserController')->group(function () {

                    //KYC
                    Route::get('kyc-form', 'kycForm')->name('kyc.form');
                    Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                    //Report
                    Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                    Route::get('transactions', 'transactions')->name('transactions');
                });

                //Profile setting
                Route::controller('UserController')->group(function () {
                    Route::post('profile-setting', 'submitProfile');
                    Route::post('change-password', 'submitPassword');
                });

                // Withdraw
                Route::controller('WithdrawController')->group(function () {
                    Route::get('withdraw-method', 'withdrawMethod')->name('withdraw.method')->middleware('kyc');
                    Route::post('withdraw-request', 'withdrawStore')->name('withdraw.money')->middleware('kyc');
                    Route::post('withdraw-request/confirm', 'withdrawSubmit')->name('withdraw.submit')->middleware('kyc');
                    Route::get('withdraw/history', 'withdrawLog')->name('withdraw.history');
                });

                // Payment
                Route::controller('PaymentController')->group(function () {
                    Route::get('deposit/methods', 'methods')->name('deposit');
                    Route::post('deposit/insert', 'depositInsert')->name('deposit.insert');
                    Route::get('deposit/confirm', 'depositConfirm')->name('deposit.confirm');
                    Route::get('deposit/manual', 'manualDepositConfirm')->name('deposit.manual.confirm');
                    Route::post('deposit/manual', 'manualDepositUpdate')->name('deposit.manual.update');
                });
            });
        });

        Route::get('logout', 'Auth\LoginController@logout');
    });
});
