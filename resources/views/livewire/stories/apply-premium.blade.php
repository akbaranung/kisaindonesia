<div class="w-full min-h-screen bg-slate-50 pb-28">

    {{-- TOP MOBILE NAVIGATION BAR --}}
    <div
        class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-100 py-3.5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ url('/my-stories') }}" wire:navigate
                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-base font-bold text-slate-800 leading-tight">Pengajuan Premium</h1>
                <p class="text-[10px] text-slate-400 font-medium">Monetisasi karya dengan Kisa Bean</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full">
            <span class="text-xs">🪙</span>
            <span class="text-[11px] font-extrabold text-amber-700">Royalti</span>
        </div>
    </div>

    <div class="pt-4 space-y-4 max-w-md mx-auto">

        {{-- ALERT NOTIFIKASI MOBILE --}}
        @if (session('success'))
            <div
                class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-2xl flex items-start gap-2.5 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <div class="font-medium leading-relaxed">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div
                class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-2xl flex items-start gap-2.5 shadow-2xs">
                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="font-medium leading-relaxed">{{ session('error') }}</div>
            </div>
        @endif

        {{-- LANGKAH 1: PILIH CERITA --}}
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs space-y-3">
            <div class="flex items-center justify-between">
                <label class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                    <span
                        class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-black flex items-center justify-center">1</span>
                    Pilih Cerita
                </label>
                <span class="text-[10px] text-slate-400">Khusus Cerita Gratis</span>
            </div>

            <select wire:model.live="storyId"
                class="w-full text-xs p-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 font-semibold text-slate-800 transition-all">
                <option value="">-- Sentuh untuk memilih cerita --</option>
                @foreach ($myStories as $story)
                    <option value="{{ $story->id }}">{{ $story->title }}</option>
                @endforeach
            </select>

            @if ($selectedStory)
                @php
                    $storyType = strtolower($selectedStory->type ?? 'novel');
                    $minWords = $storyType === 'puisi' ? 700 : 1000;
                    $maxWords = 1500;
                @endphp
                <div class="pt-3 border-t border-slate-100 space-y-2.5">

                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-700">Aturan Bab Premium (Bab 6+):</span>
                        <span
                            class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full {{ $storyType === 'puisi' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $storyType }}
                        </span>
                    </div>

                    <div class="p-2.5 rounded-xl border bg-slate-50 border-slate-200 text-xs space-y-1">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-500">Batas Kata Murni / Bab:</span>
                            <span class="font-bold text-slate-800">{{ number_format($minWords) }} -
                                {{ number_format($maxWords) }} Kata</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-500">Total Bab Cerita:</span>
                            <span class="font-bold {{ $totalChapters >= 5 ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ $totalChapters }} / 5 Bab (Min)
                            </span>
                        </div>
                    </div>

                    <div class="space-y-1.5 pt-1">
                        <span class="text-[10px] font-bold text-slate-500 uppercase block">Status Tiap Bab
                            Premium:</span>

                        @foreach ($selectedStory->chapters as $index => $chap)
                            @if ($index >= 0)
                                @php
                                    $cWords = $chap->word_count;
                                    $cValid =
                                        $storyType === 'puisi'
                                            ? $cWords >= 700 && $cWords <= 1500
                                            : $cWords >= 1000 && $cWords <= 1500;
                                @endphp
                                <div
                                    class="flex items-center justify-between p-2 rounded-lg text-[11px] {{ $cValid ? 'bg-emerald-50 text-emerald-800' : 'bg-rose-50 text-rose-800' }}">
                                    <span class="font-medium truncate max-w-[180px]">
                                        {{ $chap->title }}</span>
                                    <span class="font-bold shrink-0">
                                        {{ number_format($cWords) }} Kata
                                        {!! $cValid ? '✓' : '✗' !!}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Estimasi Kisa Bean --}}
                    <div
                        class="p-3 bg-gradient-to-br from-amber-500/10 to-amber-500/5 border border-amber-200/80 rounded-xl flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-amber-800 font-bold uppercase block">Estimasi Royalti</span>
                            <span class="text-xs text-amber-900/70 font-medium">Hitungan bab 6 ke atas</span>
                        </div>
                        <div class="text-right">
                            <span
                                class="text-base font-black text-amber-600">~{{ number_format($estimatedBeans) }}</span>
                            <span class="text-[10px] font-bold text-amber-700 block">Kisa Bean</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- LANGKAH 2: FORM REKENING / STATUS PENGAJUAN --}}
        @if (!$selectedStory)
            <div class="p-6 bg-white rounded-2xl border border-dashed border-slate-200 text-center space-y-2">
                <div
                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400 text-lg">
                    📖</div>
                <p class="text-xs font-semibold text-slate-600">Pilih cerita di atas</p>
                <p class="text-[10px] text-slate-400">Sistem akan secara otomatis memeriksa kualifikasi bab dan
                    perkiraan koin.</p>
            </div>
        @elseif($existingRequest && $existingRequest->status === 'pending')
            {{-- STATUS PENDING (MOBILE CARD) --}}
            <div class="p-4 bg-amber-500/10 border border-amber-300/80 rounded-2xl space-y-2">
                <div class="flex items-center gap-2 font-bold text-amber-900 text-xs">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    Pengajuan Dalam Proses Peninjauan
                </div>
                <p class="text-[11px] text-amber-800/80 leading-relaxed">
                    Pengajuan diajukan pada <strong
                        class="text-amber-950">{{ $existingRequest->created_at->format('d M Y, H:i') }}</strong>. Admin
                    sedang meninjau format bab cerita Anda.
                </p>
            </div>
        @elseif($selectedStory && $isEligible)
            {{-- FORM REKENING (MOBILE STYLE) --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs space-y-3.5">
                @if ($existingRequest && $existingRequest->status === 'rejected')
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-[11px] space-y-1">
                        <span class="font-bold text-rose-800 block">⚠️ Pengajuan Sebelumnya Ditolak</span>
                        <p class="text-rose-700">"{{ $existingRequest->rejection_reason }}"</p>
                    </div>
                @endif

                <label class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                    <span
                        class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-black flex items-center justify-center">2</span>
                    Rekening Pencairan Royalti
                </label>

                {{-- Bank --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Bank / E-Wallet <span
                            class="text-rose-500">*</span></label>
                    <select wire:model="bankName"
                        class="w-full text-xs p-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 font-medium">
                        <option value="">-- Pilih Bank / E-Wallet --</option>
                        <option value="BCA">BCA</option>
                        <option value="Mandiri">Bank Mandiri</option>
                        <option value="BRI">BRI</option>
                        <option value="BNI">BNI</option>
                        <option value="Gopay">GoPay</option>
                        <option value="OVO">OVO</option>
                        <option value="Dana">DANA</option>
                        <option value="ShopeePay">ShopeePay</option>
                    </select>
                    @error('bankName')
                        <span class="text-[10px] text-rose-500 font-medium block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- No Rekening --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Nomor Rekening / HP
                        <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model="accountNumber" placeholder="Contoh: 08123456789 / 12345678"
                        class="w-full text-xs p-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 font-medium">
                    @error('accountNumber')
                        <span class="text-[10px] text-rose-500 font-medium block mt-1">{{ $message }}</span>
                    @enderror

                </div>

                {{-- Atas Nama --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Nama Pemilik Rekening
                        <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="accountHolderName" placeholder="Sesuai buku tabungan / akun"
                        class="w-full text-xs p-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 font-medium">
                    @error('accountHolderName')
                        <span class="text-[10px] text-rose-500 font-medium block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Catatan untuk Admin
                        (Opsional)</label>
                    <textarea wire:model="authorNotes" rows="2" placeholder="Catatan khusus terkait cerita..."
                        class="w-full text-xs p-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 font-medium"></textarea>
                </div>
            </div>
        @elseif($selectedStory && !$isEligible)
            <div class="p-4 bg-slate-100 rounded-2xl border border-slate-200 text-center space-y-1.5">
                <p class="text-xs font-bold text-slate-700">Belum Memenuhi Syarat Minimal</p>
                <p class="text-[10px] text-slate-500 leading-relaxed">
                    Syarat minimal pengajuan cerita premium adalah <strong>5 Bab</strong> dan total untuk tipe
                    <strong>{{ strtoupper($storyType) }}</strong> adalah
                    <strong>{{ $minWords . ' - ' . $maxWords }}</strong>
                    Kata. Silakan tambah bab atau isi cerita terlebih dahulu.
                </p>
            </div>
        @endif

    </div>

    {{-- FLOATING BOTTOM BAR (MOBILE ACTION BUTTON) --}}
    @if ($selectedStory && $isEligible && (!$existingRequest || $existingRequest->status !== 'pending'))
        <div class="inset-x-0 backdrop-blur-md border-slate-200 p-3 z-30 max-w-md mx-auto">
            <button wire:click="submitApplication" wire:loading.attr="disabled"
                class="w-full py-3 bg-amber-500 hover:bg-amber-600 active:scale-[0.99] text-slate-950 font-black text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                <span wire:loading.remove>🚀 Kirim Pengajuan Premium</span>
                <span wire:loading>Memproses Pengajuan...</span>
            </button>
        </div>
    @endif

</div>
