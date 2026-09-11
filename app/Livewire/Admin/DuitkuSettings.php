<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Services\Duitku\DuitkuService;
use Livewire\Component;

class DuitkuSettings extends Component
{
    public $merchantCode;
    public $apiKey;
    public $isSandbox = true;
    public $expiryPeriod = 60;
    public $enabledMethods = [];
    public $apiPaymentMethods = [];

    public function mount(DuitkuService $duitkuService)
    {
        $this->merchantCode = Setting::get('duitku_merchant_code', '');
        $this->apiKey = Setting::get('duitku_api_key', '');
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
    }

    public function save()
    {
        $this->validate([
            'merchantCode' => 'required|string',
            'apiKey' => 'required|string',
            'isSandbox' => 'required|boolean',
            'expiryPeriod' => 'required|numeric|min:5|max:1440',
            'enabledMethods' => 'required|array|min:1',
        ]);

        Setting::set('duitku_merchant_code', trim($this->merchantCode));
        Setting::set('duitku_api_key', trim($this->apiKey));
        Setting::set('duitku_is_sandbox', $this->isSandbox ? '1' : '0');
        Setting::set('duitku_expiry_period', $this->expiryPeriod);
        Setting::set('duitku_enabled_methods', json_encode($this->enabledMethods));

        $this->dispatch('show-toast', message: 'Pengaturan Duitku berhasil disimpan!', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.duitku-settings')->layout('layouts.admin');
    }
}
