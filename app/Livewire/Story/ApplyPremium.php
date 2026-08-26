<?php

namespace App\Livewire\Story;

use Livewire\Component;
use App\Models\Story;
use App\Models\PremiumStoryRequest;
use Illuminate\Support\Facades\Auth;

class ApplyPremium extends Component
{
    public $storyId;
    public $selectedStory = null;

    public $bankName = '';
    public $accountNumber = '';
    public $accountHolderName = '';
    public $authorNotes = '';

    public $isEligible = false;
    public $totalWords = 0;
    public $totalChapters = 0;
    public $estimatedBeans = 0;
    public $existingRequest = null;

    protected $rules = [
        'bankName'          => 'required|string|max:50',
        'accountNumber'     => 'required|numeric|digits_between:8,20',
        'accountHolderName' => 'required|string|max:100',
        'authorNotes'       => 'nullable|string|max:500',
    ];

    protected $messages = [
        'bankName.required'          => 'Pilih atau isi nama bank / e-wallet pencairan.',
        'accountNumber.required'     => 'Nomor rekening wajib diisi.',
        'accountNumber.numeric'      => 'Nomor rekening harus berupa angka.',
        'accountHolderName.required' => 'Nama pemilik rekening wajib diisi.',
    ];

    public function mount()
    {

        $this->storyId = request()->query('story_id');

        if ($this->storyId) {
            $this->loadStoryDetails();
        }
    }

    public function updatedStoryId()
    {
        $this->loadStoryDetails();
    }

    public function loadStoryDetails()
    {
        if (!$this->storyId) {
            $this->selectedStory = null;
            return;
        }

        $this->selectedStory = Story::with('chapters')
            ->where('user_id', Auth::id())
            ->findOrFail($this->storyId);

        // Cek status pengajuan sebelumnya jika ada
        $this->existingRequest = PremiumStoryRequest::where('story_id', $this->storyId)
            ->latest()
            ->first();

        $this->totalChapters = $this->selectedStory->chapters->count();
        $this->totalWords = 0;
        $this->estimatedBeans = 0;

        $validPremiumChapters = 0;
        $invalidChaptersCount = 0;


        foreach ($this->selectedStory->chapters as $index => $chapter) {
            $words = $chapter->word_count;
            $beans = $chapter->calculateKisaBean();

            $this->totalWords += $words;

            // Bab 1-5 sampel gratis, perhitungan estimasi koin dimulai dari bab 6
            if ($index >= 5) {
                $type = strtolower($chapter->type ?? $this->selectedStory->type ?? 'regular');
                $isValidWordCount = false;

                if ($type === 'puisi') {
                    // Puisi: 700 - 1500 kata murni
                    $isValidWordCount = ($words >= 700 && $words <= 1500);
                } else {
                    // Novel, chat fic : 1000 - 1500 kata murni
                    $isValidWordCount = ($words >= 1000 && $words <= 1500);
                }

                if ($isValidWordCount) {
                    $validPremiumChapters++;
                    $this->estimatedBeans += $beans;
                } else {
                    $invalidChaptersCount++;
                }
            }
        }

        $this->isEligible = ($this->totalChapters >= 6) && ($validPremiumChapters > 0) && ($invalidChaptersCount === 0);
    }

    public function submitApplication()
    {
        $this->validate();

        if (!$this->selectedStory || !$this->isEligible) {
            session()->flash('error', 'Cerita Anda belum memenuhi syarat kelayakan.');
            return;
        }

        if ($this->existingRequest && $this->existingRequest->status === 'pending') {
            session()->flash('error', 'Pengajuan untuk cerita ini sedang dalam proses peninjauan.');
            return;
        }

        PremiumStoryRequest::create([
            'user_id'                 => Auth::id(),
            'story_id'                => $this->selectedStory->id,
            'bank_name'               => $this->bankName,
            'account_number'          => $this->accountNumber,
            'account_holder_name'     => $this->accountHolderName,
            'author_notes'            => $this->authorNotes ?? null,
            'total_chapters_submitted' => $this->totalChapters,
            'status'                  => 'pending',
        ]);

        session()->flash('success', 'Pengajuan cerita premium berhasil dikirim! Tim kami akan meninjau pengajuan Anda.');
        $this->loadStoryDetails();
    }

    public function render()
    {
        // Ambil daftar cerita milik Penulis yang masih berstatus 'free'
        $myStories = Story::where('user_id', Auth::id())
            ->where('monetization_type', 'free')
            ->get();

        return view('livewire.stories.apply-premium', [
            'myStories' => $myStories,
        ]);
    }
}
