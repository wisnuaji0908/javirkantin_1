@extends('buyer.master')

@section('title', 'Daftar Chat')

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
</style>

<div class="container my-1">
    <h3 class="mb-3">Daftar Chat</h3>
    <div class="chat-list">
        @foreach($chats as $chat)
            @php
                $profileImage = $chat->seller->profile_image
                    ? asset('storage/' . $chat->seller->profile_image)
                    : asset('images/default.png');

                $latestMessage = $chat->latestMessageForBuyer ?? null;

                // Pastikan latestMessage tidak null
                if ($latestMessage) {
                    $isSentByMe = $latestMessage->sender_id == Auth::id();
                    $messageText = $isSentByMe ? 'Anda: ' . $latestMessage->message : $latestMessage->message;
                    $messageTime = $latestMessage->created_at->format('H:i');
                } else {
                    $messageText = 'Belum ada pesan';
                    $messageTime = '';
                }

                // Hitung jumlah pesan belum dibaca
                $unreadCount = \App\Models\Chat::where('buyer_id', Auth::id())
                    ->where('seller_id', $chat->seller_id)
                    ->where('sender_id', $chat->seller_id)
                    ->where('is_read', false)
                    ->count();
            @endphp
            <a href="{{ route('buyer.chat.show', $chat->seller_id) }}" class="chat-item">
                <img src="{{ $profileImage }}" alt="Seller Photo">
                <div class="chat-info">
                    <strong>{{ $chat->seller->name }}</strong>
                    <p>{{ $messageText }}</p>
                </div>
                <div class="chat-time">
                    @if($messageTime)
                        <div>{{ $messageTime }}</div>
                    @endif
                    @if($unreadCount > 0)
                        <div class="unread-badge">{{ $unreadCount }}</div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    @php
        $reviewData = json_decode(Auth::user()->review_notification ?? '[]', true);
    @endphp

    let reviewDataArray = {!! json_encode($reviewData) !!};

    if (!Array.isArray(reviewDataArray) || reviewDataArray.length === 0) {
        console.warn("❌ Tidak ada data review yang valid!");
        return;
    }

    function showNextReview(index) {
        if (index >= reviewDataArray.length) return;

        let reviewData = reviewDataArray[index];
        let sellerId = reviewData.seller_id ?? null;

        if (!sellerId) {
            console.error("❌ Seller ID tidak ditemukan untuk order:", reviewData.order_id);
            return;
        }

        Swal.fire({
            title: "Konfirmasi Pesanan?",
            text: `Pesanan '${reviewData.product_name}' dari '${reviewData.seller_name}' sudah Anda terima?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Selesaikan!",
            cancelButtonText: "Belum"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/buyer/orders/update-status/${reviewData.order_id}`, { // ✅ Perbaiki path URL
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({
                        status: "finish",
                        seller_id: sellerId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text) });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire("Sukses!", "Pesanan telah selesai.", "success")
                        .then(() => {
                            showNextReview(index + 1);
                        });
                    } else {
                        Swal.fire("Error!", "Gagal memperbarui status pesanan.", "error");
                    }
                })
                .catch(error => {
                    console.error("❌ Fetch error:", error);
                    Swal.fire("Error!", "Terjadi kesalahan, coba lagi nanti.", "error");
                });
            }
        });
    }

    showNextReview(0);
});
        </script>
@endsection
