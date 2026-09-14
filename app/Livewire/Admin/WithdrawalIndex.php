<?php

namespace App\Livewire\Admin;

use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class WithdrawalIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterStatus = 'pending';

    // Modal Action
    public $selectedRequest;
    public $showProcessModal = false;
    public $actionType = ''; // 'approve' atau 'reject'

    // Form Inputs
    public $proofFile;
    public $rejectionReason = '';

    protected $paginationTheme = 'tailwind';

    public function openProcessModal($id, $type)
    {
        $this->selectedRequest = WithdrawalRequest::with('user')->findOrFail($id);
        $this->actionType = $type;
        $this->reset(['proofFile', 'rejectionReason']);
        $this->resetValidation();
        $this->showProcessModal = true;
    }

    public function processRequest()
    {
        if ($this->actionType === 'approve') {
            $this->validate([
                'proofFile' => 'required|image|max:2048', // Maks 2MB
            ], [
                'proofFile.required' => 'Wajib mengunggah bukti transfer.',
                'proofFile.image' => 'File bukti transfer harus berupa gambar.',
            ]);

            try {
                DB::transaction(function () {
                    $path = $this->proofFile->store('withdrawal-proofs', 'public');

                    $this->selectedRequest->update([
                        'status' => 'approved',
                        'proof_file_path' => $path,
                        'processed_at' => now(),
                        'processed_by' => auth()->id(),
                    ]);
                });

                $this->dispatch('show-toast', type: 'success', message: 'Permintaan pencairan berhasil disetujui!');
            } catch (\Throwable $th) {
                $this->dispatch('show-toast', type: 'error', message: 'Gagal memproses pencairan.');
            }
        } elseif ($this->actionType === 'reject') {
            $this->validate([
                'rejectionReason' => 'required|string|min:5|max:255',
            ], [
                'rejectionReason.required' => 'Alasan penolakan wajib diisi.',
            ]);

            try {
                DB::transaction(function () {
                    // Kembalikan saldo ke akun user/penulis jika ditolak
                    $user = $this->selectedRequest->user;
                    $user->increment('earned_beans', $this->selectedRequest->kisa_amount);

                    $this->selectedRequest->update([
                        'status' => 'rejected',
                        'rejection_reason' => $this->rejectionReason,
                        'processed_at' => now(),
                        'processed_by' => auth()->id(),
                    ]);
                });

                $this->dispatch('show-toast', type: 'success', message: 'Permintaan pencairan berhasil ditolak dan saldo dikembalikan.');
            } catch (\Throwable $th) {
                $this->dispatch('show-toast', type: 'error', message: 'Gagal menolak pencairan.');
            }
        }

        $this->showProcessModal = false;
    }

    public function render()
    {
        $requests = WithdrawalRequest::with(['user', 'processor'])
            ->when($this->search, function ($query) {
                $query->where('reference_no', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.withdrawal-index', [
            'requests' => $requests,
        ])->layout('layouts.admin');
    }
}
