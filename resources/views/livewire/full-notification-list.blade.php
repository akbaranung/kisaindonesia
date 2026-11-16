<div
    class="max-w-4xl mx-auto py-8 px-4 sm:px-6 space-y-6 text-slate-800 dark:text-slate-100 transition-colors duration-200">
    <!-- Header & Action Bar -->
    <div
        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-5">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Semua Notifikasi</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Daftar pembaruan cerita, status publikasi, dan
                aktivitas akun Anda.</p>
        </div>

        <div class="flex items-center gap-2">
            @if (Auth::user()->unreadNotifications->count() > 0)
                <button wire:click="markAllAsRead"
                    class="inline-flex items-center px-3.5 py-2 text-xs font-bold rounded-xl text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/60 hover:bg-brand-100 dark:hover:bg-brand-900/50 transition border border-brand-200 dark:border-brand-800/60">
                    Tandai Semua Dibaca
                </button>
            @endif
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex border-b border-slate-100 dark:border-slate-800 gap-6 text-xs font-bold">
        <button wire:click="setFilter('all')"
            class="pb-3 border-b-2 transition {{ $filter === 'all' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }}">
            Semua
        </button>
        <button wire:click="setFilter('unread')"
            class="pb-3 border-b-2 transition flex items-center gap-2 {{ $filter === 'unread' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }}">
            <span>Belum Dibaca</span>
            @if (Auth::user()->unreadNotifications->count() > 0)
                <span
                    class="bg-brand-100 dark:bg-brand-950 text-brand-600 dark:text-brand-400 text-[10px] px-2 py-0.5 rounded-full font-black border border-brand-200 dark:border-brand-800">
                    {{ Auth::user()->unreadNotifications->count() }}
                </span>
            @endif
        </button>
    </div>

    <!-- Notification List -->
    <div class="space-y-3">
        @forelse($notifications as $item)
            @php
                $data = $item->data;
                $type = $data['type'] ?? 'story';
            @endphp
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-4 transition-all duration-150 flex items-start gap-4 hover:border-slate-200 dark:hover:border-slate-700 {{ $item->unread() ? 'bg-brand-50/30 dark:bg-brand-950/20 border-brand-200/60 dark:border-brand-900/40' : '' }}">

                <!-- Icon Dinamis -->
                <div
                    class="w-10 h-10 rounded-2xl bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-100 dark:border-brand-900/50 flex items-center justify-center shrink-0 mt-0.5">
                    @if ($type === 'publish_status')
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    @elseif ($type === 'follow')
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                        </svg>
                    @elseif ($type === 'comment')
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"
                                clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                        </svg>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <h2
                            class="text-xs font-black text-slate-800 dark:text-slate-100 uppercase tracking-wider truncate">
                            {{ $data['title'] ?? ($data['story_title'] ?? 'Notifikasi') }}
                        </h2>
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium shrink-0">
                            {{ $item->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                        {{ $data['message'] ?? 'Ada pembaruan baru untuk Anda.' }}
                    </p>

                    <!-- Tampilkan Alasan Penolakan Jika Ada (Khusus Status Publish Ditolak) -->
                    @if (!empty($data['rejection_reason']))
                        <div
                            class="mt-2.5 p-2.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-[11px] text-rose-600 dark:text-rose-400">
                            <strong class="font-bold block mb-0.5">Catatan Admin:</strong>
                            <p class="leading-relaxed">{{ $data['rejection_reason'] }}</p>
                        </div>
                    @endif

                    <div class="mt-3 flex items-center gap-3">
                        @if (isset($data['story_slug']) && isset($data['chapter_slug']))
                            <button
                                wire:click="markAsRead('{{ $item->id }}', '{{ $data['story_slug'] }}', '{{ $data['chapter_slug'] }}')"
                                class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline transition">
                                Baca Bab Sekarang
                            </button>
                        @endif

                        @if ($item->unread())
                            <button wire:click="markAsRead('{{ $item->id }}')"
                                class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition font-semibold">
                                Tandai Dibaca
                            </button>
                        @endif
                    </div>
                </div>

                <button wire:click="deleteNotification('{{ $item->id }}')" title="Hapus Notifikasi"
                    class="text-slate-300 dark:text-slate-600 hover:text-rose-500 dark:hover:text-rose-400 transition p-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        @empty
            <div
                class="text-center py-12 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 border-dashed">
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500">Tidak ada notifikasi
                    {{ $filter === 'unread' ? 'yang belum dibaca' : '' }}.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="pt-4">
        {{ $notifications->links() }}
    </div>
</div>
