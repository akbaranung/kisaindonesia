<div class="relative min-h-screen bg-slate-50/50 dark:bg-slate-950 p-4 transition-colors duration-200">
    @if ($action === 'view')
        <nav class="absolute top-0 left-0 right-0 z-10 flex items-center justify-between p-6">
            <a href="{{ url('/') }}" wire:navigate
                class="p-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-xl shadow-sm text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <h2 class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-widest">My Profile</h2>
            <button onclick="toggleTheme()" type="button"
                class="p-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-xl shadow-sm text-slate-600 dark:text-slate-300 hover:text-amber-500 transition">
                <i class="fa-solid fa-moon hidden dark:block text-amber-300"></i>
                <i class="fa-solid fa-sun block dark:hidden text-amber-500"></i>
            </button>
        </nav>

        <header
            class="relative pt-24 pb-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl transition-colors shadow-2xs">
            <div
                class="absolute top-0 left-0 right-0 h-40 bg-linear-to-br from-brand-500 to-teal-600 opacity-10 rounded-t-3xl">
            </div>

            <div class="relative flex flex-col items-center">
                <div class="relative">
                    <div
                        class="w-28 h-28 rounded-[2rem] overflow-hidden border-4 border-white dark:border-slate-800 shadow-xl bg-white dark:bg-slate-900">
                        @if ($avatar_temp)
                            <img src="{{ $avatar_temp->temporaryUrl() }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ auth()->user()->profile_photo_url }}" class="w-full h-full object-cover">
                        @endif

                        <div wire:loading wire:target="avatar_temp"
                            class="absolute inset-0 bg-slate-950/80 rounded-full flex items-center justify-center">
                            <svg class="animate-spin h-6 w-6 text-brand-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight">
                        {{ $name }}</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mb-4">{{ $email }}</p>
                    @if ($bio)
                        <p
                            class="mt-3 text-xs text-slate-500 dark:text-slate-400 max-w-[250px] leading-relaxed font-medium mx-auto">
                            "{{ $bio }}"
                        </p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-8 max-w-sm mx-auto px-4">
                <div
                    class="flex flex-col items-center p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 transition hover:bg-white dark:hover:bg-slate-800 hover:shadow-2xs">
                    <span
                        class="text-lg font-black text-slate-800 dark:text-slate-100">{{ number_format($followersCount, 0, ',', '.') }}</span>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Followers</span>
                </div>
                <div
                    class="flex flex-col items-center p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 transition hover:bg-white dark:hover:bg-slate-800 hover:shadow-2xs">
                    <span
                        class="text-lg font-black text-slate-800 dark:text-slate-100">{{ number_format($followingCount, 0, ',', '.') }}</span>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Following</span>
                </div>
                <a href="{{ route('library') }}"
                    class="flex flex-col items-center p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 transition hover:bg-white dark:hover:bg-slate-800 hover:shadow-2xs">
                    <span
                        class="text-lg font-black text-brand-600 dark:text-brand-400">{{ number_format($user->savedStories->where('status', 'published')->count(), 0, ',', '.') }}</span>
                    <span
                        class="text-[9px] font-black text-brand-600 dark:text-brand-400 uppercase tracking-tighter">Library</span>
                </a>
            </div>
        </header>


        <main class="pb-24 my-3 space-y-4">
            @if (session()->has('success'))
                <div
                    class="p-4 bg-brand-500 text-white text-xs font-bold rounded-2xl shadow-lg shadow-brand-200 dark:shadow-none flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div
                class="relative overflow-hidden bg-gradient-to-br from-amber-500 via-amber-600 to-amber-700 rounded-2xl p-4 text-white shadow-md shadow-amber-500/20">
                <div class="absolute -right-4 -bottom-4 opacity-10 text-8xl font-black select-none pointer-events-none">
                    🫘
                </div>

                <div class="relative z-10 space-y-3">
                    <div class="flex items-center justify-between">
                        <div
                            class="flex items-center gap-1.5 bg-amber-900/30 backdrop-blur-md border border-amber-300/30 px-2.5 py-1 rounded-full">
                            <span class="text-xs">🫘</span>
                            <span class="text-[10px] font-bold text-amber-100 tracking-wide uppercase">Saldo Kisa
                                Bean</span>
                        </div>

                        <a href="{{ route('kisa-bean.history') }}"
                            class="text-[10px] font-medium text-amber-100/80 hover:text-white flex items-center gap-1 transition-colors">
                            Riwayat
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <div class="flex items-baseline justify-between pt-1">
                        <div>
                            <div class="text-2xl font-black tracking-tight leading-none">
                                {{ number_format($user->kisa_bean_balance ?? 0) }}
                            </div>
                            <p class="text-[10px] text-amber-100/70 font-medium mt-1">Gunakan untuk membaca bab premium
                            </p>
                        </div>

                        <a href="/topup"
                            class="bg-white text-amber-900 font-extrabold text-xs px-3.5 py-2.5 rounded-xl shadow-sm hover:bg-amber-50 active:scale-95 transition-all flex items-center gap-1.5 shrink-0">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Top Up
                        </a>
                    </div>
                </div>
            </div>

            <div
                class="relative overflow-hidden bg-gradient-to-br from-brand-600 to-brand-700 rounded-2xl p-4 text-white shadow-md shadow-brand-600/20 flex flex-col justify-between min-h-[110px]">
                <div class="absolute -right-4 -bottom-4 opacity-10 text-6xl font-black select-none pointer-events-none">
                    💰</div>

                <div class="relative z-10 flex items-center justify-between mb-2">
                    <span class="text-[9px] font-extrabold tracking-wider uppercase text-brand-100">Royalti
                        Penulis</span>
                    <span class="px-1.5 py-0.5 bg-brand-900/40 text-[8px] font-bold rounded">Aktif</span>
                </div>

                <div class="flex justify-between z-10 items-end mt-auto">
                    <div class="text-xl font-black leading-none">
                        {{ number_format($user->earned_beans ?? 0) }}
                        <p class="text-[10px] text-brand-100/80 font-medium mt-1">Pendapatan royalti dari cerita
                            premium
                        </p>
                    </div>
                    <a href="{{ route('withdrawals.index') }}"
                        class="inline-flex items-center gap-1 bg-white text-brand-900 text-[10px] font-extrabold px-3 py-2 rounded-xl shadow-xs hover:bg-brand-50 active:scale-95 transition-all">
                        <svg class="w-3 h-3 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Cairkan
                    </a>
                </div>
            </div>

            @if (count($recentTransactions) > 0)
                <div
                    class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Aktivitas Terakhir</span>
                        <a href="{{ route('kisa-bean.history') }}"
                            class="text-[10px] font-bold text-amber-600 dark:text-amber-400">Lihat
                            Semua</a>
                    </div>
                    <div class="space-y-2">
                        @foreach ($recentTransactions as $tx)
                            @php
                                $isPositive = in_array($tx->type, ['topup', 'earn']);
                                $icon = match ($tx->type) {
                                    'topup' => '📥',
                                    'spend' => '📖',
                                    'earn' => '💰',
                                    'payout' => '🏦',
                                    default => '🫘',
                                };

                                $statusClass = match ($tx->status) {
                                    'success'
                                        => 'bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border-brand-200 dark:border-brand-900',
                                    'pending'
                                        => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900 animate-pulse',
                                    'failed',
                                    'expired'
                                        => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900',
                                    default
                                        => 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700',
                                };
                            @endphp
                            <div
                                class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div
                                        class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 border {{ $statusClass }}">
                                        {{ $icon }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-bold text-slate-800 dark:text-slate-200 truncate">
                                            {{ $tx->description ?? ($tx->type === 'topup' ? 'Top Up Kisa Bean' : 'Pembelian Bab') }}
                                        </p>
                                        <p class="text-[9px] text-slate-400 dark:text-slate-500">
                                            {{ $tx->created_at->format('d M, H:i') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <span
                                        class="font-black text-xs {{ $isPositive ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-300' }}">
                                        {{ $isPositive ? '+' : '-' }}{{ number_format($tx->amount) }}
                                    </span>
                                    @if ($tx->status === 'pending')
                                        <button wire:click="reopenPayment({{ $tx->id }})"
                                            class="bg-brand-600 hover:bg-brand-700 text-white text-[10px] px-2 py-1 rounded transition">
                                            Bayar
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <section class="mt-4 flex flex-col gap-3">
                <h4
                    class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] ml-2 mb-1">
                    General Settings
                </h4>

                <!-- Mode Gelap Toggle Item -->
                <div onclick="toggleTheme()"
                    class="group w-full flex items-center justify-between p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-2xs hover:border-brand-200 dark:hover:border-brand-600 transition cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 group-hover:bg-brand-50 dark:group-hover:bg-brand-950/40 text-slate-400 dark:text-amber-400 group-hover:text-brand-600 flex items-center justify-center transition">
                            <i id="theme-icon-label" class="fa-solid fa-moon text-slate-400 dark:text-amber-400"></i>
                        </div>
                        <div class="text-left">
                            <span class="block text-sm font-bold text-slate-700 dark:text-slate-200">Mode Gelap (Dark
                                Mode)</span>
                            <span class="block text-[10px] text-slate-400 dark:text-slate-500">Aktifkan tampilan tema
                                gelap</span>
                        </div>
                    </div>
                    <button type="button"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-slate-200 dark:bg-[#38CAC8]"
                        role="switch" aria-checked="false" id="theme-switch-btn">
                        <span id="theme-switch-thumb"
                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out translate-x-0 dark:translate-x-5"></span>
                    </button>
                </div>

                <a href="{{ route('pen-names.index') }}"
                    class="group w-full flex items-center justify-between p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-2xs hover:border-brand-200 dark:hover:border-brand-600 transition">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 group-hover:bg-brand-50 dark:group-hover:bg-brand-950/40 text-slate-400 group-hover:text-brand-600 flex items-center justify-center transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-sm font-bold text-slate-700 dark:text-slate-200">Manajemen Nama
                                Pena</span>
                            <span class="block text-[10px] text-slate-400 dark:text-slate-500">Kelola persona
                                kepenulisan, foto profil, dan
                                bio kamu</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor"
                        stroke-width="3" viewBox="0 0 24 24">
                        <path d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>

                <button wire:click="switchAction('edit')"
                    class="group w-full flex items-center justify-between p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-2xs hover:border-brand-200 dark:hover:border-brand-600 transition">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 group-hover:bg-brand-50 dark:group-hover:bg-brand-950/40 text-slate-400 group-hover:text-brand-600 flex items-center justify-center transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-sm font-bold text-slate-700 dark:text-slate-200">Personal
                                Information</span>
                            <span class="block text-[10px] text-slate-400 dark:text-slate-500">Nama, Email, Phone &
                                Bio</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor"
                        stroke-width="3" viewBox="0 0 24 24">
                        <path d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                <button wire:click="switchAction('change-password')"
                    class="group w-full flex items-center justify-between p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-2xs hover:border-brand-200 dark:hover:border-brand-600 transition">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 group-hover:bg-brand-50 dark:group-hover:bg-brand-950/40 text-slate-400 group-hover:text-brand-600 flex items-center justify-center transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-sm font-bold text-slate-700 dark:text-slate-200">Security &
                                Privacy</span>
                            <span class="block text-[10px] text-slate-400 dark:text-slate-500">Ubah Password &
                                Keamanan</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor"
                        stroke-width="3" viewBox="0 0 24 24">
                        <path d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                <a href="{{ route('support') }}"
                    class="group w-full flex items-center justify-between p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-2xs hover:border-brand-200 dark:hover:border-brand-600 transition">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 group-hover:bg-brand-50 dark:group-hover:bg-brand-950/40 text-slate-400 group-hover:text-brand-600 flex items-center justify-center transition">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200">Pusat Bantuan &
                            Support</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ route('logout') }}" wire:navigate
                    class="mt-4 group w-full flex items-center justify-center gap-2 p-4 border-2 border-rose-100 dark:border-rose-900/40 border-dashed rounded-2xl hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>

                    <span class="text-sm font-black text-rose-500 uppercase tracking-widest">Sign Out</span>
                </a>
            </section>
        </main>
    @elseif($action === 'edit')
        <nav
            class="flex p-3 items-center justify-between border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-t-2xl">
            <button wire:click="switchAction('view')"
                class="p-2 bg-slate-100 dark:bg-slate-800 backdrop-blur-md rounded-xl shadow-sm text-slate-600 dark:text-slate-300 hover:text-brand-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>
            <h2 class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-widest">Edit Details
            </h2>
            <div class="w-10"></div>
        </nav>

        <main class="pb-24 mt-3">
            <div
                class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-6 shadow-2xl dark:shadow-none">
                @include('livewire.profile.form-edit')
            </div>
        </main>
    @else
        <nav
            class="flex p-3 items-center justify-between border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-t-2xl">
            <button wire:click="switchAction('view')"
                class="p-2 bg-slate-100 dark:bg-slate-800 backdrop-blur-md rounded-xl shadow-sm text-slate-600 dark:text-slate-300 hover:text-brand-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>
            <h2 class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-widest">Change Password
            </h2>
            <div class="w-10"></div>
        </nav>

        <main class="mt-3 pb-24">
            <div
                class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-6 shadow-2xl dark:shadow-none">
                @include('livewire.profile.change-password')
            </div>
        </main>
    @endif
</div>
