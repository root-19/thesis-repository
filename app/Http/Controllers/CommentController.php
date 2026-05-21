<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Thesis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(Request $request, Thesis $thesis): RedirectResponse
    {
        $request->validate([
            'comment' => ['required', 'string', 'max:1000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        $comment = Comment::create([
            'thesis_id' => $thesis->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'parent_id' => $request->parent_id,
        ]);

        // Notify thesis author about new comment (not the commenter themselves)
        if ($thesis->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $thesis->user_id,
                'type' => 'comment',
                'data' => [
                    'message' => auth()->user()->name . ' commented on your thesis: ' . $thesis->title,
                    'thesis_id' => $thesis->id,
                    'comment_id' => $comment->id,
                ],
                'notifiable_type' => Thesis::class,
                'notifiable_id' => $thesis->id,
            ]);
        }

        // Notify parent comment author if this is a reply
        if ($request->parent_id) {
            $parentComment = Comment::find($request->parent_id);
            if ($parentComment && $parentComment->user_id !== auth()->id()) {
                Notification::create([
                    'user_id' => $parentComment->user_id,
                    'type' => 'reply',
                    'data' => [
                        'message' => auth()->user()->name . ' replied to your comment',
                        'thesis_id' => $thesis->id,
                        'comment_id' => $comment->id,
                    ],
                    'notifiable_type' => Comment::class,
                    'notifiable_id' => $parentComment->id,
                ]);
            }
        }

        return back()->with(['status' => 'Comment added successfully.', 'comment_submitted' => true, 'thesis_id' => $thesis->id]);
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();

        return back();
    }
}
