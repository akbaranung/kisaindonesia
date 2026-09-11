<div class="min-h-screen bg-slate-50 pb-28">
    {{-- Header Sticky --}}
    <div
        class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-100 py-3.5 flex items-center justify-between px-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('profile') }}"
                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-base font-bold text-slate-800 leading-tight">Top Up Kisa Bean</h1>
                <p class="text-[10px] text-slate-400 font-medium">Beli koin untuk membaca bab favorit</p>
            </div>
        </div>
        <div class="px-2.5 py-1 bg-amber-50 border border-amber-200/80 rounded-full flex items-center gap-1.5">
            <span class="text-xs">🫘</span>
            <span
                class="text-[11px] font-black text-amber-700">{{ number_format(auth()->user()->kisa_bean_balance ?? 0) }}</span>
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 pt-4 space-y-5">
        {{-- Flash Message Error --}}
        @if (session('error'))
            <div
                class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-2xl flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Section 1: Pilih Paket --}}
        <div class="space-y-2.5">
            <h2 class="text-xs font-black text-slate-400 uppercase tracking-wider mb-2">Pilih Paket KISA Bean</h2>

            <div class="grid grid-cols-2 gap-3">
                @foreach ($packages as $pkg)
                    @php
                        $isSelected = $selectedPackage ? $selectedPackage->id === $pkg->id : false;
                    @endphp
                    <button type="button" wire:key="package-{{ $pkg->id }}"
                        wire:click="selectPackage({{ $pkg->id }})"
                        class="relative p-3.5 rounded-2xl border text-left transition-all active:scale-95 {{ $isSelected ? 'bg-amber-500/10 border-amber-500 ring-2 ring-amber-500/20' : 'bg-white border-slate-200/80 hover:border-slate-300' }}">

                        @if ($pkg->badge_label)
                            <div
                                class="absolute -top-2 right-2 bg-amber-500 text-white font-black text-[9px] uppercase px-2 py-0.5 rounded-full shadow-xs tracking-wider">
                                {{ $pkg->badge_label }}
                            </div>
                        @endif

                        <div class="mb-2">
                            <span
                                class="text-[10px] font-black uppercase tracking-wider text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg">
                                {{ $pkg->name }}
                            </span>
                        </div>

                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="text-base">🫘</span>
                            <span class="text-base font-black text-slate-800">{{ number_format($pkg->beans) }}</span>
                            @if ($pkg->bonus_beans > 0)
                                <span
                                    class="text-[10px] font-extrabold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">
                                    +{{ number_format($pkg->bonus_beans) }}
                                </span>
                            @endif
                        </div>

                        @if ($pkg->discount_price && $pkg->discount_price < $pkg->price)
                            <span class="text-[10px] text-slate-400 line-through block">
                                Rp {{ number_format($pkg->price, 0, ',', '.') }}
                            </span>
                            <span class="text-xs font-bold text-rose-600">
                                Rp {{ number_format($pkg->discount_price, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-xs font-bold text-amber-700">
                                Rp {{ number_format($pkg->price, 0, ',', '.') }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Section 2: Pilihan Kode Metode Pembayaran Duitku --}}
        <div class="mb-15">
            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2">Pilih Metode
                Pembayaran</label>

            @if (count($availableMethods) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($availableMethods as $method)
                        <label wire:key="method-{{ $method['paymentMethod'] }}"
                            class="flex items-center justify-between p-4 rounded-xl border cursor-pointer transition-all duration-200 {{ $paymentMethod === $method['paymentMethod'] ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-500/20' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="paymentMethod" value="{{ $method['paymentMethod'] }}"
                                    wire:model.live="paymentMethod" class="hidden">
                                <img src="{{ $method['paymentImage'] }}" alt="{{ $method['paymentName'] }}"
                                    class="h-6 object-contain">
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $method['paymentName'] }}</p>
                                    <p class="text-xs text-slate-500">
                                        Fee:
                                        @if ($method['totalFee'] > 0)
                                            Rp {{ number_format($method['totalFee'], 0, ',', '.') }}
                                        @else
                                            <span class="text-emerald-600 font-semibold">Bebas Biaya</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            @else
                <div
                    class="p-4 bg-amber-50 rounded-2xl border border-amber-200/70 text-amber-800 text-xs text-center font-medium">
                    Tidak ada metode pembayaran yang tersedia untuk nominal ini atau belum diaktifkan oleh Admin.
                </div>
            @endif

            @error('paymentMethod')
                <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- Sticky Bottom Action Button --}}
    @if ($selectedPackage)
        @php
            $totalToPay =
                $selectedPackage->discount_price && $selectedPackage->discount_price < $selectedPackage->price
                    ? $selectedPackage->discount_price
                    : $selectedPackage->price;
        @endphp
        <div
            class="fixed bottom-12 left-0 right-0 bg-white/90 backdrop-blur-md border-t border-slate-200 p-3.5 z-30 max-w-md mx-auto">
            <div class="flex items-center justify-between mb-2 px-1">
                <span class="text-[11px] font-medium text-slate-500">Total Pembayaran:</span>
                <span class="text-sm font-black text-amber-600">Rp {{ number_format($totalToPay, 0, ',', '.') }}</span>
            </div>

            <button wire:click="processTopup" wire:loading.attr="disabled"
                class="w-full py-3.5 bg-amber-500 hover:bg-amber-600 active:scale-[0.99] text-slate-950 font-black text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2 disabled:opacity-50">
                <span wire:loading.remove wire:target="processTopup">Konfirmasi & Bayar Sekarang</span>
                <span wire:loading wire:target="processTopup" class="flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin text-slate-950" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Memproses Transaksi...
                </span>
            </button>
        </div>
    @endif
</div>
