@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Code / Date')</th>
                                    <th>@lang('Product')</th>
                                    <th>@lang('Author')</th>
                                    <th>@lang('Buyer')</th>
                                    <th>@lang('License / Support')</th>
                                    <th>@lang('Price / Support Fee')</th>
                                    <th>@lang('Total Price')</th>
                                    <th>@lang('Status')</th>
                                    @if (request()->routeIs('admin.sell.pending'))
                                        <th>@lang('Action')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse ($sells as $sell)
                                    <tr>
                                        <td>{{ $sell->code }}<br>{{ showDateTime($sell->created_at) }}</td>
                                        <td>
                                            <a href="{{ route('admin.product.detail', $sell->product_id) }}">{{ strLimit(@$sell->product->name, 30) }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.detail', $sell->author->id) }}">{{ @$sell->author->username }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.detail', $sell->user->id) }}">{{ @$sell->user->username }}</a>
                                        </td>
                                        <td>
                                            @if ($sell->license == Status::REGULAR_LICENSE)
                                                @lang('Regular')
                                            @elseif ($sell->license == Status::EXTENDED_LICENSE)
                                                @lang('Extended')
                                            @endif
                                            <br>
                                            @if ($sell->support_time)
                                                {{ showDateTime($sell->support_time) }}
                                            @else
                                                @lang('No support')
                                            @endif
                                        </td>
                                        <td>{{ $general->cur_sym }}{{ showAmount($sell->product_price) }}
                                            <br>
                                            {{ $general->cur_sym }}{{ showAmount($sell->support_fee) }}
                                        </td>
                                        <td>{{ $general->cur_sym }}{{ showAmount($sell->total_price) }}</td>
                                        <td>
                                            @php
                                                echo $sell->statusBadge;
                                            @endphp
                                        </td>
                                        @if (request()->routeIs('admin.sell.pending'))
                                            <td>
                                                <a class="btn btn-sm btn-outline--primary ms-1" href="{{ route('admin.deposit.details', $sell->deposit_id) }}">
                                                    <i class="la la-desktop"></i> @lang('Payment Details')
                                                </a>
                                            </td>
                                        @endif
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
                @if ($sells->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($sells) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-search-form placeholder="Search here" />
@endpush
