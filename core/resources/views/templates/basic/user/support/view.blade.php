@extends($activeTemplate . 'layouts.' . $layout)

@section('content')
    @if ($layout == 'frontend')
        <div class="pt-100 pb-100">
            <div class="container">
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card custom--card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5>
                        @php echo $myTicket->statusBadge; @endphp
                        [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                    </h5>
                    @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                        <button class="btn btn--danger close-button btn-sm confirmationBtn" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}" data-submit_btn="btn btn--base btn-sm" type="button"><i class="fa fa-lg fa-times-circle"></i>
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-between">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea class="form-control form--control" name="message" rows="4">{{ old('message') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <a class="btn btn--base btn--sm addFile" href="javascript:void(0)"><i class="fa fa-plus"></i> @lang('Add New')</a>
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Attachments') <small class="text--danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small></label>
                            <input class="form-control form--control" name="attachments[]" type="file" accept=".jpg,.jpeg,.png,.pdf,.doc" />
                            <div id="fileUploadsContainer"></div>
                            <p class="ticket-attachments-message text-muted my-2">
                                @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                            </p>
                        </div>
                        <button class="btn btn--base w-100" type="submit"> <i class="fa fa-reply"></i> @lang('Reply')</button>
                    </form>
                </div>
            </div>

            <div class="card custom--card">
                <div class="card-body">
                    @foreach ($messages as $message)
                        @if ($message->admin_id == 0)
                            <div class="row ticket--border my-3 mx-0 rounded border py-3">
                                <div class="col-md-3 border-end ticket--border text-end">
                                    <h5>{{ $message->ticket->name }}</h5>
                                </div>
                                <div class="col-md-9">
                                    <p class="text-muted fw-bold">
                                        @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                    <p>{{ $message->message }}</p>
                                    @if ($message->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a class="me-3" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="row ticket--border my-3 mx-0 rounded border py-3">
                                <div class="col-md-3 border-end ticket--border text-end">
                                    <h5>{{ $message->admin->name }}</h5>
                                    <p class="lead text-muted">@lang('Staff')</p>
                                </div>
                                <div class="col-md-9">
                                    <p class="text-muted fw-bold">
                                        @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                    <p>{{ $message->message }}</p>
                                    @if ($message->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a class="me-3" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    @if ($layout == 'frontend')
        </div>
        </div>
    @endif

    <x-confirmation-modal />
@endsection

@if ($layout == 'master')
    @push('breadcrumb-plugins')
        <a class="btn btn-sm btn-outline--base mb-4" href="{{ route('ticket.index') }}"><i class="las la-ticket-alt"></i> @lang('My Tickets')</a>
    @endpush
@endif

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }

        .remove-btn {
            padding: 5px 15px;
            border-radius: 3px;
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 4) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="d-flex flex-shrink-0 gap-2 my-3 attachment">
                        <input type="file" name="attachments[]" class="form-control form--control" accept=".jpg,.jpeg,.png,.pdf,.doc" required />
                        <button type="button" class="btn--danger remove-btn"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.attachment').remove();
            });
        })(jQuery);
    </script>
@endpush
