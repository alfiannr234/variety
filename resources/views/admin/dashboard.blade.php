<x-admin-layout>
    <x-slot:title>Dashboard</x-slot:title>

    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-slate-900">Ringkasan Toko</h1>

        <!-- Widget Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

            <!-- Widget 1: Total Pendapatan -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pendapatan Kotor</p>
                    <h3 class="text-2xl font-black text-slate-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p class="text-xs font-semibold text-emerald-600 mt-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        +14.5% dari bulan lalu
                    </p>
                </div>
            </div>

            <!-- Widget 2: Pesanan Masuk -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pesanan Baru</p>
                    <h3 class="text-2xl font-black text-slate-900">{{ $pendingOrders }} <span
                            class="text-sm font-medium text-slate-400">Order</span></h3>
                    <p class="text-xs font-semibold text-slate-500 mt-2">Menunggu konfirmasi</p>
                </div>
            </div>

            <!-- Widget 3: Total Produk -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Produk Aktif</p>
                    <!-- Menghitung jumlah produk langsung dari database -->
                    <h3 class="text-2xl font-black text-slate-900">{{ $totalProducts }} <span
                            class="text-sm font-medium text-slate-400">Barang</span></h3>
                    <a href="/admin/products"
                        class="text-xs font-bold text-emerald-600 mt-2 inline-block hover:underline">Kelola Katalog
                        →</a>
                </div>
            </div>
        </div>
</x-admin-layout>