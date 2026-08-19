<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email;

    public function sendResetPasswordLink()
    {
        $this->validate(
            [
                'email' => 'required|email|exists:users,email',
            ],
            [
                'email.required' => 'Email wajib diisi!',
                'email.email' => 'Format email tidak valid!',
                'email.exists' => 'Alamat email tidak terdaftar di sistem!'
            ]
        );

        $status = Password::sendResetLink([
            'email' => $this->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', __($status));
            $this->reset('email');
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
