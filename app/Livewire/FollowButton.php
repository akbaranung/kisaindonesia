<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FollowButton extends Component
{
    public int $authorId;
    public bool $isFollowing = false;
    public string $variant = 'deafult'; // default,compact atau icon
    public int $followersCount = 0;

    public function mount(int $authorId, string $variant = 'default')
    {
        $this->authorId = $authorId;
        $this->variant = $variant;

        $this->checkStatus();
    }

    public function checkStatus(): void
    {
        $author = User::find($this->authorId);
        $this->followersCount = $author ? $author->followers()->count() : 0;

        if (Auth::check()) {
            $this->isFollowing = Auth::user()->isFollowing($this->authorId);
        }
    }

    public function toggleFollow()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->id === $this->authorId) {
            $this->dispatch('show-toast', type: 'warning', message: 'Anda tidak dapat mengikuti diri sendiri.');
            return;
        }

        $result = $user->toggleFollow($this->authorId);

        if ($result['status'] === 'followed') {
            $this->isFollowing = true;
            $this->followersCount++;
            $this->dispatch('show-toast', type: 'success', message: 'Berhasil mengikuti penulis!');
        } else {
            $this->isFollowing = false;
            $this->followersCount = max(0, $this->followersCount - 1);
            $this->dispatch('show-toast', type: 'info', message: 'Batal megikuti penulis.');
        }

        $this->dispatch('author-follow-updated', authorId: $this->authorId, isFollowing: $this->isFollowing);
    }

    public function render()
    {
        return view('livewire.follow-button');
    }
}
