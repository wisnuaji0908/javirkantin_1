<!DOCTYPE html>
<html>
<head>
    <title>QR Code User</title>
</head>
<body>
    @php
        use Illuminate\Support\Facades\Auth;
    @endphp
    <h1>Profil {{Auth::user()->name}}</h1>
    <p>Email: {{Auth::user()->email}}</p>
    <div>
        <h2>QR Code Profil</h2>
        {!! $qrCode !!}
    </div>
</body>
</html>