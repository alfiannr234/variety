<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        $orderQuery = Order::query();

        if (!$isAdmin) {
            $orderQuery->whereHas('items.product', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $totalRevenue = (clone $orderQuery)->whereIn('status', ['paid', 'success', 'completed', 'Selesai', 'selesai'])->sum('total_amount');
        $totalOrders = (clone $orderQuery)->count();
        $pendingOrders = (clone $orderQuery)->whereIn('status', ['pending', 'Pending'])->count();

        $recentOrders = (clone $orderQuery)->with('user')
            ->latest()
            ->take(6)
            ->get();
        $productQuery = Product::query();


        if (!$isAdmin) {
            $productQuery->where('user_id', $user->id);
        }

        $totalProducts = $productQuery->count();

        $lowStockProducts = (clone $productQuery)->where('stock', '<=', 5)
            ->latest()
            ->take(5)
            ->get();

        $totalRatings = Review::whereHas('product', function ($q) use ($user, $isAdmin) {
            if (!$isAdmin) {
                $q->where('user_id', $user->id);
            }
        })->count();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'totalProducts',
            'recentOrders',
            'lowStockProducts',
            'totalRatings'
        ));
    }
}
