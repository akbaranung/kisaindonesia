<div x-data="{
    copied: false,
    showPicturePreview: false,
    previewImageUrl: '',
    fontSize: localStorage.getItem('reader_font_size') || 'text-xs',
    visibleCount: @entangle('visibleCount'),
    totalRows: {{ (int) $totalRows }},
    isTyping: false,
    typingTimer: null,
    showComments: false,
    showResetModal: false,
    touchStartY: 0,
    isSwiping: false,
    isAtBottom: false,

    init() {
        // Cegah pull-to-refresh browser pada level dokumen
        document.documentElement.style.overscrollBehaviorY = 'none';
        document.body.style.overscrollBehaviorY = 'none';

        if (Number(this.visibleCount) >= Number(this.totalRows) && Number(this.totalRows) > 0) {
            this.showResetModal = true;
        }

        this.checkIfFinished();
        this.resetTypingTimer();

        this.$nextTick(() => {
            this.checkChatScroll();
        });

        this.$watch('fontSize', value => {
            localStorage.setItem('reader_font_size', value);
        });

        this.$watch('visibleCount', value => {
            this.checkIfFinished();
        });
    },

    restartReading() {
        this.showResetModal = false;
        this.visibleCount = 1;
        this.isAtBottom = false;
        $wire.updateChatProgress(1);
        this.checkIfFinished();
        this.resetTypingTimer();

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        this.$nextTick(() => {
            const container = this.$refs.chatScrollArea;
            if (container) {
                container.scrollTop = 0;
                this.checkChatScroll();
            }
        });
    },

    continueReading() {
        this.showResetModal = false;
        this.scrollToBottom();
    },

    scrollToComments() {
        this.$nextTick(() => {
            const commentEl = this.$refs.commentSection;
            if (commentEl) {
                const headerOffset = 65;
                const elementPosition = commentEl.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            } else {
                this.scrollToBottom();
            }
        });
    },

    scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
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
                const container = this.$refs.chatScrollArea;
                if (container) {
                    const isNearBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 150;
                    if (isNearBottom) {
                        this.scrollToBottom();
                    }
                }
            }, 10000);
        }
    },

    checkChatScroll() {
        const container = this.$refs.chatScrollArea;
        if (!container) return;

        const canScroll = container.scrollHeight > container.clientHeight + 10;
        const atBottom = (container.scrollTop + container.clientHeight) >= (container.scrollHeight - 25);
        const shouldShift = canScroll && atBottom;
        this.isAtBottom = shouldShift;

        // Prioritaskan scroll di dalam chat container, cegah body ikut tertarik
        const targetEl = this.$refs.mainContent;

        if (shouldShift && targetEl) {
            // Mentokkan elemen ke bagian atas layar (top = 0) secara presisi menggunakan scrollIntoView
            targetEl.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    },

    scrollToBottom(behavior = 'smooth') {
        this.$nextTick(() => {
            const container = this.$refs.chatScrollArea;
            if (!container) return;

            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior
                    });
                    setTimeout(() => {
                        this.checkChatScroll();
                    }, behavior === 'smooth' ? 250 : 30);
                });
            });
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
    class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col max-w-2xl w-full mx-auto border-x border-slate-100 dark:border-slate-800 shadow-xs relative transition-colors duration-200 overscroll-y-none">

    {{-- Notification Toast saat Salin Tautan --}}
    <div x-show="copied" x-cloak x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
        class="fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-slate-900 dark:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-full shadow-lg flex items-center gap-2 border border-slate-700 dark:border-slate-600">
        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
        <span>Tautan bab berhasil disalin!</span>
    </div>

    {{-- MODAL DIALOG BACA ULANG CHAT --}}
    <template x-teleport="body">
        <div x-show="showResetModal" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs select-none">

            <div
                class="bg-white dark:bg-slate-900 rounded-3xl max-w-xs w-full p-6 text-center shadow-2xl border border-slate-100 dark:border-slate-800 transform transition-all scale-100">
                <div
                    class="w-14 h-14 bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3 border border-brand-100 dark:border-brand-900/50">
                    <i class="fa-regular fa-comment"></i>
                </div>

                <h3 class="text-base font-black text-slate-900 dark:text-slate-100">Bab Sudah Selesai</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                    Kamu sudah menyelesaikan bab ini. Apakah ingin membaca ulang dari awal?
                </p>

                <div class="flex flex-col gap-2 mt-6">
                    <button @click="restartReading()"
                        class="w-full p-3 text-xs font-black text-white bg-slate-900 dark:bg-brand-600 hover:bg-slate-800 dark:hover:bg-brand-500 rounded-xl shadow-xs transition transform active:scale-95">
                        <i class="fa-solid fa-rotate"></i> Baca Ulang dari Awal
                    </button>
                    <button @click="continueReading()"
                        class="w-full p-3 text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition">
                        Lihat Seluruh Chat & Komentar
                    </button>
                </div>
            </div>
        </div>
    </template>

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

    {{-- HEADER BACA CERITA (Sticky di bagian paling atas) --}}
    <header
        class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 py-3.5 flex items-center justify-between gap-2 px-3 sm:px-4 transition-colors shadow-2xs">
        <a href="/stories/{{ $story->slug }}" wire:navigate
            class="p-2 -ml-1 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 transition rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <div class="flex-1 min-w-0 text-center">
            <h1 class="text-xs font-black text-slate-900 dark:text-slate-100 truncate tracking-tight">
                {{ $story->title }}
            </h1>
            <p class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 truncate mt-0.5">Bab
                {{ $chapter->order_number }}:
                {{ $chapter->title }}
            </p>
        </div>

        <div class="flex items-center gap-1">
            {{-- Tombol Toggle Mode Terang/Gelap --}}
            <button onclick="toggleTheme()" type="button" aria-label="Toggle Dark Mode"
                class="p-2 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 transition rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                <i class="fa-solid fa-moon hidden dark:block text-amber-300 text-sm"></i>
                <i class="fa-solid fa-sun block dark:hidden text-amber-500 text-sm"></i>
            </button>

            {{-- Dropdown Pengaturan Ukuran Font --}}
            <div x-data="{ openFont: false }" class="relative">
                <button @click="openFont = !openFont" title="Ukuran Font"
                    class="p-2 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 transition rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center gap-0.5">
                    <span class="text-[10px] font-bold">A</span><span class="text-sm font-black">A</span>
                </button>

                <div x-show="openFont" @click.outside="openFont = false" x-cloak
                    x-transition:enter="transition ease-out duration-150 transform"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    class="absolute right-0 mt-2 w-36 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800 p-1.5 z-40 flex flex-col gap-1">

                    <button @click="fontSize = 'text-xs'; openFont = false"
                        :class="fontSize === 'text-xs' ? 'bg-slate-900 dark:bg-brand-600 text-white' :
                            'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800'"
                        class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition text-left flex items-center justify-between">
                        <span>Kecil</span>
                        <span class="text-[10px] opacity-70">12px</span>
                    </button>

                    <button @click="fontSize = 'text-sm'; openFont = false"
                        :class="fontSize === 'text-sm' ? 'bg-slate-900 dark:bg-brand-600 text-white' :
                            'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800'"
                        class="px-3 py-1.5 text-xs font-bold rounded-xl transition text-left flex items-center justify-between">
                        <span>Sedang</span>
                        <span class="text-[10px] opacity-70">14px</span>
                    </button>

                    <button @click="fontSize = 'text-base'; openFont = false"
                        :class="fontSize === 'text-base' ? 'bg-slate-900 dark:bg-brand-600 text-white' :
                            'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800'"
                        class="px-3 py-1.5 text-sm font-bold rounded-xl transition text-left flex items-center justify-between">
                        <span>Besar</span>
                        <span class="text-[10px] opacity-70">16px</span>
                    </button>

                    <button @click="fontSize = 'text-lg'; openFont = false"
                        :class="fontSize === 'text-lg' ? 'bg-slate-900 dark:bg-brand-600 text-white' :
                            'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800'"
                        class="px-3 py-1.5 text-base font-bold rounded-xl transition text-left flex items-center justify-between">
                        <span>Sangat Besar</span>
                        <span class="text-[10px] opacity-70">18px</span>
                    </button>
                </div>
            </div>

            {{-- Tombol Lompat ke Komentar di Header --}}
            <button @click="scrollToComments()" title="Lihat Komentar"
                class="p-2 text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 transition rounded-xl hover:bg-brand-50 dark:hover:bg-brand-950/40 active:scale-90 relative">
                <i class="fa-regular fa-comment text-sm"></i>
                @php $cCount = $chapter->comments()->count(); @endphp
                @if ($cCount > 0)
                    <span
                        class="absolute top-1 right-1 min-w-[14px] h-[14px] px-1 bg-brand-500 text-white text-[9px] font-black rounded-full flex items-center justify-center leading-none">
                        {{ $cCount > 99 ? '99+' : $cCount }}
                    </span>
                @endif
            </button>

            <button @click="shareChapter()" title="Bagikan Bab Ini"
                class="p-2 text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 transition rounded-xl hover:bg-brand-50 dark:hover:bg-brand-950/40 active:scale-90">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684" />
                </svg>
            </button>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 flex flex-col w-full gap-4" x-ref="mainContent">

        @if ($isLocked)
            {{-- TAMPILAN GEMBOK PAYWALL --}}
            <div
                class="flex-1 flex flex-col items-center justify-center p-8 text-center my-auto min-h-[65vh] animate-fade-in">
                <div
                    class="w-16 h-16 bg-amber-50 dark:bg-amber-950/60 text-amber-500 rounded-2xl flex items-center justify-center text-3xl mb-4 border border-amber-100 dark:border-amber-800/60 animate-bounce shadow-xs">
                    🫘
                </div>

                <h2 class="text-base font-black text-slate-800 dark:text-slate-100 uppercase tracking-wide">Bab Ini
                    Terkunci Premium</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium max-w-[280px] mt-2 leading-relaxed">
                    Buka bab ini menggunakan <span
                        class="text-amber-600 dark:text-amber-400 font-bold">{{ $chapter->bean_price > 0 ? $chapter->bean_price : 5 }}
                        KISA
                        Bean</span> untuk melanjutkan membaca.
                </p>

                <div
                    class="mt-3 text-[11px] font-semibold text-amber-800 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/60 border border-amber-200/70 dark:border-amber-800/60 px-3.5 py-1.5 rounded-xl flex items-center justify-center gap-1.5 max-w-[280px] shadow-3xs">
                    <i class="fa-solid fa-clock text-amber-600 dark:text-amber-400"></i>
                    <span>Masa akses bab berlaku <strong>{{ $expiryDays }} Hari</strong></span>
                </div>

                @if (session()->has('error'))
                    <div
                        class="p-3 my-4 text-xs font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 border border-rose-100 dark:border-rose-900/60 rounded-2xl max-w-[280px]">
                        {{ session('error') }}
                    </div>
                @endif

                <button wire:click="confirmUnlock"
                    class="mt-6 p-4 px-8 bg-slate-900 dark:bg-brand-600 hover:bg-amber-500 dark:hover:bg-brand-500 text-white font-black text-xs rounded-2xl shadow-md transition-all transform active:scale-95 flex items-center gap-2">
                    <span>Buka Bab • {{ $chapter->bean_price > 0 ? $chapter->bean_price : 5 }} KISA Bean</span>
                </button>

                @if ($showUnlockModal)
                    <div
                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs animate-fade-in select-none">
                        <div
                            class="bg-white dark:bg-slate-900 rounded-3xl max-w-xs w-full p-6 text-center shadow-xl border border-slate-100 dark:border-slate-800 transform transition-all scale-100">
                            <div
                                class="w-14 h-14 bg-amber-50 dark:bg-amber-950/60 text-amber-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3 border border-amber-100 dark:border-amber-800/60">
                                🫘
                            </div>

                            <h3 class="text-base font-black text-slate-900 dark:text-slate-100">Konfirmasi Penukaran
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                                Kamu akan menggunakan <span
                                    class="font-bold text-amber-600 dark:text-amber-400">{{ $chapter->bean_price }}
                                    KISA Bean</span> untuk membuka <span
                                    class="font-bold text-slate-800 dark:text-slate-200">Bab
                                    {{ $chapter->order_number }}</span>.
                            </p>

                            <div
                                class="my-3 p-2.5 bg-amber-50 dark:bg-amber-950/60 border border-amber-200/80 dark:border-amber-800/60 rounded-2xl text-[11px] text-amber-800 dark:text-amber-300 font-semibold flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-clock text-amber-600 dark:text-amber-400"></i>
                                <span>Akses bab berlaku <strong>{{ $expiryDays }} Hari</strong> setelah dibuka</span>
                            </div>

                            <div
                                class="mb-4 p-3 bg-slate-50 dark:bg-slate-800/70 rounded-2xl border border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-semibold">
                                <span class="text-slate-400 dark:text-slate-400">Saldo Kamu:</span>
                                <span class="text-slate-800 dark:text-slate-100 font-bold">🫘
                                    {{ auth()->user()->kisa_bean_balance ?? 0 }}
                                    Beans</span>
                            </div>

                            <div class="flex items-center gap-2 mt-5">
                                <button wire:click="cancelUnlock"
                                    class="flex-1 p-3 text-xs font-bold text-slate-500 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition">
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

                <a href="/topup" wire:navigate
                    class="text-xs text-amber-600 dark:text-amber-400 hover:underline font-bold mt-4">
                    🛒 Isi Ulang KISA Bean Sekarang
                </a>
            </div>
        @else
            {{-- KONTEN TERBUKA --}}
            @if ($chapter->type === 'regular')
                {{-- KONTEN REGULAR --}}
                <div
                    class="p-4 sm:p-6 md:p-8 pb-10 flex-1 flex flex-col w-full bg-white dark:bg-slate-900 rounded-2xl transition-colors shadow-2xs mb-20 min-h-[calc(100dvh-7.5rem)]">

                    {{-- Header Judul Bab di Konten Bacaan --}}
                    <div class="mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <span
                            class="text-[11px] font-black tracking-wider text-brand-600 dark:text-brand-400 uppercase">
                            Bab {{ $chapter->order_number }}
                        </span>
                        <h2
                            class="text-base sm:text-xl font-black text-slate-900 dark:text-slate-100 mt-1 leading-snug">
                            {{ $chapter->title }}
                        </h2>
                        @if ($chapter->word_count)
                            <p
                                class="text-[11px] text-slate-400 dark:text-slate-500 font-medium mt-1 flex items-center gap-2">
                                <span><i class="fa-regular fa-clock mr-1"></i> ±
                                    {{ max(1, ceil($chapter->word_count / 150)) }} menit baca</span>
                                <span>•</span>
                                <span>{{ number_format($chapter->word_count) }} kata</span>
                            </p>
                        @endif
                    </div>

                    {{-- Konten Teks Novel / Puisi --}}
                    <div :class="fontSize"
                        class="prose prose-slate dark:prose-invert max-w-none text-slate-800 dark:text-slate-200 leading-relaxed md:leading-loose prose-p:text-[inherit] prose-p:my-5 prose-headings:text-slate-900 dark:prose-headings:text-slate-100 prose-headings:font-black prose-strong:font-black prose-strong:text-slate-900 dark:prose-strong:text-white prose-ul:list-disc prose-ol:list-decimal prose-li:my-1 break-words transition-all duration-150 text-justify">
                        {!! $regularContent !!}
                    </div>

                    {{-- Navigasi Bab Sebelumnya & Berikutnya --}}
                    <div
                        class="flex items-center justify-between mt-12 pt-6 border-t border-slate-100 dark:border-slate-800 gap-3">
                        @if ($prevSlug)
                            <a href="{{ route('stories.chapter.read', [$story->slug, $prevSlug]) }}" wire:navigate
                                class="flex-1 max-w-[170px] p-2.5 px-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition text-center flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                                <span>Bab Sebelumnya</span>
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if ($nextSlug)
                            <a href="{{ route('stories.chapter.read', [$story->slug, $nextSlug]) }}" wire:navigate
                                class="flex-1 max-w-[170px] p-2.5 px-4 bg-slate-900 dark:bg-brand-600 hover:bg-slate-800 dark:hover:bg-brand-500 text-white text-xs font-black rounded-xl shadow-xs transition text-center flex items-center justify-center gap-1.5 active:scale-95">
                                <span>Bab Berikutnya</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        @endif
                    </div>

                    {{-- Area Komentar dengan x-ref agar shortcut header berfungsi --}}
                    <div x-ref="commentSection" class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <div
                            class="flex items-center justify-between gap-3 pb-3.5 mb-3.5 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-comments text-brand-500 text-base"></i>
                                <span class="text-xs font-black text-slate-900 dark:text-slate-100">Komentar
                                    Pembaca</span>
                                @php $regCCount = $chapter->comments()->count(); @endphp
                                @if ($regCCount > 0)
                                    <span
                                        class="px-2 py-0.5 text-[10px] font-bold bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 rounded-full">
                                        {{ $regCCount }}
                                    </span>
                                @endif
                            </div>

                            <button type="button" @click="scrollToTop()"
                                class="px-3 py-1.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition flex items-center gap-1.5 active:scale-95">
                                <i class="fa-solid fa-arrow-up text-[10px]"></i>
                                <span>Ke Atas</span>
                            </button>
                        </div>

                        <livewire:story.chapter.chapter-comments :chapter="$chapter" />
                    </div>
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

                <div class="flex-1 flex flex-col w-full gap-2 sm:gap-3">

                    {{-- Container Area Chat yang diisolasi scroll-nya --}}
                    <div class="relative w-full">
                        <div @touchstart="touchStartY = $event.touches[0].clientY; isSwiping = false"
                            @touchmove="if (Math.abs($event.touches[0].clientY - touchStartY) > 8) { isSwiping = true }"
                            @click="if (isSwiping) { isSwiping = false; return; } triggerNextChat()"
                            @scroll.passive="checkChatScroll()" x-ref="chatScrollArea"
                            style="{{ $chatBgUrl ? "background-image: url('{$chatBgUrl}'); background-size: cover; background-position: center; background-repeat: no-repeat;" : '' }}; overscroll-behavior: contain; overscroll-behavior-y: contain; -webkit-overflow-scrolling: touch;"
                            class="px-3 py-4 md:p-6 pb-12 flex flex-col w-full cursor-pointer h-[calc(100dvh-8.5rem)] min-h-[480px] md:h-[calc(100dvh-9rem)] md:max-h-[820px] overflow-y-auto overscroll-contain touch-pan-y rounded-2xl relative shadow-inner {{ !$chatBgUrl ? 'bg-white dark:bg-slate-900' : '' }} bg-slate-500 bg-blend-multiply transition-colors">

                            <div class="flex flex-col gap-3 flex-1 w-full relative z-10" id="chat-container">

                                {{-- Petunjuk Tap --}}
                                <div class="flex items-center justify-center my-2" x-show="!showComments">
                                    <span
                                        class="px-3 py-1 bg-brand-100/90 dark:bg-brand-950/80 backdrop-blur-xs border border-brand-200/60 dark:border-brand-800/60 text-brand-800 dark:text-brand-300 text-[10px] font-extrabold rounded-full animate-pulse shadow-2xs">
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
                                                class="my-3 px-4 py-2 bg-slate-200/80 dark:bg-slate-500/80 rounded-2xl text-center max-w-[90%] mx-auto shadow-2xs border border-slate-300/40 dark:border-slate-700/50">
                                                <p :class="fontSize"
                                                    class="font-semibold italic text-slate-700 dark:text-slate-300 leading-relaxed transition-all duration-150">
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
                                                    class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl border text-xs font-bold shadow-2xs {{ $isMissed ? 'bg-rose-50 dark:bg-rose-950/60 border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-300' : 'bg-slate-900 dark:bg-slate-800 border-slate-800 dark:border-slate-700 text-white' }}">
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

                                                    <div :class="fontSize"
                                                        class="flex items-center gap-1.5 transition-all duration-150">
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
                                                    class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 shrink-0 cursor-pointer hover:opacity-80 transition"
                                                    @click.stop="openPreview('{{ $avatar }}')"
                                                    oncontextmenu="return false;" ondragstart="return false;">

                                                <div
                                                    class="max-w-[80%] flex flex-col {{ $isRight ? 'items-end' : 'items-start' }}">
                                                    <span
                                                        class="text-[10px] {{ $chatBgUrl ? 'text-slate-200 drop-shadow-xs' : 'text-slate-400 dark:text-slate-400' }} px-0.5 mb-0.5">{{ $charName }}</span>

                                                    <div
                                                        class="p-1.5 rounded-2xl font-semibold leading-relaxed shadow-2xs break-words {{ $isRight ? 'bg-brand-500 text-slate-950 rounded-br-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-bl-xs border border-slate-200/60 dark:border-slate-700/60' }}">
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
                                                            <p :class="fontSize"
                                                                class="font-semibold px-2 py-1 mt-1 transition-all duration-150 {{ $isRight ? 'text-slate-950' : 'text-slate-800 dark:text-slate-100' }}">
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
                                                    class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 shrink-0 cursor-pointer hover:opacity-80 transition"
                                                    @click.stop="openPreview('{{ $avatar }}')"
                                                    oncontextmenu="return false;" ondragstart="return false;">

                                                <div
                                                    class="max-w-[80%] flex flex-col {{ $isRight ? 'items-end' : 'items-start' }}">
                                                    <span
                                                        class="text-[10px] {{ $chatBgUrl ? 'text-slate-200 drop-shadow-xs' : 'text-slate-400 dark:text-slate-400' }} px-0.5 mb-0.5">{{ $charName }}</span>

                                                    <div :class="fontSize"
                                                        class="px-3.5 py-2 rounded-2xl font-semibold leading-relaxed shadow-2xs break-words transition-all duration-150 {{ $isRight ? 'bg-brand-500 text-slate-950 rounded-br-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-bl-xs border border-slate-200/60 dark:border-slate-700/60' }}">
                                                        {{ $row['message'] ?? '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach

                                {{-- Indikator Mengetik --}}
                                <div x-show="isTyping && !showComments" x-cloak
                                    class="flex items-center gap-1.5 px-3 py-2 bg-slate-100/90 dark:bg-slate-800/90 backdrop-blur-xs rounded-full w-max text-slate-400 dark:text-slate-400 my-1 animate-pulse border border-slate-200/60 dark:border-slate-700/60">
                                    <span
                                        class="w-1.5 h-1.5 bg-slate-400 dark:bg-slate-300 rounded-full animate-bounce"></span>
                                    <span
                                        class="w-1.5 h-1.5 bg-slate-400 dark:bg-slate-300 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                                    <span
                                        class="w-1.5 h-1.5 bg-slate-400 dark:bg-slate-300 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                                </div>
                            </div>
                        </div>
                    </div>



                    {{-- Area Komentar & Navigasi Bab --}}
                    <div x-ref="commentSection" x-cloak @click.stop
                        class="p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-2xs mb-20 -mt-1 sm:mt-0 transition-colors">

                        {{-- Header Komentar & Tombol Kembali ke Chat --}}
                        <div
                            class="flex items-center justify-between gap-3 pb-3.5 mb-3.5 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-comments text-brand-500 text-base"></i>
                                <span class="text-xs font-black text-slate-900 dark:text-slate-100">Komentar
                                    Pembaca</span>
                                @php $cCount = $chapter->comments()->count(); @endphp
                                @if ($cCount > 0)
                                    <span
                                        class="px-2 py-0.5 text-[10px] font-bold bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 rounded-full">
                                        {{ $cCount }}
                                    </span>
                                @endif
                            </div>

                            <button type="button" @click="scrollToTop()"
                                class="px-3 py-1.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition flex items-center gap-1.5 active:scale-95">
                                <i class="fa-solid fa-arrow-up text-[10px]"></i>
                                <span>Ke Chat</span>
                            </button>
                        </div>

                        <div class="flex items-center justify-between gap-3 mb-4">
                            @if ($prevSlug)
                                <a href="{{ route('stories.chapter.read', [$story->slug, $prevSlug]) }}" wire:navigate
                                    class="p-2.5 px-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition">
                                    Bab Sebelumnya
                                </a>
                            @else
                                <div></div>
                            @endif

                            @if ($nextSlug)
                                <a href="{{ route('stories.chapter.read', [$story->slug, $nextSlug]) }}" wire:navigate
                                    class="p-2.5 px-4 bg-slate-900 dark:bg-brand-600 hover:bg-slate-800 dark:hover:bg-brand-500 text-white text-xs font-bold rounded-xl transition">
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
