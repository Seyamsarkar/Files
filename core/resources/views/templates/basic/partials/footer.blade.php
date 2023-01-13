@php
    $totalSold = App\Models\Sell::where('status', Status::SELL_APPROVED)->count();
    $totalEarning = App\Models\Sell::where('status', Status::SELL_APPROVED)->sum('product_price');
    $socialIcons = getContent('social_icon.element', false, null, true);
    $policyPages = getContent('policy_pages.element', false, null, true);
@endphp

<footer class="footer-section">
    <div class="overlay-shape"><img src="{{ getImage($activeTemplateTrue . 'images/footer.png') }}" alt="@lang('image')"></div>
    <div class="footer-top">
        <div class="container">
            <div class="footer-top-info-wrapper">
                <div class="row mb-30 align-items-center">
                    <div class="col-lg-2 mb-lg-0 text-lg-left header mb-5 text-center">
                        <a class="footer-logo site-logo" href="{{ route('home') }}"><img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('site-logo')"></a>
                    </div>
                    <div class="col-lg-7 col-md-7">
                        <div class="row justify-content-center gy-4">
                            <div class="col-xl-4 col-sm-6 footer-overview-item text-center">
                                <h3 class="amount-number text-white">{{ getAmount($totalSold) }}</h3>
                                <p class="text-white">@lang('Total Products Sold')</p>
                            </div>
                            <div class="col-xl-4 col-sm-6 footer-overview-item text-center">
                                <h3 class="amount-number text-white">{{ $general->cur_sym }}{{ showAmount($totalEarning) }}</h3>
                                <p class="text-white">@lang('Total Earnings')</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5 mt-md-0 mt-4">
                        <div class="text-md-end text-center">
                            <a class="btn btn--base" href="{{ route('user.register') }}">@lang('Join Now')</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-5 justify-content-between">
                @foreach ($categories->take(5) as $category)
                    <div class="col-lg-2 col-md-3 col-6">
                        <div class="footer-widget">
                            <h4 class="footer-widget__title">{{ __($category->name) }}</h4>
                            <ul class="short-link-list">
                                @foreach ($category->subcategories->take(4) as $key => $subcategory)
                                    <li><a href="{{ route('subcategory.products', [$subcategory->id, slug($subcategory->name)]) }}">{{ __($subcategory->name) }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
                <div class="col-lg-2 col-md-3 col-6">
                    <div class="footer-widget">
                        <h3 class="footer-widget__title">@lang('Company Policy')</h3>
                        <ul class="short-link-list">
                            @foreach ($policyPages as $policy)
                                <li><a href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}" target="_blank">{{ __(@$policy->data_values->title) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-6 text-md-start text-center">
                    <p>@lang('Copyright') &copy; @php echo date('Y') @endphp. @lang('All Right Reserved')</p>
                </div>
                <div class="col-lg-4 col-md-6 mt-md-0 mt-3">
                    <ul class="link-list justify-content-md-end justify-content-center">
                        @foreach ($socialIcons as $social)
                            <li>
                                <a href="{{ @$social->data_values->url }}" target="_blank">
                                    @php
                                        echo @$social->data_values->social_icon;
                                    @endphp
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
