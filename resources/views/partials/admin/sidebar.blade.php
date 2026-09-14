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
                    <li>
                        <a href="{{ route('admin.dashboard') }}" wire:navigate
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <i class="fa-solid fa-chart-pie"></i>
                            Overview
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}" wire:navigate
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 transition-all {{ request()->routeIs('admin.users') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <i class="fa-solid fa-users"></i>
                            Kelola User
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.genres') }}" wire:navigate
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('admin.genres') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <i class="fa-solid fa-tags"></i>
                            Kelola Genre
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.coin-packages.index') }}" wire:navigate
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 transition-all {{ request()->routeIs('admin.coin-packages.index') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <i class="fa-solid fa-coins"></i>
                            Paket Kisa Beans
                        </a>
                    </li>

                    <li>
                        <a href="#"
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 transition-all">
                            <span>
                                <i class="fa-solid fa-book-open"></i>
                            </span> Kelola Cerita
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.premium-requests') }}" wire:navigate
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('admin.premium-requests') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <!-- Icon Star / Premium -->
                            <i class="fa-regular fa-star"></i>
                            Pengajuan Premium
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.transactions.index') }}" wire:navigate
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('admin.transactions.index') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <!-- Icon Star / Premium -->
                            <i class="fa-solid fa-wallet"></i>

                            Transaksi
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.withdrawals.index') }}" wire:navigate
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('admin.withdrawals.index') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <!-- Icon Star / Premium -->
                            <i class="fa-solid fa-money-bill-transfer"></i>

                            Withdraw
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.settings') }}" wire:navigate
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('admin.settings') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <!-- Icon Star / Premium -->
                            <i class="fa-solid fa-gear"></i>
                            Settings
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</aside>
