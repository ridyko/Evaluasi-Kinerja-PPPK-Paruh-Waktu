<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiBulanan;
use App\Models\EvaluasiHasilKerja;
use App\Models\EvaluasiPerilaku;
use App\Models\IndikatorKinerja;
use App\Models\Pegawai;
use App\Models\PejabatPenilai;
use App\Exports\EvaluasiExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isPegawai = $user->role === 'pegawai';
        $pegawaiId = $user->pegawai_id;

        $query = EvaluasiBulanan::with(['pegawai.jabatan', 'pejabatPenilai']);

        if ($isPegawai) {
            $query->where('pegawai_id', $pegawaiId);
        } else {
            if ($request->filled('pegawai_id')) {
                $query->where('pegawai_id', $request->pegawai_id);
            }
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $evaluasi = $query->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        $pegawaiList = Pegawai::where('is_active', true)->orderBy('nama')->get();

        return view('evaluasi.index', compact('evaluasi', 'pegawaiList', 'isPegawai'));
    }

    public function create()
    {
        $pegawai = Pegawai::with('jabatan')->where('is_active', true)->orderBy('nama')->get();
        $penilai = PejabatPenilai::where('is_active', true)->orderBy('nama')->get();

        return view('evaluasi.create', compact('pegawai', 'penilai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'pejabat_penilai_id' => 'required|exists:pejabat_penilai,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2099',
        ]);

        // Check if already exists
        $exists = EvaluasiBulanan::where('pegawai_id', $request->pegawai_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Evaluasi untuk pegawai ini pada bulan dan tahun tersebut sudah ada.')
                ->withInput();
        }

        $evaluasi = EvaluasiBulanan::create([
            'pegawai_id' => $request->pegawai_id,
            'pejabat_penilai_id' => $request->pejabat_penilai_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'status' => 'draft',
        ]);

        // Auto-create hasil kerja from indikator jabatan
        $pegawai = Pegawai::find($request->pegawai_id);
        $indikators = IndikatorKinerja::where('jabatan_id', $pegawai->jabatan_id)
            ->where('is_active', true)
            ->orderBy('nomor_urut')
            ->get();

        foreach ($indikators as $indikator) {
            EvaluasiHasilKerja::create([
                'evaluasi_bulanan_id' => $evaluasi->id,
                'indikator_kinerja_id' => $indikator->id,
                'target_bulan' => 1,
                'realisasi' => 0,
                'capaian' => 0,
            ]);
        }

        // Auto-create perilaku (7 aspek BerAKHLAK)
        foreach (EvaluasiPerilaku::daftarAspek() as $aspek) {
            EvaluasiPerilaku::create([
                'evaluasi_bulanan_id' => $evaluasi->id,
                'aspek_perilaku' => $aspek,
                'pengkategorian' => 'Sesuai Ekspektasi',
                'nilai' => 2,
            ]);
        }

        return redirect()->route('evaluasi.edit', $evaluasi->id)
            ->with('success', 'Evaluasi berhasil dibuat. Silakan isi penilaian.');
    }

    public function edit(EvaluasiBulanan $evaluasi)
    {
        $evaluasi->load([
            'pegawai.jabatan',
            'pejabatPenilai',
            'hasilKerja.indikatorKinerja',
            'perilaku',
        ]);

        return view('evaluasi.edit', compact('evaluasi'));
    }

    public function update(Request $request, EvaluasiBulanan $evaluasi)
    {
        $request->validate([
            'hasil_kerja' => 'required|array',
            'hasil_kerja.*.target_bulan' => 'required|integer|min:0',
            'hasil_kerja.*.realisasi' => 'required|numeric|min:0',
            'perilaku' => 'required|array',
            'perilaku.*.pengkategorian' => 'required|in:Dibawah Ekspektasi,Sesuai Ekspektasi,Diatas Ekspektasi',
            'tanggal_evaluasi' => 'nullable|date',
        ]);

        // Update hasil kerja
        foreach ($request->hasil_kerja as $id => $data) {
            $hasilKerja = EvaluasiHasilKerja::find($id);
            if ($hasilKerja && $hasilKerja->evaluasi_bulanan_id === $evaluasi->id) {
                $hasilKerja->target_bulan = $data['target_bulan'];
                $hasilKerja->realisasi = $data['realisasi'];
                $hasilKerja->capaian = $hasilKerja->hitungCapaian();
                $hasilKerja->save();
            }
        }

        // Update perilaku
        foreach ($request->perilaku as $id => $data) {
            $perilaku = EvaluasiPerilaku::find($id);
            if ($perilaku && $perilaku->evaluasi_bulanan_id === $evaluasi->id) {
                $perilaku->pengkategorian = $data['pengkategorian'];
                $perilaku->nilai = EvaluasiPerilaku::nilaiDariKategori($data['pengkategorian']);
                $perilaku->save();
            }
        }

        // Recalculate totals
        $evaluasi->refresh();
        $evaluasi->capaian_hasil_kerja = $evaluasi->hitungCapaianHasilKerja();
        $evaluasi->capaian_perilaku_kerja = $evaluasi->hitungCapaianPerilaku();
        $evaluasi->tanggal_evaluasi = $request->tanggal_evaluasi;
        $evaluasi->save();

        return redirect()->route('evaluasi.edit', $evaluasi->id)
            ->with('success', 'Evaluasi berhasil diperbarui.');
    }

    public function finalize(EvaluasiBulanan $evaluasi)
    {
        $evaluasi->status = 'final';
        $evaluasi->save();

        // Send WhatsApp Notification
        $pegawai = $evaluasi->pegawai;
        if ($pegawai && $pegawai->telepon) {
            $appName = get_setting('app_name');
            $bulanName = $evaluasi->nama_bulan;
            $tahun = $evaluasi->tahun;
            
            $message = "Halo *{$pegawai->nama}*,\n\nEvaluasi Kinerja Anda untuk bulan *{$bulanName} {$tahun}* telah selesai dinilai dan di-*FINALISASI* oleh Pejabat Penilai.\n\nSilakan cek detailnya di aplikasi {$appName}.\n\nTerima kasih.";
            
            \App\Services\WhatsAppService::sendMessage($pegawai->telepon, $message);
        }

        return redirect()->route('evaluasi.index')
            ->with('success', 'Evaluasi berhasil difinalisasi dan notifikasi WhatsApp telah dikirim.');
    }

    public function show(EvaluasiBulanan $evaluasi)
    {
        $user = auth()->user();
        if ($user->role === 'pegawai' && $evaluasi->pegawai_id !== $user->pegawai_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $evaluasi->load([
            'pegawai.jabatan',
            'pejabatPenilai',
            'hasilKerja.indikatorKinerja',
            'perilaku',
        ]);

        return view('evaluasi.show', compact('evaluasi'));
    }

    public function destroy(EvaluasiBulanan $evaluasi)
    {
        $evaluasi->delete();

        return redirect()->route('evaluasi.index')
            ->with('success', 'Evaluasi berhasil dihapus.');
    }

    public function exportPdf(EvaluasiBulanan $evaluasi)
    {
        $user = auth()->user();
        if ($user->role === 'pegawai' && $evaluasi->pegawai_id !== $user->pegawai_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $evaluasi->load([
            'pegawai.jabatan',
            'pejabatPenilai',
            'hasilKerja.indikatorKinerja',
            'perilaku',
        ]);

        $pdf = Pdf::loadView('evaluasi.pdf', compact('evaluasi'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Evaluasi_Kinerja_' . $evaluasi->pegawai->nama . '_' . $evaluasi->nama_bulan . '_' . $evaluasi->tahun . '.pdf';

        return $pdf->download($filename);
    }

    public function exportExcel(EvaluasiBulanan $evaluasi)
    {
        $user = auth()->user();
        if ($user->role === 'pegawai' && $evaluasi->pegawai_id !== $user->pegawai_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $evaluasi->load([
            'pegawai.jabatan',
            'pejabatPenilai',
            'hasilKerja.indikatorKinerja',
            'perilaku',
        ]);

        $export = new EvaluasiExport($evaluasi);
        $path = $export->generate();
        $filename = $export->getFilename();

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }
}
