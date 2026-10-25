<x-layout>
    <x-slot:title>{{ $product->name }}</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ 
            selectedImage: '{{ $product->image }}',
            selectedColor: '{{ $product->colors ? $product->colors[0]['name'] : '' }}',
            selectedSize: '{{ $product->sizes ? $product->sizes[0] : '' }}',
            qty: 1,
            stocks: {{ json_encode($product->variant_stocks ?? (object) []) }},
            defaultStock: {{ $product->stock ?? 0 }},
            defaultPrice: {{ $product->price ?? 0 }},
            getCurrentStock() {
                if (Object.keys(this.stocks).length > 0) {
                    if (this.selectedColor && this.selectedSize) {
                        let key = `${this.selectedColor}-${this.selectedSize}`;
                        if (this.stocks[key] !== undefined) {
                            return typeof this.stocks[key] === 'object' ? (this.stocks[key].stock ?? 0) : this.stocks[key];
                        }
                    }
                    if (this.selectedColor && !this.selectedSize) {
                        if (this.stocks[this.selectedColor] !== undefined) {
                            return typeof this.stocks[this.selectedColor] === 'object' ? (this.stocks[this.selectedColor].stock ?? 0) : this.stocks[this.selectedColor];
                        }
                    }
                    if (this.selectedSize && !this.selectedColor) {
                        if (this.stocks[this.selectedSize] !== undefined) {
                            return typeof this.stocks[this.selectedSize] === 'object' ? (this.stocks[this.selectedSize].stock ?? 0) : this.stocks[this.selectedSize];
                        }
                    }
                    return 0;
                }
                return this.defaultStock;
            },
            getCurrentPrice() {
                if (Object.keys(this.stocks).length > 0) {
                    if (this.selectedColor && this.selectedSize) {
                        let key = `${this.selectedColor}-${this.selectedSize}`;
                        if (this.stocks[key] !== undefined && typeof this.stocks[key] === 'object' && this.stocks[key].price) {
                            return this.stocks[key].price;
                        }
                    }
                    if (this.selectedColor && this.stocks[this.selectedColor] !== undefined && typeof this.stocks[this.selectedColor] === 'object' && this.stocks[this.selectedColor].price) {
                        return this.stocks[this.selectedColor].price;
                    }
                }
                return this.defaultPrice;
            },
            getCurrentPriceFormatted() {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(this.getCurrentPrice());
            }
         }">

        <!-- Navigasi Breadcrumb Aksesibilitas (Gantikan yang lama dengan ini) -->
        <nav aria-label="Breadcrumb"
            class="mb-6 flex items-center gap-2 text-xs text-slate-500 font-semibold flex-wrap">
            <ol class="flex items-center space-x-2 w-full">
                <li>
                    <a href="/katalog"
                        class="hover:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded px-1 transition">
                        Katalog
                    </a>
                </li>
                <li><span class="mx-1" aria-hidden="true">/</span></li>

                @if($product->subSubCategory && $product->subSubCategory->subCategory)
                    <!-- Level 1: Kategori Utama -->
                    <li>
                        <a href="/katalog?kategori={{ $product->subSubCategory->subCategory->category->slug }}"
                            class="hover:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded px-1 transition">
                            {{ $product->subSubCategory->subCategory->category->name }}
                        </a>
                    </li>
                    <li><span class="mx-1" aria-hidden="true">/</span></li>

                    <!-- Level 2: Sub-Kategori -->
                    <li>
                        <a href="/katalog?sub={{ $product->subSubCategory->subCategory->slug }}"
                            class="hover:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded px-1 transition">
                            {{ $product->subSubCategory->subCategory->name }}
                        </a>
                    </li>
                    <li><span class="mx-1" aria-hidden="true">/</span></li>

                    <!-- Level 3: Nama Produk (Current Page) -->
                    <li aria-current="page" class="font-bold text-slate-800 px-1 truncate max-w-[150px] sm:max-w-xs">
                        {{ $product->name }}
                    </li>
                @endif
            </ol>
        </nav>

        <div class="grid lg:grid-cols-12 gap-10">
            <div class="lg:col-span-5 space-y-4">
                <div
                    class="aspect-square rounded-3xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm relative">
                    <img :src="selectedImage" alt="{{ $product->name }}"
                        class="w-full h-full object-cover transition-all duration-300">
                    @if($product->discount_percentage)
                        <span
                            class="absolute top-4 left-4 bg-rose-500 text-white font-extrabold text-xs px-2.5 py-1 rounded-lg">
                            HEMAT {{ $product->discount_percentage }}%
                        </span>
                    @endif
                </div>

                @if($product->images)
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @foreach($product->images as $img)
                            <button @click="selectedImage = '{{ $img }}'"
                                :class="selectedImage === '{{ $img }}' ? 'ring-2 ring-emerald-600' : 'opacity-70 hover:opacity-100'"
                                class="w-20 h-20 rounded-xl overflow-hidden border border-slate-200 flex-shrink-0 transition">
                                <img src="{{ $img }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="lg:col-span-7 space-y-6">
                <div>
                    <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">
                        {{ $product->subCategory?->category?->name ?? 'Kategori' }}
                        @if($product->subCategory)
                            • {{ $product->subCategory->name }}
                        @endif
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ $product->name }}</h1>
                    <div class="space-y-6 mt-4">

                        @php
                            $sold = $product->sold_count ?? 0;
                            $formattedSold = $sold >= 1000 ? floor($sold / 1000) . 'rb+' : $sold;

                            $dynamicRating = $product->reviews->count() > 0
                                ? number_format($product->reviews->avg('rating'), 1)
                                : number_format($product->rating ?? 0, 1);
                        @endphp
                        <div class="flex items-center justify-between mt-3">
                            <div class="flex items-center gap-3 text-xs text-slate-600">
                                <div class="flex items-center text-amber-400">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <span class="ml-1 font-bold text-slate-900">{{ $dynamicRating }}</span>
                                </div>
                                <span>•</span>
                                <span>Terjual {{ $formattedSold }}</span>
                            </div>

                            @php
                                $isFavorited = auth()->check() ? auth()->user()->favorites->contains($product->id) : false;
                            @endphp

                            <form action="{{ route('favorites.toggle', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-1.5 text-xs font-bold transition group {{ $isFavorited ? 'text-rose-500' : 'text-slate-400 hover:text-rose-500' }}"
                                    title="{{ $isFavorited ? 'Hapus dari Favorit' : 'Tambahkan ke Favorit' }}">

                                    <svg class="w-5 h-5 transition-colors {{ $isFavorited ? 'fill-rose-500 text-rose-500' : 'group-hover:fill-rose-500' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                    <span class="hidden sm:inline">{{ $isFavorited ? 'Tersimpan' : 'Favorit' }}</span>
                                </button>
                            </form>

                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-baseline gap-3">
                            <span class="text-3xl font-black text-slate-900" x-text="getCurrentPriceFormatted()">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            @if($product->original_price)
                                <span class="text-sm text-slate-400 line-through">
                                    Rp {{ number_format($product->original_price, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                        @if(!empty($product->colors))
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-700">
                                    Pilih Warna: <span class="text-emerald-700" x-text="selectedColor"></span>
                                </label>
                                <div class="flex gap-2 flex-wrap">
                                    @foreach($product->colors as $c)
                                        <button type="button"
                                            @click="selectedColor = '{{ $c['name'] }}'; @if(!empty($c['image'])) selectedImage = '{{ $c['image'] }}'; @endif if(qty > getCurrentStock()) qty = Math.max(1, getCurrentStock())"
                                            :class="selectedColor === '{{ $c['name'] }}' ? 'ring-2 ring-emerald-600 border-white' : 'border-slate-300'"
                                            class="px-3.5 py-1.5 rounded-full border text-xs font-semibold flex items-center gap-2 bg-white transition">
                                            <span class="w-3.5 h-3.5 rounded-full border"
                                                style="background-color: {{ $c['hex'] }};"></span>
                                            {{ $c['name'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if(!empty($product->sizes))
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-700">
                                    Pilih Ukuran: <span class="text-emerald-700" x-text="selectedSize"></span>
                                </label>
                                <div class="flex gap-2 flex-wrap">
                                    @foreach($product->sizes as $sz)
                                        <button type="button"
                                            @click="selectedSize = '{{ $sz }}'; if(qty > getCurrentStock()) qty = Math.max(1, getCurrentStock())"
                                            :class="selectedSize === '{{ $sz }}' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-200'"
                                            class="w-11 h-11 rounded-xl border text-xs font-bold transition flex items-center justify-center">
                                            {{ $sz }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="flex items-center gap-3 pt-2">
                            <span class="text-xs font-bold text-slate-700">Stok Tersedia:</span>
                            <template x-if="getCurrentStock() > 0">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span x-text="getCurrentStock() + ' buah'"></span>
                                </span>
                            </template>
                            <template x-if="getCurrentStock() === 0">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 text-xs font-bold border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Stok Habis
                                </span>
                            </template>
                        </div>
                        <form action="/keranjang/tambah" method="POST"
                            class="flex flex-col sm:flex-row items-stretch sm:items-end gap-4 pt-4 border-t border-slate-100">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="color" :value="selectedColor">
                            <input type="hidden" name="size" :value="selectedSize">
                            <input type="hidden" name="qty" :value="qty">

                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-2">Jumlah:</label>
                                <div
                                    class="flex items-center border border-slate-200 rounded-xl bg-white overflow-hidden w-fit">
                                    <button type="button" @click="if(qty > 1) qty--"
                                        class="px-4 py-2.5 text-slate-600 hover:bg-slate-100 font-bold transition">-</button>
                                    <span class="px-4 py-2.5 text-sm font-bold text-slate-900 min-w-[3rem] text-center"
                                        x-text="qty"></span>
                                    <button type="button" @click="if(qty < getCurrentStock()) qty++"
                                        class="px-4 py-2.5 text-slate-600 hover:bg-slate-100 font-bold transition">+</button>
                                </div>
                            </div>

                            <button type="submit" :disabled="getCurrentStock() <= 0"
                                :class="getCurrentStock() <= 0 ? 'bg-slate-300 cursor-not-allowed text-slate-500 shadow-none' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-600/25'"
                                class="flex-1 py-3.5 px-6 rounded-xl font-bold text-sm transition">
                                <span x-text="getCurrentStock() > 0 ? '+ Tambah ke Keranjang' : 'Stok Habis'"></span>
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div class="pt-6 text-sm text-slate-600 leading-relaxed border-t border-slate-100 mt-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-2">Deskripsi Produk</h3>
            <p>{{ $product->description }}</p>
        </div>
        <div
            class="mt-12 p-6 bg-white border border-slate-200 rounded-2xl flex flex-col lg:flex-row gap-8 items-start lg:items-center shadow-sm">
            <div
                class="flex items-start sm:items-center gap-4 pr-0 lg:pr-8 lg:border-r border-slate-200 min-w-max w-full lg:w-auto">

                <!-- Foto Profil Toko -->
                <div class="relative shrink-0">
                    <img src="{{ $product->seller->avatar ?? ('https://ui-avatars.com/api/?name=' . urlencode($product->seller->name ?? 'Variety') . '&background=F3F4F6&color=10B981') }}"
                        alt="Logo Toko" class="w-20 h-20 rounded-full border border-slate-200 object-cover">
                </div>

                <!-- Detail Nama & Aksi -->
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-900 line-clamp-1">
                        {{ $product->seller->name ?? 'Variety' }}
                    </h3>
                    <p class="text-xs text-slate-500 mb-3 mt-0.5">
                        {{ $storeStats['active_status'] ?? ($product->seller?->updated_at ? 'Aktif ' . $product->seller->updated_at->locale('id')->diffForHumans() : 'Aktif baru saja') }}
                    </p>

                    <div class="flex flex-wrap gap-2">
                        <!-- Tombol Chat (Hanya muncul jika bukan barang milik sendiri) -->
                        @if(!empty($product->user_id) && $product->user_id !== auth()->id())
                            <form action="{{ route('chat.start', $product->user_id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2.5 border border-emerald-600 text-emerald-600 text-sm font-bold rounded-xl hover:bg-emerald-50 transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                        </path>
                                    </svg>
                                    Chat Penjual
                                </button>
                            </form>
                        @endif

                        <!-- Tombol Kunjungi Toko (Ukurannya sudah disamakan dengan Chat) -->
                        <a href="{{ route('store.show', $product->user_id ?? 1) }}"
                            class="px-4 py-2.5 border border-slate-300 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Kunjungi Toko
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kanan: Statistik Toko -->
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-6 w-full text-sm">

                <!-- Baris 1 -->
                <div class="flex sm:flex-col lg:flex-row justify-between lg:items-center gap-1">
                    <span class="text-slate-500">Penilaian</span>
                    <span class="font-bold text-emerald-600">{{ $storeStats['rating'] ?? '5.0' }} / 5.0</span>
                </div>
                <div class="flex sm:flex-col lg:flex-row justify-between lg:items-center gap-1">
                    <span class="text-slate-500">Chat Dibalas</span>
                    <span class="font-bold text-emerald-600">{{ $storeStats['reply_rate'] ?? '100%' }}</span>
                </div>
                <div class="flex sm:flex-col lg:flex-row justify-between lg:items-center gap-1">
                    <span class="text-slate-500">Bergabung</span>
                    <span
                        class="font-bold text-emerald-600">{{ $storeStats['joined'] ?? ($product->seller?->created_at ? $product->seller->created_at->locale('id')->diffForHumans(null, true) . ' lalu' : 'Baru bergabung') }}</span>
                </div>

                <!-- Baris 2 -->
                <div class="flex sm:flex-col lg:flex-row justify-between lg:items-center gap-1">
                    <span class="text-slate-500">Produk</span>
                    <span
                        class="font-bold text-emerald-600">{{ $storeStats['product_count'] ?? ($product->seller?->products()->count() ?? 0) }}</span>
                </div>
                <div class="flex sm:flex-col lg:flex-row justify-between lg:items-center gap-1">
                    <span class="text-slate-500">Waktu Balas</span>
                    <span
                        class="font-bold text-emerald-600">{{ $storeStats['response_speed'] ?? 'hitungan menit' }}</span>
                </div>
                <div class="flex sm:flex-col lg:flex-row justify-between lg:items-center gap-1">
                    <span class="text-slate-500">Pengikut</span>
                    <span class="font-bold text-emerald-600">{{ $storeStats['followers_count'] ?? '0' }}</span>
                </div>

            </div>
        </div>
        <div class="mt-12 pt-8 border-t border-slate-200">
            <h2 class="text-xl font-extrabold text-slate-900 mb-6 uppercase tracking-tight">Ulasan Pembeli
            </h2>

            @php
                // Kalkulasi Statistik Ulasan
                $totalReviews = $product->reviews->count();
                $averageRating = $totalReviews > 0 ? round($product->reviews->avg('rating'), 1) : 0;
                $satisfiedCount = $product->reviews->where('rating', '>=', 4)->count();
                $satisfiedPercent = $totalReviews > 0 ? round(($satisfiedCount / $totalReviews) * 100) : 0;

                $ratingCounts = [
                    5 => $product->reviews->where('rating', 5)->count(),
                    4 => $product->reviews->where('rating', 4)->count(),
                    3 => $product->reviews->where('rating', 3)->count(),
                    2 => $product->reviews->where('rating', 2)->count(),
                    1 => $product->reviews->where('rating', 1)->count(),
                ];


                $reviewsData = $product->reviews->map(function ($rev) {
                    $name = $rev->user->name ?? 'Pembeli';
                    $maskedName = mb_strlen($name) > 2
                        ? (mb_substr($name, 0, 1) . '***' . mb_substr($name, -1, 1))
                        : $name;

                    $images = [];
                    if (!empty($rev->images)) {
                        $images = is_array($rev->images) ? $rev->images : json_decode($rev->images, true);
                    }

                    return [
                        'id' => $rev->id,
                        'user_name' => strtoupper($maskedName),
                        'rating' => (int) $rev->rating,
                        'comment' => $rev->comment,
                        'color' => $rev->color,
                        'size' => $rev->size,
                        'images' => $images ?: [],
                        'created_at' => $rev->created_at ? $rev->created_at->toISOString() : now()->toISOString(),
                        'created_at_human' => $rev->created_at ? $rev->created_at->diffForHumans() : 'baru saja',
                    ];
    
                })->values();

            @endphp
        </div>
        <div
            class="border border-slate-200 rounded-2xl bg-gradient-to-b from-white to-orange-50/30 p-6 sm:p-8 mb-8 flex flex-col md:flex-row gap-8 justify-between items-start md:items-center">
            <div class="flex-1">
                <div class="flex items-baseline gap-2 mb-2">
                    <svg class="w-10 h-10 text-amber-400 fill-current" viewBox="0 0 20 20">
                        <path
                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                    </svg>
                    <span class="text-5xl font-black text-slate-900">{{ number_format($averageRating, 1) }}</span>
                    <span class="text-xl text-slate-500 font-medium">/ 5.0</span>
                </div>
                <p class="text-lg font-bold text-slate-800 mb-1">{{ $satisfiedPercent }}% pembeli merasa puas</p>
                <p class="text-sm text-slate-500">{{ $totalReviews }} rating • {{ $totalReviews }} ulasan</p>
            </div>

            <!-- Progress Bar Rating Kanan -->
            <div class="flex-1 w-full max-w-sm space-y-2">
                @for($i = 5; $i >= 1; $i--)
                    @php
                        $count = $ratingCounts[$i];
                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-3 text-sm">
                        <div class="flex items-center gap-1 w-8 text-slate-500 font-medium">
                            <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                            </svg>
                            <span>{{ $i }}</span>
                        </div>
                        <div class="flex-1 h-2 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $percentage }}%;"></div>
                        </div>
                        <div class="w-10 text-right text-slate-500">({{ $count }})</div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Layout Grid Filter & Daftar Ulasan -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="reviewFilterSystem(@js($reviewsData))">

            <!-- Sidebar Kiri: Filter Ulasan -->
            <div class="lg:col-span-3">
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm sticky top-24">
                    <div class="p-4 font-extrabold text-slate-900 border-b border-slate-100 flex items-center justify-between">
                        <span class="tracking-tight text-sm">FILTER ULASAN</span>
                        <button type="button" x-show="selectedRatings.length > 0 || withMediaOnly" 
                                @click="resetFilters()" 
                                class="text-xs text-emerald-600 hover:text-emerald-800 font-bold hover:underline transition">
                            Reset
                        </button>
                    </div>

                    <!-- Filter Media -->
                    <div class="p-4 border-b border-slate-100">
                        <div class="flex justify-between items-center font-bold text-slate-800 mb-3 text-sm">
                            <span>Media</span>
                            <span class="text-xs text-slate-400 font-normal" x-text="allMedia.length + ' Foto'"></span>
                        </div>
                        <label class="flex items-center justify-between gap-3 cursor-pointer group select-none">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" x-model="withMediaOnly"
                                    class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                <span class="text-sm text-slate-600 group-hover:text-slate-900 transition">Dengan Foto & Video</span>
                            </div>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full" 
                                  :class="allMedia.length > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-400'"
                                  x-text="allMedia.length"></span>
                        </label>
                    </div>

                    <!-- Filter Rating -->
                    <div class="p-4">
                        <div class="flex justify-between items-center font-bold text-slate-800 mb-3 text-sm">
                            <span>Rating Bintang</span>
                            <span class="text-xs text-slate-400 font-normal" x-show="selectedRatings.length > 0" x-text="selectedRatings.length + ' dipilih'"></span>
                        </div>
                        <div class="space-y-2.5">
                            @for($i = 5; $i >= 1; $i--)
                                <label class="flex items-center justify-between gap-3 cursor-pointer group select-none p-1.5 rounded-lg hover:bg-slate-50 transition">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" :value="{{ $i }}" x-model="selectedRatings"
                                            class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                        <div class="flex items-center gap-1.5 text-sm text-slate-700 font-medium">
                                            <div class="flex text-amber-400">
                                                @for($s = 1; $s <= $i; $s++)
                                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span>{{ $i }} Bintang</span>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-400">({{ $ratingCounts[$i] }})</span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Reset Filter Bar -->
                    <div x-show="selectedRatings.length > 0 || withMediaOnly" 
                         class="p-3 bg-emerald-50/70 border-t border-slate-100 flex items-center justify-between text-xs transition">
                        <span class="text-emerald-800 font-semibold">Filter aktif</span>
                        <button type="button" @click="resetFilters()" class="text-emerald-700 hover:text-emerald-900 font-bold hover:underline">
                            Hapus Semua
                        </button>
                    </div>
                </div>
            </div>

            <!-- Konten Kanan: Galeri & List Ulasan -->
            <div class="lg:col-span-9 space-y-8">

                <!-- Foto & Video Pembeli -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-extrabold text-slate-900 uppercase tracking-tight text-sm flex items-center gap-2">
                            <span>Foto & Video Pembeli</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600" x-text="allMedia.length"></span>
                        </h3>
                        <template x-if="allMedia.length > 0">
                            <span class="text-xs text-slate-400">Klik foto untuk melihat ulasan pembeli</span>
                        </template>
                    </div>

                    <!-- Jika ada media -->
                    <template x-if="allMedia.length > 0">
                        <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                            <template x-for="(item, idx) in allMedia.slice(0, 8)" :key="idx">
                                <button type="button" @click="openPreview(item)"
                                    class="relative w-20 h-20 rounded-xl overflow-hidden border-2 border-slate-200 hover:border-emerald-500 flex-shrink-0 group focus:outline-none transition-all shadow-sm">
                                    <img :src="item.url" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" alt="Foto Pembeli">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/15 transition-colors"></div>
                                </button>
                            </template>
                            <template x-if="allMedia.length > 8">
                                <button type="button" @click="withMediaOnly = true"
                                    class="w-20 h-20 bg-slate-800 hover:bg-slate-900 rounded-xl flex-shrink-0 flex flex-col items-center justify-center text-white font-bold text-xs shadow-sm transition">
                                    <span class="text-base" x-text="'+' + (allMedia.length - 8)"></span>
                                    <span class="text-[10px] text-slate-300">Lainnya</span>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Jika belum ada media -->
                    <template x-if="allMedia.length === 0">
                        <div class="p-6 text-center rounded-xl bg-slate-50 border border-dashed border-slate-200">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="text-xs font-semibold text-slate-600">Belum ada foto atau video dari pembeli.</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Jadilah yang pertama mengunggah ulasan bergambar!</p>
                        </div>
                    </template>
                </div>

                <!-- Header Ulasan Pilihan & Sortir -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-extrabold text-slate-900 uppercase tracking-tight text-sm">Daftar Ulasan</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            Menampilkan <span class="font-bold text-slate-800" x-text="filteredReviews.length"></span> dari
                            <span class="font-bold text-slate-800" x-text="reviews.length"></span> ulasan
                            <span x-show="selectedRatings.length > 0 || withMediaOnly" class="text-xs text-emerald-600 font-semibold ml-1">
                                (Filter Aktif)
                            </span>
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <label for="reviewSortSelect" class="text-sm font-bold text-slate-700">Urutkan</label>
                        <select id="reviewSortSelect" x-model="sortBy"
                            class="px-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-medium text-slate-700 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 cursor-pointer shadow-sm">
                            <option value="terbaru">Terbaru</option>
                            <option value="tertinggi">Rating Tertinggi</option>
                            <option value="terendah">Rating Terendah</option>
                            <option value="paling_membantu">Paling Membantu</option>
                        </select>
                    </div>
                </div>

                <!-- Daftar Ulasan -->
                <template x-if="reviews.length === 0">
                    <div class="p-12 text-center bg-slate-50 rounded-2xl border border-slate-200">
                        <p class="text-slate-500 text-sm">Belum ada ulasan untuk produk ini.</p>
                    </div>
                </template>

                <template x-if="reviews.length > 0 && filteredReviews.length === 0">
                    <div class="p-12 text-center bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                        <div class="w-12 h-12 rounded-full bg-slate-200/80 flex items-center justify-center text-slate-400 mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <p class="text-slate-800 font-bold text-sm">Tidak ada ulasan yang sesuai dengan filter.</p>
                        <p class="text-slate-500 text-xs">Coba sesuaikan filter rating atau media untuk melihat ulasan lainnya.</p>
                        <button type="button" @click="resetFilters()" class="inline-block mt-2 px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition shadow-sm">
                            Reset Filter
                        </button>
                    </div>
                </template>

                <div class="space-y-6" x-show="filteredReviews.length > 0">
                    <template x-for="review in filteredReviews" :key="review.id">
                        <div class="pb-6 border-b border-slate-100 last:border-0 transition-all">
                            <!-- Bintang & Waktu -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex text-amber-400">
                                        <template x-for="i in 5" :key="i">
                                            <svg class="w-4 h-4" :class="i <= review.rating ? 'fill-current text-amber-400' : 'text-slate-200 fill-current'"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                            </svg>
                                        </template>
                                    </div>
                                    <span class="text-sm text-slate-400" x-text="review.created_at_human"></span>
                                </div>
                            </div>

                            <!-- User Info -->
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden flex items-center justify-center text-slate-400">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-900 uppercase" x-text="review.user_name"></p>
                            </div>

                            <!-- Varian Info -->
                            <template x-if="review.color || review.size">
                                <p class="text-sm text-slate-500 mb-3">
                                    <span x-text="'Varian: ' + (review.color || '') + (review.color && review.size ? ' - ' : '') + (review.size || '')"></span>
                                </p>
                            </template>

                            <!-- Komentar Ulasan -->
                            <p class="text-base text-slate-800 leading-relaxed mb-4" x-text="review.comment"></p>

                            <!-- Lampiran Foto Ulasan -->
                            <template x-if="review.images && review.images.length > 0">
                                <div class="flex gap-2.5 flex-wrap pt-1">
                                    <template x-for="(imgUrl, imgIdx) in review.images" :key="imgIdx">
                                        <button type="button" @click="openPreview({ url: imgUrl, review: review })"
                                            class="w-20 h-20 rounded-xl overflow-hidden border border-slate-200 hover:border-emerald-500 hover:scale-105 transition-all shadow-sm focus:outline-none">
                                            <img :src="imgUrl" class="w-full h-full object-cover" alt="Foto Ulasan">
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Modal Lightbox Preview Foto Pembeli -->
            <div x-show="previewModalOpen" style="display: none;" 
                 class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 p-6 text-center">
                    <div x-show="previewModalOpen" x-transition.opacity 
                         class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" 
                         @click="closePreview()"></div>
                         
                    <div x-show="previewModalOpen" x-transition 
                         class="relative bg-white rounded-2xl overflow-hidden shadow-2xl max-w-2xl w-full text-left z-10">
                        <div class="flex justify-between items-center p-4 border-b border-slate-100">
                            <h4 class="font-bold text-slate-800 text-sm">Foto dari <span x-text="previewReview?.user_name"></span></h4>
                            <button @click="closePreview()" class="text-slate-400 hover:text-slate-600 p-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="bg-black/90 max-h-[65vh] flex items-center justify-center overflow-hidden">
                            <img :src="previewMedia" class="max-h-[65vh] w-auto max-w-full object-contain" alt="Preview Foto">
                        </div>
                        <div class="p-5 bg-white" x-if="previewReview">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="flex text-amber-400">
                                    <template x-for="s in 5">
                                        <svg class="w-4 h-4 fill-current" :class="s <= previewReview.rating ? 'text-amber-400' : 'text-slate-200'" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                    </template>
                                </div>
                                <span class="text-xs text-slate-400" x-text="previewReview.created_at_human"></span>
                            </div>
                            <p class="text-sm text-slate-700 leading-relaxed" x-text="previewReview.comment"></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script>
            function reviewFilterSystem(initialReviews) {
                return {
                    reviews: initialReviews || [],
                    selectedRatings: [],
                    withMediaOnly: false,
                    sortBy: 'terbaru',
                    previewModalOpen: false,
                    previewMedia: null,
                    previewReview: null,

                    get allMedia() {
                        const list = [];
                        this.reviews.forEach(r => {
                            if (r.images && Array.isArray(r.images)) {
                                r.images.forEach(img => {
                                    if (img) list.push({ url: img, review: r });
                                });
                            }
                        });
                        return list;
                    },

                    get filteredReviews() {
                        return this.reviews.filter(r => {
                            if (this.selectedRatings.length > 0) {
                                const ratings = this.selectedRatings.map(Number);
                                if (!ratings.includes(Number(r.rating))) {
                                    return false;
                                }
                            }
                            if (this.withMediaOnly) {
                                if (!r.images || !Array.isArray(r.images) || r.images.length === 0) {
                                    return false;
                                }
                            }
                            return true;
                        }).sort((a, b) => {
                            if (this.sortBy === 'terbaru') {
                                return new Date(b.created_at) - new Date(a.created_at);
                            } else if (this.sortBy === 'tertinggi') {
                                return b.rating - a.rating;
                            } else if (this.sortBy === 'terendah') {
                                return a.rating - b.rating;
                            } else if (this.sortBy === 'paling_membantu') {
                                if (b.rating !== a.rating) return b.rating - a.rating;
                                return (b.comment ? b.comment.length : 0) - (a.comment ? a.comment.length : 0);
                            }
                            return 0;
                        });
                    },

                    resetFilters() {
                        this.selectedRatings = [];
                        this.withMediaOnly = false;
                        this.sortBy = 'terbaru';
                    },

                    openPreview(item) {
                        this.previewMedia = item.url;
                        this.previewReview = item.review;
                        this.previewModalOpen = true;
                    },

                    closePreview() {
                        this.previewModalOpen = false;
                        this.previewMedia = null;
                        this.previewReview = null;
                    }
                };
            }
        </script>
    </div>
</x-layout>
