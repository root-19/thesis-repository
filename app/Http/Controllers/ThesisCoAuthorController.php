<?php

namespace App\Http\Controllers;

use App\Models\Thesis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ThesisCoAuthorController extends Controller
{
    public function create(Thesis $thesis): View
    {
        return view('add-co-author', compact('thesis'));
    }

    public function search(Request $request): View
    {
        $search = $request->get('search', '');
        $thesisId = $request->get('thesis_id');

        $users = User::where('name', 'like', "%{$search}%")
            ->where('id', '!=', auth()->id())
            ->whereDoesntHave('theses', function ($query) use ($thesisId) {
                $query->where('thesis_id', $thesisId);
            })
            ->get();

        return view('search-users', compact('users', 'thesisId'));
    }

    public function store(Request $request, Thesis $thesis): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        // Check if user is already a co-author
        if ($thesis->coAuthors()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'User is already a co-author.');
        }

        $thesis->coAuthors()->attach($request->user_id);

        return back()->with('status', 'Co-author added successfully!');
    }

    public function destroy(Thesis $thesis, User $user): RedirectResponse
    {
        $thesis->coAuthors()->detach($user->id);

        return back()->with('status', 'Co-author removed successfully!');
    }
}
