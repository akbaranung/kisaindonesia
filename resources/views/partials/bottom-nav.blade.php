<div
    class="fixed bottom-0 left-0 right-0 z-50 max-w-md mx-auto bg-white border-t border-slate-200/80 shadow-[0_-4px_25px_rgba(0,0,0,0.08)] px-2 h-14">
    <div class="flex items-center justify-around h-full relative">
        @php $isHome = request()->is('/'); @endphp
        <a href="{{ url('/') }}" wire:navigate
            class="flex flex-col items-center justify-end h-full py-1.5 flex-1 relative group">
            <div
                class="relative flex items-center justify-center shrink-0 transition-all duration-300 ease-out {{ $isHome ? '-translate-y-3 w-10 h-10 bg-[#38CAC8] text-white' : 'w-9 h-9 text-slate-400 group-hover:text-slate-600' }} rounded-full">
                <i
                    class="fa-solid fa-house text-base transition-transform duration-300 {{ $isHome ? 'scale-110' : '' }}"></i>
            </div>
            <span
                class="text-[10px] transition-all duration-300 {{ $isHome ? 'font-bold text-[#38CAC8]' : 'font-medium text-slate-400' }} leading-none">Home</span>
        </a>

        @php $isCategory = request()->routeIs('categories.*'); @endphp
        <a href="{{ route('categories.index') }}" wire:navigate
            class="flex flex-col items-center justify-end h-full py-1.5 flex-1 relative group">
            <div
                class="relative flex items-center justify-center shrink-0 transition-all duration-300 ease-out {{ $isCategory ? '-translate-y-3 w-10 h-10 bg-[#38CAC8] text-white' : 'w-9 h-9 text-slate-400 group-hover:text-slate-600' }} rounded-full">
                <i
                    class="fa-solid fa-border-all text-base transition-transform duration-300 {{ $isCategory ? 'scale-110' : '' }}"></i>
            </div>
            <span
                class="text-[10px] transition-all duration-300 {{ $isCategory ? 'font-bold text-[#38CAC8]' : 'font-medium text-slate-400' }} tracking-tight leading-none">Kategori</span>
        </a>

        @php $isStudio = Route::is('my-stories') || request()->path() === 'my-stories'; @endphp
        <a href="{{ url('/my-stories') }}" wire:navigate
            class="flex flex-col items-center justify-end h-full py-1.5 flex-1 relative group">
            <div
                class="relative flex items-center justify-center shrink-0 transition-all duration-300 ease-out {{ $isStudio ? '-translate-y-3 w-10 h-10 bg-[#38CAC8] text-white' : 'w-9 h-9 text-slate-400 group-hover:text-slate-600' }} rounded-full">
                <svg class="w-5 h-5 transition-transform duration-300 {{ $isStudio ? 'rotate-6 scale-110' : '' }}"
                    fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </div>
            <span
                class="text-[10px] transition-all duration-300 {{ $isStudio ? 'font-bold text-[#38CAC8]' : 'font-medium text-slate-400' }} leading-none">Studio</span>
        </a>

        @php $isLibrary = request()->routeIs('library'); @endphp
        <a href="{{ route('library') }}" wire:navigate
            class="flex flex-col items-center justify-end h-full py-1.5 flex-1 relative group">
            <div
                class="relative flex items-center justify-center shrink-0 transition-all duration-300 ease-out {{ $isLibrary ? '-translate-y-3 w-10 h-10 bg-[#38CAC8] text-white' : 'w-9 h-9 text-slate-400 group-hover:text-slate-600' }} rounded-full">
                <i
                    class="fa-regular fa-bookmark text-base transition-transform duration-300 {{ $isLibrary ? 'scale-110' : '' }}"></i>
            </div>
            <span
                class="text-[10px] transition-all duration-300 {{ $isLibrary ? 'font-bold text-[#38CAC8]' : 'font-medium text-slate-400' }} tracking-tight leading-none">Pustaka</span>
        </a>

        @auth
            @php $isProfile = request()->is('profile'); @endphp
            <a href="{{ route('profile') }}" wire:navigate
                class="flex flex-col items-center justify-end h-full py-1.5 flex-1 relative group">
                <div
                    class="relative flex items-center justify-center shrink-0 transition-all duration-300 ease-out {{ $isProfile ? '-translate-y-3 w-10 h-10 bg-[#38CAC8] text-white' : 'w-9 h-9 text-slate-400' }} rounded-full">
                    <div
                        class="w-5 h-5 {{ $isProfile ? 'bg-white text-[#38CAC8]' : 'bg-[#d3f3f3] text-[#38CAC8]' }} rounded-full flex items-center justify-center text-[10px] font-bold uppercase transition-all duration-300">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
                <span
                    class="text-[10px] transition-all duration-300 {{ $isProfile ? 'font-bold text-[#38CAC8]' : 'font-medium text-slate-400' }} tracking-tight leading-none">
                    Profile
                </span>
            </a>
        @else
            @php $isLogin = request()->is('login'); @endphp
            <a href="{{ route('login') }}" wire:navigate
                class="flex flex-col items-center justify-end h-full py-1.5 flex-1 relative group">
                <div
                    class="relative flex items-center justify-center shrink-0 transition-all duration-300 ease-out {{ $isLogin ? '-translate-y-3 w-10 h-10 bg-[#38CAC8] text-white' : 'w-9 h-9 text-slate-400 group-hover:text-slate-600' }} rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor"
                        class="w-5 h-5 transition-transform duration-300 {{ $isLogin ? 'scale-110' : '' }}">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <span
                    class="text-[10px] transition-all duration-300 {{ $isLogin ? 'font-bold text-[#38CAC8]' : 'font-medium text-slate-400' }} leading-none mt-1">Masuk</span>
            </a>
        @endauth

    </div>
</div>
