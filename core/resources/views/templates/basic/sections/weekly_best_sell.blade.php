@php
    $content = getContent('weekly_best_sell.content', true);
    
    $dates = weeklyDates();
    
    $products = App\Models\Product::available();
    
    if (request()->routeIs('all.subcategory')) {
        $products = $products->where('category_id', $category->id);
    }
    
    $products = $products
        ->whereHas('sells', function ($sell) use ($dates) {
            $sell->whereBetween('created_at', [$dates[0], $dates[1]]);
        })
        ->withCount([
            'sells' => function ($sell) use ($dates) {
                $sell->whereBetween('created_at', [$dates[0], $dates[1]]);
            },
        ])
        ->latest('sells_count')
        ->limit(8)
        ->with(['category', 'user'])
        ->get();
@endphp

@if ($products->count() > 0)
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h3>{{ __(@$content->data_values->heading) }}</h3>
                @if (request()->routeIs('all.subcategory'))
                    <a class="btn btn-outline--base btn-sm" href="{{ route('category.weekly.best.products', [$category->id, slug($category->name)]) }}"><i class="las la-arrow-circle-right"></i> @lang('View All')</a>
                @else
                    <a class="btn btn-outline--base btn-sm" href="{{ route('weekly.best.products') }}"><i class="las la-arrow-circle-right"></i> @lang('View All')</a>
                @endif
            </div>
            <div class="row gy-4 justify-content-center">
                @include($activeTemplate . 'product.card.grid', ['products' => $products])
            </div>
        </div>
    </section>
@endif
