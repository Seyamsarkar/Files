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
                                    <th>@lang('Category')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subcategories as $subcategory)
                                    <tr>
                                        <td>{{ $subcategories->firstItem() + $loop->index }}</td>
                                        <td>{{ __(@$subcategory->category->name) }}</td>
                                        <td>{{ __($subcategory->name) }}</td>
                                        <td>
                                            @php
                                                echo $subcategory->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary editButton" data-id="{{ $subcategory->id }}" data-name="{{ __($subcategory->name) }}" data-status="{{ __($subcategory->status) }}" data-category_id="{{ $subcategory->category_id }}">
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
                @if ($subcategories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($subcategories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="subcategoryModal" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true" tabindex="-1">
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
                            <label>@lang('Category')</label>
                            <select class="form-control" name="category_id" required>
                                <option value="" selected disabled>@lang('Select One')</option>
                                @foreach ($allCategory as $category)
                                    <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input class="form-control" name="name" type="text" value="{{ old('name') }}" required>
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
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-lg btn-outline--primary createButton"><i class="las la-plus"></i>@lang('Add New')</button>
    <x-search-form placeholder="Category / Name" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"

            let modal = $('#subcategoryModal');
            $('.createButton').on('click', function() {
                modal.find('.modal-title').text(`@lang('Add New Subcategory')`);
                modal.find('form').attr('action', `{{ route('admin.subcategory.store', '') }}`);
                modal.find('.status').addClass('d-none');
                modal.modal('show');
            });

            $('.editButton').on('click', function() {
                modal.find('form').attr('action', `{{ route('admin.subcategory.store', '') }}/${$(this).data('id')}`);
                modal.find('.modal-title').text(`@lang('Update Subcategory')`);
                modal.find('[name=name]').val($(this).data('name'));
                modal.find('select[name=category_id]').val($(this).data('category_id'));
                modal.find('.status').removeClass('d-none');

                if ($(this).data('status') == 1) {
                    modal.find('input[name=status]').bootstrapToggle('on');
                } else {
                    modal.find('input[name=status]').bootstrapToggle('off');
                }
                modal.modal('show')
            });

            modal.on('hidden.bs.modal', function() {
                $('#subcategoryModal form')[0].reset();
            });

        })(jQuery);
    </script>
@endpush
