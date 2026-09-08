<?php

namespace App\Livewire\Admin;

use App\Models\Genre;
use Livewire\Component;
use Livewire\WithPagination;

class ManageGenres extends Component
{
    use WithPagination;

    public $name, $type = 'novel', $parent_id = null, $genre_id;
    public $search = '';
    public $filterType = '';

    public $isModalOpen = false;
    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:novel,puisi,non_fiksi',
        'parent_id' => 'nullable|exists:genres,id',
    ];

    public function updatingSearch()
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
        $this->name = '';
        $this->type = 'novel';
        $this->parent_id = null;
        $this->genre_id = null;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        Genre::updateOrCreate(
            ['id' => $this->genre_id],
            [
                'name' => $this->name,
                'type' => $this->type,
                'parent_id' => $this->parent_id ?: null,
            ]
        );

        session()->flash('message', $this->genre_id ? 'Genre berhasil diperbarui!' : 'Genre baru berhasil ditambahkan!');

        $this->closeModal();
    }

    public function edit($id)
    {
        $genre = Genre::findOrFail($id);
        $this->genre_id = $id;
        $this->name = $genre->name;
        $this->type = $genre->type ?? 'novel';
        $this->parent_id = $genre->parent_id;

        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Genre::findOrFail($id)->delete();
        session()->flash('message', 'Genre berhasil dihapus!');
    }

    public function render()
    {
        $parentGenres = Genre::whereNull('parent_id')
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->get();

        $genres = Genre::with('parent')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-genres', [
            'genres' => $genres,
            'parentGenres' => $parentGenres,
        ])->layout('layouts.admin');
    }
}
