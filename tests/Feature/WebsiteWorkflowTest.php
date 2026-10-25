<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\MenuTab;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class WebsiteWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_pages_load_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->get('/katalog');
        $response->assertStatus(200);

        $product = Product::first();
        $this->assertNotNull($product);

        // Verify product detail and relationship
        $response = $this->get('/produk/' . $product->slug);
        $response->assertStatus(200);
        $this->assertNotNull($product->subCategory);
        $this->assertEquals('Atasan', $product->subCategory->name);
    }

    public function test_cart_operations(): void
    {
        $product = Product::first();

        // Add to cart
        $response = $this->post('/keranjang/tambah', [
            'product_id' => $product->id,
            'qty' => 2,
            'color' => 'Olive Green',
            'size' => 'M',
        ]);
        $response->assertRedirect('/keranjang');

        // View cart
        $response = $this->get('/keranjang');
        $response->assertStatus(200);
        $response->assertSee($product->name);

        // Clear cart
        $response = $this->post('/keranjang/clear');
        $response->assertRedirect('/keranjang');
        $response->assertSessionMissing('cart');
    }

    public function test_user_registration_and_profile_update(): void
    {
        $response = $this->post('/register', [
            'email' => 'buyer@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);
        $response->assertRedirect('/');

        $user = User::where('email', 'buyer@example.com')->first();
        $this->assertNotNull($user);

        // Update profile with phone and address
        $response = $this->actingAs($user)->put('/profil', [
            'name' => 'Buyer John',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 45 Jakarta',
        ]);
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('081234567890', $user->phone);
        $this->assertEquals('Jl. Merdeka No. 45 Jakarta', $user->address);
    }

    public function test_checkout_and_order_flow(): void
    {
        $product = Product::first();
        $initialStock = $product->stock;

        $user = User::create([
            'name' => 'Customer Satu',
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
            'phone' => '08987654321',
            'address' => 'Jl. Sudirman No. 10',
            'role' => 'user',
        ]);

        // Add to cart
        $this->withSession([
            'cart' => [
                $product->id . '-Olive Green-M' => [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'color' => 'Olive Green',
                    'size' => 'M',
                    'quantity' => 2,
                ]
            ]
        ]);

        // Checkout page
        $response = $this->actingAs($user)->get('/checkout');
        $response->assertStatus(200);

        // Place order
        $response = $this->actingAs($user)->post('/order/create', [
            'recipient_name' => 'Customer Satu',
            'phone' => '08987654321',
            'address' => 'Jl. Sudirman No. 10',
            'payment_method' => 'qris',
        ]);
        $response->assertRedirect('/riwayat-pesanan');

        // Verify Order in DB
        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertStringStartsWith('INV-', $order->invoice_number);
        $this->assertEquals('Customer Satu', $order->customer_name);
        $this->assertEquals(($product->price * 2) + 15000, $order->total_amount);
        $this->assertEquals($order->total_amount, $order->total_price);

        // Verify stock decremented
        $product->refresh();
        $this->assertEquals($initialStock - 2, $product->stock);

        // Verify order items created
        $this->assertCount(1, $order->items);
        $this->assertEquals($product->id, $order->items->first()->product_id);
        $this->assertEquals(2, $order->items->first()->quantity);

        // View Order History
        $response = $this->actingAs($user)->get('/riwayat-pesanan');
        $response->assertStatus(200);
        $response->assertSee($order->invoice_number);
    }

    public function test_admin_and_seller_permissions(): void
    {
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $sellerUser = User::create([
            'name' => 'Seller User',
            'email' => 'seller@example.com',
            'password' => Hash::make('password123'),
            'role' => 'seller',
        ]);

        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin_test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Regular user blocked from admin dashboard
        $response = $this->actingAs($regularUser)->get('/admin/dashboard');
        $response->assertStatus(403);

        // Seller allowed to dashboard
        $response = $this->actingAs($sellerUser)->get('/admin/dashboard');
        $response->assertStatus(200);

        // Seller blocked from admin-only categories
        $response = $this->actingAs($sellerUser)->get('/admin/categories');
        $response->assertStatus(403);

        // Admin allowed to categories
        $response = $this->actingAs($adminUser)->get('/admin/categories');
        $response->assertStatus(200);
    }

    public function test_user_addresses(): void
    {
        $user = User::create([
            'name' => 'Address User',
            'email' => 'address@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->post('/profil/alamat', [
            'label' => 'Rumah',
            'recipient_name' => 'Address User',
            'phone' => '08123456789',
            'full_address' => 'Jl. Kebon Jeruk No. 12',
        ]);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'label' => 'Rumah',
            'is_primary' => true,
        ]);
    }

    public function test_favorite_toggle(): void
    {
        $user = User::create([
            'name' => 'Fav User',
            'email' => 'fav@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $product = Product::first();

        // Toggle on
        $response = $this->actingAs($user)->post('/favorit/' . $product->id);
        $response->assertSessionHas('success');
        $this->assertTrue($user->favorites()->where('product_id', $product->id)->exists());

        // Toggle off
        $response = $this->actingAs($user)->post('/favorit/' . $product->id);
        $response->assertSessionHas('success');
        $this->assertFalse($user->favorites()->where('product_id', $product->id)->exists());
    }

    public function test_product_review(): void
    {
        $user = User::create([
            'name' => 'Reviewer',
            'email' => 'reviewer@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $product = Product::first();

        $order = Order::create([
            'user_id' => $user->id,
            'invoice_number' => 'INV-TESTREV1',
            'customer_name' => $user->name,
            'total_amount' => $product->price,
            'status' => 'Selesai',
        ]);

        $response = $this->actingAs($user)->post('/reviews', [
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Produk sangat bagus dan sesuai deskripsi!',
        ]);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
        ]);

        $product->refresh();
        $this->assertGreaterThan(0, $product->review_count);
    }

    public function test_product_detail_dynamic_store_stats(): void
    {
        $product = Product::first();
        $response = $this->get('/produk/' . $product->slug);

        $response->assertStatus(200);
        $response->assertViewHas('storeStats');
        $storeStats = $response->viewData('storeStats');

        $this->assertArrayHasKey('rating', $storeStats);
        $this->assertArrayHasKey('reply_rate', $storeStats);
        $this->assertArrayHasKey('joined', $storeStats);
        $this->assertArrayHasKey('product_count', $storeStats);
        $this->assertArrayHasKey('response_speed', $storeStats);
        $this->assertArrayHasKey('followers_count', $storeStats);
        $this->assertArrayHasKey('active_status', $storeStats);
    }

    public function test_product_detail_review_filters_and_media(): void
    {
        $product = Product::first();
        $response = $this->get('/produk/' . $product->slug);

        $response->assertStatus(200);
        $response->assertSee('FILTER ULASAN');
        $response->assertSeeText('Dengan Foto & Video', false);
        $response->assertSeeText('Foto & Video Pembeli', false);
        $response->assertSeeText('Urutkan', false);
        $response->assertSeeText('Paling Membantu', false);
        $response->assertSeeText('Terbaru', false);
        $response->assertSeeText('Rating Tertinggi', false);
        $response->assertSeeText('Rating Terendah', false);
        $response->assertSee('reviewFilterSystem');
    }

    public function test_product_review_with_uploaded_image(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Reviewer Media',
            'email' => 'reviewer.media@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $product = Product::first();

        $order = Order::create([
            'user_id' => $user->id,
            'invoice_number' => 'INV-TESTMEDIA1',
            'customer_name' => $user->name,
            'total_amount' => $product->price,
            'status' => 'Selesai',
        ]);

        $file = UploadedFile::fake()->create('ulasan_baju.jpg', 150, 'image/jpeg');

        $response = $this->actingAs($user)->post('/reviews', [
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Kemeja sangat bagus dan foto terlampir!',
            'images' => [$file],
        ]);

        $response->assertSessionHas('success');

        $review = \App\Models\Review::where('user_id', $user->id)->first();
        $this->assertNotNull($review);
        $this->assertIsArray($review->images);
        $this->assertCount(1, $review->images);
        $this->assertStringContainsString('/storage/reviews/', $review->images[0]);
    }

    public function test_purchase_product_with_variations(): void
    {
        $user = User::create([
            'name' => 'Buyer Variasi',
            'email' => 'buyervariasi@example.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'address' => 'Jl. Kebon Jeruk No. 88, Jakarta Barat',
            'role' => 'user',
        ]);

        $product = Product::first();
        $this->assertNotEmpty($product->colors);
        $this->assertNotEmpty($product->sizes);
        $this->assertNotEmpty($product->variant_stocks);

        $chosenColor = 'Navy Blue';
        $chosenSize = 'XL';
        $vKey = "{$chosenColor}-{$chosenSize}";
        $initialVariantStock = $product->variant_stocks[$vKey];
        $initialProductStock = $product->stock;

        // 1. Tambah variasi ke keranjang via HTTP
        $response = $this->actingAs($user)->post('/keranjang/tambah', [
            'product_id' => $product->id,
            'color' => $chosenColor,
            'size' => $chosenSize,
            'qty' => 2,
        ]);
        $response->assertRedirect('/keranjang');

        // 2. Akses halaman checkout
        $response = $this->actingAs($user)->get('/checkout');
        $response->assertStatus(200);
        $response->assertSee('Navy Blue');
        $response->assertSee('XL');

        // 3. Proses pesanan
        $response = $this->actingAs($user)->post('/order/create', [
            'recipient_name' => 'Buyer Variasi',
            'phone' => '081298765432',
            'address' => 'Jl. Kebon Jeruk No. 88, Jakarta Barat',
            'payment_method' => 'bca_va',
        ]);
        $response->assertRedirect('/riwayat-pesanan');

        // 4. Verifikasi pesanan & order items
        $order = Order::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('bca_va', $order->payment_method);
        $this->assertCount(1, $order->items);

        $orderItem = $order->items->first();
        $this->assertEquals($product->id, $orderItem->product_id);
        $this->assertEquals(2, $orderItem->quantity);
        $this->assertEquals($chosenColor, $orderItem->color);
        $this->assertEquals($chosenSize, $orderItem->size);

        // 5. Verifikasi pengurangan stok varian dan total produk
        $product->refresh();
        $this->assertEquals($initialProductStock - 2, $product->stock);
        $this->assertEquals($initialVariantStock - 2, $product->variant_stocks[$vKey]);

        // 6. Verifikasi halaman riwayat pesanan
        $response = $this->actingAs($user)->get('/riwayat-pesanan');
        $response->assertStatus(200);
        $response->assertSee($order->invoice_number);
        $response->assertSee('Navy Blue');
        $response->assertSee('XL');
    }

    public function test_admin_can_view_product_with_variations_in_edit_page(): void
    {
        $adminUser = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Admin Variety',
            'email' => 'admin_variation_test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $product = Product::whereNotNull('variant_stocks')->first();
        $this->assertNotNull($product);
        $this->assertNotEmpty($product->formatted_variants);

        $response = $this->actingAs($adminUser)->get('/admin/products/' . $product->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee('Daftar Variasi Produk');
        $response->assertSee('Olive Green - S');
        $response->assertSee('Navy Blue - M');
    }

    public function test_admin_can_update_product_variations_via_admin_panel(): void
    {
        $adminUser = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Admin Variety',
            'email' => 'admin_variation_test2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $product = Product::whereNotNull('variant_stocks')->first();
        $this->assertNotNull($product);

        // Update product variations
        $response = $this->actingAs($adminUser)->put('/admin/products/' . $product->id, [
            'name' => $product->name,
            'sub_sub_category_id' => $product->sub_sub_category_id,
            'description' => $product->description,
            'has_variation' => '1',
            'variants' => [
                [
                    'name' => 'Navy Blue - M',
                    'price' => 189000,
                    'stock' => 40,
                ],
                [
                    'name' => 'Navy Blue - L',
                    'price' => 189000,
                    'stock' => 35,
                ],
                [
                    'name' => 'Crimson Red - XL',
                    'price' => 210000,
                    'stock' => 15,
                ],
            ],
        ]);

        $response->assertRedirect('/admin/products');
        $response->assertSessionHas('success');

        $product->refresh();
        $this->assertEquals(90, $product->stock); // 40 + 35 + 15
        $this->assertEquals(189000, $product->price);
        $this->assertNotNull($product->colors);
        $this->assertNotNull($product->sizes);
        $this->assertCount(2, $product->colors); // Navy Blue, Crimson Red
        $this->assertEquals(['M', 'L', 'XL'], $product->sizes);
        $this->assertArrayHasKey('Navy Blue-M', $product->variant_stocks);
        $this->assertArrayHasKey('Crimson Red-XL', $product->variant_stocks);
        $this->assertEquals(40, $product->variant_stocks['Navy Blue-M']['stock']);
        $this->assertEquals(15, $product->variant_stocks['Crimson Red-XL']['stock']);

        // Test customer purchasing the updated variant
        $customer = User::create([
            'name' => 'Buyer Tester',
            'email' => 'buyer_var@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $this->actingAs($customer)->post('/keranjang/tambah', [
            'product_id' => $product->id,
            'color' => 'Crimson Red',
            'size' => 'XL',
            'qty' => 3,
        ]);

        $this->actingAs($customer)->post('/order/create', [
            'recipient_name' => 'Buyer Tester',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'payment_method' => 'cod',
        ]);

        $product->refresh();
        $this->assertEquals(87, $product->stock); // 90 - 3
        $this->assertEquals(12, $product->variant_stocks['Crimson Red-XL']['stock']); // 15 - 3
    }

    public function test_admin_can_toggle_off_variations(): void
    {
        $adminUser = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Admin Variety',
            'email' => 'admin_variation_test3@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $product = Product::whereNotNull('variant_stocks')->first();
        $this->assertNotNull($product);

        $response = $this->actingAs($adminUser)->put('/admin/products/' . $product->id, [
            'name' => $product->name,
            'sub_sub_category_id' => $product->sub_sub_category_id,
            'description' => $product->description,
            // has_variation NOT sent
            'price' => 150000,
            'stock' => 50,
        ]);

        $response->assertRedirect('/admin/products');

        $product->refresh();
        $this->assertEquals(150000, $product->price);
        $this->assertEquals(50, $product->stock);
        $this->assertNull($product->colors);
        $this->assertNull($product->sizes);
        $this->assertNull($product->variant_stocks);
    }
}
