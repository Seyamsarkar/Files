@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100">
        <div class="category-area">
            <div class="container">
                <div class="row gy-3 justify-content-center">
                    @foreach ($categories as $category)
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                            <div class="category-item has-link">
                                <a class="item-link" href="{{ route('all.subcategory', [$category->id, slug($category->name)]) }}"></a>
                                <div class="category-item__icon">
                                    <img src="{{ getImage(getFilePath('category') . '/' . $category->image, getFileSize('category')) }}" alt="image">
                                </div>
                                <div class="category-item__content">
                                    <h6 class="caption">{{ __($category->name) }}</h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @include($activeTemplate . 'sections.weekly_best_sell')
    @include($activeTemplate . 'sections.new_releases')
    @include($activeTemplate . 'sections.best_sell_product')
@endsection
