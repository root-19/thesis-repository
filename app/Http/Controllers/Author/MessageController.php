<?php

namespace App\Http\Controllers\Author;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $messages = Message::with('sender')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('sender_id');

        return view('author.inbox', compact('messages'));
    }

    public function show(User $user): View
    {
        $messages = Message::with('sender', 'receiver')
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $messages->where('receiver_id', auth()->id())->each->update(['is_read' => true]);

        return view('author.messages', compact('messages', 'user'));
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'message' => ['required', 'string'],
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        broadcast(new MessageSent($message));

        // Notify user about author reply
        Notification::create([
            'user_id' => $user->id,
            'type' => 'message',
            'data' => [
                'message' => 'Author replied to your message',
                'sender_id' => auth()->id(),
            ],
            'notifiable_type' => Message::class,
            'notifiable_id' => $message->id,
        ]);

        return back()->with('status', 'Message sent successfully.');
    }

    public function newMessage(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'message' => ['required', 'string'],
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->user_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        broadcast(new MessageSent($message));

        // Notify user about new message from author
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'message',
            'data' => [
                'message' => 'Author sent you a message',
                'sender_id' => auth()->id(),
            ],
            'notifiable_type' => Message::class,
            'notifiable_id' => $message->id,
        ]);

        return redirect()->route('author.messages.show', $request->user_id)->with('status', 'Message sent successfully.');
    }
}
