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
                                    <th>@lang('Minimun Earning')</th>
                                    <th>@lang('Product Charge')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($levels as $level)
                                    <tr>
                                        <td>{{ $levels->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="customer-details d-block">
                                                <a class="thumb" href="javascript:void(0)">
                                                    <img src="{{ getImage(getFilePath('level') . '/' . $level->image, getFileSize('level')) }}" alt="@lang('image')">
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ __($level->name) }}</td>
                                        <td>{{ showAmount($level->earning) }} {{ $general->cur_text }}</td>
                                        <td>{{ getAmount($level->product_charge) }}%</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary editButton" data-id="{{ $level->id }}" data-name="{{ $level->name }}" data-earning="{{ getAmount($level->earning) }}" data-product_charge="{{ getAmount($level->product_charge) }}" data-status="{{ $level->status }}" data-image="{{ getImage(getFilePath('level') . '/' . $level->image, getFileSize('level')) }}">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </button>
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
                @if ($levels->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($levels) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="levelModal" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="createModalLabel"></h4>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input class="form-control" name="name" type="text" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Minimum Earning')</label>
                            <div class="input-group">
                                <input class="form-control" name="earning" type="number" value="{{ old('earning') }}" step="any" required>
                                <span class="input-group-text">{{ $general->cur_text }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Product Charge')</label>
                            <div class="input-group">
                                <input class="form-control" name="product_charge" type="number" value="{{ old('product_charge') }}" step="any" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Image')</label>
                            <div class="image-upload">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url({{ getImage('/', getFileSize('level')) }})">
                                            <button class="remove-image" type="button"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="avatar-edit">
                                        <input class="profilePicUpload" id="profilePicUpload1" name="image" type="file" accept=".png, .jpg, .jpeg" required>
                                        <label class="bg--primary" for="profilePicUpload1">@lang('Upload Image')</label>
                                        <small class="text-facebook mt-2">@lang('Supported files'):
                                            <b>@lang('jpeg'), @lang('jpg'), @lang('png').</b>
                                            @lang('Image will be resized into '){{ getFileSize('level') }} @lang('px')
                                        </small>
                                    </div>
                                </div>
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
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-lg btn-outline--primary createButton"><i class="las la-plus"></i>@lang('Add New')</button>
    <x-search-form placeholder="Search by Name" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"
            let modal = $('#levelModal');
            $('.createButton').on('click', function() {
                modal.find('.modal-title').text(`@lang('Add New Level')`);
                modal.find('form').attr('action', `{{ route('admin.level.store', '') }}`);
                modal.modal('show');
            });

            $('.editButton').on('click', function() {
                modal.find('form').attr('action', `{{ route('admin.level.store', '') }}/${$(this).data('id')}`);
                modal.find('.modal-title').text(`@lang('Update Level')`);
                modal.find('[name=name]').val($(this).data('name'));
                modal.find('[name=earning]').val($(this).data('earning'));
                modal.find('[name=product_charge]').val($(this).data('product_charge'));
                modal.find('.profilePicPreview').attr('style', `background-image: url(${$(this).data('image')})`);
                modal.modal('show')
            });
            var defautlImage = `{{ getImage(getFilePath('level'), getFileSize('level')) }}`;

            modal.on('hidden.bs.modal', function() {
                modal.find('.profilePicPreview').attr('style', `background-image: url(${defautlImage})`);
                $('#levelModal form')[0].reset();
            });

        })(jQuery);
    </script>
@endpush
