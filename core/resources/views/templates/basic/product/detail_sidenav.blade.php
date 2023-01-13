<div class="col-lg-4 ps-lg-4 mt-lg-0 mt-5">
    <div class="product-details-sidebar">
        <div class="product-widget">
            <form class="product-price-form" action="@if (!(@$buyAuthenticate[0] && @$buyAuthenticate[1])) {{ route('add.to.cart', Crypt::encrypt($product->id)) }} @endif" method="post">
                @csrf
                <select class="form-control form--control form-select w-100 license-selectBox" name="license">
                    <option value="" selected disabled>@lang('Select License')</option>
                    <option data-resource="{{ $product }}" value="1" @disabled(@$buyAuthenticate[0] == 1)>@lang('Regular License')</option>
                    <option data-resource="{{ $product }}" value="2" @disabled(@$buyAuthenticate[1] == 2)>@lang('Extended License')</option>
                </select>
                <h5 class="mt-3 mb-2">@lang('Product Price') <span class="float-end" id="product-price"></span></h5>
                <p><i class="fas fa-check"></i> @lang('Quality checked by') <b><i>{{ $general->site_name }}</i></b></p>
                <p><i class="fas fa-check"></i> @lang('Future updates')</p>
                @if ($product->support == Status::YES)
                    <p><i class="fas fa-check"></i> @lang('6 months support from')
                        <a class="text--base" href="javascript:void(0)"><i>{{ @$product->user->username }}</i></a>
                    </p>
                    <a class="text--base mt-2" type="button" href="{{ route('product.support') }}">@lang('What does support include?')</a>
                    <div class="form-group form-check custom--checkbox mt-3 mb-0">
                        <input class="form-check-input extendSupport" id="extendSupport" name="extended_support" type="checkbox">
                        <label class="form-check-label" for="extendSupport">@lang('Extend Support for 12 months') <b class="text--dark" id="extendSupportShow"></b></label>
                    </div>
                    @if ($product->support_discount)
                        <a class="text--base discount-calc" data-resource="{{ $product }}" type="button" href="javascript:void(0)">
                            <i class="fas fa-tags"></i> @lang('Get it now and save up to') <span id="support-discount"></span>
                        </a>
                    @endif
                @else
                    <p>
                        <i class="fas fa-times"></i> @lang('Item support is not offered by the')
                        <span class="text--base"><i>{{ @$product->user->username }}</i></span>, @lang('for this item')
                    </p>
                    <p>
                        <i class="fas fa-times"></i> @lang('Support is not included in the price of purchase and support extensions are not available for this item')
                    </p>
                @endif
                @if ($product->user_id != auth()->id())
                    @if (@$buyAuthenticate[0] == 1 && @$buyAuthenticate[1] == 2)
                        <button class="btn btn--base w-100 disabled mt-3" type="button"> @lang('Already Purchased')</button>
                    @else
                        <button class="btn btn--base w-100 mt-3" type="submit"><i class="las la-cart-arrow-down fs-5"></i> @lang('Add To Cart')</button>
                    @endif
                @endif
            </form>
        </div>

        <div class="product-widget mt-4">
            <div class="total-sale">
                <i class="las la-shopping-cart"></i> {{ getAmount($product->total_sell) }} @lang('Sales')
            </div>
        </div>

        <div class="product-widget mt-4">
            <div class="author-widget">
                <div class="thumb">
                    <img src="{{ getImage(getFilePath('userProfile') . '/' . @$product->user->image, getFileSize('userProfile')) }}" alt="image">
                </div>
                <div class="content">
                    <h5 class="author-name">
                        <a href="{{ route('author.profile', @$product->user->username) }}">{{ @$product->user->username }}</a>
                    </h5>
                </div>

                <ul class="author-info-list w-100 mt-3">
                    <li>
                        <span class="caption">@lang('Since')</span>
                        <span class="value">{{ showDateTime(@$product->user->created_at, 'd/m/Y') }}</span>
                    </li>
                    <li>
                        <span class="caption">@lang('Rating')</span>
                        <span class="value text--warning">
                            @php echo displayRating(@$product->user->avg_rating) @endphp
                        </span>
                    </li>
                    <li>
                        <span class="caption">@lang('Products')</span>
                        <span class="value">{{ @$product->user->products->count() }}</span>
                    </li>
                    <li>
                        <span class="caption">@lang('Sales')</span>
                        <span class="value">{{ @$product->user->products->sum('total_sell') }}</span>
                    </li>
                </ul>
                <ul class="author-badge-list w-100">
                    @foreach ($levels as $level)
                        @if ($loop->iteration <= $product->user->level_id)
                            <li>
                                <img data-bs-toggle="tooltip" data-placement="top" src="{{ getImage(getFilePath('level') . '/' . $level->image, getFileSize('level')) }}" title="{{ __($level->name) }}" alt="@lang('image')">
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="product-widget mt-4">
            <div class="product-widget-info">
                <h6 class="title">@lang('Last Update')</h6>
                <p>{{ showDateTime($product->updated_at, 'd/m/y') }}</p>
            </div>
            <div class="product-widget-info">
                <h6 class="title">@lang('First Release')</h6>
                <p>{{ showDateTime($product->created_at, 'd/m/y') }}</p>
            </div>
            @foreach ($product->category_details as $key => $item)
                <div class="product-widget-info">
                    <h6 class="title">{{ ucwords(str_replace('_', ' ', $key)) }}</h6>
                    <p>
                        @foreach ($item as $data)
                            {{ __(str_replace('_', ' ', $data)) }} @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </p>
                </div>
            @endforeach

            <div class="product-widget-info">
                <h6 class="title mb-3">@lang('Tags')</h6>
                <div class="product-widget-tags">
                    @foreach ($product->tag as $tag)
                        <a href="{{ route('tag.products') }}?tags={{ $tag }}">{{ __($tag) }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="discount-modal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-4 text-center">
                <h4>@lang('Better Safe than sorry'):)</h4>
                <p>@lang('Get help when you need it most and extend support for') {{ $general->extended }} @lang('more months')</p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <hr>
                        <h6><del id="previous-value"></del></h6>
                        <h3 id="current-value"></h3>
                        <p>@lang('Save') <span id="discount-percentage"></span>% @lang('by extending now instead of after support has expired').</p>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn--base w-100" data-bs-dismiss="modal" type="button">@lang('Ok, got it')</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        'use strict';
        (function($) {
            $(".license-selectBox").on('change', function() {
                $('.extendSupport').prop('checked', false);
                var resource = $(this).find('option:selected').data('resource');
                var type = $(this).find('option:selected').val();
                if (type == 1) {
                    var regular = parseFloat(resource.regular_price);
                    $('#product-price').text(`{{ $general->cur_sym }}${regular.toFixed(2)}`);
                    if (resource.support) {
                        if (resource.support_discount) {
                            supportDiscount(regular, resource)
                        } else {
                            supportWithoutDiscount(regular, resource)
                        }
                    }
                }
                if (type == 2) {
                    var extended = parseFloat(resource.extended_price);
                    $('#product-price').text(`{{ $general->cur_sym }}${extended.toFixed(2)}`);
                    if (resource.support) {
                        if (resource.support_discount) {
                            supportDiscount(extended, resource)
                        } else {
                            supportWithoutDiscount(extended, resource)
                        }
                    }
                }
            }).change();


            $('.product-price-form').on('submit', function(e) {
                e.preventDefault();
                console.log($('select[name=license]').val())
                if ($('select[name=license]').val() == undefined) {
                    iziToast.error({
                        message: 'Product lisence select is required',
                        position: "topRight"
                    });
                    return;
                } else {
                    $(this).unbind().submit();
                }
            });

            function supportDiscount(price, resource) {
                var amount = (price * resource.support_charge) / 100;
                var lessCharge = (amount * resource.support_discount) / 100;
                var final = parseFloat(amount - lessCharge);

                $('#extendSupportShow').text(`{{ $general->cur_sym }}${final.toFixed(2)}`);
                $('#support-discount').text(`{{ $general->cur_sym }}${lessCharge.toFixed(2)}`);

                $(".extendSupport").on('change', function() {
                    if ($('.extendSupport').is(":checked")) {
                        var total = parseFloat(price + final);
                        $('#product-price').text(`{{ $general->cur_sym }}${total.toFixed(2)}`);
                    } else {
                        $('#product-price').text(`{{ $general->cur_sym }}${price.toFixed(2)}`);
                    }
                });
            }

            function supportWithoutDiscount(price, resource) {
                var amount = parseFloat((price * resource.support_charge) / 100);
                $('#extendSupportShow').text(`{{ $general->cur_sym }}${amount.toFixed(2)}`);
                $(".extendSupport").on('change', function() {
                    if ($('.extendSupport').is(":checked")) {
                        var total = parseFloat(price + amount);
                        $('#product-price').text(`{{ $general->cur_sym }}${total.toFixed(2)}`);
                    } else {
                        $('#product-price').text(`{{ $general->cur_sym }}${price.toFixed(2)}`);
                    }
                });
            }

            $('.discount-calc').on('click', function() {
                var modal = $('#discount-modal');
                var resource = $(this).data('resource');
                var licenseType = $(".license-selectBox").find('option:selected').val();

                if (licenseType == 1) {
                    var regular = parseFloat(resource.regular_price);
                    discountCalculation(regular, resource)
                }

                if (licenseType == 2) {
                    var extended = parseFloat(resource.extended_price);
                    discountCalculation(extended, resource)
                }
                modal.modal('show');
            });

            function discountCalculation(price, resource) {
                if (resource.support && resource.support_discount) {
                    var amount = (price * resource.support_charge) / 100;
                    var lessCharge = (amount * resource.support_discount) / 100;
                    var final = parseFloat(amount - lessCharge);

                    $("#previous-value").text(`{{ $general->cur_sym }}${amount.toFixed(2)}`);
                    $("#current-value").text(`{{ $general->cur_sym }}${final.toFixed(2)}`);
                    $("#discount-percentage").text(resource.support_discount);
                }
            }
        })(jQuery);
    </script>
@endpush
