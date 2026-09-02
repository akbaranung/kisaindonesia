<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <!-- Icon Lonceng & Badge Counter -->
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        @if ($unreadCount > 0)
            <span
                class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-amber-400 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Menu Notifikasi -->
    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 z-50 w-80 mt-2 bg-white rounded-md shadow-lg border border-gray-100 overflow-hidden"
        style="display: none;">

        <!-- Header Dropdown -->
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100">
            <span class="text-sm font-semibold text-gray-700">Notifikasi</span>
            @if ($unreadCount > 0)
                <button wire:click="markAllAsRead"
                    class="text-xs text-brand-600 hover:text-brand-800 font-medium focus:outline-none">
                    Tandai Semua Dibaca
                </button>
            @endif
        </div>

        <!-- Daftar Notifikasi -->
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $type = $data['type'] ?? 'story';
                    $isUnread = is_null($notification->read_at);
                @endphp

                <div wire:click="markAsRead('{{ $notification->id }}')"
                    class="p-3 text-sm cursor-pointer transition hover:bg-gray-50 flex items-start space-x-3 {{ $isUnread ? 'bg-brand-50/50' : 'text-gray-600' }}">

                    <!-- Icon Dinamis Berdasarkan Tipe -->
                    <div
                        class="w-7 h-7 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center shrink-0 mt-0.5">
                        @if ($type === 'follow')
                            <!-- Icon Follow User -->
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                            </svg>
                        @elseif($type === 'comment')
                            <!-- Icon Komentar -->
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"
                                    clip-rule="evenodd" />
                            </svg>
                        @elseif(in_array($type, ['monetize_request', 'monetize_status']))
                            <!-- Icon Monetisasi -->
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 2.016 1.5 3 4 3 2.5 0 2.5 1.016 2.5 2 0 .891-.502 1.666-1.324 2.146A4.53 4.53 0 0110 15.908V16a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C14.398 14.766 15 13.991 15 13c0-2.016-1.5-3-4-3-2.5 0-2.5-1.016-2.5-2 0-.891.502-1.666 1.324-2.146A4.53 4.53 0 0111 5.092V5z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <!-- Icon Buku / Cerita / Chapter Baru -->
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                            </svg>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-800 font-semibold truncate">
                            {{ $data['title'] ?? 'Notifikasi' }}
                        </p>
                        <p class="text-xs text-gray-600 line-clamp-2 mt-0.5">
                            {{ $data['message'] ?? 'Ada notifikasi baru.' }}
                        </p>
                        <span class="text-[10px] text-gray-400 mt-1 block">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <!-- Indicator Belum Dibaca -->
                    @if ($isUnread)
                        <span class="w-2 h-2 bg-brand-600 rounded-full shrink-0 mt-1.5"></span>
                    @endif
                </div>
            @empty
                <div class="p-6 text-center text-xs text-gray-400">
                    Belum ada notifikasi
                </div>
            @endforelse
        </div>
    </div>
</div>
