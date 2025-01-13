<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Material Dashboard 2 Styles -->
    <link rel="stylesheet" href="../assets/css/material-dashboard.css?v=3.1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-200">
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5 col-md-7 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header text-center">
                                    <h4 class="font-weight-bolder">Register</h4>
                                    <p class="mb-0">Enter your email and password to register</p>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="input-group input-group-outline mb-3 password-container">
                                            <label class="form-label">Password</label>
                                            <input type="password" id="password" name="password" class="form-control" required>
                                            <span class="toggle-password" onclick="togglePasswordVisibility('password', 'eyeIconPassword')">
                                                <i id="eyeIconPassword" class="material-icons">visibility_off</i>
                                            </span>
                                            @error('password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="input-group input-group-outline mb-3 password-container">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                                            <span class="toggle-password" onclick="togglePasswordVisibility('password_confirmation', 'eyeIconConfirmPassword')">
                                                <i id="eyeIconConfirmPassword" class="material-icons">visibility_off</i>
                                            </span>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Register</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0">
                                    <p class="mb-2 text-sm mx-auto">
                                        Already have an account?
                                        <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Login</a>
                                    </p>
                                </div>
                            </div>
                        </div>
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

            if (passwordInput.value) {
                passwordInput.closest('.input-group').classList.add('is-filled');
            }
        }

        document.querySelectorAll('.input-group input').forEach(input => {
            input.addEventListener('input', () => {
                if (input.value) {
                    input.closest('.input-group').classList.add('is-filled');
                } else {
                    input.closest('.input-group').classList.remove('is-filled');
                }
            });
        });
    </script>
</body>
</html>
