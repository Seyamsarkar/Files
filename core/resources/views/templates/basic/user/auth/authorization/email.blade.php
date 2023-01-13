@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="pt-100 pb-100">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper">
                    <div class="verification-area">

                        <p class="pt-3">@lang('A 6 digit verification code sent to your email address'): {{ showEmailAddress(auth()->user()->email) }}</p>
                        <form class="submit-form" action="{{ route('user.verify.email') }}" method="POST">
                            @csrf

                            @include($activeTemplate . 'partials.verification_code')

                            <div class="form-group">
                                <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                            </div>
                            <p>
                                @lang('If you don\'t get any code'), <a class="text--base" href="{{ route('user.send.verify.code', 'email') }}"> @lang('Try again')</a>
                            </p>
                            @if ($errors->has('resend'))
                                <small class="text--danger d-block">{{ $errors->first('resend') }}</small>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
