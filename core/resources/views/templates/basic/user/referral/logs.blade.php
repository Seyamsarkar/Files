@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="table-responsive--md">
        <table class="custom--table mb-0 table">
            <thead>
                <tr>
                    <th>@lang('Date')</th>
                    <th>@lang('From')</th>
                    <th>@lang('Level')</th>
                    <th>@lang('Percent')</th>
                    <th>@lang('Amount')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ showDateTime($log->created_at) }}</td>
                        <td>{{ @$log->byWho->username }}</td>
                        <td>{{ __($log->level) }} @lang('Level')</td>
                        <td>{{ getAmount($log->percent) }} %</td>
                        <td>{{ __($general->cur_sym) }}{{ showAmount($log->commission_amount) }}</td>
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
        {{ paginateLinks($logs) }}
    </div>
@endsection
@push('breadcrumb-plugins')
    <a class="btn btn-sm btn-outline--base" href="{{ route('user.referral') }}"><i class="las la-list"> </i> @lang('My Referral Users')</a>
@endpush
