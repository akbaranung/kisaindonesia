<?php

namespace App\Livewire;

use App\Models\PenName;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FollowButton extends Component
{
    public PenName $penName;
    public string $variant = 'deafult'; // default,compact atau icon
    public bool $isFollowing = false;
    public ?int $authorId = null;

    public function mount(PenName $penName, string $variant = 'default')
    {
        $this->penName = $penName;
        $this->variant = $variant;
        $this->authorId = $penName->user_id;

        $this->checkStatus();
    }

    public function checkStatus(): void
    {
        if (auth()->check()) {
            $this->isFollowing = $this->penName->isFollowedBy(auth()->user());
        }
    }

    public function toggleFollow()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($this->penName->user_id === $user->id) {
            $this->dispatch('show-toast', type: 'warning', message: 'Anda tidak dapat mengikuti diri sendiri.');
            return;
        }


        if ($this->isFollowing) {
            $user->followedPenNames()->detach($this->penName->id);
            $this->isFollowing = false;
            $this->dispatch('show-toast', type: 'info', message: 'Batal megikuti penulis.');
        } else {
            $user->followedPenNames()->attach($this->penName->id);
            $this->isFollowing = true;
            $this->dispatch('show-toast', type: 'success', message: 'Berhasil mengikuti penulis!');
        }



        $this->dispatch('follow-updated');
    }

    public function render()
    {
        return view('livewire.follow-button');
    }
}
