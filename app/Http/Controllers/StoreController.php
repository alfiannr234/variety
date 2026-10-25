<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;

class StoreController extends Controller
{
    public function show(string $id)
    {
        $store = User::findOrFail($id);

        $products = Product::where('user_id', $store->id)->latest()->get();

        return view('pages.store', compact('store', 'products'));
    }
}
