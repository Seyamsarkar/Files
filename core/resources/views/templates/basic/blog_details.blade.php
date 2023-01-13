@extends($activeTemplate . 'layouts.frontend')
@section('content')
<section class="blog-details-section pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="blog-details-wrapper">
                    <div class="blog-details__thumb">
                        <img src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->image, '860x440') }}" alt="@lang('image')">
                        <div class="post__date">
                            <span class="date">{{ showDateTime(@$blog->data_values->created_at, 'd') }}</span>
                            <span class="month">{{ showDateTime(@$blog->data_values->created_at, 'M') }}</span>
                        </div>
                    </div>
                    <div class="blog-details__content">
                        <h4 class="blog-details__title mb-3">{{ __(@$blog->data_values->title) }}</h4>
                        @php echo __(@$blog->data_values->description) @endphp
                    </div>
                    <div class="blog-details__footer">
                        <h4 class="caption">@lang('Share This Post')</h4>

                        <ul class="social__links">
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="https://twitter.com/intent/tweet?text={{ __(@$blog->data_values->title) }}%0A{{ url()->current() }}"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ __(@$blog->data_values->title) }}&media={{ getImage('assets/images/frontend/blog/' . $blog->data_values->image, '860x440') }}"><i class="fab fa-pinterest-p"></i></a></li>
                            <li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __(@$blog->data_values->title) }}&amp;summary={{ __(@$blog->data_values->description) }}"><i class="fab fa-linkedin-in"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="fb-comments" data-href="{{ route('blog.details', [$blog->id, slug($blog->data_values->title)]) }}" data-numposts="5"></div>

            </div>
            <div class="col-lg-4">
                <div class="sidebar">
                    <div class="widget">
                        <h5 class="widget__title">@lang('Recent Post')</h5>
                        <ul class="small-post-list">
                            @foreach ($latestBlogs as $latestBlog)
                            <li class="small-post">
                                <div class="small-post__thumb"><img src="{{ getImage('assets/images/frontend/blog/thumb_' . @$latestBlog->data_values->image, '430x220') }}" alt="image"></div>
                                <div class="small-post__content">
                                    <h5 class="post__title"><a href="{{ route('blog.details', [slug(@$latestBlog->data_values->title), $latestBlog->id]) }}">{{ __(@$latestBlog->data_values->title) }}</a></h5>
                                    <p class="fs-14px mt-1">{{ $latestBlog->created_at->format('d M, Y') }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('fbComment')
@php echo loadExtension('fb-comment') @endphp
@endpush