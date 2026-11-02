<?php

namespace App\Livewire\Admin;

use App\Models\Story;
use Livewire\Component;
use App\Models\User;

class Dashboard extends Component
{
    public function render()
    {
        // Contoh statistik sederhana
        $totalUsers = User::count();
        $recentUsers = User::latest()->take(5)->get();
        $storiesPublished = Story::where('status', 'published')->count();
        $users = User::all();

        return view('livewire.admin.dashboard', [
            'totalUsers' => $totalUsers,
            'recentUsers' => $recentUsers,
            'storiesPublished' => $storiesPublished,
            'totalKoin' => $users->sum('kisa_bean_balance'),
            'totalRoyalti' => $users->sum('earned_beans')
        ])->layout('layouts.admin'); // Menggunakan layout khusus admin
    }
}
