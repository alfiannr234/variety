<x-admin-layout>
    <x-slot:title>Edit Produk - Admin</x-slot:title>

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
                <h1 class="text-2xl font-extrabold text-slate-900">Edit Data Produk</h1>
                <p class="text-sm text-slate-500 mt-1">Ubah informasi barang: <span
                        class="font-bold text-emerald-600">{{ $product->name }}</span></p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
                <ul class="list-disc list-inside text-sm text-rose-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/admin/products/{{ $product->id }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            @csrf
            @method('PUT')

            <!-- Info Dasar -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Produk <span
                            class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Spesifik (Sub-Kategori) <span
                            class="text-rose-500">*</span></label>
                    <select name="sub_sub_category_id" required class="...">
                        <option value="" disabled>-- Pilih Spesifikasi Kategori --</option>
                        @foreach($subSubCategories as $subSub)
                            <option value="{{ $subSub->id }}" {{ (old('sub_sub_category_id', $product->sub_sub_category_id) == $subSub->id) ? 'selected' : '' }}>
                                {{ $subSub->subCategory->category->name }} ➔ {{ $subSub->subCategory->name }} ➔
                                {{ $subSub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- AREA VARIASI (Menggunakan Alpine.js yang sudah terinjeksi data server) -->
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
                        <label class="block text-sm font-bold text-slate-700 mb-2">Harga Utama (Rp) <span
                                class="text-rose-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}"
                            :required="!hasVariation" min="0"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Total Stok Utama <span
                                class="text-rose-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                            :required="!hasVariation" min="0"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                    </div>
                </div>

                <!-- DAFTAR VARIASI (Muncul jika ADA variasi) -->
                <div x-show="hasVariation" style="display: none;"
                    class="p-5 border border-slate-200 bg-white rounded-2xl shadow-sm" x-transition>
                    <h4 class="font-bold text-slate-800 mb-4 pb-4 border-b border-slate-100">Daftar Variasi Produk</h4>

                    <div class="space-y-4">
                        <template x-for="(variant, index) in variants" :key="variant.id">
                            <div
                                class="flex flex-col md:flex-row gap-4 p-5 border border-slate-200 bg-slate-50 rounded-xl relative group">

                                <!-- Simpan ID Variasi untuk proses Update di Backend -->
                                <input type="hidden" :name="'variants[' + index + '][id]'" :value="variant.id"
                                    x-show="!String(variant.id).startsWith('new_')">

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
                                        :required="hasVariation" placeholder="Misal: Merah XL"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:border-emerald-500 outline-none">
                                </div>
                                <div class="w-full md:w-1/4">
                                    <label
                                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Harga
                                        (Rp) <span class="text-rose-500">*</span></label>
                                    <input type="number" :name="'variants[' + index + '][price]'"
                                        x-model="variant.price" :required="hasVariation" min="0"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:border-emerald-500 outline-none">
                                </div>
                                <div class="w-full md:w-1/6">
                                    <label
                                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Stok
                                        <span class="text-rose-500">*</span></label>
                                    <input type="number" :name="'variants[' + index + '][stock]'"
                                        x-model="variant.stock" :required="hasVariation" min="0"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:border-emerald-500 outline-none">
                                </div>
                                <div class="w-full md:w-1/4">
                                    <label
                                        class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Foto
                                        <span class="font-normal text-slate-400">(Opsional)</span></label>
                                    <div class="flex items-center gap-2">
                                        <!-- Tampilkan thumbnail jika variasi sudah punya foto -->
                                        <template x-if="variant.image && typeof variant.image === 'string'">
                                            <img :src="variant.image"
                                                class="w-8 h-8 rounded border border-slate-200 object-cover shrink-0">
                                            <input type="hidden" :name="'variants[' + index + '][existing_image]'" :value="variant.image">
                                        </template>
                                        <input type="file" :name="'variants[' + index + '][image]'"
                                            accept="image/jpeg,image/png,image/jpg"
                                            class="w-full text-[10px] text-slate-500 file:cursor-pointer file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:font-bold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 transition bg-white border border-slate-200 rounded-lg">
                                    </div>
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

            <!-- Deskripsi & Foto Utama -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Lengkap <span
                        class="text-rose-500">*</span></label>
                <textarea name="description" required rows="5"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="p-6 border-2 border-dashed border-slate-200 bg-slate-50 rounded-2xl">
                <label class="block text-sm font-bold text-slate-700 mb-4">Foto Produk Utama</label>
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="flex-shrink-0">
                        <p class="text-xs text-slate-500 mb-2 text-center">Gambar Saat Ini</p>
                        <img src="{{ $product->image }}" alt="Current Image"
                            class="w-24 h-24 rounded-xl object-cover border border-slate-300 shadow-sm">
                    </div>
                    <div class="flex-grow w-full">
                        <p class="text-xs text-slate-500 mb-2">Pilih gambar baru (Abaikan jika tidak ingin mengubah
                            foto)</p>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/jpg" class="w-full text-slate-500 file:cursor-pointer
                               file:mr-4 file:py-2.5 file:px-4
                               file:rounded-xl file:border-0
                               file:text-sm file:font-bold
                               file:bg-slate-200 file:text-slate-700
                               hover:file:bg-slate-300 transition">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="/admin/products"
                    class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">Batal</a>
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md shadow-blue-500/20 transition">
                    Update Produk
                </button>
            </div>
        </form>
    </div>

    <script>
        function variationSystem() {
            // Mengambil data variasi dari server (database)
            const serverVariants = @json(old('variants', $product->formatted_variants ?? []));

            return {
                hasVariation: serverVariants.length > 0 || {{ old('has_variation') ? 'true' : 'false' }},
                variants: serverVariants,

                init() {
                    this.$watch('hasVariation', value => {
                        if (value && this.variants.length === 0) {
                            this.addVariant();
                        }
                    });
                },

                addVariant() {
                    this.variants.push({
                        id: 'new_' + Date.now(),
                        name: '',
                        price: '',
                        stock: '',
                        image: null
                    });
                },

                removeVariant(index) {
                    this.variants.splice(index, 1);
                }
            }
        }
    </script>
</x-admin-layout>