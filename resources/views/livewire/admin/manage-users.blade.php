<div>
    <!-- Header Page -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-100">Kelola Pengguna</h1>
            <p class="text-xs text-slate-400">Atur hak akses akun (Admin/User), status akun, dan buat pengguna baru.</p>
        </div>
        <button wire:click="openModal"
            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl transition shadow-lg shadow-emerald-900/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Tambah Pengguna
        </button>
    </div>

    <!-- Alert Flash Messages -->
    @if (session()->has('message'))
        <div
            class="mb-4 p-3 bg-emerald-950/80 border border-emerald-800/80 text-emerald-300 text-xs rounded-xl flex items-center justify-between">
            <span>✓ {{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div
            class="mb-4 p-3 bg-rose-950/80 border border-rose-800/80 text-rose-300 text-xs rounded-xl flex items-center justify-between">
            <span>⚠️ {{ session('error') }}</span>
        </div>
    @endif

    <!-- Search & Table Card -->
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900 p-5 shadow-sm">

        <!-- Filter Bar -->
        <div class="mb-4 flex flex-col sm:flex-row gap-3 justify-between items-center">
            <!-- Search -->
            <div class="w-full sm:w-72">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email..."
                    class="w-full px-3.5 py-2 bg-slate-800/60 border border-slate-700/80 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
            </div>

            <!-- Role & Status Filter -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select wire:model.live="roleFilter"
                    class="px-3 py-2 bg-slate-800/60 border border-slate-700/80 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-emerald-500">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="user">User (Reader & Author)</option>
                </select>

                <select wire:model.live="statusFilter"
                    class="px-3 py-2 bg-slate-800/60 border border-slate-700/80 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Diblokir / Nonaktif</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-400">
                <thead
                    class="bg-slate-800/40 text-slate-300 font-bold uppercase tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="p-3">Pengguna</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Karya (Cerita)</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Bergabung</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-800/20 transition">
                            <!-- User Info -->
                            <td class="p-3">
                                <div class="font-bold text-slate-200">{{ $user->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $user->email }}</div>
                            </td>

                            <!-- Role Badge -->
                            <td class="p-3">
                                @if ($user->role === 'admin')
                                    <span
                                        class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-bold rounded-lg text-[10px]">
                                        ADMIN
                                    </span>
                                @else
                                    <span
                                        class="px-2.5 py-1 bg-blue-500/10 border border-blue-500/30 text-blue-400 font-bold rounded-lg text-[10px]">
                                        USER
                                    </span>
                                @endif
                            </td>

                            <!-- Total Cerita -->
                            <td class="p-3">
                                <span class="text-slate-300 font-medium">{{ $user->stories_count ?? 0 }} Cerita</span>
                            </td>

                            <!-- Status Badge -->
                            <td class="p-3">
                                @if ($user->is_active)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold rounded-lg text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-500/10 border border-rose-500/30 text-rose-400 font-bold rounded-lg text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>

                            <!-- Tanggal Mendaftar -->
                            <td class="p-3 text-slate-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="p-3 text-right flex items-center justify-end gap-1.5">
                                <!-- Toggle Status Button -->
                                <button wire:click="toggleStatus({{ $user->id }})"
                                    class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition"
                                    title="{{ $user->is_active ? 'Nonaktifkan User' : 'Aktifkan User' }}">
                                    @if ($user->is_active)
                                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </button>

                                <!-- Edit -->
                                <button wire:click="edit({{ $user->id }})"
                                    class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition"
                                    title="Edit User">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <button wire:click="openAccessModal({{ $user->id }})"
                                    class="px-2.5 py-1.5 bg-brand-600/10 border border-brand-500/30 hover:bg-brand-600/20 text-brand-400 font-semibold rounded-lg transition text-[11px] flex items-center gap-1"
                                    title="Kelola Akses Menu">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    <span>Akses Menu</span>
                                </button>

                                <!-- Delete -->
                                <button wire:click="delete({{ $user->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus user ini?"
                                    class="p-1.5 bg-rose-950/80 hover:bg-rose-900 border border-rose-800/60 text-rose-300 rounded-lg transition"
                                    title="Hapus User">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-500">Data pengguna tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links('vendor.livewire.custom-pagination') }}
        </div>
    </div>

    <!-- MODAL FORM (TAMBAH / EDIT USER) -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4">
            <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-3">
                    <h3 class="text-sm font-bold text-slate-100">
                        {{ $userId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-200">✕</button>
                </div>

                <form wire:submit.prevent="store" class="space-y-4">
                    <!-- Nama -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" placeholder="Nama pengguna"
                            class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                        @error('name')
                            <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Email</label>
                        <input type="email" wire:model="email" placeholder="email@domain.com"
                            class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                        @error('email')
                            <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">
                            Password {{ $userId ? '(Kosongkan jika tidak diganti)' : '' }}
                        </label>
                        <input type="password" wire:model="password" placeholder="••••••••"
                            class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                        @error('password')
                            <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Role & Status Grid -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Role</label>
                            <select wire:model="role"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                                <option value="user">User (Reader & Author)</option>
                                <option value="admin">Admin</option>
                            </select>
                            @error('role')
                                <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Status Akun</label>
                            <select wire:model="is_active"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif / Blokir</option>
                            </select>
                            @error('is_active')
                                <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showAccessModal && $selectedUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div
                class="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full p-6 space-y-5 shadow-2xl text-slate-200">

                {{-- Header Modal --}}
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div>
                        <h3 class="font-bold text-base text-slate-100">Akses Menu User</h3>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Pengaturan menu untuk: <span
                                class="text-brand-400 font-semibold">{{ $selectedUser->name }}</span>
                        </p>
                    </div>
                    <button wire:click="closeAccessModal" class="text-slate-400 hover:text-slate-200">
                        ✕
                    </button>
                </div>

                {{-- List Checkbox Menu --}}
                <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                    @forelse($allMenus as $menu)
                        <label
                            class="flex items-center justify-between p-3 bg-slate-800/50 hover:bg-slate-800 rounded-xl border border-slate-700/40 cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" wire:model="selectedMenus" value="{{ $menu->id }}"
                                    @checked(in_array($menu->id, $selectedMenus))
                                    class="w-4 h-4 rounded border-slate-600 bg-slate-900 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-slate-900 cursor-pointer">
                                <div>
                                    <div class="font-semibold text-xs text-slate-200">{{ $menu->name }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono">{{ $menu->route_name }}</div>
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="text-center py-4 text-xs text-slate-500">
                            Belum ada data menu di sistem.
                        </div>
                    @endforelse
                </div>

                {{-- Footer Action Buttons --}}
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
                    <button type="button" wire:click="closeAccessModal"
                        class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition">
                        Batal
                    </button>

                    <button type="button" wire:click="saveMenuAccess" wire:loading.attr="disabled"
                        wire:target="saveMenuAccess"
                        class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl transition disabled:opacity-50 flex items-center gap-1.5">

                        <span wire:loading.remove wire:target="saveMenuAccess">Simpan Akses</span>

                        <span wire:loading wire:target="saveMenuAccess" class="flex items-center gap-1.5">
                            <svg class="animate-spin w-3 h-3 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>Menyimpan...</span>
                        </span>
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>
