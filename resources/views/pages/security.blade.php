<x-layout>
    <x-slot:title>Keamanan Akun - Variety</x-slot:title>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showDeleteModal: false }">
        <div class="flex flex-col md:flex-row gap-8">
            <x-user-sidebar />

            <div class="flex-1">
                <div
                    class="bg-white rounded-2xl border border-rose-200 shadow-sm overflow-hidden p-6 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>

                    <div class="pb-4 border-b border-slate-100 mb-6">
                        <h2 class="text-xl font-extrabold text-slate-900">Keamanan Akun</h2>
                    </div>

                    @error('password')
                        <div class="mb-6 p-4 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl font-bold text-sm">
                            {{ $message }}
                        </div>
                    @enderror

                    <div>
                        <h3 class="font-bold text-rose-600 mb-2">Hapus Akun Permanen</h3>
                        <p class="text-sm text-slate-600 leading-relaxed mb-6 max-w-2xl">
                            Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.
                            Sebelum menghapus akun, harap simpan data atau informasi apa pun yang ingin Anda simpan.
                            Riwayat pesanan juga tidak dapat dipulihkan.
                        </p>

                        <button @click="showDeleteModal = true"
                            class="px-6 py-2.5 bg-rose-100 hover:bg-rose-600 text-rose-700 hover:text-white font-bold text-sm rounded-xl transition">
                            Hapus Akun Saya
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal" x-transition.opacity
                    class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm transition-opacity"
                    @click="showDeleteModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showDeleteModal" x-transition
                    class="relative z-50 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form action="{{ route('user.account.destroy') }}" method="POST" class="p-6">
                        @csrf
                        @method('DELETE')

                        <h3 class="text-lg font-extrabold text-slate-900 mb-2">Apakah Anda yakin?</h3>
                        <p class="text-sm text-slate-500 mb-6">Tindakan ini tidak bisa dibatalkan. Masukkan password
                            Anda untuk mengonfirmasi penghapusan akun.</p>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password
                                Saat Ini</label>
                            <input type="password" name="password" required placeholder="Masukkan password..."
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none">
                        </div>

                        <div class="flex flex-row-reverse gap-3">
                            <button type="submit"
                                class="px-6 py-2 bg-rose-600 text-white font-bold rounded-lg hover:bg-rose-700 transition">Hapus
                                Permanen</button>
                            <button type="button" @click="showDeleteModal = false"
                                class="px-6 py-2 border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-50 transition">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>