<?php

namespace App\Livewire\Home;

use App\Models\CarouselSubmission;
use App\Models\Story;
use Livewire\Component;

class HeroCarousel extends Component
{
    public function render()
    {
        $promotedSubmissions = CarouselSubmission::with([
            'story.penName',
            'story.genre',
            'story' => function ($query) {
                $query->withExists(['chapters as has_chat' => function ($q) {
                    $q->where('type', 'chat');
                }]);
            }
        ])
            ->active()
            ->inRandomOrder()
            ->get();

        return view('livewire.home.hero-carousel', [
            'promotedSubmissions' => $promotedSubmissions,
        ]);
    }
}
