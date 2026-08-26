<?php

namespace App\Livewire\Story;

use App\Models\Genre;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;
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
    public $genreId = '';
    public $synopsis = '';
    public $type = 'novel';
    public string $status = 'draft';
    public $cover;
    public $existingCover = null; //Menampilkan cover lama ketika edit data

    public $selectedStoryId;
    public $selectedStoryTitle;

    public ?int $pen_name_id = null;
    public bool $showPenNameModal = false;
    public string $new_pen_name = '';
    public string $new_pen_bio = '';
    public bool $isEditingPenName = false;
    public ?int $editingPenNameId = null;


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
        }
    }

    public function resetForm()
    {
        $this->storyId = null;
        $this->title = '';
        $this->genreId = '';
        $this->synopsis = '';
        $this->status = 'draft';
        $this->type = 'novel';
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
        $story = Story::findOrFail($id);
        $this->storyId = $story->id;
        $this->title = $story->title;
        $this->pen_name_id = $story->pen_name_id;
        $this->genreId = $story->category;
        $this->type = $story->type;
        $this->synopsis = $story->synopsis;
        $this->status = $story->status;
        $this->existingCover = $story->cover_path;
        $this->action = 'create';
    }

    public function saveStory()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'genreId' => 'required|exists:genres,id',
            'type' => 'required|in:novel,puisi',
            'synopsis' => 'required|string|max:1000',
            'cover' => 'nullable|image|max:2048',
            'pen_name_id' => 'required|exists:pen_names,id'
        ], [
            'title.required' => 'Judul novelnya jangan lupa diisi, Bro.',
            'type.required' => 'Tipe novelnya jangan lupa dipilih, Bro.',
            'genreId.required' => 'Pilih dulu genre ceritamu.',
            'genreId.exists' => 'Genre yang kamu pilih tidak terdaftar.',
            'synopsis.required' => 'Sinopsis singkat wajib ada biar pembaca penasaran.',
            'cover.image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'cover.max' => 'Ukuran cover kegedean, Bro. Maksimal 5MB aja.',
            'pen_name_id.required' => 'Pilih terlebih dahulu nama pena/penulis nya!'
        ]);

        $slug = Str::slug($this->title);

        $count = Story::where('slug', 'LIKE', $slug . '%')->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
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
                'pen_name_id' => $this->pen_name_id
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
            'penNames' => $penNames
        ]);
    }
}
