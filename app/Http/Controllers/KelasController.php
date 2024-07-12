<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $kelas = Kelas::when($search, function ($query, $search) {
            return $query->where('nama_kelas', 'like', "%{$search}%");
        })->paginate(5);

        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
        ]);

        Kelas::create($request->all());
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function show(Kelas $kelas)
    {
        return view('kelas.show', compact('kelas'));
    }

    public function edit(Kelas $kelas)
    {
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,'.$kelas->id,
        ]);

        $kelas->update($request->all());
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus');
    }

    public function delete(Kelas $kelas)
    {
        return view('kelas.delete', compact('kelas'));
    }

    public function check(Request $request)
    {
        $namaKelas = $request->input('nama_kelas');
        $exists = Kelas::where('nama_kelas', $namaKelas)->exists();
        return response()->json(['exists' => $exists]);
    }
}

