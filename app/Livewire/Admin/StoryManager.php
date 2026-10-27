<?php

namespace App\Livewire\Admin;

use App\Models\Genre;
use App\Models\Story;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class StoryManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';
    public string $type = '';
    public string $editorChoice = '';

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

        // JIka dinonaktifkan, reset urutannya ke 0
        if (!$story->is_editor_choice) {
            $story->editor_choice_order = 0;
        }

        $story->save();

        session()->flash('message', "Status Pilihan Editor untuk '{$story->title}' berhasil diperbarui.");
    }

    public function updateEditorChoiceOrder(int $storyId, $order)
    {
        $story = Story::findOrFail($storyId);
        $story->editor_choice_order = max(0, (int) $order);
        $story->save();

        session()->flash('message', "Urutan Pilihan Editor '{$story->title}' berhasil diubah ke urutan {$story->editor_choice_order}.");
    }

    public function resetAllPeriodicViews()
    {

        if (!auth()->check() || auth()->user()->email !== 'admin@kisaindonesia.id') {
            session()->flash('error', 'Akses ditolak. Anda tidak memiliki izin untuk mereset views periodik.');
            return;
        }

        Story::query()->update(['periodic_views_count' => 0]);

        session()->flash('message', 'Views periodik berhasil di-reset untuk semua cerita.');
    }

    public function syncPeriodicViewsFromTotal()
    {
        // Proteksi backend: Pastikan hanya admin@kisaindonesia.id yang dapat mengeksekusi
        if (!auth()->check() || auth()->user()->email !== 'admin@kisaindonesia.id') {
            session()->flash('error', 'Akses ditolak. Anda tidak memiliki izin untuk melakukan sinkronisasi ini.');
            return;
        }

        // Mengopy nilai views_count ke periodic_views_count untuk seluruh cerita
        DB::statement('UPDATE stories SET periodic_views_count = views_count');

        session()->flash('message', 'Data periodic_views_count berhasil diisi sesuai nilai views_count terkini.');
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
            ->orderByRaw('is_editor_choice DESC, editor_choice_order ASC, created_at DESC')
            ->paginate(10);

        return view('livewire.admin.story-manager', ['stories' => $stories, 'categories' => $categories])->layout('layouts.admin');
    }
}
