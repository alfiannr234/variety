<x-admin-layout>
    <x-slot:title>Manajemen Kategori</x-slot:title>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

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

        @if(session('success'))
            <div
                class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Kategori Produk</h1>
            </div>
            <div class="flex gap-2">
                <a href="/admin/categories/create"
                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition">
                    + Tambah Kategori
                </a>
                <a href="/admin/subcategories/create"
                    class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl shadow-md transition">
                    + Sub-Kategori
                </a>
                <!-- Tombol baru untuk tambah level 3 -->
                <a href="/admin/subsubcategories/create"
                    class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition">
                    + Level 3
                </a>
            </div>
        </div>

        @php
            // Mengumpulkan semua sub kategori & sub-sub kategori dari data $categories agar tidak perlu query ulang
            $allSubCategories = $categories->flatMap->subCategories;
            $allSubSubCategories = $allSubCategories->flatMap->subSubCategories;
        @endphp

        <!-- ============================================== -->
        <!-- TABEL 1: KATEGORI UTAMA (LEVEL 1) -->
        <!-- ============================================== -->
        <h2 class="text-lg font-bold text-slate-800 mb-3">1. Kategori Utama (Level 1)</h2>
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-10">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-extrabold">Nama Kategori</th>
                            <th class="p-4 font-extrabold">Tab Menu Mega</th>
                            <th class="p-4 font-extrabold text-center">Jumlah Produk</th>
                            <th class="p-4 font-extrabold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4">
                                    <p class="font-bold text-slate-900">{{ $category->name }}</p>
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        @foreach($category->subCategories as $sub)
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-600 rounded text-[10px] font-semibold">
                                                {{ $sub->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="p-4">
                                    @if($category->menuTab)
                                        <span
                                            class="px-2.5 py-1 bg-emerald-100 text-emerald-700 font-bold rounded-md text-[11px] uppercase tracking-wider">
                                            {{ $category->menuTab->name }}
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 bg-slate-100 text-slate-500 font-bold rounded-md text-[11px] uppercase tracking-wider">
                                            Tanpa Tab
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @php
                                        $totalProduk = $category->subCategories->sum(function ($sub) {
                                            return $sub->products ? $sub->products->count() : 0;
                                        });
                                    @endphp
                                    <span class="px-3 py-1 bg-blue-50 text-blue-700 font-bold rounded-lg text-xs">
                                        {{ $totalProduk }} Barang
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="/admin/categories/{{ $category->id }}/edit"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="/admin/categories/{{ $category->id }}" method="POST"
                                            class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin hapus kategori ini?')"
                                                class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition"
                                                title="Hapus">
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
                            <tr>
                                <td colspan="4" class="p-6 text-center text-slate-500">Belum ada kategori utama.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TABEL 2: SUB KATEGORI (LEVEL 2) -->
        <!-- ============================================== -->
        <h2 class="text-lg font-bold text-slate-800 mb-3">2. Sub-Kategori (Level 2)</h2>
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-10">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-extrabold">Nama Sub-Kategori</th>
                            <th class="p-4 font-extrabold">Induk Kategori (Level 1)</th>
                            <th class="p-4 font-extrabold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($allSubCategories as $sub)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 font-bold text-slate-900">{{ $sub->name }}</td>
                                <td class="p-4 text-sm text-slate-600">{{ $sub->category->name ?? '-' }}</td>
                                <td class="p-4 text-right">
                                    <form action="/admin/subcategories/{{ $sub->id }}" method="POST" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus Sub-Kategori ini?')"
                                            class="text-xs text-rose-500 hover:text-rose-700 font-bold bg-rose-50 px-3 py-1.5 rounded-lg transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-6 text-center text-slate-500">Belum ada Sub-Kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- TABEL 3: SUB-SUB KATEGORI (LEVEL 3) -->
        <!-- ============================================== -->
        <h2 class="text-lg font-bold text-slate-800 mb-3">3. Spesifikasi / Sub-Sub Kategori (Level 3)</h2>
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-extrabold">Nama Spesifik (Level 3)</th>
                            <th class="p-4 font-extrabold">Induk Sub-Kategori (Level 2)</th>
                            <th class="p-4 font-extrabold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($allSubSubCategories as $subSub)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 font-bold text-slate-900">{{ $subSub->name }}</td>
                                <td class="p-4 text-sm text-slate-600">{{ $subSub->subCategory->name ?? '-' }}</td>
                                <td class="p-4 text-right">
                                    <!-- Pastikan kamu punya route destroy untuk ini jika diaktifkan -->
                                    <form action="/admin/subsubcategories/{{ $subSub->id }}" method="POST"
                                        class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus Item ini?')"
                                            class="text-xs text-rose-500 hover:text-rose-700 font-bold bg-rose-50 px-3 py-1.5 rounded-lg transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-6 text-center text-slate-500">Belum ada Sub-Sub Kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-admin-layout>