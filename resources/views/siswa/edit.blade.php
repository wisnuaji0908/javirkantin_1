@extends('layouts.app')

@section('title', 'Edit Siswa')
@section('breadcrumb', 'Edit Siswa')
@section('students-active', 'active')

@section('content')
<div class="row min-vh-80 h-100">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h1 class="card-title">Edit Siswa</h1>
        <form id="edit-siswa-form" action="{{ route('siswa.update', $siswa->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Siswa</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ $siswa->nama }}">
          </div>
          <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select class="form-select" id="kelas" name="kelas_id">
              @foreach($kelas as $k)
              <option value="{{ $k->id }}" {{ $k->id == $siswa->kelas_id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ $siswa->alamat }}</textarea>
          </div>
          <div class="mb-3">
            <label for="nomor_telp" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="nomor_telp" name="nomor_telp" value="{{ $siswa->nomor_telp }}">
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
document.getElementById('edit-siswa-form').addEventListener('submit', function(event) {
  event.preventDefault();

  let nama = document.getElementById('nama').value.trim();
  let kelas = document.getElementById('kelas').value;
  let alamat = document.getElementById('alamat').value.trim();
  let nomorTelp = document.getElementById('nomor_telp').value.trim();

  // Nilai awal
  let initialNama = '{{ $siswa->nama }}'.trim();
  let initialKelas = '{{ $siswa->kelas_id }}';
  let initialAlamat = '{{ $siswa->alamat }}'.trim();
  let initialNomorTelp = '{{ $siswa->nomor_telp }}'.trim();

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

  if (nama === initialNama && kelas === initialKelas && alamat === initialAlamat && nomorTelp === initialNomorTelp) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Tidak ada perubahan data yang dilakukan!',
    });
    return;
  }

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
      document.getElementById('edit-siswa-form').submit();
    }
  });
});
</script>
@endpush
