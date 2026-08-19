<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{

    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:355|unique:users,email',
        'password' => 'required|string|min:8|confirmed'
    ];

    protected $messages = [
        'name.required' => 'Nama lengkap jangan dikosongin ya, Bro.',
        'email.required' => 'Alamat email wajib diisi.',
        'email.email' => 'Format email kamu kayaknya salah, nih.',
        'email.unique' => 'Wah, email ini sudah terdaftar. Pakai email lain atau langsung login saja, Bro.',
        'password.required' => 'Password-nya wajib diisi, Bro.',
        'password.min' => 'Password minimal harus 8 karakter ya.',
        'password.confirmed' => 'Konfirmasi password kamu gak cocok, nih.',
    ];

    public function prosesRegister()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        // Auth::login($user);
        // Tampilkan pesan sukses dan arahkan ke login
        session()->flash('success', 'Pendaftaran berhasil! Silakan periksa email kamu untuk melakukan verifikasi.');
        return redirect()->route('login');

        return redirect()->route('verification.notice');
    }

    public function render()
    {
        return view('livewire.register');
    }
}
