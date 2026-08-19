<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validasi Inputan dengan Pesan Bahasa Indonesia yang Santai
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama kamu jangan dikosongin ya, Bro.',
            'email.required' => 'Email wajib diisi untuk verifikasi.',
            'email.email' => 'Format email-mu agak aneh nih, coba cek lagi.',
            'email.unique' => 'Email ini sudah terdaftar, Bro. Coba login aja.',
            'password.required' => 'Password wajib diisi demi keamanan.',
            'password.min' => 'Password-nya minimal 8 karakter ya biar aman.',
            'password.confirmed' => 'Konfirmasi password-mu nggak cocok, Bro.',
        ]);

        // 2. Simpan Data User Baru ke Database MySQL
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password di-hash aman
        ]);

        // 3. Otomatis Login-kan User yang Baru Daftar
        Auth::login($user);

        // 4. Lempar ke Halaman Utama dengan Status Sukses
        return redirect('/')->with('success', 'Akun Novelia-mu berhasil dibuat! 🎉');
    }
}
