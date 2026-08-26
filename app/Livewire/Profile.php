<?php

namespace App\Livewire;

use App\Models\UserTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{

    use WithFileUploads;

    public $action = 'view';
    public $name;
    public $email;
    public $phone;
    public $bio;
    public $avatar_temp;

    // password update (optional)
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public $recentTransactions = [];

    public function mount()
    {
        $this->action = request()->query('action', 'view');

        $this->loadUserData();
    }

    public function switchAction($target)
    {
        $this->action = $target;

        // Reset input foto jika user klik batal/kembali
        if ($target === 'view') {
            $user = Auth::user();
            $this->name = $user->name;
        }
    }

    /**
     * Logika Simpan Perubahan Profil & Foto Profil
     */
    public function updateProfile()
    {
        $user = Auth::user();

        // 1. Validasi Inputan
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'avatar_temp' => 'nullable|image|max:2048', // Maksimal 2MB
        ], [
            'name.required' => 'Nama lengkap jangan dikosongin ya, Bro.',
            'email.required' => 'Email jangan dikosongin ya, Bro..',
            'email.unique' => 'Email ini sudah ada yang punya.',
            'avatar_temp.image' => 'File harus berupa foto/gambar!',
            'avatar_temp.max' => 'Ukuran foto maksimal 2MB!',
        ]);

        if ($this->avatar_temp) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $newPath = $this->avatar_temp->store('avatars', 'public');

            $user->profile_photo_path = $newPath;

            $this->avatar_temp = null;
        }
        // 3. Update data teks
        $user->name = $this->name;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->bio = $this->bio;
        $user->save();

        session()->flash('success', 'Profil dan foto berhasil diperbarui! ✨');

        // Kembalikan ke tampilan detail profil utama
        $this->action = 'view';
    }

    public function updatePassword()
    {
        $this->validate(
            [
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed'
            ],
            [
                'current_password.required' => 'Password saat ini wajib diisi!',
                'new_password.required' => "Password baru wajib diisi!",
                'new_password.min' => 'Password baru minimal 8 karakter!',
                'new_password.cinfirmed' => 'Konfirmasi password tidak cocok!'
            ]
        );

        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini tidak sesuai.');
            return;
        }

        $user->password = Hash::make($this->new_password);
        $user->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('success', 'Password berhasil diubah!');
        $this->action = 'view';
    }

    public function loadUserData()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->bio = $user->bio;
        $this->bio = $user->bio ?? '';

        $this->recentTransactions = UserTransaction::where('user_id', $user->id)->latest()->take(3)->get();
    }

    public function render()
    {
        $user = auth()->user();

        $penNames = $user->penNames()->withCount('stories', 'followers')->get();

        return view('livewire.profile.profile', [
            'user' => Auth::user(),
            'followersCount' => $user->total_followers_count,
            'followingCount' => $user->following_count,
            'penNames' => $penNames,
        ]);
    }
}
