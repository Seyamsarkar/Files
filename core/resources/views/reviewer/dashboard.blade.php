@extends('admin.layouts.reviewer')
@section('panel')
    <div class="row gy-4">
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--warning has-link box--shadow2 overflow-hidden">
                <a class="item-link" href="{{ route('reviewer.product.pending') }}"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-spinner f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Pending Products')</span>
                            <h2 class="text-white">{{ $widget['pending'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--danger has-link box--shadow2">
                <a class="item-link" href="{{ route('reviewer.product.soft.rejected') }}"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-times-circle f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Soft Rejected Products')</span>
                            <h2 class="text-white">{{ $widget['softRejected'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--red has-link box--shadow2">
                <a class="item-link" href="{{ route('reviewer.product.hard.rejected') }}"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-ban f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Hard Rejected Products')</span>
                            <h2 class="text-white">{{ $widget['hardRejected'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--success has-link box--shadow2">
                <a class="item-link" href="{{ route('reviewer.product.approved') }}"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-check-circle f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Approved Products')</span>
                            <h2 class="text-white">{{ $widget['approved'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--orange has-link box--shadow2">
                <a class="item-link" href="{{ route('reviewer.update.product.pending') }}"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-hourglass-start f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Update Pending Products')</span>
                            <h2 class="text-white">{{ $widget['updatePending'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--info has-link box--shadow2">
                <a class="item-link" href="{{ route('reviewer.product.resubmitted') }}"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-list-ul f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Total Resubmitted Products')</span>
                            <h2 class="text-white">{{ $widget['resubmitted'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card b-radius--10">
                <div class="card-header">
                    <h6 class="card-title">@lang('Latest Pending Product')</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse ($pendingProducts as $product)
                                    <tr>
                                        <td>{{ __($product->name) }}</td>
                                        <td>{{ __(@$product->category->name) }}</td>
                                        <td>
                                            @php
                                                echo $product->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
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
            </div>
        </div>
        <div class="col-md-6">
            <div class="card b-radius--10">
                <div class="card-header">
                    <h6 class="card-title">@lang('Latest Update Product')</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody class="list">
                                @forelse ($updateProducts as $updateProduct)
                                    <tr>
                                        <td>{{ __($updateProduct->name) }}</td>
                                        <td>{{ __(@$updateProduct->category->name) }}</td>
                                        <td>
                                            @php
                                                echo $updateProduct->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <a class="btn btn-outline--info" href="{{ route('reviewer.update.product.detail', Crypt::encrypt($updateProduct->id)) }}">
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
            </div>
        </div>
    </div>
@endsection
