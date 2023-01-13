<div class="mt-4">
    <div class="product-comment show-comments">
        @forelse ($comments as $comment)
            @if ($product->user_id == auth()->id())
                <button class="comment-box-open-btn bg--base replyBtn text-white" data-action="{{ route('user.product.comment.reply', $comment->id) }}" type="button"><i class="las la-reply"></i></button>
            @endif
            @include($activeTemplate . 'product.comment_card', ['comment' => $comment])
        @empty
            <div class="no-message py-3 text-center">
                <h4 class="title fw-normal text--muted">@lang('No comments to display yet')</h4>
                <i class="far fa-comment-dots text--muted mt-2"></i>
            </div>
        @endforelse
    </div>
</div>
<div class="pagination--sm justify-content-end mt-3">
    {{ paginateLinks($comments) }}
</div>

@auth
    <div class="product-comment-form-area">
        <div class="form-group">
            <textarea class="form--control" name="comment" placeholder="@lang('Write your comment')..." required></textarea>
        </div>
        <div class="form-group text-end">
            <button class="btn btn-sm btn--base commentBtn" type="button">@lang('Submit')</button>
        </div>
    </div>
@else
    <div class="mt-4">
        @lang('Please') <a class="text--base" href="{{ route('user.login') }}">@lang('Login')</a> @lang('to submit your comment')
    </div>
@endauth
