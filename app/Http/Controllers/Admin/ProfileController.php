<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'store_description' => $request->store_description,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar && str_contains($user->avatar, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = '/storage/' . $avatarPath;
        }

        $user->update($data);

        return back()->with('success', 'Profil toko berhasil diperbarui!');
    }
}
