@props(['product'])

<div
    class="group bg-white rounded-2xl border border-slate-200/80 hover:border-emerald-500/40 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-300 overflow-hidden flex flex-col justify-between">
    <!-- Gambar & Badge -->
    <div class="relative aspect-square overflow-hidden bg-slate-100">
        <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">

        <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
            @if($product->discount_percentage)
                <span class="bg-rose-500 text-white font-extrabold text-[11px] px-2 py-0.5 rounded-md shadow-sm">
                    -{{ $product->discount_percentage }}%
                </span>
            @endif
            @if($product->is_best_seller)
                <span class="bg-amber-500 text-slate-900 font-bold text-[10px] px-2 py-0.5 rounded-md shadow-sm">
                    Terlaris
                </span>
            @endif
        </div>
    </div>

    <!-- Detail Produk -->
    <div class="p-4 flex flex-col flex-grow justify-between gap-3">
        <div>
            <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider">
                {{ $product->subCategory?->category?->name ?? 'Kategori Umum' }}
                @if($product->subCategory)
                    • {{ $product->subCategory->name }}
                @endif
            </span>
            <h3 class="mt-1 text-sm font-semibold text-slate-800 line-clamp-2 group-hover:text-emerald-700 transition">
                <a href="/produk/{{ $product->slug }}">
                    {{ $product->name }}
                </a>
            </h3>
        </div>

        <!-- Harga -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
            <div>
                <div class="text-base font-extrabold text-slate-900">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>
                @if($product->original_price)
                    <div class="text-xs text-slate-600 line-through">
                        Rp {{ number_format($product->original_price, 0, ',', '.') }}
                    </div>
                @endif
            </div>
            <button
                class="p-2.5 rounded-xl bg-slate-100 hover:bg-emerald-600 text-slate-700 hover:text-white transition group-hover:bg-emerald-600 group-hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </button>
        </div>
    </div>
</div>