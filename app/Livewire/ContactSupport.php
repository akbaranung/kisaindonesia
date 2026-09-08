<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;

class ContactSupport extends Component
{
    public function render()
    {
        return view('livewire.contact-support', [
            'email'    => Setting::get('support_email', 'support@kisaindonesia.com'),
            'phone'    => Setting::get('support_phone', '081234567890'),
            'whatsapp' => Setting::get('support_whatsapp', '6281234567890'),
            'address'  => Setting::get('office_address', 'Jl. Jendral Sudirman No. 123, Jakarta Selatan, DKI Jakarta 12190'),
        ]);
    }
}
