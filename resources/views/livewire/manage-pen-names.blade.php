<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Alert Notification --}}
    @if (session()->has('message'))
        <div
            class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-semibold flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button type="button" class="text-emerald-500 hover:text-emerald-700"
                onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    {{-- Breadcrumb & Header --}}
    <div class="mb-6">
        <a href="{{ route('profile') }}"
            class="text-xs font-semibold text-slate-400 hover:text-brand-600 flex items-center gap-1 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Profil
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-800">Manajemen Nama Pena</h1>
                <p class="text-xs text-slate-400 mt-0.5">Kelola seluruh nama pena dan persona karya kamu di sini.</p>
            </div>

            {{-- Tombol Buka Modal --}}
            <button wire:click="openCreateModal"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>

            </button>
        </div>
    </div>

    {{-- Daftar Nama Pena --}}
    @if ($penNames->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-slate-200 shadow-sm">
            <div
                class="w-16 h-16 bg-brand-50 text-brand-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Nama Pena</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">Kamu belum memiliki nama pena. Buat nama pena
                pertamamu untuk mulai mempublikasikan cerita.</p>
            <button wire:click="openCreateModal"
                class="px-4 py-2 bg-brand-600 text-white rounded-xl text-xs font-semibold hover:bg-brand-700 transition">
                + Buat Nama Pena
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($penNames as $penName)
                <div
                    class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:border-slate-200 transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-full bg-brand-100 overflow-hidden flex-shrink-0 flex items-center justify-center font-bold text-brand-600">
                                @if ($penName->avatar)
                                    <img src="{{ asset('storage/' . $penName->avatar) }}" alt="{{ $penName->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($penName->name, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 leading-tight">{{ $penName->name }}</h3>
                                <span class="text-[11px] text-slate-400">@ {{ $penName->slug }}</span>
                            </div>
                        </div>

                        @if ($penName->bio)
                            <p class="text-xs text-slate-500 line-clamp-2 mt-3 leading-relaxed">
                                {{ $penName->bio }}
                            </p>
                        @endif
                    </div>

                    <div>
                        {{-- Metrik Ringkas --}}
                        <div
                            class="flex items-center gap-4 text-[11px] text-slate-500 my-4 pt-3 border-t border-slate-100 font-medium">
                            <span>{{ number_format($penName->stories_count) }} Cerita</span>
                            <span>•</span>
                            <span>{{ number_format($penName->followers_count) }} Pengikut</span>
                        </div>

                        {{-- Akses Aksi --}}
                        <div class="flex items-center justify-between text-xs">
                            <a href="{{ route('pen-name.show', $penName->slug) }}" target="_blank"
                                class="text-slate-400 hover:text-slate-600 font-medium inline-flex items-center gap-1">
                                Lihat Publik
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>

                            <div class="flex items-center gap-2">
                                <button wire:click="openEditModal({{ $penName->id }})"
                                    class="px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl font-medium hover:bg-slate-100 transition">
                                    Edit
                                </button>
                                {{-- <a href="{{ route('writer.dashboard', ['pen_name_id' => $penName->id]) }}"
                                    class="px-3 py-1.5 bg-brand-50 text-brand-600 font-semibold rounded-xl hover:bg-brand-100 transition">
                                    Studio Karya
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- MODAL FORM BUAT NAMA PENA --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-xl transform transition-all">
                {{-- Header Modal --}}
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-800">Buat Nama Pena Baru</h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body Form --}}
                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    {{-- Upload Avatar --}}
                    <div class="flex flex-col items-center justify-center pb-2">
                        <div
                            class="relative w-20 h-20 rounded-full bg-slate-100 overflow-hidden flex items-center justify-center border border-slate-200 mb-2 group">
                            @if ($avatar)
                                <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            @endif
                            <label
                                class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center cursor-pointer transition text-white text-[9px] font-semibold">
                                Ubah Foto
                                <input type="file" wire:model="avatar" accept="image/*" class="hidden">
                            </label>
                        </div>
                        <label class="text-xs font-semibold text-brand-600 hover:underline cursor-pointer">
                            <span>Upload Avatar</span>
                            <input type="file" wire:model="avatar" accept="image/*" class="hidden">
                        </label>
                        @error('avatar')
                            <span class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Nama Pena --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pena <span
                                class="text-red-500">*</span></label>
                        <input wire:model.live.debounce.400ms="name" type="text"
                            placeholder="Contoh: Raden Wijaya, Tere Liye, dll."
                            class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                        @if ($name)
                            <p class="text-[10px] text-slate-400 mt-1">Slug URL: <span
                                    class="font-mono text-slate-600">@
                                    {{ \Illuminate\Support\Str::slug($name) }}</span></p>
                        @endif
                        @error('name')
                            <span class="text-xs text-red-500 mt-1 font-medium block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Bio Penulis (Opsional)</label>
                        <textarea wire:model="bio" rows="3" placeholder="Ceritakan sedikit gaya tulisanmu atau kutipan favorit..."
                            class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition resize-none"></textarea>
                        @error('bio')
                            <span class="text-xs text-red-500 mt-1 font-medium block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Footer Modal & Action Buttons --}}
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl text-xs font-semibold transition">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-brand-600 hover:bg-brand-700 disabled:opacity-50 text-white rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5">
                            <span wire:loading.remove>Simpan Nama Pena</span>
                            <span wire:loading class="flex items-center gap-1">
                                <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
