@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="pt-100 pb-100">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper">
                    <div class="verification-area">
                        <form class="submit-form" action="{{ route('user.verify.mobile') }}" method="POST">
                            @csrf
                            <p class="pt-3">@lang('A 6 digit verification code sent to your mobile number') : +{{ showMobileNumber(auth()->user()->mobile) }}</p>
                            @include($activeTemplate . 'partials.verification_code')
                            <div class="mb-3">
                                <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                            </div>
                            <div class="form-group mb-0">
                                <p>
                                    @lang('If you don\'t get any code'), <a class="text--base" href="{{ route('user.send.verify.code', 'phone') }}"> @lang('Try again')</a>
                                </p>
                                @if ($errors->has('resend'))
                                    <small class="text--danger">{{ $errors->first('resend') }}</small>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
