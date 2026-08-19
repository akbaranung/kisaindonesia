<?php

namespace App\Livewire\Story;

use App\Models\ReadHistory;
use Livewire\Component;
use App\Models\Story;
use App\Services\StoryViewService;

class StoryDetail extends Component
{
    public $story;
    public $chapters;
    public $lastReadChapter = null;

    protected $listeners = ['review-updated' => '$refresh'];

    public function mount(Story $story, StoryViewService $viewService)
    {
        if ($story->status !== 'published') {
            abort(404);
        }

        $this->story = $story;

        $this->chapters = $story->chapters()->where('status', 'published')->orderBy('order_number', 'asc')->get();

        if (auth()->check()) {
            $history = ReadHistory::where('user_id', auth()->id())->where('story_id', $story->id)->with('chapter')->first();

            if ($history && $history->chapter) {
                $this->lastReadChapter = $history->chapter;
            }

            $viewService->incrementStoryView($story);
        }
    }

    public function render()
    {
        return view('livewire.stories.story-detail');
    }
}
