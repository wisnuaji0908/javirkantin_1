@extends('seller.master')

@section('content')
<div class="container">
    <h1>Reset Email</h1>

    <form action="{{ route('profile.' . $role . '.email.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="current_email" class="form-label">Email Saat Ini</label>
            <input type="text" class="form-control" id="current_email" value="{{ $email }}" readonly>
        </div>

        <div class="mb-3">
            <label for="new_email" class="form-label">Email Baru</label>
            <input type="email" class="form-control" id="new_email" name="new_email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password Saat ini</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password Saat Ini</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
