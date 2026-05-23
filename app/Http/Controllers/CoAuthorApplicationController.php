<?php

namespace App\Http\Controllers;

use App\Models\CoAuthorApplication;
use App\Models\Thesis;
use App\Models\User;
use App\Models\Notification as NotificationModel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

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
            'keywords' => ['nullable', 'string'],
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
            'keywords' => $request->keywords,
            'status' => 'pending',
        ]);

        // Attach co-authors if provided
        if ($request->has('co_authors')) {
            $application->coAuthors()->attach($request->co_authors);
        }

        // Notify all admins about the new application
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationModel::create([
                'user_id' => $admin->id,
                'type' => 'researcher_application',
                'data' => [
                    'title' => 'New Researcher Application',
                    'message' => "{$application->user->name} has submitted a researcher application for: {$application->title}",
                    'application_id' => $application->id,
                ],
            ]);
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

        // Check if this is a co-researcher request for an existing thesis
        if ($application->thesis_id) {
            $thesis = Thesis::findOrFail($application->thesis_id);

            // Add applicant as co-author to the existing thesis
            if (!$thesis->coAuthors()->where('user_id', $application->user_id)->exists()) {
                $thesis->coAuthors()->attach($application->user_id);
            }

            // Change applicant role to author
            $application->user->update(['role' => 'author']);

            // Notify the applicant
            NotificationModel::create([
                'user_id' => $application->user_id,
                'type' => 'co_researcher_approved',
                'data' => [
                    'title' => 'Co-Researcher Request Approved',
                    'message' => "Your request to become a co-researcher for '{$thesis->title}' has been approved.",
                    'thesis_id' => $thesis->id,
                ],
            ]);

            return back()->with('status', 'Co-researcher request approved! User has been added as a co-author.');
        }

        // Otherwise, this is a new researcher application — create a new thesis
        // Change applicant role to author
        $application->user->update(['role' => 'author']);

        // Change co-authors role to author
        $application->coAuthors()->each(function ($coAuthor) {
            $coAuthor->update(['role' => 'author']);
        });

        // Move PDF file from co-author-applications to theses folder
        $newPdfPath = str_replace('co-author-applications/', 'theses/', $application->pdf_file_path);
        Storage::disk('public')->copy($application->pdf_file_path, $newPdfPath);

        // Create thesis from application
        $thesis = Thesis::create([
            'user_id' => $application->user_id,
            'title' => $application->title,
            'description' => $application->description,
            'thesis_date' => $application->thesis_date,
            'author' => $application->user->name,
            'pdf_file_path' => $newPdfPath,
            'keywords' => $application->keywords,
        ]);

        // Attach co-authors to thesis
        if ($application->coAuthors()->count() > 0) {
            $coAuthorIds = $application->coAuthors()->pluck('users.id')->toArray();
            $thesis->coAuthors()->attach($coAuthorIds);
        }

        // Notify the applicant that their application was approved and thesis uploaded
        NotificationModel::create([
            'user_id' => $application->user_id,
            'type' => 'application_approved',
            'data' => [
                'title' => 'Application Approved',
                'message' => "Your co-author application for '{$application->title}' has been approved and your thesis has been uploaded.",
                'thesis_id' => $thesis->id,
            ],
        ]);

        // Notify co-authors about the thesis upload
        foreach ($application->coAuthors as $coAuthor) {
            NotificationModel::create([
                'user_id' => $coAuthor->id,
                'type' => 'thesis_uploaded',
                'data' => [
                    'title' => 'New Thesis Uploaded',
                    'message' => "A new thesis '{$thesis->title}' has been uploaded with you as a co-author.",
                    'thesis_id' => $thesis->id,
                ],
            ]);
        }

        return back()->with('status', 'Application approved! Users are now authors and thesis has been uploaded.');
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
