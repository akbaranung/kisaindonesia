<div
    class="w-full min-h-screen bg-slate-50 flex flex-col justify-between max-w-2xl mx-auto border-x border-slate-100 shadow-xs relative">

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 1. HEADER BACA CERITA --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <header
        class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-100 px-4 py-3.5 flex items-center justify-between gap-3">
        <a href="/stories/{{ $story->slug }}" wire:navigate
            class="p-2 -ml-2 text-slate-500 hover:text-slate-800 transition rounded-xl hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <div class="flex-1 min-w-0 text-center">
            <h1 class="text-xs font-black text-slate-900 truncate tracking-tight">{{ $story->title }}</h1>
            <p class="text-[11px] font-semibold text-slate-400 truncate mt-0.5">Bab {{ $chapter->order_number }}:
                {{ $chapter->title }}</p>
        </div>

        {{-- Indikator Koin User --}}
        @auth
            <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-100/80 px-2.5 py-1 rounded-full">
                <span class="text-xs">🫘</span>
                <span class="text-xs font-black text-amber-700">{{ auth()->user()->kisa_bean_balance ?? 0 }}</span>
            </div>
        @else
            <a href="/login" wire:navigate class="text-xs font-bold text-amber-600 hover:underline">Masuk</a>
        @endauth
    </header>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- 2. KONTEN UTAMA BACA / GEMBOK PAYWALL --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <main class="flex-1 flex flex-col w-full">

        @if ($isLocked)
            {{-- 🔒 TAMPILAN PAYWALL (TERKUNCI) --}}
            <div
                class="flex-1 flex flex-col items-center justify-center p-8 text-center my-auto min-h-[65vh] animate-fade-in">
                <div
                    class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-3xl mb-4 border border-amber-100 animate-bounce shadow-xs">
                    🫘
                </div>

                <h2 class="text-base font-black text-slate-800 uppercase tracking-wide">Bab Ini Terkunci Premium</h2>
                <p class="text-sm text-slate-500 font-medium max-w-[280px] mt-2 leading-relaxed">
                    Buka bab ini menggunakan <span
                        class="text-amber-600 font-bold">{{ $chapter->bean_price > 0 ? $chapter->bean_price : 5 }} KISA
                        Bean</span> untuk melanjutkan membaca.
                </p>

                {{-- Alert Error Saldo Kurang --}}
                @if (session()->has('error'))
                    <div
                        class="p-3 my-4 text-xs font-bold text-rose-700 bg-rose-50 border border-rose-100 rounded-2xl max-w-[280px]">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Tombol Buka Bab --}}
                <button wire:click="confirmUnlock"
                    class="mt-6 p-4 px-8 bg-slate-900 hover:bg-amber-500 text-white font-black text-xs rounded-2xl shadow-md transition-all transform active:scale-95 flex items-center gap-2">
                    <span>Buka Bab • {{ $chapter->bean_price > 0 ? $chapter->bean_price : 5 }} KISA Bean</span>
                </button>

                @if ($showUnlockModal)
                    <div
                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fade-in">
                        <div
                            class="bg-white rounded-3xl max-w-xs w-full p-6 text-center shadow-xl border border-slate-100 transform transition-all scale-100">

                            <div
                                class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3 border border-amber-100">
                                🫘
                            </div>

                            <h3 class="text-base font-black text-slate-900">Konfirmasi Penukaran</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Kamu akan menggunakan <span class="font-bold text-amber-600">{{ $chapter->bean_price }}
                                    KISA Bean</span> untuk membuka <span class="font-bold text-slate-800">Bab
                                    {{ $chapter->order_number }}</span>.
                            </p>

                            {{-- Ringkasan Saldo --}}
                            <div
                                class="my-4 p-3 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between text-xs font-semibold">
                                <span class="text-slate-400">Saldo Kamu:</span>
                                <span class="text-slate-800 font-bold">🫘 {{ auth()->user()->kisa_bean_balance ?? 0 }}
                                    Beans</span>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="flex items-center gap-2 mt-5">
                                <button wire:click="cancelUnlock"
                                    class="flex-1 p-3 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                    Batal
                                </button>
                                <button wire:click="unlockWithBeans"
                                    class="flex-1 p-3 text-xs font-black text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-xs transition transform active:scale-95">
                                    Ya, Buka
                                </button>
                            </div>

                        </div>
                    </div>
                @endif

                <a href="/topup" wire:navigate class="text-xs text-amber-600 hover:underline font-bold mt-4">
                    🛒 Isi Ulang KISA Bean Sekarang
                </a>
            </div>
        @else
            {{-- ✅ KONTEN TERBUKA (GRATIS / SUDAH DIBELI) --}}

            @if ($chapter->type === 'regular')
                {{-- 📖 KONTEN REGULAR (QUILL WYSIWYG NOVEL) --}}
                <div class="p-2 pb-5 flex-1 flex flex-col w-full bg-white">

                    {{-- Styling Teks Cerita (Ukuran Font Besar & Renggang) --}}
                    <div
                        class="prose prose-slate max-w-none text-slate-800
                                leading-relaxed md:leading-loose
                                prose-p:my-5 
                                prose-headings:text-slate-900 prose-headings:font-black
                                prose-strong:font-black prose-strong:text-slate-900
                                prose-ul:list-disc prose-ol:list-decimal prose-li:my-1 text-[12px]">

                        {!! $regularContent !!}

                    </div>

                    {{-- Navigasi Bab Sebelumnya / Selanjutnya --}}
                    <div class="flex items-center justify-between mt-12 pt-6 border-t border-slate-100">
                        @if ($prevSlug)
                            <a href="{{ route('stories.chapter.read', [$story->slug, $prevSlug]) }}" wire:navigate
                                class="p-3 px-5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                                ← Bab Sebelumnya
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if ($nextSlug)
                            <a href="{{ route('stories.chapter.read', [$story->slug, $nextSlug]) }}" wire:navigate
                                class="p-3 px-5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition">
                                Bab Berikutnya →
                            </a>
                        @endif
                    </div>
                </div>
            @else
                {{-- 💬 KONTEN CHAT FIC (TAP-TO-REVEAL WITH ALPINE.JS) --}}
                <div x-data="{
                    visibleCount: @entangle('visibleCount'),
                    totalRows: {{ $totalRows }},
                    isTyping: false,
                    triggerNextChat() {
                        if (this.visibleCount < this.totalRows && !this.isTyping) {
                            this.isTyping = true;
                            this.$nextTick(() => { window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' }) });
                
                            setTimeout(() => {
                                this.visibleCount++;
                                this.isTyping = false;
                                $wire.updateChatProgress(this.visibleCount);
                                this.$nextTick(() => { window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' }) });
                            }, 500);
                        }
                    }
                }" @click="triggerNextChat()"
                    class="p-4 md:p-6 pb-28 flex-1 flex flex-col w-full cursor-pointer select-none min-h-[80vh] bg-white">
                    <div class="flex items-center justify-center my-2">
                        <span
                            class="px-3 py-1 bg-amber-100/80 border border-amber-200/60 text-amber-800 text-[10px] font-extrabold rounded-full animate-pulse shadow-2xs">
                            👇 Ketuk di mana saja untuk lanjut membaca
                        </span>
                    </div>
                    <div class="flex flex-col gap-3 py-2 flex-1 w-full" id="chat-container">
                        @foreach ($chatRows as $index => $row)
                            @php
                                $type = $row['message_type'] ?? 'chat';
                                $char = !empty($row['character_id'])
                                    ? $story->characters->firstWhere('id', $row['character_id'])
                                    : null;
                                $charName = $char ? $char->name : 'Unknown';
                                $isRight = ($char->default_position ?? 'left') === 'right';
                                $avatar =
                                    $char && $char->avatar_path
                                        ? asset('storage/' . $char->avatar_path)
                                        : 'https://ui-avatars.com/api/?name=' .
                                            urlencode($charName) .
                                            '&background=random';
                            @endphp

                            <div x-show="{{ $index }} < visibleCount"
                                x-transition:enter="transition ease-out duration-200 transform"
                                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="w-full">

                                @if ($type === 'center_text')
                                    <div
                                        class="my-3 px-4 py-2 bg-slate-200/60 backdrop-blur-xs rounded-2xl text-center max-w-[90%] mx-auto shadow-2xs border border-slate-300/40">
                                        <p class="text-xs font-semibold italic text-slate-600 leading-relaxed">
                                            {{ $row['message'] ?? ($row['center_text'] ?? '') }}
                                        </p>
                                    </div>
                                @elseif($type === 'call')
                                    @php
                                        $isMissed = $row['call_type'] === 'missed';
                                        $isOutgoing = $row['call_type'] === 'outgoing';
                                        $isIncoming = $row['call_type'] === 'incoming';
                                    @endphp

                                    <div class="flex items-center justify-center my-2">
                                        <div
                                            class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl border text-xs font-bold shadow-2xs {{ $isMissed ? 'bg-rose-50 border-rose-200 text-rose-700' : 'bg-slate-900 border-slate-800 text-white' }}">

                                            {{-- Icon Panggilan --}}
                                            @if ($isMissed)
                                                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5"
                                                        d="M16 8l-8 8m0-8l8 8M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                            @elseif($isOutgoing)
                                                <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                            @endif

                                            {{-- Teks & Durasi Telepon --}}
                                            <div class="flex items-center gap-1.5">
                                                <span>
                                                    @if ($isMissed)
                                                        Panggilan Tak Terjawab
                                                    @elseif($isOutgoing)
                                                        Panggilan Keluar
                                                    @else
                                                        Panggilan Masuk
                                                    @endif
                                                </span>

                                                @if (!empty($row['duration']))
                                                    <span
                                                        class="opacity-60 text-[11px] font-medium">({{ $row['duration'] }})</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                @elseif($type === 'image')
                                    <div
                                        class="flex items-end gap-2.5 my-1 {{ $isRight ? 'flex-row-reverse' : 'flex-row' }}">
                                        {{-- Avatar --}}
                                        <img src="{{ $avatar }}"
                                            class="w-6 h-6 rounded-full object-cover border border-slate-800 shrink-0">

                                        {{-- Gelembung Pesan --}}
                                        <div
                                            class="max-w-[80%] flex flex-col {{ $isRight ? 'items-end' : 'items-start' }}">
                                            <span
                                                class="text-[10px] font-black text-slate-600 px-0.5 mb-0.5">{{ $charName }}</span>
                                            @if (!$isRight && !empty($row['character_name']))
                                                <span class="text-[10px] font-extrabold text-slate-400 mb-1 ml-1">
                                                    {{ $row['character_name'] }}
                                                </span>
                                            @endif

                                            <div
                                                class="p-3.5 px-4 rounded-2xl text-xs font-medium leading-relaxed shadow-2xs break-words {{ $isRight ? 'bg-amber-500 text-slate-950 rounded-br-xs' : 'bg-white text-slate-800 rounded-bl-xs border border-slate-200/80' }}">
                                                @if (($row['message_type'] ?? 'text') === 'image' && !empty($row['image_url']))
                                                    <img src="{{ asset('storage/' . $row['image_url']) }}"
                                                        alt="Chat Image"
                                                        class="rounded-lg max-w-xs my-1 object-cover cursor-pointer hover:opacity-95 transition"
                                                        onclick="window.open(this.src, '_blank')">
                                                @endif
                                                @if (!empty($row['message']))
                                                    <p class="text-[10px] font-semibold text-slate-300 px-1 py-0.5">
                                                        {{ $row['message'] }}</p>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                @else
                                    <div
                                        class="flex items-end gap-2.5 my-1 {{ $isRight ? 'flex-row-reverse' : 'flex-row' }}">
                                        {{-- Avatar --}}
                                        <img src="{{ $avatar }}"
                                            class="w-6 h-6 rounded-full object-cover border border-slate-800 shrink-0">

                                        {{-- Gelembung Pesan --}}
                                        <div
                                            class="max-w-[80%] flex flex-col {{ $isRight ? 'items-end' : 'items-start' }}">
                                            <span
                                                class="text-[10px] font-black text-slate-600 px-0.5 mb-0.5">{{ $charName }}</span>
                                            @if (!$isRight && !empty($row['character_name']))
                                                <span class="text-[10px] font-extrabold text-slate-400 mb-1 ml-1">
                                                    {{ $row['character_name'] }}
                                                </span>
                                            @endif

                                            <div
                                                class="p-3.5 px-4 rounded-2xl text-xs font-medium leading-relaxed shadow-2xs break-words {{ $isRight ? 'bg-amber-500 text-slate-950 rounded-br-xs' : 'bg-white text-slate-800 rounded-bl-xs border border-slate-200/80' }}">
                                                <p class="whitespace-pre-line">{{ $row['message'] ?? '' }}</p>
                                            </div>
                                        </div>

                                    </div>
                                @endif

                            </div>
                        @endforeach

                        {{-- Typing Indicator Animasi --}}
                        <div x-show="isTyping"
                            class="flex items-center gap-1.5 p-3 bg-slate-100 w-16 rounded-2xl animate-pulse my-1">
                            <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce [animation-delay:0.2s]"></div>
                            <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce [animation-delay:0.4s]"></div>
                        </div>
                    </div>

                    {{-- Navigasi Bab Jika Chat Sudah Selesai --}}
                    <div x-show="visibleCount >= totalRows"
                        class="flex items-center justify-between mt-8 pt-6 border-t border-slate-100">
                        @if ($prevSlug)
                            <a href="{{ route('stories.chapter.read', [$story->slug, $prevSlug]) }}" wire:navigate
                                class="p-3 px-5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                                ← Bab Sebelumnya
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if ($nextSlug)
                            <a href="{{ route('stories.chapter.read', [$story->slug, $nextSlug]) }}" wire:navigate
                                class="p-3 px-5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition">
                                Bab Berikutnya →
                            </a>
                        @endif
                    </div>
                </div>
            @endif

        @endif

        <livewire:story.chapter.chapter-comments :chapter="$chapter" />
    </main>


    {{-- Spacer Aman Bawah --}}
    <div class="h-10 w-full" aria-hidden="true"></div>

</div>
