<?php

namespace App\Livewire\Admin;

use App\Models\Genre;
use App\Models\Story;
use Livewire\Component;
use Livewire\WithPagination;

class StoryManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';
    public string $type = '';
    public string $editorChoice = '0';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'type' => ['except' => ''],
        'editorChoice' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingEditorChoice()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'category', 'type', 'editorChoice']);
        $this->resetPage();
    }

    public function toggleEditorChoice(int $storyId)
    {
        $story = Story::findOrFail($storyId);

        $story->is_editor_choice = !$story->is_editor_choice;
        $story->save();

        session()->flash('message', "Status Pilihan Editor untuk '{$story->title}' berhasil diperbarui.");
    }

    public function render()
    {
        $categories = Genre::orderBy('name')->get();
        $stories = Story::with(['author', 'genre'])
            // Search Judul atau Penulis
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('author', function ($authorQuery) {
                            $authorQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            // Filter Kategori
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            // Filter Gratis / Premium
            ->when($this->type, function ($query) {
                $query->where('monetization_type', $this->type === 'premium');
            })
            // Filter Pilihan Editor
            ->when($this->editorChoice !== '', function ($query) {
                $query->where('is_editor_choice', $this->editorChoice === '1');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.story-manager', ['stories' => $stories, 'categories' => $categories])->layout('layouts.admin');
    }
}
