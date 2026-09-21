<?php

namespace App\Livewire\Admin;

use App\Models\CarouselSubmission;
use App\Models\UserTransaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ManageCarouselSubmissions extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public string $rejectReason = '';
    public ?int $selectedSubmissionId = null;
    public bool $isRejectModalOpen = false;

    public function approve(int $id)
    {
        $submission = CarouselSubmission::findOrFail($id);
        if ($submission->status !== 'pending') {
            session()->flash('error', 'Pengajuan ini sudah di proses sebelumnya.');
            return;
        }
        $transactionUser = UserTransaction::where('reference_code', $submission->referenceCode)->where('status', 'pending')->first();

        if (!$transactionUser) {
            session()->flash('error', 'Transaksi user tidak ditemukan.');
            return;
        }

        DB::transaction(function () use ($submission, $transactionUser) {

            $transactionUser->update([
                'status' => 'success'
            ]);

            $submission->update([
                'status' => 'approved',
                'starts_at' => now(),
                'expires_at' => now()->addDays($submission->days)
            ]);
        });

        session()->flash('message', 'Pengajuan disetujui! Banner langsung tayang hingga ' . $submission->expires_at->format('d M Y H:i'));
    }

    public function openRejectModal(int $id)
    {
        $this->selectedSubmissionId = $id;
        $this->rejectReason = '';
        $this->isRejectModalOpen = true;
    }

    public function closeRejectModal()
    {
        $this->isRejectModalOpen = false;
        $this->selectedSubmissionId = null;
    }

    public function reject()
    {
        $this->validate([
            'rejectReason' => 'required|string|min:5',
        ], [
            'rejectReason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $submission = CarouselSubmission::with('user')->findOrFail($this->selectedSubmissionId);

        if ($submission->status !== 'pending') {
            session()->flash('error', 'Pengajuan ini sudah diproses sebelumnya.');
            return;
        }

        $transactionUser = UserTransaction::where('reference_code', $submission->referenceCode)->where('status', 'pending')->first();

        if (!$transactionUser) {
            session()->flash('error', 'Transaksi user tidak ditemukan.');
            return;
        }

        DB::transaction(function () use ($submission, $transactionUser) {
            $submission->user->increment('kisa_bean_balance', $submission->cost_in_beans);

            $transactionUser->update([
                'status' => 'failed'
            ]);

            $submission->update([
                'status' => 'rejected',
                'admin_note' => $this->rejectReason,
            ]);
        });

        session()->flash('message', 'Pengajuan ditolak & Kisa Bean telah dikembalikan ke user.');
        $this->closeRejectModal();
    }

    public function render()
    {
        $submissions = CarouselSubmission::with(['user', 'story'])
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-carousel-submissions', [
            'submissions' => $submissions,
        ])->layout('layouts.admin');
    }
}
