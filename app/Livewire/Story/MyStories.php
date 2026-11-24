<?php

namespace App\Livewire\Story;

use App\Models\Character;
use App\Models\Genre;
use App\Models\StoryCharacter;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class MyStories extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';
    public $filterMonetization = '';
    protected $paginationTheme = 'tailwind';

    public string $action = 'list';

    public $storyId = null; //Digunakan ketika akan edit data
    public $title = '';
    public $parent_genre_id = '';
    public $genreId = '';
    public $synopsis = '';
    public $type = 'novel';
    public string $status = 'draft';
    public $cover;
    public $existingCover = null; //Menampilkan cover lama ketika edit data
    public $is_mature = false;

    public $selectedStoryId;
    public $selectedStoryTitle;

    public ?int $pen_name_id = null;
    public bool $showPenNameModal = false;
    public string $new_pen_name = '';
    public string $new_pen_bio = '';
    public bool $isEditingPenName = false;
    public ?int $editingPenNameId = null;

    // State modal konfirmasi hapus cerita
    public bool $showDeleteModal = false;
    public ?int $deleteStoryId = null;
    public string $deleteStoryTitle = '';
    public int $deleteChaptersCount = 0;
    public int $deleteCharactersCount = 0;

    public $parentGenres = [];
    public $subGenres = [];

    protected $listeners = ['storyStatusUpdated' => '$refresh'];

    public function downloadContractPdf(Story $story)
    {

        $contract = $story->contract;

        if (!$contract) {
            session()->flash('error', 'Kontrak untuk cerita ini belum ditandatangani.');
            return redirect()->route('author.contract.sign', $story->id);
        }

        return redirect()->route('author.contract.pdf', $story->id);
    }


    public function switchAction($target)
    {
        $this->action = $target;
        if ($target === 'create') {
            $this->resetForm();

            $user = auth()->user();

            // Otomatis pilih nama pena default atau yang pertama
            $defaultPenName = $user->penNames()->where('is_default', true)->first()
                ?? $user->penNames()->first();

            if ($defaultPenName) {
                $this->pen_name_id = $defaultPenName->id;
            }

            $this->loadParentGenres();
        }
    }

    public function updatedType()
    {
        $this->parent_genre_id = '';
        $this->genreId = '';
        $this->subGenres = [];
        $this->loadParentGenres();
    }

    public function updatedParentGenreId($value)
    {
        $this->genreId = '';
        if ($value) {
            $this->subGenres = Genre::where('parent_id', $value)->get();

            if ($this->subGenres->isEmpty()) {
                $this->genreId = $value;
            }
        } else {
            $this->subGenres = [];
        }
    }

    private function loadParentGenres()
    {
        $this->parentGenres = Genre::whereNull('parent_id')
            ->where(function ($q) {
                $q->where('type', $this->type)->orWhereNull('type');
            })
            ->get();
    }

    public function resetForm()
    {
        $this->storyId = null;
        $this->title = '';
        $this->genreId = '';
        $this->synopsis = '';
        $this->status = 'draft';
        $this->type = 'novel';
        $this->is_mature = false;
        $this->cover = null;
        $this->existingCover = null;
    }

    public function openPenNameModal(): void
    {
        $this->reset(['new_pen_name', 'new_pen_bio', 'editingPenNameId']);
        $this->isEditingPenName = false;
        $this->resetValidation(['new_pen_name', 'new_pen_bio']);
        $this->showPenNameModal = true;
    }

    public function openEditPenNameModal(): void
    {
        if (! $this->pen_name_id) {
            return;
        }

        $penName = auth()->user()->penNames()->find($this->pen_name_id);

        if ($penName) {
            $this->editingPenNameId = $penName->id;
            $this->new_pen_name = $penName->name;
            $this->new_pen_bio = $penName->bio ?? '';
            $this->isEditingPenName = true;
            $this->resetValidation(['new_pen_name', 'new_pen_bio']);
            $this->showPenNameModal = true;
        }
    }

    public function closePenNameModal(): void
    {
        $this->showPenNameModal = false;
        $this->reset(['new_pen_name', 'new_pen_bio', 'editingPenNameId', 'isEditingPenName']);
        $this->resetValidation(['new_pen_name', 'new_pen_bio']);
    }

    public function saveQuickPenName(): void
    {
        $this->validate(
            [
                'new_pen_name' => 'required|string|min:3|max:50',
                'new_pen_bio' => 'nullable|string|max:255'
            ],
            [
                'name_pen_name.required' => 'Nama pena/penulis harus diisi!',
                'name_pen_name.min' => 'Nama pena/penulis minimal terdiri dari 3 karakter!',
                'name_pen_name.max' => 'Nama pena/penulis maksimal terdiri dari 50 karakter!',
                'name_pen_bio.max' => 'Nama pena/penulis maksimal terdiri dari 255 karakter!',
            ]
        );

        $user = auth()->user();

        if ($this->isEditingPenName) {
            $penName = $user->penNames()->findOrFail($this->editingPenNameId);
            $penName->update([
                'name' => trim($this->new_pen_name),
                'slug' => Str::slug($this->new_pen_name),
                'bio' => trim($this->new_pen_bio),
            ]);

            session()->flash('pen_name_success', 'Nama pena "' . $penName->name . '" berhasil diperbarui!');
        } else {
            $isFirst = $user->penNames()->count() === 0;

            $penName = $user->penNames()->create([
                'name' => trim($this->new_pen_name),
                'slug' => Str::slug($this->new_pen_name),
                'bio' => trim($this->new_pen_bio),
                'is_default' => $isFirst,
            ]);

            $this->pen_name_id = $penName->id;

            session()->flash('pen_name_success', 'Nama pena "' . $penName->name . '" berhasil ditambahkan!');
        }

        $this->closePenNameModal();
    }

    public function editStory($id)
    {
        $story = Story::with('genre.parent')->findOrFail($id);
        $this->storyId = $story->id;
        $this->title = $story->title;
        $this->pen_name_id = $story->pen_name_id;
        $this->type = $story->type ?? 'novel';
        $this->synopsis = $story->synopsis;
        $this->status = $story->status;
        $this->existingCover = $story->cover_path;
        $this->is_mature = $story->is_mature;
        $this->loadParentGenres();

        $genre = $story->genre;

        if ($genre) {
            $this->genreId = $genre->id;
            if ($genre->parent_id) {
                // Jika genre_id adalah Sub-Genre
                $this->parent_genre_id = $genre->parent_id;

                // Populate daftar sub-genre berdasarkan parent_id
                $this->subGenres = Genre::where('parent_id', $genre->parent_id)->get();
            } else {
                // Jika genreId adalah Genre Utama (tanpa Sub-Genre)
                $this->parent_genre_id = $genre->id;
                $this->subGenres = Genre::where('parent_id', $genre->id)->get();
            }
        } else {
            $this->parent_genre_id = '';
            $this->genreId = '';
            $this->subGenres = [];
        }

        $this->action = 'create';
    }

    public function saveStory()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'parent_genre_id' => 'required|exists:genres,id',
            'genreId' => 'required|exists:genres,id',
            'type' => 'required|in:novel,puisi,non_fiksi',
            'synopsis' => 'required|string|max:1000',
            'cover' => 'nullable|image|max:2048',
            'pen_name_id' => 'required|exists:pen_names,id',
            'is_mature' => 'boolean'
        ], [
            'title.required' => 'Judul novelnya jangan lupa diisi, Bro.',
            'type.required' => 'Tipe novelnya jangan lupa dipilih, Bro.',
            'parent_genre_id.required' => 'Pilih genre utama cerita kamu.',
            'genreId.required' => 'Pilih sub-genre yang sesuai.',
            'genreId.exists' => 'Genre yang kamu pilih tidak terdaftar.',
            'synopsis.required' => 'Sinopsis singkat wajib ada biar pembaca penasaran.',
            'cover.image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'cover.max' => 'Ukuran file maksimal 2MB.',
            'pen_name_id.required' => 'Pilih terlebih dahulu nama pena/penulis nya!'
        ]);

        if ($this->storyId) {
            $story = Story::find($this->storyId);
            // Jika judul tidak berubah saat edit, gunakan slug yang sudah ada
            if ($story && $story->title === $this->title) {
                $slug = $story->slug;
            } else {
                $slug = Str::slug($this->title);
                $count = Story::where('slug', 'LIKE', $slug . '%')->where('id', '!=', $this->storyId)->count();
                if ($count > 0) {
                    $slug = $slug . '-' . ($count + 1);
                }
            }
        } else {
            $slug = Str::slug($this->title);
            $count = Story::where('slug', 'LIKE', $slug . '%')->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
        }

        $coverPath = $this->existingCover;;
        if ($this->cover) {
            if ($this->existingCover) {
                Storage::disk('public')->delete($this->existingCover);
            }
            $coverPath = $this->cover->store('covers', 'public');
        }

        $story = Story::updateOrCreate(
            ['id' => $this->storyId],
            [
                'user_id' => Auth::id(),
                'slug' => $slug,
                'title' => $this->title,
                'category' => $this->genreId,
                'status' => strtolower($this->status),
                'synopsis' => $this->synopsis,
                'cover_path' => $coverPath,
                'type' => $this->type,
                'pen_name_id' => $this->pen_name_id,
                'is_mature' => $this->is_mature
            ]
        );

        session()->flash('success', $this->storyId ? 'Cerita berhasil diperbarui!' : 'Cerita baru "' . $this->title . '" berhasil dibuat!")');

        // Reset state form setelah menyimpan
        $this->resetForm();

        $this->selectedStoryId = $story->id;
        $this->selectedStoryTitle = $story->title;
        return $this->redirect(route('stories.chapters', $story->id), navigate: true);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterMonetization()
    {
        $this->resetPage();
    }

    public function render()
    {
        $latestContractVersion = (int) Setting::get('current_contract_version', 1);

        $stories = Story::query()
            ->where('user_id', Auth::id())
            ->withCount('chapters')
            ->with(['premiumRequests' => function ($q) {
                $q->latest();
            }])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterMonetization, function ($query) {
                $query->where('monetization_type', $this->filterMonetization);
            })
            ->latest()
            ->paginate(10);

        $genres = Genre::orderBy('name', 'asc')->get();
        $penNames = auth()->user()->penNames()->orderBy('name')->get();

        return view('livewire.stories.my-stories', [
            'myStories' => $stories,
            'genres' => $genres,
            'penNames' => $penNames,
            'latestContractVersion' => $latestContractVersion,
        ]);
    }

    /**
     * Cerita hanya boleh dihapus jika belum premium dan tidak sedang
     * (atau sudah) diproses untuk monetisasi.
     */
    private function deleteBlockReason(Story $story): ?string
    {
        if ($story->monetization_type === 'premium') {
            return 'Cerita premium tidak bisa dihapus.';
        }

        $hasActiveRequest = $story->premiumRequests()
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($hasActiveRequest) {
            return 'Cerita yang sedang/sudah diajukan monetisasi tidak bisa dihapus.';
        }

        return null;
    }

    /**
     * Mengumpulkan semua path gambar (disk "public") yang terkait dengan cerita:
     * cover, gambar di dalam isi bab/sinopsis (URL /storage/...), serta kolom
     * path gambar pada bab dan pengajuan monetisasi.
     */
    private function collectStoryImagePaths(Story $story): array
    {
        $story->loadMissing(['chapters', 'premiumRequests']);

        $models = collect([$story])
            ->merge($story->chapters)
            ->merge($story->premiumRequests);

        $ext = 'jpe?g|png|gif|webp|svg|bmp|avif';
        $disk = Storage::disk('public');
        $paths = [];

        foreach ($models as $model) {
            foreach ($model->getAttributes() as $value) {
                if (! is_string($value) || $value === '') {
                    continue;
                }

                // 1) Gambar yang disisipkan sebagai URL, mis. <img src="/storage/chapter-images/a.jpg">
                if (preg_match_all('~/storage/([^\s"\'<>()?\\\\]+\.(?:' . $ext . '))~i', $value, $m)) {
                    foreach ($m[1] as $path) {
                        $paths[] = urldecode($path);
                    }
                }

                // 2) Kolom yang langsung berisi path, mis. covers/abc.jpg
                if (preg_match('~^[\w\-./]+\.(?:' . $ext . ')$~i', $value)) {
                    $paths[] = ltrim($value, '/');
                }
            }
        }

        // Gambar bubble chat di dalam bab (disimpan di file JSON bab, bukan kolom DB)
        foreach ($story->chapters as $chapter) {
            try {
                $data = $chapter->parseJsonData();
            } catch (\Throwable $e) {
                $data = [];
            }

            foreach (($data['bubbles'] ?? []) as $bubble) {
                foreach (['image_url', 'existing_image_url'] as $key) {
                    if (! empty($bubble[$key]) && is_string($bubble[$key])) {
                        $paths[] = $this->normalizeStoragePath($bubble[$key]);
                    }
                }
            }

            if (
                ! empty($data['content']) && is_string($data['content'])
                && preg_match_all('~/storage/([^\s"\'<>()?\\\\]+\.(?:' . $ext . '))~i', $data['content'], $m)
            ) {
                foreach ($m[1] as $path) {
                    $paths[] = urldecode($path);
                }
            }
        }

        // Avatar karakter (dua model: StoryCharacter di editor bab, Character di halaman kelola karakter)
        foreach (StoryCharacter::where('story_id', $story->id)->pluck('avatar_path') as $avatar) {
            if ($avatar) {
                $paths[] = $this->normalizeStoragePath($avatar);
            }
        }

        return collect($paths)
            ->unique()
            ->reject(fn($p) => str_contains($p, '..'))   // cegah path traversal
            ->filter(fn($p) => $disk->exists($p))
            ->values()
            ->all();
    }

    private function normalizeStoragePath(string $value): string
    {
        // Terima path relatif ("chat-fic/story_1/a.jpg") maupun URL ("/storage/chat-fic/...")
        if (($pos = strpos($value, '/storage/')) !== false) {
            $value = substr($value, $pos + strlen('/storage/'));
        }

        return urldecode(ltrim($value, '/'));
    }

    public function confirmDelete(int $storyId): void
    {
        $story = Story::where('user_id', Auth::id())->findOrFail($storyId);

        if ($reason = $this->deleteBlockReason($story)) {
            session()->flash('error', $reason);
            return;
        }

        $this->deleteStoryId = $story->id;
        $this->deleteStoryTitle = $story->title;
        $this->deleteChaptersCount = $story->chapters()->count();
        $this->deleteCharactersCount = StoryCharacter::where('story_id', $story->id)->count();
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->reset(['showDeleteModal', 'deleteStoryId', 'deleteStoryTitle', 'deleteChaptersCount', 'deleteCharactersCount']);
    }

    public function deleteStory()
    {
        if (! $this->deleteStoryId) {
            return;
        }

        // Pastikan hanya pemilik cerita yang bisa menghapus
        $story = Story::where('user_id', Auth::id())->findOrFail($this->deleteStoryId);

        if ($reason = $this->deleteBlockReason($story)) {
            $this->cancelDelete();
            session()->flash('error', $reason);
            return;
        }

        $title = $story->title;

        // Kumpulkan semua gambar terkait SEBELUM data dihapus dari database
        $imagePaths = $this->collectStoryImagePaths($story);

        // File JSON isi bab (disimpan di disk "local" lewat Chapter::saveContent)
        $contentFiles = $story->chapters
            ->pluck('file_path')
            ->filter(fn($f) => is_string($f)
                && $f !== ''
                && ! str_starts_with(ltrim($f), '{')   // bukan JSON inline
                && ! str_contains($f, '..'))
            ->unique()
            ->values()
            ->all();

        $id = $story->id;

        DB::transaction(function () use ($story, $id) {
            StoryCharacter::where('story_id', $id)->delete();
            $story->chapters()->delete();
            $story->delete();
        });

        // Hapus file dari server hanya jika data DB berhasil dihapus
        foreach ($imagePaths as $path) {
            Storage::disk('public')->delete($path);
        }

        foreach ($contentFiles as $file) {
            Storage::disk('local')->delete($file);
            Storage::delete($file); // disk default, jika berbeda dari "local" (sesuai parseJsonData)
        }

        // Sapu folder khusus cerita ini (sisa file yang tidak tercatat di database)
        foreach (["chat-fic/story_{$id}", "characters/story_{$id}", "chapters/covers/story_{$id}"] as $dir) {
            Storage::disk('public')->deleteDirectory($dir);
        }

        // Jika halaman terakhir jadi kosong, kembali ke halaman sebelumnya
        if ($this->getPage() > 1 && Story::where('user_id', Auth::id())->count() <= ($this->getPage() - 1) * 10) {
            $this->previousPage();
        }

        $this->cancelDelete();

        session()->flash('success', 'Cerita "' . $title . '" berhasil dihapus.');
    }

    public function requestCompletion(int $storyId)
    {
        $story = Story::where('user_id', Auth::id())->findOrFail($storyId);

        if ($reason = $story->completionBlockReason()) {
            session()->flash('error', $reason);
            return;
        }

        $story->update([
            'completion_status' => 'pending',
            'completion_requested_at' => now(),
            'completion_note' => null,
        ]);

        session()->flash('success', 'Pengajuan tamat untuk "' . $story->title . '" dikirim. Menunggu pengecekan editor.');
    }
}
