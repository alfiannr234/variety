<x-admin-layout>
    <x-slot:title>Tambah Produk</x-slot:title>

    <div class="max-w-5xl mx-auto py-8">

        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="/admin/products"
                class="inline-flex flex-shrink-0 items-center justify-center w-10 h-10 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition text-slate-500 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Tambah Produk Baru</h1>
            </div>
        </div>

        <!-- Alert Error -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
                <div class="flex items-center gap-2 text-rose-700 font-bold mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Terdapat kesalahan pengisian form:
                </div>
                <ul class="list-disc list-inside text-sm text-rose-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/admin/products" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            @csrf

            <!-- Info Dasar -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Produk <span
                            class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Contoh: Kemeja Flanel Kotak"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Spesifikasi Kategori (Level 3) <span
                            class="text-rose-500">*</span></label>
                    <select name="sub_sub_category_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition cursor-pointer">
                        <option value="">-- Pilih Spesifikasi Kategori --</option>
                        @foreach($subSubCategories as $subSub)
                            <option value="{{ $subSub->id }}" {{ old('sub_sub_category_id') == $subSub->id ? 'selected' : '' }}>
                                {{ $subSub->subCategory->category->name }} ➔ {{ $subSub->subCategory->name }} ➔ {{ $subSub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- AREA VARIASI (Menggunakan Alpine.js) -->
            <div x-data="variationSystem()" class="space-y-6">

                <!-- Toggle Aktifkan Variasi -->
                <div
                    class="flex items-center justify-between p-4 border border-emerald-200 rounded-2xl bg-emerald-50/50">
                    <div>
                        <h3 class="font-bold text-emerald-900">Produk ini memiliki beberapa variasi?</h3>
                        <p class="text-xs text-emerald-700 mt-1">Aktifkan jika produk memiliki pilihan (seperti Warna,
                            Ukuran, atau Paket).</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="has_variation" value="1" x-model="hasVariation"
                            class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600">
                        </div>
                    </label>
                </div>

                <!-- Input Harga & Stok Normal (Muncul jika TIDAK ADA variasi) -->
                <div x-show="!hasVariation" class="grid grid-cols-1 md:grid-cols-2 gap-6" x-transition>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Harga (Rp) <span
                                class="text-rose-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price') }}" :required="!hasVariation" min="0"
                            placeholder="Contoh: 150000"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Total Stok <span
                                class="text-rose-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock') }}" :required="!hasVariation" min="0"
                            placeholder="Contoh: 50"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                    </div>
                </div>

                <!-- DAFTAR VARIASI (Muncul jika ADA variasi) -->
                <div x-show="hasVariation" style="display: none;"
                    class="p-5 border border-slate-200 bg-white rounded-2xl shadow-sm" x-transition>
                    <h4 class="font-bold text-slate-800 mb-4 pb-4 border-b border-slate-100">Daftar Variasi Produk</h4>

                    <div class="space-y-4">
                        <!-- Looping Kartu Variasi -->
                        <template x-for="(variant, index) in variants" :key="variant.id">
                            <div
                                class="flex flex-col md:flex-row gap-4 p-5 border border-slate-200 bg-slate-50 rounded-xl relative group">

                                <!-- Tombol Hapus Baris -->
                                <button type="button" @click="removeVariant(index)" title="Hapus Variasi"
                                    class="absolute -top-3 -right-3 w-8 h-8 flex items-center justify-center bg-white border border-slate-200 text-rose-500 rounded-full shadow-sm hover:bg-rose-50 hover:text-rose-700 transition opacity-100 md:opacity-0 md:group-hover:opacity-100">
                                    ✕
                                </button>

                                <div class="w-full md:w-1/3">
                                    <label
                                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama
                                        Variasi <span class="text-rose-500">*</span></label>
                                    <input type="text" :name="'variants[' + index + '][name]'" x-model="variant.name"
                                        :required="hasVariation" placeholder="Misal: Merah XL, atau Paket Hemat"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:border-emerald-500 outline-none">
                                </div>
                                <div class="w-full md:w-1/4">
                                    <label
                                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Harga
                                        (Rp) <span class="text-rose-500">*</span></label>
                                    <input type="number" :name="'variants[' + index + '][price]'"
                                        x-model="variant.price" :required="hasVariation" min="0" placeholder="0"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:border-emerald-500 outline-none">
                                </div>
                                <div class="w-full md:w-1/6">
                                    <label
                                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Stok
                                        <span class="text-rose-500">*</span></label>
                                    <input type="number" :name="'variants[' + index + '][stock]'"
                                        x-model="variant.stock" :required="hasVariation" min="0" placeholder="0"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:border-emerald-500 outline-none">
                                </div>
                                <div class="w-full md:w-1/4">
                                    <label
                                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Foto
                                        <span class="font-normal text-slate-400">(Opsional)</span></label>
                                    <input type="file" :name="'variants[' + index + '][image]'"
                                        accept="image/jpeg,image/png,image/jpg"
                                        class="w-full text-[11px] text-slate-500 file:cursor-pointer file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:font-bold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 transition bg-white border border-slate-200 rounded-lg">
                                </div>
                            </div>
                        </template>

                        <!-- Tombol Tambah Baris -->
                        <button type="button" @click="addVariant()"
                            class="w-full py-3 mt-2 border-2 border-dashed border-emerald-300 text-emerald-700 font-bold rounded-xl hover:bg-emerald-50 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Pilihan Variasi
                        </button>
                    </div>
                </div>
            </div>
            <!-- AKHIR KOMPONEN VARIASI -->

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Lengkap <span
                        class="text-rose-500">*</span></label>
                <textarea name="description" required rows="5"
                    placeholder="Jelaskan spesifikasi detail produkmu di sini..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition">{{ old('description') }}</textarea>
            </div>

            <div class="p-6 border-2 border-dashed border-slate-200 bg-slate-50 rounded-2xl">
                <label class="block text-sm font-bold text-slate-700 mb-2">Foto Produk Utama <span
                        class="text-rose-500">*</span></label>
                <input type="file" name="image" required accept="image/jpeg,image/png,image/jpg" class="w-full text-slate-500 file:cursor-pointer
                       file:mr-4 file:py-2.5 file:px-6
                       file:rounded-xl file:border-0
                       file:text-sm file:font-bold
                       file:bg-emerald-100 file:text-emerald-700
                       hover:file:bg-emerald-200 transition">
                <p class="text-[11px] font-semibold text-slate-500 mt-2">* Akan menjadi foto pertama (cover). Foto
                    variasi bersifat opsional.</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="/admin/products"
                    class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">Batal</a>
                <button type="submit"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md shadow-emerald-500/20 transition">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>

    <script>
        function variationSystem() {
            const serverVariants = @json(old('variants', []));
            return {
                hasVariation: serverVariants.length > 0 || {{ old('has_variation') ? 'true' : 'false' }},
                variants: serverVariants,

                init() {
                    // Jika toggle variasi dihidupkan, dan tabel masih kosong, otomatis tambah 1 baris kosong
                    this.$watch('hasVariation', value => {
                        if (value && this.variants.length === 0) {
                            this.addVariant();
                        }
                    });
                },

                addVariant() {
                    this.variants.push({
                        id: Date.now(), // ID unik agar AlpineJS tidak bingung saat merender file input
                        name: '',
                        price: '',
                        stock: ''
                    });
                },

                removeVariant(index) {
                    this.variants.splice(index, 1);
                }
            }
        }
    </script>
</x-admin-layout>