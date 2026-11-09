<?php

namespace App\Livewire\Admin;

use App\Models\Badge;
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
    public string $selectedBadge = '';
    public string $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'type' => ['except' => ''],
        'selectedBadge' => ['except' => ''],
        'statusFilter' => ['except' => '']
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

    public function updatingSelectedBadge()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'category', 'type', 'selectedBadge', 'statusFilter']);
        $this->resetPage();
    }

    public function toggleBlockStory(int $storyId)
    {
        $story = Story::findOrFail($storyId);
        $story->is_blocked = !$story->is_blocked;
        $story->save();

        $statusText = $story->is_blocked ? 'diblokir' : 'dibuka blokirnya';
        session()->flash('message', "Cerita '{$story->title}' berhasil {$statusText}.");
    }

    public function toggleBadge(int $storyId, int $badgeId)
    {
        $story = Story::findOrfail($storyId);
        if ($story->badges()->where('badge_id', $badgeId)->exists()) {
            $story->badges()->detach($badgeId);
            $msg = "Badge berhasil dihapus dari cerita.";
        } else {
            $story->badges()->attach($badgeId, ['order' => 0]);
            $msg = "Badge berhasil ditambahkan ke cerita.";
        }

        session()->flash('message', $msg);
    }

    public function updateBadgeOrder(int $storyId, int $badgeId, $order)
    {
        $story = Story::findOrFail($storyId);
        $story->badges()->updateExistingPivot($badgeId, [
            'order' => max(0, (int) $order)
        ]);

        session()->flash('message', "Urutan berhasil diperbarui.");
    }

    public function resetAllPeriodicViews()
    {

        if (!auth()->check() || auth()->user()->email !== 'admin@kisaindonesia.id') {
            session()->flash('error', 'Akses ditolak. Anda tidak memiliki izin untuk mereset views periodik.');
            return;
        }

        DB::statement('UPDATE stories SET views_ranking = periodic_views_count');

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
        $badges = Badge::orderBy('name')->get();

        $stories = Story::with(['author', 'genre', 'badges'])
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
            ->when($this->selectedBadge, function ($query) {
                $query->whereHas('badges', function ($bQuery) {
                    $bQuery->where('badges.id', $this->selectedBadge);
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                if ($this->statusFilter === 'blocked') {
                    $query->where('is_blocked', true);
                } elseif ($this->statusFilter === 'active') {
                    $query->where('is_blocked', false);
                }
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.story-manager', [
            'stories' => $stories,
            'categories' => $categories,
            'badges' => $badges,
        ])->layout('layouts.admin');
    }
}
