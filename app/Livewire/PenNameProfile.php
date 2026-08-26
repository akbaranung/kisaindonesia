<?php

namespace App\Livewire;

use App\Models\Genre;
use App\Models\PenName;
use App\Models\Story;
use Livewire\Component;
use Livewire\WithPagination;

class PenNameProfile extends Component
{
    use WithPagination;

    public PenName $penName;

    public string $search = '';
    public string $status = '';
    public string $genreId = '';
    public string $sortBy = 'latest';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'genreId' => ['except' => ''],
        'sortBy' => ['except' => 'latest']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingGenreId()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'status', 'genreId', 'sortBy']);
    }

    public function mount(string $slug)
    {
        $this->penName = PenName::where('slug', $slug)->withCount('followers')->firstOrFail();
    }

    public function render()
    {
        $query = Story::where('pen_name_id', $this->penName->id)->where('status', 'published');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')->orWhere('synopsis', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->status)) {
            $query->where('story_status', $this->status); // ongoing/end
        }

        if (!empty($this->genreId)) {
            $query->where('category', $this->genreId);
        }

        match ($this->sortBy) {
            'oldest' => $query->oldest(),
            'popular' => $query->orderBy('views_count', 'desc'), // Sesuaikan kolom popularitas/views
            default => $query->latest(),
        };

        $stories = $query->paginate(12);

        // Ambil daftar genre untuk dropdown filter (opsional)
        $genres = class_exists(Genre::class) ? Genre::orderBy('name')->get() : collect();


        return view('livewire.pen-name-profile', [
            'stories' => $stories,
            'genres' => $genres,
        ])->layout('layouts.app');
    }
}
