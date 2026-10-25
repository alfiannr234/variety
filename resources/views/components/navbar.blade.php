@php
    $megaTabs = \App\Models\MenuTab::where('is_active', true)->orderBy('order_position', 'asc')->get();
@endphp

<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<header x-data="{ megaMenuOpen: false, activeTab: 'tab-{{ $megaTabs->first()->id ?? 0 }}', activeSub: '' }"
    class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-sm">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">

        <!-- Top Bar -->
        <div
            class="bg-slate-100 py-1.5 px-4 sm:px-6 lg:px-8 text-[11px] font-medium text-slate-500 flex justify-between items-center border-b border-slate-200 -mx-4 sm:-mx-6 lg:-mx-8">
            <div class="flex items-center gap-4">
                @auth
                    @if(auth()->user()->role === 'user')
                        <form action="{{ route('upgrade.seller') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" onclick="return confirm('Mulai berjualan di Variety?')"
                                class="hover:text-emerald-600 transition">Jual</button>
                        </form>
                    @else
                        <a href="/admin/dashboard" class="hover:text-emerald-600 font-bold text-emerald-600 transition">Toko
                            Saya</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="hover:text-emerald-600 transition">Jual</a>
                @endauth
                <span class="text-slate-300">|</span>
                <a href="#" class="hover:text-emerald-600 transition">Download App</a>
                <span class="text-slate-300">|</span>
                <span class="flex items-center gap-1">Ikuti kami di
                    <a href="#" class="hover:text-emerald-600">IG</a>,
                    <a href="#" class="hover:text-emerald-600">FB</a>
                </span>
            </div>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-emerald-600 flex items-center gap-1 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Bantuan
                </a>
            </div>
        </div>

        <!-- Main Navbar -->
        <div class="flex items-center justify-between h-20 gap-6">

            <!-- Logo -->
            <a href="/" class="flex items-center gap-2.5 flex-shrink-0 group">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-700 flex items-center justify-center text-white font-bold text-xl shadow-md shadow-emerald-600/20 group-hover:scale-105 transition">
                    V</div>
                <div>
                    <span class="text-xl font-extrabold tracking-tight text-slate-900">VARI<span
                            class="text-emerald-600">ETY</span></span>
                </div>
            </a>

            <!-- Tombol Kategori -->
            <button type="button" @click="megaMenuOpen = !megaMenuOpen"
                :class="megaMenuOpen ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                class="hidden md:flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition">
                Kategori
            </button>

            <!-- Form Pencarian -->
            <form action="/katalog" method="GET" class="hidden md:flex flex-1 max-w-2xl items-center relative">
                <div class="relative w-full">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari di Variety..."
                        class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition text-sm text-slate-800 placeholder-slate-400">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </form>

            <!-- Aksi: Keranjang & Profil -->
            <div class="flex items-center gap-4">
                @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
                <a href="/keranjang" class="relative p-2 text-slate-600 hover:text-emerald-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @if($cartCount > 0)
                        <span
                            class="absolute top-0 right-0 bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full transform translate-x-1 -translate-y-1">{{ $cartCount }}</span>
                    @endif
                </a>

                <div class="w-px h-6 bg-slate-200 mx-1"></div>

                @auth
                    <div x-data="{ userOpen: false }" class="relative flex items-center">
                        <button type="button" @click="userOpen = !userOpen" @click.outside="userOpen = false"
                            class="flex items-center gap-2 p-1.5 rounded-full hover:bg-slate-50 transition focus:outline-none border border-transparent hover:border-emerald-200">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=047857&color=fff"
                                alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                            <span class="text-xs font-semibold text-slate-800">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="userOpen" style="display: none;"
                            class="absolute top-full right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                            <div class="px-4 py-3 border-b border-slate-100 mb-1 bg-slate-50/50">
                                <p class="text-xs text-slate-500">Masuk sebagai</p>
                                <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            @if(auth()->user()->role === 'admin' || auth()->user()->is_admin || auth()->user()->role === 'seller')
                                <a href="/admin/dashboard"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Toko Saya
                                </a>
                            @endif

                            <a href="{{ route('user.profile.edit') }}"
                                class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-emerald-600 transition">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profil Saya
                                </div>
                            </a>
                            <a href="{{ route('favorites.index') }}"
                                class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-emerald-600 transition">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                    Favorit Saya
                                </div>
                            </a>
                            <a href="{{ route('chat.index') }}"
                                class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-emerald-600 transition">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                        </path>
                                    </svg>
                                    Pesan
                                </div>
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form action="/logout" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="/login"
                            class="text-sm font-bold text-emerald-600 px-4 py-2 rounded-lg border border-emerald-600 hover:bg-emerald-50 transition">Masuk</a>
                        <a href="/register"
                            class="text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded-lg transition">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Menu Kategori (Mega Menu) -->
    <div x-show="megaMenuOpen" @click.outside="megaMenuOpen = false" style="display: none;"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-[-10px]"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-[-10px]"
        class="absolute left-0 top-full w-full bg-white shadow-xl border-t border-slate-200 z-50">

        <div class="max-w-7xl mx-auto flex flex-col h-[450px]">

            <!-- TOP TABS -->
            <div
                class="flex items-center gap-3 overflow-x-auto py-3 px-4 sm:px-0 border-b border-slate-100 hide-scrollbar">
                @foreach($megaTabs as $tab)
                    <button type="button"
                        @click="activeTab = 'tab-{{ $tab->id }}'; activeSub = 'cat-{{ $tab->categories->first()->id ?? '' }}'"
                        :class="activeTab === 'tab-{{ $tab->id }}' 
                                    ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' 
                                    : 'bg-slate-50 text-slate-700 border border-slate-200 hover:bg-emerald-50 hover:text-emerald-700'"
                        class="shrink-0 px-5 py-2 text-sm font-bold rounded-full transition flex items-center gap-2">
                        {{ $tab->name }}
                    </button>
                @endforeach
            </div>

            <!-- PERBAIKAN 2: Area Konten Mega Menu sudah benar -->
            <div class="flex flex-1 overflow-hidden">

                <!-- SIDEBAR: KATEGORI -->
                <div class="w-64 bg-slate-50 border-r border-slate-100 overflow-y-auto py-4 px-2">
                    @foreach($megaTabs as $tab)
                        <div x-show="activeTab === 'tab-{{ $tab->id }}'" style="display: none;" class="flex flex-col gap-1">
                            @forelse($tab->categories as $kategori)
                                <button type="button" @click="activeSub = 'cat-{{ $kategori->id }}'"
                                    :class="activeSub === 'cat-{{ $kategori->id }}' ? 'bg-white font-bold text-emerald-700 shadow-sm' : 'text-slate-600 font-semibold hover:bg-slate-200/50'"
                                    class="px-4 py-2.5 rounded-lg text-left text-sm transition">
                                    {{ $kategori->name }}
                                </button>
                            @empty
                                <p class="text-xs text-slate-500 p-4 italic">Belum ada kategori di tab ini.</p>
                            @endforelse
                        </div>
                    @endforeach
                </div>

                <!-- MAIN CONTENT: SUB-KATEGORI -->
                <div class="flex-1 bg-white p-8 overflow-y-auto">
                    @foreach($megaTabs as $tab)
                        @foreach($tab->categories as $kategori)
                            <div x-show="activeTab === 'tab-{{ $tab->id }}' && activeSub === 'cat-{{ $kategori->id }}'"
                                style="display: none;">
                                <h3 class="text-2xl font-extrabold text-slate-900 mb-6">{{ $kategori->name }}</h3>

                                @if($kategori->subCategories->count() > 0)
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-8 mb-8">
                                        @foreach($kategori->subCategories as $sub)
                                            <a href="/katalog?kategori={{ $kategori->slug }}&sub={{ $sub->slug }}"
                                                class="group flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full bg-slate-300 group-hover:bg-emerald-500 transition-colors"></span>
                                                {{ $sub->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500 mb-8 italic">Belum ada jenis spesifik untuk kategori ini.</p>
                                @endif

                                <a href="/katalog?kategori={{ $kategori->slug }}"
                                    class="inline-flex items-center gap-2 px-6 py-2 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition">
                                    Lihat Semua Produk {{ $kategori->name }} →
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                </div>

            </div>

        </div>
    </div>
</header>