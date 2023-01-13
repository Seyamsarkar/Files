@php
    $content = getContent('testimonial.content', true);
    $testimonials = getContent('testimonial.element', false, null, true);
@endphp

<section class="pt-100 pb-100 bg_img" style="background-image: url({{ getImage($activeTemplateTrue . '/images/bg2.jpg') }});">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __(@$content->data_values->heading) }}</h2>
                    <p class="mt-3">{{ __(@$content->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="testimonial-slider">
            @foreach ($testimonials as $testimonial)
                <div class="single-slide">
                    <div class="testimonial-card">
                        <div class="testimonial-card__top d-flex align-items-center flex-wrap">
                            <div class="client-thumb">
                                <img src="{{ getImage('assets/images/frontend/testimonial/' . @$testimonial->data_values->image, '70x70') }}" alt="@lang('image')">
                            </div>
                            <div class="content">
                                <h6 class="name">{{ __(@$testimonial->data_values->name) }}</h6>
                                <span class="designation fs-14px text--base">{{ __(@$testimonial->data_values->designation) }}</span>
                            </div>
                        </div>
                        <p class="mt-3">{{ __(@$testimonial->data_values->quote) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
