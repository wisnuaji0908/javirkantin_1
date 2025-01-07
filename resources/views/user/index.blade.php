<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @php
        use Illuminate\Support\Facades\Auth;
    @endphp

    <nav class="navbar navbar-expand-lg bg-primary-subtle">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="{{ route('user.qrcode', Auth::user()->id) }}" class="nav-link">{{ Auth::user()->name }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link text-body font-weight-bold px-0 ms-4">
                                <span>Log Out</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        Halaman User
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Selamat datang di halaman user, {{ Auth::user()->name }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
