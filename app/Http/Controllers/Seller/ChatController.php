<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewMessage;

class ChatController extends Controller
{
    /**
     * Menampilkan daftar percakapan Seller.
     */
    public function index()
    {
        $sellerId = Auth::id();

        // Ambil admin
        $admin = User::where('role', 'admin')->first();

        // Ambil daftar chat dengan buyer
        $chats = Chat::where('seller_id', $sellerId)
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('chats')
                    ->groupBy('buyer_id', 'seller_id');
            })
            ->with(['buyer', 'latestMessageForSeller'])
            ->orderByDesc('created_at')
            ->get();

        // Jika ada admin, buat chat dummy agar muncul di UI
        if ($admin) {
            $adminChat = (object) [
                'id' => 0,
                'buyer' => $admin,
                'latestMessageForSeller' => (object) [
                    'message' => 'Admin siap membantu!',
                    'created_at' => now(),
                ],
            ];

            // Tambahkan admin ke daftar chat (paling atas)
            $chats->prepend($adminChat);
        }

        return view('seller.chat.index', compact('chats', 'admin'));
    }


    /**
     * Halaman isi chat dengan buyer tertentu.
     */
    public function show($buyerId)
    {
        $sellerId = Auth::id();
        $buyer = User::where('id', $buyerId)->firstOrFail(); // Admin atau Buyer bisa masuk sini

        // 1. Ambil semua pesan "unread" yang dikirim buyer (sender_id = $buyerId)
        $unreadMessages = Chat::where('seller_id', $sellerId)
            ->where('buyer_id', $buyerId)
            ->where('sender_id', $buyerId)  // pesan yang dikirim Buyer
            ->where('is_read', false)
            ->get();

        // 2. Jika ada pesan unread, set is_read = true
        if ($unreadMessages->count() > 0) {
            // Ambil ID pesan
            $chatIds = $unreadMessages->pluck('id')->toArray();

            // Update is_read
            Chat::whereIn('id', $chatIds)->update(['is_read' => true]);

            // 3. Broadcast event "MessageRead"
            // Agar di sisi Buyer, bubble "Belum dibaca" bisa berubah "Sudah dibaca" real-time
            event(new \App\Events\MessageRead($chatIds, $sellerId, $buyerId));
            /*
             * Contoh constructor:
             * public function __construct(array $chatIds, int $readerId, int $senderId) { ... }
             */
        }

        // 4. Ambil semua pesan untuk ditampilkan
        $chats = Chat::where('seller_id', $sellerId)
            ->where('buyer_id', $buyerId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('seller.chat.show', compact('buyer', 'chats'));
    }

    /**
     * Kirim pesan ke buyer.
     */
    public function store(Request $request, $buyerId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // (B) Buat pesan baru
        $chat = Chat::create([
            'seller_id' => Auth::id(),
            'buyer_id' => $buyerId,
            'sender_id' => Auth::id(),  // menandakan “yang mengetik = Seller”
            'message' => $request->message,
            'is_read' => false,       // Buyer belum baca
        ]);

        // Broadcast event ke Redis -> Node.js
        event(new NewMessage($chat));

        return back()->with('success', 'Pesan berhasil dikirim!');
    }
}
