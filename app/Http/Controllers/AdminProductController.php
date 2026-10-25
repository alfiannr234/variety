<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index()
    {
        $query = Product::with(['subSubCategory.subCategory.category', 'seller'])->latest();

        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        $products = $query->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $subSubCategories = SubSubCategory::with('subCategory.category')->get();
        return view('admin.products.create', compact('subSubCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_sub_category_id' => 'required|exists:sub_sub_categories,id',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'has_variation' => 'nullable',
            'price' => 'nullable|integer|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($request->has_variation && !empty($request->variants)) {
            $variantData = $this->processVariants($request);
            $finalPrice = $variantData['price'];
            $finalStock = $variantData['stock'];
            $colors = $variantData['colors'];
            $sizes = $variantData['sizes'];
            $variantStocks = $variantData['variant_stocks'];
        } else {
            $finalPrice = (int) ($request->price ?? 0);
            $finalStock = (int) ($request->stock ?? 0);
            $colors = null;
            $sizes = null;
            $variantStocks = null;
        }

        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
            'sub_sub_category_id' => $request->sub_sub_category_id,
            'price' => $finalPrice,
            'stock' => $finalStock,
            'description' => $request->description,
            'image' => '/storage/' . $imagePath,
            'colors' => $colors,
            'sizes' => $sizes,
            'variant_stocks' => $variantStocks,
        ]);

        return redirect('/admin/products')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $subSubCategories = SubSubCategory::with('subCategory.category')->get();

        return view('admin.products.edit', compact('product', 'subSubCategories'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $product->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'sub_sub_category_id' => 'required|exists:sub_sub_categories,id',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'has_variation' => 'nullable',
            'price' => 'nullable|integer|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($request->has_variation && !empty($request->variants)) {
            $variantData = $this->processVariants($request, $product);
            $finalPrice = $variantData['price'];
            $finalStock = $variantData['stock'];
            $colors = $variantData['colors'];
            $sizes = $variantData['sizes'];
            $variantStocks = $variantData['variant_stocks'];
        } else {
            $finalPrice = (int) ($request->price ?? 0);
            $finalStock = (int) ($request->stock ?? 0);
            $colors = null;
            $sizes = null;
            $variantStocks = null;
        }

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'sub_sub_category_id' => $request->sub_sub_category_id,
            'price' => $finalPrice,
            'stock' => $finalStock,
            'description' => $request->description,
            'colors' => $colors,
            'sizes' => $sizes,
            'variant_stocks' => $variantStocks,
        ];

        if ($request->hasFile('image')) {
            if ($product->image && str_contains($product->image, '/storage/')) {
                $oldImagePath = str_replace('/storage/', '', $product->image);
                Storage::disk('public')->delete($oldImagePath);
            }
            $newImagePath = $request->file('image')->store('products', 'public');
            $data['image'] = '/storage/' . $newImagePath;
        }

        $product->update($data);
        return redirect('/admin/products')->with('success', 'Data produk berhasil diperbarui!');
    }

    protected function processVariants(Request $request, ?Product $existingProduct = null): array
    {
        $variantsInput = $request->variants ?? [];
        $totalStock = 0;
        $minPrice = null;

        $variantStocks = [];
        $uniqueColors = [];
        $uniqueSizes = [];
        $existingColorsMap = [];

        if ($existingProduct && !empty($existingProduct->colors) && is_array($existingProduct->colors)) {
            foreach ($existingProduct->colors as $col) {
                if (is_array($col) && !empty($col['name'])) {
                    $existingColorsMap[$col['name']] = $col;
                }
            }
        }

        $colorHexMap = [
            'hitam' => '#000000', 'black' => '#000000',
            'putih' => '#FFFFFF', 'white' => '#FFFFFF',
            'merah' => '#EF4444', 'red' => '#EF4444',
            'biru' => '#3B82F6', 'blue' => '#3B82F6',
            'hijau' => '#10B981', 'green' => '#10B981',
            'kuning' => '#FBBF24', 'yellow' => '#FBBF24',
            'navy' => '#1B2A4A', 'navy blue' => '#1B2A4A',
            'olive' => '#556B2F', 'olive green' => '#556B2F',
            'terracotta' => '#D97706', 'terakota' => '#D97706',
            'abu' => '#94A3B8', 'abu-abu' => '#94A3B8', 'grey' => '#94A3B8', 'gray' => '#94A3B8',
            'coklat' => '#78350F', 'brown' => '#78350F',
            'beige' => '#D4B996',
            'sage' => '#9CAF88', 'sage green' => '#9CAF88',
            'maroon' => '#800000',
            'mustard' => '#D97706',
            'pink' => '#EC4899',
            'ungu' => '#8B5CF6', 'purple' => '#8B5CF6',
        ];

        $colorPalette = ['#10B981', '#3B82F6', '#6366F1', '#EC4899', '#F59E0B', '#8B5CF6', '#14B8A6', '#F97316', '#64748B'];
        $colorIndex = 0;

        foreach ($variantsInput as $key => $var) {
            $rawName = trim($var['name'] ?? '');
            if ($rawName === '') {
                continue;
            }

            $vStock = max(0, (int) ($var['stock'] ?? 0));
            $vPrice = max(0, (int) ($var['price'] ?? 0));

            $totalStock += $vStock;
            if (is_null($minPrice) || ($vPrice > 0 && $vPrice < $minPrice)) {
                $minPrice = $vPrice;
            }

            // Foto variasi: upload baru atau gunakan existing
            $vImage = null;
            if ($request->hasFile("variants.{$key}.image")) {
                $vImgPath = $request->file("variants.{$key}.image")->store('products/variants', 'public');
                $vImage = '/storage/' . $vImgPath;
            } elseif (!empty($var['existing_image'])) {
                $vImage = $var['existing_image'];
            }

            // Parsing nama variasi (Pemisah " - " atau "-")
            $colorName = $rawName;
            $sizeName = null;

            if (str_contains($rawName, ' - ')) {
                $parts = explode(' - ', $rawName, 2);
                $colorName = trim($parts[0]);
                $sizeName = trim($parts[1]);
            } elseif (str_contains($rawName, '-')) {
                $lastDashPos = strrpos($rawName, '-');
                $possibleColor = trim(substr($rawName, 0, $lastDashPos));
                $possibleSize = trim(substr($rawName, $lastDashPos + 1));
                if (in_array(strtoupper($possibleSize), ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL']) || is_numeric($possibleSize)) {
                    $colorName = $possibleColor;
                    $sizeName = $possibleSize;
                } else {
                    $colorName = $rawName;
                }
            }

            if ($sizeName !== null && $sizeName !== '') {
                if (!in_array($sizeName, $uniqueSizes)) {
                    $uniqueSizes[] = $sizeName;
                }
            }

            if (!isset($uniqueColors[$colorName])) {
                $existing = $existingColorsMap[$colorName] ?? null;
                $hex = $existing['hex'] ?? ($colorHexMap[strtolower($colorName)] ?? $colorPalette[$colorIndex % count($colorPalette)]);
                $colorIndex++;
                $uniqueColors[$colorName] = [
                    'name' => $colorName,
                    'hex' => $hex,
                    'image' => $vImage ?? ($existing['image'] ?? null),
                ];
            } else {
                if ($vImage && empty($uniqueColors[$colorName]['image'])) {
                    $uniqueColors[$colorName]['image'] = $vImage;
                }
            }

            $variantKey = ($sizeName !== null && $sizeName !== '') ? "{$colorName}-{$sizeName}" : $colorName;

            $variantStocks[$variantKey] = [
                'name' => $rawName,
                'price' => $vPrice,
                'stock' => $vStock,
                'image' => $vImage,
            ];
        }

        return [
            'price' => $minPrice ?? (int) ($request->price ?? 0),
            'stock' => $totalStock,
            'colors' => !empty($uniqueColors) ? array_values($uniqueColors) : null,
            'sizes' => !empty($uniqueSizes) ? $uniqueSizes : null,
            'variant_stocks' => !empty($variantStocks) ? $variantStocks : null,
        ];
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $product->user_id !== Auth::id()) {
            abort(403);
        }

        if ($product->image && str_contains($product->image, '/storage/')) {
            $imagePath = str_replace('/storage/', '', $product->image);
            Storage::disk('public')->delete($imagePath);
        }
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus permanen!');
    }
}
