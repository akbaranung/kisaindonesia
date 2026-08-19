<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // Mengirim data user yang login (jika ada) dan data dummy cerita ke React
        $stories = Story::where('status', 'published')
            ->with(['chapters', 'characters']) // Eager loading biar hemat query
            ->latest() // Urutkan dari yang terbaru dirilis
            ->get(); // Atau ->paginate(6);

        return view('home', compact('stories'));
    }
}
