<?php

namespace App\Livewire\KisaBean;

use Livewire\Component;
use App\Models\CoinPackage;
use App\Models\Setting;
use App\Models\UserTransaction;
use App\Services\Duitku\DuitkuService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Topup extends Component
{
    public $selectedPackage = null;
    public $paymentMethod = '';
    public $availableMethods = [];
    public $amount = 10000;
    public $packages = [];

    public function mount(DuitkuService $duitkuService)
    {
        $this->packages = CoinPackage::where('is_active', true)
            ->orderBy('order_priority', 'asc')
            ->orderBy('price', 'asc')
            ->get();

        if ($this->packages->isNotEmpty()) {
            $this->selectPackage($this->packages->first()->id, $duitkuService);
        }
    }



    public function selectPackage($packageId, DuitkuService $duitkuService = null)
    {
        $this->selectedPackage = CoinPackage::find($packageId);

        if (!$this->selectedPackage) {
            return;
        }

        // Hitung nominal efektif (diskon jika ada)
        $amount = $this->selectedPackage->discount_price && $this->selectedPackage->discount_price < $this->selectedPackage->price
            ? $this->selectedPackage->discount_price
            : $this->selectedPackage->price;

        // Ambil DuitkuService dari container jika dipanggil via event Livewire
        $duitkuService = $duitkuService ?? app(DuitkuService::class);

        // Ambil metode yang diizinkan Admin dari Setting
        $enabledCodes = json_decode(Setting::get('duitku_enabled_methods', '[]'), true);

        // Fetch metode pembayaran real-time berdasarkan nominal paket terpilih
        $response = $duitkuService->getPaymentMethods((int) $amount);

        if ($response['status'] && !empty($response['methods'])) {
            $this->availableMethods = array_values(array_filter($response['methods'], function ($item) use ($enabledCodes) {
                return empty($enabledCodes) || in_array($item['paymentMethod'], $enabledCodes);
            }));
        } else {
            $this->availableMethods = [];
        }
    }

    public function processTopup(DuitkuService $duitkuService)
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }
        $this->validate([
            'selectedPackage' => 'required',
            'paymentMethod'   => 'required|string',
        ], [
            'selectedPackage.required' => 'Silakan pilih paket KISA Bean terlebih dahulu.',
            'paymentMethod.required'   => 'Silakan pilih metode pembayaran.',
        ]);

        // // Cari paket aktif dari database
        // $package = CoinPackage::where('is_active', true)->findOrFail($this->selectedPackage->id);

        $finalPrice = $this->selectedPackage->discount_price && $this->selectedPackage->discount_price < $this->selectedPackage->price
            ? $this->selectedPackage->discount_price
            : $this->selectedPackage->price;

        $totalBeansGained = $this->selectedPackage->total_beans;
        $price = ($this->selectedPackage->discount_price && $this->selectedPackage->discount_price < $this->selectedPackage->price)
            ? $this->selectedPackage->discount_price
            : $this->selectedPackage->price;

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
            DB::transaction(function () use ($userId, $totalBeansGained, $price, $refCode, $result) {
                UserTransaction::create([
                    'user_id' => $userId,
                    'reference_code' => $refCode,
                    'type' => 'topup',
                    'amount' => $totalBeansGained,
                    'gross_amount' => $price,
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
