<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ManageUsers extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';

    public $userId = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'user';
    public $is_active = true;

    public $isModalOpen = false;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'user';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min:3|max:100',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->userId)],
            'role' => 'required|in:admin,user',
            'is_active' => 'boolean',
        ];

        if (!$this->userId) {
            $rules['password'] = 'required|min:6';
        } else {
            $rules['password'] = 'nullable|min:6';
        }

        $this->validate($rules, [
            'name.required' => 'Nama lengkap wajib diisi!',
            'email.required' => 'Email wajib diisi!',
            'email.unique' => 'Email sudah digunakan oleh user lain!',
            'password.required' => 'Password wajib diisi untuk user baru!',
            'password.min' => 'Password minimal 6 karakter!',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->userId], $data);

        session()->flash('message', $this->userId ? 'Data pengguna berhasil diperbarui!' : 'Pengguna baru berhasil ditambahkan!');

        $this->closeModal();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->is_active = (bool) $user->is_active;

        $this->isModalOpen = true;
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Mencegah admin menonaktifkan akunnya sendiri
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri!');
            return;
        }

        $user->is_active = !$user->is_active;
        $user->save();

        session()->flash('message', 'Status akun ' . $user->name . ' berhasil diubah!');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            return;
        }

        $user->delete();
        session()->flash('message', 'Pengguna berhasil dihapus!');
    }

    public function render()
    {
        $query = User::withCount('stories')
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });

        if ($this->roleFilter !== '') {
            $query->where('role', $this->roleFilter);
        }

        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter);
        }

        $users = $query->latest()->paginate(10);

        return view('livewire.admin.manage-users', [
            'users' => $users
        ])->layout('layouts.admin');
    }
}
