<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 space-y-6">
    <!-- Header Halaman -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Feed Penulis</h1>
            <p class="text-sm text-slate-500 mt-1">Update bab terbaru dari penulis yang Anda ikuti.</p>
        </div>
    </div>

    @if (empty($followingIds))
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
                    class="bg-white rounded-xl border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all duration-200 overflow-hidden">
                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row gap-4 sm:items-center justify-between">

                        <!-- Info Cerita & Bab -->
                        <div class="flex gap-4 items-start">
                            <!-- Cover Cerita -->
                            <a href="{{ route('stories.chapters', $chapter->story->slug) }}" class="shrink-0 group">
                                <img src="{{ $chapter->story->cover_path ? asset('storage/' . $chapter->story->cover_path) : asset('images/default-cover.jpg') }}"
                                    alt="{{ $chapter->story->title }}"
                                    class="w-16 h-24 object-cover rounded-lg shadow-sm border border-slate-100 group-hover:opacity-90 transition">
                            </a>

                            <!-- Detail Konten -->
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <span class="font-medium text-slate-800">{{ $chapter->story->user->name }}</span>
                                    <span>•</span>
                                    <span>{{ $chapter->created_at->diffForHumans() }}</span>
                                </div>

                                <h2
                                    class="font-semibold text-base text-slate-900 hover:text-brand-600 transition line-clamp-1">
                                    <a
                                        href="{{ route('stories.chapter.read', [$chapter->story->slug, $chapter->slug]) }}">
                                        {{ $chapter->title }}
                                    </a>
                                </h2>

                                <p class="text-xs text-slate-600 line-clamp-1">
                                    {{ $chapter->story->title }} — <span class="font-semibold text-slate-700">Bab
                                        {{ $chapter->order_number }}</span>
                                </p>

                                <!-- Tag Status & Tipe -->
                                <div class="pt-1 flex items-center gap-2">
                                    @if ($chapter->is_premium)
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span>⚡</span> {{ $chapter->coins }} Kisa Beans
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Gratis
                                        </span>
                                    @endif

                                    <span
                                        class="text-[10px] font-medium text-slate-400 uppercase tracking-wider bg-slate-100 px-1.5 py-0.5 rounded">
                                        {{ $chapter->type }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Action -->
                        <div
                            class="w-full sm:w-auto flex justify-end shrink-0 pt-2 sm:pt-0 border-t border-slate-100 sm:border-0">
                            <a href="{{ route('stories.chapter.read', [$chapter->story->slug, $chapter->slug]) }}"
                                class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 text-xs font-semibold rounded-lg border border-brand-600 text-brand-600 hover:bg-brand-50 active:bg-brand-100 transition-all duration-150">
                                Baca Bab
                            </a>
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
