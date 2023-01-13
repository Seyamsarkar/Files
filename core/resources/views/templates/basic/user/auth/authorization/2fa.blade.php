@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="pt-100 pb-100">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper">
                    <div class="verification-area">
                        
                        <form class="submit-form" action="{{ route('user.go2fa.verify') }}" method="POST">
                            @csrf
                            <div class="mt-3">
                                @include($activeTemplate . 'partials.verification_code')
                            </div>
                            <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
