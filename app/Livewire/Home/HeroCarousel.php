<?php

namespace App\Livewire\Home;

use App\Models\Story;
use Livewire\Component;

class HeroCarousel extends Component
{
    public function render()
    {
        $featuredStories = Story::with(['penName', 'genre'])
            ->where('status', 'published')
            ->where('monetization_type', 'premium')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.home.hero-carousel', [
            'featuredStories' => $featuredStories
        ]);
    }
}
