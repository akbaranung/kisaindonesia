<?php

namespace App\Livewire\Story\Chapter;

use App\Models\Chapter;
use App\Models\Story;
use App\Models\StoryCharacter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;


class ManageStoryChapters extends Component
{
    public Story $story;
    public $isCreateModalOpen = false;
    public $title = '';
    public $type = 'regular';
    public $status = 'draft';

    public function mount(Story $story)
    {
        $this->story = $story;
    }

    public function openCreateModal()
    {
        $this->resetErrorBag();
        $this->title = '';
        $this->status = 'draft';
        $this->type = strtolower($this->story->type === 'puisi' ? 'regular' : $this->type);
        $this->isCreateModalOpen = true;
    }

    public function closeCreateModal()
    {
        $this->isCreateModalOpen = false;
    }

    public function createAndRedirect()
    {
        $this->validate(
            [
                'title' => 'required|string|max:255',
                'type' => 'required|in:regular,chat',
                'status' => 'required|in:draft,published',
            ],
            [
                'title.required' => 'Judul bab wajib diisi!',
                'type.required' => 'Tipe bab wajib dipilih!'
            ]
        );


        $filePath = null;

        try {
            $chapter = DB::transaction(function () use (&$filePath) {
                $nextOrder = ($this->story->chapters()->max('order_number') ?? 0) + 1;
                $folder = "chapters/story_{$this->story->id}";
                $filePath = "{$folder}/chap_{$nextOrder}_" . time() . ".json";

                if (!Storage::disk('local')->exists($folder)) {
                    Storage::disk('local')->makeDirectory($folder);
                }

                $initialContent = [
                    'type' => strtolower($this->type),
                    'bubbles' => [],
                    'content' => '',
                    'created_at' => now()->toDateTimeString()
                ];

                $saved = Storage::disk('local')->put($filePath, json_encode($initialContent, JSON_PRETTY_PRINT));

                if (!$saved) {
                    throw new \Exception("Sistem gagal menulis file json bab ke storage.");
                }

                $baseSlug = Str::slug($this->title);
                $slug = $baseSlug;

                $count = Chapter::where('story_id', $this->story->id)
                    ->where('slug', 'LIKE', $baseSlug . '%')
                    ->count();

                if ($count > 0) {
                    $slug = "{$baseSlug}-" . ($count + 1);
                }

                return Chapter::create([
                    'story_id' => $this->story->id,
                    'title' => $this->title,
                    'slug' => $slug,
                    'file_path' => $filePath,
                    'word_count' => 0,
                    'order_number' => $nextOrder,
                    'is_premium' => ($this->story->monetization_type === 'premium' && $nextOrder > 5),
                    'bean_price' => 0,
                    'status' => $this->status,
                    'type' => $this->type
                ]);
            });

            return redirect()->route(
                'chapters.editor',
                [
                    'story' => $this->story->id,
                    'chapter' => $chapter->id
                ]
            );
        } catch (\Throwable $th) {
            if ($filePath && Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
            }

            Log::error(
                'Gagal membuat bab baru: ' . $th->getMessage(),
                ['story_id' => $this->story->id ?? null, 'user_id' => auth()->id(), 'trace' => $th->getTraceAsString()]
            );

            $this->dispatch('show-toast', type: 'error', message: $th->getMessage());
            $this->closeCreateModal();
        }
    }

    public function deleteChapter($chapterId)
    {
        $chapter = $this->story->chapters()->find($chapterId);

        if (!$chapter) {
            $this->dispatch('show-toast', type: 'error', message: 'Bab tidak ditemukan.');
            return;
        }

        // 1. Validasi: Wajib berstatus draft
        if ($chapter->status !== 'draft') {
            $this->dispatch('show-toast', type: 'error', message: 'Gagal! Bab yang sudah dipublikasikan tidak dapat dihapus.');
            return;
        }

        // 2. Validasi: Cek apakah sudah ada transaksi pembelian
        $hasPurchases = DB::table('user_purchased_chapters')
            ->where('chapter_id', $chapter->id)
            ->exists();

        if ($hasPurchases) {
            $this->dispatch('show-toast', type: 'error', message: 'Gagal! Bab ini tidak dapat dihapus karena sudah dibeli oleh pembaca.');
            return;
        }

        try {
            DB::transaction(function () use ($chapter) {
                // Hapus file JSON fisik dari storage local
                if ($chapter->file_path && Storage::disk('local')->exists($chapter->file_path)) {
                    Storage::disk('local')->delete($chapter->file_path);
                }

                // Hapus record bab
                $chapter->delete();

                // Re-index order_number bab yang tersisa
                $remainingChapters = $this->story->chapters()
                    ->orderBy('order_number', 'asc')
                    ->get();

                foreach ($remainingChapters as $index => $item) {
                    $newOrder = $index + 1;

                    // Update order_number & penyesuaian otomatis is_premium jika melewati bab 5
                    $item->update([
                        'order_number' => $newOrder,
                        'is_premium' => ($this->story->monetization_type === 'premium' && $newOrder > 5),
                    ]);
                }
            });

            // Reload relasi chapters di instance story agar view ter-render ulang dengan data presisi
            $this->story->load('chapters');

            $this->dispatch('show-toast', type: 'success', message: 'Bab draft berhasil dihapus dan nomor urut disesuaikan!');
        } catch (\Throwable $th) {
            Log::error(
                'Gagal menghapus bab: ' . $th->getMessage(),
                ['story_id' => $this->story->id, 'chapter_id' => $chapterId, 'user_id' => auth()->id()]
            );

            $this->dispatch('show-toast', type: 'error', message: 'Terjadi kesalahan saat menghapus bab.');
        }
    }

    public function render()
    {
        return view('livewire.stories.chapters.manage-story-chapters', [
            'chapters' => $this->story->chapters()->orderBy('order_number', 'asc')->get(),
        ])->layout('layouts.app');
    }
}
