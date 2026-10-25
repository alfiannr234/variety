<x-admin-layout>
    <x-slot:title>Pesanan Masuk</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-slate-900">Pesanan Masuk</h1>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-extrabold">Invoice & Tanggal</th>
                            <th class="p-4 font-extrabold">Pelanggan</th>
                            <th class="p-4 font-extrabold">Total Bayar</th>
                            <th class="p-4 font-extrabold">Total Item</th>
                            <th class="p-4 font-extrabold text-right">Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/50 transition">
                                <!-- Invoice & Tanggal -->
                                <td class="p-4">
                                    <p class="font-bold text-emerald-700 text-sm">{{ $order->invoice_number }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}
                                    </p>
                                </td>

                                <!-- Nama Pelanggan -->
                                <td class="p-4">
                                    <p class="font-bold text-slate-900 text-sm">{{ $order->customer_name }}</p>
                                </td>

                                <!-- Total Bayar -->
                                <td class="p-4 text-sm font-bold text-slate-900">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>

                                <!-- Total Item -->
                                <td class="p-4 text-sm font-semibold text-slate-600">
                                    {{ $order->items->sum('quantity') }} Barang
                                </td>

                                <!-- Form Ubah Status -->
                                <td class="p-4 text-right">
                                    <form action="/admin/orders/{{ $order->id }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <!-- Atribut onchange="this.form.submit()" membuat form otomatis tersimpan saat kita memilih opsi -->
                                        <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 text-xs font-bold rounded-lg border outline-none cursor-pointer transition
                                                            {{ $order->status == 'Pending' ? 'bg-amber-50 text-amber-700 border-amber-200 focus:ring-amber-500' : '' }}
                                                            {{ $order->status == 'Diproses' ? 'bg-blue-50 text-blue-700 border-blue-200 focus:ring-blue-500' : '' }}
                                                            {{ $order->status == 'Dikirim' ? 'bg-purple-50 text-purple-700 border-purple-200 focus:ring-purple-500' : '' }}
                                                            {{ $order->status == 'Selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 focus:ring-emerald-500' : '' }}
                                                            {{ $order->status == 'Dibatalkan' ? 'bg-rose-50 text-rose-700 border-rose-200 focus:ring-rose-500' : '' }}
                                                            ">
                                            <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="Diproses" {{ $order->status == 'Diproses' ? 'selected' : '' }}>
                                                Diproses</option>
                                            <option value="Dikirim" {{ $order->status == 'Dikirim' ? 'selected' : '' }}>
                                                Dikirim</option>
                                            <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>
                                                Selesai</option>
                                            <option value="Dibatalkan" {{ $order->status == 'Dibatalkan' ? 'selected' : '' }}>
                                                Dibatalkan</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center">
                                    <p class="text-slate-500 font-semibold text-lg">Belum ada pesanan masuk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>