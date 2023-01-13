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
                                    <th>@lang('SL')</th>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Action By')</th>
                                    <th>@lang('Updated at')</th>
                                </tr>
                            </thead>

                            <tbody class="list">
                                @forelse ($histories as $history)
                                    <tr>
                                        <td>{{ $histories->firstItem() + $loop->index }}</td>
                                        <td>
                                            @lang('Product updated to') <span class="fw-bold">{{ __($history->statusText) }}</span>
                                        </td>
                                        <td>
                                            @if ($history->admin_id)
                                                {{ $history->admin->name }}
                                            @else
                                                {{ $history->reviewer->fullname }}
                                                <br />
                                                <a href="{{ route('admin.reviewers.detail', $history->reviewer_id) }}"><span>@</span>{{ $history->reviewer->username }}</a>
                                            @endif
                                        </td>
                                        <td>
                                            {{ showDateTime($history->updated_at) }}
                                            <br />
                                            {{ diffForHumans($history->updated_at) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($histories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($histories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.product.detail', $product->id) }}" />
@endpush
