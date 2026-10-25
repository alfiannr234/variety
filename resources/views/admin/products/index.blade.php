<x-admin-layout>
    <x-slot:title>Manajemen Produk</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Notifikasi -->
        @if(session('success'))
            <div
                class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div
                class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl font-bold flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Manajemen Produk</h1>
            </div>

            <!-- Tombol Tambah Produk -->
            <a href="/admin/products/create"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md shadow-emerald-500/20 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Produk
            </a>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">

                    <!-- Judul Kolom -->
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-extrabold">Info Produk</th>
                            <th class="p-4 font-extrabold">Kategori</th>
                            <th class="p-4 font-extrabold">Harga</th>
                            <th class="p-4 font-extrabold">Stok</th>
                            <th class="p-4 font-extrabold text-right">Aksi</th>
                        </tr>
                    </thead>

                    <!-- Isi Tabel -->
                    <tbody class="divide-y divide-slate-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-slate-50/50 transition">
                                <!-- Kolom Gambar & Nama -->
                                <td class="p-4 min-w-[250px]">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                            class="w-12 h-12 rounded-lg object-cover border border-slate-200 shadow-sm">
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm line-clamp-1">{{ $product->name }}
                                            </p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <p class="text-[11px] text-slate-500">ID: #{{ $product->id }}</p>
                                                <span class="text-slate-300">•</span>
                                                <!-- Info Pemilik Toko (Sangat berguna untuk akun Admin) -->
                                                <p class="text-[11px] font-bold text-emerald-600">
                                                    Toko: {{ $product->seller->name ?? 'Admin' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom Kategori & Sub -->
                                <td class="p-4">
                                    @if($product->subSubCategory && $product->subSubCategory->subCategory)
                                        <!-- Menampilkan Induk Utama (Level 1) -->
                                        <p class="font-bold text-slate-700">
                                            {{ $product->subSubCategory->subCategory->category->name ?? 'Kategori Dihapus' }}
                                        </p>

                                        <!-- Menampilkan Badge Level 2 & Level 3 -->
                                        <div class="mt-1 flex flex-wrap gap-1">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200 shadow-sm">
                                                {{ $product->subSubCategory->subCategory->name }} ➔
                                                {{ $product->subSubCategory->name }}
                                            </span>
                                        </div>
                                    @else
                                        <p class="text-slate-400 italic text-sm">Kategori tidak valid / Dihapus</p>
                                    @endif
                                </td>
                                <!-- Kolom Harga & Variasi -->
                                <td class="p-4 whitespace-nowrap">
                                    <p class="text-sm font-bold text-slate-900">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                    <!-- Menampilkan Badge jika produk ini punya variasi -->
                                    @if(!empty($product->variant_stocks))
                                        <span
                                            class="inline-block mt-1 px-2 py-0.5 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded text-[10px] font-extrabold uppercase tracking-wider">
                                            Ada Variasi
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Stok -->
                                <td class="p-4">
                                    <!-- Jika stok tipis (di bawah 5), warnanya jadi merah -->
                                    <span
                                        class="text-sm font-bold {{ $product->stock < 5 ? 'text-rose-600' : 'text-emerald-700' }}">
                                        {{ $product->stock }} Pcs
                                    </span>
                                </td>

                                <!-- Kolom Tombol Edit & Hapus -->
                                <td class="p-4 text-right whitespace-nowrap">
                                    <div class="flex justify-end gap-2">
                                        <!-- Tombol Edit -->
                                        <a href="/admin/products/{{ $product->id }}/edit"
                                            class="p-2 text-blue-600 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition"
                                            title="Edit Data">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="/admin/products/{{ $product->id }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Kamu yakin ingin menghapus permanen {{ $product->name }}?')"
                                                class="p-2 text-rose-500 hover:bg-rose-50 hover:text-rose-700 rounded-lg transition"
                                                title="Hapus Data">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <!-- Tampilan jika tidak ada data sama sekali -->
                            <tr>
                                <td colspan="5" class="p-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                        <p class="text-slate-500 font-semibold text-lg">Belum ada data produk</p>
                                        <a href="/admin/products/create"
                                            class="px-5 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-xl transition">
                                            + Tambah Data Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>