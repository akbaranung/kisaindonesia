<?php

namespace App\Livewire\Author;

use App\Models\Setting;
use App\Models\Story;
use App\Models\StoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SignContract extends Component
{
    public Story $story;

    public string $fullName = '';
    public string  $ktpNumber = '';
    public string $pob = '';
    public ?string $dob = null;
    public string $address = '';
    public bool $agree = false;
    public bool $isSigned = false;

    public function mount(Story $story)
    {
        $user = Auth::user();

        if ($story->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $this->story = $story;

        $latestVersion = (int) Setting::get('current_contract_version', 1);
        $existingContract = $story->contract;

        if ($existingContract && $existingContract->contract_version >= $latestVersion) {
            return redirect()->route('my-stories');
        }

        $this->fullName = $user->name ?? '';
        $this->ktpNumber = $user->ktp_number ?? '';
        $this->pob = $user->pob ?? '';
        $this->dob = $user ? Carbon::parse($user->dob)->format('Y-m-d') : null;
        $this->address = $user->address ?? '';
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

        $latestVersion = (int) Setting::get('current_contract_version', 1);
        StoryContract::updateOrCreate(
            ['story_id' => $this->story->id],
            [
                'user_id' => Auth::id(),
                'contract_version' => $latestVersion,
                'signer_name' => $this->fullName,
                'ktp_number' => $this->ktpNumber,
                'pob' => $this->pob,
                'dob' => $this->dob,
                'address' => $this->address,
                'signed_at' => now(),
                'signature_ip' => request()->ip(),
            ]
        );

        $dataStory = $this->story;

        if ($dataStory) {
            $dataStory->update([
                'monetization_type' => 'premium'
            ]);


            foreach ($dataStory->chapters as $chapter) {
                if ($chapter->order_number > 5) {
                    $priceInBeans = $chapter->calculateKisaBean();

                    $chapter->update([
                        'is_premium' => true,
                        'bean_price'      => $priceInBeans,
                    ]);
                } else {
                    $chapter->update([
                        'is_premium' => false,
                        'bean_price'      => 0,
                    ]);
                }
            }
        }

        session()->flash('message', "Perjanjian Kerja Sama untuk cerita '{$this->story->title}' berhasil ditandatangani!");

        return redirect()->route('my-stories');
    }

    public function render()
    {
        return view('livewire.author.sign-contract')->layout('layouts.app', ['title' => 'Tanda Tangan Kontrak Penulis']);
    }
}
