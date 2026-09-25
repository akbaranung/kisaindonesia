<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="absolute left-0 top-0 z-50 flex h-screen w-64 flex-col overflow-y-hidden bg-slate-900 border-r border-slate-800 duration-300 ease-linear lg:static lg:translate-x-0">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between gap-2 px-6 py-5 lg:py-6 border-b border-slate-800/80">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-brand-400 font-black text-lg">
                <img src="{{ asset('images/logo-2.png') }}" alt="logo">
            </div>
            <div>
                <h1 class="font-black text-slate-100 text-sm tracking-wide">Kisa Admin</h1>
                <p class="text-[10px] text-slate-400 font-medium">Dashboard Control</p>
            </div>
        </a>

        <button @click="sidebarOpen = false" class="block lg:hidden text-slate-400 hover:text-slate-200">
            ✕
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear px-4 py-4">
        <nav class="space-y-6">
            <div>
                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Menu Utama
                </p>
                <ul class="space-y-1">
                    @php
                        $userMenus =
                            auth()->user()->role === 'superadmin'
                                ? \App\Models\Menu::orderBy('order', 'asc')->get()
                                : auth()->user()->menus;
                    @endphp
                    @forelse ($userMenus as $menu)
                        <li>
                            <a href="{{ route($menu->route_name) }}" wire:navigate
                                class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs($menu->route_name) ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                                @if ($menu->icon)
                                    <i class="{{ $menu->icon }}"></i>
                                @endif
                                <span>{{ $menu->name }}</span>
                            </a>
                        </li>

                    @empty
                        <div class="px-3 text-xs text-slate-500">Tidak ada akses menu.</div>
                    @endforelse
                </ul>
            </div>
        </nav>
    </div>
</aside>
