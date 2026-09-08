<?php


namespace App\Livewire\Admin;

use App\Models\UserTransaction;
use App\Services\Duitku\DuitkuService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $selectedTransaction = null;
    public $showDetailModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openDetail($transactionId)
    {
        $this->selectedTransaction = UserTransaction::with('user')->find($transactionId);
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedTransaction = null;
    }

    public function syncStatus($transactionId, DuitkuService $duitkuService)
    {
        $transaction = UserTransaction::find($transactionId);
        if (!$transaction) {
            $this->dispatch('show-toast', message: 'Transaksi tidak ditemukan.', type: 'error');
            return;
        }

        $checkResult = $duitkuService->checkTransactionStatus($transaction->reference_code);
        $statusCode = $checkResult['statusCode'] ?? null;

        if ($statusCode == '00') {
            if ($transaction->status !== 'success') {
                DB::transaction(function () use ($transaction, $checkResult) {
                    $payload = json_decode($transaction->payment_payload, true) ?? [];
                    $payload['admin_sync_response'] = $checkResult;

                    $transaction->update([
                        'status' => 'success',
                        'payment_payload' => json_encode($payload),
                    ]);

                    DB::table('users')
                        ->where('id', $transaction->user_id)
                        ->increment('kisa_bean_balance', $transaction->amount);
                });
                $this->dispatch('show-toast', message: 'Status berhasil diperbarui ke SUKSES & saldo ditambahkan.', type: 'success');
            } else {
                $this->dispatch('show-toast', message: 'Transaksi sudah berstatus SUKSES.', type: 'info');
            }
        } elseif (in_array($statusCode, ['01', '02'])) {
            $transaction->update(['status' => 'expired']);
            $this->dispatch('show-toast', message: 'Status diperbarui: Transaksi Expired/Gagal.', type: 'warning');
        } else {
            $this->dispatch('show-toast', message: 'Status di Duitku masih PENDING', type: 'info');
        }

        if ($this->selectedTransaction && $this->selectedTransaction->id === $transactionId) {
            $this->selectedTransaction = $transaction->fresh('user');
        }
    }

    public function render()
    {
        $stats = [
            'total_revenue' => UserTransaction::where('status', 'success')->sum('gross_amount'),
            'total_success' => UserTransaction::where('status', 'success')->count(),
            'total_pending' => UserTransaction::where('status', 'pending')->count(),
            'total_failed'  => UserTransaction::whereIn('status', ['expired', 'failed'])->count(),
        ];

        // Query transaksi dengan filter & pencarian
        $transactions = UserTransaction::with('user')
            ->where('type', 'topup')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference_code', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($u) {
                            $u->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.admin.transaction-management', [
            'transactions' => $transactions,
            'stats' => $stats,
        ])->layout('layouts.admin');;
    }
}
