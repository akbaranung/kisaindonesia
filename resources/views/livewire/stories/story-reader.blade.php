<div x-data="{
    copied: false,
    showPicturePreview: false,
    previewImageUrl: '',
    visibleCount: @entangle('visibleCount'),
    totalRows: {{ (int) $totalRows }},
    isTyping: false,
    typingTimer: null,
    showComments: false,

    init() {
        this.checkIfFinished();
        this.resetTypingTimer();

        this.$watch('visibleCount', value => {
            this.checkIfFinished();
        });
    },

    openPreview(url) {
        this.previewImageUrl = url;
        this.showPicturePreview = true;
    },

    shareChapter() {
        const shareData = {
            title: '{{ addslashes($story->title) }} - Bab {{ $chapter->order_number }}',
            text: 'Baca cerita {{ addslashes($story->title) }} - Bab {{ $chapter->order_number }}: {{ addslashes($chapter->title) }}',
            url: window.location.href
        };

        if (navigator.share) {
            navigator.share(shareData).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href);
            this.copied = true;
            setTimeout(() => this.copied = false, 2500);
        }
    },

    checkIfFinished() {
        if (Number(this.visibleCount) >= Number(this.totalRows)) {
            this.showComments = true;
            this.isTyping = false;
            clearTimeout(this.typingTimer);
        } else {
            this.showComments = false;
        }
    },

    resetTypingTimer() {
        this.isTyping = false;
        clearTimeout(this.typingTimer);
        if (!this.showComments) {
            this.typingTimer = setTimeout(() => {
                this.isTyping = true;
                this.scrollToBottom();
            }, 10000); // 10 detik diam
        }
    },

    scrollToBottom() {
        this.$nextTick(() => {
            const container = this.$refs.chatScrollArea;
            if (container) {
                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: 'smooth'
                });
            }
        });
    },

    triggerNextChat() {
        if (!this.showComments) {
            this.visibleCount = Number(this.visibleCount) + 1;
            $wire.updateChatProgress(this.visibleCount);
            this.checkIfFinished();
            this.resetTypingTimer();
            this.scrollToBottom();
        }
    }
}"
    class="w-full min-h-screen bg-slate-50 flex flex-col justify-between max-w-2xl mx-auto border-x border-slate-100 shadow-xs relative">

    {{-- Notification Toast saat Salin Tautan --}}
    <div x-show="copied" x-cloak x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
        class="fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-slate-900 text-white text-xs font-bold px-4 py-2.5 rounded-full shadow-lg flex items-center gap-2 border border-slate-700">
        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
        <span>Tautan bab berhasil disalin!</span>
    </div>

    {{-- Modal Preview Avatar / Gambar --}}
    <template x-teleport="body">
        <div x-show="showPicturePreview" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @keydown.window.escape="showPicturePreview = false"
            @keydown.window.ctrl.s.prevent @keydown.window.ctrl.p.prevent oncontextmenu="return false;"
            ondragstart="return false;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/90 backdrop-blur-md p-4 print:hidden select-none">

            <div class="fixed inset-0" @click="showPicturePreview = false"></div>

            <div class="relative z-10 max-w-sm w-full flex flex-col items-center gap-3">
                <div class="w-full flex items-center justify-between text-slate-300 px-2">
                    <span class="text-xs font-bold flex items-center gap-1.5 text-slate-400">
                        <i class="fa-solid fa-shield-halved text-brand-400"></i>
                        Preview Image
                    </span>
                    <button type="button" @click="showPicturePreview = false"
                        class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center text-sm font-bold transition">
                        ✕
                    </button>
                </div>

                <div
                    class="relative rounded-2xl overflow-hidden shadow-2xl border border-slate-800 bg-slate-900 max-h-[75vh] select-none">
                    <img :src="previewImageUrl" alt="Avatar Preview" oncontextmenu="return false;"
                        ondragstart="return false;" class="max-h-[75vh] w-auto object-contain select-none">

                    <div class="absolute inset-0 z-20 pointer-events-auto flex items-center justify-center overflow-hidden"
                        oncontextmenu="return false;" ondragstart="return false;">
                        <div
                            class="opacity-20 rotate-[-30deg] text-white font-extrabold text-xs tracking-widest text-center select-none pointer-events-none whitespace-nowrap">
                            KISA INDONESIA • PROTECTED PREVIEW
                        </div>
                    </div>
                </div>

                <p class="text-[10px] text-slate-400 font-medium text-center">
                    🔒 Image dilindungi hak cipta &amp; tidak dapat diunduh.
                </p>
            </div>
        </div>
    </template>

    {{-- HEADER BACA CERITA --}}
    <header
        class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-100 py-3.5 flex items-center justify-between gap-2.5 px-4">
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

        <div class="flex items-center gap-1.5">
            <button @click="shareChapter()" title="Bagikan Bab Ini"
                class="p-2 text-slate-500 hover:text-brand-600 transition rounded-xl hover:bg-brand-50 active:scale-90">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684" />
                </svg>
            </button>

            @auth
                <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-100/80 px-2.5 py-1 rounded-full">
                    <span class="text-xs">🫘</span>
                    <span class="text-xs font-black text-amber-700">{{ auth()->user()->kisa_bean_balance ?? 0 }}</span>
                </div>
            @else
                <a href="/login" wire:navigate class="text-xs font-bold text-amber-600 hover:underline">Masuk</a>
            @endauth
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 flex flex-col w-full">

        @if ($isLocked)
            {{-- TAMPILAN GEMBOK PAYWALL --}}
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

                <div
                    class="mt-3 text-[11px] font-semibold text-amber-800 bg-amber-50 border border-amber-200/70 px-3.5 py-1.5 rounded-xl flex items-center justify-center gap-1.5 max-w-[280px] shadow-3xs">
                    <i class="fa-solid fa-clock text-amber-600"></i>
                    <span>Masa akses bab berlaku <strong>{{ $expiryDays }} Hari</strong></span>
                </div>

                @if (session()->has('error'))
                    <div
                        class="p-3 my-4 text-xs font-bold text-rose-700 bg-rose-50 border border-rose-100 rounded-2xl max-w-[280px]">
                        {{ session('error') }}
                    </div>
                @endif

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

                            <div
                                class="my-3 p-2.5 bg-amber-50 border border-amber-200/80 rounded-2xl text-[11px] text-amber-800 font-semibold flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-clock text-amber-600"></i>
                                <span>Akses bab berlaku <strong>{{ $expiryDays }} Hari</strong> setelah dibuka</span>
                            </div>

                            <div
                                class="mb-4 p-3 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between text-xs font-semibold">
                                <span class="text-slate-400">Saldo Kamu:</span>
                                <span class="text-slate-800 font-bold">🫘 {{ auth()->user()->kisa_bean_balance ?? 0 }}
                                    Beans</span>
                            </div>

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
            {{-- KONTEN TERBUKA --}}
            @if ($chapter->type === 'regular')
                {{-- KONTEN REGULAR --}}
                <div class="p-4 pb-5 flex-1 flex flex-col w-full bg-white">
                    <div
                        class="prose prose-slate max-w-none text-slate-800 leading-relaxed md:leading-loose prose-p:my-5 prose-headings:text-slate-900 prose-headings:font-black prose-strong:font-black prose-strong:text-slate-900 prose-ul:list-disc prose-ol:list-decimal prose-li:my-1 text-[12px] text-justify">
                        {!! $regularContent !!}
                    </div>

                    <div class="flex items-center justify-between mt-12 pt-6 border-t border-slate-100">
                        @if ($prevSlug)
                            <a href="{{ route('stories.chapter.read', [$story->slug, $prevSlug]) }}" wire:navigate
                                class="p-3 px-5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                                Bab Sebelumnya
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if ($nextSlug)
                            <a href="{{ route('stories.chapter.read', [$story->slug, $nextSlug]) }}" wire:navigate
                                class="p-3 px-5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition">
                                Bab Berikutnya
                            </a>
                        @endif
                    </div>
                    <livewire:story.chapter.chapter-comments :chapter="$chapter" />
                </div>
            @else
                {{-- KONTEN CHAT FIC --}}
                @php
                    $chatBgUrl = $chapter->cover_path
                        ? (\Illuminate\Support\Str::startsWith($chapter->cover_path, ['http://', 'https://'])
                            ? $chapter->cover_path
                            : asset('storage/' . $chapter->cover_path))
                        : null;
                @endphp

                <div class="flex-1 flex flex-col w-full gap-4">

                    <div class="relative w-full">
                        <div @click="triggerNextChat()" @scroll="checkScroll($event)" x-ref="chatScrollArea"
                            style="{{ $chatBgUrl ? "background-image: url('{$chatBgUrl}'); background-size: cover; background-position: center; background-repeat: no-repeat;" : '' }}"
                            class="px-3 py-4 md:p-6 mb-20 flex flex-col w-full cursor-pointer h-[100vh] overflow-y-auto rounded-2xl relative shadow-inner {{ !$chatBgUrl ? 'bg-white' : '' }} bg-slate-500 bg-blend-multiply">

                            <div class="flex flex-col gap-3 flex-1 w-full relative z-10" id="chat-container">

                                {{-- Petunjuk Tap --}}
                                <div class="flex items-center justify-center my-2" x-show="!showComments">
                                    <span
                                        class="px-3 py-1 bg-brand-100/90 backdrop-blur-xs border border-brand-200/60 text-brand-800 text-[10px] font-extrabold rounded-full animate-pulse shadow-2xs">
                                        👇 Ketuk di mana saja untuk lanjut membaca
                                    </span>
                                </div>

                                {{-- Loop Baris Chat --}}
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

                                    <div x-show="{{ $index }} < Number(visibleCount)"
                                        x-transition:enter="transition ease-out duration-100 transform"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100" class="w-full relative">

                                        @if ($type === 'center_text')
                                            <div
                                                class="my-3 px-4 py-2 bg-slate-200/80 backdrop-blur-xs rounded-2xl text-center max-w-[90%] mx-auto shadow-2xs border border-slate-300/40">
                                                <p class="text-xs font-semibold italic text-slate-700 leading-relaxed">
                                                    {{ $row['message'] ?? ($row['center_text'] ?? '') }}
                                                </p>
                                            </div>
                                        @elseif($type === 'call')
                                            @php
                                                $isMissed = ($row['call_type'] ?? '') === 'missed';
                                                $isOutgoing = ($row['call_type'] ?? '') === 'outgoing';
                                            @endphp

                                            <div class="flex items-center justify-center my-2">
                                                <div
                                                    class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl border text-xs font-bold shadow-2xs {{ $isMissed ? 'bg-rose-50 border-rose-200 text-rose-700' : 'bg-slate-900 border-slate-800 text-white' }}">
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
                                                <img src="{{ $avatar }}"
                                                    class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0 cursor-pointer hover:opacity-80 transition"
                                                    @click.stop="openPreview('{{ $avatar }}')"
                                                    oncontextmenu="return false;" ondragstart="return false;">

                                                <div
                                                    class="max-w-[80%] flex flex-col {{ $isRight ? 'items-end' : 'items-start' }}">
                                                    <span
                                                        class="text-[10px] {{ $chatBgUrl ? 'text-slate-200 drop-shadow-xs' : 'text-slate-400' }} px-0.5 mb-0.5">{{ $charName }}</span>

                                                    <div
                                                        class="p-1.5 rounded-2xl text-xs font-semibold leading-relaxed shadow-2xs break-words {{ $isRight ? 'bg-brand-500 text-slate-950 rounded-br-xs' : 'bg-slate-100 text-slate-800 rounded-bl-xs border border-slate-200/60' }}">
                                                        @php
                                                            $rawImg =
                                                                $row['image_url'] ?? ($row['existing_image_url'] ?? '');
                                                            $imgSrc = !empty($rawImg)
                                                                ? (\Illuminate\Support\Str::startsWith($rawImg, [
                                                                    'http://',
                                                                    'https://',
                                                                ])
                                                                    ? $rawImg
                                                                    : (\Illuminate\Support\Str::startsWith(
                                                                        $rawImg,
                                                                        'storage/',
                                                                    )
                                                                        ? asset($rawImg)
                                                                        : asset('storage/' . $rawImg)))
                                                                : null;
                                                        @endphp
                                                        @if ($imgSrc)
                                                            <img src="{{ $imgSrc }}" alt="Chat Image"
                                                                class="rounded-xl w-full max-w-[240px] sm:max-w-xs max-h-[320px] object-cover cursor-pointer hover:opacity-95 transition"
                                                                @click.stop="openPreview('{{ $imgSrc }}')">
                                                        @endif
                                                        @if (!empty($row['message']) || !empty($row['caption']))
                                                            <p
                                                                class="text-xs font-semibold px-2 py-1 mt-1 {{ $isRight ? 'text-slate-950' : 'text-slate-800' }}">
                                                                {{ $row['message'] ?? $row['caption'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{-- Tipe Chat Biasa --}}
                                            <div
                                                class="flex items-end gap-2.5 my-1 {{ $isRight ? 'flex-row-reverse' : 'flex-row' }}">
                                                <img src="{{ $avatar }}"
                                                    class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0 cursor-pointer hover:opacity-80 transition"
                                                    @click.stop="openPreview('{{ $avatar }}')"
                                                    oncontextmenu="return false;" ondragstart="return false;">

                                                <div
                                                    class="max-w-[80%] flex flex-col {{ $isRight ? 'items-end' : 'items-start' }}">
                                                    <span
                                                        class="text-[10px] {{ $chatBgUrl ? 'text-slate-200 drop-shadow-xs' : 'text-slate-400' }} px-0.5 mb-0.5">{{ $charName }}</span>

                                                    <div
                                                        class="px-3.5 py-2 rounded-2xl text-xs font-semibold leading-relaxed shadow-2xs break-words {{ $isRight ? 'bg-brand-500 text-slate-950 rounded-br-xs' : 'bg-slate-100 text-slate-800 rounded-bl-xs border border-slate-200/60' }}">
                                                        {{ $row['message'] ?? '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach

                                {{-- Indikator Mengetik --}}
                                <div x-show="isTyping && !showComments" x-cloak
                                    class="flex items-center gap-1.5 px-3 py-2 bg-slate-100/90 backdrop-blur-xs rounded-full w-max text-slate-400 my-1 animate-pulse border border-slate-200/60">
                                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></span>
                                    <span
                                        class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                                    <span
                                        class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div x-show="showComments" x-cloak @click.stop
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="p-2 bg-white rounded-2xl border border-slate-100 shadow-2xs mt-4 mb-20">

                        <div class="flex items-center justify-between gap-3">
                            @if ($prevSlug)
                                <a href="{{ route('stories.chapter.read', [$story->slug, $prevSlug]) }}" wire:navigate
                                    class="p-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                                    Bab Sebelumnya
                                </a>
                            @else
                                <div></div>
                            @endif

                            @if ($nextSlug)
                                <a href="{{ route('stories.chapter.read', [$story->slug, $nextSlug]) }}" wire:navigate
                                    class="p-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition">
                                    Bab Berikutnya
                                </a>
                            @endif
                        </div>

                        <livewire:story.chapter.chapter-comments :chapter="$chapter" />
                    </div>

                </div>
            @endif

        @endif

    </main>
</div>
