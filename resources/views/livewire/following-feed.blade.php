<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 space-y-6">
    <!-- Header Halaman -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Feed Penulis</h1>
            <p class="text-sm text-slate-500 mt-1">Update bab terbaru dari penulis yang Anda ikuti.</p>
        </div>
    </div>

    @if (empty($followedPenNameIds))
        <!-- State 1: Belum Follow Siapapun -->
        <div class="text-center py-12 px-6 bg-slate-50/60 rounded-2xl border-2 border-dashed border-slate-200">
            <div class="w-14 h-14 bg-brand-50 text-brand-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <h3 class="font-semibold text-lg text-slate-800">Anda Belum Mengikuti Penulis</h3>
            <p class="text-sm text-slate-500 max-w-sm mx-auto mt-1 mb-6 leading-relaxed">
                Temukan penulis favorit Anda dan ikuti mereka untuk mendapatkan pembaruan bab terbaru di sini.
            </p>
            <a href="{{ route('home') }}"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-brand-600 text-white hover:bg-brand-700 active:bg-brand-800 shadow-sm transition-all duration-150">
                Jelajahi Cerita
            </a>
        </div>
    @elseif($chapters->isEmpty())
        <!-- State 2: Sudah Follow Tapi Belum Ada Update -->
        <div class="text-center py-12 px-6 bg-slate-50 rounded-2xl border border-slate-100">
            <p class="text-slate-500 text-sm">Belum ada bab baru yang dirilis dari penulis yang Anda ikuti.</p>
        </div>
    @else
        <!-- List Bab Terbaru -->
        <div class="space-y-4">
            @foreach ($chapters as $chapter)
                <div
                    class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 shadow-sm hover:border-slate-200 transition">
                    {{-- Header: Info Nama Pena --}}
                    <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-50">
                        <a href="{{ route('pen-name.show', $chapter->story->penName->slug) }}"
                            class="flex items-center gap-3 group">
                            <div
                                class="w-10 h-10 rounded-full bg-brand-100 overflow-hidden flex-shrink-0 border border-slate-100 flex items-center justify-center font-bold text-brand-600 text-sm">
                                @if ($chapter->story->penName->avatar)
                                    <img src="{{ asset('storage/' . $chapter->story->penName->avatar) }}"
                                        alt="{{ $chapter->story->penName->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($chapter->story->penName->name, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-brand-600 transition">
                                    {{ $chapter->story->penName->name }}
                                </h4>
                                <span class="text-[10px] text-slate-400">
                                    Menerbitkan bab baru • {{ $chapter->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </a>


                    </div>

                    {{-- Content: Detail Bab & Cerita --}}
                    <div class="flex gap-4">
                        <a href="{{ route('stories.chapters', $chapter->story->slug ?? $chapter->story->id) }}"
                            class="w-16 h-24 md:w-20 md:h-28 bg-slate-100 rounded-lg overflow-hidden flex-shrink-0">
                            @if ($chapter->story->cover_path)
                                <img src="{{ asset('storage/' . $chapter->story->cover_path) }}"
                                    alt="{{ $chapter->story->title }}" class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center p-2 bg-brand-50 text-brand-300 font-bold text-[10px] text-center">
                                    {{ $chapter->story->title }}
                                </div>
                            @endif
                        </a>

                        <div class="flex-1 flex flex-col justify-between py-0.5">
                            <div>
                                <span class="text-[10px] font-semibold text-brand-600 bg-brand-50 px-2 py-0.5 rounded">
                                    {{ $chapter->story->title }}
                                </span>

                                <a href="{{ route('stories.chapter.read', [$chapter->story->slug, $chapter->slug]) }}"
                                    class="block text-sm font-bold text-slate-800 hover:text-brand-600 transition mt-1">
                                    Bab {{ $chapter->order }}: {{ $chapter->title }}
                                </a>

                                @if ($chapter->synopsis)
                                    <p class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed">
                                        {{ $chapter->synopsis }}
                                    </p>
                                @endif
                            </div>

                            <div class="text-[11px] text-slate-400 font-medium mt-2">
                                Dibaca {{ number_format($chapter->views_count ?? 0) }} kali
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-4">
            {{ $chapters->links() }}
        </div>
    @endif
</div>
