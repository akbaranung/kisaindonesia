<?php

namespace App\Livewire;

use App\Models\Chapter;
use App\Models\Genre;
use App\Models\ReadHistory;
use App\Models\Story;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\WithPagination;

class Home extends Component
{
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
        $query = Story::query()
            ->with(['penName', 'genre'])
            ->where('status', 'published');

        if (auth()->check()) {
            auth()->user()->load('savedStories');
        }

        $popularStories = Story::with(['penName'])->where('status', 'published')->orderByDesc('views_count')->take(10)->get();
        $recentChapters = Chapter::with(['story.penName'])
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        $continueReading = [];

        if (auth()->check()) {
            $continueReading = ReadHistory::with(['chapter.story.penName'])
                ->where('user_id', auth()->id())
                ->latest('updated_at')
                ->take(4)
                ->get();
        }

        return view('livewire.home.home', [
            'stories' => $query->latest()->take(5)->get(),
            'popularStories' => $popularStories,
            'recentChapters' => $recentChapters,
            'continueReading' => $continueReading,
            'user' => Auth::user()
        ]);
    }
}
