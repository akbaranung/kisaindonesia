@push('heads')
    @php
        // Pastikan variabel $story tersedia di halaman detail
        $shareTitle = isset($story)
            ? $story->title . ' - Kisa Indonesia'
            : 'Kisa Indonesia - Platform Membaca & Menulis';
        $shareDescription = isset($story)
            ? Str::limit(strip_tags($story->synopsis), 120)
            : 'Baca cerita seru dan interaktif.';
        $shareImage = isset($story)
            ? route('stories.og', ['story' => $story->slug, 'v' => optional($story->updated_at)->timestamp])
            : asset('default-banner.jpg');
        $shareUrl = url()->current();
    @endphp

    <!-- Standard Meta Tags -->
    <title>{{ $shareTitle }}</title>
    <meta name="description" content="{{ $shareDescription }}">

    <!-- Open Graph / Facebook / WhatsApp / Telegram -->
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ $shareUrl }}" />
    <meta property="og:title" content="{{ $shareTitle }}" />
    <meta property="og:description" content="{{ $shareDescription }}" />
    <meta property="og:image" content="{{ $shareImage }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ $shareUrl }}" />
    <meta name="twitter:title" content="{{ $shareTitle }}" />
    <meta name="twitter:description" content="{{ $shareDescription }}" />
    <meta name="twitter:image" content="{{ $shareImage }}" />
@endpush

<div x-data="{
    showCoverPreview: false,
    copied: false,
    isGeneratingShare: false,
    cardFile: null, // cache gambar kartu supaya tap berikutnya instan

    // html2canvas dimuat hanya saat dibutuhkan (share di HP), tanpa tag script di luar root Livewire
    loadHtml2canvas() {
        if (window.html2canvas) return Promise.resolve();
        return new Promise((resolve, reject) => {
            const s = document.createElement('script');
            s.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
            s.onload = resolve;
            s.onerror = () => reject(new Error('Gagal memuat html2canvas'));
            document.head.appendChild(s);
        });
    },

    async buildCardFile() {
        if (this.cardFile) return this.cardFile;
        await this.loadHtml2canvas();

        const cardElement = document.getElementById('share-card-element');
        if (!cardElement) throw new Error('Elemen #share-card-element tidak ditemukan');

        const canvas = await html2canvas(cardElement, {
            scale: 2,
            useCORS: true,
            allowTaint: false,
            backgroundColor: '#0f172a',
            onclone: (clonedDoc) => {
                const clonedEl = clonedDoc.getElementById('share-card-element');
                if (clonedEl) clonedEl.style.color = '#ffffff';
            }
        });

        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
        this.cardFile = new File([blob], 'Kisa-{{ Str::slug($story->title) }}.png', { type: 'image/png' });
        return this.cardFile;
    },

    flashCopied() {
        this.copied = true;
        setTimeout(() => this.copied = false, 3500);
    },

    async shareStory() {
        if (this.isGeneratingShare) return;
        this.isGeneratingShare = true;

        const shareUrl = window.location.href;
        const title = '{{ addslashes($story->title) }}';
        const text = '✨ Sedang membaca ' + title + ' di Kisa Indonesia. Yuk, ikuti kisah serunya di sini!';

        try {
            // 1) Perangkat yang bisa share file (umumnya HP): kirim gambar + link sebagai caption
            const probe = new File([''], 'x.png', { type: 'image/png' });
            const canShareFile = navigator.canShare && navigator.canShare({ files: [probe] });

            if (canShareFile) {
                try {
                    const imageFile = await this.buildCardFile();
                    await navigator.share({
                        title: title,
                        text: text + '\n' + shareUrl, // link di dalam text, BUKAN di parameter url
                        files: [imageFile]
                    });
                    return;
                } catch (err) {
                    if (err.name === 'AbortError') return; // user menutup panel share
                    console.warn('Share file gagal, pakai fallback link:', err);
                }
            }

            // 2) Fallback: share link saja (preview kartu muncul otomatis dari og:image)
            if (navigator.share) {
                try {
                    await navigator.share({ title: title, text: text, url: shareUrl });
                    return;
                } catch (err) {
                    if (err.name === 'AbortError') return;
                }
            }

            // 3) Desktop tanpa Web Share API: salin link
            await navigator.clipboard.writeText(shareUrl);
            this.flashCopied();

        } catch (error) {
            console.error('Gagal membagikan cerita:', error);
            alert('Gagal membagikan cerita. Coba lagi ya.');
        } finally {
            this.isGeneratingShare = false;
        }
    }

}" @contextmenu.prevent @copy.prevent @cut.prevent @paste.prevent
    style="user-select: none; -webkit-user-select: none; -webkit-touch-callout: none;" class="select-none"
    oncontextmenu="return false;" ondragstart="return false;">

    {{-- 🛑 PINDAHKAN TEMPLATE KARTU KE LUAR (DI SINI AMAN UNTUK HTML2CANVAS) --}}
    <div style="position: absolute; left: -9999px; top: -9999px;">
        <div id="share-card-element"
            style="width: 400px; height: 500px; background: #0f172a; color: #ffffff; padding: 24px; border-radius: 24px; border: 1px solid #1e293b; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden; text-align: center; user-select: none; font-family: sans-serif;">

            {{-- Glow Accent (Ganti dengan Hex/RGBA agar aman) --}}
            <div
                style="position: absolute; top: -40px; left: -40px; width: 150px; height: 150px; background: rgba(99, 102, 241, 0.2); border-radius: 50%; filter: blur(40px);">
            </div>
            <div
                style="position: absolute; bottom: -40px; right: -40px; width: 150px; height: 150px; background: rgba(245, 158, 11, 0.2); border-radius: 50%; filter: blur(40px);">
            </div>

            {{-- Header Watermark --}}
            <div
                style="position: relative; z-index: 10; display: flex; align-items: center; justify-content: space-between; font-size: 10px; font-weight: 900; letter-spacing: 0.1em; color: #818cf8; text-transform: uppercase;">
                <span>KISA INDONESIA</span>
                <span style="color: #64748b;">• OFFICIAL READER</span>
            </div>

            {{-- Cover & Metadata --}}
            <div
                style="position: relative; z-index: 10; display: flex; flex-direction: column; align-items: center; margin: auto 0;">
                <div
                    style="width: 112px; height: 160px; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5); border: 1px solid #334155; margin-bottom: 14px; transform: rotate(-2deg);">
                    @if ($story->cover_path)
                        <img src="{{ asset('storage/' . $story->cover_path) }}" crossorigin="anonymous"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div
                            style="width: 100%; height: 100%; background: #1e293b; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            📖</div>
                    @endif
                </div>

                <h2
                    style="font-size: 16px; font-weight: 900; color: #ffffff; line-height: 1.25; margin: 0 0 6px 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; padding: 0 8px;">
                    {{ $story->title }}
                </h2>

                <p style="font-size: 11px; font-weight: 700; color: #fbbf24; margin: 0 0 12px 0;">
                    ✍️ {{ $story->penName->name ?? 'Penulis' }}
                </p>

                <div
                    style="padding: 4px 12px; background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(51, 65, 85, 0.8); border-radius: 9999px; font-size: 10px; color: #cbd5e1; font-weight: 500;">
                    {{ $story->genre->name ?? 'Fiksi' }} •
                    {{ $story->chapters->where('status', 'published')->count() }} Bab
                </div>
            </div>

            {{-- Footer --}}
            <div
                style="position: relative; z-index: 10; padding-top: 12px; border-top: 1px solid #1e293b; display: flex; align-items: center; justify-content: space-between; font-size: 10px; color: #94a3b8; font-weight: 500;">
                <span>Baca selengkapnya di</span>
                <span
                    style="font-weight: 900; color: #ffffff; background: #4f46e5; padding: 4px 10px; border-radius: 8px;">kisaindonesia.id</span>
            </div>
        </div>
    </div>

    {{-- TOAST NOTIFIKASI SUKSES UNTUK DESKTOP --}}
    <div x-show="copied" x-cloak x-transition
        class="fixed bottom-5 right-5 z-50 bg-slate-900 border border-slate-700 text-white text-xs font-bold px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-2.5">
        <span class="text-emerald-400 text-base">✓</span>
        <div>
            <p class="font-black">Berhasil!</p>
            <p class="text-[10px] text-slate-400 font-normal">Tautan disalin. Tempel di chat untuk menampilkan kartu
                pratinjau.</p>
        </div>
    </div>

    {{-- 📱 TOP NAVIGATION BAR --}}
    <div
        class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between sticky top-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xs z-50 mb-3 transition-colors">
        <a href="{{ url()->previous() }}" wire:navigate
            class="text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center gap-1 transition bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 p-2 rounded-xl">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <div class="flex items-center gap-2">
            <button @click="shareStory()" :disabled="isGeneratingShare" title="Bagikan Cerita Ini"
                class="p-2 text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 transition rounded-xl hover:bg-brand-50 dark:hover:bg-brand-950/40 active:scale-90 relative disabled:opacity-50">

                <!-- Spinner Loading saat Generate Gambar -->
                <template x-if="isGeneratingShare">
                    <i class="fa-solid fa-spinner animate-spin text-sm text-brand-500"></i>
                </template>

                <!-- Icon Share Biasa -->
                <template x-if="!isGeneratingShare">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684" />
                    </svg>
                </template>
            </button>
            {{-- Tombol Toggle Mode Terang/Gelap --}}
            <button onclick="toggleTheme()" type="button" aria-label="Toggle Dark Mode"
                class="p-2 text-xs rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                <i class="fa-solid fa-moon hidden dark:block text-amber-300 text-sm"></i>
                <i class="fa-solid fa-sun block dark:hidden text-amber-500 text-sm"></i>
            </button>

            <button wire:click="toggleLibrary"
                class="p-2 text-xs font-bold rounded-xl transition flex items-center justify-center border {{ $isSaved ? 'bg-brand-50 dark:bg-brand-950/60 border-brand-200 dark:border-brand-800 text-brand-600 dark:text-brand-400' : 'bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}"
                title="{{ $isSaved ? 'Hapus dari Perpustakaan' : 'Tambah ke Perpustakaan' }}">
                @if ($isSaved)
                    <i class="fa-solid fa-bookmark text-brand-600 dark:text-brand-400 text-sm"></i>
                @else
                    <i class="fa-regular fa-bookmark text-slate-600 dark:text-slate-300 text-sm"></i>
                @endif
            </button>
        </div>
    </div>

    {{-- 📖 COVER & METADATA UTAMA CERITA --}}
    <div
        class="flex flex-col items-center text-center bg-slate-50/60 dark:bg-slate-900/40 border-b border-slate-100/90 dark:border-slate-800 animate-fade-in py-2 transition-colors">

        <div
            class="w-32 h-44 bg-slate-200 dark:bg-slate-800 rounded-2xl overflow-hidden shadow-md mb-4 border border-slate-200/40 dark:border-slate-700/50 transform hover:scale-[1.02] transition-transform relative select-none">
            @if ($story->cover_path)
                <img src="{{ asset('storage/' . $story->cover_path) }}" @click="showCoverPreview = true"
                    oncontextmenu="return false;" ondragstart="return false;"
                    class="w-full h-full object-cover cursor-pointer select-none">
                <div @click="showCoverPreview = true" oncontextmenu="return false;" ondragstart="return false;"
                    class="absolute inset-0 z-10 cursor-pointer"></div>
            @else
                <div
                    class="w-full h-full flex flex-col items-center justify-center text-3xl bg-slate-100 dark:bg-slate-800">
                    <span><i class="fa-solid fa-book"></i></span>
                </div>
            @endif
        </div>

        @if ($story->cover_path)
            <!-- MODAL PREVIEW COVER PROTECTED -->
            <template x-teleport="body">
                <div x-show="showCoverPreview" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" @keydown.window.escape="showCoverPreview = false"
                    @keydown.window.ctrl.s.prevent @keydown.window.ctrl.p.prevent oncontextmenu="return false;"
                    ondragstart="return false;"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/90 backdrop-blur-md p-4 print:hidden select-none">

                    <div class="fixed inset-0" @click="showCoverPreview = false"></div>

                    <div class="relative z-10 max-w-sm w-full flex flex-col items-center gap-3">
                        <div class="w-full flex items-center justify-between text-slate-300 px-2">
                            <span class="text-xs font-bold flex items-center gap-1.5 text-slate-400">
                                <i class="fa-solid fa-shield-halved text-brand-400"></i> Preview Cover
                            </span>
                            <button type="button" @click="showCoverPreview = false"
                                class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center text-sm font-bold transition">
                                ✕
                            </button>
                        </div>

                        <div
                            class="relative rounded-2xl overflow-hidden shadow-2xl border border-slate-800 bg-slate-900 max-h-[75vh] select-none">
                            <img src="{{ asset('storage/' . $story->cover_path) }}" alt="{{ $story->title }}"
                                oncontextmenu="return false;" ondragstart="return false;"
                                class="max-h-[75vh] w-auto object-contain select-none">

                            <div class="absolute inset-0 z-20 pointer-events-auto flex items-center justify-center overflow-hidden"
                                oncontextmenu="return false;" ondragstart="return false;">
                                <div
                                    class="opacity-20 rotate-[-30deg] text-white font-extrabold text-xs tracking-widest text-center select-none pointer-events-none whitespace-nowrap">
                                    KISA INDONESIA • PROTECTED PREVIEW
                                </div>
                            </div>
                        </div>

                        <p class="text-[10px] text-slate-400 font-medium text-center">
                            🔒 Cover dilindungi hak cipta &amp; tidak dapat diunduh.
                        </p>
                    </div>
                </div>
            </template>
        @endif

        {{-- Badge Jenis Cerita --}}

        <div class="flex items-center gap-1.5 flex-wrap text-[12px] font-bold mb-2">
            @if ($story->hasTypeChat())
                <span class="px-3 py-0.5 rounded-xl text-[12px] font-bold bg-brand-600 text-white">
                    <i class="fa-solid fa-comment-dots me-1"></i> CHAT
                </span>
            @elseif(!$story->hasTypeChat() && $story->type === 'novel')
                <span class="px-3 py-0.5 rounded-xl text-[12px] font-bold bg-brand-600 text-white">
                    <i class="fa-solid fa-pencil me-1"></i> NOVEL
                </span>
            @elseif($story->type === 'puisi')
                <span class="px-3 py-0.5 rounded-xl text-[12px] font-bold bg-brand-600 text-white">
                    <i class="fa-solid fa-feather-pointed me-1"></i> PUISI
                </span>
            @elseif($story->type === 'non_fiksi')
                <span class="px-3 py-0.5 rounded-xl text-[12px] font-bold bg-brand-600 text-white">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> NON FIKSI
                </span>
            @endif
        </div>

        {{-- Judul & Penulis --}}
        <h1 class="text-lg font-black text-slate-800 dark:text-slate-100 leading-tight px-4">{{ $story->title }}</h1>
        <span class="text-[15px] my-2 text-slate-400 dark:text-slate-500 font-medium line-clamp-1">
            {{ $story->genre->name ?? 'Uncategorized' }}
        </span>
        <div class="flex gap-2 items-center text-brand-600 dark:text-brand-400 font-bold">
            <div
                class="w-8 h-8 rounded-full overflow-hidden bg-brand-100 dark:bg-slate-800 flex-shrink-0 border-4 border-slate-50 dark:border-slate-800 shadow-inner flex items-center justify-center">
                <img src="{{ $story->penName->profile_photo_url }}" alt="{{ $story->penName->name }}"
                    class="w-full h-full object-cover">
            </div>
            <a href="{{ route('pen-name.show', $story->penName->id) }}"
                class="flex items-center gap-1 hover:underline">
                <span>{{ $story->penName->name }}</span>
                <span><i class="fa-solid fa-chevron-right text-xs"></i></span>
            </a>
        </div>

        {{-- Tombol Simpan ke Perpustakaan --}}
        <div class="mt-3">
            <button wire:click="toggleLibrary"
                class="px-4 py-2 text-xs font-bold rounded-xl transition flex items-center gap-2 border shadow-3xs active:scale-95 {{ $isSaved ? 'bg-brand-50 dark:bg-brand-950/60 border-brand-200 dark:border-brand-800 text-brand-600 dark:text-brand-400' : 'bg-slate-900 dark:bg-brand-600 border-slate-900 dark:border-brand-600 text-white hover:bg-brand-600 dark:hover:bg-brand-500' }}">
                @if ($isSaved)
                    <i class="fa-solid fa-bookmark text-brand-600 dark:text-brand-400"></i> Tersimpan di Perpustakaan
                @else
                    <i class="fa-regular fa-bookmark"></i> + Tambah ke Perpustakaan
                @endif
            </button>
        </div>

        @if ($story->penName)
            {{-- <livewire:follow-button :pen-name="$story->penName" variant="compact" /> --}}
        @endif

        <div
            class="my-6 p-3 grid grid-cols-2 text-slate-500 dark:text-slate-400 divide-x-2 divide-slate-200 dark:divide-slate-800 font-bold text-[20px]">
            <div class="px-6">
                <i class="fa-solid fa-eye text-brand-600 dark:text-brand-400 me-2"></i>
                {{ number_format_short($story->views_count) }}
            </div>

            <div class="px-6">
                <i class="fa-solid fa-book-open text-brand-600 dark:text-brand-400 me-2"></i>
                {{ number_format_short($story->chapters->where('status', 'published')->count()) }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 text-center my-3">
        <div wire:click="setTab('synopsis')"
            class="pb-3 text-sm font-bold border-b-2 cursor-pointer transition {{ $tab === 'synopsis' ? 'border-brand-400 text-brand-500 dark:text-brand-400' : 'border-slate-300 dark:border-slate-700 text-slate-400 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }}">
            Sinopsis (Blurb)
        </div>
        <div wire:click="setTab('chaptersList')"
            class="pb-3 text-sm font-bold border-b-2 cursor-pointer transition {{ $tab === 'chaptersList' ? 'border-brand-400 text-brand-500 dark:text-brand-400' : 'border-slate-300 dark:border-slate-700 text-slate-400 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }}">
            Daftar Bab
        </div>
    </div>
    {{-- 📝 BLOK SINOPSIS --}}
    @if ($tab === 'synopsis')
        <div class="border-b-2 border-slate-100 dark:border-slate-800 pb-4 select-none" oncontextmenu="return false;"
            ondragstart="return false;">
            <h2
                class="text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-widest mb-2 px-0.5">
                Blurb</h2>
            <p style="user-select: none; -webkit-user-select: none; -webkit-touch-callout: none;"
                class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed bg-slate-50/50 dark:bg-slate-900/60 p-3.5 rounded-2xl border border-slate-100/80 dark:border-slate-800 text-justify select-none">
                {{ $story->synopsis ?? 'Belum ada sinopsis resmi yang dibagikan oleh penulis untuk kisah ini, Bro.' }}
            </p>
        </div>
    @else
        @if ($lastReadChapter)
            <div class="pb-2 animate-fade-in">
                <a href="{{ route('stories.chapter.read', [$story->slug, $lastReadChapter->slug]) }}" wire:navigate
                    class="flex items-center justify-center gap-2 w-full p-3.5 bg-brand-600 hover:bg-brand-700 text-white font-black text-xs rounded-2xl shadow-md transition transform hover:scale-[1.01]">
                    LANJUTKAN MEMBACA: {{ Str::limit($lastReadChapter->title, 20) }}
                </a>
            </div>
        @endif

        {{-- 📑 DAFTAR ISI BAB (HANYA YANG PUBLISHED) --}}
        <div class="my-3 animate-fade-in" style="animation-delay: 100ms;">
            <div class="flex items-center justify-between mb-3 px-0.5">
                <h2 class="text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-widest">Daftar
                    Isi Bab
                    ({{ $chapters->count() }})</h2>
                <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 italic">Diperbarui Baru-baru
                    Ini</span>
            </div>

            <div class="flex flex-col gap-2.5">
                @forelse($chapters as $index => $ch)
                    {{-- KUNCI UTAMA: Wajib pakai wire:navigate agar transisi pindah ke simulator baca berjalan instan --}}
                    <a href="{{ route('stories.chapter.read', [$story->slug, $ch->slug]) }}" wire:navigate
                        class="group flex items-center justify-between p-3.5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-brand-500/40 dark:hover:border-brand-500/40 hover:bg-brand-50/10 dark:hover:bg-brand-950/20 transition shadow-3xs">
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col gap-0.5">
                                <span
                                    class="text-[9px] text-slate-400 font-extrabold uppercase group-hover:text-brand-600 dark:group-hover:text-brand-400 transition">BAB
                                    {{ $index + 1 }}</span>
                                <span
                                    class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white transition">{{ $ch->title }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <span
                                class="text-[10px] text-brand-600 dark:text-brand-400 font-black tracking-wide bg-brand-50 dark:bg-brand-950/60 border border-transparent dark:border-brand-900/50 px-2 py-1 rounded-lg group-hover:bg-brand-600 group-hover:text-white transition">
                                🫘 {{ $ch->is_premium ? $ch->bean_price : 0 }}
                            </span>
                        </div>
                    </a>
                @empty
                    {{-- State Jika Isi Bab Masih Kosong / Belum Ada yang Published --}}
                    <div
                        class="text-center py-10 text-xs text-slate-400 dark:text-slate-400 font-bold italic bg-slate-50/50 dark:bg-slate-900/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-6">
                        <span class="text-xl mb-1 block">📭</span>
                        Kisah ini belum merilis bab apa pun untuk publik, Bro.
                    </div>
                @endforelse
            </div>
        </div>

    @endif
    <div class="py-6 px-2 pb-6">
        @livewire('Story.story-review', ['storyId' => $story->id])
    </div>

    @if ($showAgeConfirmation)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md select-none animate-fade-in">
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-slate-100 dark:border-slate-800">
                <div
                    class="w-14 h-14 bg-rose-50 dark:bg-rose-950/60 text-rose-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3 border border-rose-100 dark:border-rose-900/50">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <h3 class="text-base font-black text-slate-900 dark:text-slate-100">Konten Khusus Dewasa (18+)</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                    Cerita <span class="font-bold text-slate-800 dark:text-slate-200">"{{ $story->title }}"</span> ini
                    mengandung tema, bahasa, atau adegan yang ditujukan khusus untuk pembaca dewasa.
                </p>

                <div
                    class="my-4 p-3 bg-slate-50 dark:bg-slate-800/70 rounded-2xl border border-slate-100 dark:border-slate-800 text-[11px] text-slate-600 dark:text-slate-300 font-medium">
                    Apakah usia Anda sudah 18 tahun atau lebih?
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    {{-- Tombol Ya, Lanjutkan --}}
                    <button wire:click="verifyAge"
                        class="w-full p-3 text-xs font-black text-white bg-slate-900 dark:bg-brand-600 hover:bg-slate-800 dark:hover:bg-brand-500 rounded-xl shadow-xs transition transform active:scale-95">
                        Ya, Saya Sudah 18 Tahun
                    </button>

                    {{-- Tombol Batal / Kembali ke Daftar Cerita --}}
                    <a href="{{ URL::previous() }}" wire:navigate
                        class="w-full p-3 text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition">
                        Tidak, Kembali
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
