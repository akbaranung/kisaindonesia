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

        $followingIds = $user ? $user->followings()->pluck('following_id')->toArray() : [];

        $latestChapters = Chapter::with(['story.user'])->whereHas('story', function ($query) use ($followingIds) {
            $query->whereIn('user_id', $followingIds)->where('status', 'published');
        })->where('status', 'published')->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.following-feed', [
            'chapters' => $latestChapters,
            'followingIds' => $followingIds
        ]);
    }
}
