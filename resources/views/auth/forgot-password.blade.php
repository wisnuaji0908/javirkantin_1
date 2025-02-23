<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        body {
            background-image: url('../image/bg1.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
        }

        /* Header */
        .card-header {
            background: #69585e;
            padding: 15px;
            border-radius: 10px 10px 0 0;
        }
        .card-header h4 {
            color: #f9b10a;
            font-size: 22px;
            margin: 0;
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
        }
        .input-group input:focus {
            border-color: #f9b10a;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 12px;
            background: #65545a;
            color: #f9b10a;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            transition: 0.3s;
        }
        .btn:hover {
            background: #f9b10a;
            color: #65545a;
        }

        /* Footer */
        .toggle-text {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .toggle-text a {
            color: #f31440;
            cursor: pointer;
            font-weight: bold;
        }

        /* Responsiveness */
        @media screen and (max-width: 500px) {
            .container {
                width: 90%;
                padding: 25px;
            }
            .card-header h4 {
                font-size: 20px;
            }
            .btn {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <main class="main-content">
        <section>
            <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h4>Forgot Password</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="input-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn">Send Password Reset Link</button>
                            </div>
                        </form>
                    </div>
                    <div class="toggle-text">
                        <a href="{{ route('login') }}">Back to Login</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>
        @if (session('status') == 'admin_reset_error')
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Admin tidak bisa reset password.'
            });
        @elseif (session('status') == 'email_not_found')
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Email tidak ditemukan dalam database!'
            });
        @elseif (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Email Sent!',
                text: '{{ session('status') }}'
            });
        @endif
    </script>
</body>
</html>
