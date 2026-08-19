<?php

namespace App\Livewire\Story;

use App\Models\Character;
use App\Models\Story;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageStoryCharacters extends Component
{
    use WithFileUploads;

    public $story;

    public $name = '';

    public $avatar = null;

    public $defaultPosition = 'left';

    public function mount($storyId)
    {
        $this->story = Story::findOrFail($storyId);
    }

    public function addCharacter()
    {
        $this->validate([
            'name' => 'required|string|max:50',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $avatarPath = $this->avatar ? $this->avatar->store('characters', 'public') : null;

        Character::create([
            'story_id' => $this->story->id,
            'name' => trim($this->name),
            'avatar' => $avatarPath,
            'default_position' => $this->defaultPosition,
        ]);

        $this->reset(['name', 'avatar', 'defaultPosition']);
        session()->flash('message', 'Karakter berhasil ditambahkan!');
    }

    public function deleteCharacter($id)
    {
        $char = Character::where('story_id', $this->story->id)->findOrFail($id);
        if ($char->avatar) {
            Storage::disk('public')->delete($char->avatar);
        }
        $char->delete();
        session()->flash('message', 'Karakter dihapus.');
    }

    public function render()
    {
        return view('livewire.stories.manage-story-characters', [
            'characters' => Character::where('story_id', $this->story->id)->get(),
        ])->layout('layouts.app');
    }
}
