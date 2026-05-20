<?php

namespace App\Http\Controllers;

use App\Models\AuthorRecommendation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthorRecommendationController extends Controller
{
    public function create(): View
    {
        $recommendations = AuthorRecommendation::with(['recommender', 'recommendedUser'])
            ->where('recommender_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('author.recommend', compact('recommendations'));
    }

    public function team(): View
    {
        $authors = \App\Models\User::where('role', 'author')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('author.team', compact('authors'));
    }

    public function userTeam(): View
    {
        $authors = \App\Models\User::where('role', 'author')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.author-team', compact('authors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'recommendation_type' => ['required', 'in:existing_user,new_user'],
            'recommended_user_id' => ['required_if:recommendation_type,existing_user', 'exists:users,id'],
            'recommended_name' => ['nullable', 'required_if:recommendation_type,new_user', 'string', 'max:255'],
            'recommended_email' => ['nullable', 'required_if:recommendation_type,new_user', 'email', 'max:255'],
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        // Check for duplicate pending recommendations
        if ($request->recommendation_type === 'existing_user') {
            $existingRecommendation = AuthorRecommendation::where('recommended_user_id', $request->recommended_user_id)
                ->where('status', 'pending')
                ->exists();

            if ($existingRecommendation) {
                return back()->withErrors(['recommendation' => 'This user already has a pending recommendation.'])->withInput();
            }
        } else {
            $existingRecommendation = AuthorRecommendation::where('recommended_email', $request->recommended_email)
                ->where('status', 'pending')
                ->exists();

            if ($existingRecommendation) {
                return back()->withErrors(['recommendation' => 'This email already has a pending recommendation.'])->withInput();
            }
        }

        $data = [
            'recommender_id' => auth()->id(),
            'reason' => $request->reason,
            'status' => 'pending',
        ];

        if ($request->recommendation_type === 'existing_user') {
            $data['recommended_user_id'] = $request->recommended_user_id;
        } else {
            $data['recommended_name'] = $request->recommended_name;
            $data['recommended_email'] = $request->recommended_email;
        }

        AuthorRecommendation::create($data);

        return back()->with('status', 'Recommendation submitted successfully!');
    }

    public function index(): View
    {
        $recommendations = AuthorRecommendation::with(['recommender', 'recommendedUser'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.author-recommendations', compact('recommendations'));
    }

    public function approve(AuthorRecommendation $recommendation): RedirectResponse
    {
        $recommendation->update(['status' => 'approved']);

        // If the recommended user exists and is not already an author, update their role
        if ($recommendation->recommended_user_id) {
            $user = $recommendation->recommendedUser;
            if ($user && $user->role !== 'author') {
                $user->update(['role' => 'author']);
            }
        }

        return back()->with('status', 'Recommendation approved successfully!');
    }

    public function reject(Request $request, AuthorRecommendation $recommendation): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $recommendation->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('status', 'Recommendation rejected successfully!');
    }
}
