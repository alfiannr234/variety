<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::create([
            'name' => 'Variety Admin',
            'email' => 'admin@variety.com',
            'password' => Hash::make('password123'),
        ]);
        $catPakaian = Category::create([
            'name' => 'Pakaian Pria',
            'slug' => 'pakaian-pria'
        ]);

        $subAtasan = SubCategory::create([
            'category_id' => $catPakaian->id,
            'name' => 'Atasan',
            'slug' => 'atasan-pria',
        ]);

        $subSubKemeja = SubSubCategory::create([
            'sub_category_id' => $subAtasan->id,
            'name' => 'Kemeja',
            'slug' => 'kemeja-pria',
        ]);

        // 2. Buat Kategori Elektronik (Level 1, 2, 3)
        $catElektronik = Category::create([
            'name' => 'Elektronik & Gadget',
            'slug' => 'elektronik-gadget'
        ]);

        $subAudio = SubCategory::create([
            'category_id' => $catElektronik->id,
            'name' => 'Audio',
            'slug' => 'audio-elektronik',
        ]);

        $subSubHeadphone = SubSubCategory::create([
            'sub_category_id' => $subAudio->id,
            'name' => 'Headphone',
            'slug' => 'headphone-bluetooth',
        ]);

        // 3. Buat Produk 1 (Kemeja) -> Relasi ke $seller dan $subSubKemeja
        $kemejaColors = [
            [
                'name' => 'Olive Green',
                'hex' => '#556B2F',
                'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=700&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Warm Beige',
                'hex' => '#D2B48C',
                'image' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=700&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Navy Blue',
                'hex' => '#1B263B',
                'image' => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=700&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Terracotta',
                'hex' => '#E07A5F',
                'image' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=700&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Charcoal Black',
                'hex' => '#2B2D42',
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=700&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Broken White',
                'hex' => '#F4F1DE',
                'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=700&auto=format&fit=crop&q=80',
            ],
        ];

        $kemejaSizes = ['S', 'M', 'L', 'XL', 'XXL'];

        $variantStocks = [];
        $totalStock = 0;
        foreach ($kemejaColors as $col) {
            foreach ($kemejaSizes as $sz) {
                $stockForVariant = match ($sz) {
                    'S' => 12,
                    'M' => 20,
                    'L' => 18,
                    'XL' => 10,
                    'XXL' => 6,
                };
                $variantStocks["{$col['name']}-{$sz}"] = $stockForVariant;
                $totalStock += $stockForVariant;
            }
        }

        Product::create([
            'user_id' => $seller->id,
            'sub_sub_category_id' => $subSubKemeja->id,
            'name' => 'Kemeja Katun Linen Casual Pria - Earth Series',
            'slug' => Str::slug('Kemeja Katun Linen Casual Pria - Earth Series'),
            'description' => 'Kemeja berbahan premium 100% Linen Katun dengan tekstur sejuk, breathable, dan cocok untuk iklim tropis Indonesia.',
            'price' => 189000,
            'original_price' => 289000,
            'discount_percentage' => 35,
            'stock' => $totalStock,
            'variant_stocks' => $variantStocks,
            'rating' => 4.8,
            'review_count' => 342,
            'sold_count' => 1200,
            'is_flash_sale' => true,
            'is_best_seller' => true,
            'image' => $kemejaColors[0]['image'],
            'images' => array_column($kemejaColors, 'image'),
            'specifications' => ['Bahan' => 'Katun Linen 100%', 'Fitting' => 'Regular Modern Fit'],
            'colors' => $kemejaColors,
            'sizes' => $kemejaSizes,
            'tags' => ['Kemeja', 'Linen', 'Casual']
        ]);

        // 4. Buat Produk 2 (Headphone) -> Relasi ke $seller dan $subSubHeadphone
        Product::create([
            'user_id' => $seller->id,
            'sub_sub_category_id' => $subSubHeadphone->id,
            'name' => 'Wireless Noise Cancelling Headphone HIFI Bass',
            'slug' => Str::slug('Wireless Noise Cancelling Headphone HIFI Bass'),
            'description' => 'Headphone nirkabel dengan Active Noise Cancelling (ANC) hingga 35dB, driver 40mm Titanium untuk suara jernih.',
            'price' => 549000,
            'original_price' => 899000,
            'discount_percentage' => 39,
            'stock' => 28,
            'rating' => 4.9,
            'review_count' => 812,
            'sold_count' => 2450,
            'is_best_seller' => true,
            'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=700&auto=format&fit=crop&q=80',
            'images' => [
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=700&auto=format&fit=crop&q=80'
            ],
            'specifications' => ['Bluetooth' => 'Versi 5.3', 'Baterai' => '45 Jam Playtime'],
            'colors' => [['name' => 'Matte Black', 'hex' => '#1E1E1E']],
            'sizes' => null,
            'tags' => ['Headphone', 'ANC', 'Wireless']
        ]);

        $this->call(ReviewSampleSeeder::class);
    }
}
