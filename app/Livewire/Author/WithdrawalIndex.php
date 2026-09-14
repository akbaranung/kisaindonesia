<?php

namespace App\Livewire\Author;

use App\Models\Setting;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class WithdrawalIndex extends Component
{
    public $kisa_amount;

    // Data Rekening Bank
    public $bank_name = '';
    public $account_number = '';
    public $account_name = '';
    public $is_manual_bank = false; // Flag jika user ingin menginput manual
    public $has_saved_bank = false;  // Flag penanda apakah ada data bawaan dari premium

    // Kalkulasi Realtime
    public $rate = 150;
    public $admin_fee = 3000;
    public $min_kisa = 100;

    public function mount()
    {
        $user = Auth::user();

        // 1. Ambil setting konversi & admin fee dari database
        $this->rate = (float) Setting::get('kisa_to_rupiah_rate', 150);
        $this->admin_fee = (float) Setting::get('withdrawal_admin_fee', 3000);
        $this->min_kisa = (int) Setting::get('min_withdrawal_kisa', 100);

        // 2. Ambil data rekening bank dari tabel premium_story_requests
        $premiumRequest = DB::table('premium_story_requests')
            ->where('user_id', $user->id)
            ->whereNotNull('bank_name')
            ->latest()
            ->first();

        if ($premiumRequest) {
            $this->bank_name = $premiumRequest->bank_name;
            $this->account_number = $premiumRequest->account_number;
            $this->account_name = $premiumRequest->account_name;
            $this->has_saved_bank = true;
        } else {
            // Jika tidak ada data tersimpan, otomatis masuk mode manual input
            $this->is_manual_bank = true;
        }
    }

    public function toggleManualBank()
    {
        $this->is_manual_bank = !$this->is_manual_bank;

        // Reset bidang jika beralih kembali ke mode otomatis
        if (!$this->is_manual_bank && $this->has_saved_bank) {
            $this->mount();
        }
    }

    public function getGrossAmountProperty()
    {
        $amount = (int) $this->kisa_amount;
        return $amount > 0 ? $amount * $this->rate : 0;
    }

    public function getNetAmountProperty()
    {
        $gross = $this->grossAmount;
        return max(0, $gross - $this->admin_fee);
    }

    public function submitWithdrawal()
    {
        $user = Auth::user();

        // Rules validasi dinamis
        $rules = [
            'kisa_amount' => [
                'required',
                'integer',
                "min:{$this->min_kisa}",
                "max:{$user->earned_beans}",
            ],
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:100',
        ];

        $messages = [
            'kisa_amount.min' => "Minimal penarikan adalah {$this->min_kisa} Kisa.",
            'kisa_amount.max' => 'Saldo Kisa Anda tidak mencukupi.',
            'bank_name.required' => 'Nama bank/e-wallet wajib diisi.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_name.required' => 'Nama pemilik rekening wajib diisi.',
        ];

        $this->validate($rules, $messages);

        if ($this->netAmount <= 0) {
            $this->dispatch('show-toast', type: 'error', message: 'Nominal penarikan terlalu kecil setelah dipotong biaya admin.');
            return;
        }

        DB::transaction(function () use ($user) {
            $referenceNo = 'WD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            WithdrawalRequest::create([
                'user_id' => $user->id,
                'reference_no' => $referenceNo,
                'kisa_amount' => $this->kisa_amount,
                'conversion_rate' => $this->rate,
                'gross_amount' => $this->grossAmount,
                'admin_fee' => $this->admin_fee,
                'net_amount' => $this->netAmount,
                'bank_name' => trim($this->bank_name),
                'account_number' => trim($this->account_number),
                'account_name' => trim($this->account_name),
                'status' => 'pending',
            ]);

            $user->decrement('earned_beans', $this->kisa_amount);
        });

        $this->reset('kisa_amount');
        $this->dispatch('show-toast', type: 'success', message: 'Pengajuan penarikan dana berhasil dikirim!');
    }

    public function render()
    {
        $withdrawals = WithdrawalRequest::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.author.withdrawal-index', [
            'withdrawals' => $withdrawals,
        ]);
    }
}
