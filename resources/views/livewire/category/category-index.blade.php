<div class="w-full min-h-screen bg-slate-50 max-w-2xl mx-auto border-x border-slate-100 pb-28">
    {{-- Header Page --}}
    <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-100 px-4 py-3.5 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" wire:navigate class="p-1.5 text-slate-500 hover:text-slate-800 transition rounded-xl hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="font-black text-base text-slate-900 tracking-tight">Kategori Cerita</h1>
                <p class="text-[10px] text-slate-400 font-medium">Pilih kategori untuk menjelajahi kisah</p>
            </div>
        </div>
    </header>

    <main class="p-4 md:p-6 space-y-2.5">
        {{-- List Cards Parent Category --}}
        @forelse($parentCategories as $category)
            <a href="{{ route('categories.show', $category->id) }}" wire:navigate
                class="group bg-white border border-slate-100 hover:border-brand-500/40 rounded-2xl p-4 flex items-center justify-between shadow-3xs hover:shadow-2xs transition duration-200">
                <span class="text-sm font-bold text-slate-800 group-hover:text-brand-600 transition">
                    {{ $category->name }}
                </span>
                <i class="fa-solid fa-chevron-right text-xs text-slate-400 group-hover:text-brand-600 group-hover:translate-x-0.5 transition transform"></i>
            </a>
        @empty
            <div class="text-center py-12 bg-white rounded-3xl border border-slate-100 p-8 shadow-2xs">
                <div class="w-14 h-14 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3">
                    <i class="fa-solid fa-shapes"></i>
                </div>
                <h3 class="text-sm font-black text-slate-800">Belum Ada Kategori</h3>
                <p class="text-xs text-slate-400 mt-1">Kategori cerita akan segera ditambahkan.</p>
            </div>
        @endforelse
    </main>
</div>
