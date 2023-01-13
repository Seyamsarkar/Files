@extends($activeTemplate . 'layouts.master')
@php
    $hiddenProductsRoute = request()->routeIs('user.hidden.product');
@endphp
@section('content')
    <div class="table-responsive--md">
        <table class="custom--table mb-0 table">
            <thead>
                <tr>
                    <th>@lang('Image')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Category')</th>
                    <th>@lang('Subcategory')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Total Sell')</th>
                    <th>@lang('Update Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="user d-flex justify-content-lg-start justify-content-end">
                                <div class="thumb"><img class="plugin_bg" src="{{ getImage(getFilePath('product') . '/' . $product->featured_image, getFileSize('product')) }}" alt="{{ __($product->name) }}"></div>
                            </div>
                        </td>
                        <td><a class="text--base" href="@if (@$product->status == Status::PRODUCT_APPROVE) {{ route('product.detail', [@$product->id, slug(@$product->name)]) }} @else javascript:void(0) @endif" target="_blank">{{ strLimit(@$product->name, 32) }}</a></td>
                        <td>
                            <span @if ($hiddenProductsRoute && @$product->category && !$product->category->status) class="text-decoration-line-through text--danger" data-bs-toggle="tooltip" title="@lang('Category unavailable')" @endif>{{ __(@$product->category->name) }}</span>
                        </td>
                        <td>
                            <span @if ($hiddenProductsRoute && @$product->subcategory && !$product->subcategory->status) class="text-decoration-line-through text--danger" data-bs-toggle="tooltip" title="@lang('Subcategory unavailable')" @endif>{{ __(@$product->subcategory->name ?? 'N/A') }}</span>
                        </td>
                        <td>
                            @php
                                echo $product->statusBadge;
                            @endphp
                            @if ($product->status == Status::PRODUCT_SOFT_REJECT || $product->status == Status::PRODUCT_HARD_REJECT)
                                <a class="text--danger reasonBtn" data-reason="{{ $product->reason }}" href="javascript:void(0)"><span><i class="fas fa-info-circle"></i></span></a>
                            @endif
                        </td>
                        <td>
                            <span>{{ $product->status == Status::PRODUCT_APPROVE ? $product->total_sell : 0 }}</span>
                        </td>
                        <td>
                            @if ($product->status == Status::PRODUCT_APPROVE)
                                @if ($product->update_status == Status::PRODUCT_UPDATE_PENDING)
                                    <span class="badge badge--warning">@lang('Pending')</span>
                                @elseif($product->update_status == Status::PRODUCT_UPDATE_APPROVED)
                                    <span class="badge badge--success">@lang('Approved')</span>
                                @elseif($product->update_status == Status::PRODUCT_UPDATE_REJECTED)
                                    <span class="badge badge--danger">@lang('Rejected')</span>
                                    <a class="text--danger reasonBtn" data-reason="{{ $product->update_reject }}" href="javascript:void(0)"><span><i class="fas fa-info-circle"></i></span></a>
                                @endif
                            @else
                                <span>@lang('N/A')</span>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                <a class="btn btn--info btn--sm" href="{{ route('user.product.detail', $product->id) }}">
                                    <i class="las la-desktop"></i> @lang('Detail')
                                </a>

                                @if ($product->status == Status::PRODUCT_APPROVE && $product->update_status != Status::PRODUCT_UPDATE_PENDING)
                                    <a class="btn btn--base btn--sm" href="{{ route('user.product.edit', $product->id) }}">
                                        <i class="las la-pen"></i> @lang('Edit')
                                    </a>
                                @elseif($product->status == Status::PRODUCT_PENDING || $product->status == Status::PRODUCT_HARD_REJECT || $product->status == Status::PRODUCT_RESUBMIT)
                                    <button class="btn btn--base btn--sm" @disabled(true)>
                                        <i class="las la-pen"></i> @lang('Edit')
                                    </button>
                                @endif

                                @if ($product->status == Status::PRODUCT_SOFT_REJECT)
                                    <a class="btn btn--primary btn--sm" href="{{ route('user.product.resubmit', $product->id) }}">
                                        <i class="las la-sync-alt"></i> @lang('Resubmit')
                                    </a>
                                @endif

                                <a class="btn btn--warning btn--sm" href="{{ route('user.product.reviews', $product->id) }}">
                                    <i class="las la-star"></i> @lang('Reviews')
                                </a>
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="justify-content-center text-center" colspan="100%">
                            <i class="la la-4x la-frown"></i>
                            <br>
                            {{ __($emptyMessage) }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination--sm justify-content-end">
            {{ paginateLinks($products) }}
        </div>
    </div>

    <div class="modal fade" id="reasonModal" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">@lang('Detail of Reason')</h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <p class="modal-detail"></p>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('style')
    <style>
        table .user {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        table .user .thumb {
            width: 40px;
            height: 40px;
        }

        table .user .thumb img {
            width: 40px;
            height: 40px;
            -webkit-border-radius: 50%;
            object-fit: cover;
            object-position: center;
            border: 2px solid #ffffff;
            box-shadow: 0 5px 10px 0 rgb(0 0 0 / 20%);
        }
    </style>
@endpush

@push('breadcrumb-plugins')
    <form action="" method="GET">
        <div class="input-group">
            <input class="form-control form--control search-form" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search here')...">
            <button class="input-group-text" type="submit"><i class="la la-search"></i></button>
        </div>
    </form>
    <a class="btn btn-outline--base btn-sm" href="{{ route('user.product.add') }}">
        <i class="las la-plus"></i> @lang('Add New')
    </a>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.reasonBtn').on('click', function() {
                var modal = $('#reasonModal');
                modal.find('.modal-detail').text($(this).data('reason'));
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush
