@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card custom--card">
                <div class="card-body p-0">
                    <div class="user-profile-area">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="text-bold">@lang('Product Image') </span>
                                <div class="user-profile-header p-0">
                                    <div class="profile-thumb product-profile-thumb">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview productPicPreview" style="background-image: url({{ getImage(getFilePath($featuredPath) . '/' . @$featuredImage, getFileSize($featuredPath)) }})"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                        <span class="text-bold">@lang('Name')</span>
                                        <span>{{ __(@$data->name) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                        <span class="text-bold">@lang('Category')</span>
                                        <span>{{ __(@$data->category->name) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                        <span class="text-bold">@lang('Subcategory')</span>
                                        <span>{{ __(@$data->subcategory->name) ?? 'N/A' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                        <span class="text-bold">@lang('Regular Price')</span>
                                        <span>{{ showAmount(@$data->regular_price) }} {{ $general->cur_text }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                        <span class="text-bold">@lang('Extended Price')</span>
                                        <span>{{ showAmount(@$data->extended_price) }} {{ $general->cur_text }}</span>
                                    </li>
                                    @if (@$data->support)
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                            <span class="text-bold">@lang('Support Charge')</span>
                                            <span>{{ showAmount(@$data->support_charge) }} {{ $general->cur_text }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                            <span class="text-bold">@lang('Support Discount')</span>
                                            <span>{{ showAmount(@$data->support_discount) }} {{ $general->cur_text }}</span>
                                        </li>
                                    @endif
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                        <span class="text-bold">@lang('Status')</span>
                                        <span>
                                            @php
                                                echo $data->statusBadge;
                                            @endphp
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                        <span class="text-bold">@lang('Demo Link')</span>
                                        <a href="{{ @$data->demo_link }}" target="_blank">{{ @$data->demo_link }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                        <span class="text-bold">@lang('Tags')</span>
                                        <div>
                                            @if (@$data->tag)
                                                @foreach (@$data->tag as $item)
                                                    <span class="badge badge--dark">{{ __($item) }}</span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                        <span class="text-bold">@lang('Message To Reviewer')</span>
                                        <span>{{ __(@$data->message) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                        <span class="text-bold">@lang('Screenshots')</span>
                                        <span>
                                            @if (@$data->screenshots)
                                                @foreach (@$data->screenshots as $screenshot)
                                                    @if ($loop->first)
                                                        <a class="text--base" data-rel="lightcase:myCollection:slideshow" href="{{ getImage(getFilePath($path) . '/' . $screenshot) }}"><i class="las la-image fs-5 me-2"></i> @lang('Screenshots')</a>
                                                    @endif
                                                    @if (!$loop->first)
                                                        <a data-rel="lightcase:myCollection:slideshow" href="{{ getImage(getFilePath($path) . '/' . $screenshot) }}"></a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </span>
                                    </li>
                                    @foreach (@$data->category_details as $key => $categoryDetail)
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-0">
                                            <span class="text-bold">{{ inputTitle($key) }}</span>
                                            <div>
                                                @if (count($categoryDetail) > 1)
                                                    @foreach ($categoryDetail as $detail)
                                                        <span class="badge badge--dark">{{ str_replace('_', ' ', @$detail) }}</span>
                                                    @endforeach
                                                @endif

                                                @if (count($categoryDetail) == 1)
                                                    <span class="badge badge--dark">{{ str_replace('_', ' ', @$categoryDetail[0]) }}</span>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-12 mt-4">
                                <h6 class="text-bold mb-2">@lang('HTML Description')</h6>
                                @php echo @$data->description; @endphp
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-outline--base btn-sm" href="{{ route('user.product.download', Crypt::encrypt($data->id)) }}"><i class="las la-download"></i> @lang('Download')</a>
    <a class="btn btn-outline--base btn-sm" href="{{ route('user.product.index') }}"><i class="las la-undo"></i> @lang('Back')</a>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/global/css/lightcase.css') }}" rel="stylesheet">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/global/js/lightcase.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            $("a[data-rel^=lightcase]").lightcase();
        })(jQuery)
    </script>
@endpush
