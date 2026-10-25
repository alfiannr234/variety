<x-layout>
    <x-slot:title>{{ $store->name }}</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Header Profil Toko -->
        <div
            class="bg-white border border-slate-200 rounded-2xl p-8 mb-10 flex flex-col sm:flex-row items-center gap-6 shadow-sm">
            <img src="{{ $store->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($store->name) . '&background=10B981&color=fff' }}"
                alt="Logo Toko" class="w-24 h-24 rounded-full border-4 border-emerald-50 object-cover">

            <div class="text-center sm:text-left">
                <h1 class="text-2xl font-extrabold text-slate-900">{{ $store->name }}</h1>
                <p class="text-slate-500 mt-2 max-w-2xl text-sm leading-relaxed">
                    {{ $store->store_description ?? 'Toko ini belum menambahkan deskripsi.' }}
                </p>
            </div>
        </div>

        <!-- Grid Produk Toko -->
        <h2 class="text-lg font-extrabold text-slate-900 mb-6 uppercase tracking-tight border-b border-slate-200 pb-2">
            Semua Produk</h2>

        @if($products->isEmpty())
            <div class="text-center py-20 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-slate-500">Toko ini belum memiliki produk.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($products as $item)
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden group">
                        <a href="/produk/{{ $item->slug }}">
                            <img src="{{ $item->image }}" class="w-full h-48 object-cover group-hover:scale-105 transition">
                        </a>
                        <div class="p-3">
                            <h3 class="text-sm font-bold text-slate-800 line-clamp-2">{{ $item->name }}</h3>
                            <p class="text-emerald-600 font-extrabold mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>