<!DOCTYPE html>
<html>
<head>
    <title>Profil Pengguna</title>
</head>
<body>
    <h1>Profil {{Auth::user()->name}}</h1>
    <p>Email: {{Auth::user()->email}}</p>
    <div>
        <h2>QR Code Profil</h2>
        {!! $qrCode !!}
    </div>
    <a href="{{ route('user.qrcode', $user->id) }}">Generate QR Code for Login</a>
</body>
</html>