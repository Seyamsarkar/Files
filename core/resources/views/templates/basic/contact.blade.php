@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $content = getContent('contact_us.content', true);
    @endphp
    <section class="pt-100 pb-100 bg--gradient">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="section-header text-center">
                        <span class="subtitle fw-bold text--base font-size--18px border-left">{{ __(@$content->data_values->heading) }}</span>
                        <h2 class="section-title">{{ __(@$content->data_values->subheading) }}</h2>
                    </div>
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-7">
                    <form class="contact-form verify-gcaptcha" method="post" action="">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>@lang('Name') </label>
                                <div class="custom-icon-field">
                                    <i class="las la-user"></i>
                                    <input class="form-control form--control" name="name" type="text" value="@if (auth()->user()){{ auth()->user()->fullname }}@else{{ old('name') }}@endif" @if (auth()->user()) readonly @endif placeholder="@lang('Enter Name')" required>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Email') </label>
                                <div class="custom-icon-field">
                                    <i class="las la-envelope"></i>
                                    <input class="form-control form--control" name="email" type="email" value="@if (auth()->user()){{ auth()->user()->email }}@else{{ old('email') }} @endif" @if (auth()->user()) readonly @endif placeholder="@lang('Enter Email')" required>
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <label>@lang('Subject') </label>
                                <div class="custom-icon-field">
                                    <i class="las la-clipboard-list"></i>
                                    <input class="form-control form--control" name="subject" type="text" value="{{ old('subject') }}" placeholder="@lang('Enter Subject')" required>
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <label>@lang('Message') </label>
                                <div class="custom-icon-field">
                                    <textarea class="form-control form--control" name="message" placeholder="@lang('Your message')" required>{{ old('message') }}</textarea>
                                    <i class="las la-sms"></i>
                                </div>
                            </div>
                            <x-captcha />
                            <div class="col-lg-12">
                                <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-5 ps-lg-4">
                    <div class="map-area">
                        <iframe src="https://maps.google.com/maps?q={{ __(@$content->data_values->latitude) }},{{ __(@$content->data_values->longitude) }}&hl=es;z=14&amp;output=embed"></iframe>
                    </div>
                </div>
            </div>
            <div class="row gy-4 mt-lg-5 mt-4">
                <div class="col-md-4">
                    <div class="single-info d-flex align-items-center flex-wrap">
                        <div class="single-info__icon d-flex justify-content-center align-items-center rounded-3 text-white">
                            <i class="las la-map-marked-alt"></i>
                        </div>
                        <div class="single-info__content">
                            <h4 class="title">@lang('Office Address')</h4>
                            <p class="mt-2">{{ __(@$content->data_values->contact_details) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single-info d-flex align-items-center flex-wrap">
                        <div class="single-info__icon d-flex justify-content-center align-items-center rounded-3 text-white">
                            <i class="las la-envelope"></i>
                        </div>
                        <div class="single-info__content">
                            <h4 class="title">@lang('Email Address')</h4>
                            <a class="text--base mt-2" href="mailto:{{ @$content->data_values->email_address }}">{{ __(@$content->data_values->email_address) }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single-info d-flex align-items-center flex-wrap">
                        <div class="single-info__icon d-flex justify-content-center align-items-center rounded-3 text-white">
                            <i class="las la-phone"></i>
                        </div>
                        <div class="single-info__content">
                            <h4 class="title">@lang('Contact No')</h4>
                            <a class="text--base mt-2" href="tel:{{ @$content->data_values->contact_number }}">{{ __(@$content->data_values->contact_number) }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
