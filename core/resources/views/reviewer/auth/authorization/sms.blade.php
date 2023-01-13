@extends('admin.layouts.master')
@section('content')
    <section class="verification-page flex-column justify-content-center" style="background-image: url('{{ asset('assets/admin/images/login.jpg') }}')">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper">
                    <div class="verification-area">
                        <div class="d-flex border-bottom justify-content-between flex-wrap pb-3">
                            <h5>@lang('Verify Mobile')</h5>
                            <a class="text--base" href="{{ route('reviewer.logout') }}"><strong><i class="las la-sign-in-alt"></i> @lang('Logout')</strong></a>
                        </div>
                        <form class="submit-form" action="{{ route('reviewer.verify.mobile') }}" method="POST">
                            @csrf
                            <p class="pt-3">@lang('A 6 digit verification code sent to your mobile number') : +{{ showMobileNumber(auth()->guard('reviewer')->user()->mobile) }}</p>
                            <div class="my-3">
                                @include($activeTemplate . 'partials.verification_code')
                            </div>
                            <button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
                            <div class="mt-3">
                                <p>
                                    @lang('If you don\'t get any code'), <a class="text--base" href="{{ route('reviewer.send.verify.code', 'phone') }}"> @lang('Try again')</a>
                                </p>
                                @if ($errors->has('resend'))
                                    <br />
                                    <small class="text--danger">{{ $errors->first('resend') }}</small>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
@push('style')
    <style>
        .verification-page {
            display: grid;
            place-content: center;
            width: 100vw;
            height: 100vh;
        }

        .verification-code span {
            border: solid 1px #3725ed;
            color: #3725ed;
        }
    </style>
@endpush
