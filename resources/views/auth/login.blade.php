<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Material Dashboard 2 Styles -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/material-dashboard.css?v=3.1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top:73%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        body {
            background-image: url('../image/bg1.jpg');
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 400px;
            background: #fff;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #f9b10a;
        }
        .input-group {
            margin: 15px 0;
        }
        .input-group label {
            display: block;
            font-size: 14px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background: #f9b10a;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn:hover {
            background: #e69a00;
        }
        .toggle-text {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }
        .toggle-text a {
            color: #f9b10a;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="{{ route('login') }}" role="form" class="text-start">
            @csrf
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="input-group password-container">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <span class="toggle-password" onclick="togglePasswordVisibility('password', 'eyeIconPassword')">
                    <i id="eyeIconPassword" class="material-icons">visibility_off</i>
                </span>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-check form-switch d-flex align-items-center mb-3">
                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
            </div>
            <div class="text-center">
                <button type="submit" class="btn">Login</button>
            </div>
            <p class="mt-4 text-sm text-center">
                <a href="{{ route('password.request') }}" class="text-primary text-gradient font-weight-bold">Forgot Password?</a>
            </p>
            <p class="toggle-text">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
        </form>
    </div>
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = 'visibility';
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = 'visibility_off';
            }
        }
    </script>
</body>
</html>
