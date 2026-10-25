<x-admin-layout>
    <x-slot:title>Manajemen Tab</x-slot:title>

    <div class="max-w-6xl mx-auto py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Manajemen Tab</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- FORM TAMBAH TAB BARU -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm sticky top-8">
                    <h3 class="font-bold text-slate-800 mb-4 pb-4 border-b border-slate-100">Tambah Tab Baru</h3>

                    <form action="{{ route('menu-tabs.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Tab
                                Menu</label>
                            <input type="text" name="name" required placeholder="Contoh: Kebutuhan Sehari-hari"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Urutan
                                Tampil</label>
                            <input type="number" name="order_position" required value="0" min="0"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                        </div>

                        <button type="submit"
                            class="w-full py-3 mt-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md shadow-emerald-500/20 transition">
                            Simpan Tab Baru
                        </button>
                    </form>
                </div>
            </div>
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600">
                                <tr>
                                    <th class="p-4 font-bold">Nama Tab Menu</th>
                                    <th class="p-4 font-bold text-center">Urutan</th>
                                    <th class="p-4 font-bold text-center">Status</th>
                                    <th class="p-4 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tabs as $tab)
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                        <td class="p-4">
                                            <p class="font-bold text-slate-800">{{ $tab->name }}</p>
                                        </td>
                                        <td class="p-4 text-center font-bold text-emerald-600 bg-slate-50/50">
                                            {{ $tab->order_position }}
                                        </td>
                                        <td class="p-4 text-center">
                                            @if($tab->is_active)
                                                <span
                                                    class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[11px] font-extrabold uppercase tracking-wider rounded-md">Aktif</span>
                                            @else
                                                <span
                                                    class="px-2.5 py-1 bg-slate-100 text-slate-500 text-[11px] font-extrabold uppercase tracking-wider rounded-md">Mati</span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-right flex items-center justify-end gap-2">
                                            <form action="{{ route('menu-tabs.destroy', $tab->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin menghapus Tab ini? Pastikan kategori di dalamnya sudah dipindah.')"
                                                    class="px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 font-bold rounded-lg transition text-xs">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-10 text-center text-slate-500">
                                            <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 6h16M4 12h16M4 18h7"></path>
                                            </svg>
                                            <p>Belum ada tab menu yang ditambahkan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>