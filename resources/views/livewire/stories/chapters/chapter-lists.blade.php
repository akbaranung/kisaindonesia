<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-sm font-black text-slate-700 uppercase tracking-wider">Daftar Bab
            ({{ $story->chapters->count() }})</h3>
        <button type="button" wire:click="openCreateModal"
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] px-3 py-1.5 rounded-xl transition flex items-center gap-1">
            + Bab Baru
        </button>
        {{-- <button type="button" wire:click="switchChapterAction('editor')"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] px-3 py-1.5 rounded-xl transition flex items-center gap-1">
                    + Bab Baru
                </button> --}}
    </div>

    @if (session()->has('success'))
        <div
            class="mb-5 p-4 bg-slate-900 text-white text-[11px] font-bold rounded-2xl animate-fade-in flex items-center gap-2">
            <span class="text-emerald-400">✔</span> {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-3">
        @forelse($story->chapters as $index => $chapter)
            <div
                class="group bg-white border border-slate-100 rounded-2xl p-4 shadow-2xs hover:border-emerald-200 transition-all flex items-center gap-4">
                <div
                    class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-xs font-black text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition">
                    {{ $index + 1 }}
                </div>
                <div class="flex-1 cursor-pointer" wire:click="viewChapter({{ $chapter->id }})">
                    <h4 class="text-sm font-bold text-slate-800">{{ $chapter->title }}</h4>
                    <div class="flex items-center gap-2 mt-1">
                        <span
                            class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-100 text-slate-500">
                            {{ $chapter->type === 'chat' ? 'Chat' : 'Regular' }}
                        </span>
                        <span class="text-[10px] font-bold text-slate-300">Dibuat
                            {{ $chapter->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                <button wire:click="editChapter({{ $chapter->id }})"
                    class="p-2 bg-slate-50 text-slate-400 hover:bg-emerald-600 hover:text-white rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                </button>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-[2rem] border border-dashed border-slate-200">
                <div class="text-3xl mb-2">✍️</div>
                <p class="text-xs font-bold text-slate-400">Belum ada bab. Mulai tulis bab pertamamu!</p>
            </div>
        @endforelse
    </div>
</div>
