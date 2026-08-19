<?php

namespace App\Livewire\Admin;

use App\Models\Genre;
use Livewire\Component;
use Livewire\WithPagination;

class ManageGenres extends Component
{
    use WithPagination;

    public $search = '';
    public $name = '';
    public $genreId = null;

    public $isModalOpen = false;
    protected $paginationTheme = 'tailwind';

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
        $this->genreId = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate(
            [
                'name' => 'required|min:3|max:50|unique:genres,name,' . $this->genreId,
            ],
            [
                'name.required' => 'Nama genre wajib diisi!',
                'name.unique' => 'Nama genre ini sudah ada!',
                'name.min' => 'Nama genre minimal 3 karakter!',
                'name.max' => 'Nama genre maksimal 50 karakter!'
            ]
        );

        Genre::updateOrCreate(
            ['id' => $this->genreId],
            ['name' => $this->name]
        );

        session()->flash('message', $this->genreId ? 'Genre berhasil diperbarui!' : 'Genre baru berhasil ditambahkan!');

        $this->closeModal();
    }

    public function edit($id)
    {
        $genre = Genre::findOrFail($id);
        $this->genreId = $id;
        $this->name = $genre->name;

        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Genre::findOrFail($id)->delete();
        session()->flash('message', 'Genre berhasil dihapus!');
    }

    public function render()
    {
        $genres = Genre::where('name', 'like', '%' . $this->search . '%')->latest()->paginate(10);

        return view('livewire.admin.manage-genres', [
            'genres' => $genres
        ])->layout('layouts.admin');
    }
}
