<div class="min-h-screen bg-gray-50 pb-20">
    {{-- Header Bar Mobile --}}
    <div class="bg-white border-b border-gray-100 px-4 py-3 sticky top-0 z-10 flex items-center gap-3">
        <a href="javascript:history.back()" class="p-1 rounded-full text-gray-600 hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="font-bold text-gray-800 text-base">Contact Support</h1>
    </div>

    {{-- Content --}}
    <div class="p-4 space-y-4">

        <div class="text-center my-2">
            <h2 class="text-lg font-bold text-gray-800">Butuh Bantuan?</h2>
            <p class="text-xs text-gray-500 mt-0.5">Tim kami siap membantu kamu melalui kontak di bawah ini.</p>
        </div>

        {{-- Card WhatsApp / Telepon --}}
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="w-11 h-11 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block font-medium">Telepon / WhatsApp</span>
                    <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $phone }}</p>
                </div>
            </div>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank"
                class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold px-3 py-2 rounded-xl shadow-sm">
                Hubungi
            </a>
        </div>

        {{-- Card Email --}}
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <div
                    class="w-11 h-11 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="truncate">
                    <span class="text-xs text-gray-400 block font-medium">Email Support</span>
                    <p class="text-sm font-bold text-gray-800 mt-0.5 truncate">{{ $email }}</p>
                </div>
            </div>
            <a href="mailto:{{ $email }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-3 py-2 rounded-xl shadow-sm shrink-0 ml-2">
                Kirim
            </a>
        </div>

        {{-- Card Alamat --}}
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div class="flex items-start gap-3">
                <div
                    class="w-11 h-11 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block font-medium">Alamat Kantor</span>
                    <p class="text-xs font-medium text-gray-700 leading-relaxed mt-1">
                        {{ $address }}
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
