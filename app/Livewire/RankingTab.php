<?php

namespace App\Livewire;

use App\Models\Story;
use Livewire\Component;

class RankingTab extends Component
{
    // State Tab Peringkat ('free' atau 'premium')
    public string $rankingTab = 'premium';

    public function mount()
    {
        $this->rankingTab = 'premium';
    }

    public function setRankingTab(string $type)
    {
        if (in_array($type, ['free', 'premium'])) {
            $this->rankingTab = $type;
        }
    }

    public function render()
    {
        // 2. Peringkat Top 10 Berdasarkan Tab (Gratis vs Premium)
        $topStories = Story::with(['author', 'genre'])
            ->where('monetization_type', $this->rankingTab)
            ->where('status', 'published')
            ->orderByDesc('views_count')
            ->limit(10)
            ->get();

        return view('livewire.ranking-tab', [
            'topStories' => $topStories,
        ]);
    }
}
