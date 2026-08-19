<div>
    {{-- 📱 TOP NAVIGATION BAR --}}
    <div
        class="p-4 border-b border-slate-50 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xs z-50">
        <a href="{{ url('/') }}" wire:navigate
            class="text-slate-600 font-bold text-xs flex items-center gap-1 hover:text-emerald-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Kisah</span>
        <div class="w-10"></div> {{-- Penyeimbang tata letak flex --}}
    </div>

    {{-- 📖 COVER & METADATA UTAMA CERITA --}}
    <div class="flex flex-col items-center text-center bg-slate-50/60 border-b border-slate-100/80 animate-fade-in">
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
        <span
            class="text-[9px] font-extrabold px-2 py-0.5 rounded-md text-white tracking-wider {{ $story->type === 'chat' ? 'bg-emerald-600' : 'bg-slate-800' }} uppercase mb-2">
            {{ $story->type === 'chat' ? '💬 Chat Fic' : '📝 Novel Regular' }}
        </span>

        {{-- Judul & Penulis --}}
        <h1 class="text-lg font-black text-slate-800 leading-tight px-4">{{ $story->title }}</h1>
        <p class="text-xs text-slate-400 font-bold mt-1">Karya: <span
                class="text-slate-500">{{ $story->author->name ?? 'Penulis Kisa' }}</span></p>

        <livewire:follow-button :author-id="$story->author->id" variant="compact" />

        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>

            {{-- Format angka (contoh: 1,2rb / 1.2k) --}}
            <span>{{ number_format_short($story->views_count) }} dibaca</span>

        </div>
    </div>

    {{-- 📝 BLOK SINOPSIS --}}
    <div class="my-3 border-b border-slate-50">
        <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-0.5">Sinopsis Cerita</h2>
        <p
            class="text-xs text-slate-600 leading-relaxed bg-slate-50/30 p-3.5 rounded-2xl border border-slate-100/60 text-justify">
            {{ $story->synopsis ?? 'Belum ada sinopsis resmi yang dibagikan oleh penulis untuk kisah ini, Bro.' }}
        </p>
    </div>

    @if ($lastReadChapter)
        <div class="pb-2 animate-fade-in">
            <a href="{{ route('stories.chapter.read', [$story->slug, $lastReadChapter->slug]) }}" wire:navigate
                class="flex items-center justify-center gap-2 w-full p-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-2xl shadow-md transition transform hover:scale-[1.01]">
                ⚡ LANJUTKAN MEMBACA: {{ Str::limit($lastReadChapter->title, 20) }}
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
                    class="group flex items-center justify-between p-3.5 bg-white border border-slate-100 rounded-2xl hover:border-emerald-500/40 hover:bg-emerald-50/10 transition shadow-3xs">
                    <div class="flex flex-col gap-0.5">
                        <span
                            class="text-[9px] text-slate-400 font-extrabold uppercase group-hover:text-emerald-600 transition">BAB
                            {{ $index + 1 }}</span>
                        <span
                            class="text-xs font-bold text-slate-800 group-hover:text-slate-900 transition">{{ $ch->title }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span
                            class="text-[10px] text-emerald-600 font-black tracking-wide bg-emerald-50 px-2 py-1 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition">
                            ⚡{{ $ch->bean_price }}
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
    <div class="py-6 pb-4">
        @livewire('Story.story-review', ['storyId' => $story->id])
    </div>
</div>
