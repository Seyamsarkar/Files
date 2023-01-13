@php
    $content = getContent('latest_product.content', true);
    
    $products = App\Models\Product::available()
        ->with(['category', 'user'])
        ->limit(8)
        ->latest()
        ->get();
    
    $categories = App\Models\Category::active()
        ->featured()
        ->whereHas('products', function ($q) {
            $q->available();
        })
        ->with([
            'products' => function ($q) {
                $q->available()->limit(8);
            },
            'products.category',
            'products.user',
        ])
        ->latest()
        ->get();
@endphp
<section class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __(@$content->data_values->heading) }}</h2>
                    <p class="mt-2">{{ __(@$content->data_values->subheading) }}</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mb-50">
            <div class="col-lg-12">
                <ul class="nav nav-tabs custom--nav-tabs gap-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all-tab" type="button" role="tab">@lang('All Categories')</button>
                    </li>
                    @foreach ($categories as $category)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#{{ str_replace(' ', '_', strtolower($category->name)) }}" type="button" role="tab">{{ __($category->name) }}</button>
                        </li>
                    @endforeach

                </ul>
            </div>
        </div>

        <div class="tab-content mt-5">
            <div class="tab-pane fade show active" id="all-tab" role="tabpanel">
                <div class="row gy-4 justify-content-center">
                    @include($activeTemplate . 'product.card.grid', ['products' => $products])
                </div>
            </div>

            @foreach ($categories as $category)
                <div class="tab-pane fade" id="{{ str_replace(' ', '_', strtolower($category->name)) }}" role="tabpanel">
                    @php
                        $products = $category->products;
                    @endphp
                    <div class="row gy-4 justify-content-center">
                        @include($activeTemplate . 'product.card.grid', ['products' => $products])
                    </div>
                </div>
            @endforeach
        </div>
        @if ($products->count())
            <div class="mt-5 text-center">
                <a class="btn btn-outline--base" href="{{ route('products') }}"><i class="las la-arrow-circle-right"></i> @lang('View All')</a>
            </div>
        @endif
    </div>
</section>
