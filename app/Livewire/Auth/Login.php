<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;

    public $password;

    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    protected $messages = [
        'email.required' => 'Email-nya jangan dikosongin ya, Bro.',
        'email.email' => 'Format alamat email kamu salah, nih.',
        'password.required' => 'Password-nya wajib diisi, Bro.',
    ];

    public function prosesLogin()
    {
        $this->validate();

        if (! Auth::validate(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('email', 'Email atau password salah.');

            return;
        }

        $user = User::where('email', $this->email)->first();

        if (! $user->hasVerifiedEmail()) {

            // Opsional: Kirim ulang link verifikasi secara otomatis jika belum ada
            // $user->sendEmailVerificationNotification();

            $this->addError('email', 'Email kamu belum diverifikasi. Silakan periksa inbox/spam email kamu terlebih dahulu.');

            return;
        }

        Auth::login($user, $this->remember);

        session()->regenerate();

        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended('/');
    }

    public function render()
    {
        return view('livewire.login')->layout('layouts.app');
    }
}
