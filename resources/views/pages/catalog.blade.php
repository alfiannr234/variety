<x-layout>
    <x-slot:title>
        {{ $pageTitle ?? 'Katalog Produk' }}
    </x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(!request()->hasAny(['kategori', 'sub', 'q']))
            <div x-data="{ 
                                                activeSlide: 0, 
                                                timer: null,
                                                startTimer() {
                                                    this.timer = setInterval(() => {
                                                        this.activeSlide = this.activeSlide === 2 ? 0 : this.activeSlide + 1;
                                                    }, 4000);
                                                },
                                                stopTimer() {
                                                    clearInterval(this.timer);
                                                }
                                             }" x-init="startTimer()" @mouseenter="stopTimer()" @mouseleave="startTimer()"
                class="relative w-full h-[180px] sm:h-[280px] md:h-[350px] rounded-2xl overflow-hidden group mb-10 shadow-sm border border-slate-200">

                <div class="flex w-full h-full transition-transform duration-700 ease-in-out"
                    :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">

                    <div
                        class="w-full h-full flex-shrink-0 relative bg-gradient-to-r from-amber-500 to-orange-400 flex items-center justify-center">
                        <h2 class="text-3xl sm:text-4xl font-black text-white tracking-widest drop-shadow-md">PROMO 1</h2>
                    </div>

                    <div
                        class="w-full h-full flex-shrink-0 relative bg-gradient-to-r from-emerald-500 to-teal-400 flex items-center justify-center">
                        <h2 class="text-3xl sm:text-4xl font-black text-white tracking-widest drop-shadow-md">PROMO 2</h2>
                    </div>

                    <div
                        class="w-full h-full flex-shrink-0 relative bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center">
                        <h2 class="text-3xl sm:text-4xl font-black text-white tracking-widest drop-shadow-md">PROMO 3</h2>
                    </div>
                </div>

                <button @click="activeSlide = activeSlide === 0 ? 2 : activeSlide - 1"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/20 text-white flex items-center justify-center hover:bg-black/50 transition opacity-0 group-hover:opacity-100 backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <button @click="activeSlide = activeSlide === 2 ? 0 : activeSlide + 1"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/20 text-white flex items-center justify-center hover:bg-black/50 transition opacity-0 group-hover:opacity-100 backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <div class="absolute bottom-4 left-0 w-full px-6 flex justify-between items-end">
                    <!-- Titik Navigasi -->
                    <div class="flex gap-2 mb-1">
                        <button @click="activeSlide = 0" :class="activeSlide === 0 ? 'w-6 bg-white' : 'w-2 bg-white/50'"
                            class="h-2 rounded-full transition-all duration-300"></button>
                        <button @click="activeSlide = 1" :class="activeSlide === 1 ? 'w-6 bg-white' : 'w-2 bg-white/50'"
                            class="h-2 rounded-full transition-all duration-300"></button>
                        <button @click="activeSlide = 2" :class="activeSlide === 2 ? 'w-6 bg-white' : 'w-2 bg-white/50'"
                            class="h-2 rounded-full transition-all duration-300"></button>
                    </div>
                </div>
            </div>
        @endif

        <!-- HEADER JUDUL & TOMBOL HAPUS FILTER -->
        <div
            class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                @if(request('sub'))
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Kategori: {{ ucwords(str_replace('-', ' ', request('sub'))) }}
                    </h1>
                @elseif(isset($activeCategory) && $activeCategory)
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Kategori: {{ $activeCategory->name }}
                    </h1>
                @elseif(request('q'))
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Hasil Pencarian: "{{ request('q') }}"
                    </h1>
                @else
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Katalog Produk</h1>
                @endif
            </div>

            <!-- Tombol Reset Filter -->
            @if(request()->anyFilled(['kategori', 'sub', 'q', 'sort', 'min_price', 'max_price']))
                <a href="/katalog"
                    class="inline-flex items-center gap-1.5 text-sm font-bold text-rose-500 hover:text-rose-700 bg-rose-50 px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    Hapus Filter
                </a>
            @endif
        </div>

        <!-- LAYOUT UTAMA: SIDEBAR & GRID PRODUK -->
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- SIDEBAR KIRI (Hanya muncul jika sudah ada parameter kategori/sub/pencarian) -->
            @if(request()->hasAny(['kategori', 'sub', 'q']))
                <aside class="w-full lg:w-64 shrink-0">
                    <form action="/katalog" method="GET"
                        class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm space-y-6 sticky top-24">

                        <!-- Pertahankan Kategori & Pencarian Aktif -->
                        @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                        @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                        @endif
                        @if(request('sub')) <input type="hidden" name="sub" value="{{ request('sub') }}"> @endif

                        <!-- Filter Urutkan -->
                        <div>
                            <h3 class="font-bold text-slate-800 mb-3 text-xs uppercase tracking-wider">Urutkan Berdasarkan
                            </h3>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="sort" value="terbaru"
                                        class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" {{ request('sort', 'terbaru') == 'terbaru' ? 'checked' : '' }}>
                                    <span
                                        class="text-sm text-slate-600 font-medium group-hover:text-emerald-600 transition">Terbaru</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="sort" value="populer"
                                        class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" {{ request('sort') == 'populer' ? 'checked' : '' }}>
                                    <span
                                        class="text-sm text-slate-600 font-medium group-hover:text-emerald-600 transition">Populer
                                        (Terlaris)</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="sort" value="rating"
                                        class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" {{ request('sort') == 'rating' ? 'checked' : '' }}>
                                    <span
                                        class="text-sm text-slate-600 font-medium group-hover:text-emerald-600 transition">Rating
                                        Terbaik</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="sort" value="termurah"
                                        class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" {{ request('sort') == 'termurah' ? 'checked' : '' }}>
                                    <span
                                        class="text-sm text-slate-600 font-medium group-hover:text-emerald-600 transition">Harga
                                        Terendah</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="sort" value="termahal"
                                        class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" {{ request('sort') == 'termahal' ? 'checked' : '' }}>
                                    <span
                                        class="text-sm text-slate-600 font-medium group-hover:text-emerald-600 transition">Harga
                                        Tertinggi</span>
                                </label>
                            </div>
                        </div>

                        <div class="border-t border-slate-100"></div>

                        <!-- Filter Rentang Harga -->
                        <div>
                            <h3 class="font-bold text-slate-800 mb-3 text-xs uppercase tracking-wider">Rentang Harga</h3>
                            <div class="space-y-3">
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">Rp</span>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                                        placeholder="Harga Minimum" min="0"
                                        class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-emerald-500 outline-none transition">
                                </div>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">Rp</span>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                                        placeholder="Harga Maksimum" min="0"
                                        class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-emerald-500 outline-none transition">
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition shadow-md shadow-emerald-500/20 active:scale-95">
                            Terapkan Filter
                        </button>
                    </form>
                </aside>
            @endif

            <!-- KONTEN KANAN (GRID PRODUK) -->
            <main class="flex-1">
                @if($products->isEmpty())
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
                        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p class="text-slate-500 font-bold text-lg">Maaf, produk tidak ditemukan.</p>
                        <p class="text-slate-400 text-sm mt-1">Coba sesuaikan filter harga atau ganti pengurutan.</p>
                    </div>
                @else
                    <!-- Grid Katalog (Responsif mengikuti ada tidaknya sidebar) -->
                    <div
                        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-{{ request()->hasAny(['kategori', 'sub', 'q']) ? '3' : '4' }} gap-4 sm:gap-6">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- Area Pagination -->
                    @if(method_exists($products, 'links'))
                        <div class="mt-8">
                            {{ $products->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </main>

        </div>
    </div>
    <div x-data="{ showButton: false }" @scroll.window="showButton = window.pageYOffset > 300"
        class="fixed bottom-6 right-6 z-50">

        <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="showButton"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
            aria-label="Kembali ke atas halaman"
            class="p-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full shadow-lg focus:outline-none focus:ring-4 focus:ring-emerald-500/50 transition-colors">

            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18">
                </path>
            </svg>
            <span class="sr-only">Kembali ke atas</span>
        </button>
    </div>
</x-layout>