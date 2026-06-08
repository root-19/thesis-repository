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

        // Notify user about new message
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'message',
            'data' => [
                'message' => auth()->user()->name . ' sent you a message',
                'sender_id' => auth()->id(),
            ],
            'notifiable_type' => Message::class,
            'notifiable_id' => $message->id,
        ]);

        return redirect()->route('user.messages.show', $request->user_id)->with('status', 'Message sent successfully.');
    }

    public function index(): View
    {
        // Get all messages the user has sent or received
        $messages = Message::with('sender', 'receiver')
            ->where(function ($query) {
                $query->where('sender_id', auth()->id())
                    ->orWhere('receiver_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(function ($message) {
                // Group by the other person in the conversation
                return $message->sender_id === auth()->id() 
                    ? $message->receiver_id 
                    : $message->sender_id;
            });

        return view('user-messages', compact('messages'));
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

        return view('user-messages', compact('messages', 'user'));
    }
}
