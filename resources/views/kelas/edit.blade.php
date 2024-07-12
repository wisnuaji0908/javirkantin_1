@extends('layouts.app')

@section('title', 'Edit Kelas')
@section('breadcrumb', 'Edit Kelas')
@section('classes-active', 'active')

@section('content')
<div class="container-fluid py-4">
  <div class="row min-vh-80 h-100">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h1 class="card-title">Edit Kelas</h1>
          <form id="edit-kelas-form" action="{{ route('kelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label for="nama_kelas" class="form-label">Nama Kelas</label>
              <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" value="{{ $kelas->nama_kelas }}">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
document.getElementById('edit-kelas-form').addEventListener('submit', function(event) {
  event.preventDefault();

  const initialNamaKelas = '{{ $kelas->nama_kelas }}';
  const namaKelas = document.getElementById('nama_kelas').value.trim();

  if (namaKelas === '') {
    Swal.fire({
      title: 'Error!',
      text: 'Nama Kelas harus diisi terlebih dahulu!',
      icon: 'error',
      confirmButtonText: 'OK'
    });
    return;
  }

  if (namaKelas === initialNamaKelas) {
    Swal.fire({
      title: 'Error!',
      text: 'Tidak ada perubahan yang dilakukan pada Nama Kelas!',
      icon: 'error',
      confirmButtonText: 'OK'
    });
    return;
  }

  // AJAX request to check if the class name is already taken
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
    if (data.exists && data.exists !== '{{ $kelas->id }}') {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Kelas dengan nama tersebut sudah ada!',
      });
    } else {
      Swal.fire({
        title: 'Anda yakin ingin menyimpan perubahan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, simpan!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('edit-kelas-form').submit();
        }
      });
    }
  })
  .catch(error => {
    console.error('Error:', error);
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Terjadi kesalahan saat memeriksa kelas!',
    });
  });
});
</script>
@endpush
