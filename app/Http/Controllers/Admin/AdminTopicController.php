<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Subject;
use Illuminate\Http\Request;

class AdminTopicController extends Controller
{
    public function index(Request $request)
    {
        $query = Topic::with('subject')->latest();

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $topics = $query->paginate(20)->withQueryString();
        $subjects = Subject::all();

        return view('admin.topik.index', compact('topics', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('admin.topik.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id'   => 'required|exists:subjects,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string', // Markdown content
            'order_number' => 'required|integer|min:1',
        ]);

        Topic::create($request->only('subject_id', 'name', 'description', 'order_number'));

        return redirect()->route('admin.topik.index')
            ->with('success', 'Topik/Bab berhasil ditambahkan!');
    }

    public function edit(Topic $topik)
    {
        $subjects = Subject::all();
        return view('admin.topik.edit', compact('topik', 'subjects'));
    }

    public function update(Request $request, Topic $topik)
    {
        $request->validate([
            'subject_id'   => 'required|exists:subjects,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'order_number' => 'required|integer|min:1',
        ]);

        $topik->update($request->only('subject_id', 'name', 'description', 'order_number'));

        return redirect()->route('admin.topik.index')
            ->with('success', 'Topik/Bab berhasil diperbarui!');
    }

    public function destroy(Topic $topik)
    {
        $topik->delete();
        return back()->with('success', 'Topik/Bab berhasil dihapus!');
    }
}
