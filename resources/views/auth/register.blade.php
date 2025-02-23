<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Material Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/material-dashboard.css?v=3.1.0">

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Tambahkan link Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            /* Ganti dengan asset() kalau di Laravel dan file bg1.jpg ada di folder public/image */
            background-image: url('/image/bg1.jpg');
            background-size: cover;
            background-position: center;

            /* Flex container untuk center */
            display: flex;
            justify-content: center;
            align-items: center;

            /* Gunakan min-height agar kalau konten melebihi tinggi layar, tetap bisa di-scroll */
            min-height: 100vh;
            margin: 0;
            padding: 10px;
        }

        .container {
            width: 100%;
            max-width: 450px;
            background: #fff;
            padding: 20px; /* tambah padding biar lebih lega */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
        }

        /* Header */
        .card-header {
            text-align: center;
            margin-bottom: 10px;
        }
        h4 {
            font-size: 26px;
            font-weight: bold;
            color: #f9b10a;
            margin-bottom: 5px;
        }
        p {
            font-size: 16px;
            color: #666;
        }

        /* Input Field */
        .input-group {
            width: 100%;
            margin-bottom: 15px;
            text-align: left;
        }
        .input-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            font-size: 16px;
            background: white;
        }
        .input-group input:focus {
            border-color: #f9b10a;
        }

        /* Password Visibility Icon */
        .password-container {
            position: relative;
        }
        .password-container input {
            padding-right: 45px;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 60%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            font-size: 18px;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 12px;
            background: #f9b10a;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            transition: 0.3s;
        }
        .btn:hover {
            background: #e69a00;
        }

        /* Footer / Link Login */
        .card-footer p {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }
        .card-footer a {
            color: #f9b10a;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }

        /* Responsiveness */
        @media screen and (max-width: 500px) {
            .container {
                width: 90%;
                padding: 25px;
            }
            h4 {
                font-size: 22px;
            }
            .input-group input {
                padding: 10px;
            }
            .btn {
                padding: 10px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <main class="main-content">
        <section>
            <div class="container">
                <div class="card card-plain">
                    <div class="card-header text-center">
                        <h4 class="font-weight-bolder">Register</h4>
                        <p>Masukan email dan password untuk register</p>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="input-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
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
                            <div class="input-group password-container">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required>
                                <span class="toggle-password" onclick="togglePasswordVisibility('password_confirmation', 'eyeIconConfirmPassword')">
                                    <i id="eyeIconConfirmPassword" class="material-icons">visibility_off</i>
                                </span>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn">Register</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center pt-0">
                        <p class="mb-2 text-sm">
                            Sudah punya akun kah?
                            <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Login</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

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
