<div class="min-h-screen bg-slate-50/50 pb-12">
    @if ($action === 'list')
        <header class="flex items-center justify-between w-full pb-5 border-b border-slate-100 mb-6">
            <div class="flex flex-col">
                <span class="text-[10px] font-black text-brand-600 uppercase tracking-widest">Writer Studio</span>
                <h1 class="text-xl font-black text-slate-800 tracking-tight">Cerita Saya</h1>
            </div>

            <button wire:click="switchAction('create')"
                class="bg-slate-900 hover:bg-brand-600 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-xs flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7-7H5" />
                </svg>
                Cerita Baru
            </button>
        </header>

        @if (session()->has('success'))
            <div
                class="mb-5 p-4 bg-brand-500 text-white text-xs font-bold rounded-2xl shadow-lg shadow-emerald-100 animate-fade-in">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            {{-- Filter & Search --}}
            <div class="flex items-center gap-3">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul..."
                    class="text-xs p-2.5 rounded-xl border border-slate-200 w-60 focus:ring-brand-500 focus:border-brand-500">

                <select wire:model.live="filterMonetization"
                    class="text-xs p-2.5 rounded-xl border border-slate-200 w-35 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">Semua Tipe</option>
                    <option value="free">Gratis</option>
                    <option value="premium">Premium</option>
                </select>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            @forelse($myStories as $story)
                @php
                    // Cek pengajuan terakhir untuk menentukan status tombol
                    $latestRequest = $story->premiumRequests->first();
                    $isPending = $latestRequest && $latestRequest->status === 'pending';
                @endphp
                <div class="bg-white border border-slate-100 rounded-3xl p-4 shadow-2xs">
                    <div class="flex gap-4 items-center">
                        <div
                            class="w-16 h-22 bg-slate-50 rounded-2xl overflow-hidden flex-shrink-0 border border-slate-100 flex items-center justify-center">
                            @if ($story->cover_path)
                                <img src="{{ asset('storage/' . $story->cover_path) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor"
                                    stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                </svg>
                            @endif
                        </div>

                        <div class="flex-2 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md">
                                    <livewire:update-story-status :story="$story" :key="'story-status-' . $story->id" />
                                </span>
                                @if ($story->monetization_type === 'premium')
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-brand-100 text-brand-800 text-[10px] font-extrabold uppercase">
                                        ⭐ Premium
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold uppercase">
                                        Gratis
                                    </span>
                                @endif
                                <p class="text-[10px] font-bold text-slate-400">{{ $story->genre->name }}</p>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 truncate">{{ $story->title }}</h3>

                            <div class="flex items-center gap-3 mt-2 text-[11px] font-semibold text-slate-400">
                                <span class="flex items-center gap-0.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    {{ $story->chapters->count() }} Bab
                                    <span class="mx-1">•</span>
                                    Dibuat {{ $story->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        {{-- <div class="flex items-center gap-2">

                        </div> --}}
                    </div>

                    <div class="block mt-5">
                        <div class="flex items-center gap-2 self-end sm:self-center">

                            {{-- Status 1: Sudah Premium --}}
                            @if ($story->monetization_type === 'premium')
                                <span
                                    class="px-3 py-1.5 rounded-xl bg-brand-50 text-brand-700 border border-brand-200 text-xs font-bold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    Monetisasi Aktif
                                </span>

                                {{-- Status 2: Sedang Dalam Peninjauan Admin --}}
                            @elseif($isPending)
                                <span
                                    class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Sedang Ditinjau Admin
                                </span>

                                {{-- Status 3: Masih Gratis -> Tampilkan Tombol Ajukan Premium --}}
                            @else
                                <a href="{{ route('monetization.apply', ['story_id' => $story->id]) }}"
                                    class="px-3.5 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-600 text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5">
                                    <span>Ajukan Premium</span>
                                </a>
                            @endif
                            <a href="{{ route('stories.chapters', $story->id) }}" wire:navigate
                                class="px-3.5 py-1.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-xs font-bold transition-all text-white">
                                Open
                            </a>

                            <button wire:click="editStory({{ $story->id }})"
                                class="p-2.5 bg-slate-50 hover:bg-brand-50 hover:text-brand-600 rounded-xl text-xs text-slate-400 transition flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                Ubah
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-3xl border border-slate-100 border-dashed">
                    <p class="text-sm font-medium text-slate-400">Belum ada cerita yang dibuat.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $myStories->links() }}
        </div>
    @else
        @include('stories.create-stories')
    @endif

</div>
