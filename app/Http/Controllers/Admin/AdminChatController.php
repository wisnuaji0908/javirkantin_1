<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewMessage;

class AdminChatController extends Controller
{
    public function index()
    {
        $adminId = Auth::id();

        // Ambil daftar seller
        $chats = Chat::where('buyer_id', $adminId) // Admin sebagai penerima
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('chats')
                    ->groupBy('seller_id', 'buyer_id');
            })
            ->with(['seller', 'latestMessageForSeller'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.chat.index', compact('chats'));
    }

    public function show($sellerId)
    {
        $adminId = Auth::id();
        $seller = User::where('id', $sellerId)->where('role', 'seller')->firstOrFail();

        // Update status is_read untuk pesan yang belum dibaca
        $unreadMessages = Chat::where('buyer_id', $adminId)
            ->where('seller_id', $sellerId)
            ->where('sender_id', $sellerId)
            ->where('is_read', false)
            ->get();

        if ($unreadMessages->count() > 0) {
            $chatIds = $unreadMessages->pluck('id')->toArray();
            Chat::whereIn('id', $chatIds)->update(['is_read' => true]);
            event(new \App\Events\MessageRead($chatIds, $adminId, $sellerId));
        }

        $chats = Chat::where('buyer_id', $adminId)
            ->where('seller_id', $sellerId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.chat.show', compact('seller', 'chats'));
    }

    public function store(Request $request, $sellerId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $adminId = Auth::id();

        $chat = Chat::create([
            'buyer_id' => $adminId,
            'seller_id' => $sellerId,
            'sender_id' => $adminId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        event(new NewMessage($chat));

        return back()->with('success', 'Pesan berhasil dikirim!');
    }
}
