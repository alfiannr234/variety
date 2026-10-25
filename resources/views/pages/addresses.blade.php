<x-layout>
    <x-slot:title>Alamat Saya - Variety</x-slot:title>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showAddModal: false }">

        <div class="flex flex-col md:flex-row gap-8">

            <x-user-sidebar />
            <div class="flex-1">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6">

                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 mb-6 gap-4">
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900">Alamat Saya</h2>
                            </p>
                        </div>
                        <button @click="showAddModal = true"
                            class="shrink-0 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-lg shadow-sm transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Alamat
                        </button>
                    </div>

                    @if(session('success'))
                        <div
                            class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl font-bold text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- List Alamat -->
                    @if($addresses->isEmpty())
                        <div class="text-center py-10 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                            <p class="text-slate-500 text-sm">Anda belum menambahkan alamat pengiriman.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($addresses as $address)
                                <div
                                    class="border border-slate-200 rounded-xl p-5 hover:border-emerald-500 transition relative {{ $address->is_primary ? 'bg-emerald-50/30' : '' }}">

                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-bold text-slate-800">{{ $address->recipient_name }}</h3>
                                            <span class="text-slate-300">|</span>
                                            <span class="text-sm text-slate-600">{{ $address->phone }}</span>
                                        </div>
                                        @if($address->is_primary)
                                            <span
                                                class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-extrabold uppercase tracking-wider rounded-md">Utama</span>
                                        @endif
                                    </div>

                                    @if($address->label)
                                        <span
                                            class="inline-block px-2 py-0.5 bg-slate-100 text-slate-600 text-xs rounded mb-2 font-medium">{{ $address->label }}</span>
                                    @endif

                                    <p class="text-sm text-slate-600 leading-relaxed mb-4">
                                        {{ $address->full_address }}
                                    </p>

                                    <div class="flex items-center gap-4 text-sm font-bold">

                                        <button class="text-emerald-600 hover:text-emerald-700">Ubah</button>

                                        @if(!$address->is_primary)
                                            <form action="{{ route('user.address.destroy', $address->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus alamat ini?')"
                                                    class="text-rose-500 hover:text-rose-600">Hapus</button>
                                            </form>

                                            <form action="{{ route('user.address.setPrimary', $address->id) }}" method="POST"
                                                class="ml-auto inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="px-3 py-1.5 border border-slate-300 rounded-lg text-slate-600 hover:border-emerald-500 hover:text-emerald-600 transition">
                                                    Atur sebagai Utama
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
        <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="showAddModal" x-transition.opacity
                    class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm transition-opacity"
                    @click="showAddModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showAddModal" x-transition
                    class="relative z-50 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                            <h3 class="text-lg leading-6 font-extrabold text-slate-900" id="modal-title">Tambah Alamat
                                Baru</h3>
                            <button type="button" @click="showAddModal = false"
                                class="text-slate-400 hover:text-slate-500 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form action="{{ route('user.address.store') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama
                                            Penerima</label>
                                        <input type="text" name="recipient_name" required
                                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor
                                            HP</label>
                                        <input type="text" name="phone" required
                                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Label
                                        Alamat (Opsional)</label>
                                    <input type="text" name="label" placeholder="Contoh: Rumah, Kantor, Kosan"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat
                                        Lengkap</label>
                                    <textarea name="full_address" rows="3" required
                                        placeholder="Nama Jalan, Gedung, No. Rumah, RT/RW, Kecamatan, Kota..."
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"></textarea>
                                </div>
                            </div>

                            <div class="mt-6 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-bold text-white hover:bg-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition">Simpan
                                    Alamat</button>
                                <button type="button" @click="showAddModal = false"
                                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layout>