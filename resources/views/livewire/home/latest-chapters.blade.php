<div class="w-full px-3 py-2">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center space-x-2">
            <span class="w-2 h-5 bg-brand-500 rounded-full inline-block"></span>
            <h2 class="text-base font-bold text-white tracking-tight">Bab Terbaru</h2>
        </div>
        <a href="#"
            class="text-xs font-semibold text-brand-400 hover:text-brand-300 transition flex items-center space-x-1">
            <span>Lihat Semua</span>
            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd" />
            </svg>
        </a>
    </div>

    <!-- Item List / Card List -->
    @if ($latestChapters->isNotEmpty())
        <div class="grid grid-cols-1 gap-2.5">
            @foreach ($latestChapters as $chapter)
                <a href="{{ route('stories.chapter.read', [$chapter->story->slug, $chapter->slug]) }}"
                    class="flex items-center p-2.5 rounded-xl bg-slate-900/90 border border-slate-800/80 active:bg-slate-800 transition space-x-3 group">

                    <!-- Cover Image Thumbnail -->
                    <div
                        class="relative w-14 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-slate-950 border border-slate-800">
                        <img src="{{ asset('storage/' . $chapter->story->cover_path) ?? asset('images/default-cover.jpg') }}"
                            alt="{{ $chapter->story->title }}"
                            class="w-full h-full object-cover group-active:scale-105 transition duration-200">

                        @if ($chapter->is_premium)
                            <span
                                class="absolute top-1 left-1 bg-amber-500 text-slate-950 text-[8px] font-black px-1 rounded shadow">
                                💎
                            </span>
                        @endif
                    </div>

                    <!-- Meta Data Details -->
                    <div class="flex-1 min-w-0 space-y-1">
                        <!-- Genre & Author -->
                        <div class="flex items-center space-x-1.5 text-[10px] text-slate-400">
                            <span class="text-brand-400 font-semibold truncate max-w-[100px]">
                                {{ $chapter->story->penName->name ?? 'Anonim' }}
                            </span>
                            <span>•</span>
                            <span class="bg-slate-800 px-1.5 py-0.2 rounded text-[9px]">
                                {{ $chapter->story->genre->name ?? 'Umum' }}
                            </span>
                        </div>

                        <!-- Judul Cerita Utama -->
                        <h3 class="text-xs font-bold text-white leading-tight truncate group-hover:text-brand-300">
                            {{ $chapter->story->title }}
                        </h3>

                        <!-- Badge Bab Terbaru & Waktu Rilis -->
                        <div class="flex items-center justify-between pt-0.5">
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-200 border border-slate-700/60">
                                Bab {{ $chapter->number }}
                                @if ($chapter->title)
                                    : {{ Str::limit($chapter->title, 15) }}
                                @endif
                            </span>

                            <span class="text-[10px] text-slate-500">
                                {{ $chapter->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                </a>
            @endforeach
        </div>
    @else
        <div class="bg-slate-950 border border-slate-800/80 rounded-xl p-6 text-center text-xs text-slate-500">
            Belum ada bab terbaru saat ini.
        </div>
    @endif
</div>
