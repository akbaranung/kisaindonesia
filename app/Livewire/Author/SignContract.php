<?php

namespace App\Livewire\Author;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SignContract extends Component
{
    public string $fullName = '';
    public string  $ktpNumber = '';
    public string $pob = '';
    public ?string $dob = null;
    public string $address = '';
    public bool $agree = false;
    public bool $isSigned = false;

    public function mount()
    {
        $user = Auth::user();

        if ($user->contract_signed_at) {
            $this->isSigned = true;
            $this->fullName = $user->name ?? '';
            $this->ktpNumber = $user->ktp_number ?? '';
            $this->pob = $user->pob ?? '';
            $this->dob = $user->dob;
            $this->address = $user->address ?? '';
        } else {
            $this->fullName = $user->name ?? '';
            $this->ktpNumber = $user->ktp_number ?? '';
            $this->pob = $user->pob ?? '';
            $this->dob = $user->dob ? $user->dob->format('Y-m-d') : null;
            $this->address = $user->address ?? '';
        }
    }

    protected array $rules = [
        'fullName' => 'required|string|min:3|max:255',
        'ktpNumber' => 'required|numeric|digits:16',
        'pob' => 'required|string|min:2|max:100',
        'dob' => 'required|date|before:-17 years',
        'address' => 'required|string|min:10|max:500',
        'agree' => 'accepted'
    ];

    protected array $messages = [
        'fullName.required' => 'Nama lengkap sesuai KTP wajib diisi.',
        'ktpNumber.required' => 'Nomor KTP 16 digit wajib diisi.',
        'ktpNumber.digits' => 'Nomor KTP harus berjumlah 16 digit angka.',
        'pob.required' => 'Tempat lahir wajib diisi.',
        'dob.required' => 'Tanggal lahir wajib diisi.',
        'dob.before' => 'Penulis harus berusia minimal 17 tahun untuk menandatangani kontrak.',
        'address.required' => 'Alamat domisili lengkap wajib diisi.',
        'agree.accepted' => 'Anda wajib mencentang persetujuan seluruh isi Perjanjian Kerja Sama.'
    ];

    public function submitContract()
    {
        $this->validate();

        $user = Auth::user();

        $latestVersion = (int) Setting::get('current_contract_version', 1);

        $user->update([
            'ktp_number' => $this->ktpNumber,
            'pob' => $this->pob,
            'dob' => $this->dob,
            'address' => $this->address,
            'contract_signed_at' => now(),
            'contract_version' => $latestVersion,
            'contract_signature_ip' => request()->ip(),
        ]);

        session()->flash('message', 'Perjanjian Kerja Sama Penerbitan berhasil ditandatangani!');

        return redirect()->route('my-stories');
    }

    public function render()
    {
        return view('livewire.author.sign-contract')->layout('layouts.app', ['title' => 'Tanda Tangan Kontrak Penulis']);
    }
}
