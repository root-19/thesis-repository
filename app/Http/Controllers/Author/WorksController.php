<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorksController extends Controller
{
    public function index(): View
    {
        // Get all theses where the user is the primary author or a co-author
        $theses = Thesis::with('coAuthors')
            ->where('user_id', auth()->id())
            ->orWhereHas('coAuthors', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('author.works', compact('theses'));
    }

    public function edit(Thesis $thesis): View
    {
        // Check if user is authorized to edit this thesis
        if ($thesis->user_id !== auth()->id() && !$thesis->coAuthors()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }

        return view('author.thesis-edit', compact('thesis'));
    }

    public function update(Request $request, Thesis $thesis): RedirectResponse
    {
        // Check if user is authorized to edit this thesis
        if ($thesis->user_id !== auth()->id() && !$thesis->coAuthors()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'thesis_date' => ['required', 'date'],
            'keywords' => ['nullable', 'string'],
        ]);

        // Store original data for notification
        $originalData = [
            'title' => $thesis->title,
            'description' => $thesis->description,
            'thesis_date' => $thesis->thesis_date,
            'keywords' => $thesis->keywords,
        ];

        // Update thesis
        $thesis->update([
            'title' => $request->title,
            'description' => $request->description,
            'thesis_date' => $request->thesis_date,
            'keywords' => $request->keywords,
        ]);

        // Notify all admins about the edit request
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'thesis_edit_request',
                'data' => [
                    'title' => 'Thesis Edit Request',
                    'message' => auth()->user()->name . ' has requested an edit for thesis: ' . $thesis->title,
                    'thesis_id' => $thesis->id,
                    'original_data' => $originalData,
                    'new_data' => $request->only(['title', 'description', 'thesis_date', 'keywords']),
                ],
            ]);
        }

        return redirect()->route('author.works')->with('status', 'Edit request submitted for admin approval.');
    }

    public function destroy(Thesis $thesis): RedirectResponse
    {
        // Check if user is authorized to delete this thesis
        if ($thesis->user_id !== auth()->id() && !$thesis->coAuthors()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }

        // Notify all admins about the delete request
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'thesis_delete_request',
                'data' => [
                    'title' => 'Thesis Delete Request',
                    'message' => auth()->user()->name . ' has requested to delete thesis: ' . $thesis->title,
                    'thesis_id' => $thesis->id,
                ],
            ]);
        }

        return back()->with('status', 'Delete request submitted for admin approval.');
    }
}
