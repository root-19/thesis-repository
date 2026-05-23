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
        // Check if user already has a pending application for this thesis
        $existingApplication = CoAuthorApplication::where('user_id', auth()->id())
            ->where('thesis_id', $thesis->id)
            ->where('status', 'pending')
            ->first();

        if ($existingApplication) {
            return back()->with('error', 'You already have a pending application for this thesis.');
        }

        // Check if user is already a co-author
        if ($thesis->coAuthors()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You are already a co-researcher for this thesis.');
        }

        CoAuthorApplication::create([
            'user_id' => auth()->id(),
            'thesis_id' => $thesis->id,
            'title' => $thesis->title,
            'description' => $thesis->description,
            'thesis_date' => $thesis->thesis_date,
            'pdf_file_path' => $thesis->pdf_file_path,
            'department' => $thesis->department,
            'keywords' => $thesis->keywords,
            'status' => 'pending',
        ]);

        // Notify all admins about the co-researcher request
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'co_researcher_request',
                'data' => [
                    'title' => 'Co-Researcher Request',
                    'message' => auth()->user()->name . ' has requested to become a co-researcher for thesis: ' . $thesis->title,
                    'thesis_id' => $thesis->id,
                    'user_id' => auth()->id(),
                ],
            ]);
        }

        return back()->with('status', 'Co-researcher request sent to admin for approval.');
    }
}
