<?php

namespace App\Livewire\Story;

use Livewire\Component;
use App\Models\Rating;

class StoryReview extends Component
{
    public $storyId;
    public $rating = 5;
    public $review = '';

    public $hasSubmitted = false;

    public function mount($storyId)
    {
        $this->storyId = $storyId;

        if (auth()->check()) {
            $existing = Rating::where('user_id', auth()->id())->where('story_id', $this->storyId)->first();

            if ($existing) {
                $this->rating = $existing->rating;
                $this->review = $existing->review;
                $this->hasSubmitted = true;
            }
        }
    }

    public function saveReview()
    {
        if (!auth()->check()) {
            session()->flash('error', 'Kamu harus login dulu untuk memberi ulasan, Bro!');
            return;
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'story_id' => $this->storyId
            ],
            [
                'rating' => $this->rating,
                'review' => $this->review
            ]
        );

        $this->hasSubmitted = true;

        // Kirim sinyal ke parent component (StoryDetail) agar skor rating rata-rata langsung ter-refresh
        $this->dispatch('review-updated');

        session()->flash('success', 'Ulasan kamu berhasil disimpan, hatur nuhun Bro!');
    }

    public function deleteReview()
    {
        if (!auth()->check()) {
            return;
        }

        Rating::where('user_id', auth()->id())->where('story_id', $this->storyId)->delete();

        $this->rating = 5;
        $this->review = '';
        $this->hasSubmitted = false;

        $this->dispatch('review-updated');

        session()->flash('success', 'Ulasan kamu berhasil dihapus, Bro!');
    }

    public function render()
    {
        $reviews = Rating::with('user')
            ->where('story_id', $this->storyId)
            ->latest()
            ->get();

        return view('livewire.stories.story-review', [
            'reviews' => $reviews
        ]);
    }
}
