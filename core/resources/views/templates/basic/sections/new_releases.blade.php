@php
    $content = getContent('new_release.content', true);
    $products = App\Models\Product::available();
    
    if (request()->routeIs('all.subcategory')) {
        $products = $products->where('category_id', $category->id);
    }
    
    $products = $products
        ->with('category', 'user')
        ->limit(8)
        ->latest()
        ->get();
@endphp

<section class="pt-100 pb-100">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h3>{{ __(@$content->data_values->heading) }}</h3>
            @if (request()->routeIs('all.subcategory'))
                <a class="btn btn-outline--base btn-sm" href="{{ route('category.products', [$category->id, slug($category->name)]) }}"><i class="las la-arrow-circle-right"></i> @lang('View All')</a>
            @else
                <a class="btn btn-outline--base btn-sm" href="{{ route('products') }}"><i class="las la-arrow-circle-right"></i> @lang('View All')</a>
            @endif
        </div>
        <div class="row gy-4 justify-content-center">
            @include($activeTemplate . 'product.card.grid', ['products' => $products])
        </div>
    </div>
</section>
