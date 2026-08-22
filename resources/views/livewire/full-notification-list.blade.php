<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 space-y-6">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Semua Notifikasi</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar pembaruan cerita dan aktivitas akun Anda.</p>
        </div>

        <div class="flex items-center gap-2">
            @if (Auth::user()->unreadNotifications->count() > 0)
                <button wire:click="markAllAsRead"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                    Tandai Semua Dibaca
                </button>
            @endif
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex border-b border-slate-200 gap-6 text-sm font-medium">
        <button wire:click="setFilter('all')"
            class="pb-3 border-b-2 transition {{ $filter === 'all' ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
            Semua
        </button>
        <button wire:click="setFilter('unread')"
            class="pb-3 border-b-2 transition flex items-center gap-2 {{ $filter === 'unread' ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
            <span>Belum Dibaca</span>
            @if (Auth::user()->unreadNotifications->count() > 0)
                <span class="bg-indigo-100 text-indigo-600 text-[10px] px-2 py-0.5 rounded-full font-bold">
                    {{ Auth::user()->unreadNotifications->count() }}
                </span>
            @endif
        </button>
    </div>

    <!-- Notification List -->
    <div class="space-y-3">
        @forelse($notifications as $item)
            @php $data = $item->data; @endphp
            <div
                class="bg-white rounded-xl border border-slate-200 p-4 transition-all duration-150 flex items-start gap-4 hover:border-slate-300 {{ $item->unread() ? 'bg-indigo-50/30 border-indigo-100' : '' }}">

                <div
                    class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <h2 class="text-sm font-semibold text-slate-900 truncate">
                            {{ $data['story_title'] ?? 'Notifikasi' }}
                        </h2>
                        <span class="text-xs text-slate-400 shrink-0">
                            {{ $item->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                        {{ $data['message'] ?? 'Ada pembaruan baru untuk Anda.' }}
                    </p>

                    <div class="mt-3 flex items-center gap-3">
                        @if (isset($data['story_slug']) && isset($data['chapter_slug']))
                            <button
                                wire:click="markAsRead('{{ $item->id }}', '{{ $data['story_slug'] }}', '{{ $data['chapter_slug'] }}')"
                                class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                                Baca Bab Sekarang
                            </button>
                        @endif

                        @if ($item->unread())
                            <button wire:click="markAsRead('{{ $item->id }}')"
                                class="text-xs text-slate-500 hover:text-slate-700">
                                Tandai Dibaca
                            </button>
                        @endif
                    </div>
                </div>

                <button wire:click="deleteNotification('{{ $item->id }}')" title="Hapus Notifikasi"
                    class="text-slate-300 hover:text-rose-500 transition p-1 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        @empty
            <div class="text-center py-12 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-sm text-slate-500">Tidak ada notifikasi
                    {{ $filter === 'unread' ? 'yang belum dibaca' : '' }}.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="pt-4">
        {{ $notifications->links() }}
    </div>
</div>
