<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewMessage;

class ChatSellerController extends Controller
{
    /**
     * Tampilkan isi chat antara buyer dan seller tertentu.
     */
    public function show($sellerId)
    {
        $seller = User::where('id', $sellerId)
            ->where('role', 'seller')
            ->firstOrFail();

        // Tandai pesan buyer->seller sebagai terbaca (opsional)
        Chat::where('buyer_id', Auth::id())
            ->where('seller_id', $sellerId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $chats = Chat::where('buyer_id', Auth::id())
            ->where('seller_id', $sellerId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('buyer.chat.show', compact('seller', 'chats'));
    }

    /**
     * Kirim pesan dari buyer ke seller.
     */
    public function store(Request $request, $sellerId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chat = Chat::create([
            'buyer_id' => Auth::id(),
            'seller_id' => $sellerId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Broadcast event
        event(new NewMessage($chat));

        return back()->with('success', 'Pesan berhasil dikirim!');
    }
}
