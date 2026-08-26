<?php

namespace App\Livewire;

use App\Models\Chapter;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class FollowingFeed extends Component
{
    use WithPagination;

    public int $perPage = 10;

    public function render()
    {
        $user = Auth::user();

        $followedPenNameIds = $user ? $user->followedPenNames()->pluck('pen_names.id')->toArray() : [];

        $latestChapters = Chapter::with(['story.penName', 'story.genre'])
            ->whereHas('story', function ($query) use ($followedPenNameIds) {
                $query->whereIn('pen_name_id', $followedPenNameIds)
                    ->where('status', 'published');
            })
            ->where('status', 'published')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.following-feed', [
            'chapters' => $latestChapters,
            'followedPenNameIds' => $followedPenNameIds,
        ]);
    }
}
