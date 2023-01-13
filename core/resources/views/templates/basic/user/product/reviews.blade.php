@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="table-responsive--md">
        <table class="custom--table mb-0 table">
            <thead>
                <tr>
                    <th>@lang('Product Name')</th>
                    <th>@lang('Username')</th>
                    <th>@lang('Rating')</th>
                    <th>@lang('Review')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr>
                        <td>
                            <a class="text--base" href="{{ route('product.detail', [@$review->product->id, slug(@$review->product->name)]) }}" target="_blank">{{ strLimit($product->name, 32) }}</a>
                        </td>
                        <td>
                            <span>{{ __(@$review->user->username) }}</span>
                        </td>
                        <td>
                            {{ $review->rating }} @lang('Star')
                        </td>
                        <td>
                            <span>{{ __(strLimit($review->review, 40)) }}</span>
                        </td>
                        <td>
                            <button class="btn btn--info btn--sm viewBtn" data-detail="{{ $review->review }}">
                                <i class="las la-desktop"></i> @lang('View')
                            </button>
                            @if ($review->status == Status::REVIEW_REPORTED)
                                <button class="btn btn--dark btn--sm report-msg" data-report="{{ @$review->report_message }}">
                                    <i class="las la-gavel"></i> @lang('View Report')
                                </button>
                            @else
                                <button class="btn btn--dark btn--sm reportbtn" data-action="{{ route('user.product.review.report', [$review->id, $product->id]) }}">
                                    <i class="las la-gavel"></i> @lang('Report')
                                </button>
                            @endif

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
            {{ paginateLinks($reviews) }}
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="detailModal" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">@lang('Review')</h5>
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

    <div class="modal fade" id="reportModal" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">@lang('Are you sure report this review')?</h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Report of reason')</label>
                            <textarea class="form-control from--control" name="report_message" required></textarea>
                        </div>
                        <div class="form-group text-end">
                            <button class="btn btn--danger btn--sm" data-bs-dismiss="modal" type="button">@lang('No')</button>
                            <button class="btn btn--base btn--sm" type="submit">@lang('Yes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-sm btn-outline--base" href="{{ route('user.product.index') }}">
        <i class="las la-arrow-left"></i> @lang('Back')
    </a>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            let modal = $('#detailModal');
            $('.viewBtn').on('click', function() {
                modal.find('.modal-title').text('Buyer Review');
                modal.find('.modal-detail').text($(this).data('detail'));
                modal.modal('show');
            });

            $('.report-msg').on('click', function() {
                modal.find('.modal-title').text('View Report');
                modal.find('.modal-detail').text($(this).data('report'));
                modal.modal('show')
            });

            $('.reportbtn').on('click', function() {
                var modal = $('#reportModal');
                modal.find('form').attr('action', $(this).data('action'));
                modal.modal('show');
            });

        })(jQuery)
    </script>
@endpush
