<?php

use App\Models\CarouselSubmission;
use App\Models\Setting;
use App\Models\Story;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class SubmitCarouselModal extends Component
{
    public bool $isOpen = false;
    public $storyId;
    public $durationDays = 0;
    public $costPerDay = 20;

    protected $listeners = ['openSubmitCarouselModal' => 'openModal'];

    public function mount()
    {
        $this->costPerDay = Setting::get('carousel_cost_per_day', 20);
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['storyId', 'durationDays']);
        $this->durationDays = 7;
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function getCalculatedCostProperty(): int
    {
        $days = (int) $this->durationDays > 0 ? (int) $this->durationDays : 1;
        return $days * $this->costPerDay;
    }

    public function submit()
    {
        $user = auth()->user();

        $this->validate([
            'storyId' => 'required|exists:stories,id',
            'durationDays' => 'required|integer|min:1|max:30',
        ], [
            'storyId.required' => 'Pilih cerita yang ingin dipromosikan.',
            'durationDays.min' => 'Minimal durasi promosi adalah 1 hari.',
            'durationDays.max' => 'Maksimal durasi promosi adalah 30 hari.',
        ]);

        $story = Story::withCount(['chapters' => function ($q) {
            $q->where('status', 'published');
        }])->where('user_id', $user->id)->findOrFail($this->storyId);

        if ($story->chapters_count < 5) {
            $this->addError('storyId', 'Cerita ini belum memenuhi syarat. Minimal harus memiliki 5 bab yang dipublikasikan (saat ini: ' . $story->chapters_count . ' bab).');
            return;
        }

        $totalCost = $this->calculatedCost;

        if (($user->kisa_bean_balance ?? 0) < $totalCost) {
            $this->addError('durationDays', 'Saldo Kisa Bean Kamu tidak mencukupi untuk durasi ini. Butuh ' . number_format($totalCost) . ' Bean.');
            return;
        }

        $existingPending = CarouselSubmission::where('story_id', $this->storyId)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->exists();

        if ($existingPending) {
            $this->addError('storyId', 'Cerita ini sudah dalam antrean atau sedang aktif promosi.');
            return;
        }

        DB::transaction(function () use ($user, $totalCost) {
            $user->decrement('kisa_bean_balance', $totalCost);

            // Buat Catatan Transaksi (Jika ada tabel coin_transactions)
            $refGroup = 'ADS-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            $user->transactions()->create([
                'user_id'        => $user->id,
                'reference_code' => $refGroup,
                'type'           => 'spend',
                'amount'         => $totalCost,
                'gross_amount'   => 0,
                'payment_method' => 'KISA_BEAN',
                'status'         => 'pending',
                'description'    => 'Pengajuan Promo Banner Carousel',
            ]);

            CarouselSubmission::create([
                'user_id' => $user->id,
                'story_id' => $this->storyId,
                'cost_in_beans' => $totalCost,
                'status' => 'pending',
                'referenceCode' => $refGroup
            ]);
        });

        session()->flash('success', 'Pengajuan carousel berhasil dikirim! Menunggu persetujuan admin.');
        $this->closeModal();
        $this->dispatch('carouselSubmitted');
    }

    public function render()
    {
        $userStories = auth()->check()
            ? Story::where('user_id', auth()->id())->where('status', 'published')->get() : collect();

        return view('livewire.submit-carousel-modal', ['userStories' => $userStories]);
    }
}
