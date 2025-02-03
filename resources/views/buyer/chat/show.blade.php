@extends('buyer.master')

@section('title', 'Isi Chat')

@section('content')

<style>
    /* ======= Chat Header Style ======= */
    .chat-header {
        display: flex;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #ccc;
        background-color: #f8f8f8;
        margin-bottom: 0.5rem;
    }
    .chat-header img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
    }
    .chat-header .seller-info {
        margin-left: 10px;
    }
    .chat-header .seller-info h5 {
        margin: 0;
        font-size: 1rem;
    }
    .chat-header .seller-info p {
        margin: 0;
        font-size: 0.8rem;
        color: #666;
    }

    /* ======= Chat Box ======= */
    .chat-box {
        height: 60vh;
        overflow-y: auto;
        padding: 10px;
        background-color: #ffffff;
        border-radius: 5px;
        border: 1px solid #e5e5e5;
    }

    /* ======= Bubble Style ======= */
    .bubble {
        max-width: 70%;
        border-radius: 15px;
        padding: 8px 12px;
        margin-bottom: 8px;
        position: relative;
    }

    /* Buyer bubble (You) → Right */
    .bubble-right {
        background-color: #c8e6c9; /* hijau muda */
        margin-left: auto;
        text-align: right;
    }

    /* Seller bubble → Left */
    .bubble-left {
        background-color: #e1f5fe; /* biru muda */
        margin-right: auto;
        text-align: left;
    }

    /* Sender name & time */
    .sender {
        font-weight: bold;
        margin-bottom: 2px;
        font-size: 0.9rem;
    }
    .timestamp {
        font-size: 0.75rem;
        color: #666;
        margin-top: 4px;
        text-align: right;
    }

    /* Input Form */
    #chat-form {
        margin-top: 1rem;
    }
</style>

<div class="container mt-4">
    <!-- Chat Header -->
    <div class="chat-header">
        {{-- Foto profil seller --}}
        @php
            $profileImage = $seller->profile_image
                ? asset('storage/'.$seller->profile_image)
                : asset('images/default.png');
        @endphp
        <img src="{{ $profileImage }}" alt="Seller Photo">
        <div class="seller-info">
            <h5>{{ $seller->name }}</h5>
            {{-- <p>Auth ID: {{ Auth::id() }} | Role: {{ Auth::user()->role }}</p> --}}
            {{-- <p>Online</p> <!-- Atau status lain kalau mau --> --}}
        </div>
    </div>

    <!-- Chat Box -->
    <div class="chat-box" id="chat-box">
        @if ($chats->isNotEmpty())
            @foreach ($chats as $chat)
                @php
                    $isMe = ($chat->sender_id == Auth::id());
                    $bubbleClass = $isMe ? 'bubble-right' : 'bubble-left';
                    // Jika pengirim = saya (Buyer), label "You"
                    // Jika pengirim bukan saya, label = $seller->name
                    $senderName = $isMe ? 'You' : $seller->name;
                @endphp
                <div
                    class="bubble {{ $bubbleClass }}"
                    data-id="{{ $chat->id }}"
                >
                    <div class="sender">{{ $senderName }}</div>
                    <div>{{ $chat->message }}</div>
                    <div class="timestamp">
                        {{ $chat->created_at->format('H:i') }}
                        @if($isMe && !$chat->is_read)
                            <span style="font-size:0.75rem; color:red;">(Belum Dibaca)</span>
                        @elseif($isMe && $chat->is_read)
                            <span style="font-size:0.75rem; color:green;">(Sudah Dibaca)</span>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-muted">Belum ada pesan.</p>
        @endif
    </div>

    <!-- Form Kirim Pesan -->
    <form id="chat-form">
        @csrf
        <div class="input-group">
            <input type="text" name="message" id="message" class="form-control" placeholder="Tulis pesan..." required>
            <button class="btn btn-primary" type="submit">Kirim</button>
        </div>
    </form>
</div>

{{-- Socket.IO Client (CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/socket.io-client@4.5.4/dist/socket.io.min.js"></script>

<script>
    // --------------------------------------------------
    // 1. Connect ke Socket.IO server Node.js
    // --------------------------------------------------
    const socket = io("{{ env('SOCKET_IO_URL', 'http://localhost:3000') }}");

    // Variabel untuk identitas user & seller
    const authId    = {{ Auth::id() }};
    const sellerId  = {{ $seller->id }};
    const chatBox   = document.getElementById('chat-box');
    const chatForm  = document.getElementById('chat-form');
    const messageInput = document.getElementById('message');

    // --------------------------------------------------
    // 2. Listen event "chat:NewMessage" dari Node.js
    // --------------------------------------------------
    socket.on('chat:NewMessage', (data) => {
        console.log("TERIMA EVENT:", data);
        console.log("authId:", authId, "sellerId:", sellerId);

        if (parseInt(data.senderId) === authId) {
        // Artinya Seller sudah baca pesan kita
        data.chatIds.forEach(id => {
            // Temukan bubble dengan data-id=ID
            let bubble = document.querySelector(`.bubble[data-id="${id}"]`);
            if (bubble) {
                // Ubah label "Belum Dibaca" jadi "Sudah Dibaca"
                let timeDiv = bubble.querySelector('.timestamp');
                if (timeDiv) {
                    // contoh: tambahkan text " (Sudah Dibaca)"
                    // atau hapus label Belum Dibaca
                    timeDiv.innerHTML += ' <span style="font-size:0.75rem; color:green;">(Sudah Dibaca)</span>';
                }
            }
        });
    }

    });

    // --------------------------------------------------
    // 3. Function untuk nambah DOM pesan ke chat box (bubble style)
    // --------------------------------------------------
    function addMessageToUI(chat) {
        // Apakah pengirim = me?
        const isMe = (parseInt(chat.sender_id) === authId);

        const bubble = document.createElement('div');
        bubble.classList.add('bubble', isMe ? 'bubble-right' : 'bubble-left');
        bubble.setAttribute('data-id', chat.id);

        const senderDiv = document.createElement('div');
        senderDiv.className = 'sender';
        senderDiv.textContent = isMe ? 'You' : "{{ $seller->name }}";

        const msgBody = document.createElement('div');
        msgBody.textContent = chat.message;

        // Timestamp
        const timeDiv = document.createElement('div');
        timeDiv.className = 'timestamp';
        // Jika chat.timestamp ada (dari local echo), pakai itu.
        // Kalau tidak, pakai jam sekarang.
        let timeText = chat.created_at
            ? chat.created_at
            : new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        timeDiv.textContent = timeText;

        bubble.appendChild(senderDiv);
        bubble.appendChild(msgBody);
        bubble.appendChild(timeDiv);

        chatBox.appendChild(bubble);
        chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll
    }

    // --------------------------------------------------
    // 4. Submit form -> fetch ke Laravel -> save MySQL -> broadcast event
    //    + LOCAL ECHO di sisi klien
    // --------------------------------------------------
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (!message) return;

        // Local echo: buat ID sementara (pseudo ID)
        const tempId = 'temp-' + Date.now();

        // Tambahkan ke UI dulu
        addMessageToUI({
            id: tempId,
            buyer_id: authId,
            seller_id: sellerId,
            sender_id: authId,      // <--- diisi
            message: message,
            created_at: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
        });

        // Reset input
        messageInput.value = '';

        try {
            const response = await fetch("{{ route('buyer.chat.store', $seller->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message })
            });

            if (!response.ok) {
                console.error("Error saat mengirim pesan");
                // Opsional: Bisa hapus "local echo" kalau error,
                // atau ubah warna bubble menandakan gagal terkirim.
            }
        } catch (err) {
            console.error(err);
            // Opsional: ubah bubble jadi status error
        }
    });
</script>
@endsection
