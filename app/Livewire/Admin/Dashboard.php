<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;

class Dashboard extends Component
{
    public function render()
    {
        // Contoh statistik sederhana
        $totalUsers = User::count();
        $recentUsers = User::latest()->take(5)->get();

        return view('livewire.admin.dashboard', [
            'totalUsers' => $totalUsers,
            'recentUsers' => $recentUsers,
        ])->layout('layouts.admin'); // Menggunakan layout khusus admin
    }
}
