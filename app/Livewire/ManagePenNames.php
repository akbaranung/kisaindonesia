<?php

namespace App\Livewire;

use App\Models\PenName;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManagePenNames extends Component
{
    use WithFileUploads;

    // State Modal & Form
    public bool $showModal = false;
    public ?int $penNameId = null; // null = Tambah, filled = Edit

    public string $name = '';
    public string $bio = '';
    public $avatar;
    public ?string $currentAvatar = null;

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('pen_names', 'name')->ignore($this->penNameId),
            ],
            'bio' => 'nullable|string|max:250',
            'avatar' => 'nullable|image|max:2048',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama pena wajib diisi.',
        'name.min' => 'Nama pena minimal 3 karakter.',
        'name.max' => 'Nama pena maksimal 50 karakter.',
        'name.unique' => 'Nama pena ini sudah digunakan oleh penulis lain.',
        'bio.max' => 'Bio tidak boleh lebih dari 250 karakter.',
        'avatar.image' => 'File harus berupa gambar.',
        'avatar.max' => 'Ukuran gambar maksimal 2 MB.',
    ];

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id)
    {
        $this->resetForm();

        $penName = auth()->user()->penNames()->findOrFail($id);

        $this->penNameId = $penName->id;
        $this->name = $penName->name;
        $this->bio = $penName->bio ?? '';
        $this->currentAvatar = $penName->avatar;

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['showModal', 'penNameId', 'name', 'bio', 'avatar', 'currentAvatar']);
        $this->resetErrorBag();
    }

    public function updatedName()
    {
        $this->validateOnly('name');
    }

    public function save()
    {
        $this->validate();

        if ($this->penNameId) {
            // EDIT MODE
            $penName = auth()->user()->penNames()->findOrFail($this->penNameId);

            $avatarPath = $penName->avatar;
            if ($this->avatar) {
                $avatarPath = $this->avatar->store('pen-names/avatars', 'public');
            }

            $penName->update([
                'name' => trim($this->name),
                'slug' => Str::slug($this->name),
                'bio' => trim($this->bio),
                'avatar' => $avatarPath,
            ]);

            session()->flash('message', 'Nama pena "' . $penName->name . '" berhasil diperbarui!');
        } else {
            // CREATE MODE
            $avatarPath = null;
            if ($this->avatar) {
                $avatarPath = $this->avatar->store('pen-names/avatars', 'public');
            }

            $penName = PenName::create([
                'user_id' => auth()->id(),
                'name' => trim($this->name),
                'slug' => Str::slug($this->name),
                'bio' => trim($this->bio),
                'avatar' => $avatarPath,
            ]);

            session()->flash('message', 'Nama pena "' . $penName->name . '" berhasil dibuat!');
        }

        $this->closeModal();
    }

    public function render()
    {
        $penNames = auth()->user()->penNames()
            ->withCount(['stories', 'followers'])
            ->latest()
            ->get();

        return view('livewire.manage-pen-names', [
            'penNames' => $penNames,
        ])->layout('layouts.app');
    }
}
