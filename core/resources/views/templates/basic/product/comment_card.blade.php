<div class="client-comment">
    <div class="d-flex comment-top mb-3 flex-wrap">
        <div class="thumb">
            <img src="{{ getImage(getFilePath('userProfile') . '/' . @$comment->user->image, getFileSize('userProfile')) }}" alt="@lang('user image')">
        </div>
        <div class="content">
            <h6 class="author-name">
                <a href="{{ route('author.profile', @$comment->user->username) }}">{{ $comment->user->username }}</a>
                @if ($comment->product->user_id == @$comment->user_id)
                    <span class="badge badge--primary">@lang('Author')</span>
                @else
                    <span class="badge badge--success">@lang('Client')</span>
                @endif
            </h6>
            <span class="time">{{ showDateTime($comment->created_at) }}</span>
        </div>
    </div>
    <p class="mb-4">{{ $comment->comment }}</p>

    @if ($comment->replies->count() > 0)
        @foreach ($comment->replies as $reply)
            @if ($comment->product->user_id == $reply->user_id && $comment->user_id == auth()->id())
                <button class="comment-box-open-btn bg--base replyBtn text-white" data-action="{{ route('user.product.comment.reply', $comment->id) }}" type="button"><i class="las la-reply"></i></button>
            @endif
            <div class="author-reply">
                <div class="d-flex comment-top ms-5 flex-wrap">
                    <div class="thumb">
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $reply->user->image, getFileSize('userProfile')) }}" alt="@lang('user image')">
                    </div>
                    <div class="content">
                        <h6 class="author-name">
                            <a href="{{ route('author.profile', @$reply->user->username) }}">{{ $reply->user->username }}
                                @if ($comment->product->user_id == @$reply->user_id)
                                    <span class="badge badge--primary">@lang('Author')</span>
                                @else
                                    <span class="badge badge--success">@lang('Client')</span>
                                @endif
                            </a>
                        </h6>
                        <span class="time">{{ showDateTime($reply->created_at) }}</span>
                    </div>
                    <p class="mt-3">{{ $reply->reply }}</p>
                </div>
            </div>
        @endforeach
    @endif
</div>
