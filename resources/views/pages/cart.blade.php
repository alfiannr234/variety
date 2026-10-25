<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-extrabold text-slate-900 mb-6">Keranjang Belanja Anda</h1>

        <div class="grid lg:grid-cols-12 gap-8">
            <!-- Kiri: Daftar Barang -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <div
                        class="flex items-center justify-between pb-4 border-b border-slate-100 text-xs font-bold text-slate-600">
                        <span>DAFTAR PRODUK ({{ count($cartItems) }} ITEM)</span>
                    </div>

                    @if(empty($cartItems))
                        <div class="py-12 text-center text-slate-500">
                            Keranjang Anda masih kosong. Yuk belanja dulu!
                        </div>
                    @else
                        <form action="{{ route('cart.clear') }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" onclick="return confirm('Kosongkan semua isi keranjang?')"
                                class="text-xs text-rose-600 font-semibold hover:underline">
                                Kosongkan Keranjang
                            </button>
                        </form>
                        <div class="divide-y divide-slate-100">
                            @foreach($cartItems as $key => $item)
                                <div class="py-4 flex gap-4 items-center">
                                    <img src="{{ $item['image'] }}"
                                        class="w-20 h-20 rounded-xl object-cover border border-slate-200 flex-shrink-0"
                                        alt="{{ $item['name'] }}">
                                    <div class="flex-grow min-w-0">
                                        <h3 class="text-sm font-bold text-slate-800 truncate">{{ $item['name'] }}</h3>
                                        <p class="text-xs text-slate-600 mt-0.5">Varian: {{ $item['color'] ?? '-' }} / Size:
                                            {{ $item['size'] ?? '-' }}
                                        </p>
                                        <div class="text-sm font-extrabold text-emerald-600 mt-1">
                                            Rp {{ number_format($item['price'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <!-- Jumlah -->
                                    <div
                                        class="px-3 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-800">
                                        Qty: {{ $item['quantity'] }}
                                    </div>
                                    <form action="{{ route('cart.destroy', $key) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus produk ini dari keranjang?')"
                                            class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition"
                                            title="Hapus Produk">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kanan: Ringkasan Total -->
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-slate-900">Ringkasan Belanja</h2>

                    <div class="space-y-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
                        <div class="flex justify-between">
                            <span>Total Harga</span>
                            <span class="font-semibold text-slate-800">Rp
                                {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div
                            class="flex justify-between pt-3 border-t border-slate-100 text-base font-extrabold text-slate-900">
                            <span>Total Belanja</span>
                            <span class="text-emerald-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @if(!empty($cartItems))
                        <a href="/checkout"
                            class="w-full block text-center py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/25 transition">
                            Lanjut ke Pembayaran
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>