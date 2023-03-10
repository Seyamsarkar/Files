@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="table-responsive--md">
        <table class="custom--table mb-0 table">
            <thead>
                <tr>
                    <th>@lang('Gateway | Transaction')</th>
                    <th>@lang('Initiated')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Conversion')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdraws as $withdraw)
                    <tr>
                        <td>
                            <div>
                                <span class="fw-bold text--base"> {{ __(@$withdraw->method->name) }}</span>
                                <br>
                                <small>{{ $withdraw->trx }}</small>
                            </div>
                        </td>
                        <td>
                            <span>{{ showDateTime($withdraw->created_at) }} <br> {{ diffForHumans($withdraw->created_at) }}</span>
                        </td>
                        <td>
                            <div>
                                {{ __($general->cur_sym) }}{{ showAmount($withdraw->amount) }} - <span class="text--danger" title="@lang('charge')">{{ showAmount($withdraw->charge) }} </span>
                                <br>
                                <strong title="@lang('Amount after charge')">
                                    {{ showAmount($withdraw->amount - $withdraw->charge) }} {{ __($general->cur_text) }}
                                </strong>
                            </div>
                        </td>
                        <td>
                            <div>
                                1 {{ __($general->cur_text) }} = {{ showAmount($withdraw->rate) }} {{ __($withdraw->currency) }}
                                <br>
                                <strong>{{ showAmount($withdraw->final_amount) }} {{ __($withdraw->currency) }}</strong>
                            </div>
                        </td>
                        <td>
                            @php echo $withdraw->statusBadge @endphp
                        </td>
                        <td>
                            <button class="btn btn--sm btn--base detailBtn" data-user_data="{{ json_encode($withdraw->withdraw_information) }}" @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                <i class="la la-desktop"></i> @lang('Detail')
                            </button>
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
        {{ paginateLinks($withdraws) }}
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
                    <ul class="list-group list-group-flush userData"></ul>
                    <div class="feedback"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <form action="" method="GET">
        <div class="input-group">
            <input class="form-control form--control search-form" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search by transactions')">
            <button class="input-group-text" type="submit"><i class="la la-search"></i></button>
        </div>
    </form>
    <a class="btn btn-outline--base btn-sm" href="{{ route('user.withdraw') }}">
        <i class="las la-hand-holding-usd"></i> @lang('Withdraw Now')
    </a>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    }
                });
                modal.find('.userData').html(html);
                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }
                modal.find('.feedback').html(adminFeedback);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
