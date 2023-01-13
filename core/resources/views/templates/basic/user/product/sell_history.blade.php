@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="table-responsive--md">
        <table class="custom--table mb-0 table">
            <thead>
                <tr>
                    <th>@lang('Date')</th>
                    <th>@lang('Code')</th>
                    <th>@lang('Product')</th>
                    <th>@lang('Licence')</th>
                    <th>@lang('Support')</th>
                    <th>@lang('Price')</th>
                    <th>@lang('Support Fee')</th>
                    <th>@lang('Amount')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sells as $sell)
                    <tr>
                        <td>
                            <span>{{ showDateTime($sell->created_at, 'Y-m-d') }}</span>
                        </td>
                        <td>
                            <span>{{ $sell->code }}</span>
                        </td>
                        <td>
                            <span>{{ @$sell->product->name }}</span>
                        </td>
                        <td>
                            @if ($sell->license == Status::REGULAR_LICENSE)
                                <span>@lang('Regular')</span>
                            @elseif ($sell->license == Status::EXTENDED_LICENSE)
                                <span>@lang('Extended')</span>
                            @endif
                        </td>
                        <td>
                            @if ($sell->support_time)
                                <span>{{ showDateTime($sell->support_time, 'Y-m-d') }}</span>
                            @else
                                <span>@lang('No support')</span>
                            @endif
                        </td>
                        <td>
                            <span>{{ $general->cur_sym }}{{ showAmount($sell->product_price) }}</span>
                        </td>
                        <td>
                            <span>{{ $general->cur_sym }}{{ showAmount($sell->support_fee) }}</span>
                        </td>
                        <td>
                            <span>{{ $general->cur_sym }}{{ showAmount($sell->total_price) }}</span>
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
            {{ paginateLinks($sells) }}
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <form action="" method="GET">
        <div class="input-group">
            <input class="form-control form--control search-form" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search by code or product')">
            <button class="input-group-text" type="submit"><i class="la la-search"></i></button>
        </div>
    </form>
@endpush
