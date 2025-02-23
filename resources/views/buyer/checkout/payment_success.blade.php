@extends('buyer.master')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="success-container">
    <div class="success-card">
        <h1 class="success-title">🎉 Pembayaran Berhasil! 🎉</h1>
        <p class="success-message">Terima kasih telah melakukan pembayaran. Pesanan Anda sedang diproses. Silakan ambil pesanan dengan menunjukkan bukti pembayaran.</p>
        <a href="{{ route('checkout.receipt', ['order_id' => $order_id]) }}" class="btn btn-primary animate-btn">Lihat Bukti Pembayaran</a>
    </div>
</div>

<!-- Tambahin Confetti Effect -->
<canvas id="confetti"></canvas>

<style>
/* 🎨 Styling Tampilan */
body {
    background: linear-gradient(to right, #00c6ff, #0072ff);
    font-family: 'Poppins', sans-serif;
}

.success-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
    text-align: center;
}

.success-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
    animation: fadeIn 1.2s ease-in-out;
    width: 50%;
    max-width: 600px;
}

.success-title {
    font-size: 2rem;
    font-weight: bold;
    color: #28a745;
    animation: bounce 1.2s infinite alternate;
}

.success-message {
    font-size: 1rem;
    color: #555;
    margin: 1rem 0;
}

.btn-primary {
    background-color: #007bff;
    border: none;
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: bold;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

/* ✨ Animasi */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes bounce {
    from { transform: translateY(0); }
    to { transform: translateY(-10px); }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // 🎉 Tambahin efek confetti
    const confettiCanvas = document.getElementById("confetti");
    const ctx = confettiCanvas.getContext("2d");
    confettiCanvas.width = window.innerWidth;
    confettiCanvas.height = window.innerHeight;

    let confettiArray = [];
    const colors = ["#ff0", "#f00", "#0f0", "#00f", "#ff4500"];

    for (let i = 0; i < 50; i++) {
        confettiArray.push({
            x: Math.random() * confettiCanvas.width,
            y: Math.random() * confettiCanvas.height,
            r: Math.random() * 8 + 2,
            d: Math.random() * 5 + 2,
            color: colors[Math.floor(Math.random() * colors.length)],
            tilt: Math.random() * 10 - 5,
            tiltAngle: Math.random() * 2 * Math.PI
        });
    }

    function drawConfetti() {
        ctx.clearRect(0, 0, confettiCanvas.width, confettiCanvas.height);
        confettiArray.forEach((c) => {
            ctx.beginPath();
            ctx.arc(c.x, c.y, c.r, 0, 2 * Math.PI);
            ctx.fillStyle = c.color;
            ctx.fill();
        });
        updateConfetti();
    }

    function updateConfetti() {
        confettiArray.forEach((c) => {
            c.y += c.d;
            c.tiltAngle += 0.05;
            c.x += Math.sin(c.tiltAngle);
            if (c.y > confettiCanvas.height) {
                c.y = -10;
                c.x = Math.random() * confettiCanvas.width;
            }
        });
    }

    function animateConfetti() {
        drawConfetti();
        requestAnimationFrame(animateConfetti);
    }

    animateConfetti();
});
</script>
@endsection
