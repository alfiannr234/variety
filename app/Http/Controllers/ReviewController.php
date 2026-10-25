<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $existingReview = Review::where('user_id', Auth::id())
            ->where('order_id', $request->order_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('reviews', 'public');
                    $imagePaths[] = '/storage/' . $path;
                }
            }
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'color' => $request->color,
            'size' => $request->size,
            'images' => !empty($imagePaths) ? $imagePaths : null,
        ]);

        $product = \App\Models\Product::find($request->product_id);
        if ($product) {
            $avgRating = Review::where('product_id', $product->id)->avg('rating') ?: 0;
            $count = Review::where('product_id', $product->id)->count();
            $product->update([
                'rating' => round($avgRating, 1),
                'review_count' => $count,
            ]);
        }

        return back()->with('success', 'Terima kasih! Ulasan Anda telah berhasil dikirim.');
    }
}
