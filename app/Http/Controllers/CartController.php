<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index(Request $request)
    {

        $cartItems = $request->session()->get('cart', []);


        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return view('pages.cart', [
            'title' => 'Keranjang Belanja',
            'cartItems' => $cartItems,
            'subtotal' => $subtotal
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'color' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = $request->session()->get('cart', []);

        $cartKey = $product->id . '-' . $request->color . '-' . $request->size;

        $itemPrice = $product->price;
        $itemImage = $product->image;

        if (!empty($product->variant_stocks) && is_array($product->variant_stocks)) {
            $vKey = ($request->color && $request->size) ? "{$request->color}-{$request->size}" : ($request->color ?: $request->size);
            if ($vKey && isset($product->variant_stocks[$vKey])) {
                $vData = $product->variant_stocks[$vKey];
                if (is_array($vData) && !empty($vData['price'])) {
                    $itemPrice = (int) $vData['price'];
                }
                if (is_array($vData) && !empty($vData['image'])) {
                    $itemImage = $vData['image'];
                }
            }
        }

        if ($itemImage === $product->image && !empty($request->color) && !empty($product->colors) && is_array($product->colors)) {
            $matchedCol = collect($product->colors)->firstWhere('name', $request->color);
            if ($matchedCol && !empty($matchedCol['image'])) {
                $itemImage = $matchedCol['image'];
            }
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->qty;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $itemPrice,
                'image' => $itemImage,
                'color' => $request->color,
                'size' => $request->size,
                'quantity' => $request->qty,
            ];
        }

        $request->session()->put('cart', $cart);
        return redirect('/keranjang');
    }
    public function destroy($cartKey)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
    public function clear()
    {
        session()->forget('cart');
        return redirect('/keranjang')->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
