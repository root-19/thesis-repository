<?php

namespace App\Http\Controllers;

use App\Models\CoAuthorApplication;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CoAuthorApplicationController extends Controller
{
    public function create(): View
    {
        return view('co-author-application');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'thesis_date' => ['required', 'date'],
            'pdf_file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'co_authors' => ['array'],
            'co_authors.*' => ['exists:users,id'],
        ]);

        $pdfPath = $request->file('pdf_file')->store('co-author-applications', 'public');

        $application = CoAuthorApplication::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'thesis_date' => $request->thesis_date,
            'pdf_file_path' => $pdfPath,
            'status' => 'pending',
        ]);

        // Attach co-authors if provided
        if ($request->has('co_authors')) {
            $application->coAuthors()->attach($request->co_authors);
        }

        return back()->with('status', 'Co-author application submitted successfully!');
    }

    public function index(): View
    {
        $applications = CoAuthorApplication::with(['user', 'coAuthors'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.co-author-applications', compact('applications'));
    }

    public function approve(CoAuthorApplication $application): RedirectResponse
    {
        $application->update(['status' => 'approved']);

        // Change applicant role to author
        $application->user->update(['role' => 'author']);

        // Change co-authors role to author
        $application->coAuthors()->each(function ($coAuthor) {
            $coAuthor->update(['role' => 'author']);
        });

        return back()->with('status', 'Application approved! Users are now authors.');
    }

    public function reject(Request $request, CoAuthorApplication $application): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('status', 'Application rejected successfully!');
    }
}
