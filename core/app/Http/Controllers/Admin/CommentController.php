<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Reply;
use Illuminate\Http\Request;

class CommentController extends Controller {
    public function index(Request $request) {
        $pageTitle = 'All Comment';
        $comments  = Comment::searchable(['comment', 'user:username', 'product:name'])->with(['product:id,name', 'user'])->latest()->paginate(getPaginate());
        return view('admin.comment.index', compact('pageTitle', 'comments'));
    }

    public function delete($id) {
        $comment = Comment::findOrFail($id);
        $comment->replies()->delete();
        $comment->delete();

        $notify[] = ['success', 'Comment removed successfully'];
        return back()->withNotify($notify);
    }

    public function replies(Request $request, $id) {
        $pageTitle = 'Comment Replies';
        $replies   = Reply::where('comment_id', $id)->searchable(['reply', 'user:username'])->with('user')->latest()->paginate(getPaginate());
        return view('admin.comment.replies', compact('pageTitle', 'replies'));
    }

    public function replyDelete($id) {
        Reply::where('id', $id)->delete();
        $notify[] = ['success', 'Reply removed successfully'];
        return back()->withNotify($notify);
    }
}
