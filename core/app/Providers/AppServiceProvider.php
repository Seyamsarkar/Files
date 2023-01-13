<?php

namespace App\Providers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Product;
use App\Models\Review;
use App\Models\Reviewer;
use App\Models\Sell;
use App\Models\SupportTicket;
use App\Models\TempProduct;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $general                         = gs();
        $activeTemplate                  = activeTemplate();
        $viewShare['general']            = $general;
        $viewShare['activeTemplate']     = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        $viewShare['language']           = Language::all();
        $viewShare['emptyMessage']       = 'Data not found';
        $viewShare['emptyMsgImage']      = getContent('empty_message.content', true);
        $viewShare['categories']         = Category::active()->with(['subcategories', 'categoryFeature'])->withCount('subcategories')->orderBy('subcategories_count', 'desc')->get();
        view()->share($viewShare);

        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount'               => User::banned()->count(),
                'emailUnverifiedUsersCount'      => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount'     => User::mobileUnverified()->count(),
                'kycUnverifiedUsersCount'        => User::kycUnverified()->count(),
                'kycPendingUsersCount'           => User::kycPending()->count(),
                'pendingTicketCount'             => SupportTicket::whereIN('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
                'pendingDepositsCount'           => Deposit::pending()->count(),
                'pendingPaymentsCount'           => Sell::pending()->count(),
                'pendingWithdrawCount'           => Withdrawal::pending()->count(),
                'bannedReviewersCount'           => Reviewer::banned()->count(),
                'emailUnverifiedReviewersCount'  => Reviewer::emailUnverified()->count(),
                'mobileUnverifiedReviewersCount' => Reviewer::mobileUnverified()->count(),
                'pendingProductCount'            => Product::pending()->count(),
                'softProductCount'               => Product::softRejected()->count(),
                'hardProductCount'               => Product::hardRejected()->count(),
                'updatePendingProductCount'      => TempProduct::where('type', 2)->count(),
                'resubmitProductCount'           => TempProduct::where('type', 1)->count(),
                'reportedReviewCount'            => Review::where('status', 2)->count(),
            ]);
        });

        view()->composer('reviewer.partials.sidenav', function ($view) {
            $view->with([
                'pendingProductCount'       => Product::pending()->count(),
                'softProductCount'          => Product::softRejected()->count(),
                'hardProductCount'          => Product::hardRejected()->count(),
                'updatePendingProductCount' => TempProduct::where('type', 2)->count(),
                'resubmitProductCount'      => Product::resubmitted()->count(),
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications'     => AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', Status::NO)->count(),
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        if ($general->force_ssl) {
            \URL::forceScheme('https');
        }

        Paginator::useBootstrapFour();
    }
}
