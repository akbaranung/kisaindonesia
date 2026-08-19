<?php

namespace App\Livewire\Story;

use App\Models\Genre;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
    public string $status = 'draft';
    public $cover;
    public $existingCover = null; //Menampilkan cover lama ketika edit data

    public $selectedStoryId;
    public $selectedStoryTitle;

    public function switchAction($target)
    {
        $this->action = $target;
        if ($target === 'create') {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->storyId = null;
        $this->title = '';
        $this->genreId = '';
        $this->synopsis = '';
        $this->status = 'draft';
        $this->cover = null;
        $this->existingCover = null;
    }

    public function editStory($id)
    {
        $story = Story::findOrFail($id);
        $this->storyId = $story->id;
        $this->title = $story->title;
        $this->genreId = $story->category;
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
            'synopsis' => 'required|string|max:1000',
            'cover' => 'nullable|image|max:2048',
        ], [
            'title.required' => 'Judul novelnya jangan lupa diisi, Bro.',
            'genreId.required' => 'Pilih dulu genre ceritamu.',
            'genreId.exists' => 'Genre yang kamu pilih tidak terdaftar.',
            'synopsis.required' => 'Sinopsis singkat wajib ada biar pembaca penasaran.',
            'cover.image' => 'File harus berupa gambar (jpg, jpeg, png).',
            'cover.max' => 'Ukuran cover kegedean, Bro. Maksimal 5MB aja.',
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
            ]
        );

        session()->flash('success', $this->storyId ? 'Cerita berhasil diperbarui!' : 'Cerita baru "' . $this->title . '" berhasil dibuat!")');

        // Reset state form setelah menyimpan
        $this->resetForm();

        $this->selectedStoryId = $story->id;
        $this->selectedStoryTitle = $story->title;
        return $this->redirect(route('stories.manage', $story->slug), navigate: true);
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

        return view('livewire.stories.my-stories', [
            'myStories' => $stories,
            'genres' => $genres
        ]);
    }
}
