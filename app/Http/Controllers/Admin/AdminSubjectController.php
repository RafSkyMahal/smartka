<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class AdminSubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::latest();

        if ($request->filled('class_level')) {
            $query->where('class_level', $request->class_level);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $subjects = $query->paginate(20)->withQueryString();
        return view('admin.mata-pelajaran.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.mata-pelajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'class_level' => 'required|in:6,9,12',
            'icon'        => 'nullable|string|max:50',
            'color_hex'   => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        Subject::create([
            'name'        => $request->name,
            'class_level' => $request->class_level,
            'icon'        => $request->icon ?? '📚',
            'color_hex'   => $request->color_hex ?? '#1a56db',
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    public function edit(Subject $mata_pelajaran)
    {
        return view('admin.mata-pelajaran.edit', ['subject' => $mata_pelajaran]);
    }

    public function update(Request $request, Subject $mata_pelajaran)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'class_level' => 'required|in:6,9,12',
            'icon'        => 'nullable|string|max:50',
            'color_hex'   => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $mata_pelajaran->update([
            'name'        => $request->name,
            'class_level' => $request->class_level,
            'icon'        => $request->icon ?? '📚',
            'color_hex'   => $request->color_hex ?? '#1a56db',
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    public function destroy(Subject $mata_pelajaran)
    {
        // Pengecekan sederhana apakah ada paket atau soal
        if ($mata_pelajaran->topics()->count() > 0 || $mata_pelajaran->questions()->count() > 0) {
            return back()->with('error', 'Tidak bisa dihapus karena masih ada topik atau soal yang menggunakan mata pelajaran ini.');
        }

        $mata_pelajaran->delete();
        return back()->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}
