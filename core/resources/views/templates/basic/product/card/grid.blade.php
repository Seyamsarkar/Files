@forelse($products as $product)
    <div class="col-xl-3 col-md-6">
        <div class="product-card style--three p-0">
            @if ($product->featured == 1)
                <span class="tending-badge"><i class="las la-bolt"></i></span>
            @endif
            <div class="product-card__thumb">
                <a href="{{ route('product.detail', [$product->id, slug($product->name)]) }}">
                    <img src="{{ getImage(getFilePath('product') . '/thumb_' . $product->featured_image) }}" alt="@lang('product-image')">
                </a>
            </div>
            <div class="product-card__content bg-white">
                <p class="mb-1">@lang('by')
                    <a class="text--base" href="{{ route('author.profile', $product->user->username) }}">{{ __($product->user->username) }}</a> @lang('in')
                    <a class="text--base" href="{{ route('category.products', [$product->category->id, slug($product->category->name)]) }}">{{ __($product->category->name) }}</a>
                </p>
                <h6 class="product-title mb-1"><a href="{{ route('product.detail', [$product->id, slug($product->name)]) }}">{{ strLimit(__($product->name), 32) }}</a></h6>
                <div class="product-card__meta">
                    <div class="left">
                        <h5 class="product-price mb-3">{{ $general->cur_sym }}{{ getAmount($product->regular_price) }}</h5>
                        <ul class="meta-list">
                            <li class="product-sale-amount"><i class="las la-shopping-cart text--base"></i> <span class="text--base">{{ $product->total_sell }}</span> @lang('Sales')</li>
                        </ul>
                    </div>
                    <div class="right">
                        <a class="cart-btn" href="{{ route('product.detail', [$product->id, slug($product->name)]) }}"><i class="las la-shopping-cart"></i> @lang('Purchase')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-md-6 col-lg-8 ms-auto">
        <img src="{{ getImage('assets/images/frontend/empty_message/' . @$emptyMsgImage->data_values->image, '400x300') }}" alt="">
    </div>
@endforelse
