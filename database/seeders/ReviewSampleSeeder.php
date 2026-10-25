<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ReviewSampleSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::first();
        if (!$product) {
            return;
        }

        $sampleReviews = [
            [
                'name' => 'Dedi Prasetyo',
                'email' => 'dedi.prasetyo@example.com',
                'rating' => 5,
                'comment' => 'Kemejanya benar-benar berkualitas! Bahannya katun linen adem banget dipakai siang hari, jahitan rapih dan fitting-nya pas sekali di badan.',
                'color' => 'Olive Green',
                'size' => 'L',
                'images' => [
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600&auto=format&fit=crop&q=80'
                ],
                'created_at' => now()->subDays(1),
            ],
            [
                'name' => 'Rina Wijayanti',
                'email' => 'rina.wijaya@example.com',
                'rating' => 5,
                'comment' => 'Pengiriman super cepat oleh toko Variety! Packaging aman dan rapi. Warna Warm Beige persis seperti foto produk.',
                'color' => 'Warm Beige',
                'size' => 'M',
                'images' => [
                    'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&auto=format&fit=crop&q=80'
                ],
                'created_at' => now()->subDays(3),
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@example.com',
                'rating' => 4,
                'comment' => 'Bahan cukup oke untuk harga segini, cutting modern fit. Sedikit tipis tapi sangat wajar untuk karakter bahan linen iklim tropis.',
                'color' => 'Olive Green',
                'size' => 'XL',
                'images' => [
                    'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=600&auto=format&fit=crop&q=80'
                ],
                'created_at' => now()->subDays(5),
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti.rahma@example.com',
                'rating' => 4,
                'comment' => 'Cocok banget buat kado suami, ukurannya pas. Seller Variety responsif saat tanya-tanya di chat.',
                'color' => 'Warm Beige',
                'size' => 'L',
                'images' => null,
                'created_at' => now()->subDays(7),
            ],
            [
                'name' => 'Kevin Ardiansyah',
                'email' => 'kevin.ardi@example.com',
                'rating' => 3,
                'comment' => 'Barang sampai dengan selamat, tapi warna aslinya sedikit lebih pekat dari ekspektasi saya. Overall masih oke untuk daily wear.',
                'color' => 'Olive Green',
                'size' => 'M',
                'images' => null,
                'created_at' => now()->subDays(10),
            ],
        ];

        foreach ($sampleReviews as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                ]
            );

            // Create order if not exists
            $order = Order::firstOrCreate(
                ['user_id' => $user->id, 'invoice_number' => 'INV-' . strtoupper(Str::random(10))],
                [
                    'customer_name' => $data['name'],
                    'total_amount' => $product->price,
                    'status' => 'Selesai',
                    'payment_method' => 'qris',
                    'created_at' => $data['created_at'],
                ]
            );

            // Upsert review
            Review::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ],
                [
                    'order_id' => $order->id,
                    'rating' => $data['rating'],
                    'comment' => $data['comment'],
                    'color' => $data['color'],
                    'size' => $data['size'],
                    'images' => $data['images'],
                    'created_at' => $data['created_at'],
                    'updated_at' => $data['created_at'],
                ]
            );
        }

        // Update product statistics
        $avgRating = Review::where('product_id', $product->id)->avg('rating') ?: 0;
        $count = Review::where('product_id', $product->id)->count();
        $product->update([
            'rating' => round($avgRating, 1),
            'review_count' => $count,
        ]);
    }
}
