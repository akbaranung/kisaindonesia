<div class="inline-block my-3">
    @if (Auth::check() && Auth::id() === $authorId)
        {{-- Tampilan khusus jika ini adalah profil/karya milik user sendiri --}}
        <span
            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
            Pemilik Cerita
        </span>
    @else
        @if ($variant === 'compact')
            {{-- Varian Kecil (Cocok untuk Header Reader / List Penulis) --}}
            <button wire:click="toggleFollow" wire:loading.attr="disabled"
                class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-medium rounded-md transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed
                {{ $isFollowing
                    ? 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 active:bg-slate-100'
                    : 'bg-brand-600 text-white hover:bg-brand-700 active:bg-brand-800 shadow-sm' }}">

                <span wire:loading.remove wire:target="toggleFollow">
                    {{ $isFollowing ? 'Mengikuti' : '+ Ikuti' }}
                </span>

                <span wire:loading wire:target="toggleFollow" class="inline-flex items-center">
                    <svg class="animate-spin h-3 w-3 text-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
            </button>
        @elseif($variant === 'icon')
            {{-- Varian Hanya Ikon --}}
            <button wire:click="toggleFollow" wire:loading.attr="disabled"
                title="{{ $isFollowing ? 'Batal Ikuti' : 'Ikuti Penulis' }}"
                class="inline-flex items-center justify-center w-8 h-8 rounded-full transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed
                {{ $isFollowing
                    ? 'bg-slate-800 text-white hover:bg-slate-900'
                    : 'border border-indigo-600 text-indigo-600 hover:bg-indigo-50' }}">

                <span wire:loading.remove wire:target="toggleFollow">
                    @if ($isFollowing)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    @endif
                </span>

                <span wire:loading wire:target="toggleFollow" class="inline-flex items-center">
                    <svg class="animate-spin h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
            </button>
        @else
            {{-- Varian Default / Utuh (Cocok untuk Halaman Profil & Detail Cerita) --}}
            <button wire:click="toggleFollow" wire:loading.attr="disabled"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-150 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed
                {{ $isFollowing
                    ? 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 active:bg-slate-100'
                    : 'bg-indigo-600 text-white hover:bg-indigo-700 active:bg-indigo-800' }}">

                <span wire:loading.remove wire:target="toggleFollow" class="flex items-center gap-2">
                    @if ($isFollowing)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Mengikuti</span>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Ikuti Penulis</span>
                    @endif
                </span>

                <span wire:loading wire:target="toggleFollow" class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span>Memproses...</span>
                </span>
            </button>
        @endif
    @endif
</div>
