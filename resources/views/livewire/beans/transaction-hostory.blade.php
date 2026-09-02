<div class="min-h-screen bg-slate-50 pb-12">

    {{-- TOP NAVIGATION BAR --}}
    <div
        class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-100 py-3.5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('profile') }}"
                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-base font-bold text-slate-800 leading-tight">Riwayat Transaksi</h1>
                <p class="text-[10px] text-slate-400 font-medium">Mutasi koin & aktivitas akun</p>
            </div>
        </div>

        <div class="px-2.5 py-1 bg-amber-50 border border-amber-200/80 rounded-full flex items-center gap-1.5">
            <span class="text-xs">🫘</span>
            <span
                class="text-[11px] font-black text-amber-700">{{ number_format(auth()->user()->kisa_bean_balance ?? 0) }}</span>
        </div>
    </div>

    <div class="px-4 pt-4 space-y-4 max-w-md mx-auto">

        {{-- FILTER TABS MOBILE --}}
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-1 -mx-1 px-1">
            @php
                $tabs = [
                    'all' => 'Semua',
                    'topup' => 'Top Up',
                    'spend' => 'Penggunaan',
                    'earn' => 'Royalti',
                    'payout' => 'Pencairan',
                ];
            @endphp

            @foreach ($tabs as $key => $label)
                <button wire:click="setFilter('{{ $key }}')"
                    class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-all shrink-0 {{ $filterType === $key ? 'bg-amber-500 text-slate-950 shadow-xs' : 'bg-white text-slate-600 border border-slate-200/80 hover:bg-slate-100' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- LIST TRANSAKSI --}}
        <div class="space-y-2.5">
            @forelse($transactions as $tx)
                @php
                    // Konfigurasi visual berdasarkan jenis transaksi
                    $isPositive = in_array($tx->type, ['topup', 'earn']);
                    $icon = match ($tx->type) {
                        'topup' => '📥',
                        'spend' => '📖',
                        'earn' => '💰',
                        'payout' => '🏦',
                        default => '🫘',
                    };

                    $statusClass = match ($tx->status) {
                        'success' => 'bg-brand-50 text-brand-700 border-brand-200',
                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200 animate-pulse',
                        'failed', 'expired' => 'bg-rose-50 text-rose-700 border-rose-200',
                        default => 'bg-slate-50 text-slate-600 border-slate-200',
                    };

                    $statusLabel = match ($tx->status) {
                        'success' => 'Berhasil',
                        'pending' => 'Menunggu',
                        'failed' => 'Gagal',
                        'expired' => 'Kadaluarsa',
                        default => $tx->status,
                    };
                @endphp

                <div class="p-3.5 bg-white rounded-2xl border border-slate-200/80 shadow-2xs space-y-2">
                    {{-- Header Kartu --}}
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div
                                class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-base shrink-0">
                                {{ $icon }}
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-xs font-bold text-slate-800 truncate">
                                    {{ $tx->description ?? 'Transaksi Kisa Bean' }}
                                </h2>
                                <p class="text-[10px] text-slate-400 font-medium">
                                    {{ $tx->created_at->format('d M Y, H:i') }} • <span
                                        class="uppercase font-semibold text-slate-500">{{ $tx->payment_method ?? 'Kisa Bean' }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- Perubahan Saldo Bean --}}
                        <div class="text-right shrink-0">
                            <span class="text-xs font-black {{ $isPositive ? 'text-brand-600' : 'text-slate-800' }}">
                                {{ $isPositive ? '+' : '-' }}{{ number_format($tx->amount) }} 🫘
                            </span>
                            @if ($tx->gross_amount > 0)
                                <p class="text-[10px] font-medium text-slate-400">
                                    Rp {{ number_format($tx->gross_amount, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Footer Kartu / Ref Code & Status --}}
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[10px]">
                        <span class="font-mono text-slate-400">Ref: {{ $tx->reference_code }}</span>

                        <span
                            class="px-2 py-0.5 rounded-full border text-[9px] font-extrabold uppercase {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-8 bg-white rounded-2xl border border-dashed border-slate-200 text-center space-y-2">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-xl">📜
                    </div>
                    <p class="text-xs font-bold text-slate-700">Belum Ada Transaksi</p>
                    <p class="text-[10px] text-slate-400 max-w-[200px] mx-auto">Riwayat mutasi Kisa Bean Anda akan
                        tercatat secara otomatis di sini.</p>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION LINK --}}
        <div class="pt-2">
            {{ $transactions->links() }}
        </div>

    </div>

</div>
