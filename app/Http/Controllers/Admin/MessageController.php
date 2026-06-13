<?php

namespace App\Http\Controllers\Admin;

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
        $messages = Message::with('sender', 'receiver')
            ->where(function ($query) {
                $query->where('sender_id', auth()->id())
                    ->orWhere('receiver_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(function ($message) {
                return $message->sender_id === auth()->id()
                    ? $message->receiver_id
                    : $message->sender_id;
            });

        return view('admin.inbox', compact('messages'));
    }

    public function show(User $user): View
    {
        $messages = Message::with('sender', 'receiver')
            ->where(function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('sender_id', auth()->id())
                        ->where('receiver_id', $user->id);
                })->orWhere(function ($q) use ($user) {
                    $q->where('sender_id', $user->id)
                        ->where('receiver_id', auth()->id());
                });
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $messages->where('receiver_id', auth()->id())->each->update(['is_read' => true]);

        return view('admin.messages', compact('messages', 'user'));
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

        // Notify user about admin reply
        Notification::create([
            'user_id' => $user->id,
            'type' => 'message',
            'data' => [
                'message' => 'Admin replied to your message',
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

        // Notify user about new message from admin
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'message',
            'data' => [
                'message' => 'Admin sent you a message',
                'sender_id' => auth()->id(),
            ],
            'notifiable_type' => Message::class,
            'notifiable_id' => $message->id,
        ]);

        return redirect()->route('admin.messages.show', $request->user_id)->with('status', 'Message sent successfully.');
    }
}
