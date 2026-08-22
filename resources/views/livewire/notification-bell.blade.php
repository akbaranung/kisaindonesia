<div class="relative" x-data="{ open: false }">
    <!-- Icon Lonceng Header -->
    <button @click="open = !open"
        class="relative p-2 rounded-full text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition duration-150 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        @if ($unreadCount > 0)
            <span
                class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white ring-2 ring-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Container -->
    <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 sm:w-96 rounded-xl bg-white shadow-lg border border-slate-200 z-50 overflow-hidden"
        style="display: none;">

        <!-- Header -->
        <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">Notifikasi</h3>
            @if ($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                    Tandai Semua Dibaca
                </button>
            @endif
        </div>

        <!-- Notification List -->
        <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
            @forelse($notifications as $item)
                @php $data = $item->data; @endphp
                <button
                    wire:click="markAsRead('{{ $item->id }}', '{{ $data['story_slug'] ?? '' }}', '{{ $data['chapter_slug'] ?? '' }}')"
                    class="w-full text-left p-3.5 hover:bg-slate-50 transition flex items-start gap-3 {{ $item->unread() ? 'bg-indigo-50/40' : '' }}">

                    <div
                        class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-900 truncate">
                            {{ $data['story_title'] ?? 'Cerita' }}
                        </p>
                        <p class="text-xs text-slate-600 line-clamp-2 mt-0.5">
                            {{ $data['message'] ?? 'Ada bab baru rilis.' }}
                        </p>
                        <span class="text-[10px] text-slate-400 mt-1 block">
                            {{ $item->created_at->diffForHumans() }}
                        </span>
                    </div>

                    @if ($item->unread())
                        <span class="w-2 h-2 bg-indigo-600 rounded-full shrink-0 mt-2"></span>
                    @endif
                </button>
            @empty
                <div class="p-6 text-center text-xs text-slate-500">
                    Belum ada notifikasi baru.
                </div>
            @endforelse
        </div>

        <!-- Footer Link ke Full Page -->
        <div class="p-2.5 bg-slate-50 border-t border-slate-200 text-center">
            <a href="{{ route('notifications.index') }}"
                class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition block py-1">
                Lihat Semua Notifikasi
            </a>
        </div>
    </div>
</div>
