@extends('layouts.app')

@section('title', 'Hapus Kelas')
@section('breadcrumb', 'Hapus Kelas')
@section('classes-active', 'active')

@section('content')
<div class="row min-vh-80 h-100">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h1 class="card-title">Hapus Kelas</h1>
        <h5 class="card-text">Nama Kelas: {{ $kelas->nama_kelas }}</h5>
        <p class="card-text">Anda yakin ingin menghapus kelas ini?</p>
        <form id="hapus-kelas-form" action="{{ route('kelas.destroy', $kelas->id) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="button" id="hapus-button" class="btn btn-danger">Ya, Hapus</button>
          <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
document.getElementById('hapus-button').addEventListener('click', function(event) {
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
      document.getElementById('hapus-kelas-form').submit();
    }
  });
});
</script>
@endpush
