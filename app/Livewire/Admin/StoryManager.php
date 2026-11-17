<?php

namespace App\Livewire\Admin;

use App\Models\Badge;
use App\Models\Genre;
use App\Models\Story;
use App\Notifications\PublishStatusUpdatedNotification;
use Carbon\Carbon;
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

    // Modal Detail Pembelian Bab
    public bool $showDetailModal = false;
    public ?int $selectedStoryId = null;
    public array $selectedStoryData = [];
    public array $purchaseSummary = [];
    public array $chapterPurchases = [];
    public bool $onlyPaidChapters = true;
    public string $modalSearch = '';

    // Properti manage publish cerita
    public bool $showRejectPublishModal = false;
    public ?int $rejectingStoryId = null;
    public string $publishRejectionReason = '';

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

    public function openDetailModal(int $storyId)
    {
        $this->selectedStoryId = $storyId;
        $this->modalSearch = '';
        $this->onlyPaidChapters = true;
        $this->loadPurchaseDetails();

        if (($this->purchaseSummary['total_paid_chapters'] ?? 0) === 0) {
            $this->onlyPaidChapters = false;
        }

        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedStoryId = null;
        $this->selectedStoryData = [];
        $this->purchaseSummary = [];
        $this->chapterPurchases = [];
        $this->modalSearch = '';
    }

    public function updateStoryStatus(int $storyId, string $status)
    {
        $allowed = ['draft', 'pending_review', 'published', 'rejected'];
        if (!in_array($status, $allowed)) {
            return;
        }

        $story = Story::findOrFail($storyId);
        $story->update(['status' => $status]);

        session()->flash('message', "Status cerita '{$story->title}' berhasil diubah ke " . ucfirst($status) . ".");
    }

    public function updateChapterStatus(int $chapterId, string $status)
    {
        $allowed = ['draft', 'published'];
        if (!in_array($status, $allowed)) {
            return;
        }

        $chapter = \App\Models\Chapter::findOrFail($chapterId);
        $chapter->update(['status' => $status]);

        // Refresh data detail bab di dalam modal
        $this->loadPurchaseDetails();

        session()->flash('message', "Status Bab '{$chapter->title}' berhasil diubah ke " . ucfirst($status) . ".");
    }

    public function loadPurchaseDetails()
    {
        if (!$this->selectedStoryId) {
            return;
        }

        $story = Story::with(['author', 'genre'])->findOrFail($this->selectedStoryId);

        $this->selectedStoryData = [
            'id' => $story->id,
            'title' => $story->title,
            'author_name' => $story->author->name ?? 'Anonim',
            'genre_name' => $story->genre->name ?? 'Uncategorized',
            'cover_path' => $story->cover_path,
        ];

        $chapters = DB::table('chapters')
            ->where('story_id', $this->selectedStoryId)
            ->orderBy('order_number', 'asc')
            ->get();

        $chapterIds = $chapters->pluck('id')->toArray();

        // Agregasi jumlah pembelian & total bean per bab sekali query (sangat ringan)
        $purchaseStats = DB::table('user_purchased_chapters')
            ->whereIn('chapter_id', $chapterIds)
            ->select('chapter_id', DB::raw('COUNT(*) as total_purchases'), DB::raw('SUM(beans_spent) as total_beans'))
            ->groupBy('chapter_id')
            ->get()
            ->keyBy('chapter_id');

        $paidChapters = $chapters->filter(function ($chap) {
            return (bool) $chap->is_premium || (int) $chap->bean_price > 0;
        });

        $purchasedPaidChaptersCount = $paidChapters->filter(function ($chap) use ($purchaseStats) {
            return $purchaseStats->has($chap->id);
        })->count();

        $totalPurchases = (int) $purchaseStats->sum('total_purchases');
        $totalBeans = (int) $purchaseStats->sum('total_beans');

        $this->purchaseSummary = [
            'total_chapters' => $chapters->count(),
            'total_paid_chapters' => $paidChapters->count(),
            'purchased_paid_chapters_count' => $purchasedPaidChaptersCount,
            'total_purchases' => $totalPurchases,
            'total_beans' => $totalBeans,
        ];

        $this->chapterPurchases = $chapters->map(function ($chap) use ($purchaseStats) {
            $stat = $purchaseStats->get($chap->id);
            $isPaid = (bool) $chap->is_premium || (int) $chap->bean_price > 0;

            return [
                'id' => $chap->id,
                'order_number' => $chap->order_number,
                'title' => $chap->title,
                'status' => $chap->status,
                'is_paid' => $isPaid,
                'bean_price' => (int) $chap->bean_price,
                'purchases_count' => $stat ? (int) $stat->total_purchases : 0,
                'total_beans' => $stat ? (int) $stat->total_beans : 0,
                'cover_path' => $chap->cover_path ?? null,
            ];
        })->toArray();
    }

    public function approvePublishStory(int $storyId)
    {
        $story = Story::findOrFail($storyId);
        $story->update([
            'status' => 'published',
            'rejection_reason' => null,
        ]);

        if ($story->author) {
            $story->author->notify(new PublishStatusUpdatedNotification('approved'));
        }

        session()->flash('message', "Cerita '{$story->title}' berhasil disetujui dan resmi dipublikasikan!");
    }

    public function openRejectPublishModal(int $storyId)
    {
        $this->rejectingStoryId = $storyId;
        $this->publishRejectionReason = '';
        $this->resetErrorBag();
        $this->showRejectPublishModal = true;
    }

    public function closeRejectPublishModal()
    {
        $this->showRejectPublishModal = false;
        $this->rejectingStoryId = null;
        $this->publishRejectionReason = '';
    }

    public function rejectPublishStory()
    {
        $this->validate([
            'publishRejectionReason' => 'required|string|min:10',
        ], [
            'publishRejectionReason.required' => 'Alasan penolakan wajib diisi agar penulis tahu bagian mana yang harus diperbaiki.',
            'publishRejectionReason.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $story = Story::findOrFail($this->rejectingStoryId);
        $story->update([
            'status' => 'rejected',
            'rejection_reason' => $this->publishRejectionReason,
        ]);

        if ($story->author) {
            $story->author->notify(new PublishStatusUpdatedNotification('rejected', $this->publishRejectionReason));
        }

        session()->flash('message', "Pengajuan publish cerita '{$story->title}' telah ditolak beserta alasannya.");
        $this->closeRejectPublishModal();
    }

    public function render()
    {
        $categories = Genre::orderBy('name')->get();
        $badges = Badge::orderBy('name')->get();

        $filteredChapterPurchases = collect($this->chapterPurchases);

        if ($this->onlyPaidChapters) {
            $filteredChapterPurchases = $filteredChapterPurchases->where('is_paid', true);
        }

        if (!empty(trim($this->modalSearch))) {
            $search = strtolower(trim($this->modalSearch));
            $filteredChapterPurchases = $filteredChapterPurchases->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['title'] ?? ''), $search) ||
                    str_contains((string) ($item['order_number'] ?? ''), $search);
            });
        }

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
                } else {
                    $query->where('status', $this->statusFilter);
                }
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.story-manager', [
            'stories' => $stories,
            'categories' => $categories,
            'badges' => $badges,
            'filteredChapterPurchases' => $filteredChapterPurchases->values(),
        ])->layout('layouts.admin');
    }
}
