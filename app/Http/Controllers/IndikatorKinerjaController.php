<?php

namespace App\Http\Controllers;

use App\Models\IndikatorKinerja;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class IndikatorKinerjaController extends Controller
{
    public function index(Request $request)
    {
        $jabatanList = Jabatan::where('is_active', true)->get();
        $selectedJabatan = $request->get('jabatan_id');

        $query = IndikatorKinerja::with('jabatan')->orderBy('jabatan_id')->orderBy('nomor_urut');

        if ($selectedJabatan) {
            $query->where('jabatan_id', $selectedJabatan);
        }

        $indikator = $query->get();

        return view('indikator.index', compact('indikator', 'jabatanList', 'selectedJabatan'));
    }

    public function create()
    {
        $jabatan = Jabatan::where('is_active', true)->get();
        return view('indikator.create', compact('jabatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jabatan_id' => 'required|exists:jabatan,id',
            'nomor_urut' => 'required|integer|min:1',
            'deskripsi' => 'required|string',
            'target_tahunan' => 'required|string|max:100',
        ]);

        IndikatorKinerja::create($request->only(['jabatan_id', 'nomor_urut', 'deskripsi', 'target_tahunan']));

        return redirect()->route('indikator.index', ['jabatan_id' => $request->jabatan_id])
            ->with('success', 'Indikator kinerja berhasil ditambahkan.');
    }

    public function edit(IndikatorKinerja $indikator)
    {
        $jabatan = Jabatan::where('is_active', true)->get();
        return view('indikator.edit', compact('indikator', 'jabatan'));
    }

    public function update(Request $request, IndikatorKinerja $indikator)
    {
        $request->validate([
            'jabatan_id' => 'required|exists:jabatan,id',
            'nomor_urut' => 'required|integer|min:1',
            'deskripsi' => 'required|string',
            'target_tahunan' => 'required|string|max:100',
        ]);

        $indikator->update($request->only(['jabatan_id', 'nomor_urut', 'deskripsi', 'target_tahunan']));

        return redirect()->route('indikator.index', ['jabatan_id' => $request->jabatan_id])
            ->with('success', 'Indikator kinerja berhasil diperbarui.');
    }

    public function destroy(IndikatorKinerja $indikator)
    {
        $jabatanId = $indikator->jabatan_id;
        $indikator->delete();

        return redirect()->route('indikator.index', ['jabatan_id' => $jabatanId])
            ->with('success', 'Indikator kinerja berhasil dihapus.');
    }
}
