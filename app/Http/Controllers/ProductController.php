<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\Chat;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. Eager load menggunakan relasi Level 3 yang baru
        $query = Product::with('subSubCategory.subCategory.category');

        $pageTitle = 'Katalog Produk';
        $activeCategory = null;

        // 2. Filter Kategori Utama (Level 1)
        if ($request->filled('kategori')) {
            $query->whereHas('subSubCategory.subCategory.category', function ($q) use ($request) {
                $q->where('slug', $request->kategori)->orWhere('name', $request->kategori);
            });
            $activeCategory = Category::where('slug', $request->kategori)->first();
            if ($activeCategory) {
                $pageTitle = 'Katalog ' . $activeCategory->name;
            }
        }

        // 3. Filter Sub-Kategori (Level 2)
        if ($request->filled('sub')) {
            $query->whereHas('subSubCategory.subCategory', function ($q) use ($request) {
                $q->where('slug', $request->sub)->orWhere('name', $request->sub);
            });
            $pageTitle = 'Katalog ' . ucwords(str_replace('-', ' ', $request->sub));
        }

        // 4. Pencarian Lanjutan (Berdasarkan nama produk, deskripsi, atau kategori)
        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $words = array_filter(explode(' ', $keyword));

            $query->where(function ($mainQuery) use ($words, $keyword) {
                // Pencarian kata kunci utuh
                $mainQuery->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    // Pencarian hingga ke level kategori
                    ->orWhereHas('subSubCategory.subCategory.category', function ($catQ) use ($keyword) {
                        $catQ->where('name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('subSubCategory.subCategory', function ($subQ) use ($keyword) {
                        $subQ->where('name', 'like', "%{$keyword}%");
                    });

                // Pencarian per kata (pecahan spasi)
                foreach ($words as $word) {
                    $mainQuery->orWhere('name', 'like', "%{$word}%")
                        ->orWhere('description', 'like', "%{$word}%");
                }
            });

            $pageTitle = 'Hasil Pencarian: "' . $keyword . '"';
        }

        // 5. Filter Harga
        if ($request->filled('min_price')) {
            $minPrice = preg_replace('/[^0-9]/', '', $request->min_price);
            $query->where('price', '>=', $minPrice);
        }

        if ($request->filled('max_price')) {
            $maxPrice = preg_replace('/[^0-9]/', '', $request->max_price);
            $query->where('price', '<=', $maxPrice);
        }

        // 6. Pengurutan (Sorting)
        $sort = $request->input('sort', 'terbaru');
        switch ($sort) {
            case 'termurah':
                $query->orderBy('price', 'asc');
                break;
            case 'termahal':
                $query->orderBy('price', 'desc');
                break;
            case 'populer':
                $query->orderBy('sold_count', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'terbaru':
            default:
                $query->latest();
                break;
        }

        // 7. Render Halaman dengan Pagination
        $products = $query->paginate(12)->withQueryString();

        return view('pages.catalog', compact('products', 'activeCategory', 'pageTitle'));
    }


    public function show($slug)
    {
        // PENTING: with() juga harus menggunakan relasi Level 3 yang baru
        $product = Product::with(['subSubCategory.subCategory.category', 'reviews.user', 'seller'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Mengambil produk terkait berdasarkan spesifikasi / sub-sub-kategori yang sama
        $relatedProducts = Product::where('sub_sub_category_id', $product->sub_sub_category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $seller = $product->seller;

        // 1. Penilaian rata-rata seller
        $sellerReviews = Review::whereHas('product', fn($q) => $q->where('user_id', $product->user_id));
        $sellerReviewCount = $sellerReviews->count();
        $sellerRating = $sellerReviewCount > 0 ? round($sellerReviews->avg('rating'), 1) : 5.0;

        // 2. Chat dibalas & waktu balas
        $totalSellerChats = Chat::where('seller_id', $product->user_id)->count();
        $repliedSellerChats = Chat::where('seller_id', $product->user_id)
            ->whereHas('messages', fn($q) => $q->where('sender_id', $product->user_id))
            ->count();
        $chatReplyRate = $totalSellerChats > 0 ? round(($repliedSellerChats / $totalSellerChats) * 100) : 100;

        $responseSpeed = 'hitungan menit';
        if ($repliedSellerChats > 0) {
            $recentChats = Chat::where('seller_id', $product->user_id)
                ->whereHas('messages', fn($q) => $q->where('sender_id', $product->user_id))
                ->with(['messages' => fn($q) => $q->oldest()])
                ->take(5)
                ->get();

            $diffs = [];
            foreach ($recentChats as $c) {
                $buyerMsg = $c->messages->firstWhere('sender_id', '!=', $product->user_id);
                $sellerMsg = $c->messages->firstWhere('sender_id', $product->user_id);
                if ($buyerMsg && $sellerMsg && $sellerMsg->created_at >= $buyerMsg->created_at) {
                    $diffs[] = $sellerMsg->created_at->diffInMinutes($buyerMsg->created_at);
                }
            }
            if (!empty($diffs)) {
                $avgMin = array_sum($diffs) / count($diffs);
                if ($avgMin > 1440) {
                    $responseSpeed = 'beberapa hari';
                } elseif ($avgMin > 60) {
                    $responseSpeed = 'hitungan jam';
                } else {
                    $responseSpeed = 'hitungan menit';
                }
            }
        }

        // 3. Jumlah produk seller
        $sellerProductCount = Product::where('user_id', $product->user_id)->count();

        // 4. Pengikut (jumlah user unik yang memfavoritkan produk milik seller ini)
        $sellerFollowersCount = DB::table('favorites')
            ->whereIn('product_id', function ($query) use ($product) {
                $query->select('id')->from('products')->where('user_id', $product->user_id);
            })
            ->distinct('user_id')
            ->count('user_id');

        $storeStats = [
            'rating' => number_format($sellerRating, 1),
            'review_count' => $sellerReviewCount,
            'reply_rate' => $chatReplyRate . '%',
            'joined' => $seller?->created_at ? $seller->created_at->locale('id')->diffForHumans(null, true) . ' lalu' : 'Baru bergabung',
            'product_count' => $sellerProductCount >= 1000 ? number_format($sellerProductCount / 1000, 1, ',', '.') . 'RB' : (string) $sellerProductCount,
            'response_speed' => $responseSpeed,
            'followers_count' => $sellerFollowersCount >= 1000 ? number_format($sellerFollowersCount / 1000, 1, ',', '.') . 'RB' : (string) $sellerFollowersCount,
            'active_status' => $seller?->updated_at ? 'Aktif ' . $seller->updated_at->locale('id')->diffForHumans() : 'Aktif baru saja',
        ];

        return view('pages.product-detail', compact('product', 'relatedProducts', 'storeStats'));
    }
}
