<div
    class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 shadow-lg px-6 py-2 flex justify-between items-center z-50 max-w-md mx-auto">
    <a href="{{ url('/') }}" wire:navigate
        class="flex flex-col items-center {{ request()->is('/') ? 'text-[#38CAC8]' : 'text-slate-400' }}">
        <i class="fa-solid fa-house"></i>
        <span class="text-[10px] {{ request()->is('/') ? 'font-bold' : 'font-medium' }} mt-1">Home</span>
    </a>

    <a href="{{ route('feed') }}" wire:navigate
        class="flex flex-col items-center {{ request()->routeIs('feed') ? 'text-[#38CAC8] font-bold' : 'text-slate-400' }}">
        <i class="fa-solid fa-layer-group fa-14"></i>
        <span class="text-[10px] tracking-tight">Feeds</span>
    </a>

    <a href="{{ route('library') }}" wire:navigate
        class="flex flex-col items-center {{ request()->routeIs('library') ? 'text-[#38CAC8] font-bold' : 'text-slate-400' }}">
        <i class="fa-regular fa-bookmark"></i>
        <span class="text-[10px] tracking-tight">Pustaka</span>
    </a>

    <a href="{{ url('/my-stories') }}" wire:navigate
        class="flex flex-col items-center {{ Route::is('my-stories') || request()->path() === 'my-stories' ? 'text-[#38CAC8] font-bold' : 'text-slate-400' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
        </svg>
        <span class="text-[10px] tracking-wide">Studio</span>
    </a>

    @auth
        <a href="{{ route('profile') }}" wire:navigate
            class="flex flex-col items-center {{ request()->is('profile') ? 'text-[#38CAC8]' : 'text-slate-400' }}">
            <div
                class="w-5 h-5 {{ request()->is('profile') ? 'bg-[#38CAC8] text-white' : 'bg-[#d3f3f3] text-[#38CAC8]' }} rounded-full flex items-center justify-center text-[10px] font-bold uppercase border border-[#d3f3f3]">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <span
                class="text-[10px] {{ request()->is('profile') ? 'font-bold text-[#38CAC8]' : 'font-medium text-slate-500' }} mt-1 max-w-[50px] truncate">{{ Auth::user()->name }}</span>
        </a>
    @else
        <a href="{{ route('login') }}" wire:navigate
            class="flex flex-col items-center {{ request()->is('login') ? 'text-[#38CAC8]' : 'text-slate-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <span class="text-[10px] {{ request()->is('login') ? 'font-bold' : 'font-medium' }} mt-1">Masuk</span>
        </a>
    @endauth
</div>
