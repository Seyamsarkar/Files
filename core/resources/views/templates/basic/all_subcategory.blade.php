@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100">
        <div class="category-area">
            <div class="container">
                <div class="row gy-3 justify-content-center">
                    @foreach ($category->subcategories as $subcategory)
                        <div class="col-lg-3 col-md-4 col-6">
                            <div class="subcategory-item has-link pb-3">
                                <a class="item-link" href="{{ route('subcategory.products', [$subcategory->id, slug($subcategory->name)]) }}"></a>
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="subcategory-name">{{ __($subcategory->name) }}</h6>
                                    <i class="las la-angle-right d-sm-block d-none"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
    </section>
    @include($activeTemplate . 'sections.featured_product')
    @include($activeTemplate . 'sections.weekly_best_sell')
    @include($activeTemplate . 'sections.new_releases')
    @include($activeTemplate . 'sections.best_sell_product')
@endsection
