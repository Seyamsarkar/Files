@extends($activeTemplate . 'layouts.app')
@section('app')
    @php
        $policyPages = getContent('policy_pages.element', false, null, true);
        $register = getContent('register.content', true);
        
        $topAuthors = App\Models\User::where('status', Status::USER_ACTIVE)
            ->where('top_author', 1)
            ->limit(12)
            ->get(['image', 'username']);
    @endphp
    <div class="account-area style--two">
        <div class="account-area-bg bg_img" style="background-image: url({{ getImage('assets/images/frontend/register/' . @$register->data_values->image, '1270x970') }});"></div>
        <div class="account-area-left style--two">
            <div class="account-area-left-inner">
                <div class="mb-5 text-center">
                    <span class="subtitle text--base fw-bold border-left">@lang('Welcome to') {{ __($general->site_name) }}</span>
                    <h2 class="title text-white">{{ __(@$register->data_values->heading) }}</h2>
                    <p class="fs-14px mt-4 text-white">@lang('Already you have an account?') <a class="text--base" href="{{ route('user.login') }}">@lang('Login Now')</a></p>
                </div>
                @if ($topAuthors->count() > 0)
                    <h5 class="mt-5 mb-3 text-center text-white">{{ __(@$register->data_values->subheading) }}</h5>
                    <div class="top-author-slider">
                        @foreach ($topAuthors as $author)
                            <div class="single-slide">
                                <a class="s-top-author" href="{{ route('author.profile', $author->username) }}">
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $author->image) }}" alt="image">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="account-wrapper style--two">
            <div class="account-logo text-center">
                <a class="site-logo" href="{{ route('home') }}"><img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('site-logo')"></a>
            </div>
            <form class="account-form verify-gcaptcha" action="{{ route('user.register') }}" method="POST">
                @csrf
                <div class="row">
                    @if (session()->get('reference') != null)
                        <div class="form-group col-md-12">
                            <label class="text-white" for="referenceBy">@lang('Reference by')</label>
                            <div class="custom-icon-field">
                                <i class="las la-user fs-4"></i>
                                <input class="form--control" id="referenceBy" name="referBy" type="text" value="{{ session()->get('reference') }}" readonly>
                            </div>
                        </div>
                    @endif
                    <div class="form-group col-lg-6">
                        <label class="text-white" for="username">@lang('Username')</label>
                        <div class="custom-icon-field">
                            <i class="las la-user fs-4"></i>
                            <input class="form--control checkUser" id="username" name="username" type="text" value="{{ old('username') }}" placeholder="@lang('Enter Username')" required>
                            <small class="text--danger usernameExist"></small>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="text-white" for="email">@lang('Email Address')</label>
                        <div class="custom-icon-field">
                            <i class="las la-envelope fs-4"></i>
                            <input class="form--control checkUser" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="@lang('Enter Email Address')" required>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="text-white" for="country">@lang('Country')</label>
                        <div class="custom-icon-field">
                            <i class="las la-globe fs-4"></i>
                            <select class="form--control" id="country" name="country" required>
                                @foreach ($countries as $key => $country)
                                    <option data-mobile_code="{{ $country->dial_code }}" data-code="{{ $key }}" value="{{ $country->country }}" @selected(old('country') == $country->country)>{{ __($country->country) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="text-white" for="mobile">@lang('Mobile Number')</label>
                        <div class="custom-icon-field custom-icon-field--style">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text mobile-code"></span>
                                    <input name="mobile_code" type="hidden">
                                    <input name="country_code" type="hidden">
                                </div>
                                <input class="form--control checkUser" id="mobile" name="mobile" type="text" value="{{ old('mobile') }}" placeholder="@lang('Enter Mobile Number')" required>
                            </div>
                            <small class="text--danger mobileExist"></small>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="text-white" for="password">@lang('Password')</label>
                        <div class="custom-icon-field">
                            <i class="las la-lock"></i>
                            <input class="form--control" id="password" name="password" type="password" placeholder="@lang('Enter Password')" required>
                            @if ($general->secure_password)
                                <div class="input-popup">
                                    <p class="error lower">@lang('1 small letter minimum')</p>
                                    <p class="error capital">@lang('1 capital letter minimum')</p>
                                    <p class="error number">@lang('1 number minimum')</p>
                                    <p class="error special">@lang('1 special character minimum')</p>
                                    <p class="error minimum">@lang('6 character password')</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="text-white">@lang('Confirm Password') <sup class="text--danger">*</sup></label>
                        <div class="custom-icon-field">
                            <i class="las la-key fs-4"></i>
                            <input class="form--control" name="password_confirmation" type="password" placeholder="@lang('Confirm Password')" required>
                        </div>
                    </div>

                    <x-captcha />

                    @if ($general->agree)
                        <div class="form-group">
                            <div class="form--check">
                                <input class="form-check-input" id="flexCheckDefault" name="agree" type="checkbox" @checked(old('agree')) required>
                                <label class="form-check-label text-white" for="flexCheckDefault">
                                    @lang(' I agree with')
                                </label>
                                <span class="ms-1">
                                    @foreach ($policyPages as $policy)
                                        <a class="text--base" href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}" target="_blank">{{ __($policy->data_values->title) }}</a>
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="col-12">
                        <button class="btn btn--base w-100" id="recaptcha" type="submit">@lang('Submit')</button>
                    </div>
                </div>
            </form>

            <div class="account-footer text-center">
                <span class="text-white">@lang('Copyright') &copy; @php echo date('Y') @endphp. @lang('All Right Reserved')</span>
            </div>
        </div>
    </div>

    <div class="modal fade" id="existModalCenter" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-dark btn-sm" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                    <a class="btn btn--base btn-sm" href="{{ route('user.login') }}">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .agree-field {
            display: inline !important;
        }
    </style>
@endpush

@if ($general->secure_password)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('script')
    <script>
        "use strict";
        (function($) {
            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));


            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false && response.type == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
