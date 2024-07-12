@extends('layouts.app')

@section('title', 'Daftar Kelas')
@section('breadcrumb', 'Daftar Kelas')

@section('content')
<div class="row min-vh-80 h-100">
  <div class="col-12">
    <h1>Daftar Kelas</h1>
    <div class="mb-3">
      <form method="GET" action="{{ route('kelas.index') }}" class="d-flex mb-3">
        <div class="input-group no-border">
          <input type="search" name="search" class="form-control" placeholder="Search..." value="{{ request()->input('search') }}">
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Search</button>
          </div>
        </div>
        <button type="button" class="btn btn-secondary ml-2" id="refresh-btn">Refresh</button>
      </form>
      <a href="{{ route('kelas.create') }}" class="btn btn-primary">Tambah Kelas</a>
      <a href="{{ route('dashboard.index') }}" class="btn btn-secondary ml-2">Kembali ke Dashboard</a>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama Kelas</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($kelas as $key => $kls)
        <tr>
          <th scope="row">{{ ($kelas->currentPage() - 1) * $kelas->perPage() + $key + 1 }}</th>
          <td>{{ $kls->nama_kelas }}</td>
          <td>
            <a href="{{ route('kelas.show', $kls->id) }}" class="btn btn-info">Detail</a>
            <a href="{{ route('kelas.edit', $kls->id) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('kelas.destroy', $kls->id) }}" method="POST" style="display: inline-block;" class="delete-form">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger delete-btn" data-name="{{ $kls->nama_kelas }}">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="3">Tidak ada kelas yang ditemukan.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    
    <!-- Tambahkan pagination links disini -->
    <div class="d-flex justify-content-center">
      {{ $kelas->links() }}
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // SweetAlert untuk Hapus dari daftar
    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', function(event) {
        event.preventDefault();

        Swal.fire({
          title: 'Hapus Kelas',
          text: `Anda yakin ingin menghapus kelas "${this.dataset.name}"?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            this.parentElement.submit();
          }
        });
      });
    });

    // Refresh button functionality
    const refreshBtn = document.getElementById('refresh-btn');
    refreshBtn.addEventListener('click', function () {
      window.location.href = "{{ route('kelas.index') }}";
    });

    // Display success message after deletion
    @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: '{{ session('success') }}',
      confirmButtonText: 'OK'
    });
    @endif
  });
</script>
@endpush
