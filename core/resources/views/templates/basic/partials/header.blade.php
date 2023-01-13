@php
    $pages = App\Models\Page::where('tempname', $activeTemplate)
        ->where('is_default', Status::NO)
        ->get();
    if (auth()->user()) {
        $ordersCount = auth()
            ->user()
            ->myCart->count();
    } else {
        $ordersCount = App\Models\Cart::where('order_number', session()->get('order_number'))->count();
    }
@endphp
<div class="header__top">
    <div class="container">
        <div class="row gy-2 align-items-center">
            <div class="col-lg-6">
                <ul class="header-menu-list justify-content-lg-start justify-content-center">
                    <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                    @foreach ($pages as $k => $data)
                        <li>
                            <a href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a>
                        </li>
                    @endforeach
                    <li><a href="{{ route('products') }}">@lang('Products')</a></li>
                    <li><a href="{{ route('blogs') }}">@lang('Blogs')</a></li>
                    <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>
                </ul>
            </div>
            <div class="col-lg-6 text-md-end">
                <div class="d-flex align-items-center justify-content-lg-end justify-content-center gap-xl-0 flex-wrap gap-2">
                    <a class="menu-cart-btn me-3" href="{{ route('my.cart') }}">
                        <i class="las la-cart-arrow-down"></i>
                        <span class="cart-badge">
                            {{ $ordersCount }}
                        </span>
                    </a>

                    @auth
                        <button class="menu-cart-btn me-3" type="button">
                            <span class="cart-badge">
                                {{ $general->cur_sym }}{{ showAmount(auth()->user()->balance) }}
                            </span>
                        </button>
                    @endauth
                    <ul class="header-menu-list me-3">
                        @auth
                            <li>
                                <div class="dropdown mb-1">
                                    <button class="btn btn-sm btn--base dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" type="button" aria-expanded="false">
                                        @lang('My Account')
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="{{ route('user.home') }}">@lang('Dashboard')</a></li>
                                        <li><a class="dropdown-item" href="{{ route('user.profile.setting') }}">@lang('Profile Settings')</a></li>
                                        <li><a class="dropdown-item" href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
                                        <li><a class="dropdown-item" href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
                                        <li><a class="dropdown-item" href="{{ route('user.logout') }}">@lang('Logout')</a></li>
                                    </ul>
                                </div>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('user.login') }}"><i class="las la-user"></i> @lang('Login')</a>
                            </li>
                            <li>
                                <a href="{{ route('user.register') }}"><i class="las la-user-plus"></i> @lang('Register')</a>
                            </li>
                        @endauth
                    </ul>
                    @if ($general->multi_language)
                        <select class="laguage-select langSel" name="site-language">
                            @foreach ($language as $item)
                                <option value="{{ __($item->code) }}" @if (session('lang') == $item->code) selected @endif>{{ __($item->name) }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<header class="header">
    <div class="header__bottom">
        <div class="container">
            <nav class="navbar navbar-expand-lg align-items-center p-0">
                <a class="site-logo site-title" href="{{ route('home') }}"><img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('site-logo')"></a>

                <button class="navbar-toggler header-button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>

                <div class="collapse navbar-collapse mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav main-menu ms-auto pb-0">
                        @foreach ($categories->take(6) as $key => $category)
                            <li class="menu_has_children">
                                <a class="category-name" href="{{ route('category.products', [$category->id, slug($category->name)]) }}">{{ __($category->name) }}</a>
                                @if (count($category->subcategories) > 0)
                                    <ul class="sub-menu">
                                        @foreach ($category->subcategories->take(8) as $subcategory)
                                            <li><a href="{{ route('subcategory.products', [$subcategory->id, slug($subcategory->name)]) }}">{{ __($subcategory->name) }}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <div class="nav-right">
                        <button class="header-serch-btn toggle-close"><i class="fa fa-search"></i></button>
                        <div class="header-top-search-area">
                            <form class="header-search-form" method="GET" action="{{ route('products') }}">
                                <input id="header_search" name="search" type="search" placeholder="@lang('Search here')...">
                                <button class="header-search-btn" type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>

                </div>
            </nav>
        </div>
    </div>
</header>

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.category-name').on('click', function(e) {
                if ($(window).width() <= 991) {
                    e.preventDefault();
                }
            })

        })(jQuery)
    </script>
@endpush
