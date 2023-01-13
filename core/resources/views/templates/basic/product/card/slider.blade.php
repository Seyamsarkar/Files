@php
    $class = 'product-two-slider ';
    if (@$view == 'two') {
        $class = 'more-product-slider';
    }
@endphp
<div class="custom-arrow {{ $class }}">
    @foreach ($products as $product)
        <div class="single-slide">
            <div class="product-card style--three v2">
                @if ($product->featured == 1)
                    <span class="tending-badge"><i class="las la-bolt"></i></span>
                @endif
                <div class="product-card__thumb">
                    <a href="{{ route('product.detail', [$product->id, slug($product->name)]) }}">
                        <img src="{{ getImage(getFilePath('product') . '/thumb_' . $product->featured_image) }}" alt="@lang('image')">
                    </a>
                </div>
                <div class="product-card__content">
                    <p class="mb-1">@lang('by')
                        <a class="text--base" href="{{ route('author.profile', $product->user->username) }}">{{ __($product->user->username) }}</a> @lang('in')
                        <a class="text--base" href="{{ route('category.products', [$product->category->id, slug($product->category->name)]) }}">{{ __($product->category->name) }}</a>
                    </p>
                    <h6 class="product-title mb-1"><a href="{{ route('product.detail', [$product->id, slug($product->name)]) }}">{{ strLimit(__($product->name), 32) }}</a></h6>
                    <div class="product-card__meta align-items-center">
                        <div class="left">
                            <ul class="meta-list">
                                <li class="product-sale-amount"><i class="las la-shopping-cart text--base"></i> <span class="text--base">{{ $product->total_sell }}</span> @lang('Sales')</li>
                                <li class="ratings">
                                    @php echo displayRating($product->avg_rating) @endphp
                                    ({{ $product->total_response }})
                                </li>
                            </ul>
                        </div>
                        <div class="right">
                            <h5 class="product-price">{{ $general->cur_sym }}{{ getAmount($product->regular_price) }}</h5>
                        </div>
                    </div>
                    <div class="product-card__btn-area">
                        <a class="cart-btn style--two" href="{{ route('product.detail', [$product->id, slug($product->name)]) }}"><i class="las la-shopping-cart"></i> @lang('Details')</a>
                        <a class="cart-btn" href="{{ $product->demo_link }}" target="_blank"><i class="las la-eye"></i> @lang('Preview')</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
