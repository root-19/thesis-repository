<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserMessageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'message' => ['required', 'string'],
            'author_id' => ['required', 'exists:users,id'],
        ]);

        $author = User::where('id', $request->author_id)->where('role', 'author')->first();

        if (!$author) {
            return back()->with('error', 'No author found.');
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $author->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        broadcast(new MessageSent($message));

        // Notify author about new message
        Notification::create([
            'user_id' => $author->id,
            'type' => 'message',
            'data' => [
                'message' => auth()->user()->name . ' sent you a message',
                'sender_id' => auth()->id(),
            ],
            'notifiable_type' => Message::class,
            'notifiable_id' => $message->id,
        ]);

        return back()->with('status', 'Message sent successfully.');
    }

    public function index(): View
    {
        // Get only authors that the current user has messaged with
        $messagedAuthorIds = Message::where(function ($query) {
            $query->where('sender_id', auth()->id())
                ->orWhere('receiver_id', auth()->id());
        })->pluck('sender_id')
            ->merge(Message::where(function ($query) {
                $query->where('sender_id', auth()->id())
                    ->orWhere('receiver_id', auth()->id());
            })->pluck('receiver_id'))
            ->unique()
            ->toArray();

        $authors = User::where('role', 'author')
            ->whereIn('id', $messagedAuthorIds)
            ->get();

        return view('user-messages', compact('authors'));
    }

    public function show(User $author): View|RedirectResponse
    {
        if ($author->role !== 'author') {
            return back()->with('error', 'Invalid author.');
        }

        $messages = Message::with('receiver')
            ->where(function ($query) use ($author) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $author->id)
                    ->orWhere(function ($q) use ($author) {
                        $q->where('receiver_id', auth()->id())
                            ->where('sender_id', $author->id);
                    });
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('user-messages', compact('messages', 'author'));
    }
}
