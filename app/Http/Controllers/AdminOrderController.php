<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Order::with('user')->latest();

        if ($user->role !== 'admin') {
            $query->whereHas('items.product', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $orders = $query->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    // Mengubah status pesanan
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Pending,Diproses,Dikirim,Selesai,Dibatalkan'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pesanan ' . $order->invoice_number . ' berhasil diperbarui!');
    }
}
