<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel - Variety' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<!-- x-data untuk mengontrol Sidebar di mode HP -->

<body class="bg-slate-50 text-slate-900 antialiased" x-data="{ sidebarOpen: false }">

    <!-- OVERLAY (Latar gelap saat sidebar terbuka di HP) -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" style="display: none;"
        class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden backdrop-blur-sm transition-opacity"></div>

    <!-- ================= SIDEBAR KIRI ================= -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col shadow-2xl lg:shadow-none">

        <!-- Logo Admin -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-slate-950/50">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-emerald-500/20">
                    A</div>
                <span class="text-lg font-extrabold text-white tracking-wider">ADMIN<span
                        class="text-emerald-400">PANEL</span></span>
            </div>
        </div>

        <!-- Menu Sidebar -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <p class="px-2 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Menu Utama</p>

            <a href="/admin/dashboard"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition hover:bg-slate-800 hover:text-white {{ request()->is('admin/dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.profile.edit') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition hover:bg-slate-800 hover:text-white {{ request()->routeIs('admin.profile.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                Profile
            </a>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('menu-tabs.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition hover:bg-slate-800 hover:text-white {{ request()->is('admin/menu-tabs*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7">
                        </path>
                    </svg>
                    <span>Menu Tab</span>
                </a>
                <a href="{{ route('categories.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition hover:bg-slate-800 hover:text-white {{ request()->is('admin/categories*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    Kategori Produk
                </a>
            @endif

            <a href="/admin/products"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition hover:bg-slate-800 hover:text-white {{ request()->is('admin/products*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Produk Etalase
            </a>

            <a href="{{ route('orders.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition hover:bg-slate-800 hover:text-white {{ request()->is('admin/orders*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                Pesanan Masuk
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800">
            <a href="/"
                class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-sm font-bold text-white transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Lihat Toko
            </a>
        </div>
    </aside>

    <div class="lg:ml-64 flex flex-col min-h-screen">

        <!-- NAVBAR ATAS -->
        <header
            class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-30 shadow-sm">

            <button @click="sidebarOpen = true"
                class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>

            <!-- Pencarian (Dummy) -->
            <div class="hidden lg:block relative w-96">
                <input type="text" placeholder="Cari data admin..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <!-- Profil Admin -->
            <div x-data="{ profileOpen: false }" class="relative flex items-center ml-auto lg:ml-0">
                <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false"
                    class="flex items-center gap-2 focus:outline-none">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-extrabold text-slate-900">
                            {{ auth()->check() ? auth()->user()->name : 'Admin User' }}
                        </p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Admin&background=020617&color=fff"
                        class="w-9 h-9 rounded-full object-cover border-2 border-slate-200">
                </button>

                <!-- Dropdown Profil -->
                <div x-show="profileOpen" style="display: none;"
                    class="absolute top-full right-0 mt-3 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50">
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">Keluar</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Notifikasi -->
        {{-- @if(session('success'))
        <div class="px-4 sm:px-6 lg:px-8 mt-6">
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif --}}

        <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-slate-50">
            {{ $slot }}
        </main>

    </div>

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>