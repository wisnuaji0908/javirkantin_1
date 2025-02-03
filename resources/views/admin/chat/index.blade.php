@extends('admin.master')

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

    /* Warna khusus untuk chat dengan seller */
    .seller-chat {
        background-color: #e1f5fe;
    }

</style>

<div class="container my-4">
    <h3 class="mb-3">List Chat</h3>
    <div class="chat-list">

        @foreach($chats as $chat)
            @if(isset($chat->seller) && $chat->seller->role === 'seller')
                @php
                    $profileImage = $chat->seller->profile_image
                        ? asset('storage/' . $chat->seller->profile_image)
                        : asset('images/default.png');

                    $latestMessage = $chat->latestMessageForSeller ?? null;
                    $messageText = $latestMessage ? ($latestMessage->sender_id == Auth::id() ? 'Anda: ' : '') . $latestMessage->message : 'Belum ada pesan';

                    $unreadCount = \App\Models\Chat::where('buyer_id', Auth::id()) // Admin sebagai penerima
                        ->where('seller_id', $chat->seller_id)
                        ->where('sender_id', $chat->seller_id)
                        ->where('is_read', false)
                        ->count();
                @endphp
                <a href="{{ route('admin.chat.show', $chat->seller_id) }}" class="chat-item seller-chat">
                    <img src="{{ $profileImage }}" alt="Seller Photo">
                    <div class="chat-info">
                        <strong>{{ $chat->seller->name }}</strong>
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
