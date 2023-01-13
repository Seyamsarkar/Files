@php
$content = getContent('blog.content', true);
$blogs = getContent('blog.element', false, 3, true);
@endphp

<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __(@$content->data_values->heading) }}</h2>
                    <p class="mt-3">{{ __(@$content->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row gy-4 justify-content-center">
            @foreach ($blogs as $blog)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <div class="post-card">
                        <div class="post-card__thumb">
                            <img src="{{ getImage('assets/images/frontend/blog/thumb_' . @$blog->data_values->image, '430x220') }}" alt="@lang('image')">
                            <ul class="post-meta">
                                <li><i class="las la-calendar"></i> {{ showDateTime(@$blog->data_values->created_at, 'd M, Y') }}</li>
                            </ul>
                        </div>
                        <div class="post-card__content">
                            <h5 class="post-card__title mb-3">
                                <a href="{{ route('blog.details', [slug(@$blog->data_values->title), $blog->id]) }}">
                                    {{ __(@$blog->data_values->title) }}
                                </a>
                            </h5>
                            <p>@php echo strLimit(strip_tags(__(@$blog->data_values->description)), 90) @endphp</p>
                            <a class="read-more mt-2" href="{{ route('blog.details', [slug(@$blog->data_values->title), $blog->id]) }}">@lang('Read More')</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
