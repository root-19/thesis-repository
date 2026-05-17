<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Reaction;
use App\Models\Thesis;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class ReactionController extends Controller
{
    public function toggle(Thesis $thesis): RedirectResponse
    {
        $existingReaction = $thesis->reactions()->where('user_id', auth()->id())->first();

        if ($existingReaction) {
            $existingReaction->delete();
        } else {
            Reaction::create([
                'thesis_id' => $thesis->id,
                'user_id' => auth()->id(),
                'type' => 'heart',
            ]);

            // Notify thesis uploader about the like
            if ($thesis->user_id !== auth()->id()) {
                Notification::create([
                    'user_id' => $thesis->user_id,
                    'type' => 'like',
                    'data' => [
                        'message' => auth()->user()->name . ' liked your thesis',
                        'thesis_id' => $thesis->id,
                    ],
                    'notifiable_type' => Thesis::class,
                    'notifiable_id' => $thesis->id,
                ]);
            }
        }

        return back();
    }
}
