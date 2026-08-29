<?php

namespace App\Livewire;

use App\Models\Genre;
use App\Models\Story;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\WithPagination;

class Home extends Component
{

    use WithPagination;

    // Untuk keperluan filter
    public string $search = '';
    public string $selectedCategory = '';
    public string $selectedMonetization = '';
    public string $sortBy = 'latest';

    public function searchStories()
    {
        // Cukup reset paginasi ke halaman 1 agar hasil pencarian mulai dari awal
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatedSelectedType()
    {
        $this->resetPage();
    }

    public function updatedSelectedMonetization()
    {
        $this->resetPage();
    }

    public function updatedStory()
    {
        $this->resetPage();
    }


    public function resetFilters()
    {
        $this->reset(['search', 'selectedCategory', 'selectedMonetization', 'sortBy']);
        $this->resetPage();
    }


    public function toggleLibrary(int $storyId)
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $user = Auth::user();

        $user->savedStories()->toggle($storyId);
    }


    public function render()
    {
        //Untuk menampilkan cerita yang status nya sudah published
        $query = Story::query()
            ->with(['penName', 'genre'])
            ->where('status', 'published');

        if (auth()->check()) {
            auth()->user()->load('savedStories');
        }

        // function untuk search judul/penulis
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')->orWhereHas('penName', function ($authorQuery) {
                    $authorQuery->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        // filter kategori
        if (!empty($this->selectedCategory)) {
            $query->where('category', $this->selectedCategory);
        }

        // filter monetisasi
        if (!empty($this->selectedMonetization) && $this->selectedMonetization !== 'all') {
            $query->where('monetization_type', $this->selectedMonetization);
        }

        // sorting / urutan
        match ($this->sortBy) {
            'popular' => $query->orderBy('views_count', 'desc'),
            'title' => $query->orderBy('title', 'asc'),
            default => $query->orderBy('created_at', 'desc')
        };

        // Rekomendasi cerita utama (featured banner)
        $featuredStory = Story::where('status', 'published')->where('is_featured', true)->latest()->first();

        // Daftar kategori
        $categories = Genre::all();

        return view('livewire.home.home', [
            'categories' => $categories,
            'stories' => $query->paginate(10),
            'featuredStory' => $featuredStory,
            'user' => Auth::user() // Ambil data user aktif jika sudah login
        ]);
    }
}
