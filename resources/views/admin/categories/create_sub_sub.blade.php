<x-admin-layout>
    <x-slot:title>Tambah Sub-Sub Kategori</x-slot:title>

    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="/admin/categories"
                class="inline-flex flex-shrink-0 items-center justify-center w-10 h-10 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition text-slate-500 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Tambah Level 3</h1>
                <p class="text-sm text-slate-500 mt-1">Buat spesifikasi / Sub-Sub Kategori baru.</p>
            </div>
        </div>

        <!-- Alert Error -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
                <ul class="list-disc list-inside text-sm text-rose-600 space-y-1 font-semibold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Tambah -->
        <form action="/admin/subsubcategories" method="POST"
            class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            @csrf

            <!-- Pilihan Induk (Level 2) -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Pilih Induk (Sub-Kategori Level 2) <span class="text-rose-500">*</span>
                </label>
                <select name="sub_category_id" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition cursor-pointer">
                    <option value="" disabled selected>-- Pilih Induk Kategori --</option>
                    @foreach($subCategories as $sub)
                        <option value="{{ $sub->id }}" {{ old('sub_category_id') == $sub->id ? 'selected' : '' }}>
                            {{ $sub->category->name }} ➔ {{ $sub->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 mt-2">
                    Tentukan di bawah Sub-Kategori mana item ini akan berada.
                </p>
            </div>

            <!-- Nama Sub-Sub Kategori -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Nama Sub-Sub Kategori (Level 3) <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="Contoh: Kemeja Flanel, Headphone Bluetooth..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition">
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="/admin/categories"
                    class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md shadow-blue-500/20 transition">
                    Simpan Data
                </button>
            </div>
        </form>

    </div>
</x-admin-layout>