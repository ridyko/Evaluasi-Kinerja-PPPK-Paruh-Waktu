<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with('jabatan')->orderBy('nama')->get();
        return view('pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        $jabatan = Jabatan::where('is_active', true)->get();
        return view('pegawai.create', compact('jabatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'ni_pppk' => 'required|string|max:50|unique:pegawai,ni_pppk',
            'pangkat_gol' => 'nullable|string|max:50',
            'jabatan_id' => 'required|exists:jabatan,id',
            'unit_kerja' => 'required|string|max:255',
        ]);

        Pegawai::create($request->only(['nama', 'ni_pppk', 'pangkat_gol', 'jabatan_id', 'unit_kerja']));

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        $jabatan = Jabatan::where('is_active', true)->get();
        return view('pegawai.edit', compact('pegawai', 'jabatan'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'ni_pppk' => 'required|string|max:50|unique:pegawai,ni_pppk,' . $pegawai->id,
            'pangkat_gol' => 'nullable|string|max:50',
            'jabatan_id' => 'required|exists:jabatan,id',
            'unit_kerja' => 'required|string|max:255',
        ]);

        $pegawai->update($request->only(['nama', 'ni_pppk', 'pangkat_gol', 'jabatan_id', 'unit_kerja']));

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->evaluasiBulanan()->exists()) {
            return redirect()->route('pegawai.index')
                ->with('error', 'Pegawai tidak bisa dihapus karena masih memiliki data evaluasi.');
        }

        $pegawai->delete();

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }
}
