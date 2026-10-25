<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        \App\Models\User::find($user->id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
    public function editPassword()
    {
        return view('pages.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'], // Bawaan Laravel untuk cek password lama
            'password' => ['required', 'min:6', 'confirmed'], // Wajib ada password_confirmation
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.'
        ]);

        $request->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    // --- FITUR KEAMANAN (HAPUS AKUN) ---
    public function security()
    {
        return view('pages.security');
    }

    public function destroyAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.current_password' => 'Password yang Anda masukkan salah. Gagal menghapus akun.'
        ]);

        $user = $request->user();

        // Logout akun
        Auth::logout();

        // Hapus akun dari database
        $user->delete();

        // Bersihkan sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus secara permanen.');
    }
}
