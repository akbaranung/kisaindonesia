<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Services\Duitku\DuitkuService;
use Livewire\Component;

class SystemSettings extends Component
{
    // Navigasi Tab Active ('duitku', 'withdrawal', 'general')
    public string $activeTab = 'withdrawal';

    // Form Field: Duitku Settings
    public string $duitku_merchant_code = '';
    public string $duitku_api_key = '';
    public $isSandbox = true;
    public $expiryPeriod = 60;
    public $enabledMethods = [];
    public $apiPaymentMethods = [];

    // Form Field: Withdrawal & Kisa Settings
    public float $kisa_to_rupiah_rate = 150;
    public float $withdrawal_admin_fee = 3000;
    public int $min_withdrawal_kisa = 100;

    public function mount(DuitkuService $duitkuService)
    {
        // Load Duitku
        $this->duitku_merchant_code = Setting::get('duitku_merchant_code', '');
        $this->duitku_api_key = Setting::get('duitku_api_key', '');
        $this->isSandbox      = filter_var(Setting::get('duitku_is_sandbox', true), FILTER_VALIDATE_BOOLEAN);
        $this->expiryPeriod = Setting::get('duitku_expiry_period', 60);
        $response = $duitkuService->getPaymentMethods();
        $this->apiPaymentMethods = $response['methods'];

        $savedMethods = Setting::get('duitku_enabled_methods');

        if ($savedMethods) {
            $this->enabledMethods = json_decode($savedMethods, true);
        } else {
            $this->enabledMethods = array_column($this->apiPaymentMethods, 'paymentMethod');
        }

        // Load Withdrawal & Kisa
        $this->kisa_to_rupiah_rate = (float) Setting::get('kisa_to_rupiah_rate', 150);
        $this->withdrawal_admin_fee = (float) Setting::get('withdrawal_admin_fee', 3000);
        $this->min_withdrawal_kisa = (int) Setting::get('min_withdrawal_kisa', 100);
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function saveWithdrawalSettings()
    {
        $this->validate([
            'kisa_to_rupiah_rate' => 'required|numeric|min:1',
            'withdrawal_admin_fee' => 'required|numeric|min:0',
            'min_withdrawal_kisa' => 'required|integer|min:1',
        ]);

        Setting::set('kisa_to_rupiah_rate', $this->kisa_to_rupiah_rate);
        Setting::set('withdrawal_admin_fee', $this->withdrawal_admin_fee);
        Setting::set('min_withdrawal_kisa', $this->min_withdrawal_kisa);

        $this->dispatch('show-toast', type: 'success', message: 'Pengaturan Pencairan & Kisa berhasil disimpan!');
    }

    public function saveDuitkuSettings()
    {
        $this->validate([
            'duitku_merchant_code' => 'required|string',
            'duitku_api_key' => 'required|string',
            'isSandbox' => 'required|boolean',
            'expiryPeriod' => 'required|numeric|min:5|max:1440',
            'enabledMethods' => 'required|array|min:1',
        ]);

        Setting::set('duitku_merchant_code', $this->duitku_merchant_code);
        Setting::set('duitku_api_key', $this->duitku_api_key);
        Setting::set('duitku_is_sandbox', $this->isSandbox ? '1' : '0');
        Setting::set('duitku_expiry_period', $this->expiryPeriod);
        Setting::set('duitku_enabled_methods', json_encode($this->enabledMethods));

        $this->dispatch('show-toast', type: 'success', message: 'Pengaturan Payment Gateway Duitku berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.admin.system-settings')->layout('layouts.admin');
    }
}
