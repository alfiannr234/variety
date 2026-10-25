<x-layout>
    <x-slot:title>Checkout Pembayaran - Variety</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Judul Halaman -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900">Checkout Pesanan</h1>
            <p class="text-sm text-slate-500 mt-1">Lengkapi informasi pengiriman dan tentukan metode pembayaran.</p>
        </div>

        <form action="/order/create" method="POST" class="grid lg:grid-cols-12 gap-8">
            @csrf

            <!-- Kolom Kiri: Form Alamat & Pilihan Pembayaran -->
            <div class="lg:col-span-7 xl:col-span-8 space-y-6">

                <!-- 1. Form Data Pengiriman -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
                    <h2 class="text-lg font-extrabold text-slate-900 mb-4 flex items-center gap-2">
                        <span
                            class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs flex items-center justify-center font-black">1</span>
                        Alamat & Tujuan Pengiriman
                    </h2>

                    <div class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama
                                    Penerima <span class="text-rose-500">*</span></label>
                                <input type="text" name="recipient_name"
                                    value="{{ old('recipient_name', auth()->user()->name) }}" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor
                                    Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required
                                    placeholder="Contoh: 08123456789"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat
                                Lengkap <span class="text-rose-500">*</span></label>
                            <textarea name="address" rows="3" required
                                placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, kode pos..."
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Pilihan Metode Pembayaran (Interactive Tab) -->
                <div x-data="{ selectedMethod: 'qris', activeTab: 'qris' }"
                    class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
                    <h2 class="text-lg font-extrabold text-slate-900 mb-1 flex items-center gap-2">
                        <span
                            class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs flex items-center justify-center font-black">2</span>
                        Pilih Metode Pembayaran
                    </h2>
                    <p class="text-xs text-slate-500 mb-6 pl-8">Tentukan channel transaksi yang paling nyaman untuk
                        Anda.</p>

                    <!-- Input Hidden untuk dikirim ke Backend -->
                    <input type="hidden" name="payment_method" :value="selectedMethod">

                    <!-- Tab Kategori Pembayaran -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-6">
                        <button type="button" @click="activeTab = 'qris'; selectedMethod = 'qris'"
                            :class="activeTab === 'qris' ? 'border-emerald-600 bg-emerald-50 text-emerald-700 font-bold' : 'border-slate-200 hover:bg-slate-50 text-slate-600 font-medium'"
                            class="py-3 px-2 border rounded-xl text-xs sm:text-sm text-center transition">
                            QRIS & E-Wallet
                        </button>
                        <button type="button" @click="activeTab = 'va'; selectedMethod = 'bca_va'"
                            :class="activeTab === 'va' ? 'border-emerald-600 bg-emerald-50 text-emerald-700 font-bold' : 'border-slate-200 hover:bg-slate-50 text-slate-600 font-medium'"
                            class="py-3 px-2 border rounded-xl text-xs sm:text-sm text-center transition">
                            Virtual Account
                        </button>
                        <button type="button" @click="activeTab = 'outlet'; selectedMethod = 'indomaret'"
                            :class="activeTab === 'outlet' ? 'border-emerald-600 bg-emerald-50 text-emerald-700 font-bold' : 'border-slate-200 hover:bg-slate-50 text-slate-600 font-medium'"
                            class="py-3 px-2 border rounded-xl text-xs sm:text-sm text-center transition">
                            Minimarket
                        </button>
                        <button type="button" @click="activeTab = 'cod'; selectedMethod = 'cod'"
                            :class="activeTab === 'cod' ? 'border-emerald-600 bg-emerald-50 text-emerald-700 font-bold' : 'border-slate-200 hover:bg-slate-50 text-slate-600 font-medium'"
                            class="py-3 px-2 border rounded-xl text-xs sm:text-sm text-center transition">
                            COD
                        </button>
                    </div>

                    <!-- Panel 1: QRIS & E-Wallet -->
                    <div x-show="activeTab === 'qris'" class="space-y-3">
                        <label @click="selectedMethod = 'qris'"
                            :class="selectedMethod === 'qris' ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300'"
                            class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                    :class="selectedMethod === 'qris' ? 'border-emerald-600 bg-emerald-600' : 'border-slate-300'">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"
                                        x-show="selectedMethod === 'qris'"></span>
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">QRIS (Semua Pembayaran QR)</p>
                                    <p class="text-xs text-slate-500">Scan via BCA, Mandiri, GoPay, OVO, Dana, LinkAja
                                    </p>
                                </div>
                            </div>
                            <span
                                class="px-2 py-1 bg-emerald-100 text-emerald-700 font-bold rounded text-[10px]">Instan</span>
                        </label>

                        <label @click="selectedMethod = 'gopay'"
                            :class="selectedMethod === 'gopay' ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300'"
                            class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                    :class="selectedMethod === 'gopay' ? 'border-emerald-600 bg-emerald-600' : 'border-slate-300'">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"
                                        x-show="selectedMethod === 'gopay'"></span>
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">GoPay</p>
                                    <p class="text-xs text-slate-500">Bayar langsung dengan saldo GoPay</p>
                                </div>
                            </div>
                        </label>

                        <label @click="selectedMethod = 'dana'"
                            :class="selectedMethod === 'dana' ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300'"
                            class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                    :class="selectedMethod === 'dana' ? 'border-emerald-600 bg-emerald-600' : 'border-slate-300'">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"
                                        x-show="selectedMethod === 'dana'"></span>
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">DANA</p>
                                    <p class="text-xs text-slate-500">Dompet digital DANA</p>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Panel 2: Virtual Account -->
                    <div x-show="activeTab === 'va'" style="display: none;" class="space-y-3">
                        <label @click="selectedMethod = 'bca_va'"
                            :class="selectedMethod === 'bca_va' ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300'"
                            class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                    :class="selectedMethod === 'bca_va' ? 'border-emerald-600 bg-emerald-600' : 'border-slate-300'">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"
                                        x-show="selectedMethod === 'bca_va'"></span>
                                </span>
                                <p class="text-sm font-bold text-slate-900">BCA Virtual Account</p>
                            </div>
                            <span class="text-xs font-black text-slate-400">BCA</span>
                        </label>

                        <label @click="selectedMethod = 'mandiri_va'"
                            :class="selectedMethod === 'mandiri_va' ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300'"
                            class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                    :class="selectedMethod === 'mandiri_va' ? 'border-emerald-600 bg-emerald-600' : 'border-slate-300'">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"
                                        x-show="selectedMethod === 'mandiri_va'"></span>
                                </span>
                                <p class="text-sm font-bold text-slate-900">Mandiri Bill Payment</p>
                            </div>
                            <span class="text-xs font-black text-slate-400">MANDIRI</span>
                        </label>

                        <label @click="selectedMethod = 'bri_va'"
                            :class="selectedMethod === 'bri_va' ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300'"
                            class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                    :class="selectedMethod === 'bri_va' ? 'border-emerald-600 bg-emerald-600' : 'border-slate-300'">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"
                                        x-show="selectedMethod === 'bri_va'"></span>
                                </span>
                                <p class="text-sm font-bold text-slate-900">BRIVA (BRI Virtual Account)</p>
                            </div>
                            <span class="text-xs font-black text-slate-400">BRI</span>
                        </label>
                    </div>

                    <!-- Panel 3: Minimarket -->
                    <div x-show="activeTab === 'outlet'" style="display: none;" class="space-y-3">
                        <label @click="selectedMethod = 'indomaret'"
                            :class="selectedMethod === 'indomaret' ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300'"
                            class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                    :class="selectedMethod === 'indomaret' ? 'border-emerald-600 bg-emerald-600' : 'border-slate-300'">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"
                                        x-show="selectedMethod === 'indomaret'"></span>
                                </span>
                                <p class="text-sm font-bold text-slate-900">Indomaret / Ceriamart</p>
                            </div>
                        </label>

                        <label @click="selectedMethod = 'alfamart'"
                            :class="selectedMethod === 'alfamart' ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300'"
                            class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                    :class="selectedMethod === 'alfamart' ? 'border-emerald-600 bg-emerald-600' : 'border-slate-300'">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"
                                        x-show="selectedMethod === 'alfamart'"></span>
                                </span>
                                <p class="text-sm font-bold text-slate-900">Alfamart / Alfamidi</p>
                            </div>
                        </label>
                    </div>

                    <!-- Panel 4: COD -->
                    <div x-show="activeTab === 'cod'" style="display: none;" class="space-y-3">
                        <label @click="selectedMethod = 'cod'"
                            class="flex items-start gap-3 p-4 border border-emerald-600 bg-emerald-50/40 rounded-xl cursor-pointer ring-2 ring-emerald-500/20">
                            <span
                                class="w-4 h-4 mt-0.5 rounded-full border-2 border-emerald-600 bg-emerald-600 flex items-center justify-center flex-shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-slate-900">Cash on Delivery (COD)</p>
                                <p class="text-xs text-slate-500 mt-1">Bayar tunai secara langsung ke kurir saat paket
                                    tiba di alamat Anda.</p>
                            </div>
                        </label>
                    </div>

                </div>

            </div>

            <!-- Kolom Kanan: Ringkasan Belanja & Tombol Submit -->
            <div class="lg:col-span-5 xl:col-span-4">
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm sticky top-24">
                    <h2 class="text-lg font-extrabold text-slate-900 mb-6">Ringkasan Pesanan</h2>

                    <!-- Daftar Barang Singkat -->
                    <div class="space-y-3 mb-6 max-h-56 overflow-y-auto pr-1">
                        @foreach($checkoutItems as $item)
                            <div class="flex items-center gap-3">
                                <img src="{{ $item['product']->image }}" alt="{{ $item['product']->name }}"
                                    class="w-12 h-12 rounded-lg object-cover border border-slate-100 flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ $item['product']->name }}</p>
                                    @if(!empty($item['color']) || !empty($item['size']))
                                        <p class="text-[11px] text-emerald-600 font-semibold">
                                            Varian: {{ $item['color'] ?? '' }}{{ !empty($item['color']) && !empty($item['size']) ? ' - ' : '' }}{{ $item['size'] ?? '' }}
                                        </p>
                                    @endif
                                    <p class="text-[11px] text-slate-500">{{ $item['quantity'] }} x Rp
                                        {{ number_format($item['product']->price, 0, ',', '.') }}</p>
                                </div>
                                <p class="text-xs font-bold text-slate-900">Rp
                                    {{ number_format($item['item_total'], 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-slate-100 pt-4 space-y-2 mb-6 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal Produk</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Biaya Pengiriman</span>
                            <span>Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                        </div>
                        <div
                            class="flex justify-between text-base font-extrabold text-slate-900 pt-3 border-t border-slate-100">
                            <span>Total Tagihan</span>
                            <span class="text-emerald-600">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Tombol Final Submit Form -->
                    <button type="submit"
                        class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md shadow-emerald-600/20 transition">
                        Bayar Sekarang
                    </button>

                    <p class="text-[11px] text-center text-slate-400 mt-4">
                        Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan transaksi.
                    </p>
                </div>
            </div>

        </form>
    </div>
</x-layout>