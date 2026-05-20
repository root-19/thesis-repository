<?php

namespace App\Http\Controllers;

use App\Models\SavedThesis;
use App\Models\Thesis;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function saveThesis(Request $request, Thesis $thesis): RedirectResponse
    {
        $user = auth()->user();

        $savedThesis = SavedThesis::where('user_id', $user->id)
            ->where('thesis_id', $thesis->id)
            ->first();

        if ($savedThesis) {
            $savedThesis->delete();
            return back()->with('status', 'Thesis removed from saved.');
        } else {
            SavedThesis::create([
                'user_id' => $user->id,
                'thesis_id' => $thesis->id,
            ]);
            return back()->with('status', 'Thesis saved successfully.');
        }
    }
}
