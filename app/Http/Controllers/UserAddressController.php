<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderByDesc('is_primary')->latest()->get();
        return view('pages.addresses', compact('user', 'addresses'));
    }

    // Menyimpan alamat baru
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'full_address' => 'required|string',
        ]);

        $user = Auth::user();

        $isFirst = $user->addresses()->count() === 0;

        $user->addresses()->create([
            'label' => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'full_address' => $request->full_address,
            'is_primary' => $isFirst ? true : false,
        ]);

        return back()->with('success', 'Alamat baru berhasil ditambahkan!');
    }

    // Mengubah alamat (Edit)
    public function update(Request $request, Address $address)
    {
        // Pastikan alamat ini milik user yang sedang login
        if ($address->user_id !== Auth::id()) abort(403);

        $request->validate([
            'label' => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'full_address' => 'required|string',
        ]);

        $address->update($request->only('label', 'recipient_name', 'phone', 'full_address'));

        return back()->with('success', 'Alamat berhasil diperbarui!');
    }

    // Menghapus alamat
    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);

        $address->delete();

        if ($address->is_primary) {
            $nextAddress = Auth::user()->addresses()->first();
            if ($nextAddress) {
                $nextAddress->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Alamat berhasil dihapus!');
    }

    // Menjadikan alamat sebagai Alamat Utama
    public function setPrimary(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);

        // Ubah semua alamat user ini menjadi BUKAN utama
        Auth::user()->addresses()->update(['is_primary' => false]);

        // Jadikan alamat yang dipilih menjadi utama
        $address->update(['is_primary' => true]);

        return back()->with('success', 'Alamat utama berhasil diubah!');
    }
}
