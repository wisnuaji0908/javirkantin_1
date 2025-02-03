<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Diblokir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f6f6f6;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .blocked-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            max-width: 500px;
            text-align: center;
            padding: 30px;
            animation: fadeIn 1s ease-in-out;
        }

        .blocked-container img {
            max-width: 100px;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }

        .blocked-container h1 {
            font-size: 2rem;
            color: #ff4b4b;
            margin-bottom: 15px;
        }

        .blocked-container p {
            color: #333;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .btn-primary {
            background: #007bff;
            border: none;
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-3px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body>
    <div class="blocked-container">
        <img src="{{ asset("image/cancel.png") }}" alt="Blocked Icon">
        <h1>Akun Diblokir</h1>
        <p>
            Akun Anda telah diblokir oleh admin. Jika Anda merasa ini adalah kesalahan,
            silakan hubungi admin untuk informasi lebih lanjut.
        </p>
        <a href="{{ route('login') }}" class="btn btn-primary">Kembali ke Login</a>
    </div>
</body>
</html>
