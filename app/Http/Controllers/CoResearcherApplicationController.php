<?php

namespace App\Http\Controllers;

use App\Models\CoAuthorApplication;
use App\Models\Notification;
use App\Models\Thesis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CoResearcherApplicationController extends Controller
{
    public function search(): View
    {
        return view('co-researcher-search');
    }

    public function searchResults(Request $request): View
    {
        $query = $request->input('query');
        
        $theses = Thesis::query()
            ->where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('keywords', 'like', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('co-researcher-search', compact('theses', 'query'));
    }

    public function request(Thesis $thesis): RedirectResponse
    {
        // Check if user already has a pending recommendation for this thesis
        $existingRecommendation = \App\Models\AuthorRecommendation::where('recommended_user_id', auth()->id())
            ->where('thesis_id', $thesis->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRecommendation) {
            return back()->with('error', 'You already have a pending co-researcher request for this thesis.');
        }

        // Check if user is already a co-author
        if ($thesis->coAuthors()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You are already a co-researcher for this thesis.');
        }

        // Create a self-recommendation (user recommends themselves as co-researcher)
        \App\Models\AuthorRecommendation::create([
            'recommender_id' => auth()->id(),
            'recommended_user_id' => auth()->id(),
            'reason' => 'Requesting to become a co-researcher for this thesis.',
            'status' => 'pending',
            'thesis_id' => $thesis->id,
        ]);

        // Notify all admins about the co-researcher request
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'researcher_recommendation',
                'data' => [
                    'title' => 'Co-Researcher Request',
                    'message' => auth()->user()->name . ' has requested to become a co-researcher for thesis: ' . $thesis->title,
                    'thesis_id' => $thesis->id,
                    'user_id' => auth()->id(),
                ],
            ]);
        }

        return redirect()->route('author.recommendation.create')
            ->with('status', 'Co-researcher request sent to admin for approval.');
    }
}
