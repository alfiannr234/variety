<x-layout>
    <x-slot:title>Favorit Saya - Variety</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-extrabold text-slate-900 mb-6 border-b border-slate-200 pb-4">Favorit Saya</h1>

        @if($favorites->isEmpty())
            <div class="text-center py-20 bg-slate-50 rounded-2xl border border-slate-100">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                <h3 class="text-lg font-bold text-slate-700 mb-2">Belum ada produk favorit</h3>
            </div>
        @else
            <!-- Grid Katalog Produk Favorit -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($favorites as $item)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition relative group">
                        
                        <!-- Tombol Hapus dari Favorit (X) -->
                        <form action="{{ route('favorites.toggle', $item->id) }}" method="POST" class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition">
                            @csrf
                            <button type="submit" class="w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-rose-500 hover:bg-rose-500 hover:text-white shadow-sm transition" title="Hapus Favorit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>

                        <a href="/produk/{{ $item->slug }}">
                            <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">
                        </a>
                        <div class="p-4">
                            <a href="/produk/{{ $item->slug }}">
                                <h3 class="text-sm font-bold text-slate-800 line-clamp-2 hover:text-emerald-600 transition">{{ $item->name }}</h3>
                            </a>
                            <p class="text-emerald-600 font-extrabold mt-2">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>