@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="table-responsive--md">
        <table class="custom--table mb-0 table">
            <thead>
                <tr>
                    <th>@lang('S.N.')</th>
                    <th>@lang('Purchase Code')</th>
                    <th>@lang('Product Name')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sells as $sell)
                    <tr>
                        <td>
                            <span>{{ $sells->firstItem() + $loop->index }}</span>
                        </td>
                        <td>
                            <span>{{ $sell->code }}</span>
                        </td>
                        <td>
                            <a class="text--base" href="@if (@$sell->product->status == Status::PRODUCT_APPROVE) {{ route('product.detail', [@$sell->product->id, slug(@$sell->product->name)]) }} @else javascript:void(0) @endif">{{ strLimit(@$sell->product->name, 32) }}</a>
                        </td>
                        <td>
                            @php
                                echo $sell->statusBadge;
                            @endphp
                            @if (@$sell->deposit->sell_status == Status::SELL_REJECTED)
                                <a class="text--danger reasonBtn" data-reason="{{ $sell->reject_message }}" href="javascript:void(0)"><span><i class="fas fa-info-circle"></i></span></a>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-end flex-wrap gap-1">
                                <button class="btn btn--info btn--sm detailBtn" data-resource="{{ $sell }}"><i class="las la-desktop"></i> @lang('Detail')</button>
                                @if ($sell->status == Status::SELL_APPROVED)
                                    <a class="btn btn--primary btn--sm" href="{{ route('user.purchased.product.download', Crypt::encrypt($sell->id)) }}">
                                        <i class="las la-download"></i> @lang('Download')
                                    </a>
                                    <a class="btn btn--dark btn--sm" href="{{ route('user.purchased.product.invoice', Crypt::encrypt($sell->id)) }}" target="_blank">
                                        <i class="las la-receipt"></i> @lang('Invoice')
                                    </a>
                                @else
                                    <button class="btn btn--primary btn--sm disabled"><i class="las la-download"></i> @lang('Download')</button>
                                    <button class="btn btn--dark btn--sm disabled"><i class="las la-receipt"></i> @lang('Invoice')</button>
                                @endif

                                <button class="btn btn--warning btn--sm reviewBtn" data-resource="{{ @$sell->review }}" data-id="{{ @$sell->id }}"><i class="las la-star-of-david"></i> @lang('Review')</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="justify-content-center text-center" colspan="100%">
                            <i class="la la-4x la-frown"></i>
                            <br>
                            {{ __($emptyMessage) }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination--sm justify-content-end">
            {{ paginateLinks($sells) }}
        </div>
    </div>

    <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Product Review')</h4>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Your Ratings') :</label>
                            <div class="rating">
                                <div class="rating-form-group">
                                    <label class="star-label">
                                        <input name="rating" type="radio" value="1" />
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                    </label>
                                    <label class="star-label">
                                        <input name="rating" type="radio" value="2" />
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                    </label>
                                    <label class="star-label">
                                        <input name="rating" type="radio" value="3" />
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                    </label>
                                    <label class="star-label">
                                        <input name="rating" type="radio" value="4" />
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                    </label>
                                    <label class="star-label">
                                        <input name="rating" type="radio" value="5" />
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                        <span class="icon fs-26"><i class="las la-star"></i></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Write your opinion')</label>
                            <textarea class="from-control form--control" name="review" rows="5" required></textarea>
                        </div>
                        <button class="btn btn-md btn--base w-100 submitButton" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reasonModal" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId"></h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <p class="modal-detail"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush userData">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('Purchased At')</span>
                            <span class="purchased_at"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('Payment Via')</span>
                            <span class="payment_via"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('Amount')</span>
                            <span class="purchased_amount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('Support')</span>
                            <span class="support"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>@lang('Support End')</span>
                            <span class="support_end"></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <form action="" method="GET">
        <div class="input-group">
            <input class="form-control form--control search-form" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search by code or name')">
            <button class="input-group-text" type="submit"><i class="la la-search"></i></button>
        </div>
    </form>
@endpush

@push('script')
    <script>
        'use strict';
        $('.reviewBtn').on('click', function() {
            var reviewModal = $('#reviewModal');
            var resource = $(this).data('resource');
            var action = `{{ route('user.purchased.product.review', '') }}/${$(this).data('id')}`;
            reviewModal.find('form').attr('action', action);
            if (resource.rating) {
                reviewModal.find(`[name="rating"][value="${resource.rating}"]`).attr('checked', true)
            } else {
                reviewModal.find(`[name="rating"]`).removeAttr('checked')
            }
            reviewModal.find(`[name="review"]`).text(resource.review)
            reviewModal.modal('show');
        });

        let modal = $('#reasonModal');
        $('.reasonBtn').on('click', function() {
            modal.find('.modal-title').text(`@lang('Detail of Reason')`)
            modal.find('.modal-detail').text($(this).data('reason'));
            modal.modal('show');
        });

        $('form').on('submit', function(e) {
            if ($('[name=rating]:checked').val() == undefined) {
                iziToast.error({
                    message: 'Rating is required',
                    position: "topRight"
                });
                e.preventDefault();
                return;
            }
            $(this).submit();

        });

        $('.detailBtn').on('click', function() {
            var modal = $('#detailModal');
            var sell = $(this).data('resource');
            var paymentVia = sell?.deposit?.gateway;
            modal.find('.purchased_at').text(new Date(sell.created_at).toLocaleDateString())
            modal.find('.support').text(sell.support == 1 ? 'Yes' : 'No')
            modal.find('.support_end').text(sell.support_time ?? 'N/A')
            modal.find('.payment_via').text(paymentVia ? paymentVia.name : 'Account Balance')
            modal.find('.purchased_amount').text(`${parseFloat(sell.total_price).toFixed(2)} {{ $general->cur_text }}`)
            modal.modal('show')
        });
    </script>
@endpush
