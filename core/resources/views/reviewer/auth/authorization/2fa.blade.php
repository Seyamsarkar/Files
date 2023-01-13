@extends('admin.layouts.master')
@section('content')
    <section class="verification-page flex-column justify-content-center" style="background-image: url('{{ asset('assets/admin/images/login.jpg') }}')">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper">
                    <div class="verification-area">
                        <h5 class="border-bottom pb-3 text-center">@lang('2FA Verification')</h5>
                        <form class="submit-form" action="{{ route('reviewer.go2fa.verify') }}" method="POST">
                            @csrf

                            <div class="my-3">
                                @include($activeTemplate . 'partials.verification_code')
                            </div>

                            <button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
                        </form>
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
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#code').on('input change', function() {
                var xx = document.getElementById('code').value;
                $(this).val(function(index, value) {
                    value = value.substr(0, 7);
                    return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
                });
            });
        })(jQuery)
    </script>
@endpush
