@extends($activeTemplate . 'layouts.app')
@section('app')
    @php
        $content = getContent('login.content', true);
        $topAuthors = App\Models\User::where('status', Status::USER_ACTIVE)
            ->topAuthor()
            ->limit(12)
            ->get(['image', 'username']);
        
    @endphp

    <div class="account-area style--two">
        <div class="account-area-bg bg_img" style="background-image: url({{ getImage('assets/images/frontend/login/' . @$content->data_values->image, '1270x970') }});"></div>
        <div class="account-area-left style--two">
            <div class="account-area-left-inner">
                <div class="mb-5 text-center">
                    <span class="subtitle text--base fw-bold border-left">{{ __(@$content->data_values->heading) }}</span>
                    <h2 class="title text-white">{{ __(@$content->data_values->subheading) }}</h2>
                    <p class="fs-14px mt-4 text-white">@lang('Don\'t have an account?') <a class="text--base" href="{{ route('user.register') }}">@lang('Create an Account')</a></p>
                </div>
                @if ($topAuthors->count() > 0)
                    <h5 class="mt-5 mb-3 text-center text-white">{{ __(@$content->data_values->title) }}</h5>
                    <div class="top-author-slider">
                        @foreach ($topAuthors as $author)
                            <div class="single-slide">
                                <a class="s-top-author" href="{{ route('author.profile', $author->username) }}">
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $author->image, getFileSize('userProfile')) }}" alt="image">
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
            <form class="account-form verify-gcaptcha" method="POST" action="{{ route('user.login') }}">
                @csrf
                <div class="form-group">
                    <label class="text-white">@lang('Username')</label>
                    <div class="custom-icon-field">
                        <i class="las la-user fs-4"></i>
                        <input class="form--control" name="username" type="text" value="{{ old('username') }}" autocomplete="off" placeholder="@lang('Enter Username')" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="text-white">@lang('Password')</label>
                    <div class="custom-icon-field">
                        <i class="las la-key fs-4"></i>
                        <input class="form--control" name="password" type="password" placeholder="@lang('Enter password')" required>
                    </div>
                </div>

                <x-captcha />

                <div class="d-flex justify-content-between mt-3 flex-wrap">
                    <div class="form-group form-check">
                        <input class="form-check-input" id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                        <label class="text-white" for="remember">
                            @lang('Remember Me')
                        </label>
                    </div>
                    <p class="text-white"><i class="las la-lock"></i> <a class="text--base" href="{{ route('user.password.request') }}">@lang('Forgot password?')</a></p>
                </div>
                <button class="btn btn--base w-100" id="recaptcha" type="submit">@lang('Submit')</button>
            </form>
            <div class="account-footer text-center">
                <span class="text-white">@lang('Copyright') &copy; @php echo date('Y') @endphp. @lang('All Right Reserved')</span>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .form-check-input {
            margin-top: 0.45em !important;
        }
    </style>
@endpush
