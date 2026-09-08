<x-layouts::app>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-3xl p-6 border border-slate-100 shadow-xl text-center space-y-4">

            @if ($transaction->status === 'success')
                <div
                    class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto text-2xl font-black">
                    ✓
                </div>
                <h1 class="text-lg font-black text-slate-800">Pembayaran Berhasil!</h1>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Selamat! Top up sebesar <strong class="text-slate-800">{{ number_format($transaction->amount) }} KISA
                        Bean</strong> telah ditambahkan ke akunmu.
                </p>
            @elseif ($transaction->status === 'pending')
                <div
                    class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mx-auto text-2xl">
                    ⏳
                </div>
                <h1 class="text-lg font-black text-slate-800">Menunggu Pembayaran</h1>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Selesaikan pembayaran kamu melalui metode <strong>{{ $transaction->payment_method }}</strong>. Saldo
                    akan otomatis bertambah setelah terverifikasi.
                </p>
            @else
                <div
                    class="w-16 h-16 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mx-auto text-2xl font-black">
                    ✕
                </div>
                <h1 class="text-lg font-black text-slate-800">Pembayaran Gagal / Dibatalkan</h1>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Transaksi tidak dapat diproses atau telah melewati batas waktu pembayaran.
                </p>
            @endif

            <div class="pt-4 border-t border-slate-100">
                <a href="{{ route('profile') }}"
                    class="block w-full py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition">
                    Kembali ke Profil
                </a>
            </div>
        </div>
    </div>
</x-layouts::app>
