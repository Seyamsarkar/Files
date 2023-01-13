@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="product-details-top mb-4">
                        <h3 class="product-details-title mb-2">{{ __($product->name) }}</h3>
                        <ul class="product-details-meta style--two">
                            <li>
                                <a href="{{ route('category.products', [@$product->category->id, slug(@$product->category->name)]) }}">{{ __($product->category->name) }}</a>
                            </li>
                            <li class="ratings">
                                @php echo displayRating($product->avg_rating) @endphp
                                ({{ $product->total_response }})
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="product-thumb-slider-area shadow-sm">
                        <div class="product-details-thumb">
                            @if ($product->featured == 1)
                                <div class="tending-badge-two">
                                    <span class="caption">@lang('Featured')</span>
                                    <i class="las la-bolt"></i>
                                </div>
                            @endif
                            <img src="{{ getImage(getFilePath('product') . '/' . $product->featured_image, getFileSize('product')) }}" alt="image">
                        </div>
                    </div>

                    <div class="product-details-meta style--three mt-5 mb-4 shadow-sm">
                        <div class="left">
                            <div class="btn--group justify-content-md-start justify-content-center">
                                <a class="btn btn-md btn--base d-inline-flex align-items-center justify-content-center text-center" href="{{ $product->demo_link }}" target="_blank"><i class="las la-desktop fs-5 me-2"></i> @lang('Live Preview')</a>

                                @if ($product->screenshots)
                                    @foreach ($product->screenshots as $key => $screenshot)
                                        @if ($loop->first)
                                            <a class="btn btn-md btn-outline--base" data-rel="lightcase:myCollection:slideshow" href="{{ getImage(getFilePath('product') . '/' . $screenshot) }}"><i class="las la-image fs-5 me-2"></i> @lang('Screenshot')</a>
                                        @endif
                                        @if (!@$loop->first)
                                            <a data-rel="lightcase:myCollection:slideshow" href="{{ getImage(getFilePath('product') . '/' . $screenshot) }}"></a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="right">
                            <ul class="socail-list justify-content-md-end justify-content-center">
                                <li class="caption">@lang('Share'): </li>
                                <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="https://twitter.com/intent/tweet?text={{ __(@$product->name) }}%0A{{ url()->current() }}"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ __(@$product->name) }}&media={{ getImage(getFilePath('product') . '/' . $product->featured_image, getFileSize('product')) }}"><i class="fab fa-pinterest-p"></i></a></li>
                                <li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __(@$product->name) }}&amp;summary={{ __(@$product->description) }}"><i class="fab fa-linkedin-in"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="product-details-content mt-50">
                        <ul class="nav nav-tabs custom--nav-tabs style--two" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">@lang('Overview')</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button" role="tab" aria-controls="review" aria-selected="false">@lang('Review')</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="comment-tab" data-bs-toggle="tab" data-bs-target="#comment" type="button" role="tab" aria-controls="comment" aria-selected="false">@lang('Comment')</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="pt-4">
                                    @php
                                        echo $product->description;
                                    @endphp
                                </div>
                            </div>
                            <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                                @include($activeTemplate . 'product.reviews')
                            </div>
                            <div class="tab-pane fade" id="comment" role="tabpanel" aria-labelledby="comment-tab">
                                @include($activeTemplate . 'product.comments')
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-5 mb-3">@lang('More products by') <a href="javascript:void(0)"><em>{{ $product->user->username }}</em></a></h5>
                    @include($activeTemplate . 'product.card.slider', ['products' => $moreProducts, 'view' => 'two'])
                </div>
                @include($activeTemplate . 'product.detail_sidenav')
            </div>
        </div>
        <div class="modal fade" id="detailModal" role="dialog" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="text--dark">@lang('Reply this comment')</h6>
                        <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                            <i class="las la-times"></i>
                        </span>
                    </div>
                    <form action="" method="POST">
                        @csrf
                        <div class="modal-body text-center">
                            <textarea class="form--control" name="reply" placeholder="@lang('Write here')..."></textarea>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn--base w-100" type="submit">@lang('Reply')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style-lib')
    <link href="{{ asset('assets/global/css/lightcase.css') }}" rel="stylesheet">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/global/js/lightcase.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            $("a[data-rel^=lightcase]").lightcase();

            $(document).on('click', '.replyBtn', function() {
                var modal = $('#detailModal');
                modal.find('form').attr('action', $(this).data('action'))
                modal.modal('show')
            });


            $('.commentBtn').on('click', function(e) {
                e.preventDefault();
                var comment = $('[name=comment]').val();
                var url = "{{ route('user.product.comment', $product->id) }}";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    url: url,
                    method: "POST",
                    data: {
                        comment: comment,
                    },
                    success: function(response) {
                        if (response.error) {
                            notify('error', response.error);
                        } else {
                            $('[name=comment]').val('');
                            $('.no-message').addClass('d-none')
                            $(".show-comments").append(response);
                        }
                    }
                });
            });
        })(jQuery)
    </script>
@endpush
