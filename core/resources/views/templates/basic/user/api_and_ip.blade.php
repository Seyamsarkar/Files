@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center gy-3">
        <div class="col-md-12">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title m-0 text-center">@lang('API Keys')</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('app_secret')</label>
                                <div class="input-group">
                                    <input class="form-control form--control referralURL" type="text" value="{{ auth()->user()->username }}" readonly>
                                    <button class="input-group-text copytext" id="copyBoard" type="button"> <i class="fa fa-copy"></i> </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('api_key')</label>
                                <div class="input-group">
                                    <input class="form-control form--control referralURL" type="text" value="{{ auth()->user()->api_key }}" readonly>
                                    @if (auth()->user()->api_key)
                                        <button class="input-group-text copytext" id="copyBoard" type="button"> <i class="fa fa-copy"></i> </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-group text-end">
                        <button class="btn btn--base resetApiKey"><i class="las la-sync"></i>
                            @if (auth()->user()->api_key)
                                @lang('Reset Api Key')
                            @else
                                @lang('Generate Api Key')
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title m-0 text-center">@lang('Whitelisted IP')</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive--md">
                        <table class="custom--table mb-3 table">
                            <thead>
                                <tr>
                                    <th>@lang('SL#')</th>
                                    <th>@lang('IP')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ips as $ip)
                                    <tr>
                                        <td>
                                            {{ $ips->firstItem() + $loop->index }}
                                        </td>
                                        <td>
                                            {{ $ip->ip }}
                                        </td>
                                        <td>
                                            <button class="btn btn--sm btn--danger deleteBtn" data-id="{{ $ip->id }}">
                                                <i class="la la-trash"></i> @lang('Delete')
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="justify-content-center text- center" colspan="100%">
                                            {{ __($emptyMessage) }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($ips->hasPages())
                            <div class="py-3">
                                {{ paginateLinks($ips) }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group text-end">
                        <button class="btn btn--base addIpToWhitelist"><i class="las la-plus"></i> @lang('Add new IP')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmationAlert" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="existModalLongTitle">@lang('Confirmation Alert')</h5>
                        <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                            <i class="las la-times"></i>
                        </span>
                    </div>
                    <div class="modal-body">
                        <h6 class="confirmationTitle text-center"></h6>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark btn-sm" data-bs-dismiss="modal" type="button">@lang('No')</button>
                        <button class="btn btn--base btn-sm" type="submit">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addIpToWhitelistModal" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('user.api.whitelist.ip') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="existModalLongTitle">@lang('Whitelist your IP')</h5>
                        <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                            <i class="las la-times"></i>
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">@lang('IP')</label>
                            <input class="form-control form--control" name="ip" type="text" required placeholder="127.0.0.1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <a class="btn btn-outline--base btn-sm" href="{{ route('user.api.documentation') }}">
        <i class="las la-file"></i> @lang('Documentation')
    </a>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            $('.copytext').click(function() {
                var copyText = this.parentElement.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
            $('.resetApiKey').on('click', function() {
                var confirmationModal = $('#confirmationAlert');
                var actionUrl = "{{ route('user.api.reset') }}";
                var text = `{{ auth()->user()->api_key ? __('Are you sure to reset API key?') : __('Are you sure to generate API key?') }}`;
                confirmationModal.find('form').attr('action', actionUrl)
                confirmationModal.find('.confirmationTitle').text(text)
                confirmationModal.modal('show');
            });
            $('.deleteBtn').on('click', function() {
                var confirmationModal = $('#confirmationAlert');
                var ipId = $(this).data('id');
                var actionUrl = `{{ route('user.api.ip.remove', '') }}/${ipId}`;
                var text = `@lang('Are you sure to remove this IP?')`;

                confirmationModal.find('form').attr('action', actionUrl)
                confirmationModal.find('.confirmationTitle').text(text)
                confirmationModal.modal('show');
            });
            $('.addIpToWhitelist').on('click', function() {
                $('#addIpToWhitelistModal').modal('show');
            });
        })(jQuery);
    </script>
@endpush
