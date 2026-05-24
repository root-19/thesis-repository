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
        $recommendations = AuthorRecommendation::with(['recommender', 'recommendedUser', 'thesis'])
            ->where('recommender_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $theses = \App\Models\Thesis::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('author.recommend', compact('recommendations', 'theses'));
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
            'thesis_id' => ['nullable', 'exists:theses,id'],
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
            'thesis_id' => $request->thesis_id,
        ];

        if ($request->recommendation_type === 'existing_user') {
            $data['recommended_user_id'] = $request->recommended_user_id;
        } else {
            $data['recommended_name'] = $request->recommended_name;
            $data['recommended_email'] = $request->recommended_email;
        }

        $recommendation = AuthorRecommendation::create($data);

        // Notify all admins about the new recommendation
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'type' => 'researcher_recommendation',
                'data' => [
                    'title' => 'New Researcher Recommendation',
                    'message' => auth()->user()->name . ' has recommended a new researcher to join the team.',
                    'recommendation_id' => $recommendation->id,
                ],
            ]);
        }

        return back()->with('status', 'Recommendation submitted successfully!');
    }

    public function index(): View
    {
        $recommendations = AuthorRecommendation::with(['recommender', 'recommendedUser', 'thesis'])
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

            // Notify the user that their application has been approved
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'type' => 'application_approved',
                'data' => [
                    'title' => 'Application Approved',
                    'message' => 'Your application to become an author has been approved.',
                ],
            ]);

            // Add the user as a co-author to the thesis if thesis_id is set
            if ($recommendation->thesis_id && $user) {
                $thesis = \App\Models\Thesis::find($recommendation->thesis_id);
                if ($thesis) {
                    // Check if user is not already a co-author
                    if (!$thesis->coAuthors()->where('user_id', $user->id)->exists()) {
                        $thesis->coAuthors()->attach($user->id);
                    }
                }
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
