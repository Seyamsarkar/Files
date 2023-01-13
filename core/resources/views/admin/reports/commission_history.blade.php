@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('TRX') | @lang('Date')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Level')</th>
                                    <th>@lang('From')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Percentage')</th>
                                    <th>@lang('Description')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $data)
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold">{{ __($data->trx) }}</div>
                                            {{ showDateTime($data->created_at, 'Y-m-d') }}
                                        </td>
                                        <td>
                                            <span class="font-weight-bold d-block">{{ @$data->user->fullname }}</span>
                                            <span class="small"> <a href="{{ route('admin.users.detail', @$data->user->id ?? 0) }}"><span>@</span>{{ @$data->user->username }}</a> </span>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold d-block">@lang('level '){{ __($data->level) }}</span>
                                        </td>
                                        <td>
                                            <span class="small"> <a href="{{ route('admin.users.detail', @$data->byWho->id ?? 0) }}"><span>@</span>{{ @$data->byWho->username }}</a> </span>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold d-block" data-toggle="tooltip" title="@lang('Transacted Amount') : {{ __($general->cur_sym) }}{{ getAmount($data->trx_amo) }}">{{ __($general->cur_sym) }}{{ getAmount($data->commission_amount) }}</span>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold" data-toggle="tooltip" title="@lang('Percent')">{{ getAmount($data->percent) }}%</span>
                                        </td>
                                        <td>
                                            {{ __($data->title) }}
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
                @if ($logs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($logs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search by username/TRX" />
@endpush
