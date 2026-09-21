<?php

namespace App\Livewire;

use App\Models\CarouselSubmission;
use Livewire\Component;
use Livewire\WithPagination;

class MyPromotions extends Component
{
    use WithPagination;

    public string $filterStatus = 'all'; // 'all', 'pending', 'approved', 'rejected'

    public function render()
    {
        $submissions = CarouselSubmission::with(['story'])
            ->where('user_id', auth()->id())
            ->when($this->filterStatus !== 'all', fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(5);

        return view('livewire.my-promotions', [
            'submissions' => $submissions,
        ]);
    }
}
