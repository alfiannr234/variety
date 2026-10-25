<x-admin-layout>
    <x-slot:title>Pengaturan Toko</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-extrabold text-slate-900 mb-6 uppercase tracking-tight">Pengaturan Profil Toko</h1>

        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div
                class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl font-bold text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <!-- Form Update Profil -->
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data"
                class="p-6 sm:p-8">
                @csrf
                @method('PUT') <!-- Karena di web.php kita pakai rute PUT -->

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    <!-- Kolom Kiri: Foto Profil dengan Live Preview Alpine.js -->
                    <div class="md:col-span-1 flex flex-col items-center" x-data="imageViewer()">
                        <label
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 text-center w-full">Logo
                            / Avatar Toko</label>

                        <div class="flex flex-col items-center gap-4">
                            <!-- Lingkaran Preview Image -->
                            <div
                                class="w-40 h-40 rounded-full border-4 border-slate-100 overflow-hidden bg-slate-50 relative group shadow-inner">

                                <!-- Jika ada foto baru dipilih -->
                                <template x-if="imageUrl">
                                    <img :src="imageUrl" class="w-full h-full object-cover">
                                </template>

                                <!-- Foto existing / default UI Avatars -->
                                <template x-if="!imageUrl">
                                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=10B981&color=fff' }}"
                                        class="w-full h-full object-cover">
                                </template>

                                <!-- Overlay saat di-hover -->
                                <label
                                    class="absolute inset-0 bg-slate-900/50 flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 cursor-pointer transition">
                                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-xs font-bold">Ubah Foto</span>
                                    <input type="file" name="avatar" class="hidden" accept="image/*"
                                        @change="fileChosen">
                                </label>
                            </div>

                            <p class="text-[11px] text-slate-500 text-center px-4">Format: JPG, PNG, atau JPEG. Maksimal
                                2MB.</p>
                            @error('avatar')
                                <p class="text-xs text-rose-500 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan: Input Data Toko -->
                    <div class="md:col-span-2 space-y-6">

                        <!-- Input Nama Toko -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Nama Toko <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                            @error('name')
                                <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Input Deskripsi Toko -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Deskripsi Toko
                            </label>
                            <textarea name="store_description" rows="5"
                                placeholder="Ceritakan tentang tokomu, produk yang dijual, atau jam operasional..."
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">{{ old('store_description', $user->store_description) }}</textarea>
                            <p class="text-[11px] text-slate-500 mt-1">Deskripsi ini akan muncul di halaman publik
                                tokomu.</p>
                            @error('store_description')
                                <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="pt-4 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/25 transition">
                                Simpan Perubahan
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Alpine.js untuk fitur Live Preview -->
    <script>
        function imageViewer() {
            return {
                imageUrl: '',
                fileChosen(event) {
                    this.fileToDataUrl(event, src => this.imageUrl = src)
                },
                fileToDataUrl(event, callback) {
                    if (!event.target.files.length) return
                    let file = event.target.files[0],
                        reader = new FileReader()
                    reader.readAsDataURL(file)
                    reader.onload = e => callback(e.target.result)
                },
            }
        }
    </script>
</x-admin-layout>