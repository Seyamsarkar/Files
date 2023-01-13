@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="image-upload mt-2">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image:url({{ getImage(getFilePath('product') . '/' . $product->featured_image, getFileSize('product')) }})">
                                            <button class="remove-image" type="button"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="mb-3 text-end">
                                @if ($product->status != 3)
                                    <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to approved in this product?')" data-action="{{ route('admin.product.approve', $product->id) }}" @if ($product->status == Status::PRODUCT_APPROVE) disabled @endif><i class="las la-check-circle"></i> @lang('Approve') </button>

                                    <button class="btn btn-sm btn-outline--warning rejectBtn" data-title="@lang('Are you sure to soft reject in this product?')" data-action="{{ route('admin.product.soft.reject', $product->id) }}" @if ($product->status == Status::PRODUCT_SOFT_REJECT) disabled @endif><i class="las la-times-circle"></i> @lang('Soft Reject') </button>

                                    <button class="btn btn-sm btn-outline--danger rejectBtn" data-title="@lang('Are you sure to hard reject in this product?')" data-action="{{ route('admin.product.hard.reject', $product->id) }}"><i class="las la-ban"></i> @lang('Hard Reject') </button>
                                @endif

                            </div>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Name')</span>
                                    <span>{{ $product->name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Category')</span>
                                    <span>{{ $product->category->name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Subcategory')</span>
                                    <span>{{ $product->subcategory->name ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Regular Price')</span>
                                    <span>{{ showAmount($product->regular_price) }} {{ $general->cur_text }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Extended Price')</span>
                                    <span>{{ showAmount($product->extended_price) }} {{ $general->cur_text }}</span>
                                </li>
                                @if ($product->support)
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <span class="fw-bold">@lang('Support Charge')</span>
                                        <span>{{ showAmount($product->support_charge) }} {{ $general->cur_text }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <span class="fw-bold">@lang('Support Discount')</span>
                                        <span>{{ showAmount($product->support_discount) }} {{ $general->cur_text }}</span>
                                    </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Status')</span>
                                    <p>
                                        @php
                                            echo $product->statusBadge;
                                        @endphp
                                    </p>
                                </li>
                                @if ($product->status == Status::PRODUCT_SOFT_REJECT || $product->status == Status::PRODUCT_HARD_REJECT)
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <span class="fw-bold text--danger">@lang('Reason of Rejection')</span>
                                        <p>{{ $product->reason }}</p>
                                    </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Demo Link')</span>
                                    <a href="{{ $product->demo_link }}" target="_blank">{{ $product->demo_link }}</a>
                                </li>
                                @foreach ($product->category_details as $key => $detail)
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <span class="fw-bold">{{ inputTitle($key) }}</span>
                                        <div>
                                            @if (count($detail) > 1)
                                                @foreach ($detail as $data)
                                                    <span class="badge badge--dark">{{ str_replace('_', ' ', @$data) }}</span>
                                                @endforeach
                                            @endif

                                            @if (count($detail) == 1)
                                                <span class="badge badge--dark">{{ str_replace('_', ' ', @$detail[0]) }}</span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Tags')</span>
                                    <div>
                                        @if (@$product->tag)
                                            @foreach ($product->tag as $item)
                                                <span class="badge badge--dark">{{ __($item) }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Message To Reviewer')</span>
                                    <span>{{ __($product->message) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Screenshot')</span>
                                    <span>
                                        @if ($product->screenshots)
                                            <a class="text--primary fw-bold" data-rel="lightcase:myCollection:slideshow" href="{{ getImage(getFilePath('product') . '/' . $product->screenshots[0]) }}"><i class="las la-image fs-5 me-2"></i> @lang('Screenshot')</a>
                                            @foreach ($product->screenshots as $item)
                                                @if ($loop->iteration == 1)
                                                    @continue
                                                @endif
                                                <a data-rel="lightcase:myCollection:slideshow" href="{{ getImage(getFilePath('product') . '/' . $item) }}"></a>
                                            @endforeach
                                        @endif
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row gy-3 mt-3">
                        <div class="col-md-12">
                            <h6>@lang('HTML Description')</h6>
                            @php echo $product->description; @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="rejectModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Reason for rejection')</label>
                            <textarea class="form-control" name="message" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--dark" data-bs-dismiss="modal" type="button">@lang('No')</button>
                        <button class="btn btn--primary" type="submit">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.product.review.history', $product->id) }}">
        <i class="la la-history"></i> @lang('Review History')
    </a>
    <x-back route="{{ $url }}" />
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
            "use strict";
            $('.rejectBtn').on('click', function() {
                var modal = $('#rejectModal');
                modal.find('.modal-title').text($(this).data('title'));
                modal.find('form').attr('action', $(this).data('action'));
                modal.modal('show');
            });

            $("a[data-rel^=lightcase]").lightcase();

        })(jQuery)
    </script>
@endpush
