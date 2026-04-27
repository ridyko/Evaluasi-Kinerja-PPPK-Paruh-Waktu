<?php

namespace App\Http\Controllers;

use App\Models\PejabatPenilai;
use Illuminate\Http\Request;

class PejabatPenilaiController extends Controller
{
    public function index()
    {
        $penilai = PejabatPenilai::orderBy('nama')->get();
        return view('penilai.index', compact('penilai'));
    }

    public function create()
    {
        return view('penilai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:pejabat_penilai,nip',
            'pangkat_gol' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:255',
            'unit_kerja' => 'required|string|max:255',
        ]);

        PejabatPenilai::create($request->only(['nama', 'nip', 'pangkat_gol', 'jabatan', 'unit_kerja']));

        return redirect()->route('penilai.index')
            ->with('success', 'Data pejabat penilai berhasil ditambahkan.');
    }

    public function edit(PejabatPenilai $penilai)
    {
        return view('penilai.edit', compact('penilai'));
    }

    public function update(Request $request, PejabatPenilai $penilai)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:pejabat_penilai,nip,' . $penilai->id,
            'pangkat_gol' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:255',
            'unit_kerja' => 'required|string|max:255',
        ]);

        $penilai->update($request->only(['nama', 'nip', 'pangkat_gol', 'jabatan', 'unit_kerja']));

        return redirect()->route('penilai.index')
            ->with('success', 'Data pejabat penilai berhasil diperbarui.');
    }

    public function destroy(PejabatPenilai $penilai)
    {
        if ($penilai->evaluasiBulanan()->exists()) {
            return redirect()->route('penilai.index')
                ->with('error', 'Pejabat penilai tidak bisa dihapus karena masih memiliki data evaluasi.');
        }

        $penilai->delete();

        return redirect()->route('penilai.index')
            ->with('success', 'Data pejabat penilai berhasil dihapus.');
    }
}
