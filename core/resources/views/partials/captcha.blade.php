@php
    $customCaptcha = loadCustomCaptcha();
    $googleCaptcha = loadReCaptcha();
@endphp
@if ($googleCaptcha)
    <div class="mb-3">
        @php echo $googleCaptcha @endphp
    </div>
@endif
@if ($customCaptcha)
    @if (request()->routeIs('admin.login') || request()->routeIs('reviewer.login'))
        <div class="form-group">
            <div class="mb-2">
                @php echo $customCaptcha @endphp
            </div>
            <label>@lang('Captcha')</label>
            <div class="custom-icon-field">
                <input class="form-control" name="captcha" type="text" placeholder="@lang('Enter Code')" required>
            </div>
        </div>
    @else
        <div class="form-group">
            <div class="mb-2">
                @php echo $customCaptcha @endphp
            </div>
            <label @if (!request()->routeIs('contact')) class="text-white" @endif>@lang('Captcha')</label>
            <div class="custom-icon-field">
                <i class="las la-code"></i>
                <input class="form--control" name="captcha" type="text" placeholder="@lang('Enter Code')" required>
            </div>
        </div>
    @endif
@endif
@if ($googleCaptcha)
    @push('script')
        <script>
            (function($) {
                "use strict"
                $('.verify-gcaptcha').on('submit', function() {
                    var response = grecaptcha.getResponse();
                    if (response.length == 0) {
                        document.getElementById('g-recaptcha-error').innerHTML = '<span class="text--danger">@lang('Captcha field is required.')</span>';
                        return false;
                    }
                    return true;
                });
            })(jQuery);
        </script>
    @endpush
@endif
