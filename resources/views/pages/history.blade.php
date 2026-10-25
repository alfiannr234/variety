<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-2xl font-extrabold text-slate-900 mb-6">Riwayat Pembelian</h1>

        @if($orders->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Belum ada pesanan</h3>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <!-- Header Pesanan -->
                        <div
                            class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">No. Invoice</p>
                                <p class="text-sm font-bold text-emerald-700">{{ $order->invoice_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Tanggal</p>
                                <p class="text-sm font-bold text-slate-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Status</p>
                                <span
                                    class="px-3 py-1 rounded-full text-[11px] font-extrabold 
                                            {{ $order->status == 'Pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Daftar Barang -->
                        <div class="p-6 divide-y divide-slate-100">
                            @foreach($order->items as $item)
                                <div class="py-4 first:pt-0 last:pb-0 flex items-center gap-4">
                                    <img src="{{ $item->product->image ?? 'https://via.placeholder.com/150' }}"
                                        class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                                    <div class="flex-grow min-w-0">
                                        <h4 class="text-sm font-bold text-slate-900 truncate">
                                            {{ $item->product->name ?? 'Produk Dihapus' }}</h4>
                                        <p class="text-xs text-slate-500 mt-0.5">Varian: {{ $item->color ?? '-' }} | Size:
                                            {{ $item->size ?? '-' }}</p>
                                        <p class="text-xs font-semibold text-slate-700 mt-1">{{ $item->quantity }} x Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-extrabold text-slate-900">Rp
                                            {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total Pembayaran -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-700">Total Belanja</span>
                            <span class="text-lg font-black text-emerald-600">Rp
                                {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>