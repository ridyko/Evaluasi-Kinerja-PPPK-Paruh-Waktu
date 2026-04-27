<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\PejabatPenilai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunPegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with(['jabatan', 'user'])->orderBy('nama')->get();
        $penilai = PejabatPenilai::with('user')->orderBy('nama')->get();
        return view('akun.index', compact('pegawai', 'penilai'));
    }

    public function create()
    {
        // Pegawai yang belum punya akun
        $pegawai = Pegawai::whereDoesntHave('user')
            ->where('is_active', true)
            ->orderBy('nama')
            ->get();

        return view('akun.create', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'email' => 'required|email|unique:users,email',
        ]);

        $pegawai = Pegawai::findOrFail($request->pegawai_id);

        // Password default = NI PPPK
        User::create([
            'name' => $pegawai->nama,
            'email' => $request->email,
            'password' => Hash::make($pegawai->ni_pppk),
            'role' => 'pegawai',
            'pegawai_id' => $pegawai->id,
        ]);

        return redirect()->route('akun.index')
            ->with('success', 'Akun pegawai "' . $pegawai->nama . '" berhasil dibuat. Password default: NI PPPK.');
    }

    /**
     * Buat akun untuk semua pegawai yang belum punya akun
     */
    public function createAll(Request $request)
    {
        $request->validate([
            'accounts' => 'required|array|min:1',
            'accounts.*.pegawai_id' => 'required|exists:pegawai,id',
            'accounts.*.email' => 'required|email',
        ]);

        $created = 0;
        $errors = [];

        foreach ($request->accounts as $account) {
            $pegawai = Pegawai::find($account['pegawai_id']);
            if (!$pegawai) continue;

            // Skip jika pegawai sudah punya akun
            if (User::where('pegawai_id', $pegawai->id)->exists()) {
                continue;
            }

            // Skip jika email sudah digunakan
            if (User::where('email', $account['email'])->exists()) {
                $errors[] = 'Email "' . $account['email'] . '" sudah digunakan.';
                continue;
            }

            User::create([
                'name' => $pegawai->nama,
                'email' => $account['email'],
                'password' => Hash::make($pegawai->ni_pppk),
                'role' => 'pegawai',
                'pegawai_id' => $pegawai->id,
            ]);

            $created++;
        }

        $message = $created . ' akun pegawai berhasil dibuat. Password default: NI PPPK masing-masing.';
        if (!empty($errors)) {
            $message .= ' (' . implode(' ', $errors) . ')';
        }

        return redirect()->route('akun.index')
            ->with('success', $message);
    }

    public function edit(User $akun)
    {
        $akun->load(['pegawai', 'pejabatPenilai']);
        return view('akun.edit', compact('akun'));
    }

    public function update(Request $request, User $akun)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $akun->id,
            'name' => 'required|string|max:255',
        ]);

        $akun->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('akun.index')
            ->with('success', 'Akun "' . $akun->name . '" berhasil diperbarui.');
    }

    /**
     * Reset password ke NI PPPK (pegawai) atau NIP (penilai)
     */
    public function resetPassword(User $akun)
    {
        if ($akun->pegawai) {
            $akun->update([
                'password' => Hash::make($akun->pegawai->ni_pppk),
            ]);
            return redirect()->route('akun.index')
                ->with('success', 'Password "' . $akun->name . '" berhasil direset ke NI PPPK (' . $akun->pegawai->ni_pppk . ').');
        }

        if ($akun->pejabatPenilai) {
            $akun->update([
                'password' => Hash::make($akun->pejabatPenilai->nip),
            ]);
            return redirect()->route('akun.index')
                ->with('success', 'Password "' . $akun->name . '" berhasil direset ke NIP (' . $akun->pejabatPenilai->nip . ').');
        }

        return redirect()->route('akun.index')
            ->with('error', 'Akun ini tidak terkait dengan pegawai atau penilai.');
    }

    public function destroy(User $akun)
    {
        if ($akun->isAdmin()) {
            return redirect()->route('akun.index')
                ->with('error', 'Tidak bisa menghapus akun admin.');
        }

        $name = $akun->name;
        $akun->delete();

        return redirect()->route('akun.index')
            ->with('success', 'Akun "' . $name . '" berhasil dihapus.');
    }
}
