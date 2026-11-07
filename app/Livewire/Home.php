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

        $editorChoices = Story::whereHas('badges', function ($q) {
            $q->where('slug', 'editor-choice');
        })
            ->where('status', 'published')
            ->with(['author', 'genre', 'badges'])
            ->get()
            ->sortBy(function ($story) {
                return $story->badges->firstWhere('slug', 'editor-choice')->pivot->order;
            });

        $recommendedStories = Story::whereHas('badges', function ($q) {
            $q->where('slug', 'recommended');
        })
            ->where('status', 'published')
            ->with(['author', 'genre', 'badges'])
            ->get()
            ->sortBy(function ($story) {
                return $story->badges->firstWhere('slug', 'recommended')->pivot->order;
            });

        $latestStories = Story::with(['penName', 'genre'])
            ->where('status', 'published')
            ->orderByDesc(
                Chapter::select('created_at')
                    ->whereColumn('story_id', 'stories.id')
                    ->latest()
                    ->limit(1)
            )
            ->take(10)
            ->get();

        return view('livewire.home.home', [
            'stories' => $query->latest()->take(5)->get(),
            'editorChoices' => $editorChoices,
            'popularStories' => $popularStories,
            'recentChapters' => $recentChapters,
            'latestStories' => $latestStories,
            'recommendedStories' => $recommendedStories,
            'user' => Auth::user()
        ]);
    }
}
