<div>
    <!-- Header Title -->
    <div class="mb-8 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-100">Overview Dashboard</h1>
            <p class="text-xs text-slate-400">Ringkasan statistik dan statistik pengguna sistem.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <!-- Card 1 -->
        <div class="rounded-2xl border border-slate-800/80 bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total User</span>
                <span class="p-2 bg-emerald-500/10 text-emerald-400 rounded-xl text-sm">👥</span>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-slate-100">{{ $totalUsers }}</h3>
                <span class="text-[11px] text-emerald-400 font-bold">Terdaftar</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="rounded-2xl border border-slate-800/80 bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Cerita</span>
                <span class="p-2 bg-sky-500/10 text-sky-400 rounded-xl text-sm">📚</span>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-slate-100">0</h3>
                <span class="text-[11px] text-slate-400 font-bold">Dipublikasi</span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="rounded-2xl border border-slate-800/80 bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kategori</span>
                <span class="p-2 bg-amber-500/10 text-amber-400 rounded-xl text-sm">🏷️</span>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-slate-100">0</h3>
                <span class="text-[11px] text-slate-400 font-bold">Genre aktif</span>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="rounded-2xl border border-slate-800/80 bg-slate-900 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Laporan</span>
                <span class="p-2 bg-rose-500/10 text-rose-400 rounded-xl text-sm">🚩</span>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-slate-100">0</h3>
                <span class="text-[11px] text-rose-400 font-bold">Perlu review</span>
            </div>
        </div>

    </div>

    <!-- Recent Users Table -->
    <div class="rounded-2xl border border-slate-800/80 bg-slate-900 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-100">Pengguna Baru Terdaftar</h2>
                <p class="text-xs text-slate-400">Daftar akun terbaru yang masuk ke sistem.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-400">
                <thead
                    class="bg-slate-800/40 text-slate-300 font-bold uppercase tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">Pengguna</th>
                        <th class="p-3.5">Email</th>
                        <th class="p-3.5">Status Email</th>
                        <th class="p-3.5">Role</th>
                        <th class="p-3.5">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @foreach ($recentUsers as $u)
                        <tr class="hover:bg-slate-800/20 transition">
                            <td class="p-3.5 text-slate-200 font-bold flex items-center gap-3">
                                <img src="{{ $u->profile_photo_url }}"
                                    class="w-8 h-8 rounded-full object-cover border border-slate-700">
                                {{ $u->name }}
                            </td>
                            <td class="p-3.5 font-mono text-slate-300">{{ $u->email }}</td>
                            <td class="p-3.5">
                                @if ($u->email_verified_at)
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-950/80 text-emerald-400 border border-emerald-800/60">
                                        ✓ Terverifikasi
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-950/80 text-amber-400 border border-amber-800/60">
                                        ⏳ Belum
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5">
                                <span
                                    class="px-2.5 py-0.5 text-[10px] rounded-md font-bold uppercase tracking-wide {{ $u->role === 'admin' ? 'bg-purple-950 text-purple-400 border border-purple-800' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="p-3.5">{{ $u->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
