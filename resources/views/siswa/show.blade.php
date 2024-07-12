@extends('layouts.app')

@section('title', 'Detail Siswa')
@section('breadcrumb', 'Detail Siswa')
@section('students-active', 'active')

@section('content')
<div class="row min-vh-80 h-100">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h1 class="card-title">Detail Siswa</h1>
        <h5 class="card-text">Nama: {{ $siswa->nama }}</h5>
        <h5 class="card-text">Kelas: {{ $siswa->kelas->nama_kelas }}</h5>
        <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-primary" id="edit-btn">Edit</a>
        <form id="hapus-siswa-form" action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" style="display: inline-block;">
          @csrf
          @method('DELETE')
          <button type="button" class="btn btn-danger" id="hapus-btn">Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
  // SweetAlert untuk Edit
  document.getElementById('edit-btn').addEventListener('click', function(event) {
    event.preventDefault();

    Swal.fire({
      title: 'Edit Siswa',
      text: 'Anda akan mengarahkan untuk mengedit siswa ini. Lanjutkan?',
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, lanjutkan!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = this.getAttribute('href');
      }
    });
  });

  // SweetAlert untuk Hapus
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
</script>
@endpush
