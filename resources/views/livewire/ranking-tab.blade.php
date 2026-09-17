<section class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                Peringkat Cerita
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">10 Cerita teratas dengan pembaca terbanyak saat ini.</p>
        </div>

        <!-- Navigasi Tab Gratis & Premium -->
        <div class="grid grid-cols-2 text-center">
            <div wire:click="setRankingTab('free')"
                class="pb-3 text-sm font-bold border-b-2 transition {{ $rankingTab === 'free' ? 'border-brand-400 text-brand-400' : 'border-transparent text-slate-400 hover:text-slate-500' }}">
                Gratis
            </div>
            <div wire:click="setRankingTab('premium')"
                class="pb-3 text-sm font-bold border-b-2 transition {{ $rankingTab === 'premium' ? 'border-brand-400 text-brand-400' : 'border-transparent text-slate-400 hover:text-slate-500' }}">
                Premium
            </div>
        </div>
    </div>

    <!-- Daftar Peringkat (List 1 - 10) -->
    <div class="overflow-hidden">
        <div class="divide-y divide-slate-800/60">
            @forelse($topStories as $index => $story)
                <a href="{{ route('stories.read', $story->slug) }}"
                    class="p-4 flex items-center gap-4 transition group">

                    <!-- Nomor Peringkat (Ranking Badge) -->
                    <div class="flex-shrink-0 w-8 text-center">
                        @if ($index == 0)
                            <span class="text-xl font-black text-amber-400">#1</span>
                        @elseif($index == 1)
                            <span class="text-lg font-bold text-slate-300">#2</span>
                        @elseif($index == 2)
                            <span class="text-lg font-bold text-amber-600">#3</span>
                        @else
                            <span class="text-sm font-semibold text-slate-500">#{{ $index + 1 }}</span>
                        @endif
                    </div>

                    <!-- Cover Gambar -->
                    <div
                        class="w-12 h-16 bg-slate-800 rounded-lg overflow-hidden flex-shrink-0 border border-slate-700/50">
                        @if ($story->cover_path)
                            <img src="{{ asset('storage/' . $story->cover_path) }}" alt="{{ $story->title }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-600">
                                No Cover</div>
                        @endif
                    </div>

                    <!-- Detail Cerita -->
                    <div class="flex-1 min-w-0">
                        <h3
                            class="text-xs sm:text-sm font-bold text-slate-500 group-hover:text-brand-400 transition truncate">
                            {{ $story->title }}
                        </h3>
                        <p class="text-[11px] text-slate-400 truncate mt-0.5">
                            {{ '@' . $story->author->name ?? 'Anonim' }}
                        </p>
                        <span
                            class="px-1 py-0.5 rounded-md text-[8px] font-bold bg-white text-black border border-white">
                            <i class="fa-regular fa-eye"></i>
                            {{ number_format_short($story->views_count ?? 0) }}
                        </span>
                    </div>

                    <!-- Statistik / Total Views -->
                    <div class="text-right flex-shrink-0 text-xs text-slate-400">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>

                </a>
            @empty
                <div class="p-8 text-center text-xs text-slate-500">
                    Belum ada peringkat cerita untuk kategori ini.
                </div>
            @endforelse
        </div>
    </div>
</section>
