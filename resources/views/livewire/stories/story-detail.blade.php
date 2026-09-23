<div class="p-4">
    {{-- 📱 TOP NAVIGATION BAR --}}
    <div
        class="p-4 border-b border-slate-50 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs z-50 mb-3">
        <a href="{{ url('/') }}" wire:navigate
            class="text-slate-600 font-bold text-xs flex items-center gap-1 transition bg-slate-100 p-2 rounded-xl">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <button wire:click="toggleLibrary"
            class="p-2 text-xs font-bold rounded-xl transition flex items-center justify-center border {{ $isSaved ? 'bg-brand-50 border-brand-200 text-brand-600' : 'bg-slate-100 border-slate-200 text-slate-600 hover:bg-slate-200' }}"
            title="{{ $isSaved ? 'Hapus dari Perpustakaan' : 'Tambah ke Perpustakaan' }}">
            @if ($isSaved)
                <i class="fa-solid fa-bookmark text-brand-600 text-sm"></i>
            @else
                <i class="fa-regular fa-bookmark text-slate-600 text-sm"></i>
            @endif
        </button>
    </div>

    {{-- 📖 COVER & METADATA UTAMA CERITA --}}
    <div
        class="flex flex-col items-center text-center bg-slate-50/60 border-b border-slate-100/90 animate-fade-in py-2">
        <div
            class="w-32 h-44 bg-slate-200 rounded-2xl overflow-hidden shadow-md mb-4 border border-slate-200/40 transform hover:scale-[1.02] transition-transform">
            @if ($story->cover_path)
                <img src="{{ asset('storage/' . $story->cover_path) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex flex-col items-center justify-center text-3xl bg-slate-100">
                    <span>📖</span>
                </div>
            @endif
        </div>

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
        <h1 class="text-lg font-black text-slate-800 leading-tight px-4">{{ $story->title }}</h1>
        <div class="flex gap-2 items-center text-brand-600 font-bold">
            <div
                class="w-8 h-8 rounded-full overflow-hidden bg-brand-100 flex-shrink-0 border-4 border-slate-50 shadow-inner flex items-center justify-center">
                <img src="{{ $story->penName->profile_photo_url }}" alt="{{ $story->penName->name }}"
                    class="w-full h-full object-cover">
            </div>
            <a href="{{ route('pen-name.show', $story->penName->slug) }}">
                <span>{{ $story->penName->name }}</span>
                <span><i class="fa-solid fa-chevron-right"></i></span>
            </a>
        </div>

        {{-- Tombol Simpan ke Perpustakaan --}}
        <div class="mt-3">
            <button wire:click="toggleLibrary"
                class="px-4 py-2 text-xs font-bold rounded-xl transition flex items-center gap-2 border shadow-3xs active:scale-95 {{ $isSaved ? 'bg-brand-50 border-brand-200 text-brand-600' : 'bg-slate-900 border-slate-900 text-white hover:bg-brand-600 hover:border-brand-600' }}">
                @if ($isSaved)
                    <i class="fa-solid fa-bookmark text-brand-600"></i> Tersimpan di Perpustakaan
                @else
                    <i class="fa-regular fa-bookmark"></i> + Tambah ke Perpustakaan
                @endif
            </button>
        </div>

        @if ($story->penName)
            {{-- <livewire:follow-button :pen-name="$story->penName" variant="compact" /> --}}
        @endif

        <div class="my-6 p-3 grid grid-cols-2 text-slate-500 divide-x-2 divide-slate-200 font-bold text-[20px]">
            <div class="px-6">
                <i class="fa-solid fa-eye text-brand-600 me-2"></i> {{ number_format_short($story->views_count) }}
            </div>

            <div class="px-6">
                <i class="fa-solid fa-book-open text-brand-600 me-2"></i>
                {{ number_format_short($story->chapters->where('status', 'published')->count()) }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 text-center my-3">
        <div wire:click="setTab('synopsis')"
            class="pb-3 text-sm font-bold border-b-2 transition {{ $tab === 'synopsis' ? 'border-brand-400 text-brand-400' : 'border-slate-300 text-slate-400 hover:text-slate-500' }}">
            Sinopsis (Blurb)
        </div>
        <div wire:click="setTab('chaptersList')"
            class="pb-3 text-sm font-bold border-b-2 transition {{ $tab === 'chaptersList' ? 'border-brand-400 text-brand-400' : 'border-slate-300 text-slate-400 hover:text-slate-500' }}">
            Daftar Bab
        </div>
    </div>
    {{-- 📝 BLOK SINOPSIS --}}
    @if ($tab === 'synopsis')
        <div class="border-b-2 border-slate-100">
            <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-0.5">Blurb</h2>
            <p
                class="text-xs text-slate-600 leading-relaxed bg-slate-50/30 p-3.5 rounded-2xl border border-slate-100/60 text-justify">
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
                <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Daftar Isi Bab
                    ({{ $chapters->count() }})</h2>
                <span class="text-[9px] font-bold text-slate-400 italic">Diperbarui Baru-baru Ini</span>
            </div>

            <div class="flex flex-col gap-2.5">
                @forelse($chapters as $index => $ch)
                    {{-- KUNCI UTAMA: Wajib pakai wire:navigate agar transisi pindah ke simulator baca berjalan instan --}}
                    <a href="{{ route('stories.chapter.read', [$story->slug, $ch->slug]) }}" wire:navigate
                        class="group flex items-center justify-between p-3.5 bg-white border border-slate-100 rounded-2xl hover:border-brand-500/40 hover:bg-brand-50/10 transition shadow-3xs">
                        <div class="flex flex-col gap-0.5">
                            <span
                                class="text-[9px] text-slate-400 font-extrabold uppercase group-hover:text-brand-600 transition">BAB
                                {{ $index + 1 }}</span>
                            <span
                                class="text-xs font-bold text-slate-800 group-hover:text-slate-900 transition">{{ $ch->title }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span
                                class="text-[10px] text-brand-600 font-black tracking-wide bg-brand-50 px-2 py-1 rounded-lg group-hover:bg-brand-600 group-hover:text-brand-600 transition">
                                🫘 {{ $ch->is_premium ? $ch->bean_price : 0 }}
                            </span>
                        </div>
                    </a>
                @empty
                    {{-- State Jika Isi Bab Masih Kosong / Belum Ada yang Published --}}
                    <div
                        class="text-center py-10 text-xs text-slate-400 font-bold italic bg-slate-50/50 rounded-2xl border border-dashed border-slate-200 p-6">
                        <span class="text-xl mb-1 block">📭</span>
                        Kisah ini belum merilis bab apa pun untuk publik, Bro.
                    </div>
                @endforelse
            </div>
        </div>

    @endif
    <div class="py-6 pb-4">
        @livewire('Story.story-review', ['storyId' => $story->id])
    </div>
</div>
