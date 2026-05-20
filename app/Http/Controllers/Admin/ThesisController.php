<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ThesisController extends Controller
{
    public function index(): View
    {
        $theses = Thesis::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.theses', compact('theses'));
    }

    public function create(): View
    {
        return view('admin.thesis-create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'thesis_date' => ['required', 'date'],
            'department' => ['required', 'string', 'max:255'],
            'pdf_file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'co_authors' => ['required', 'array', 'min:1'],
            'co_authors.*' => ['exists:users,id'],
            'keywords' => ['nullable', 'string'],
        ]);

        $pdfPath = $request->file('pdf_file')->store('theses', 'public');

        $firstAuthor = \App\Models\User::find($request->co_authors[0]);

        $thesis = Thesis::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'thesis_date' => $request->thesis_date,
            'department' => $request->department,
            'author' => $firstAuthor ? $firstAuthor->name : 'Unknown',
            'pdf_file_path' => $pdfPath,
            'keywords' => $request->keywords,
        ]);

        // Attach co-authors to thesis and change their role to author
        if ($request->has('co_authors')) {
            $thesis->coAuthors()->attach($request->co_authors);
            
            // Change co-authors role to author
            \App\Models\User::whereIn('id', $request->co_authors)->update(['role' => 'author']);
        }

        return redirect()->route('admin.theses')->with('status', 'Thesis uploaded successfully. Authors added to thesis.');
    }

    public function destroy(Thesis $thesis): RedirectResponse
    {
        if (Storage::disk('public')->exists($thesis->pdf_file_path)) {
            Storage::disk('public')->delete($thesis->pdf_file_path);
        }

        $thesis->delete();

        return back()->with('status', 'Thesis deleted successfully.');
    }

    public function edit(Thesis $thesis): View
    {
        return view('admin.thesis-edit', compact('thesis'));
    }

    public function update(Request $request, Thesis $thesis): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'thesis_date' => ['required', 'date'],
            'department' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'co_authors' => ['nullable', 'array'],
            'co_authors.*' => ['exists:users,id'],
            'keywords' => ['nullable', 'string'],
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'thesis_date' => $request->thesis_date,
            'department' => $request->department,
            'author' => $request->author,
            'keywords' => $request->keywords,
        ];

        if ($request->hasFile('pdf_file')) {
            // Delete old PDF
            if (Storage::disk('public')->exists($thesis->pdf_file_path)) {
                Storage::disk('public')->delete($thesis->pdf_file_path);
            }
            // Store new PDF
            $pdfPath = $request->file('pdf_file')->store('theses', 'public');
            $data['pdf_file_path'] = $pdfPath;
        }

        $thesis->update($data);

        // Sync co-authors to thesis and change their role to author
        if ($request->has('co_authors')) {
            $thesis->coAuthors()->sync($request->co_authors);
            
            // Change co-authors role to author
            \App\Models\User::whereIn('id', $request->co_authors)->update(['role' => 'author']);
        } else {
            // Remove all co-authors if none selected
            $thesis->coAuthors()->detach();
        }

        return redirect()->route('admin.theses')->with('status', 'Thesis updated successfully.');
    }
}
