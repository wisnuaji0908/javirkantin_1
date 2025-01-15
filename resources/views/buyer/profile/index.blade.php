@extends('buyer.master')

@section('title', 'Profile Buyer')

@section('page-title', 'Akun Saya')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
<style>
    .app-header {
        position: sticky;
        top: 0;
        z-index: 1000;
        background-color: #ffffff;
        box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: left;
        justify-content: left;
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
    }

    .app-header h1 {
        font-size: 18px;
        color: #1a1c1e;
        font-weight: bold;
        margin: 0;
        flex-grow: 1;
        text-align: left;
    }

    .profile-container {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .profile-header {
        text-align: center;
        position: relative;
        width: 80px;
        height: 80px;
    }

    .profile-header img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .profile-header img:hover {
        transform: scale(1.1);
    }

    .edit-icon {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 24px;
        height: 24px;
        background-color: white;
        border-radius: 50%;
        border: 2px solid #1cdd12;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .profile-header:hover .edit-icon {
        transform: scale(1.1);
    }

    .form-input {
        margin-bottom: 20px;
    }

    .form-input label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-input input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .btn-save {
        color: rgb(255, 255, 255);
        border: none;
        padding: 15px;
        width: 100%;
        border-radius: 5px;
        font-size: 16px;
        text-align: center;
    }
</style>

<div class="app-header">
    <h1>Profile Saya</h1>
</div>

<div class="profile-container">
    <div class="profile-header">
        <img id="profile_preview" src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('path/to/default-avatar.png') }}" alt="Avatar" class="avatar">
        <div class="edit-icon" onclick="document.getElementById('profile_image_input').click();">
            <i class="fas fa-pencil-alt" style="font-size: 12px; color: #123bdd;"></i>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('profile.buyer.update') }}" enctype="multipart/form-data">
    @csrf
    <input type="file" id="profile_image_input" name="profile_image" accept="image/*" style="display: none;" onchange="previewImage(event);">

    <div class="form-input">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required>
    </div>

    <div class="form-input">
        <label for="email">Email</label>
        <div class="input-group">
            <input type="text" id="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
            <button type="button" class="btn btn-primary input-group-text" onclick="sendResetLink()"><i class="fas fa-edit"></i></button>
        </div>
    </div>

    <div class="form-input">
        <label for="current_password">Password Saat Ini</label>
        <div style="position: relative;">
            <input type="password" id="current_password" name="current_password" placeholder="Masukkan password saat ini" style="padding-right: 40px;">
            <i class="fas fa-eye" id="toggleCurrentPassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
        </div>
        @error('current_password')
                <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-input">
        <label for="password">Password Baru</label>
        <div style="position: relative;">
            <input type="password" id="password" name="password" placeholder="Ubah password jika perlu" style="padding-right: 40px;">
            <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
        </div>
    </div>

    <!-- Input Konfirmasi Password Baru -->
    <div class="form-input">
        <label for="password_confirmation">Konfirmasi Password Baru</label>
        <div style="position: relative;">
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" style="padding-right: 40px;">
            <i class="fas fa-eye" id="toggleConfirmPassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-save">Simpan</button>
</form>

<!-- Flash Message Noty -->
@if (session('success'))
    <script>
        new Noty({
            text: "{{ session('success') }}",
            type: 'success',
            layout: 'topRight',
            timeout: 5000,
            progressBar: true,
        }).show();
    </script>
@endif

@if ($errors->any())
    <script>
        new Noty({
            text: "{{ $errors->first() }}",
            type: 'error',
            layout: 'topRight',
            timeout: 5000,
            progressBar: true,
        }).show();
    </script>
@endif

<script>
    function sendResetLink() {
        fetch('{{ route("profile.email.send-reset-link") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Berhasil!', 'Link reset email telah dikirim.', 'success');
            } else {
                Swal.fire('Gagal!', 'Gagal mengirim link reset email.', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Terjadi Kesalahan!', 'Tidak dapat menghubungi server.', 'error');
        });
    }

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            document.getElementById('profile_preview').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
    const toggleCurrentPassword = document.querySelector('#toggleCurrentPassword');
    const currentPassword = document.querySelector('#current_password');
    toggleCurrentPassword.addEventListener('click', function () {
        const type = currentPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        currentPassword.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });

    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirmPassword = document.querySelector('#password_confirmation');

    toggleConfirmPassword.addEventListener('click', function (e) {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);

        if (type === 'password') {
            toggleConfirmPassword.classList.remove('fa-eye-slash');
            toggleConfirmPassword.classList.add('fa-eye');
        } else {
            toggleConfirmPassword.classList.remove('fa-eye');
            toggleConfirmPassword.classList.add('fa-eye-slash');
        }
    });
</script>
@endsection
