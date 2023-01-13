@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Purchased Code')</th>
                                    <th>@lang('Product Name')</th>
                                    <th>@lang('Reviewer')</th>
                                    <th>@lang('Rating')</th>
                                    <th>@lang('Created_at')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">
                                                <a href="{{ route('admin.sell.history') }}?search={{ @$review->sell->code }}">{{ @$review->sell->code }} </a>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.product.detail', $review->product_id) }}">
                                                {{ @$review->product->name }}
                                            </a>
                                        </td>

                                        <td>
                                            <span class="fw-bold">
                                                {{ __(@$review->user->fullname) }}<br>
                                                <a href="{{ route('admin.users.detail', $review->user_id) }}">
                                                    <span>@</span>{{ @$review->user->username }}
                                                </a>
                                            </span>
                                        </td>

                                        <td>
                                            <span>{{ $review->rating }} @lang('star')</span>
                                        </td>

                                        <td>
                                            <span>{{ showDateTime($review->created_at) }} <br> {{ diffForHumans($review->created_at) }}</span>
                                        </td>

                                        <td>

                                            <button class="btn btn-sm btn-outline--info viewBtn" data-review="{{ $review->review }}" type="button">
                                                <i class="las la-desktop text--shadow"></i> @lang('View')
                                            </button>

                                            @if (request()->routeIs('admin.reviews.reported'))
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{ route('admin.reviews.delete', $review->id) }}" data-question="@lang('Are you sure accept this reported review?')" data-btn_class="btn btn--primary" type="button">
                                                    <i class="las la-check text--shadow"></i> @lang('Accept')
                                                </button>

                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.reviews.reject', $review->id) }}" data-question="@lang('Are you sure reject this reported review?')" data-btn_class="btn btn--primary" type="button">
                                                    <i class="las la-times text--shadow"></i> @lang('Reject')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.reviews.delete', $review->id) }}" data-question="@lang('Are you sure remove this review?')" data-btn_class="btn btn--primary" type="button">
                                                    <i class="las la-trash text--shadow"></i> @lang('Delete')
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($reviews->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($reviews) }}
                    </div>
                @endif
            </div>
        </div>

    </div>

    <div class="modal fade" id="viewModal" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Review')</h4>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                <div class="modal-body">
                    <p class="modal-detail"></p>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search here" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.viewBtn').on('click', function() {
                var modal = $('#viewModal');
                modal.find('.modal-detail').text($(this).data('review'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
