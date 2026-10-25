<x-layout>
    <x-slot:title>Riwayat Pembelian - Variety</x-slot:title>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col md:flex-row gap-8">

            <x-user-sidebar />

            <div class="flex-1">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6">

                    <div class="pb-4 border-b border-slate-100 mb-6">
                        <h1 class="text-2xl font-extrabold text-slate-900">Riwayat Pembelian</h1>
                    </div>

                    @php
                        $methodNames = [
                            'qris' => 'QRIS',
                            'gopay' => 'GoPay',
                            'dana' => 'DANA',
                            'bca_va' => 'BCA Virtual Account',
                            'mandiri_va' => 'Mandiri Bill Payment',
                            'bri_va' => 'BRI Virtual Account',
                            'indomaret' => 'Indomaret',
                            'alfamart' => 'Alfamart',
                            'cod' => 'Cash on Delivery (COD)',
                        ];
                    @endphp

                    @if($orders->isEmpty())
                        <div class="bg-slate-50 p-12 rounded-2xl border border-dashed border-slate-300 text-center">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <p class="text-slate-500 text-sm">Belum ada transaksi pembelian.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div
                                    class="border border-slate-200 rounded-xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-emerald-500 transition">

                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span
                                                class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded">{{ $order->invoice_number ?? '#ORD-' . $order->id }}</span>
                                            <span class="text-xs text-slate-400">•
                                                {{ $order->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        <p class="text-sm font-bold text-slate-800">
                                            Total Tagihan: <span class="text-emerald-600">Rp
                                                {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        </p>
                                        <p class="text-xs text-slate-500 mt-1">
                                            Metode: <span
                                                class="font-semibold text-slate-700">{{ $methodNames[$order->payment_method] ?? strtoupper($order->payment_method) }}</span>
                                        </p>
                                    </div>

                                        @if($order->items && $order->items->isNotEmpty())
                                            <div class="mt-3 pt-3 border-t border-slate-100 space-y-1.5">
                                                @foreach($order->items as $orderItem)
                                                    <div class="flex items-center gap-2 text-xs text-slate-700">
                                                        <span class="font-bold text-slate-900">{{ $orderItem->product?->name ?? 'Produk' }}</span>
                                                        <span class="text-slate-400">x{{ $orderItem->quantity }}</span>
                                                        @if($orderItem->color || $orderItem->size)
                                                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                                                                {{ $orderItem->color ?? '' }}{{ $orderItem->color && $orderItem->size ? ' - ' : '' }}{{ $orderItem->size ?? '' }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-row md:flex-col lg:flex-row items-center gap-3">
                                        <span
                                            class="px-3 py-1.5 rounded-lg text-[11px] font-extrabold uppercase tracking-wider
                                                                    @if(strtolower($order->status) == 'pending') bg-amber-50 text-amber-700 border border-amber-200
                                                                    @elseif(in_array(strtolower($order->status), ['success', 'paid', 'completed', 'selesai'])) bg-emerald-50 text-emerald-700 border border-emerald-200
                                                                    @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                            {{ $order->status }}
                                        </span>

                                        @if($order->status === 'completed' || $order->status === 'selesai')
                                            <!-- Tombol & Modal Ulasan -->
                                            <div x-data="{ openReview: false, rating: 5 }" class="shrink-0">
                                                <button @click="openReview = true"
                                                    class="px-4 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 shadow-sm transition">
                                                    Beri Ulasan
                                                </button>

                                                <div x-show="openReview" style="display: none;"
                                                    class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
                                                    role="dialog" aria-modal="true">
                                                    <div
                                                        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                                        <div x-show="openReview" x-transition.opacity
                                                            class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm transition-opacity"
                                                            @click="openReview = false"></div>

                                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                            aria-hidden="true">&#8203;</span>
                                                        <div x-show="openReview" x-transition
                                                            class="relative z-50 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">

                                                                <div
                                                                    class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                                                                    <h3 class="text-lg font-extrabold text-slate-900">Tulis Ulasan
                                                                        Produk</h3>
                                                                    <button type="button" @click="openReview = false"
                                                                        class="text-slate-400 hover:text-slate-600 transition">
                                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                        </svg>
                                                                    </button>
                                                                </div>

                                                                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                                    <input type="hidden" name="product_id"
                                                                        value="{{ $order->items->first()?->product_id ?? ($order->product_id ?? 1) }}">
                                                                    <input type="hidden" name="color" value="{{ $order->items->first()?->color ?? '' }}">
                                                                    <input type="hidden" name="size" value="{{ $order->items->first()?->size ?? '' }}">

                                                                    <div class="mb-5">
                                                                        <label
                                                                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih
                                                                            Rating Bintang</label>
                                                                        <div
                                                                            class="flex gap-2 text-3xl justify-center py-2 bg-slate-50 rounded-xl border border-slate-100">
                                                                            <template x-for="star in 5">
                                                                                <button type="button" @click="rating = star"
                                                                                    :class="star <= rating ? 'text-amber-400' : 'text-slate-200'"
                                                                                    class="focus:outline-none transition hover:scale-125">
                                                                                    ★
                                                                                </button>
                                                                            </template>
                                                                        </div>
                                                                        <input type="hidden" name="rating" :value="rating">
                                                                    </div>

                                                                    <div class="mb-5">
                                                                        <label
                                                                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Unggah Foto Produk (Opsional)</label>
                                                                        <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                                                                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition cursor-pointer">
                                                                        <p class="text-[11px] text-slate-400 mt-1">Maksimal 5MB per file (JPG, PNG, WEBP)</p>
                                                                    </div>

                                                                    <div class="mb-6">
                                                                        <label
                                                                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Komentar
                                                                            & Pengalaman</label>
                                                                        <textarea name="comment" rows="4" required
                                                                            placeholder="Bagaimana kualitas produk ini? Apakah sesuai ekspektasi Anda?"
                                                                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition"></textarea>
                                                                    </div>

                                                                    <button type="submit"
                                                                        class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-sm transition">
                                                                        Kirim Ulasan Sekarang
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-layout>