<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class VerifyEmail extends Component
{
    public function resendVerificationEmail()
    {
        if (auth()->user() && auth()->user()->hasVerifiedEmail()) {
            return redirect()->intended('/');
        }

        if (auth()->user()) {
            auth()->user()->sendEmailVerificationNotification();
            session()->flash('status', 'verification-link-sent');
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
