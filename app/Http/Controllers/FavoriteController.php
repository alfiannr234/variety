<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $favorites = $user->favorites()->latest()->get();
        return view('pages.favorites', compact('favorites'));
    }

    public function toggle(Product $product)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->favorites()->toggle($product->id);

        return back()->with('success', 'Daftar favorit berhasil diperbarui!');
    }
}
