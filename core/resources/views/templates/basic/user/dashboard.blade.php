@php
    $kycContent = getContent('user_kyc.content', true);
@endphp
@extends($activeTemplate . 'layouts.master')
@section('content')
    @if ($user->kv == Status::KYC_UNVERIFIED)
        <div class="alert alert-danger" role="alert">
            <h5 class="alert-heading">@lang('KYC Verification required')</h5>
            <hr class="my-2">
            <p class="mb-0">{{ __($kycContent->data_values->verification_content) }} <a class="text--danger" href="{{ route('user.kyc.form') }}">@lang('Click Here to Verify')</a></p>
        </div>
    @elseif($user->kv == Status::KYC_PENDING)
        <div class="alert alert-warning" role="alert">
            <h5 class="alert-heading">@lang('KYC Verification pending')</h5>
            <hr class="my-2">
            <p class="mb-0">{{ __($kycContent->data_values->pending_content) }} <a class="text--warning" href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-8">
            <div class="row gy-4">
                <div class="col-xl-4 col-sm-6">
                    <div class="d-widget d-flex align-items-center rounded-3 flex-wrap">
                        <div class="d-widget__content">
                            <h3 class="d-number">{{ $general->cur_sym }}{{ showAmount($user->balance) }}</h3>
                            <span class="caption">@lang('Balance')</span>
                        </div>
                        <div class="d-widget__icon rounded">
                            <i class="las la-money-bill text--base"></i>
                            <a class="btn btn-sm btn--base py-1 text-center" href="{{ route('user.transactions') }}">@lang('View all')</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="d-widget d-flex align-items-center rounded-3 flex-wrap">
                        <div class="d-widget__content">
                            <h3 class="d-number">{{ $general->cur_sym }}{{ showAmount($user->earning) }}</h3>
                            <span class="caption">@lang('Earning')</span>
                        </div>
                        <div class="d-widget__icon rounded">
                            <i class="las la-money-bill text--base"></i>
                            <a class="btn btn-sm btn--base py-1 text-center" href="{{ route('user.transactions') }}">@lang('View all')</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="d-widget d-flex align-items-center rounded-3 flex-wrap">
                        <div class="d-widget__content">
                            <h3 class="d-number">{{ $data['total_product'] }}</h3>
                            <span class="caption">@lang('Your Products')</span>
                        </div>
                        <div class="d-widget__icon rounded">
                            <i class="las la-upload text--base"></i>
                            <a class="btn btn-sm btn--base py-1 text-center" href="{{ route('user.product.index') }}">@lang('View all')</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="d-widget d-flex align-items-center rounded-3 flex-wrap">
                        <div class="d-widget__content">
                            <h3 class="d-number">{{ $data['total_purchased'] }}</h3>
                            <span class="caption">@lang('Purchased Product')</span>
                        </div>
                        <div class="d-widget__icon rounded">
                            <i class="las la-cart-arrow-down text--base"></i>
                            <a class="btn btn-sm btn--base py-1 text-center" href="{{ route('user.purchased.history') }}">@lang('View all')</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="d-widget d-flex align-items-center rounded-3 flex-wrap">
                        <div class="d-widget__content">
                            <h3 class="d-number">{{ $data['total_transaction'] }}</h3>
                            <span class="caption">@lang('Transaction')</span>
                        </div>
                        <div class="d-widget__icon rounded">
                            <i class="las la-exchange-alt text--base"></i>
                            <a class="btn btn-sm btn--base py-1 text-center" href="{{ route('user.transactions') }}">@lang('View all')</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="d-widget d-flex align-items-center rounded-3 flex-wrap">
                        <div class="d-widget__content">
                            <h3 class="d-number">{{ $data['total_sell'] }}</h3>
                            <span class="caption">@lang('Total Sell')</span>
                        </div>
                        <div class="d-widget__icon rounded">
                            <i class="las la-wallet text--base"></i>
                            <a class="btn btn-sm btn--base py-1 text-center" href="{{ route('user.sell.history') }}">@lang('View all')</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-sm-6">
                    <div class="d-widget d-flex align-items-center rounded-3 flex-wrap">
                        <div class="d-widget__content">
                            <h3 class="d-number">{{ $general->cur_sym }}{{ showAmount($data['total_deposited']) }}</h3>
                            <span class="caption">@lang('Total Deposited')</span>
                        </div>
                        <div class="d-widget__icon rounded">
                            <i class="las la-money-bill-wave text--base"></i>
                            <a class="btn btn-sm btn--base py-1 text-center" href="{{ route('user.deposit.history') }}">@lang('View all')</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-sm-6">
                    <div class="d-widget d-flex align-items-center rounded-3 flex-wrap">
                        <div class="d-widget__content">
                            <h3 class="d-number">{{ $general->cur_sym }}{{ showAmount($data['total_withdrawn']) }}</h3>
                            <span class="caption">@lang('Total Withdrawan')</span>
                        </div>
                        <div class="d-widget__icon rounded">
                            <i class="las la-money-check-alt text--base"></i>
                            <a class="btn btn-sm btn--base py-1 text-center" href="{{ route('user.withdraw.history') }}">@lang('View all')</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-md-12 col-sm-12 mt-5">
                <h5>@lang('Sell History')</h5>
                <div id="chartprofile" width="400" height="150"></div>
            </div>
        </div>
        <div class="col-lg-4 ps-lg-4 mt-lg-0 mt-5">
            <div class="user-sidebar">
                <div class="user-widget">
                    <h4 class="user-widget__title">@lang('Your Balance')</h4>
                    <p>@lang('You Have') <b class="bg--base px-2 py-1 text-white">{{ showAmount($user->balance) }} {{ $general->cur_text }}</b> @lang('in your Account')</p>
                </div>
                <div class="user-widget">
                    <h4 class="user-widget__title">@lang('This Month\'s Stats')</h4>
                    <ul class="caption-list">
                        <li>
                            <span class="caption">@lang('Released Products')</span>
                            <span class="value">{{ $data['monthly_released'] }}</span>
                        </li>
                        <li>
                            <span class="caption">@lang('Purchased Products')</span>
                            <span class="value">{{ $data['monthly_purchased'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            @if (@$user->level)
                <div class="product-widget border-1 mt-5">
                    <div class="author-widget">
                        <div class="thumb">
                            <img src="{{ getImage(getFilePath('level') . '/' . @$user->level->image, getFileSize('level')) }}" alt="@lang('image')">
                        </div>
                        <div class="content">
                            <h5 class="author-name">{{ @$user->level->name }}</h5>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/chart.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var color = "{{ $general->base_color }}";

            function generateData(baseval, count, yrange) {
                var i = 0;
                var series = [];
                while (i < count) {
                    var x = Math.floor(Math.random() * (750 - 1 + 1)) + 1;;
                    var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
                    var z = Math.floor(Math.random() * (75 - 15 + 1)) + 15;

                    series.push([x, y, z]);
                    baseval += 86400000;
                    i++;
                }
                return series;
            }

            var curText = "{{ $general->cur_text }}";
            var options = {
                series: [{
                    name: `Sell Amount in ${curText}`,
                    data: [
                        @foreach ($sellArr as $sell)
                            @json($sell['product_price']),
                        @endforeach
                    ]
                }],
                chart: {
                    height: 360,
                    type: 'line',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ["#" + color],
                stroke: {
                    curve: 'straight',
                    width: [1]
                },
                markers: {
                    size: 5,
                    colors: ["#" + color],
                    strokeColors: "#" + color,
                    strokeWidth: 1,
                    hover: {
                        size: 6,
                    }
                },
                grid: {
                    position: 'front',
                    borderColor: '#ddd',
                    strokeDashArray: 5,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
                xaxis: {
                    categories: [
                        @foreach ($sellArr as $sell)
                            @json($sell['month']),
                        @endforeach
                    ],
                    lines: {
                        show: false,
                    }
                },
                yaxis: {
                    lines: {
                        show: false,
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#chartprofile"), options);
            chart.render();
        })(jQuery)
    </script>
@endpush
