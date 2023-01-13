@php
    $banner = getContent('banner.content', true);
@endphp

<div class="hero bg_img" style="background-image: url({{ getImage('assets/images/frontend/banner/' . @$banner->data_values->image, '1920x1500') }});">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 text-center">
                <h2 class="hero__title wow fadeInUp mb-2" data-wow-duration="0.5s" data-wow-delay="0.3s">{{ __(@$banner->data_values->heading) }}</h2>
                <p class="wow fadeInUp text-white" data-wow-duration="0.5s" data-wow-delay="0.5s">{{ __(@$banner->data_values->subheading) }}</p>
            </div>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-xl-7 col-lg-8">
                <form class="hero-search-form wow fadeInUp" data-wow-duration="0.7s" data-wow-delay="0.7s" method="GET" action="{{ route('products') }}">
                    <i class="las la-search icon"></i>
                    <input class="form--control" id="hero-search-field" name="search" type="text" placeholder="@lang('e.g. php script')">
                    <button class="hero-search-btn" type="submit">@lang('Search')</button>
                </form>
            </div>
        </div>
    </div>
</div>
