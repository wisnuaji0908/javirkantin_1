@extends('layouts.app')

@section('title', 'Tambah Siswa')
@section('breadcrumb', 'Tambah Siswa')
@section('students-active', 'active')

@section('content')
<div class="row min-vh-80 h-100">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h1 class="card-title">Tambah Siswa</h1>
        <form id="create-siswa-form" action="{{ route('siswa.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Siswa</label>
            <input type="text" class="form-control" id="nama" name="nama">
          </div>
          <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select class="form-select" id="kelas" name="kelas_id">
              @foreach($kelas as $k)
              <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="nomor_telp" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="nomor_telp" name="nomor_telp">
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
document.getElementById('create-siswa-form').addEventListener('submit', function(event) {
  event.preventDefault();

  let nama = document.getElementById('nama').value.trim();
  let kelas = document.getElementById('kelas').value;
  let alamat = document.getElementById('alamat').value.trim();
  let nomorTelp = document.getElementById('nomor_telp').value.trim();

  if (nama === '' || kelas === '' || alamat === '' || nomorTelp === '') {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Kolom Nama Siswa, Kelas, Alamat, dan Nomor Telepon harus diisi!',
    });
    return;
  }

  if (isNaN(nomorTelp)) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Kolom Nomor Telepon harus berupa angka!',
    });
    return;
  }

  fetch('{{ route('siswa.check') }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ nama: nama, kelas_id: kelas })
  })
  .then(response => response.json())
  .then(data => {
    if (data.sameNameAndClass) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Data Siswa dengan nama tersebut sudah ada di kelas yang sama!',
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
          document.getElementById('create-siswa-form').submit();
        }
      });
    }
  });
});
</script>
@endpush
