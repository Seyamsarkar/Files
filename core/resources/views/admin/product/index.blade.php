@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('SL')</th>
                                    <th>@lang('Image')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Subcategory')</th>
                                    <th>@lang('Status')</th>
                                    @if (request()->routeIs('admin.product.approved'))
                                        <th>@lang('Featured')</th>
                                    @endif
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody class="list">
                                @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $products->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="user justify-content-center">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('product') . '/thumb_' . $product->featured_image, getFileSize('product')) }}" alt="@lang('image')">
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ __($product->name) }}</td>
                                        <td>{{ __(@$product->category->name) }}</td>
                                        <td>{{ __(@$product->subcategory->name ?? 'N/A') }}</td>
                                        <td>
                                            @php
                                                echo $product->statusBadge;
                                            @endphp
                                        </td>
                                        @if (request()->routeIs('admin.product.approved'))
                                            <td>
                                                @if ($product->featured)
                                                    <span class="badge badge--success">@lang('Yes')</span>
                                                @else
                                                    <span class="badge badge--danger">@lang('No')</span>
                                                @endif
                                            </td>
                                        @endif
                                        <td>
                                            @if (request()->routeIs('admin.product.approved'))
                                                @if ($product->featured)
                                                    <button class="btn btn-outline--danger confirmationBtn" data-question="@lang('Are you sure unfeature this product?')" data-action="{{ route('admin.product.featured', $product->id) }}" type="button"><i class="las la-eye-slash"></i> @lang('Unfeature')</button>
                                                @else
                                                    <button class="btn btn-outline--success confirmationBtn" data-question="@lang('Are you sure feature this product?')" data-action="{{ route('admin.product.featured', $product->id) }}" type="button"><i class="las la-eye"></i> @lang('Feature')</button>
                                                @endif
                                            @endif
                                            <a class="btn btn-outline--primary" href="{{ route('admin.product.download', $product->id) }}">
                                                <i class="las la-download"></i> @lang('Download')
                                            </a>
                                            <a class="btn btn-outline--info" href="{{ route('admin.product.detail', $product->id) }}">
                                                <i class="las la-desktop"></i> @lang('Detail')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($products->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($products) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <x-search-form placeholder="Search Here" />
@endpush
