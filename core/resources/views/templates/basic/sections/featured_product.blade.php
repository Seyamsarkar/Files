@php
    $content = getContent('featured_product.content', true);
    $products = App\Models\Product::available();
    
    if (request()->routeIs('all.subcategory')) {
        $products = $products->where('category_id', @$category->id);
    }
    
    $products = $products
        ->featured()
        ->limit(8)
        ->latest()
        ->with('category', 'user')
        ->get();
@endphp
@if ($products->count())
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section-header text-center">
                        <h2 class="section-title">{{ __(@$content->data_values->heading) }}</h2>
                        <p class="mt-2">{{ __(@$content->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
            <div class="row gy-4 justify-content-center">
                @include($activeTemplate . 'product.card.grid', ['products' => $products])
            </div>
            <div class="mt-5 text-center">
                @if (request()->routeIs('all.subcategory'))
                    <a class="btn btn-outline--base" href="{{ route('category.featured.products', [$category->id, slug($category->name)]) }}"><i class="las la-arrow-circle-right"></i> @lang('View All')</a>
                @else
                    <a class="btn btn-outline--base" href="{{ route('featured.products') }}"><i class="las la-arrow-circle-right"></i> @lang('View All')</a>
                @endif
            </div>
        </div>
    </section>
@endif
