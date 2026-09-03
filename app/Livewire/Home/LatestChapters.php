<?php

namespace App\Livewire\Home;

use App\Models\Chapter;
use Livewire\Component;

class LatestChapters extends Component
{
    public function render()
    {
        $latestChapters = Chapter::with(['story.penName', 'story.genre'])
            ->where('is_premium', true)
            ->latest()
            ->take(6)
            ->get();

        return view('livewire.home.latest-chapters', ['latestChapters' => $latestChapters]);
    }
}
