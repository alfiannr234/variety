<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Memulai chat dari halaman detail produk
    public function startChat($seller_id)
    {
        $buyer_id = Auth::id();

        // Cek agar tidak bisa chat dengan diri sendiri
        if ($buyer_id == $seller_id) {
            return back()->with('error', 'Anda tidak bisa mengirim pesan ke toko Anda sendiri.');
        }

        // Cari ruang chat yang sudah ada, atau buat baru
        $chat = Chat::firstOrCreate([
            'user_id' => $buyer_id,
            'seller_id' => $seller_id
        ]);

        return redirect()->route('chat.index', ['room' => $chat->id]);
    }

    // Menampilkan halaman Inbox & Ruang Chat
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil semua riwayat chat (baik dia sebagai pembeli atau penjual)
        $chats = Chat::where('user_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->with(['user', 'seller', 'latestMessage'])
            ->get()
            ->sortByDesc(fn($chat) => $chat->latestMessage->created_at ?? $chat->created_at);

        $activeChat = null;
        $messages = [];

        // Jika sedang membuka salah satu ruang chat
        if ($request->has('room')) {
            $activeChat = Chat::findOrFail($request->room);

            // Validasi kepemilikan chat
            if ($activeChat->user_id !== $user->id && $activeChat->seller_id !== $user->id) {
                abort(403);
            }

            $messages = $activeChat->messages()->with('sender')->oldest()->get();

            // Tandai pesan telah dibaca
            $activeChat->messages()->where('sender_id', '!=', $user->id)->update(['is_read' => true]);
        }

        return view('pages.chat', compact('chats', 'activeChat', 'messages', 'user'));
    }

    // Mengirim pesan
    public function store(Request $request, Chat $chat)
    {
        $userId = Auth::id();
        if ($chat->user_id !== $userId && $chat->seller_id !== $userId) {
            abort(403, 'Akses tidak diizinkan untuk percakapan ini.');
        }

        $request->validate(['message' => 'required|string']);

        $chat->messages()->create([
            'sender_id' => Auth::id(),
            'message' => $request->message
        ]);

        return back();
    }
}
