<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Reviewer\Auth')->name('reviewer.')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('/', 'showLoginForm')->name('login');
        Route::post('/', 'login')->name('login');
        Route::get('logout', 'logout')->name('logout');
    });

    // Reviewer Password Reset
    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('reset');
        Route::post('reset', 'sendResetCodeEmail');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'reset')->name('password.change');
    });
});

Route::middleware('reviewer')->name('reviewer.')->group(function () {

    Route::namespace('Reviewer')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
    });

    Route::middleware(['checkReviewerStatus'])->namespace('Reviewer')->group(function () {
        Route::controller('ReviewerController')->group(function () {
            Route::get('dashboard', 'home')->name('home');
            Route::get('profile', 'profile')->name('profile');
            Route::post('profile/update', 'profileUpdate')->name('profile.update');
            Route::get('password', 'password')->name('password');
            Route::post('password/update', 'passwordUpdate')->name('password.update');

            Route::get('twofactor', 'show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');
        });

        Route::controller('ProductController')->name('product.')->prefix('product')->group(function () {
            Route::get('pending', 'pending')->name('pending');
            Route::get('approved', 'approved')->name('approved');
            Route::get('soft-rejected', 'softRejected')->name('soft.rejected');
            Route::get('hard-rejected', 'hardRejected')->name('hard.rejected');
            Route::get('resubmitted', 'resubmitted')->name('resubmitted');
            Route::get('reviewed-by-me', 'reviewedByMe')->name('reviewed.by.me');
            Route::get('detail/{id}', 'detail')->name('detail');
            Route::get('download/{id}', 'download')->name('download');
            Route::post('approved/{id}', 'approveProduct')->name('approve');
            Route::post('soft-reject/{id}', 'softRejectProduct')->name('soft.reject');
            Route::post('hard-reject/product/{id}', 'hardRejectProduct')->name('hard.reject');
        });

        Route::controller('UpdateProductController')->name('update.product.')->prefix('update/product')->group(function () {
            Route::get('pending', 'pending')->name('pending');
            Route::get('detail/{id}', 'detail')->name('detail');
            Route::get('download/{id}', 'download')->name('download');
            Route::post('approve/{id}', 'approve')->name('approve');
            Route::post('reject/{id}', 'reject')->name('reject');
        });
    });
});
