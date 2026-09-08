<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class DuitkuSettings extends Component
{
    public $merchantCode;
    public $apiKey;
    public $isSandbox = true;
    public $expiryPeriod = 60;

    public function mount()
    {
        $this->merchantCode = Setting::get('duitku_merchant_code', config('services.duitku.merchant_code'));
        $this->apiKey = Setting::get('duitku_api_key', config('services.duitku.api_key'));
        $this->isSandbox = filter_var(
            Setting::get('duitku_is_sandbox', config('services.duitku.sandbox', true)),
            FILTER_VALIDATE_BOOLEAN
        );
        $this->expiryPeriod = Setting::get('duitku_expiry_period', 60);
    }

    public function save()
    {
        $this->validate([
            'merchantCode' => 'required|string',
            'apiKey' => 'required|string',
            'isSandbox' => 'required|boolean',
            'expiryPeriod' => 'required|numeric|min:5|max:1440',
        ]);

        Setting::set('duitku_merchant_code', trim($this->merchantCode));
        Setting::set('duitku_api_key', trim($this->apiKey));
        Setting::set('duitku_is_sandbox', $this->isSandbox ? '1' : '0');
        Setting::set('duitku_expiry_period', $this->expiryPeriod);

        $this->dispatch('show-toast', message: 'Pengaturan Duitku berhasil disimpan!', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.duitku-settings')->layout('layouts.admin');
    }
}
