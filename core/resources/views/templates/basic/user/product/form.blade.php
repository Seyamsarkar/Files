@extends($activeTemplate . 'layouts.master')
@section('content')
    <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card custom--card">
                    <div class="card-body p-0">
                        <div class="user-profile-area">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label>@lang('Image')<span class="text--danger">*</span></label>
                                    <div class="user-profile-header p-0">
                                        <div class="profile-thumb product-profile-thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview productPicPreview" style="background-image: url({{ getImage(getFilePath('product') . '/' . @$product->featured_image, getFileSize('product')) }})"></div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input class="profilePicUpload" id="profilePicUpload1" name="featured_image" type='file' accept=".png, .jpg, .jpeg" required>
                                                <label for="profilePicUpload1"><i class="la la-pencil text-white"></i></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>@lang('Product Name') </label>
                                                <input class="form--control" name="name" type="text" value="{{ old('name', @$product->name) }}" required>
                                            </div>
                                            @if (request()->routeIs('user.product.edit'))
                                                <div class="form-group">
                                                    <label>@lang('Category') </label>
                                                    <input class="form--control" name="category_id" type="text" value="{{ @$product->category->name }}" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label>@lang('Subcategory') </label>
                                                    <input class="form--control" name="subcategory_id" type="text" value="{{ @$product->subcategory->name ?? 'N/A' }}" readonly>
                                                </div>
                                            @else
                                                <div class="form-group">
                                                    <label>@lang('Category') </label>
                                                    <select class="form--control form-select" id="category" name="category_id" required>
                                                        <option value="" selected disabled>@lang('Select One')</option>
                                                        @foreach ($categories as $category)
                                                            <option data-subcategory="{{ $category->subcategories }}" data-category_feature="{{ $category->categoryFeature }}" data-buyerfee="{{ $category->buyer_fee }}" value="{{ $category->id }}" @selected(old('category_id', @$product->category_id) == $category->id)>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>@lang('Subcategory') </label>
                                                    <select class="form--control form-select" id="subcategory" name="subcategory_id"></select>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Demo Link') </label>
                                    <input class="form--control" name="demo_link" type="url" value="{{ old('demo_link', @$product->demo_link) }}" required>
                                </div>
                                <div class="col-lg-12" id="category-details">
                                    @foreach (@$product->category->categoryFeature ?? [] as $feature)
                                        @php
                                            $catname = str_replace(' ', '_', strtolower($feature->name));
                                            $selected = @$product->category_details[$catname] ?? null;
                                        @endphp
                                        <div class="col-md-12 form-group">
                                            <label>{{ $feature->name }} <sup class="text--danger">*</sup></label>
                                            <select class="form--control select2-basic" name="c_details[{{ $catname }}][]" @if ($feature->type == 2) multiple @endif required>
                                                @foreach ($feature->options as $data)
                                                    @php
                                                        $myselect = in_array(str_replace(' ', '_', $data), $selected);
                                                    @endphp
                                                    <option value="{{ str_replace(' ', '_', $data) }}" @if ($myselect) selected @endif>{{ $data }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="col-lg-12 form-group">
                                    <label>@lang('Tags') </label>
                                    <select class="form--control select2-auto-tokenize" name="tag[]" multiple="multiple" required>
                                        @foreach (@$product->tag ?? [] as $item)
                                            <option value="{{ $item }}" selected>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-12 form-group nicParent">
                                    <label>@lang('Description') <code>(@lang('HTML or plain text allowed'))</code></label>
                                    <textarea class="form-control nicEdit" name="description" rows="15">@php echo old('description',@$product->description)@endphp
                                    </textarea>
                                </div>

                                <div class="col-lg-12 form-group">
                                    <label>@lang('Message To Reviewer') <code>(@lang('Max 255 charecters'))</code></label>
                                    <textarea class="form--control" name="message" maxlength="255">{{ old('message', @$product->message) }}</textarea>
                                </div>

                                <div class="col-12 py-3">
                                    <div class="border-line-area">
                                        <h6 class="border-line-title">@lang('Payment Information')</h6>
                                    </div>
                                </div>
                                <div class="col-lg-5 form-group">
                                    <label>@lang('Regular Price') </label>
                                    <div class="input-group">
                                        <input class="form--control regular-price" name="regular_price" type="number" value="{{ old('regular_price', @$product->regular_price ? getAmount(@$product->regular_price - @$product->category->buyer_fee) : '') }}" step="any" required>
                                        <span class="input-group-text">{{ $general->cur_text }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-2 form-group">
                                    <label>@lang('Buyer Fee')</label>
                                    <div class="input-group">
                                        <input class="form--control buyer-fee" type="text" value="{{ getAmount(@$product->category->buyer_fee ?? 0) }}" readonly>
                                        <span class="input-group-text">{{ $general->cur_text }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-5 form-group">
                                    <label>@lang('Final Regular Price')</label>
                                    <div class="input-group">
                                        <input class="form--control final-regular-price" type="text" value="{{ getAmount(@$product->regular_price ?? 0) }}" readonly>
                                        <span class="input-group-text">{{ $general->cur_text }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-5 form-group">
                                    <label>@lang('Extended Price') </label>
                                    <div class="input-group">
                                        <input class="form--control extended-price" name="extended_price" type="number" value="{{ old('extended_price', @$product->extended_price ? getAmount(@$product->extended_price - @$product->category->buyer_fee) : '') }}" step="any" required>
                                        <span class="input-group-text">{{ $general->cur_text }}</span>
                                    </div>
                                </div>

                                <div class="col-lg-2 form-group">
                                    <label>@lang('Buyer Fee')</label>
                                    <div class="input-group">
                                        <input class="form--control buyer-fee" type="text" value="{{ getAmount(@$product->category->buyer_fee ?? 0) }}" readonly>
                                        <span class="input-group-text">{{ $general->cur_text }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-5 form-group">
                                    <label>@lang('Final Extended Price')</label>
                                    <div class="input-group">
                                        <input class="form--control final-extended-price" type="text" value="{{ getAmount(@$product->extended_price ?? 0) }}" readonly>
                                        <span class="input-group-text">{{ $general->cur_text }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-2 form-group">
                                    <label>@lang('Support') </label>
                                    <small><code>({{ $general->regular }} @lang('months'))</code></small>
                                    <select class="form--control form-select" id="support" name="support" required>
                                        <option value="1" @selected(old('support', @$product->support) == Status::YES)>@lang('Yes')</option>
                                        <option value="0" @selected(old('support', @$product->support) == Status::NO)>@lang('No')</option>
                                    </select>
                                </div>
                                <div class="col-lg-5 form-group" id="discount-div">
                                    <label>@lang('Discount For Extended Support (%)') </label> <code>(@lang('for') {{ $general->extended }} @lang('months '))</code>
                                    <input class="form--control" name="support_discount" type="number" value="{{ old('support_discount', getAmount(@$product->support_discount)) }}" step="any">
                                    <span class="text--danger support-charge"></span>
                                </div>

                                <div class="col-lg-5 form-group" id="support-charge-div">
                                    <label>@lang('Extended Support Charge (%)') </label> <code>(@lang('for') {{ $general->extended }} @lang('months '))</code>
                                    <input class="form--control" name="support_charge" type="number" value="{{ old('support_charge', getAmount(@$product->support_charge)) }}" step="any">
                                    <span class="text--danger support-charge"></span>
                                </div>
                                <div class="col-12 py-3">
                                    <div class="border-line-area">
                                        <h6 class="border-line-title">@lang('Files and Screenshots')</h6>
                                    </div>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Upload File') <code>(@lang('only zip'))</code> </label>
                                    <input class="form-control form--control" name="file" type="file" accept=".zip" required />
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label class="form-label">@lang('Screenshots')<span class="text--danger">*</span>
                                        @if (!request()->routeIs('user.product.add'))
                                            <i class="fas fa-info-circle text--danger" data-bs-toggle="tooltip" data-placement="top" title="@lang('Old images will be removed after submitted')"></i>
                                        @endif
                                    </label>
                                    <div class="input-images"></div>
                                </div>
                                <div class="col-lg-12">
                                    <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="showModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="modal-detail"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-outline--base btn-sm" href="{{ route('user.product.index') }}"><i class="las la-list"></i> @lang('My Products')</a>
@endpush

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/lib/image-uploader.min.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/lib/image-uploader.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/nicEdit.js') }}"></script>
@endpush

@push('script')
    <script>
        bkLib.onDomLoaded(function() {
            $(".nicEdit").each(function(index) {
                $(this).attr("id", "nicEditor" + index);
                new nicEditor({
                    fullPanel: true
                }).panelInstance('nicEditor' + index, {
                    hasPanel: true
                });
            });
        });
        (function($) {
            "use strict";
            $('[name=file]').on('change', function() {
                var input = $(this).prop('files')
                var fileType = ['zip'];
                if (input) {
                    var extension = input[0].name.split('.').pop().toLowerCase();
                    var isSuccess = fileType.indexOf(extension) > -1;

                    if (isSuccess) { //yes
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $(input).closest('.fileUpload').find(".icon").attr('src', `{{ asset('assets/images/') }}/${extension}.svg`);
                        }
                        reader.readAsDataURL(input[0]);
                    } else {
                        iziToast.error({
                            message: 'This type of file is not allowed',
                            position: "topRight"
                        });
                        $('.validate').val('').closest('.fileUpload').find(".icon").attr('src', `{{ asset('assets/images/first.svg') }}`);
                    }
                }
            });

            $('form').on('submit', function(e) {
                var avatar = $('[name="screenshots[]"]').prop('files').length
                if (avatar == 0) {
                    iziToast.error({
                        message: 'Screenshot is required',
                        position: "topRight"
                    });
                    e.preventDefault();
                    return;
                } else {
                    $(this).submit();
                }
            });

            @if (isset($screenshots))
                let preloaded = @json($screenshots);
            @else
                let preloaded = [];
            @endif

            $('.input-images').imageUploader({
                extensions: ['.jpg', '.jpeg', '.png'],
                preloaded: preloaded,
                imagesInputName: 'screenshots',
                preloadedInputName: 'old',
                maxSize: 2 * 1024 * 1024,
            });


            $('#support').on('change', function() {
                var value = $(this).find('option:selected').val();
                if (value == 1) {
                    $('#discount-div').show();
                    $('#support-charge-div').show();
                } else {
                    $('#discount-div').hide();
                    $('#support-charge-div').hide();
                }
            }).change();

            $('[name=support_discount],[name=support_charge]').on('input', function() {
                var discount = $(this).val();
                if (parseInt(discount) > 100) {
                    $(this).siblings('.support-charge').text(`Discount can\'t be more than 100%`);
                    $(this).val('');
                }
            });


            $(document).on('click', '.removeBtn', function() {
                $(this).closest('.remove-data').remove();
            });




            $(document).on('change', '.up', function() {
                var id = $(this).attr('id');
                var profilePicValue = $(this).val();
                var fileNameStart = profilePicValue.lastIndexOf('\\');
                profilePicValue = profilePicValue.substr(fileNameStart + 1).substring(0, 20);
                if (profilePicValue != '') {
                    $(this).closest('.fileUpload').find('.upl').html(profilePicValue);
                }
            });


            $('.regular-price').on('focusout', function() {
                checkPrice('regular');

            });
            $('.extended-price').on('focusout', function() {
                checkPrice('extended');
            });

            function checkPrice(priceType, buyerCharge = 0) {
                var value = parseFloat($(`.${priceType}-price`).val());
                let buyerFee = parseFloat(buyerCharge);
                if (buyerCharge == 0) {
                    buyerFee = parseFloat($('.buyer-fee').val());
                }
                $('.buyer-fee').val(buyerFee);
                var authorFee = parseFloat("{{ auth()->user()->level->product_charge ?? 0 }}");
                var minPrice = parseFloat(buyerFee + ((buyerFee * authorFee) / 100));
                if (value == '' || isNaN(value)) {
                    $(`.final-${priceType}-price`).val(0);
                } else {
                    var totalPrice = value + buyerFee;
                    $(`.final-${priceType}-price`).val(totalPrice);
                    if (value < minPrice) {
                        showModal(minPrice, priceType);
                    }
                }
            }

            var subcategoryId = "{{ @$product->subcategory_id }}";

            $('#category').on('change', function() {

                var subcategory = $(this).find('option:selected').data('subcategory');
                var categoryFeature = $(this).find('option:selected').data('category_feature');

                var buyerCharge = $(this).find('option:selected').data('buyerfee') ?? 0;
                checkPrice('regular', buyerCharge);
                checkPrice('extended', buyerCharge);

                $('#subcategory').empty();
                let subcategoryHtml = `<option value='' disabled selected>@lang('Select one')</option>`;
                $.each(subcategory, function(i, val) {
                    subcategoryHtml += `<option value="${val.id}" ${val.id == subcategoryId ? 'selected':''}>${val.name}</option>`
                });
                $('[name=subcategory_id]').html(subcategoryHtml);

                $('#category-details').empty();
                var htmal = ``;
                var name;
                var categoryDetails = @json(@$product->category_details);
                $.each(categoryFeature, function(index, value) {
                    name = value.name.toLowerCase().replace(' ', '_');
                    let multiple = (value.type == 2) ? 'multiple' : '';
                    htmal += `<div class="form-group">
                        <label class="required">${value.name}</label>
                        <select class="form--control select2-basic" name="c_details[${name}][]" ${multiple} required>`;

                    if (value.options) {
                        var mySelect = categoryDetails ? categoryDetails[name] : [];
                        $.each(value.options, function(i, val) {
                            htmal += `<option value=${val.replace(' ','_')} ${mySelect.includes(val.replace(' ','_')) ? 'selected':''}>${val}</option>`
                        });
                    }
                    htmal += `</select></div>`;
                });

                $('#category-details').append(htmal);
                $('.select2-basic').select2();
            }).change();

            function showModal(minPrice, priceType) {
                var modal = $("#showModal");
                $('body').find(`.final-${priceType}-price`).val(0);
                $(`.${priceType}-price`).val('');
                var curText = `{{ $general->cur_text }}`;
                modal.find('.modal-detail').text(`Minimum ${priceType} price ${minPrice} ${curText}`)
                modal.modal('show');
            }
        })(jQuery);
    </script>
@endpush
