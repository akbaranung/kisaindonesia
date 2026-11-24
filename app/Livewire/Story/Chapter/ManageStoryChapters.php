<?php

namespace App\Livewire\Story\Chapter;

use App\Models\Chapter;
use App\Models\Story;
use App\Models\StoryCharacter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;


use Livewire\WithFileUploads;

class ManageStoryChapters extends Component
{
    use WithFileUploads;

    public Story $story;
    public $isCreateModalOpen = false;
    public $title = '';
    public $type = 'regular';
    public $status = 'draft';
    public $cover_upload = null;

    public function mount(Story $story)
    {
        $this->story = $story;
    }

    public function openCreateModal()
    {
        if ($this->story->fresh()->isChapterLocked()) {
            session()->flash('message', 'Bab baru tidak bisa ditambahkan: cerita sedang direview tamat atau sudah tamat.');
            return;
        }

        $this->resetErrorBag();
        $this->title = '';
        $this->status = 'draft';
        $this->type = strtolower($this->story->type === 'puisi' ? 'regular' : $this->type);
        $this->cover_upload = null;
        $this->isCreateModalOpen = true;
    }

    public function closeCreateModal()
    {
        $this->isCreateModalOpen = false;
        $this->cover_upload = null;
    }

    public function createAndRedirect()
    {
        abort_if($this->story->fresh()->isChapterLocked(), 403);

        if ($this->story->isChapterLocked()) {
            $this->dispatch('show-toast', type: 'error', message: 'Cerita sudah tamat atau sedang menunggu review tamat. Bab baru tidak bisa ditambahkan.');
            return;
        }

        $this->validate(
            [
                'title' => 'required|string|max:255',
                'type' => 'required|in:regular,chat',
                'status' => 'required|in:draft,published',
                'cover_upload' => 'nullable|image|max:500',
            ],
            [
                'title.required' => 'Judul bab wajib diisi!',
                'type.required' => 'Tipe bab wajib dipilih!',
                'cover_upload.image' => 'Cover bab harus berupa file gambar!',
                'cover_upload.max' => 'Ukuran cover bab maksimal 500KB!'
            ]
        );


        $filePath = null;
        $coverPath = null;

        try {
            if ($this->cover_upload) {
                $coverPath = $this->cover_upload->store("chapters/covers/story_{$this->story->id}", 'public');
            }

            $chapter = DB::transaction(function () use (&$filePath, $coverPath) {
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
                    'cover_path' => $coverPath,
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
            if ($coverPath && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
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
        // 1. Kepemilikan & keberadaan bab (cek sebelum mengakses relasi apa pun)
        abort_unless($this->story->user_id === Auth::id(), 403);

        $chapter = $this->story->chapters()->find($chapterId);

        if (!$chapter) {
            $this->dispatch('show-toast', type: 'error', message: 'Bab tidak ditemukan.');
            return;
        }

        $story = $this->story->fresh();

        // 2. Cerita tamat / sedang direview tamat
        if ($story->isChapterLocked()) {
            $this->dispatch('show-toast', type: 'error', message: 'Cerita ini sudah tamat atau sedang direview, tidak bisa hapus bab.');
            return;
        }

        // 3. Bab draft selalu boleh dihapus. Bab yang sudah terbit tidak boleh dihapus
        //    jika cerita premium atau ada pengajuan monetisasi pending/approved.
        if ($chapter->status !== 'draft') {
            $storyLocked = $story->monetization_type === 'premium'
                || $story->premiumRequests()->whereIn('status', ['pending', 'approved'])->exists();

            if ($storyLocked) {
                $this->dispatch('show-toast', type: 'error', message: 'Gagal! Bab yang sudah dipublikasikan tidak dapat dihapus karena cerita premium atau sedang/sudah diajukan monetisasi.');
                return;
            }
        }

        // 4. Bab yang sudah dibeli pembaca tidak boleh dihapus
        $hasPurchases = DB::table('user_purchased_chapters')
            ->where('chapter_id', $chapter->id)
            ->exists();

        if ($hasPurchases) {
            $this->dispatch('show-toast', type: 'error', message: 'Gagal! Bab ini tidak dapat dihapus karena sudah dibeli oleh pembaca.');
            return;
        }

        $wasPublished = $chapter->status === 'published';

        // Kumpulkan semua file terkait SEBELUM record dihapus
        $publicFiles = $this->collectChapterPublicFiles($chapter);
        $contentFile = (is_string($chapter->file_path)
            && $chapter->file_path !== ''
            && !str_starts_with(ltrim($chapter->file_path), '{')
            && !str_contains($chapter->file_path, '..'))
            ? $chapter->file_path
            : null;

        try {
            DB::transaction(function () use ($chapter, $story) {
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
                        'is_premium' => ($story->monetization_type === 'premium' && $newOrder > 5),
                    ]);
                }
            });

            // File fisik dihapus hanya setelah database berhasil diperbarui
            foreach ($publicFiles as $path) {
                Storage::disk('public')->delete($path);
            }

            if ($contentFile) {
                Storage::disk('local')->delete($contentFile);
            }

            // Reload relasi chapters di instance story agar view ter-render ulang dengan data presisi
            $this->story->load('chapters');

            $this->dispatch('show-toast', type: 'success', message: $wasPublished
                ? 'Bab berhasil dihapus permanen dan nomor urut disesuaikan!'
                : 'Bab draft berhasil dihapus dan nomor urut disesuaikan!');
        } catch (\Throwable $th) {
            Log::error(
                'Gagal menghapus bab: ' . $th->getMessage(),
                ['story_id' => $this->story->id, 'chapter_id' => $chapterId, 'user_id' => auth()->id()]
            );

            $this->dispatch('show-toast', type: 'error', message: 'Terjadi kesalahan saat menghapus bab.');
        }
    }

    /**
     * Semua file di disk "public" milik satu bab: cover bab, gambar bubble chat,
     * dan gambar yang disisipkan di isi bab (URL /storage/...).
     */
    private function collectChapterPublicFiles(Chapter $chapter): array
    {
        $paths = [];

        if ($chapter->cover_path) {
            $paths[] = $this->normalizeStoragePath($chapter->cover_path);
        }

        try {
            $data = $chapter->parseJsonData();
        } catch (\Throwable $e) {
            $data = [];
        }

        foreach (($data['bubbles'] ?? []) as $bubble) {
            foreach (['image_url', 'existing_image_url'] as $key) {
                if (!empty($bubble[$key]) && is_string($bubble[$key])) {
                    $paths[] = $this->normalizeStoragePath($bubble[$key]);
                }
            }
        }

        $ext = 'jpe?g|png|gif|webp|svg|bmp|avif';
        if (
            !empty($data['content']) && is_string($data['content'])
            && preg_match_all('~/storage/([^\s"\'<>()?\\\\]+\.(?:' . $ext . '))~i', $data['content'], $m)
        ) {
            foreach ($m[1] as $path) {
                $paths[] = urldecode($path);
            }
        }

        $disk = Storage::disk('public');

        return collect($paths)
            ->unique()
            ->reject(fn($p) => $p === '' || str_contains($p, '..'))
            ->filter(fn($p) => $disk->exists($p))
            ->values()
            ->all();
    }

    private function normalizeStoragePath(string $value): string
    {
        if (($pos = strpos($value, '/storage/')) !== false) {
            $value = substr($value, $pos + strlen('/storage/'));
        }

        return urldecode(ltrim($value, '/'));
    }

    public function render()
    {
        return view('livewire.stories.chapters.manage-story-chapters', [
            'chapters' => $this->story->chapters()->orderBy('order_number', 'asc')->get(),
        ])->layout('layouts.app');
    }
}
