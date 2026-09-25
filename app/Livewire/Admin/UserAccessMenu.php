<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Menu;
use Livewire\Component;

class UserAccessMenu extends Component
{
    public $userId;
    public $selectedMenus = [];

    public function mount($userId)
    {
        $this->userId = $userId;
        $user = User::findOrFail($userId);

        // Ambil ID menu yang sudah diizinkan untuk user ini
        $this->selectedMenus = $user->menus()->pluck('menus.id')->toArray();
    }

    public function saveAccess()
    {
        $user = User::findOrFail($this->userId);

        // Sync relasi menu_user
        $user->menus()->sync($this->selectedMenus);

        session()->flash('success', 'Akses menu berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.admin.user-access-menu', [
            'user' => User::findOrFail($this->userId),
            'menus' => Menu::orderBy('order', 'asc')->get(),
        ])->layout('layouts.admin');
    }
}
