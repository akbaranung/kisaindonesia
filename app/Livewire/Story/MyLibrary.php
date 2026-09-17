<?php

namespace App\Livewire\Story;

use App\Models\ReadHistory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class MyLibrary extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * Hapus cerita dari Library / Favorit
     */
    public function removeFromLibrary(int $storyId)
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        // Unbookmark cerita dari tabel pivot 'libraries'
        Auth::user()->savedStories()->detach($storyId);

        session()->flash('success_library', 'Cerita berhasil dihapus dari perpustakaanmu.');
    }

    public function render()
    {
        $user = Auth::user();

        // Query mengambil daftar cerita yang di-bookmark user
        $query = $user->savedStories()
            ->with(['author', 'genre'])
            ->where('status', 'published');

        // Fitur pencarian lokal di dalam My Library
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('author', function ($authorQuery) {
                        $authorQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $continueReading = [];
        if ($user) {
            $continueReading = ReadHistory::with(['chapter.story.penName'])
                ->where('user_id', $user->id)
                ->latest('updated_at')
                ->take(6)
                ->get();
        }

        return view('livewire.library.my-library', [
            'savedStories' => $query->latest('libraries.created_at')->paginate(8),
            'continueReading' => $continueReading,
        ]);
    }
}
