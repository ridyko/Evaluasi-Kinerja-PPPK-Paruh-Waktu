<?php

use App\Http\Controllers\AkunPegawaiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\IndikatorKinerjaController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PejabatPenilaiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ==========================================
// INSTALLER
// ==========================================
Route::get('/install', [InstallController::class, 'index'])->name('install.index');
Route::post('/install', [InstallController::class, 'setup'])->name('install.setup');

Route::middleware([\App\Http\Middleware\CheckInstalled::class])->group(function() {


// ==========================================
// AUTH ROUTES
// ==========================================
Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// AUTHENTICATED ROUTES
// ==========================================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // ADMIN ONLY ROUTES
    // ==========================================
    Route::middleware('role:admin')->group(function () {
        // Jabatan
        Route::resource('jabatan', JabatanController::class)->except(['show']);

        // Pegawai
        Route::resource('pegawai', PegawaiController::class)->except(['show']);

        // Pejabat Penilai
        Route::resource('penilai', PejabatPenilaiController::class)->except(['show']);

        // Indikator Kinerja
        Route::resource('indikator', IndikatorKinerjaController::class)->except(['show']);

        // Akun Pegawai
        Route::get('/akun', [AkunPegawaiController::class, 'index'])->name('akun.index');
        Route::get('/akun/create', [AkunPegawaiController::class, 'create'])->name('akun.create');
        Route::post('/akun', [AkunPegawaiController::class, 'store'])->name('akun.store');
        Route::post('/akun/create-all', [AkunPegawaiController::class, 'createAll'])->name('akun.createAll');
        Route::get('/akun/{akun}/edit', [AkunPegawaiController::class, 'edit'])->name('akun.edit');
        Route::put('/akun/{akun}', [AkunPegawaiController::class, 'update'])->name('akun.update');
        Route::post('/akun/{akun}/reset-password', [AkunPegawaiController::class, 'resetPassword'])->name('akun.resetPassword');
        Route::delete('/akun/{akun}', [AkunPegawaiController::class, 'destroy'])->name('akun.destroy');

        // System Settings
        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

        // WhatsApp Service Management
        Route::get('/wa/status', [\App\Http\Controllers\WhatsAppController::class, 'status'])->name('wa.status');
        Route::post('/wa/start', [\App\Http\Controllers\WhatsAppController::class, 'start'])->name('wa.start');
        Route::post('/wa/stop', [\App\Http\Controllers\WhatsAppController::class, 'stop'])->name('wa.stop');
        Route::post('/wa/install', [\App\Http\Controllers\WhatsAppController::class, 'install'])->name('wa.install');
        Route::get('/wa/qr', [\App\Http\Controllers\WhatsAppController::class, 'qr'])->name('wa.qr');
    });

    // ==========================================
    // ADMIN & PENILAI ROUTES (MANAGEMENT)
    // ==========================================
    Route::middleware('role:admin,penilai')->group(function () {
        Route::get('/evaluasi/create', [EvaluasiController::class, 'create'])->name('evaluasi.create');
        Route::post('/evaluasi', [EvaluasiController::class, 'store'])->name('evaluasi.store');
        Route::get('/evaluasi/{evaluasi}/edit', [EvaluasiController::class, 'edit'])->name('evaluasi.edit');
        Route::put('/evaluasi/{evaluasi}', [EvaluasiController::class, 'update'])->name('evaluasi.update');
        Route::post('/evaluasi/{evaluasi}/finalize', [EvaluasiController::class, 'finalize'])->name('evaluasi.finalize');
        Route::delete('/evaluasi/{evaluasi}', [EvaluasiController::class, 'destroy'])->name('evaluasi.destroy');
    });

    // ==========================================
    // ALL AUTHENTICATED USERS (READ & EXPORT)
    // ==========================================
    Route::middleware('role:admin,penilai,pegawai')->group(function () {
        Route::get('/evaluasi', [EvaluasiController::class, 'index'])->name('evaluasi.index');
        Route::get('/evaluasi/{evaluasi}/show', [EvaluasiController::class, 'show'])->name('evaluasi.show');
        Route::get('/evaluasi/{evaluasi}/pdf', [EvaluasiController::class, 'exportPdf'])->name('evaluasi.pdf');
        Route::get('/evaluasi/{evaluasi}/excel', [EvaluasiController::class, 'exportExcel'])->name('evaluasi.excel');
    });

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
});
