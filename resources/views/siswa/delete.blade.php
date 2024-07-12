@extends('layouts.app')

@section('title', 'Hapus Siswa')
@section('breadcrumb', 'Hapus Siswa')
@section('students-active', 'active')

@section('content')
<div class="row min-vh-80 h-100">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h1 class="card-title">Hapus Siswa</h1>
        <h5 class="card-text">Nama: {{ $siswa->nama }}</h5>
        <p class="card-text">Anda yakin ingin menghapus siswa ini?</p>
        <form id="hapus-siswa-form" action="{{ route('siswa.destroy', $siswa->id) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="button" id="hapus-btn" class="btn btn-danger">Ya, Hapus</button>
          <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // SweetAlert untuk Hapus Siswa
  document.getElementById('hapus-btn').addEventListener('click', function(event) {
    event.preventDefault();

    Swal.fire({
      title: 'Hapus Siswa',
      text: 'Anda yakin ingin menghapus siswa ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('hapus-siswa-form').submit();
      }
    });
  });
});
</script>
@endpush
