@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- cart section start -->
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row gy-4 justify-content-center">
                <div class="col-lg-8">
                    <div class="card custom--card">
                        <div class="card-header">
                            <p>@lang('You have') {{ $orders->count() }} @lang('products in your cart')</p>
                        </div>
                        <div class="card-body">
                            @forelse ($orders as $item)
                                <div class="single-cart">
                                    <div class="single-cart__thumb">
                                        <a class="d-block" href="{{ route('product.detail', [@$item->product->id, slug(@$item->product->name)]) }}">
                                            <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->product->featured_image, getFileSize('product')) }}" alt="image">
                                        </a>
                                    </div>
                                    <div class="single-cart__content">
                                        <h6 class="single-cart__title"><a href="{{ route('product.detail', [@$item->product->id, slug(@$item->product->name)]) }}">{{ __($item->product->name) }}</a></h6>
                                        <span class="fs-14px">@lang('Product by') - <a class="text-decoration-underline text--base" href="{{ route('author.profile', @$item->product->user->username) }}">{{ $item->author->username }}</a></span>
                                        <ul class="d-flex cart-feature-list mt-2 flex-wrap">
                                            <li class="fs-12px">
                                                <span class="fw-bold">@lang('License') : </span>
                                                @if ($item->license == Status::REGULAR_LICENSE)
                                                    <span>@lang('Regular Licences')<Span>
                                                        @elseif($item->license == Status::EXTENDED_LICENSE)
                                                            <span>@lang('Extended Licences')<Span>
                                                @endif
                                            </li>
                                            <li class="fs-12px">
                                                <span class="fw-bold">@lang('Support') : </span>
                                                @if ($item->support == Status::YES)
                                                    <span>@lang('Yes')<Span>
                                                        @elseif($item->support == 0)
                                                            <span>@lang('No')<Span>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="single-cart__price">
                                        <button class="btn btn--danger btn--sm confirmationBtn mb-3" data-action="{{ route('remove.cart', Crypt::encrypt($item->id)) }}" data-question="@lang('Are you sure to remove this product?')" data-submit_btn="btn btn--base btn-sm">
                                            <i class="las la-times"></i>
                                        </button>
                                        <div class="price">{{ $general->cur_sym }}{{ showAmount($item->total_price) }}</div>
                                    </div>
                                </div>
                            @empty
                                <h6 class="text--danger text-center"><i class="la la-4x la-frown"></i><br>@lang('No product in your cart')</h6>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 ps-xl-5 ps-lg-4">
                    <div class="cart-total-box text-center">
                        <h6 class="mb-3 text-white">@lang('Your Cart Total')</h6>
                        <div class="totoal-price text-white">{{ $general->cur_sym }}{{ showAmount($orders->sum('total_price')) }}</div>
                        @if ($orders->count() > 0)
                            @auth
                                <a class="btn btn--light w-100 mt-4 bg-white" data-bs-toggle="modal" data-bs-target="#paymentModal" href="javascript:void(0)">@lang('Checkout Now')</a>
                            @else
                                <a class="btn btn--light w-100 mt-4 bg-white" data-bs-toggle="modal" data-bs-target="#loginMessageModal" href="javascript:void(0)">@lang('Checkout Now')</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- cart section end -->

    <div class="modal fade" id="loginMessageModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>@lang('Login Required!')</h6>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text--danger text-center">@lang('You have to login before checkout')</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-dark btn-sm" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                    <a class="btn btn--base btn-sm" href="{{ route('user.login') }}">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />

    @auth
        <div class="modal fade" id="paymentModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6>@lang('Make Payment')</h6>
                        <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('user.checkout') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="">@lang('Select Method')</label>
                                <select class="form--control" name="wallet_type" required>
                                    <option value="own">@lang('Own Wallet') - {{ $general->cur_sym }}{{ getAmount(auth()->user()->balance) }}</option>
                                    <option value="online">@lang('Online Payment')</option>
                                </select>
                            </div>
                            <button class="btn btn-md btn--base w-100" type="submit">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth
@endsection
