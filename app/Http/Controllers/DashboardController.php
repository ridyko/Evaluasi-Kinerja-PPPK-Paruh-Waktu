<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiBulanan;
use App\Models\Pegawai;
use App\Models\PejabatPenilai;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isPegawai = $user->role === 'pegawai';
        $pegawaiId = $user->pegawai_id;

        $totalPegawai = Pegawai::where('is_active', true)->count();
        $totalPenilai = PejabatPenilai::where('is_active', true)->count();
        
        if ($isPegawai) {
            $totalEvaluasi = EvaluasiBulanan::where('pegawai_id', $pegawaiId)->count();
            $evaluasiBulanIni = EvaluasiBulanan::where('pegawai_id', $pegawaiId)
                ->where('bulan', now()->month)
                ->where('tahun', now()->year)
                ->count();
        } else {
            $totalEvaluasi = EvaluasiBulanan::count();
            $evaluasiBulanIni = EvaluasiBulanan::where('bulan', now()->month)
                ->where('tahun', now()->year)
                ->count();
        }

        // Recent evaluations
        $query = EvaluasiBulanan::with(['pegawai.jabatan', 'pejabatPenilai'])
            ->orderBy('created_at', 'desc');
            
        if ($isPegawai) {
            $query->where('pegawai_id', $pegawaiId);
        }

        $recentEvaluasi = $query->limit(10)->get();

        // Monthly statistics for chart
        $monthlyStats = [];
        for ($m = 1; $m <= 12; $m++) {
            $statQuery = EvaluasiBulanan::where('bulan', $m)->where('tahun', now()->year);
            if ($isPegawai) {
                $statQuery->where('pegawai_id', $pegawaiId);
            }
            $monthlyStats[] = $statQuery->count();
        }

        // Recent WhatsApp notifications
        $recentWaLogs = \App\Models\WaLog::with('evaluasi.pegawai')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalPegawai',
            'totalPenilai',
            'totalEvaluasi',
            'evaluasiBulanIni',
            'recentEvaluasi',
            'monthlyStats',
            'recentWaLogs'
        ));
    }
}
