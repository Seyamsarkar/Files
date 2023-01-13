@php
    $content = getContent('best_sell_product.content', true);
    
    $products = App\Models\Product::available();
    
    if (request()->routeIs('all.subcategory')) {
        $products = $products->where('category_id', $category->id);
    }
    
    $products = $products
        ->where('total_sell', '>', 0)
        ->latest('total_sell')
        ->limit(12)
        ->with(['category', 'user'])
        ->get();
@endphp
@if ($products->count() > 0)
    <section class="pt-100 pb-100 px-xxl-5 bg_img" style="background-image: url({{ getImage($activeTemplateTrue . 'images/bg2.jpg') }});">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6 wow fadeInLeft mb-lg-3 mb-3" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <div class="section-header text-lg-start mb-0 text-center">
                        <h2 class="section-title">{{ __(@$content->data_values->heading) }}</h2>
                        <p class="mt-2">{{ __(@$content->data_values->subheading) }}</p>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end text-center">
                    @if (request()->routeIs('all.subcategory'))
                        <a class="btn btn-outline--base btn-sm" href="{{ route('category.best.selling.products', [$category->id, slug($category->name)]) }}"><i class="las la-arrow-circle-right"></i> @lang('View All')</a>
                    @else
                        <a class="btn btn-outline--base btn-sm" href="{{ route('best.selling.products') }}"><i class="las la-arrow-circle-right"></i> @lang('View All')</a>
                    @endif
                </div>
            </div>
            @include($activeTemplate . 'product.card.slider', ['products' => $products])
        </div>
    </section>
@endif
