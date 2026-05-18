<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TestPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    /**
     * Display a listing of the test packages.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $classLevel = $user->class_level;

        $packages = TestPackage::where('class_level', $classLevel)
                               ->where('status', 'published')
                               ->orderBy('created_at', 'desc')
                               ->get();

        return view('latihan.index', compact('packages'));
    }

    /**
     * Display the specified test package.
     */
    public function show(TestPackage $package)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ensure user can access this package based on class level and status
        if ($user->class_level !== $package->class_level || $package->status !== 'published') {
            abort(403, 'Akses ditolak.');
        }

        return view('latihan.show', compact('package'));
    }
}
