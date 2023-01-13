@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 mb-lg-0 mb-3">
                    <button class="action-sidebar-open"><i class="las la-sliders-h"></i> @lang('Filter')</button>
                    <div class="action-sidebar">
                        <button class="action-sidebar-close" type="button"><i class="las la-times"></i></button>
                        <div class="action-widget action-widget-responsive">
                            <div class="input-group">
                                <input class="form--control mySearch" name="search" type="text" value="{{ request()->search }}" placeholder="@lang('Search here')...">
                                <button class="input-group-text searchBtn" type="button"><i class="las la-search"></i></button>
                            </div>
                        </div>
                        @if (@$allCategory)
                            <div class="action-widget mt-3">
                                <h6 class="action-widget__title">@lang('Categories')</h6>
                                <div class="action-widget__body">
                                    <div class="d-flex justify-content-between flex-wrap">
                                        <div class="form-check custom--checkbox mb-0">
                                            <input class="form-check-input sortCategory" id="category0" name="category" type="checkbox" value="" checked>
                                            <label class="form-check-label" for="category0">@lang('All Categories')</label>
                                        </div>
                                    </div>
                                    @foreach ($allCategory as $category)
                                        <div class="d-flex justify-content-between flex-wrap">
                                            <div class="form-check custom--checkbox mb-0">
                                                <input class="form-check-input sortCategory" id="category{{ @$category->id }}" name="category" type="checkbox" value="{{ @$category->id }}">
                                                <label class="form-check-label" for="category{{ @$category->id }}">{{ __(@$category->name) }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (@$subcategories)
                            <div class="action-widget mt-3">
                                <h6 class="action-widget__title">@lang('Filter by categories')</h6>
                                <div class="action-widget__body">
                                    <div class="form-check custom--checkbox mb-0">
                                        <input class="form-check-input sortSubcategory" id="subcategory0" name="subcategory" type="checkbox" value="" checked>
                                        <label class="form-check-label" for="subcategory0">@lang('All Categories')</label>
                                    </div>
                                    @foreach (@$subcategories as $subcategory)
                                        <div class="form-check custom--checkbox mb-0">
                                            <input class="form-check-input sortSubcategory" id="subcategory{{ @$subcategory->id }}" name="subcategory" type="checkbox" value="{{ @$subcategory->id }}">
                                            <label class="form-check-label" for="subcategory{{ @$subcategory->id }}">{{ __(@$subcategory->name) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="action-widget mt-3">
                            <h6 class="action-widget__title">@lang('Tags')</h6>
                            <div class="action-widget__body scroll--active __tag_wrapper">
                                <div class="form-check custom--checkbox">
                                    <input class="form-check-input sortTag" id="tag" name="tag" type="checkbox" value="" checked>
                                    <label class="form-check-label" for="tag">
                                        @lang('All')
                                    </label>
                                </div>
                                @foreach ($tags as $tag)
                                    <div class="form-check custom--checkbox">
                                        <input class="form-check-input sortTag" id="tag{{ $loop->index }}" name="tag" type="checkbox" value="{{ $tag }}">
                                        <label class="form-check-label" for="tag{{ $loop->index }}">
                                            {{ __($tag) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="action-widget mt-3">
                            <h6 class="action-widget__title">@lang('Order By')</h6>
                            <div class="action-widget__body">
                                <div class="form-check custom--checkbox mb-0">
                                    <input class="form-check-input sortProduct" id="latest" name="sort" type="radio" value="id_desc">
                                    <label class="form-check-label" for="latest">
                                        @lang('Latest')
                                    </label>
                                </div>
                                <div class="form-check custom--checkbox mb-0">
                                    <input class="form-check-input sortProduct" id="low_to_high" name="sort" type="radio" value="price_asc">
                                    <label class="form-check-label" for="low_to_high">
                                        @lang('Low to High')
                                    </label>
                                </div>
                                <div class="form-check custom--checkbox mb-0">
                                    <input class="form-check-input sortProduct" id="high_to_low" name="sort" type="radio" value="price_desc">
                                    <label class="form-check-label" for="high_to_low">
                                        @lang('High to Low')
                                    </label>
                                </div>
                                <div class="form-check custom--checkbox mb-0">
                                    <input class="form-check-input sortProduct" id="best_selling" name="sort" type="radio" value="totalSell_desc">
                                    <label class="form-check-label" for="best_selling">
                                        @lang('Best Selling')
                                    </label>
                                </div>
                                <div class="form-check custom--checkbox mb-0">
                                    <input class="form-check-input sortProduct" id="best_rating" name="sort" type="radio" value="totalReview_desc">
                                    <label class="form-check-label" for="best_rating">
                                        @lang('Best Rating')
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="action-widget mt-3">
                            <h6 class="action-widget__title">@lang('Rating')</h6>
                            <div class="action-widget__body" style="">

                                <div class="form-check custom--radio d-flex justify-content-between align-items-center">
                                    <div class="left">
                                        <input class="form-check-input sortRating" id="ratings-0" name="star" type="radio" value="">
                                        <label class="form-check-label" for="ratings-0">@lang('All') </label>
                                    </div>
                                </div>

                                <div class="form-check custom--radio d-flex justify-content-between align-items-center">
                                    <div class="left">
                                        <input class="form-check-input sortRating" id="ratings-4" name="star" type="radio" value="4">
                                        <label class="form-check-label" for="ratings-4">
                                            <span class="text--warning">
                                                @php
                                                    echo displayRating(4);
                                                @endphp
                                            </span> & @lang('up')
                                        </label>
                                    </div>
                                </div>
                                <div class="form-check custom--radio d-flex justify-content-between align-items-center">
                                    <div class="left">
                                        <input class="form-check-input sortRating" id="ratings-3" name="star" type="radio" value="3">
                                        <label class="form-check-label" for="ratings-3">
                                            <span class="text--warning">
                                                @php
                                                    echo displayRating(3);
                                                @endphp
                                            </span> & @lang('up')

                                        </label>
                                    </div>
                                </div>
                                <div class="form-check custom--radio d-flex justify-content-between align-items-center">
                                    <div class="left">
                                        <input class="form-check-input sortRating" id="ratings-2" name="star" type="radio" value="2">
                                        <label class="form-check-label" for="ratings-2">
                                            <span class="text--warning">
                                                @php
                                                    echo displayRating(2);
                                                @endphp
                                            </span> & @lang('up')
                                        </label>
                                    </div>
                                </div>
                                <div class="form-check custom--radio d-flex justify-content-between align-items-center">
                                    <div class="left">
                                        <input class="form-check-input sortRating" id="ratings-1" name="star" type="radio" value="1">
                                        <label class="form-check-label" for="ratings-1">
                                            <span class="text--warning">
                                                @php
                                                    echo displayRating(1);
                                                @endphp
                                            </span> & @lang('up')
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="action-widget mt-3">
                            <h6 class="action-widget__title">@lang('Filter by price')</h6>
                            <div class="action-widget__body">
                                <div class="filter-price-widget pt-2">
                                    <div id="slider-range"></div>
                                    <div class="price-range">
                                        <label class="form-check-label" for="amount">@lang('Price :')</label>
                                        <input id="amount" type="text" readonly>
                                        <input name="min_price" type="hidden" value="{{ getAmount($minPrice) }}">
                                        <input name="max_price" type="hidden" value="{{ getAmount($maxPrice) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <p>
                            @if (request()->search)
                                @lang('Search Result For')
                                <span class="text--base">{{ __(request()->search) }}</span> :
                            @endif
                            @lang('Total') <span class="text--base total-product-count">{{ $totalProduct }} </span> @lang('products found')
                        </p>
                        <ul class="top__bar-left">
                            <li class="list-view-btn active" id="list-item">
                                <i class="fas fa-th-list"></i>
                            </li>
                            <li class="grid-view-btn" id="box-item">
                                <i class="fas fa-th-large"></i>
                            </li>
                        </ul>
                    </div>
                    <div class="main-content position-relative">
                        <div class="loader-wrapper">
                            <div class="loader-pre"></div>
                        </div>
                        <div class="row gy-4 card-view-area list-view" id="products">
                            @include($activeTemplate . 'product.card.list', ['products' => $products])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            let page = null;
            $('.loader-wrapper').addClass('d-none');
            let totalProduct = $("#products").find('.card-view').length;

            $('.sortProduct, .sortCategory,.sortSubcategory, .sortRating, .sortTag').on('click', function() {
                $('#category0').removeAttr('checked', 'checked');
                if ($('#category0').is(':checked')) {
                    $("input[type='checkbox'][name='category']").not(this).prop('checked', false);
                }
                if ($("input[type='checkbox'][name='category']:checked").length == 0) {
                    $('#category0').attr('checked', 'checked');
                }

                $('#subcategory0').removeAttr('checked', 'checked');
                if ($('#subcategory0').is(':checked')) {
                    $("input[type='checkbox'][name='subcategory']").not(this).prop('checked', false);
                }
                if ($("input[type='checkbox'][name='subcategory']:checked").length == 0) {
                    $('#subcategory0').attr('checked', 'checked');
                }


                if ($('#tag').is(':checked')) {
                    $("input[type='checkbox'][name='tag']").not(this).prop('checked', false);
                }
                if ($("input[type='checkbox'][name='tag']:checked").length == 0) {
                    $('#tag').attr('checked', 'checked');
                }

                if ($('#ratings-0').is(':checked')) {
                    $("input[type='radio'][name='star']").not(this).prop('checked', false);
                }
                page = null;
                fetchProduct();
            });

            $('.productPaginate').on('change', function() {
                page = null;
                fetchProduct();
            });

            $('.searchBtn').on('click', function() {
                $(this).attr('disabled', 'disabled');
                page = null;
                fetchProduct();
            });

            $("#slider-range").slider({
                range: true,
                min: {{ $minPrice }},
                max: {{ $maxPrice }},
                values: [{{ $minPrice }}, {{ $maxPrice }}],
                slide: function(event, ui) {
                    $("#amount").val("{{ $general->cur_sym }}" + ui.values[0] + " - {{ $general->cur_sym }}" + ui.values[1]);
                    $('input[name=min_price]').val(ui.values[0]);
                    $('input[name=max_price]').val(ui.values[1]);
                },
                change: function() {
                    $('.loader-wrapper').removeClass('d-none')
                    page = null;
                    fetchProduct();
                }
            });
            $("#amount").val("{{ $general->cur_sym }}" + $("#slider-range").slider("values", 0) + " - {{ $general->cur_sym }}" + $("#slider-range").slider("values", 1));

            function fetchProduct() {
                $('.loader-wrapper').removeClass('d-none');
                let data = {};

                data.categories = [];
                $.each($("[name=category]:checked"), function() {
                    if ($(this).val()) {
                        data.categories.push($(this).val());
                    }
                });

                data.tags = [];
                $.each($("[name=tag]:checked"), function() {
                    if ($(this).val()) {
                        data.tags.push($(this).val());
                    }
                });

                data.search = $('.mySearch').val();
                data.sort = $('.sortProduct:checked').val();
                data.rating = $('.sortRating:checked').val();
                data.min = $('input[name="min_price"]').val();
                data.max = $('input[name="max_price"]').val();
                data.paginate = $('.productPaginate').find(":selected").val();
                data.categoryId = "{{ @$categoryId }}";
                data.subcategoryId = "{{ @$subcategoryId }}";
                data.route = "{{ request()->route()->getname() }}";

                let url = `{{ route('products.filter') }}`;
                if (page) {
                    url = `{{ route('products.filter') }}?page=${page}`;
                }
                $.ajax({
                    method: "GET",
                    url: url,
                    data: data,
                    success: function(response) {
                        $('.searchBtn').removeAttr('disabled', 'disabled');
                        $('#products').html(response.view);
                        totalProduct = response.totalProduct;
                        $('.total-product-count').text(totalProduct)
                    }
                }).done(function() {
                    $('.loader-wrapper').addClass('d-none')
                });
            }
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                page = $(this).attr('href').split('page=')[1];
                fetchProduct();
            });
        })(jQuery)
    </script>
@endpush
