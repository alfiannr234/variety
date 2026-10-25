<x-admin-layout>
    <x-slot:title>Edit Kategori</x-slot:title>

    <div class="max-w-2xl mx-auto py-8">
        <div class="flex items-center gap-4 mb-8">
            <a href="/admin/categories"
                class="inline-flex flex-shrink-0 items-center justify-center w-10 h-10 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition text-slate-500 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900">Edit Kategori Utama</h1>
        </div>

        <form action="/admin/categories/{{ $category->id }}" method="POST"
            class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori <span
                        class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Tab Mega Menu <span
                        class="text-rose-500">*</span></label>

                <select name="menu_tab_id" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition cursor-pointer">
                    <option value="">-- Pilih Tab Menu --</option>

                    @foreach($menuTabs as $tab)
                        <option value="{{ $tab->id }}" {{ $category->menu_tab_id == $tab->id ? 'selected' : '' }}>
                            {{ $tab->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="/admin/categories"
                    class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">Batal</a>
                <button type="submit"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition">
                    Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>