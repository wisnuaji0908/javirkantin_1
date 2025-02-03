<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewMessage;
use App\Events\MessageRead;

class ChatController extends Controller
{
    /**
     * Menampilkan daftar chat (opsi).
     */
    public function index()
    {
        $buyerId = Auth::id();

        $chats = Chat::where('buyer_id', $buyerId)
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('chats')
                    ->groupBy('seller_id', 'buyer_id');
            })
            ->with(['seller', 'latestMessageForBuyer'])
            ->orderByDesc('created_at')
            ->get();

        return view('buyer.chat.index', compact('chats'));
    }

    /**
     * Halaman isi chat dengan seller tertentu.
     */
    public function show($sellerId)
    {
        $buyerId = Auth::id();
        $seller = User::where('id', $sellerId)->where('role', 'seller')->firstOrFail();

        // (A) Cari pesan "unread" yang dikirim Seller ke Buyer
        $unreadMessages = Chat::where('buyer_id', $buyerId)
            ->where('seller_id', $sellerId)
            ->where('sender_id', $sellerId)  // Artinya pesan dari Seller
            ->where('is_read', false)
            ->get();

        if ($unreadMessages->count() > 0) {
            // Ambil ID pesan yang belum dibaca
            $chatIds = $unreadMessages->pluck('id')->toArray();

            // Update is_read = true
            Chat::whereIn('id', $chatIds)->update(['is_read' => true]);

            // (B) Broadcast event MessageRead
            // Agar Seller tahu pesan2-nya sudah dibaca
            event(new MessageRead($chatIds, $buyerId, $sellerId));
        }

        // Ambil semua pesan untuk ditampilkan
        $chats = Chat::where('buyer_id', $buyerId)
            ->where('seller_id', $sellerId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('buyer.chat.show', compact('seller', 'chats'));
    }
    /**
     * Buyer mengirim pesan ke Seller.
     */
    public function store(Request $request, $sellerId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Simpan ke MySQL
        // - buyer_id  => Auth::id() (ID buyer)
        // - seller_id => $sellerId (ID seller)
        // - sender_id => Auth::id() (yang mengetik)
        // - is_read   => false, karena Seller belum lihat
        $chat = Chat::create([
            'buyer_id' => Auth::id(),
            'seller_id' => $sellerId,
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Broadcast event ke Redis -> Node.js
        event(new NewMessage($chat));

        return back()->with('success', 'Pesan berhasil dikirim!');
    }
}
