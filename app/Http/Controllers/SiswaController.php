<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $siswas = Siswa::when($search, function ($query, $search) {
                        return $query->where('nama', 'like', "%{$search}%");
                    })
                    ->paginate(5); // Ganti 10 dengan jumlah item per halaman yang diinginkan

        return view('siswa.index', compact('siswas'));
    }
    public function create()
    {
        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'alamat' => 'required|string|max:255',
            'nomor_telp' => 'required|numeric',
        ]);

        // Periksa apakah siswa sudah ada di kelas yang sama
        $exists = Siswa::where('nama', $request->nama)
                    ->where('kelas_id', $request->kelas_id)
                    ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['Siswa dengan nama tersebut sudah ada di kelas yang sama']);
        }

        Siswa::create($request->all());

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan');
    }

    public function show(Siswa $siswa)
    {
        return view('siswa.show', compact('siswa'));
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'alamat' => 'required|string|max:255',
            'nomor_telp' => 'required|numeric',
        ]);

        $siswa = Siswa::findOrFail($id);

        // Periksa apakah siswa dengan nama dan kelas yang sama sudah ada, kecuali diri sendiri
        $exists = Siswa::where('nama', $request->nama)
                    ->where('kelas_id', $request->kelas_id)
                    ->where('id', '!=', $id)
                    ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['Siswa dengan nama tersebut sudah ada di kelas yang sama']);
        }

        $siswa->update($request->all());

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $siswas = Siswa::where('nama', 'like', '%' . $query . '%')
                ->orWhereHas('kelas', function ($q) use ($query) {
                    $q->where('nama_kelas', 'like', '%' . $query . '%');
                })
                ->get();

        return view('siswa.index', compact('siswas'));
    }

    // Metode untuk menangani pemeriksaan AJAX untuk siswa yang sudah ada
    public function check(Request $request)
    {
        $nama = $request->input('nama');
        $kelas_id = $request->input('kelas_id');

        $sameNameAndClass = Siswa::where('nama', $nama)->where('kelas_id', $kelas_id)->exists();
        $sameNameDifferentClass = Siswa::where('nama', $nama)->where('kelas_id', '!=', $kelas_id)->exists();
        $sameClassDifferentName = Siswa::where('nama', '!=', $nama)->where('kelas_id', $kelas_id)->exists();

        return response()->json([
            'sameNameAndClass' => $sameNameAndClass,
            'sameNameDifferentClass' => $sameNameDifferentClass,
            'sameClassDifferentName' => $sameClassDifferentName
        ]);
    }
}
