<div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-100">Kelola Cerita</h1>
            <p class="text-xs text-slate-400 mt-1">Atur status publikasi, blokir cerita, pilihan editor, serta cari
                cerita platform.</p>
        </div>

        @if (auth()->check() && auth()->user()->email === 'admin@kisaindonesia.id')
            <div class="flex items-center gap-2">
                <button type="button" wire:click="syncPeriodicViewsFromTotal"
                    wire:confirm="Isi periodic_views_count menggunakan data views_count saat ini?"
                    class="px-4 py-2 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 text-blue-400 text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-sync"></i>
                    <span>Copy dari Views Count</span>
                </button>

                <button type="button" wire:click="resetAllPeriodicViews"
                    wire:confirm="Apakah Anda yakin ingin mereset seluruh perhitungan views periodik ini ke angka 0?"
                    class="px-4 py-2 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-400 text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-rotate-right"></i>
                    <span>Reset Periodic Views</span>
                </button>
            </div>
        @endif
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

        <div>
            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Status Blokir</label>
            <select wire:model.live="statusFilter"
                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-400">
                <option value="">Semua Status</option>
                <option value="active">Aktif (Normal)</option>
                <option value="blocked">Diblokir</option>
            </select>
        </div>

        <div>
            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Badge / Section</label>
            <div class="flex gap-2">
                <select wire:model.live="selectedBadge"
                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 focus:outline-none focus:border-brand-400">
                    <option value="">Semua Badge</option>
                    @foreach ($badges as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>

                <!-- Tombol Reset Filter -->
                @if ($search || $category || $type || $selectedBadge)
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
                    <th class="p-3">Status</th>
                    <th class="p-3">Penulis</th>
                    <th class="p-3 text-center">Total Views</th>
                    <th class="p-3 text-center">Views Periode Ini</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3 text-center">Badge & Section Spesial</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($stories as $index => $story)
                    <tr class="hover:bg-slate-800/30 transition" wire:key="story-row-{{ $story->id }}">
                        <td class="p-3 font-semibold text-slate-200">
                            {{ $stories->firstItem() + $index }}
                        </td>
                        <td class="p-3 font-semibold text-slate-200">
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="openDetailModal({{ $story->id }})"
                                    class="text-left font-semibold text-slate-100 hover:text-brand-400 transition"
                                    title="Lihat Detail Pembelian Bab Berbayar">
                                    {{ $story->title }}
                                </button>
                                @if ($story->monetization_type === 'premium')
                                    <span
                                        class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30 shrink-0">
                                        Premium
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="p-3 font-semibold text-slate-200">
                            @php
                                if ($story->status === 'draft') {
                                    $color = 'amber';
                                } elseif ($story->status === 'published') {
                                    $color = 'brand';
                                }
                            @endphp
                            <span
                                class="px-2 py-0.5 rounded-md bg-{{ $color }}-500/10 hover:bg-{{ $color }}-500/20 border border-{{ $color }}-500/30 text-{{ $color }}-400 text-[10px]">
                                {{ $story->status }}
                            </span>

                        </td>
                        <td class="p-3 text-slate-400">
                            {{ $story->author->name ?? '-' }}
                        </td>
                        <td class="p-3 text-center font-bold text-slate-300">{{ number_format($story->views_count) }}
                        </td>
                        <td class="p-3 text-center font-bold text-brand-400">
                            {{ number_format($story->periodic_views_count) }}</td>
                        <td class="p-3 text-slate-400">
                            {{ $story->author->email ?? '-' }}
                        </td>
                        <td class="p-3">
                            <span
                                class="px-2 py-0.5 rounded-md bg-slate-800 border border-slate-700 text-slate-300 text-[10px]">
                                {{ $story->genre->name ?? 'Uncategorized' }}
                            </span>
                        </td>

                        <td class="p-3">
                            <div class="flex flex-wrap items-center justify-center gap-1.5">
                                @foreach ($badges as $badge)
                                    @php
                                        $hasBadge = $story->badges->contains('id', $badge->id);
                                        $pivotOrder = $hasBadge ? $story->badges->find($badge->id)->pivot->order : 0;
                                    @endphp

                                    <div
                                        class="flex items-center gap-1 bg-slate-800 p-1 rounded-xl border border-slate-700">
                                        <button type="button"
                                            wire:click="toggleBadge({{ $story->id }}, {{ $badge->id }})"
                                            class="px-2 py-0.5 rounded-lg text-[10px] font-bold transition {{ $hasBadge ? 'bg-brand-500 text-slate-950' : 'text-slate-400 hover:text-slate-200' }}">
                                            {{ $badge->name }}
                                        </button>

                                        @if ($hasBadge)
                                            <input type="number" value="{{ $pivotOrder }}"
                                                wire:change="updateBadgeOrder({{ $story->id }}, {{ $badge->id }}, $event.target.value)"
                                                title="Urutan Tampil"
                                                class="w-8 px-1 py-0.5 bg-slate-900 border border-slate-700 rounded text-[10px] font-bold text-center text-slate-100 focus:outline-none focus:border-brand-400">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- Tombol Detail Bab Berbayar -->
                                <button type="button" wire:click="openDetailModal({{ $story->id }})"
                                    class="px-2.5 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 text-blue-400 text-[10px] font-bold rounded-xl transition flex items-center justify-center gap-1.5 shadow-sm"
                                    title="Lihat Detail Pembelian Bab Berbayar">
                                    <i class="fa-solid fa-coins"></i>
                                    <span>Detail Bab</span>
                                </button>

                                @if (auth()->check() && auth()->user()->email === 'admin@kisaindonesia.id')
                                    @if ($story->is_blocked)
                                        <button type="button" wire:click="toggleBlockStory({{ $story->id }})"
                                            wire:confirm="Buka blokir cerita '{{ $story->title }}'?"
                                            class="px-2.5 py-1.5 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold rounded-xl transition flex items-center justify-center gap-1 shadow-sm"
                                            title="Buka Blokir">
                                            <i class="fa-solid fa-lock-open"></i>
                                            <span>Buka</span>
                                        </button>
                                    @else
                                        <button type="button" wire:click="toggleBlockStory({{ $story->id }})"
                                            wire:confirm="Yakin ingin memblokir cerita '{{ $story->title }}'?"
                                            class="px-2.5 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 text-[10px] font-bold rounded-xl transition flex items-center justify-center gap-1 shadow-sm"
                                            title="Blokir Cerita">
                                            <i class="fa-solid fa-ban"></i>
                                            <span>Blokir</span>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="p-6 text-center text-slate-500">Tidak ada data cerita.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $stories->links('vendor.livewire.custom-pagination') }}
    </div>

    <!-- Modal Detail Pembelian Bab Berbayar -->
    @if ($showDetailModal && !empty($selectedStoryData))
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm p-4 flex items-center justify-center"
            x-data @keydown.window.escape="$wire.closeDetailModal()">

            <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col shadow-2xl animate-fade-in my-auto"
                @click.away="$wire.closeDetailModal()">

                <!-- Header Modal -->
                <div
                    class="p-4 sm:p-5 border-b border-slate-800 flex items-center justify-between gap-4 shrink-0 bg-slate-900">
                    <div>
                        <h2 class="text-base font-bold text-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-coins text-amber-400"></i>
                            <span>{{ $selectedStoryData['title'] }}</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Penulis: <span
                                class="text-slate-300 font-medium">{{ $selectedStoryData['author_name'] }}</span> •
                            Kategori: <span
                                class="text-slate-300 font-medium">{{ $selectedStoryData['genre_name'] }}</span>
                        </p>
                    </div>

                    <button type="button" wire:click="closeDetailModal"
                        class="p-1.5 text-slate-400 hover:text-slate-100 hover:bg-slate-800 rounded-lg transition shrink-0"
                        title="Tutup (Esc)">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                        <!-- 4 Kartu Ringkasan Sederhana -->
                        <div
                            class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 p-4 bg-slate-950/50 border-b border-slate-800 shrink-0">
                            <div class="bg-slate-900/90 border border-slate-800 p-2.5 rounded-xl text-center">
                                <span class="text-[11px] text-slate-400 font-medium block">Bab Berbayar</span>
                                    <span class="text-base font-bold text-slate-100 mt-1 block">
                                        {{ $purchaseSummary['total_paid_chapters'] ?? 0 }} <span
                                            class="text-xs font-normal text-slate-500">Bab</span>
                                    </span>
                            </div>

                                <div class="bg-slate-900/90 border border-slate-800 p-2.5 rounded-xl text-center">
                                    <span class="text-[11px] text-slate-400 font-medium block">Sudah Dibeli</span>
                                        <span class="text-base font-bold text-emerald-400 mt-1 block">
                                            {{ $purchaseSummary['purchased_paid_chapters_count'] ?? 0 }} <span
                                                class="text-xs font-normal text-slate-500">Bab</span>
                                        </span>
                                </div>

                                    <div class="bg-slate-900/90 border border-slate-800 p-2.5 rounded-xl text-center">
                                        <span class="text-[11px] text-slate-400 font-medium block">Total
                                            Pembelian</span>
                                            <span class="text-base font-bold text-blue-400 mt-1 block">
                                                {{ number_format($purchaseSummary['total_purchases'] ?? 0) }} <span
                                                    class="text-xs font-normal text-slate-500">Kali</span>
                                            </span>
                                    </div>

                                    <div class="bg-slate-900/90 border border-slate-800 p-2.5 rounded-xl text-center">
                                        <span class="text-[11px] text-slate-400 font-medium block">Total Koin</span>
                                        <span
                                            class="text-base font-bold text-amber-400 mt-1 block flex items-center justify-center gap-1">
                                            <i class="fa-solid fa-coins text-xs"></i>
                                            <span>{{ number_format($purchaseSummary['total_beans'] ?? 0) }}</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Toolbar Sederhana: Toggle & Search -->
                                <div
                                    class="px-4 py-3 bg-slate-900 border-b border-slate-800 flex items-center justify-between gap-3 shrink-0">
                                    <label
                                        class="flex items-center gap-2 cursor-pointer text-xs text-slate-300 select-none">
                                        <input type="checkbox" wire:model.live="onlyPaidChapters"
                                            class="rounded bg-slate-800 border-slate-700 text-brand-500 focus:ring-brand-400 focus:ring-offset-slate-900">
                                        <span>Hanya Bab Berbayar</span>
                                    </label>

                                    <div class="relative w-48 sm:w-56">
                                        <input type="text" wire:model.live.debounce.250ms="modalSearch"
                                            placeholder="Cari judul/nomor bab..."
                                            class="w-full pl-7 pr-7 py-1 bg-slate-950/60 border border-slate-700 rounded-lg text-xs text-slate-100 placeholder:text-slate-500 focus:outline-none focus:border-brand-400">
                                        @if ($modalSearch)
                                            <button type="button" wire:click="$set('modalSearch', '')"
                                                class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 text-xs">✕</button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Tabel Daftar Bab Sederhana -->
                                <div class="flex-1 min-h-0 overflow-y-auto">
                                    <table class="w-full text-xs text-left text-slate-300">
                                        <thead
                                            class="bg-slate-800/80 text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-800 sticky top-0 z-10 backdrop-blur-md">
                                            <tr>
                                                <th class="p-3 text-center w-16">Bab</th>
                                                <th class="p-3">Judul Bab</th>
                                                <th class="p-3 text-center w-20">Harga</th>
                                                <th class="p-3 text-center w-28">Dibeli Pembaca</th>
                                                <th class="p-3 text-right w-28">Total Koin</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-800/60">
                                            @forelse ($filteredChapterPurchases as $chap)
                                                <tr class="hover:bg-slate-800/40 transition">
                                                    <td class="p-3 text-center font-bold text-slate-300">
                                                        Bab {{ $chap['order_number'] }}
                                                    </td>
                                                    <td class="p-3 font-medium text-slate-200">
                                                        {{ $chap['title'] }}
                                                    </td>
                                                    <td class="p-3 text-center">
                                                        @if ($chap['is_paid'])
                                                            <span
                                                                class="px-2 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/30 text-amber-400 font-bold text-[10px]">
                                                                {{ $chap['bean_price'] }} Bean
                                                            </span>
                                                        @else
                                                            <span
                                                                class="px-2 py-0.5 rounded-md bg-slate-800 text-slate-400 text-[10px]">
                                                                Gratis
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="p-3 text-center">
                                                        @if ($chap['purchases_count'] > 0)
                                                            <span
                                                                class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold text-xs">
                                                                {{ $chap['purchases_count'] }} kali dibeli
                                                            </span>
                                                        @else
                                                            <span class="text-slate-500 text-xs">
                                                                0 kali
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="p-3 text-right">
                                                        @if ($chap['total_beans'] > 0)
                                                            <span
                                                                class="font-bold text-amber-400 text-xs inline-flex items-center gap-1 justify-end">
                                                                <i class="fa-solid fa-coins text-[10px]"></i>
                                                                <span>{{ number_format($chap['total_beans']) }}</span>
                                                            </span>
                                                        @else
                                                            <span class="text-slate-500 text-xs">0</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="p-6 text-center text-slate-500">
                                                        Tidak ada bab yang sesuai.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Footer Modal -->
                                <div
                                    class="p-3.5 border-t border-slate-800 flex items-center justify-between gap-3 shrink-0 bg-slate-900">
                                    <span class="text-xs text-slate-400">
                                        Total: <strong
                                            class="text-slate-200">{{ count($filteredChapterPurchases) }}</strong> bab
                                    </span>

                                    <button type="button" wire:click="closeDetailModal"
                                        class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs rounded-xl transition">
                                        Tutup
                                    </button>
                                </div>

                            </div>
                        </div>
    @endif
</div>
