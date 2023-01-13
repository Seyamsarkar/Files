@extends('admin.layouts.reviewer')
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
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody class="list">
                                @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            <div class="user justify-content-center">
                                                <div class="thumb"><img src="{{ getImage(getFilePath('product') . '/thumb_' . $product->featured_image, getFileSize('product')) }}" alt="@lang('image')"></div>
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ @$product->category->name }}</td>
                                        <td>{{ @$product->subcategory->name ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                echo $product->statusBadge;
                                            @endphp
                                        </td>

                                        <td>

                                            <a class="btn btn-outline--primary" href="{{ route('reviewer.product.download', Crypt::encrypt($product->id)) }}">
                                                <i class="las la-download"></i> @lang('Download')
                                            </a>

                                            <a class="btn btn-outline--info" href="{{ route('reviewer.product.detail', Crypt::encrypt($product->id)) }}">
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
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search Here" />
@endpush
