@forelse($products as $product)
    <div class="col-md-6 card-view">
        <div class="product-card">
            @if ($product->featured == 1)
                <span class="tending-badge"><i class="las la-bolt"></i></span>
            @endif
            <div class="product-card__thumb">
                <a href="{{ route('product.detail', [$product->id, slug(__($product->name))]) }}">
                    <img src="{{ getImage(getFilePath('product') . '/thumb_' . $product->featured_image) }}" alt="@lang('image')">
                </a>
            </div>
            <div class="product-card__content">
                <h6 class="product-title mb-1"><a href="{{ route('product.detail', [$product->id, slug(__($product->name))]) }}">{{ strLimit(__($product->name), 40) }}</a></h6>
                <p>@lang('by')
                    <a href="{{ route('author.profile', $product->user->username) }}">{{ @$product->user->username }}</a> @lang('in')
                    <a href="{{ route('category.products', [@$product->category->id, slug(@$product->category->name)]) }}">{{ __(@$product->category->name) }}</a>
                </p>
                <div class="product-card__meta">
                    <div class="left">
                        <p class="mb-1">@lang('Last Updated') - {{ showDateTime($product->updated_at, 'd M Y') }}</p>
                        <ul class="meta-list">
                            <li class="product-sale-amount">
                                <i class="las la-shopping-cart text--base"></i> <span class="text--base">{{ $product->total_sell }}</span> @lang('Sales')
                            </li>
                            <li class="ratings">
                                @php echo displayRating($product->avg_rating) @endphp
                                ({{ $product->total_response }})
                            </li>
                        </ul>
                    </div>
                    <div class="right">
                        <h5 class="product-price mb-2 text-center">{{ __($general->cur_sym) }}{{ getAmount($product->regular_price) }}</h5>
                        <a class="cart-btn" href="{{ route('product.detail', [$product->id, slug(__($product->name))]) }}"><i class="las la-shopping-cart"></i> @lang('Purchase')</a>
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
@if ($products->hasPages())
    {{ paginateLinks($products) }}
@endif
