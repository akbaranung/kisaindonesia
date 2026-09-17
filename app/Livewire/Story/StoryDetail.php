<?php

namespace App\Livewire\Story;

use App\Models\ReadHistory;
use Livewire\Component;
use App\Models\Story;
use App\Services\StoryViewService;
use Illuminate\Support\Facades\Auth;

class StoryDetail extends Component
{
    public $story;
    public $chapters;
    public $lastReadChapter = null;
    public $tab = '';

    protected $listeners = ['review-updated' => '$refresh'];

    public function mount(Story $story, StoryViewService $viewService)
    {
        if ($story->status !== 'published') {
            abort(404);
        }

        $this->story = $story;
        $this->tab = 'synopsis';

        $this->chapters = $story->chapters()->where('status', 'published')->orderBy('order_number', 'asc')->get();

        if (auth()->check()) {
            $history = ReadHistory::where('user_id', auth()->id())->where('story_id', $story->id)->with('chapter')->first();

            if ($history && $history->chapter) {
                $this->lastReadChapter = $history->chapter;
            }

            $viewService->incrementStoryView($story);
        }
    }

    public function toggleLibrary()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        Auth::user()->savedStories()->toggle($this->story->id);
    }

    public function setTab(string $type)
    {
        if (in_array($type, ['synopsis', 'chaptersList'])) {
            $this->tab = $type;
        }
    }

    public function render()
    {
        $isSaved = Auth::check() ? Auth::user()->savedStories()->where('story_id', $this->story->id)->exists() : false;

        return view('livewire.stories.story-detail', [
            'isSaved' => $isSaved
        ]);
    }
}
