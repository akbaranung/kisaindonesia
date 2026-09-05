<?php

namespace App\Livewire\Story;

use App\Models\Category;
use App\Models\Genre;
use App\Models\Story;
use Livewire\Component;
use Livewire\WithPagination;

class StoryIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = '';
    public $selectedMonetization = '';
    public $sortBy = 'latest';

    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
        'selectedMonetization' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    public function mount()
    {
        if (request()->has('search')) {
            $this->search = request()->query('search');
        }
    }

    public function updatingSearch()
    {
        $this->resetPerPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPerPage();
    }

    public function updatingSelectedMonetization()
    {
        $this->resetPerPage();
    }

    public function updatingSortBy()
    {
        $this->resetPerPage();
    }

    public function resetPerPage()
    {
        $this->perPage = 12;
    }

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'selectedCategory', 'selectedMonetization', 'sortBy']);
        $this->resetPerPage();
    }

    public function render()
    {
        $query = Story::query()
            ->where('status', 'published')
            ->with(['penName'])
            ->withCount(['chapters' => function ($q) {
                $q->where('status', 'published');
            }])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('penName', function ($pq) {
                            $pq->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category', $this->selectedCategory);
            })
            ->when($this->selectedMonetization, function ($query) {
                $query->where('monetization_type', $this->selectedMonetization);
            })
            ->when($this->sortBy === 'latest', fn($q) => $q->latest())
            ->when($this->sortBy === 'popular', fn($q) => $q->orderByDesc('views_count'))
            ->when($this->sortBy === 'title', fn($q) => $q->orderBy('title', 'asc'));

        $totalStories = $query->count();
        $stories = $query->take($this->perPage)->get();
        $categories = Genre::all();

        return view('livewire.stories.story-index', [
            'stories' => $stories,
            'totalStories' => $totalStories,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
