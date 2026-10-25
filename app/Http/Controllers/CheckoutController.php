<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/katalog')->with('error', 'Keranjang kosong.');
        }

        DB::transaction(function () use ($request, $cart) {
            $user = Auth::user();
            if ($user) {
                $user->update([
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]);
            }

            $subtotal = 0;
            $itemsToCreate = [];

            foreach ($cart as $key => $item) {
                $productId = is_array($item) && isset($item['product_id'])
                    ? $item['product_id']
                    : (is_array($item) && isset($item['id']) ? $item['id'] : (int) explode('-', (string) $key)[0]);

                $product = Product::find($productId);
                if (!$product) {
                    continue;
                }

                $qty = is_array($item) ? (int) ($item['quantity'] ?? 1) : 1;
                $price = is_array($item) && isset($item['price']) ? (int) $item['price'] : $product->price;
                $color = is_array($item) ? ($item['color'] ?? null) : null;
                $size = is_array($item) ? ($item['size'] ?? null) : null;

                $subtotal += $price * $qty;
                $product->stock = max(0, (int) $product->stock - $qty);

                if ($product->variant_stocks && is_array($product->variant_stocks)) {
                    $vStocks = $product->variant_stocks;
                    $vKey = ($color && $size) ? "{$color}-{$size}" : ($color ?: $size);
                    if ($vKey && isset($vStocks[$vKey])) {
                        if (is_array($vStocks[$vKey])) {
                            $vStocks[$vKey]['stock'] = max(0, (int) ($vStocks[$vKey]['stock'] ?? 0) - $qty);
                        } else {
                            $vStocks[$vKey] = max(0, (int) $vStocks[$vKey] - $qty);
                        }
                        $product->variant_stocks = $vStocks;
                    }
                }
                $product->save();

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $price,
                    'color' => $color,
                    'size' => $size,
                ];
            }

            $ongkir = 15000;
            $totalPrice = $subtotal + $ongkir;

            $order = Order::create([
                'user_id' => Auth::id(),
                'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                'customer_name' => $request->recipient_name,
                'total_amount' => $totalPrice,
                'payment_method' => $request->payment_method,
                'status' => 'Pending',
            ]);

            foreach ($itemsToCreate as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            session()->forget('cart');
        });

        return redirect('/riwayat-pesanan')->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
    }
    public function checkout()
    {
        $user = Auth::user();

        if (empty($user->address) || empty($user->phone)) {
            return redirect()->route('user.profile.edit')
                ->with('warning', 'Silakan lengkapi nomor telepon dan alamat pengiriman Anda sebelum membeli barang.');
        }
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/keranjang')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $subtotal = 0;
        $checkoutItems = [];

        foreach ($cart as $key => $item) {
            // Ambil ID produk (menangani key string seperti '1-Olive Green-S' maupun array id)
            $productId = is_array($item) && isset($item['product_id'])
                ? $item['product_id']
                : (is_array($item) && isset($item['id']) ? $item['id'] : (int) explode('-', (string) $key)[0]);

            $product = \App\Models\Product::with('subCategory.category')->find($productId);

            if (!$product) {
                continue;
            }

            $quantity = is_array($item) ? ($item['quantity'] ?? 1) : 1;
            $price = is_array($item) && isset($item['price']) ? $item['price'] : $product->price;
            $itemTotal = $price * $quantity;
            $subtotal += $itemTotal;

            $checkoutItems[] = [
                'cart_key'   => $key,
                'product'    => $product,
                'quantity'   => $quantity,
                'price'      => $price,
                'item_total' => $itemTotal,
                'color'      => is_array($item) ? ($item['color'] ?? null) : null,
                'size'       => is_array($item) ? ($item['size'] ?? null) : null,
            ];
        }

        $ongkir = 15000;
        $totalPrice = $subtotal + $ongkir;

        return view('pages.checkout', compact('checkoutItems', 'subtotal', 'ongkir', 'totalPrice'));
    }
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product'])
            ->latest()
            ->get();

        return view('pages.order-history', compact('orders'));
    }
}
