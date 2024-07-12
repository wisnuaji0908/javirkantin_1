@extends('layouts.app')

@section('title', 'Daftar Siswa')
@section('breadcrumb', 'Daftar Siswa')
@section('students-active', 'active')

@section('content')
<div class="row min-vh-80 h-100">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h1 class="card-title">Daftar Siswa</h1>
        
        @if(session('success'))
        <div class="alert alert-success" role="alert">
          {{ session('success') }}
        </div>
        @endif
        
        @if($errors->any())
        <div class="alert alert-danger" role="alert">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        
        <div class="mb-3">
          <form method="GET" action="{{ route('siswa.index') }}" class="d-flex mb-3">
            <div class="input-group no-border">
              <input type="search" name="search" class="form-control" placeholder="Cari Siswa..." value="{{ request()->input('search') }}">
              <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Cari</button>
              </div>
            </div>
            <button type="button" class="btn btn-secondary ml-2" id="refresh-btn">Refresh</button>
          </form>
          <a href="{{ route('siswa.create') }}" class="btn btn-primary">Tambah Siswa</a>
          <a href="{{ route('dashboard.index') }}" class="btn btn-secondary ml-2">Kembali ke Dashboard</a>
        </div>
        
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>NO</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Alamat</th>
                <th>Nomor Telepon</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($siswas as $key => $student)
              <tr>
                <td>{{ ($siswas->currentPage() - 1) * $siswas->perPage() + $key + 1 }}</td>
                <td>{{ $student->nama }}</td>
                <td>{{ $student->kelas->nama_kelas }}</td>
                <td>{{ $student->alamat }}</td>
                <td>{{ $student->nomor_telp }}</td>
                <td>
                  <a href="{{ route('siswa.show', $student->id) }}" class="btn btn-info btn-sm">Detail</a>
                  <a href="{{ route('siswa.edit', $student->id) }}" class="btn btn-primary btn-sm">Edit</a>
                  <form action="{{ route('siswa.destroy', $student->id) }}" method="POST" class="delete-form" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger btn-sm delete-btn">Hapus</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6">Tidak ada siswa yang cocok dengan pencarian Anda.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Tambahkan pagination links disini -->
        <div class="d-flex justify-content-center">
          {{ $siswas->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // SweetAlert untuk Hapus Siswa
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
      const deleteButton = form.querySelector('.delete-btn');
      deleteButton.addEventListener('click', function (event) {
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
            form.submit();
          }
        });
      });
    });

    // Fungsi tombol refresh
    const refreshBtn = document.getElementById('refresh-btn');
    refreshBtn.addEventListener('click', function () {
      window.location.href = "{{ route('siswa.index') }}";
    });
  });
</script>
@endpush
