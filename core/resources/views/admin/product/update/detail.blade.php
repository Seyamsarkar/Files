@extends('admin.layouts.app')
@section('panel')

    @php
        if ($tempProduct->image) {
            $image = getImage(getFilePath('tempProduct') . '/' . $tempProduct->image, getFileSize('tempProduct'));
            $screenshot = $tempProduct->screenshot;
            $path = 'tempProduct';
        } else {
            $image = getImage(getFilePath('product') . '/' . $product->featured_image, getFileSize('product'));
            $screenshot = $product->screenshots;
            $path = 'product';
        }
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="image-upload mt-2">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image:url({{ $image }})">
                                            <button class="remove-image" type="button"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="mb-3 text-end">
                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to approved this product?')" data-action="{{ route('admin.update.product.approve', Crypt::encrypt($tempProduct->id)) }}"><i class="las la-check-circle"></i> @lang('Approve') </button>

                                <button class="btn btn-sm btn-outline--danger rejectBtn" data-title="@lang('Are you sure to reject this product?')" data-action="{{ route('admin.update.product.reject', Crypt::encrypt($tempProduct->id)) }}"><i class="las la-times-circle"></i> @lang('Reject') </button>
                            </div>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Name')</span>
                                    <a href="{{ route('admin.product.detail', Crypt::encrypt($tempProduct->product->id)) }}">{{ $tempProduct->name }}</a>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Category')</span>
                                    <span>{{ @$tempProduct->category->name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Subcategory')</span>
                                    <span>{{ @$tempProduct->subcategory->name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Regular Price')</span>
                                    <span>{{ showAmount($tempProduct->regular_price) }} {{ $general->cur_text }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Extended Price')</span>
                                    <span>{{ showAmount($tempProduct->extended_price) }} {{ $general->cur_text }}</span>
                                </li>
                                @if ($tempProduct->support)
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <span class="fw-bold">@lang('Support Charge')</span>
                                        <span>{{ showAmount($tempProduct->support_charge) }} {{ $general->cur_text }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <span class="fw-bold">@lang('Support Discount')</span>
                                        <span>{{ showAmount($tempProduct->support_discount) }} {{ $general->cur_text }}</span>
                                    </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Status')</span>
                                    <p>
                                        @php
                                            echo $tempProduct->statusBadge;
                                        @endphp
                                    </p>
                                </li>
                                @if ($tempProduct->status == 2 || $tempProduct->status == 3)
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <span class="fw-bold text--danger">@lang('Reason of Rejection')</span>
                                        <p>{{ $tempProduct->reason }}</p>
                                    </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Demo Link')</span>
                                    <a href="{{ $tempProduct->demo_link }}" target="_blank">{{ $tempProduct->demo_link }}</a>
                                </li>
                                @foreach ($tempProduct->category_details as $key => $detail)
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
                                        @if (@$tempProduct->tag)
                                            @foreach ($tempProduct->tag as $item)
                                                <span class="badge badge--dark">{{ __($item) }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Message To User')</span>
                                    <span>{{ __($tempProduct->message) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <span class="fw-bold">@lang('Screenshot')</span>
                                    <span>
                                        @if ($screenshot)
                                            <a class="text--primary fw-bold" data-rel="lightcase:myCollection:slideshow" href="{{ getImage(getFilePath($path) . '/' . $screenshot[0]) }}"><i class="las la-image fs-5 me-2"></i> @lang('Screenshot')</a>

                                            @foreach ($screenshot as $item)
                                                @if ($loop->iteration == 1)
                                                    @continue
                                                @endif
                                                <a data-rel="lightcase:myCollection:slideshow" href="{{ getImage(getFilePath($path) . '/' . $item) }}"></a>
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
                            @php echo $tempProduct->description; @endphp
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
