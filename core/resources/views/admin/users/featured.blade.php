@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('SL')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Username')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Featured at')</th>
                                    <th>@lang('Revoked at')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse ($authors as $author)
                                    <tr>
                                        <td>{{ $authors->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="name">{{ @$author->user->fullname }}</span>
                                        </td>
                                        <td><a href="{{ route('admin.users.detail', @$author->user->id ?? 0) }}">{{ @$author->user->username }}</a></td>
                                        <td>
                                            @if ($loop->index == 0)
                                                <span class="badge badge--primary">@lang('Featured')</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ showDateTime($author->created_at, 'd M, Y') }}
                                        </td>
                                        <td>{{ $author->revoked_at ? showDateTime($author->revoked_at, 'd M, Y') : 'N/A' }}</td>
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
                @if ($authors->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($authors) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Add New Level')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                <form method="POST" action="{{ route('admin.users.make.featured') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Enter Username')</label>
                            <input class="form-control" name="username" type="text" placeholder="@lang('Valid username')" required>
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
@endpush
@push('script')
    <script>
        'use strict';

        (function($) {
            $('.createButton').on('click', function() {
                var modal = $('#addModal');
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
