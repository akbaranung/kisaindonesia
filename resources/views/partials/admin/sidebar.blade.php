<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="absolute left-0 top-0 z-50 flex h-screen w-64 flex-col overflow-y-hidden bg-slate-900 border-r border-slate-800 duration-300 ease-linear lg:static lg:translate-x-0">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between gap-2 px-6 py-5 lg:py-6 border-b border-slate-800/80">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-emerald-400 font-black text-lg">
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
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                                </svg>

                            </span> Overview
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}" wire:navigate
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 transition-all {{ request()->routeIs('admin.users') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>

                            </span> Kelola User
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.genres') }}" wire:navigate
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('admin.genres') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5a1 1 0 01.707.293l7 7a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-7-7A1 1 0 017 9V3z" />
                            </svg>
                            Kelola Genre
                        </a>
                    </li>

                    <li>
                        <a href="#"
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 transition-all">
                            <span>
                                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </span> Kelola Cerita
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.premium-requests') }}" wire:navigate
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('admin.premium-requests') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <!-- Icon Star / Premium -->
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Pengajuan Premium
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</aside>
