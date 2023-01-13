@php
    $content = getContent('featured_author.content', true);
    $featuredAuthor = App\Models\Featured::latest()->first();
@endphp

@if (@$featuredAuthor && @$featuredAuthor->user->status == Status::USER_ACTIVE)
    @php
        $products = App\Models\Product::available()
            ->where('user_id', $featuredAuthor->user_id)
            ->latest()
            ->limit(4)
            ->with(['category', 'user'])
            ->get();
    @endphp
    <section class="pt-100 pb-100 bg__img" style="background-image: url({{ getImage($activeTemplateTrue . 'images/bg3.jpg') }});">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 mb-5">
                    <h2 class="section-title mb-4">{{ __(@$content->data_values->heading) }}</h2>
                    <div class="row gy-3 align-items-center">
                        <div class="col-lg-8">
                            <div class="featured-author d-flex align-items-center flex-wrap">
                                <div class="thumb">
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $featuredAuthor->user->image, getFileSize('userProfile')) }}" alt="image">
                                </div>
                                <div class="content">
                                    <h4 class="title">{{ $featuredAuthor->user->username }}</h4>
                                    <p>{{ @$featuredAuthor->user->address->country }}, @lang('Member since') {{ showDateTime($featuredAuthor->user->created_at, 'F, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a class="btn-sm btn--base mt-2" href="{{ route('author.profile', $featuredAuthor->user->username) }}">@lang('View Author Profile')</a>
                        </div>
                    </div>
                </div>
                @if ($products->count())
                    <div class="col-lg-12">
                        <div class="row gy-4 justify-content-center">
                            @include($activeTemplate . 'product.card.grid', ['products' => $products])
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
