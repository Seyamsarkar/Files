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
                                @forelse($features as $feature)
                                    <tr>
                                        <td>{{ $features->firstItem() + $loop->index }}</td>
                                        <td>{{ __(@$feature->category->name) }}</td>
                                        <td>{{ __($feature->name) }}</td>
                                        <td>
                                            @php
                                                echo $feature->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary editButton" data-feature="{{ $feature }}">
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
                @if ($features->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($features) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="featureModal" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="createModalLabel"></h4>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Category')</label>
                                    <select class="form-control" name="category_id" required>
                                        <option value="" selected disabled>@lang('Select One')</option>
                                        @foreach ($allCategory as $category)
                                            <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Option Type')</label>
                                    <select class="form-control" name="type" required>
                                        <option value="" selected disabled>@lang('Select One')</option>
                                        <option value="1">@lang('Single Select')</option>
                                        <option value="2">@lang('Multiple Select')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input class="form-control" name="name" type="text" value="{{ old('name') }}" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                            <label>@lang('Options')<span class="text--danger">*</span></label>
                            <button class="btn btn-sm btn-outline--primary add-option" type="button"><i class="las la-plus"></i> @lang('Add More')</button>
                        </div>
                        <div class="form-group">
                            <input class="form-control first-option" name="options[]" type="text" required>
                        </div>

                        <div class="option-area"></div>

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

@push('style')
    <style>
        .remove-option {
            cursor: pointer;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"

            let modal = $('#featureModal');
            $('.createButton').on('click', function() {
                modal.find('.modal-title').text(`@lang('Add New Feature')`);
                modal.find('.status').addClass('d-none');
                modal.find('form').attr('action', `{{ route('admin.category.feature.store', '') }}`);
                modal.modal('show');
            });

            $('.editButton').on('click', function() {

                var feature = $(this).data('feature');
                modal.find('form').attr('action', `{{ route('admin.category.feature.store', '') }}/${feature.id}`);
                modal.find('.modal-title').text(`@lang('Update Feature')`);
                modal.find('[name=name]').val(feature.name);
                modal.find('select[name=category_id]').val(feature.category_id);
                modal.find('select[name=type]').val(feature.type);
                modal.find('.status').removeClass('d-none');
                modal.find('.first-option').val(feature.options[0]);

                var options = feature.options;

                for (let index = 1; index < options.length; index++) {
                    var html = `<div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control" name="options[]" type="text" value="${options[index]}" required>
                                        <span class="input-group-text bg--danger text-white remove-option border-0"><i class="las la-times"></i></span>
                                    </div>
                                </div`;
                    modal.find('.option-area').append(html);
                }

                if (feature.status == 1) {
                    modal.find('input[name=status]').bootstrapToggle('on');
                } else {
                    modal.find('input[name=status]').bootstrapToggle('off');
                }
                modal.modal('show')
            });

            modal.on('hidden.bs.modal', function() {
                $('.option-area').html('');
                $('#featureModal form')[0].reset();
            });

            $('.add-option').on('click', function() {
                var html = `<div class="form-group">
                                <div class="input-group">
                                    <input class="form-control" name="options[]" type="text" required>
                                    <span class="input-group-text bg--danger text-white remove-option border-0"><i class="las la-times"></i></span>
                                </div>
                            </div>`;
                $('.option-area').append(html);
            });

            $(document).on('click', '.remove-option', function() {
                $(this).closest('.form-group').remove();
            });



        })(jQuery);
    </script>
@endpush
