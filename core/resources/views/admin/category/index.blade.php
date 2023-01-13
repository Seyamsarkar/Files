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
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Image')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Buyer Fee')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Featured')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allCategory as $category)
                                    <tr>
                                        <td>{{ $allCategory->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="user">
                                                <div class="thumb"><img class="plugin_bg" src="{{ getImage(getFilePath('category') . '/' . $category->image, getFileSize('category')) }}" alt="{{ __($category->name) }}"></div>
                                            </div>
                                        </td>
                                        <td><span>{{ __($category->name) }}</span></td>
                                        <td>{{ showAmount($category->buyer_fee) }} {{ $general->cur_text }}</td>
                                        <td>
                                            @php
                                                echo $category->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            @if ($category->featured == Status::YES)
                                                <span class="badge badge--info">@lang('Yes')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('No')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary editButton" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-buyer_fee="{{ getAmount($category->buyer_fee) }}" data-status="{{ $category->status }}" data-image="{{ getImage(getFilePath('category') . '/' . $category->image, getFileSize('category')) }}">
                                                    <i class="la la-pencil"></i> @lang('Edit')
                                                </button>
                                                @if ($category->featured == Status::YES)
                                                    <button class="btn btn-outline--warning btn-sm confirmationBtn" data-question="@lang('Are you sure unfeature this category?')" data-action="{{ route('admin.category.featured', $category->id) }}" type="button"><i class="las la-eye-slash"></i> @lang('Unfeature')</button>
                                                @else
                                                    <button class="btn btn-outline--info btn-sm confirmationBtn" data-question="@lang('Are you sure feature this category?')" data-action="{{ route('admin.category.featured', $category->id) }}" type="button"><i class="las la-eye"></i> @lang('Feature')</button>
                                                @endif
                                            </div>
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
                @if ($allCategory->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($allCategory) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="categoryModal" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="createModalLabel"></h4>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input class="form-control" name="name" type="text" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Buyer Fee')</label>
                            <div class="input-group">
                                <input class="form-control" name="buyer_fee" type="text" value="{{ old('buyer_fee') }}" required>
                                <span class="input-group-text">{{ $general->cur_text }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Image')<span class="text--danger">*</span></label>
                            <div class="image-upload">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url({{ getImage('/', getFileSize('category')) }})">
                                            <button class="remove-image" type="button"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="avatar-edit">
                                        <input class="profilePicUpload" id="profilePicUpload1" name="image" type="file" accept=".png, .jpg, .jpeg">
                                        <label class="bg--primary" for="profilePicUpload1">@lang('Upload Image')</label>
                                        <small class="text-facebook mt-2">@lang('Supported files'):
                                            <b>@lang('jpeg'), @lang('jpg'), @lang('png').</b>
                                            @lang('Image will be resized into '){{ getFileSize('category') }} @lang('px')
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group status">
                            <label>@lang('Status')</label>
                            <div class="col-sm-12">
                                <input name="status" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Active')" data-off="@lang('Inactive')" type="checkbox">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-lg btn-outline--primary createButton"><i class="las la-plus"></i>@lang('Add New')</button>
    <x-search-form placeholder="Search by Name" />
@endpush

@push('style')
    <style>
        .table .user {
            justify-content: center;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"
            let modal = $('#categoryModal');
            $('.createButton').on('click', function() {
                modal.find('.modal-title').text(`@lang('Add New Category')`);
                modal.find('form').attr('action', `{{ route('admin.category.store', '') }}`);
                modal.find('.status').addClass('d-none');
                modal.modal('show');
            });

            $('.editButton').on('click', function() {
                modal.find('form').attr('action', `{{ route('admin.category.store', '') }}/${$(this).data('id')}`);
                modal.find('.modal-title').text(`@lang('Update Category')`);
                modal.find('[name=name]').val($(this).data('name'));
                modal.find('[name=buyer_fee]').val($(this).data('buyer_fee'));
                modal.find('.profilePicPreview').attr('style', `background-image: url(${$(this).data('image')})`);
                modal.find('.status').removeClass('d-none');

                if ($(this).data('status') == 1) {
                    modal.find('input[name=status]').bootstrapToggle('on');
                } else {
                    modal.find('input[name=status]').bootstrapToggle('off');
                }
                modal.modal('show')
            });
            var defautlImage = `{{ getImage(getFilePath('category'), getFileSize('category')) }}`;

            modal.on('hidden.bs.modal', function() {
                modal.find('.profilePicPreview').attr('style', `background-image: url(${defautlImage})`);
                $('#categoryModal form')[0].reset();
            });

        })(jQuery);
    </script>
@endpush
