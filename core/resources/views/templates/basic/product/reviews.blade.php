<div class="pt-4">
    <h3 class="mb-3">{{ @$product->reviews_count }} @lang(' Reviews Found')</h3>

    @forelse ($reviews as $review)
        <div class="single-review">
            <div class="single-review__top">
                <div class="left">
                    <h6 class="author-name"><a href="{{ route('author.profile', $review->user->username) }}">{{ $review->user->username }}</a></h6>
                    <span class="time">{{ showDateTime($review->created_at) }}</span>
                    @if ($review->status == Status::REVIEW_REPORTED)
                        (<code>@lang('This review is under reported')</code>)
                    @endif
                </div>
                <div class="right">
                    <div class="ratings">
                        @php echo displayRating($review->rating) @endphp
                    </div>
                </div>
            </div>
            @if ($review->status != Status::REVIEW_REPORTED)
                <p>
                    {{ __($review->review) }}
                </p>
            @endif
        </div>
    @empty
        <div class="product-comment">
            <div class="no-message py-3 text-center">
                <h4 class="title fw-normal text--muted">@lang('No reviews to display yet')</h4>
                <i class="far fa-comment-dots text--muted mt-2"></i>
            </div>
        </div>
    @endforelse
</div>

<div class="pagination--sm justify-content-end mt-3">
    {{ paginateLinks($reviews) }}
</div>
