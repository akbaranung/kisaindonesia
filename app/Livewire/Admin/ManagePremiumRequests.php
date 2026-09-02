<?php

namespace App\Livewire\Admin;

use App\Models\PremiumStoryRequest;
use App\Notifications\MonetizeStatusUpdatedNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ManagePremiumRequests extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public $selectedRequest = null;
    public $rejectionReason = '';
    public $isDetailModalOpen = false;
    public $isRejectModalOpen = false;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openDetailModal($id)
    {
        $this->selectedRequest = PremiumStoryRequest::with([
            'user',
            'story.genre',
            'story.chapters' => function ($q) {
                $q->orderBy('order_number', 'asc');
            },
            'processor'
        ])->findOrFail($id);

        $this->isDetailModalOpen = true;
    }

    public function closeModals()
    {
        $this->isDetailModalOpen = false;
        $this->isRejectModalOpen = false;
        $this->selectedRequest = null;
        $this->rejectionReason = '';
        $this->resetErrorBag();
    }

    public function approve($id)
    {
        $requestItem = PremiumStoryRequest::findOrFail($id);

        $requestItem->update([
            'status' => 'approved',
            'rejection_reason' => null,
            'processed_by' => Auth::id()
        ]);

        if ($requestItem->story) {
            $requestItem->story->update([
                'monetization_type' => 'premium'
            ]);


            foreach ($requestItem->story->chapters as $chapter) {
                if ($chapter->order_number > 5) {
                    $priceInBeans = $chapter->calculateKisaBean();

                    $chapter->update([
                        'is_premium' => true,
                        'bean_price'      => $priceInBeans,
                    ]);
                } else {
                    $chapter->update([
                        'is_premium' => false,
                        'bean_price'      => 0,
                    ]);
                }
            }
        }

        $user = $requestItem->user;

        if ($user) {
            $user->notify(new MonetizeStatusUpdatedNotification('approved'));
        }

        session()->flash('message', 'Pengajuan cerita premium berhasil disetujui!');
        $this->closeModals();
    }

    public function openRejectModal($id)
    {
        $this->selectedRequest = PremiumStoryRequest::findOrFail($id);
        $this->isRejectModalOpen = true;
    }

    public function reject()
    {
        $this->validate(
            [
                'rejectionReason' => 'required|min:5|max:255',
            ],
            [
                'rejectionReason.required' => 'Alasan penolakan wajib diisi!',
                'rejectionReason.min' => 'Alasan minimal 5 karakter!'
            ]
        );

        if ($this->selectedRequest) {
            $this->selectedRequest->update([
                'status' => 'rejected',
                'rejection_reason' => $this->rejectionReason,
                'processed_by' => Auth::id()
            ]);

            $user = $this->selectedRequest->user;

            if ($user) {
                // Kirim notifikasi status 'rejected' beserta alasannya
                $user->notify(new MonetizeStatusUpdatedNotification(
                    'rejected',
                    $this->rejectionReason
                ));
            }

            session()->flash('message', 'Pengajuan berhasil ditolak!');
        }

        $this->closeModals();
    }

    public function render()
    {
        $query = PremiumStoryRequest::with(['user', 'story', 'processor'])
            ->where(function ($q) {
                $q->whereHas('story', function ($storyQuery) {
                    $storyQuery->where('title', 'like', '%' . $this->search . '%');
                })->orWhereHas('user', function ($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%');
                });
            });

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        $requests = $query->latest()->paginate(10);

        return view('livewire.admin.page-premium-requests', [
            'requests' => $requests
        ])->layout('layouts.admin');
    }
}
