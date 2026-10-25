<div class="w-full md:w-64 shrink-0">
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-slate-100">
            <div
                class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold text-lg">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
                <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
            </div>
        </div>

        <div x-data="{ open: true }">
            <button @click="open = !open"
                class="flex items-center justify-between w-full text-left font-bold text-slate-800 hover:text-emerald-600 transition mb-2">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Akun Saya
                </div>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open" class="pl-7 space-y-3 mt-3 mb-4">
                <a href="{{ route('user.profile.edit') }}"
                    class="block text-sm transition {{ request()->routeIs('user.profile.*') && !request()->routeIs('user.address.*') ? 'font-bold text-emerald-600' : 'text-slate-600 hover:text-emerald-600' }}">Profil</a>

                <a href="{{ route('user.address.index') }}"
                    class="block text-sm transition {{ request()->routeIs('user.address.*') ? 'font-bold text-emerald-600' : 'text-slate-600 hover:text-emerald-600' }}">Alamat</a>

                <a href="{{ route('user.password.edit') }}"
                    class="block text-sm transition {{ request()->routeIs('user.password.*') ? 'font-bold text-emerald-600' : 'text-slate-600 hover:text-emerald-600' }}">Ubah
                    Password</a>

                <a href="{{ route('user.security') }}"
                    class="block text-sm transition {{ request()->routeIs('user.security') ? 'font-bold text-emerald-600' : 'text-slate-600 hover:text-emerald-600' }}">Keamanan</a>
            </div>
        </div>
        <div class="mt-2 pt-4 border-t border-slate-100">
            <a href="/riwayat-pesanan"
                class="flex items-center gap-2 font-bold text-slate-800 hover:text-emerald-600 transition">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
                Riwayat Pembelian
            </a>
        </div>

    </div>
</div>