<div>
    <!-- Header Page -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-100">Kelola Genre</h1>
            <p class="text-xs text-slate-400">Atur daftar genre cerita yang tersedia di platform.</p>
        </div>
        <button wire:click="openModal"
            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl transition shadow-lg shadow-emerald-900/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Genre
        </button>
    </div>

    <!-- Alert Flash Message -->
    @if (session()->has('message'))
        <div
            class="mb-4 p-3 bg-emerald-950/80 border border-emerald-800/80 text-emerald-300 text-xs rounded-xl flex items-center justify-between">
            <span>✓ {{ session('message') }}</span>
        </div>
    @endif

    <!-- Search & Table Card -->
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900 p-5 shadow-sm">

        <!-- Search Filter -->
        <div class="mb-4 max-w-xs">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari genre..."
                class="w-full px-3.5 py-2 bg-slate-800/60 border border-slate-700/80 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-400">
                <thead
                    class="bg-slate-800/40 text-slate-300 font-bold uppercase tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="p-3 w-16">#</th>
                        <th class="p-3">Nama Genre</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($genres as $index => $genre)
                        <tr class="hover:bg-slate-800/20 transition">
                            <td class="p-3 text-slate-500">{{ $genres->firstItem() + $index }}</td>
                            <td class="p-3 font-bold text-slate-200">{{ $genre->name }}</td>
                            <td class="p-3 text-right flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $genre->id }})"
                                    class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition"
                                    title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $genre->id }})"
                                    wire:confirm="Yakin ingin menghapus genre ini?"
                                    class="p-1.5 bg-rose-950/80 hover:bg-rose-900 border border-rose-800/60 text-rose-300 rounded-lg transition"
                                    title="Hapus">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-slate-500">Belum ada genre yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $genres->links('vendor.livewire.custom-pagination') }}
        </div>
    </div>

    <!-- MODAL FORM (TAMBAH / EDIT) -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4">
            <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-3">
                    <h3 class="text-sm font-bold text-slate-100">
                        {{ $genreId ? 'Edit Genre' : 'Tambah Genre Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-200">✕</button>
                </div>

                <form wire:submit.prevent="store" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Nama Genre</label>
                        <input type="text" wire:model="name" placeholder="Misal: Horor, Romantis, Sci-Fi"
                            class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-emerald-500">
                        @error('name')
                            <span class="text-[10px] text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
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
</div>
