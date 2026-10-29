<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;

class PrivacyPolicy extends Component
{
    public function render()
    {
        return view('livewire.privacy-policy', [
            'lastUpdated' => '28 Oktober 2026',
        ])->layout('layouts.app');
    }
}
