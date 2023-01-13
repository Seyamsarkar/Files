@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card custom--card">
                <div class="card-body">
                    <form action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">@lang('Subject')</label>
                                <input class="form-control form--control" name="subject" type="text" value="{{ old('subject') }}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">@lang('Priority')</label>
                                <select class="form-control form--control form-select" name="priority" required>
                                    <option value="3">@lang('High')</option>
                                    <option value="2">@lang('Medium')</option>
                                    <option value="1">@lang('Low')</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Message')</label>
                            <textarea class="form-control form--control" name="message" rows="6" required>{{ old('message') }}</textarea>
                        </div>

                        <div class="form-group">
                            <div class="text-end">
                                <button class="btn btn--base btn-sm addFile" type="button">
                                    <i class="fa fa-plus"></i> @lang('Add New')
                                </button>
                            </div>
                            <div class="file-upload">
                                <label class="form-label">@lang('Attachments') <small class="text--danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small></label>
                                <input class="form-control form--control mb-2" id="inputAttachments" name="attachments[]" type="file" accept=".jpg,.jpeg,.png,.pdf,.doc" />
                                <div id="fileUploadsContainer"></div>
                                <p class="ticket-attachments-message text-muted">
                                    @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                </p>
                            </div>
                        </div>
                        <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-sm btn-outline--base" href="{{ route('ticket.index') }}"><i class="las la-ticket-alt"></i> @lang('My Tickets')</a>
@endpush

@push('style')
    <style>
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
