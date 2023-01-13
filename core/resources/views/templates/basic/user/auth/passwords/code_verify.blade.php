@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 col-xl-5">
                <div class="d-flex justify-content-center">
                    <div class="verification-code-wrapper">
                        <div class="verification-area">
                            <h5 class="border-bottom pb-3 text-center">@lang('Verify Email Address')</h5>
                            <p class="pt-3">@lang('A 6 digit verification code sent to your email address') : {{ showEmailAddress($email) }}</p>
                            <form class="submit-form" action="{{ route('user.password.verify.code') }}" method="POST">
                                @csrf
                                <input name="email" type="hidden" value="{{ $email }}">
                                @include($activeTemplate . 'partials.verification_code')
                                <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                                <p class="mt-3">
                                    @lang('Please check including your Junk/Spam Folder. if not found, you can')
                                    <a class="text--base" href="{{ route('user.password.request') }}">@lang('Try to send again')</a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection