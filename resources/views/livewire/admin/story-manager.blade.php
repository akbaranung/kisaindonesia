<div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl p-6 space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-100">Kelola Cerita</h1>
            <p class="text-xs text-slate-400 mt-1">Atur status publikasi, pilihan editor, serta cari cerita platform.</p>
        </div>
    </div>
    <!-- Flash Message / Notifikasi -->
    @if (session()->has('message'))
        <div
            class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-xs font-medium flex items-center justify-between">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <div
        class="bg-slate-950/60 p-4 border border-slate-800 rounded-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">

        <!-- Search Input -->
        <div class="lg:col-span-2">
            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Cari Judul / Penulis</label>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Ketik judul atau nama penulis..."
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-400 placeholder:text-slate-500">
            </div>
        </div>

        <!-- Filter Kategori -->
        <div>
            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Kategori</label>
            <select wire:model.live="category"
                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-400">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filter Tipe (Gratis / Premium) -->
        <div>
            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Tipe Akses</label>
            <select wire:model.live="type"
                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-400">
                <option value="">Semua Tipe</option>
                <option value="free">Gratis</option>
                <option value="premium">Premium</option>
            </select>
        </div>

        <!-- Filter Pilihan Editor -->
        <div>
            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Pilihan Editor</label>
            <div class="flex gap-2">
                <select wire:model.live="editorChoice"
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-400">
                    <option value="">Semua</option>
                    <option value="1">Ya (Bintang)</option>
                    <option value="0">Tidak</option>
                </select>

                <!-- Tombol Reset Filter -->
                @if ($search || $category || $type || $editorChoice)
                    <button type="button" wire:click="resetFilters"
                        class="px-3 py-2 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 text-xs rounded-xl font-bold transition"
                        title="Reset Filter">
                        ✕
                    </button>
                @endif
            </div>
        </div>

    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left text-slate-300">
            <thead
                class="bg-slate-800/80 text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-800">
                <tr>
                    <th class="p-3">No.</th>
                    <th class="p-3">Judul Cerita</th>
                    <th class="p-3">Penulis</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3 text-center">Pilihan Editor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($stories as $index => $story)
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="p-3 font-semibold text-slate-200">
                            {{ $stories->firstItem() + $index }}
                        </td>
                        <td class="p-3 font-semibold text-slate-200">
                            {{ $story->title }}
                        </td>
                        <td class="p-3 text-slate-400">
                            {{ $story->author->name ?? '-' }}
                        </td>
                        <td class="p-3">
                            <span
                                class="px-2 py-0.5 rounded-md bg-slate-800 border border-slate-700 text-slate-300 text-[10px]">
                                {{ $story->genre->name ?? 'Uncategorized' }}
                            </span>
                        </td>

                        <!-- KOLOM TOGGLE PILIHAN EDITOR -->
                        <td class="p-3 text-center">
                            <button type="button" wire:click="toggleEditorChoice({{ $story->id }})"
                                wire:loading.attr="disabled"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $story->is_editor_choice ? 'bg-brand-500' : 'bg-slate-700' }}"
                                role="switch" aria-checked="{{ $story->is_editor_choice ? 'true' : 'false' }}">

                                <span class="sr-only">Toggle Pilihan Editor</span>

                                <!-- Indicator Circle -->
                                <span
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-slate-950 shadow ring-0 transition duration-200 ease-in-out flex items-center justify-center {{ $story->is_editor_choice ? 'translate-x-5' : 'translate-x-0' }}">
                                    @if ($story->is_editor_choice)
                                        <span class="text-[9px] text-brand-400">★</span>
                                    @endif
                                </span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-slate-500">Tidak ada data cerita.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $stories->links('vendor.livewire.custom-pagination') }}
    </div>
</div>
