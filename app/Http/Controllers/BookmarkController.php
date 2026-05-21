<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Thesis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookmarkController extends Controller
{
    public function index(): View
    {
        $bookmarks = Bookmark::with(['thesis', 'thesis.user'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bookmarks', compact('bookmarks'));
    }

    public function store(Request $request, Thesis $thesis): RedirectResponse
    {
        $existingBookmark = Bookmark::where('user_id', auth()->id())
            ->where('thesis_id', $thesis->id)
            ->first();

        if ($existingBookmark) {
            return back()->with('status', 'Already bookmarked.');
        }

        Bookmark::create([
            'user_id' => auth()->id(),
            'thesis_id' => $thesis->id,
        ]);

        return back()->with('status', 'Thesis bookmarked successfully.');
    }

    public function destroy(Thesis $thesis): RedirectResponse
    {
        Bookmark::where('user_id', auth()->id())
            ->where('thesis_id', $thesis->id)
            ->delete();

        return back()->with('status', 'Bookmark removed.');
    }
}
