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
            ->where('status', 'published')
            ->where('monetization_type', 'premium');

        if (auth()->check()) {
            auth()->user()->load('savedStories');
        }

        $popularStories = Story::with(['penName'])->where('status', 'published')->where('monetization_type', 'premium')->orderByDesc('views_count')->take(10)->get();
        $recentChapters = Chapter::with(['story.penName'])
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        $editorChoices = Story::with(['author', 'genre'])
            ->where('is_editor_choice', true)
            ->latest()
            ->take(6)
            ->get();

        $latestStories = Story::with(['penName', 'genre'])
            ->where('status', 'published')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.home.home', [
            'stories' => $query->latest()->take(5)->get(),
            'editorChoices' => $editorChoices,
            'popularStories' => $popularStories,
            'recentChapters' => $recentChapters,
            'latestStories' => $latestStories,
            'user' => Auth::user()
        ]);
    }
}
