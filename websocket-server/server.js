const io = require("socket.io")(3000, {
    cors: {
        origin: "*", // Izinkan semua origin (untuk dev)
    },
});
const Redis = require("ioredis");
const redis = new Redis();

// Subscribe channel "chat" (sesuaikan dengan event broadcastOn() di Laravel)
redis.subscribe("chat");

// Terima pesan dari Redis, lalu broadcast ke semua klien
redis.on("message", (channel, message) => {
    // message dari Redis masih string JSON, parse dulu
    const data = JSON.parse(message);
    console.log("DARI REDIS:", data); // <-- debug

    // Format emit => 'chat:NewMessage' atau sesuai event lo
    // data di sini adalah $this->chat (object) di event NewMessage
    io.emit(`${channel}:NewMessage`, data);
});

io.on("connection", (socket) => {
    console.log("User connected:", socket.id);

    // Test: Akses data dari client
    socket.on("test-message", (msg) => {
        console.log("Pesan dari client:", msg);
    });

    socket.on("disconnect", () => {
        console.log("User disconnected:", socket.id);
    });
});
