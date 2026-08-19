<?php

namespace App\Livewire\KisaBean;

use Livewire\Component;
use App\Models\CoinPackage;
use App\Models\UserTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Topup extends Component
{
    public $selectedPackage = null;
    public $paymentMethod = 'qris'; // Default payment method

    public function selectPackage($packageId)
    {
        $package = CoinPackage::where('is_active', true)->findOrFail($packageId);
        $this->selectedPackage = $package;
    }

    public function processTopup()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        if (!$this->selectedPackage) {
            session()->flash('error', 'Silakan pilih paket Kisa Bean terlebih dahulu.');
            return redirect()->route('topup');
        }

        // Cari paket aktif dari database
        $package = CoinPackage::where('is_active', true)->findOrFail($this->selectedPackage->id);


        if (!$package) {
            session()->flash('error', 'Paket yang Anda pilih tidak valid atau sudah tidak aktif.');
            return;
        }

        $totalBeansGained = $package->total_beans; // Mendapatkan beans + bonus_beans

        $userId = Auth::id();

        DB::transaction(function () use ($userId, $totalBeansGained, $package) {
            $refCode = 'KB-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $transaction = UserTransaction::create([
                'user_id' => $userId,
                'reference_code' => $refCode,
                'type' => 'topup',
                'amount' => $totalBeansGained,
                'gross_amount' => $package->price,
                'payment_method' => strtoupper($this->paymentMethod),
                'status' => 'success',
                'description' => 'Top Up ' . number_format($totalBeansGained) . ' Kisa Bean via' . strtoupper($this->paymentMethod),
            ]);


            // Update saldo kisa bean ke user
            DB::table('users')
                ->where('id', $userId)
                ->increment('kisa_bean_balance', $totalBeansGained);
        });

        session()->flash('success', "Berhasil menambahkan {$totalBeansGained} KISA Bean ke akunmu!");
        return redirect()->route('profile');
    }

    public function render()
    {
        // Ambil paket aktif berurutan berdasarkan priority
        $packages = CoinPackage::where('is_active', true)
            ->orderBy('order_priority', 'asc')
            ->get();

        return view('livewire.beans.topup', [
            'packages' => $packages
        ]);
    }
}
