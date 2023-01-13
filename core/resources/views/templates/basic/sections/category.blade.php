@php
    $categories = App\Models\Category::active()
        ->featured()
        ->limit(6)
        ->get();
    $content = getContent('category.content', true);
@endphp

<div class="category-area">
    <div class="container">
        <div class="category-wrapper">
            <div class="d-flex justify-content-center justify-content-md-between align-items-center mb-4 flex-wrap gap-3">
                <h3 class="m-0">{{ __(@$content->data_values->heading) }}</h3>
                <a class="btn btn-outline--base btn-sm ms-sm-auto flex-shrink-0" href="{{ route('all.category') }}"><i class="las la-arrow-circle-right"></i> @lang('View All')</a>
            </div>
            <div class="row gy-3 justify-content-center">
                @foreach ($categories as $category)
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
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
</div>
