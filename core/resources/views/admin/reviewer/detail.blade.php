@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-4">
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two style--two box--shadow2 b-radius--5 bg--19">
                <div class="widget-two__icon b-radius--5 bg--primary">
                    <i class="las la-money-bill-wave-alt"></i>
                </div>
                <div class="widget-two__content">
                    <h3 class="text-white">{{ @$reviewer->total_reviewed_count }}</h3>
                    <p class="text-white">@lang('Total Reviewed')</p>
                </div>
                <a class="widget-two__btn" href="{{ route('admin.reviewers.products', [$reviewer->id]) }}">@lang('View All')</a>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">
                <div class="widget-two__icon b-radius--5 bg--primary">
                    <i class="las la-wallet"></i>
                </div>
                <div class="widget-two__content">
                    <h3 class="text-white">{{ @$reviewer->total_approved_count }}</h3>
                    <p class="text-white">@lang('Total Approved')</p>
                </div>
                <a class="widget-two__btn" href="{{ route('admin.reviewers.products', [$reviewer->id, 'approved']) }}">@lang('View All')</a>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two style--two box--shadow2 b-radius--5 bg--1">
                <div class="widget-two__icon b-radius--5 bg--primary">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="widget-two__content">
                    <h3 class="text-white">{{ @$reviewer->total_soft_reject_count }}</h3>
                    <p class="text-white">@lang('Total Soft Rejected')</p>
                </div>
                <a class="widget-two__btn" href="{{ route('admin.reviewers.products', [$reviewer->id, 'softRejected']) }}">@lang('View All')</a>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two style--two box--shadow2 b-radius--5 bg--17">
                <div class="widget-two__icon b-radius--5 bg--primary">
                    <i class="las la-exchange-alt"></i>
                </div>
                <div class="widget-two__content">
                    <h3 class="text-white">{{ @$reviewer->total_hard_reject_count }}</h3>
                    <p class="text-white">@lang('Total Hard Rejected')</p>
                </div>
                <a class="widget-two__btn" href="{{ route('admin.reviewers.products', [$reviewer->id, 'hardRejected']) }}">@lang('View All')</a>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-12">
            <div class="d-flex flex-wrap gap-3">

                <div class="flex-fill">
                    <a class="btn btn--primary btn--shadow w-100 btn-lg" href="{{ route('admin.report.reviewer.login.history') }}?search={{ $reviewer->username }}">
                        <i class="las la-list-alt"></i>@lang('Logins')
                    </a>
                </div>

                <div class="flex-fill">
                    <a class="btn btn--success btn--gradi btn--shadow w-100 btn-lg" href="{{ route('admin.reviewers.login', $reviewer->id) }}" target="_blank">
                        <i class="las la-sign-in-alt"></i>@lang('Login as Reviewer')
                    </a>
                </div>

                <div class="flex-fill">
                    <a class="btn btn--info btn--gradi btn--shadow w-100 btn-lg" href="{{ route('admin.reviewers.notification.single', $reviewer->id) }}" target="_blank">
                        <i class="las la-envelope"></i>@lang('Send Notification')
                    </a>
                </div>

                <div class="flex-fill">
                    @if ($reviewer->status == Status::REVIEWER_ACTIVE)
                        <button class="btn btn--warning btn--gradi btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal" type="button">
                            <i class="las la-ban"></i>@lang('Ban Reviewer')
                        </button>
                    @else
                        <button class="btn btn--success btn--gradi btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal" type="button">
                            <i class="las la-undo"></i>@lang('Unban Reviewer')
                        </button>
                    @endif
                </div>
            </div>

            <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Information of') {{ $reviewer->fullname }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reviewers.store', $reviewer->id) }}" method="POST">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('First Name')</label>
                                    <input class="form-control" name="firstname" type="text" value="{{ $reviewer->firstname }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Last Name')</label>
                                    <input class="form-control" name="lastname" type="text" value="{{ $reviewer->lastname }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Email') </label>
                                    <input class="form-control" name="email" type="email" value="{{ $reviewer->email }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Mobile Number') </label>
                                    <div class="input-group">
                                        <span class="input-group-text mobile-code"></span>
                                        <input class="form-control checkUser" id="mobile" name="mobile" type="number" value="{{ old('mobile') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Address')</label>
                                    <input class="form-control" name="address" type="text" value="{{ @$reviewer->address->address }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('City')</label>
                                    <input class="form-control" name="city" type="text" value="{{ @$reviewer->address->city }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('State')</label>
                                    <input class="form-control" name="state" type="text" value="{{ @$reviewer->address->state }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Zip/Postal')</label>
                                    <input class="form-control" name="zip" type="text" value="{{ @$reviewer->address->zip }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Country')</label>
                                    <select class="form-control" name="country">
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}">{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-xl-4 col-md-6 col-12">
                                <label>@lang('Email Verification')</label>
                                <input name="ev" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" type="checkbox" @if ($reviewer->ev) checked @endif>

                            </div>
                            <div class="form-group col-xl-4 col-md-6 col-12">
                                <label>@lang('Mobile Verification')</label>
                                <input name="sv" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" type="checkbox" @if ($reviewer->sv) checked @endif>
                            </div>
                            <div class="form-group col-xl-4 col-md- col-12">
                                <label>@lang('2FA Verification') </label>
                                <input name="ts" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" type="checkbox" @if ($reviewer->ts) checked @endif>
                            </div>

                            <div class="col-md-12">
                                <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="userStatusModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($reviewer->status == Status::REVIEWER_ACTIVE)
                            <span>@lang('Ban Reviewer')</span>
                        @else
                            <span>@lang('Unban Reviewer')</span>
                        @endif
                    </h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.reviewers.status', $reviewer->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if ($reviewer->status == Status::REVIEWER_ACTIVE)
                            <h6 class="mb-2">@lang('If you ban this reviewer he/she won\'t able to access his/her dashboard.')</h6>
                            <div class="form-group">
                                <label>@lang('Reason')</label>
                                <textarea class="form-control" name="reason" rows="4" required></textarea>
                            </div>
                        @else
                            <h6><span>@lang('Ban reason was'):</span></h6>
                            <p>{{ $reviewer->ban_reason }}</p>
                            <h4 class="mt-3 text-center">@lang('Are you sure to unban this reviewer?')</h4>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if ($reviewer->status == Status::REVIEWER_ACTIVE)
                            <button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
                        @else
                            <button class="btn btn--dark" data-bs-dismiss="modal" type="button">@lang('No')</button>
                            <button class="btn btn--primary" type="submit">@lang('Yes')</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.reviewers.all') }}" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"

            let mobileElement = $('.mobile-code');
            $('select[name=country]').change(function() {
                mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
            });

            $('select[name=country]').val('{{ @$reviewer->country_code }}');
            let dialCode = $('select[name=country] :selected').data('mobile_code');
            let mobileNumber = `{{ $reviewer->mobile }}`;
            mobileNumber = mobileNumber.replace(dialCode, '');
            $('input[name=mobile]').val(mobileNumber);
            mobileElement.text(`+${dialCode}`);

        })(jQuery);
    </script>
@endpush
