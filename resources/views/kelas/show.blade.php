@extends('layouts.app')

@section('title', 'Detail Kelas')
@section('breadcrumb', 'Detail Kelas')
@section('classes-active', 'active')

@section('content')
<div class="row min-vh-80 h-100">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h1 class="card-title">Detail Kelas</h1>
        <h5 class="card-text">Nama Kelas: {{ $kelas->nama_kelas }}</h5>
        <a href="{{ route('kelas.edit', $kelas->id) }}" class="btn btn-primary" id="edit-btn">Edit</a>

        <!-- Form Hapus dengan SweetAlert -->
        <form id="delete-form" action="{{ route('kelas.destroy', $kelas->id) }}" method="POST" style="display: inline-block;">
          @csrf
          @method('DELETE')
          <button type="button" class="btn btn-danger" id="delete-btn">Hapus</button>
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
      title: 'Edit Kelas',
      text: 'Anda akan mengarahkan untuk mengedit kelas ini. Lanjutkan?',
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
  document.getElementById('delete-btn').addEventListener('click', function(event) {
    event.preventDefault();

    Swal.fire({
      title: 'Hapus Kelas',
      text: 'Anda yakin ingin menghapus kelas ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('delete-form').submit();
      }
    });
  });
</script>
@endpush
