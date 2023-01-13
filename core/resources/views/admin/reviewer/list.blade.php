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
                                    <th>@lang('Name')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Mobile')</th>
                                    <th>@lang('Country')</th>
                                    <th>@lang('Joined At')</th>
                                    @if (request()->routeIs('admin.reviewers.all'))
                                        <th>@lang('Status')</th>
                                    @endif
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviewers as $reviewer)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $reviewer->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.reviewers.detail', $reviewer->id) }}"><span>@</span>{{ $reviewer->username }}</a>
                                            </span>
                                        </td>

                                        <td>
                                            <span>{{ $reviewer->email }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $reviewer->mobile }}</span>
                                        </td>
                                        <td>
                                            <span>{{ @$reviewer->address->country }}</span>
                                        </td>

                                        <td>
                                            <span>{{ showDateTime($reviewer->created_at) }} <br> {{ diffForHumans($reviewer->created_at) }}</span>
                                        </td>
                                        @if (request()->routeIs('admin.reviewers.all'))
                                            <td>
                                                @if ($reviewer->status == Status::REVIEWER_ACTIVE)
                                                    <span class="badge badge--success">@lang('Active')</span>
                                                @else
                                                    <span class="badge badge--danger">@lang('Banned')</span>
                                                @endif
                                            </td>
                                        @endif
                                        <td>
                                            <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.reviewers.detail', $reviewer->id) }}">
                                                <i class="las la-desktop text--shadow"></i> @lang('Details')
                                            </a>
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
                @if ($reviewers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($reviewers) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-lg btn-outline--primary" href="{{ route('admin.reviewers.add') }}"><i class="las la-plus"></i>@lang('Add New')</a>
    <x-search-form placeholder="Username / Email" />
@endpush
