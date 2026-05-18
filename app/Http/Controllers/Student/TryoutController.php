<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TestPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TryoutController extends Controller
{
    /**
     * Display a listing of try out packages.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $classLevel = $user->class_level;

        // Cari paket yang difokuskan sebagai Try Out berdasarkan namanya
        $packages = TestPackage::where('class_level', $classLevel)
                               ->where('status', 'published')
                               ->where(function($q) {
                                   $q->where('name', 'LIKE', '%Try Out%')
                                     ->orWhere('name', 'LIKE', '%Tryout%')
                                     ->orWhere('name', 'LIKE', '%TO %');
                               })
                               ->orderBy('created_at', 'desc')
                               ->get();

        return view('tryout.index', compact('packages'));
    }
}
