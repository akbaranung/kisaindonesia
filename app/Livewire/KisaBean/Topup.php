<?php

namespace App\Livewire\KisaBean;

use Livewire\Component;
use App\Models\CoinPackage;
use App\Models\UserTransaction;
use App\Services\Duitku\DuitkuService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Topup extends Component
{
    public $selectedPackage = null;
    public $paymentMethod = 'NQ'; // Default payment method

    public function selectPackage($packageId)
    {
        $package = CoinPackage::where('is_active', true)->findOrFail($packageId);
        $this->selectedPackage = $package;
    }

    public function processTopup(DuitkuService $duitkuService)
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
        $price = ($package->discount_price && $package->discount_price < $package->price)
            ? $package->discount_price
            : $package->price;

        $user = Auth::user();
        $userId = $user->id;

        $refCode = 'KB-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $result = $duitkuService->createInvoice(
            $refCode,
            (int) $price,
            $this->paymentMethod,
            $user->email,
            $user->name ?? 'User Kisa'
        );

        if (isset($result['statusCode']) && $result['statusCode'] == '00') {
            DB::transaction(function () use ($userId, $totalBeansGained, $package, $refCode, $result) {
                UserTransaction::create([
                    'user_id' => $userId,
                    'reference_code' => $refCode,
                    'type' => 'topup',
                    'amount' => $totalBeansGained,
                    'gross_amount' => $package->price,
                    'payment_method' => strtoupper($this->paymentMethod),
                    'status' => 'pending',
                    'description' => 'Top Up ' . number_format($totalBeansGained) . ' Kisa Bean via ' . strtoupper($this->paymentMethod),
                    'payment_payload' => json_encode($result),
                ]);
            });


            $this->dispatch('open-duitku-popup', [
                'reference' => $result['reference'],
                'referenceCode' => $refCode
            ]);
            return;
        }

        session()->flash('error', $result['statusMessage'] ?? 'Gagal membuat transaksi ke payment gateway.');
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
