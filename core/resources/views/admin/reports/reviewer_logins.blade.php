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
                                    <th>@lang('Reviewer')</th>
                                    <th>@lang('Login at')</th>
                                    <th>@lang('IP')</th>
                                    <th>@lang('Location')</th>
                                    <th>@lang('Browser | OS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginLogs as $log)
                                    <tr>

                                        <td>
                                            <span class="fw-bold">{{ @$log->reviewer->fullname }}</span>
                                            <br>
                                            <span class="small"> <a href="{{ route('admin.reviewers.detail', $log->reviewer_id) }}"><span>@</span>{{ @$log->reviewer->username }}</a> </span>
                                        </td>

                                        <td>
                                            {{ showDateTime($log->created_at) }} <br> {{ diffForHumans($log->created_at) }}
                                        </td>

                                        <td>
                                            <span class="fw-bold">
                                                <a href="{{ route('admin.report.login.ipHistory', [$log->reviewer_ip]) }}">{{ $log->reviewer_ip }}</a>
                                            </span>
                                        </td>

                                        <td>{{ __($log->city) }} <br> {{ __($log->country) }}</td>
                                        <td>
                                            {{ __($log->browser) }} <br> {{ __($log->os) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($loginLogs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($loginLogs) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>

    </div>
@endsection

@push('breadcrumb-plugins')
    @if (request()->routeIs('admin.report.reviewer.login.history'))
        <x-search-form placeholder="Enter Username"></x-search-form>
    @endif
@endpush
@if (request()->routeIs('admin.report.reviewer.login.ipHistory'))
    @push('breadcrumb-plugins')
        <a class="btn btn--primary" href="https://www.ip2location.com/{{ $ip }}" target="_blank">@lang('Lookup IP') {{ $ip }}</a>
    @endpush
@endif
