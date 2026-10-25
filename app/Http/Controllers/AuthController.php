<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('pages.register');
    }
    public function showLogin()
    {
        return view('pages.login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $emailPrefix = explode('@', $request->email)[0];

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $emailPrefix,
            'role' => 'user'
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Akun berhasil dibuat!');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/katalog')->with('success', 'Berhasil login kembali!');
        }

        return back()->with('error', 'Email atau Password salah.');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/katalog')->with('success', 'Kamu berhasil logout.');
    }
    public function upgradeToSeller()
    {
        $user = Auth::user();

        if ($user->role === 'user') {
            \App\Models\User::find($user->id)->update(['role' => 'seller']);

            return redirect('/admin/dashboard')->with('success', 'Selamat! Akun Anda kini menjadi Penjual. Silakan atur profil toko Anda.');
        }

        return redirect('/admin/dashboard');
    }
}
