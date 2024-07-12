@extends('layouts.app')

@section('title', 'Tambah Kelas')
@section('breadcrumb', 'Tambah Kelas')
@section('classes-active', 'active')

@section('content')
<div class="row min-vh-80 h-100">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h1 class="card-title">Tambah Kelas</h1>
        <form id="tambah-kelas-form" action="{{ route('kelas.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="nama_kelas" class="form-label">Nama Kelas</label>
            <input type="text" class="form-control" id="nama_kelas" name="nama_kelas">
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
document.getElementById('tambah-kelas-form').addEventListener('submit', function(event) {
  event.preventDefault();

  const namaKelas = document.getElementById('nama_kelas').value.trim();

  if (namaKelas === '') {
    Swal.fire({
      title: 'Error!',
      text: 'Kolom Nama Kelas harus diisi terlebih dahulu!',
      icon: 'error',
      confirmButtonText: 'OK'
    });
    return;
  }

  // AJAX request to check if the class already exists
  fetch('{{ route('kelas.check') }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ nama_kelas: namaKelas })
  })
  .then(response => response.json())
  .then(data => {
    if (data.exists) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Kelas dengan nama tersebut sudah ada!',
      });
    } else {
      Swal.fire({
        title: 'Anda yakin ingin menyimpan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, simpan!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('tambah-kelas-form').submit();
        }
      });
    }
  });
});
</script>
@endpush
