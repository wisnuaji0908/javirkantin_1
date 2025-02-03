@extends('seller.master')

@section('title', 'List Chat')

@section('content')

<style>
    .chat-list {
        display: flex;
        flex-direction: column;
    }

    .chat-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #e5e5e5;
        text-decoration: none;
        color: black;
    }

    .chat-item:hover {
        background-color: #f8f9fa;
    }

    .chat-item img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
    }

    .chat-info {
        flex-grow: 1;
    }

    .chat-info strong {
        font-size: 1rem;
        display: block;
    }

    .chat-info p {
        font-size: 0.9rem;
        color: #666;
        margin: 3px 0;
    }

    .chat-time {
        text-align: right;
        font-size: 0.8rem;
        color: #888;
    }

    .unread-badge {
        background-color: red;
        color: white;
        font-size: 0.75rem;
        padding: 3px 7px;
        border-radius: 10px;
    }

    /* Warna khusus untuk admin */
    .admin-chat {
        background-color: #ffeeba;
        position: relative;
    }
</style>

<div class="container my-4">
    <h3 class="mb-3">List Chat</h3>
    <div class="chat-list">

        @if($admin)
        @php
            // Ambil pesan terakhir dari admin ke seller atau seller ke admin
            $latestAdminMessage = \App\Models\Chat::where(function ($query) use ($admin) {
            $query->where('seller_id', Auth::id()) // Pesan yang dikirim ke seller
                    ->where('buyer_id', $admin->id); // Dari admin
            })
            ->orWhere(function ($query) use ($admin) {
                $query->where('buyer_id', Auth::id()) // Pesan yang dikirim dari seller
                    ->where('seller_id', $admin->id); // Ke admin
            })
            ->orderByDesc('created_at')
            ->first();


            // Pesan terakhir admin
            $adminMessageText = $latestAdminMessage ?
                ($latestAdminMessage->sender_id == Auth::id() ? 'Anda: ' : '') . $latestAdminMessage->message
                : 'Belum ada pesan';

                $adminUnreadCount = \App\Models\Chat::where('seller_id', Auth::id()) // Seller menerima pesan
                ->where('buyer_id', $admin->id) // Dari admin
                ->where('is_read', false) // Belum dibaca
                ->where('sender_id', $admin->id) // Pastikan pesan itu benar-benar dari admin
                ->count();
        @endphp

<a href="{{ route('seller.chat.show', $admin->id) }}" class="chat-item admin-chat">
    <img src="{{ $admin->profile_image ? asset('storage/' . $admin->profile_image) : asset('images/admin-default.png') }}" alt="Admin Photo">
    <div class="chat-info">
        <strong>Admin</strong>
        <p>{{ $adminMessageText }}</p>
    </div>
    <div class="chat-time">
        @if($latestAdminMessage)
            <div>{{ $latestAdminMessage->created_at->format('H:i') }}</div>
        @endif
        @if($adminUnreadCount > 0)
            <div class="unread-badge">{{ $adminUnreadCount }}</div>
        @endif
    </div>
</a>
    @endif

        @foreach($chats as $chat)
            @if(isset($chat->buyer) && $chat->buyer->role !== 'admin')
                @php
                    $profileImage = $chat->buyer->profile_image
                        ? asset('storage/' . $chat->buyer->profile_image)
                        : asset('images/default.png');

                    $latestMessage = $chat->latestMessageForSeller ?? null;
                    $messageText = $latestMessage ? ($latestMessage->sender_id == Auth::id() ? 'Anda: ' : '') . $latestMessage->message : 'Belum ada pesan';

                    $unreadCount = \App\Models\Chat::where('seller_id', Auth::id())
                        ->where('buyer_id', $chat->buyer_id)
                        ->where('sender_id', $chat->buyer_id)
                        ->where('is_read', false)
                        ->count();
                @endphp
                <a href="{{ route('seller.chat.show', $chat->buyer_id) }}" class="chat-item">
                    <img src="{{ $profileImage }}" alt="Buyer Photo">
                    <div class="chat-info">
                        <strong>{{ $chat->buyer->name }}</strong>
                        <p>{{ $messageText }}</p>
                    </div>
                    <div class="chat-time">
                        @if($latestMessage)
                            <div>{{ $latestMessage->created_at->format('H:i') }}</div>
                        @endif
                        @if($unreadCount > 0)
                            <div class="unread-badge">{{ $unreadCount }}</div>
                        @endif
                    </div>
                </a>
            @endif
        @endforeach

    </div>
</div>

@endsection
