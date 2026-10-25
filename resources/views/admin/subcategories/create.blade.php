<x-admin-layout>
    <x-slot:title>Tambah Sub-Kategori</x-slot:title>

    <div class="max-w-2xl mx-auto py-8">
        <div class="flex items-center gap-4 mb-8">
            <a href="/admin/categories"
                class="inline-flex flex-shrink-0 items-center justify-center w-10 h-10 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition text-slate-500 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Tambah Jenis Spesifik</h1>
            </div>
        </div>

        <form action="/admin/subcategories" method="POST"
            class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Kategori Utama <span
                        class="text-rose-500">*</span></label>
                <select name="category_id" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition cursor-pointer">
                    <option value="">-- Pilih Kategori Induk --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }} (Tab: {{ $category->menuTab ? $category->menuTab->name : 'Tanpa Tab' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Nama Sub-Kategori -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Sub-Kategori <span
                        class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="Contoh: Kemeja Pria"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="submit"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition">
                    Simpan Sub-Kategori
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>