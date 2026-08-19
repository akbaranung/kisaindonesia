<header class="sticky top-0 z-40 flex w-full bg-slate-900/90 backdrop-blur border-b border-slate-800 px-4 py-3 sm:px-6">
    <div class="flex flex-grow items-center justify-between">

        <!-- Mobile Hamburger Button -->
        <button @click="sidebarOpen = !sidebarOpen"
            class="block lg:hidden rounded-lg border border-slate-700 p-1.5 text-slate-400 hover:text-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>

        </button>

        <div class="hidden sm:block">
            <span class="text-xs font-medium text-slate-400">Selamat datang kembali, <strong
                    class="text-slate-200">{{ auth()->user()->name }}</strong></span>
        </div>

        <!-- Profile Dropdown -->
        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-3 focus:outline-none">
                <img src="{{ auth()->user()->profile_photo_url }}" alt="Avatar"
                    class="h-8 w-8 rounded-full object-cover border border-slate-700">
                <span class="hidden text-left sm:block">
                    <span class="block text-xs font-bold text-slate-200">{{ auth()->user()->name }}</span>
                    <span
                        class="block text-[10px] text-emerald-400 font-semibold uppercase">{{ auth()->user()->role }}</span>
                </span>
                <span class="text-xs text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                    </svg>

                </span>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="dropdownOpen" @click.outside="dropdownOpen = false"
                class="absolute right-0 mt-2 w-48 rounded-xl border border-slate-800 bg-slate-900 py-1 shadow-2xl z-50"
                style="display: none;">
                <a href="#" class="block px-4 py-2 text-xs text-slate-300 hover:bg-slate-800 hover:text-white">
                    Pengaturan Profil
                </a>
                <div class="border-t border-slate-800 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-400 hover:bg-slate-800">
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>
