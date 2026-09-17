<?php

namespace App\Livewire\Category;

use App\Models\Genre;
use App\Models\Story;
use Livewire\Component;

class CategoryDetail extends Component
{
    public $parentCategory;
    public $subCategories;

    public string $search = '';
    public string $selectedSubCategory = ''; // ID sub-kategori terpilih

    public int $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedSubCategory' => ['except' => ''],
    ];

    public function mount(int $id)
    {
        $this->parentCategory = Genre::findOrFail($id);

        $this->subCategories = Genre::where('parent_id', $this->parentCategory->id)->get();
    }

    public function updatingSearch()
    {
        $this->resetPerPage();
    }

    public function updatingSelectedSubCategory()
    {
        $this->resetPerPage();
    }

    public function selectSubCategory(string $subCategoryId)
    {
        $this->selectedSubCategory = $this->selectedSubCategory === (string) $subCategoryId ? '' : (string) $subCategoryId;
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
        $this->reset(['search', 'selectedSubCategory']);
        $this->resetPerPage();
    }

    public function render()
    {
        // Ambil semua ID genre di bawah parent ini
        $allParentGenreIds = $this->subCategories->pluck('id')->push($this->parentCategory->id)->toArray();

        // Tentukan ID mana yang dipakai untuk query cerita (sub-kategori terpilih atau semua)
        if (!empty($this->selectedSubCategory)) {
            $genreIds = [(int) $this->selectedSubCategory];
        } else {
            $genreIds = $allParentGenreIds;
        }

        // 1. Query Pilihan Editor khusus parent kategori ini
        $editorChoices = Story::with(['penName', 'genre'])
            ->where('status', 'published')
            ->where('is_editor_choice', true)
            ->whereIn('category', $allParentGenreIds)
            ->latest()
            ->take(6)
            ->get();

        // 2. Query Cerita dengan Filter & Searching
        $query = Story::query()
            ->where('status', 'published')
            ->whereIn('category', $genreIds)
            ->with(['penName', 'genre'])
            ->when($this->search, function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('penName', function ($pq) {
                            $pq->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            });

        $totalStories = $query->count();
        $stories = $query->latest()->take($this->perPage)->get();

        return view('livewire.category.category-detail', [
            'editorChoices' => $editorChoices,
            'stories' => $stories,
            'totalStories' => $totalStories,
        ])->layout('layouts.app');
    }
}
