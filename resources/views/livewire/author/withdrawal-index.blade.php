<div class="bg-slate-950 text-slate-100 min-h-screen pt-4 pb-20">
    <div class="max-w-6xl mx-auto space-y-8">

        <!-- Header Title Banner -->
        <div
            class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-slate-900 to-amber-950/40 border border-slate-800 p-6 md:p-8 shadow-2xl">
            <!-- Decorative Light Effects -->
            <div class="absolute -top-24 -right-24 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute -bottom-24 -left-24 w-72 h-72 bg-brand-500/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-semibold">
                        <i class="fa-solid fa-coins text-xs"></i>
                        <span>Pencairan Royalti Penulis</span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black text-slate-100 tracking-tight">
                        Penarikan Saldo Kisa
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-400 max-w-xl leading-relaxed">
                        Tukarkan koin Kisa hasil karya cerita Anda menjadi uang tunai langsung ke rekening bank atau
                        e-wallet pilihan Anda secara aman & transparan.
                    </p>
                </div>

                <!-- Rate Tag & Quick Info -->
                <div
                    class="flex items-center gap-3 bg-slate-950/60 backdrop-blur-md border border-slate-800 rounded-2xl p-4 self-start md:self-auto shadow-inner">
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shrink-0">
                        <i class="fa-solid fa-chart-line text-lg"></i>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold block">Rate Konversi
                            Saat Ini</span>
                        <div class="text-sm font-bold text-slate-100">
                            1 Kisa = <span class="text-emerald-400">Rp {{ number_format($rate, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Saldo Utama -->
            <div
                class="relative overflow-hidden bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-xl hover:border-slate-700/80 transition">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-xs font-medium text-slate-400">Saldo Kisa Anda</span>
                    <div
                        class="w-8 h-8 rounded-xl bg-amber-400/10 text-amber-400 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-wheat-awn"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-amber-400 tracking-tight">
                    {{ number_format(auth()->user()->earned_beans ?? 0) }} <span
                        class="text-xs font-semibold text-slate-400">Kisa</span>
                </div>
                <div class="text-[11px] text-slate-400 mt-1">
                    &approx; Rp {{ number_format((auth()->user()->earned_beans ?? 0) * $rate, 0, ',', '.') }}
                </div>
            </div>

            <!-- Total Berhasil Ditarik -->
            <div
                class="relative overflow-hidden bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-xl hover:border-slate-700/80 transition">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-xs font-medium text-slate-400">Total Berhasil Ditarik</span>
                    <div
                        class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-emerald-400 tracking-tight">
                    Rp {{ number_format($this->totalWithdrawn, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-[10px]"></i>
                    <span>{{ $this->successCount }} Transaksi Berhasil</span>
                </div>
            </div>

            <!-- Pengajuan Menunggu -->
            <div
                class="relative overflow-hidden bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-xl hover:border-slate-700/80 transition">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-xs font-medium text-slate-400">Pengajuan Pending</span>
                    <div
                        class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-amber-400 tracking-tight">
                    {{ $this->pendingCount }} <span class="text-xs font-semibold text-slate-400">Pengajuan</span>
                </div>
                <div class="text-[11px] text-slate-400 mt-1">
                    {{ $this->pendingCount > 0 ? 'Sedang diverifikasi admin' : 'Tidak ada antrean' }}
                </div>
            </div>

            <!-- Ketentuan Biaya & Min -->
            <div
                class="relative overflow-hidden bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-xl hover:border-slate-700/80 transition">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-xs font-medium text-slate-400">Biaya Admin & Min WD</span>
                    <div
                        class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>
                <div class="text-lg font-bold text-slate-200">
                    Rp {{ number_format($admin_fee, 0, ',', '.') }} <span class="text-xs text-slate-400 font-normal">/
                        transaksi</span>
                </div>
                <div class="text-[11px] text-slate-400 mt-1">
                    Minimal Penarikan: <strong class="text-amber-400 font-semibold">{{ $min_kisa }} Kisa</strong>
                </div>
            </div>
        </div>

        <!-- Content Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left Side: Withdrawal Form (7 Cols) -->
            <div class="lg:col-span-7 bg-slate-900 border border-slate-800 rounded-3xl p-2 sm:p-8 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center">
                            <i class="fa-solid fa-hand-holding-dollar text-base"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-100">Form Pengajuan Pencairan</h2>
                            <p class="text-xs text-slate-400">Lengkapi data penerima dan jumlah penarikan.</p>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="submitWithdrawal" class="space-y-6">

                    <!-- Mode Select: Saved Bank vs Manual -->
                    @if ($has_saved_bank)
                        <div
                            class="p-3 bg-slate-950 border border-slate-800 rounded-2xl flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-7 h-7 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-building-columns"></i>
                                </div>
                                <span class="text-xs font-medium text-slate-300">Rekening Dari Pendaftaran
                                    Premium</span>
                            </div>
                            <button type="button" wire:click="toggleManualBank"
                                class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-xs font-semibold text-amber-400 transition flex items-center gap-1.5">
                                <i
                                    class="fa-solid {{ $is_manual_bank ? 'fa-rotate-left' : 'fa-pen-to-square' }} text-[10px]"></i>
                                <span>{{ $is_manual_bank ? 'Gunakan Bawaan' : 'Ganti Rekening' }}</span>
                            </button>
                        </div>
                    @endif

                    @if (!$is_manual_bank && $has_saved_bank)
                        <!-- Tampilan Rekening Bawaan -->
                        <div
                            class="bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 border border-amber-500/30 rounded-2xl p-4 space-y-2 relative overflow-hidden">
                            <div
                                class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-full blur-xl pointer-events-none">
                            </div>
                            <div
                                class="flex items-center justify-between text-[11px] text-amber-400 font-semibold uppercase tracking-wider">
                                <span><i class="fa-solid fa-shield-check me-1"></i> Rekening Bawaan Terverifikasi</span>
                                <span
                                    class="px-2 py-0.5 rounded-md bg-amber-400/10 border border-amber-400/20 text-[10px]">Aktif</span>
                            </div>
                            <div class="text-sm font-bold text-slate-100 flex items-center gap-2">
                                <span
                                    class="px-2 py-0.5 rounded bg-slate-800 text-amber-400 text-xs font-mono uppercase">{{ $bank_name }}</span>
                                <span>{{ $account_number }}</span>
                            </div>
                            <div class="text-xs text-slate-400 flex items-center gap-1">
                                <i class="fa-solid fa-user-circle text-slate-500"></i>
                                <span>Atas Nama: <strong class="text-slate-200">{{ $account_name }}</strong></span>
                            </div>
                        </div>
                    @else
                        <!-- Form Manual Rekening Bank / E-Wallet -->
                        <div class="space-y-4 bg-slate-950/60 p-4 sm:p-5 rounded-2xl border border-slate-800">
                            <div class="flex items-center justify-between">
                                <label
                                    class="text-xs font-bold text-amber-400 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fa-solid fa-building-columns"></i>
                                    Detail Rekening Penerima
                                </label>
                                <span class="text-[10px] text-slate-500">Bank / E-Wallet Indonesia</span>
                            </div>

                            <!-- Preset Quick Bank Pickers -->
                            <div>
                                <span class="block text-[11px] text-slate-400 mb-2">Pilih Cepat Bank / E-Wallet:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach (['BCA', 'Mandiri', 'BRI', 'BNI', 'DANA', 'OVO', 'GoPay', 'ShopeePay'] as $preset)
                                        <button type="button" wire:click="selectBankPreset('{{ $preset }}')"
                                            class="px-2.5 py-1 rounded-lg text-xs font-medium border transition-all duration-150 {{ strtolower($bank_name) === strtolower($preset) ? 'bg-amber-500/20 border-amber-500 text-amber-300 shadow-sm' : 'bg-slate-800/80 border-slate-700/70 text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                                            {{ $preset }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Input Bank Name -->
                            <div>
                                <label class="block text-xs font-medium text-slate-300 mb-1">
                                    Nama Bank / E-Wallet <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                                        <i class="fa-solid fa-landmark text-xs"></i>
                                    </span>
                                    <input type="text" wire:model="bank_name"
                                        placeholder="Contoh: BCA, Mandiri, DANA, OVO"
                                        class="w-full pl-9 pr-3 py-2.5 bg-slate-900 border border-slate-700/80 rounded-xl text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                                </div>
                                @error('bank_name')
                                    <span class="text-rose-400 text-[11px] mt-1 flex items-center gap-1"><i
                                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Input Account Number -->
                            <div>
                                <label class="block text-xs font-medium text-slate-300 mb-1">
                                    Nomor Rekening / No. HP E-Wallet <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                                        <i class="fa-solid fa-credit-card text-xs"></i>
                                    </span>
                                    <input type="text" wire:model="account_number"
                                        placeholder="Contoh: 1234567890 atau 08123456789"
                                        class="w-full pl-9 pr-3 py-2.5 bg-slate-900 border border-slate-700/80 rounded-xl text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                                </div>
                                @error('account_number')
                                    <span class="text-rose-400 text-[11px] mt-1 flex items-center gap-1"><i
                                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Input Account Holder Name -->
                            <div>
                                <label class="block text-xs font-medium text-slate-300 mb-1">
                                    Nama Pemilik Rekening <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                                        <i class="fa-solid fa-user text-xs"></i>
                                    </span>
                                    <input type="text" wire:model="account_name"
                                        placeholder="Nama lengkap sesuai pada buku tabungan / e-wallet"
                                        class="w-full pl-9 pr-3 py-2.5 bg-slate-900 border border-slate-700/80 rounded-xl text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                                </div>
                                @error('account_name')
                                    <span class="text-rose-400 text-[11px] mt-1 flex items-center gap-1"><i
                                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Amount Selection Field -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <label class="block text-xs font-bold text-slate-200">
                                Jumlah Kisa yang Ditarik <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400">Minimal: <strong
                                    class="text-amber-400">{{ $min_kisa }} Kisa</strong></span>
                        </div>

                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-400 font-bold text-xs">
                                <i class="fa-solid fa-coins me-1"></i>
                            </span>
                            <input type="number" wire:model.live="kisa_amount" min="{{ $min_kisa }}"
                                max="{{ auth()->user()->earned_beans ?? 0 }}" placeholder="Masukkan jumlah kisa..."
                                class="w-full pl-12 pr-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-sm font-semibold text-slate-100 placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                        </div>

                        <!-- Quick Amount Preset Buttons -->
                        <div class="flex items-center gap-2 pt-1">
                            <span class="text-[11px] text-slate-400 font-medium">Pilihan Cepat:</span>
                            <button type="button" wire:click="setKisaPreset('min')"
                                class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 border border-slate-700/60 rounded-lg text-xs text-slate-300 transition">
                                Min ({{ $min_kisa }})
                            </button>
                            <button type="button" wire:click="setKisaPreset('half')"
                                class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 border border-slate-700/60 rounded-lg text-xs text-slate-300 transition">
                                50% Saldo
                            </button>
                            <button type="button" wire:click="setKisaPreset('max')"
                                class="px-2.5 py-1 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 rounded-lg text-xs font-semibold text-amber-400 transition">
                                Maksimal (100%)
                            </button>
                        </div>

                        @error('kisa_amount')
                            <span class="text-rose-400 text-[11px] mt-1 flex items-center gap-1"><i
                                    class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Rincian Kalkulasi Card -->
                    <div
                        class="bg-gradient-to-b from-slate-950 to-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 space-y-3">
                        <div
                            class="text-xs font-bold text-slate-300 flex items-center justify-between border-b border-slate-800/80 pb-2">
                            <span><i class="fa-solid fa-calculator me-1.5 text-amber-400"></i> Rincian Kalkulasi
                                Pencairan</span>
                            <span class="text-[10px] text-slate-500">Realtime</span>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between text-slate-400">
                                <span>Kurs Konversi (1 Kisa)</span>
                                <span class="text-slate-200 font-medium">Rp
                                    {{ number_format($rate, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Jumlah Kotor (Gross)</span>
                                <span class="text-slate-200 font-medium">Rp
                                    {{ number_format($this->grossAmount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Biaya Penanganan Admin</span>
                                <span class="text-rose-400 font-medium">- Rp
                                    {{ number_format($admin_fee, 0, ',', '.') }}</span>
                            </div>

                            <!-- Net Amount Highlight -->
                            <div class="pt-2 border-t border-slate-800">
                                <div
                                    class="bg-emerald-950/40 border border-emerald-500/30 rounded-xl p-3.5 flex items-center justify-between shadow-inner">
                                    <div>
                                        <span
                                            class="text-[11px] font-bold uppercase tracking-wider text-emerald-400 block">Total
                                            Diterima (Net)</span>
                                        <span class="text-[10px] text-slate-400">Masuk ke rekening tujuan</span>
                                    </div>
                                    <div class="text-lg font-black text-emerald-400">
                                        Rp {{ number_format($this->netAmount, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full py-3.5 bg-gradient-to-r from-amber-500 via-amber-400 to-amber-500 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs sm:text-sm uppercase tracking-wider rounded-xl shadow-lg shadow-amber-500/10 hover:shadow-amber-500/20 transition-all duration-200 flex items-center justify-center gap-2">
                        <span wire:loading.remove class="flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            Kirim Pengajuan Pencairan
                        </span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-slate-950" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Memproses Pengajuan...
                        </span>
                    </button>
                </form>
            </div>

            <!-- Right Side: Workflow Steps & Guidelines (5 Cols) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- Alur Penarikan Card -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-800 pb-4">
                        <div
                            class="w-9 h-9 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center">
                            <i class="fa-solid fa-route text-base"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-100">Alur Proses Penarikan</h2>
                            <p class="text-xs text-slate-400">3 langkah mudah pencairan royalti.</p>
                        </div>
                    </div>

                    <!-- Step Timeline -->
                    <div class="space-y-4 text-xs">
                        <div class="flex items-start gap-3.5 relative">
                            <div
                                class="w-7 h-7 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center text-xs font-bold shrink-0">
                                1
                            </div>
                            <div class="pt-0.5 space-y-0.5">
                                <h3 class="font-bold text-slate-200">Kirim Pengajuan</h3>
                                <p class="text-slate-400 leading-relaxed text-[11px]">Pilih nominal Kisa & isi rekening
                                    tujuan dengan teliti.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5 relative">
                            <div
                                class="w-7 h-7 rounded-full bg-blue-500/20 border border-blue-500/40 text-blue-400 flex items-center justify-center text-xs font-bold shrink-0">
                                2
                            </div>
                            <div class="pt-0.5 space-y-0.5">
                                <h3 class="font-bold text-slate-200">Verifikasi Admin</h3>
                                <p class="text-slate-400 leading-relaxed text-[11px]">Tim admin akan memeriksa
                                    kelayakan & keabsahan transaksi.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5 relative">
                            <div
                                class="w-7 h-7 rounded-full bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center text-xs font-bold shrink-0">
                                3
                            </div>
                            <div class="pt-0.5 space-y-0.5">
                                <h3 class="font-bold text-slate-200">Transfer Berhasil</h3>
                                <p class="text-slate-400 leading-relaxed text-[11px]">Dana masuk ke rekening & bukti
                                    transfer diunggah oleh admin.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Syarat & Ketentuan Card -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
                    <div class="flex items-center gap-3 border-b border-slate-800 pb-3">
                        <div
                            class="w-8 h-8 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-file-contract"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-100">Syarat & Ketentuan</h2>
                    </div>

                    <ul class="space-y-2.5 text-xs text-slate-300 leading-relaxed">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-amber-400 text-xs mt-0.5 shrink-0"></i>
                            <span>Minimal penarikan saldo adalah <strong class="text-slate-100">{{ $min_kisa }}
                                    Kisa</strong>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-amber-400 text-xs mt-0.5 shrink-0"></i>
                            <span>Nilai kurs konversi saat ini: <strong class="text-slate-100">1 Kisa = Rp
                                    {{ number_format($rate, 0, ',', '.') }}</strong>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-amber-400 text-xs mt-0.5 shrink-0"></i>
                            <span>Setiap penarikan dikenakan biaya admin <strong class="text-slate-100">Rp
                                    {{ number_format($admin_fee, 0, ',', '.') }}</strong>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-amber-400 text-xs mt-0.5 shrink-0"></i>
                            <span>Jika pengajuan ditolak, saldo Kisa akan dikembalikan secara otomatis 100%.</span>
                        </li>
                    </ul>
                </div>

                <!-- Assistance Card -->
                <div
                    class="bg-gradient-to-r from-slate-900 via-slate-900 to-amber-950/20 border border-slate-800 rounded-3xl p-5 flex items-center gap-4">
                    <div
                        class="w-10 h-10 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-headset text-lg"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h4 class="text-xs font-bold text-slate-200">Butuh Bantuan Kendala Penarikan?</h4>
                        <p class="text-[11px] text-slate-400">Hubungi tim customer support Kisa Indonesia melalui
                            halaman bantuan.</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- History Section: Riwayat Penarikan Saldo -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6"
            x-data="{ openProofModal: false, selectedProof: '', openReasonModal: false, selectedReason: '' }">

            <div
                class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-800 pb-5">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                        <i class="fa-solid fa-clock-rotate-left text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-100">Riwayat Penarikan Saldo</h2>
                        <p class="text-xs text-slate-400">Daftar semua transaksi penarikan saldo Kisa Anda.</p>
                    </div>
                </div>

                <!-- Status Filter Pills -->
                <div class="flex items-center gap-1.5 bg-slate-950 p-1 rounded-xl border border-slate-800 text-xs">
                    <button type="button" wire:click="$set('filterStatus', '')"
                        class="px-3 py-1 rounded-lg font-medium transition {{ $filterStatus === '' ? 'bg-slate-800 text-slate-100 font-bold shadow-sm' : 'text-slate-400 hover:text-slate-200' }}">
                        Semua
                    </button>
                    <button type="button" wire:click="$set('filterStatus', 'pending')"
                        class="px-3 py-1 rounded-lg font-medium transition {{ $filterStatus === 'pending' ? 'bg-amber-500/20 text-amber-300 font-bold shadow-sm' : 'text-slate-400 hover:text-slate-200' }}">
                        Menunggu
                    </button>
                    <button type="button" wire:click="$set('filterStatus', 'approved')"
                        class="px-3 py-1 rounded-lg font-medium transition {{ $filterStatus === 'approved' ? 'bg-emerald-500/20 text-emerald-300 font-bold shadow-sm' : 'text-slate-400 hover:text-slate-200' }}">
                        Berhasil
                    </button>
                    <button type="button" wire:click="$set('filterStatus', 'rejected')"
                        class="px-3 py-1 rounded-lg font-medium transition {{ $filterStatus === 'rejected' ? 'bg-rose-500/20 text-rose-300 font-bold shadow-sm' : 'text-slate-400 hover:text-slate-200' }}">
                        Ditolak
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-slate-800/80">
                <table class="w-full text-xs text-left text-slate-300">
                    <thead
                        class="bg-slate-950/80 text-slate-400 uppercase text-[10px] tracking-wider font-semibold border-b border-slate-800">
                        <tr>
                            <th class="p-4">No. Ref & Tanggal</th>
                            <th class="p-4">Jumlah Kisa</th>
                            <th class="p-4">Total Diterima (Net)</th>
                            <th class="p-4">Rekening Tujuan</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Detail / Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 bg-slate-900/40">
                        @forelse($withdrawals as $item)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="p-4">
                                    <span
                                        class="font-bold font-mono text-slate-100 block">{{ $item->reference_no ?? 'WD-' . $item->id }}</span>
                                    <span class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                        <i class="fa-regular fa-clock text-[10px]"></i>
                                        {{ $item->created_at->format('d M Y, H:i') }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="font-bold text-amber-400 text-sm flex items-center gap-1">
                                        <i class="fa-solid fa-coins text-xs"></i>
                                        {{ number_format($item->kisa_amount) }} Kisa
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="font-extrabold text-emerald-400 text-sm">
                                        Rp {{ number_format($item->net_amount, 0, ',', '.') }}
                                    </span>
                                    <span class="block text-[10px] text-slate-500">
                                        (Gross: Rp {{ number_format($item->gross_amount, 0, ',', '.') }})
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="space-y-0.5">
                                        <span
                                            class="px-2 py-0.5 rounded bg-slate-800 text-amber-400 text-[10px] font-mono font-bold uppercase border border-slate-700">
                                            {{ strtoupper($item->bank_name) }}
                                        </span>
                                        <div class="font-medium text-slate-200 font-mono text-xs">
                                            {{ $item->account_number }}</div>
                                        <div class="text-[11px] text-slate-400">a.n {{ $item->account_name }}</div>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    @if ($item->status === 'pending')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-[11px] font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                            Menunggu Admin
                                        </span>
                                    @elseif($item->status === 'approved')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[11px] font-semibold">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                                            Berhasil
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/30 text-rose-400 text-[11px] font-semibold">
                                            <i class="fa-solid fa-circle-xmark text-[10px]"></i>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if ($item->status === 'approved' && $item->proof_file_path)
                                        <button type="button"
                                            @click="selectedProof='{{ asset('storage/' . $item->proof_file_path) }}'; openProofModal = true"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-xs font-semibold text-emerald-400 transition shadow-sm">
                                            <i class="fa-solid fa-image text-xs"></i>
                                            <span>Lihat Bukti</span>
                                        </button>
                                    @elseif($item->status === 'rejected' && $item->rejection_reason)
                                        <button type="button"
                                            @click="selectedReason='{{ addslashes($item->rejection_reason) }}'; openReasonModal = true"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-950/40 hover:bg-rose-900/40 border border-rose-800/60 rounded-xl text-xs font-semibold text-rose-300 transition shadow-sm">
                                            <i class="fa-solid fa-circle-info text-xs"></i>
                                            <span>Alasan Penolakan</span>
                                        </button>
                                    @else
                                        <span class="text-slate-500 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center text-slate-500 space-y-3">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-slate-800/60 text-slate-600 flex items-center justify-center mx-auto text-xl">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <p class="text-xs">Belum ada riwayat penarikan saldo Kisa.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $withdrawals->links() }}
            </div>

            <!-- Modal Bukti Transfer -->
            <div x-show="openProofModal" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
                <div @click.away="openProofModal=false"
                    class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 space-y-5 shadow-2xl">
                    <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-receipt text-emerald-400"></i>
                            <h3 class="text-sm font-bold text-slate-100">Bukti Transfer Pencairan</h3>
                        </div>
                        <button @click="openProofModal=false"
                            class="text-slate-400 hover:text-slate-200 text-base font-bold">&times;</button>
                    </div>
                    <div class="flex justify-center bg-slate-950 p-3 rounded-2xl border border-slate-800">
                        <img :src="selectedProof" alt="Bukti Transfer" class="max-h-96 rounded-xl object-contain">
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <a :href="selectedProof" target="_blank" download
                            class="flex-1 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-semibold rounded-xl text-slate-200 text-center transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-download"></i>
                            Unduh Gambar
                        </a>
                        <button @click="openProofModal=false"
                            class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-xs rounded-xl transition">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Alasan Penolakan -->
            <div x-show="openReasonModal" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
                <div @click.away="openReasonModal = false"
                    class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl">
                    <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                            <h3 class="text-sm font-bold text-rose-400">Detail Alasan Penolakan</h3>
                        </div>
                        <button @click="openReasonModal=false"
                            class="text-slate-400 hover:text-slate-200 text-base font-bold">&times;</button>
                    </div>
                    <div
                        class="bg-rose-950/30 border border-rose-900/50 p-4 rounded-2xl text-xs text-rose-200 leading-relaxed">
                        <p x-text="selectedReason"></p>
                    </div>
                    <div
                        class="p-3 bg-slate-950 border border-slate-800 rounded-xl flex items-center gap-2.5 text-[11px] text-slate-400">
                        <i class="fa-solid fa-circle-info text-amber-400"></i>
                        <span>Saldo Kisa Anda telah dikembalikan secara otomatis ke akun Anda.</span>
                    </div>
                    <button @click="openReasonModal=false"
                        class="w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-xs font-semibold rounded-xl text-slate-200 transition">
                        Tutup
                    </button>
                </div>
            </div>

        </div>

    </div>
</div>
