<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Users\DashboardController as UsersDashboardController;
use App\Http\Controllers\Admin\AdministrasiController as AdministrasiController;
use App\Http\Controllers\Admin\SuratPengantarController as SuratPengantarController;
use App\Http\Controllers\Admin\MasterWargaController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\PengeluaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Users\LaporanController as UsersLaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\WargaController;
use App\Http\Controllers\Admin\LaporanPulasaraController;
use App\Http\Controllers\Admin\LaporanSampahKeamananController;
use App\Http\Controllers\Admin\LaporanDansosController;
use App\Http\Controllers\Admin\LaporanUmumController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

/*
|--------------------------------------------------------------------------
| Admin Routes (role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('masterwarga', MasterWargaController::class);
   // Route AJAX untuk pencarian warga
    Route::get('/ajax/search-warga', [AjaxController::class, 'searchWarga'])->name('ajax.search-warga');
    Route::get('/ajax/warga', [AjaxController::class, 'warga'])->name('ajax.warga');

    Route::resource('suratpengantar', SuratPengantarController::class);
    Route::get('suratpengantar/{suratpengantar}/cetak', [SuratPengantarController::class, 'cetak'])->name('suratpengantar.cetak');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');   
    Route::get('/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/{id}/edit', [PembayaranController::class, 'edit'])->name('pembayaran.edit');   
    route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::get('/pengeluaran/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::get('/pengeluaran/{id}/edit', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit'); 
    Route::resource('pembayaran', PembayaranController::class);
    Route::resource('pengeluaran', PengeluaranController::class);
    Route::resource('users', UserController::class)->except(['show']);;
    Route::resource('user', UserController::class);

    // Laporan Routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Laporan Pembayaran
    Route::get('/laporan/pembayaran', [LaporanController::class, 'pembayaran'])->name('laporan.pembayaran');
    Route::get('/laporan/pembayaran/export/pdf', [LaporanController::class, 'pembayaranExportPDF'])->name('laporan.pembayaran.pdf');

    // Laporan Pengeluaran
    Route::get('/laporan/pengeluaran', [LaporanController::class, 'pengeluaran'])->name('laporan.pengeluaran');
    Route::get('/laporan/pengeluaran/export/pdf', [LaporanController::class, 'pengeluaranExportPDF'])->name('laporan.pengeluaran.pdf');
    Route::get('/laporan/pulasara', [LaporanPulasaraController::class, 'index'])->name('laporan.pulasara');
    Route::get('/laporan/pulasara/pdf', [LaporanPulasaraController::class, 'exportPdf'])->name('laporan.pulasara_pdf');
    Route::get('/laporan/sampah-keamanan', [LaporanSampahKeamananController::class, 'index'])->name('laporan.sampahkeamanan');
    Route::get('/laporan/sampah-keamanan/pdf', [LaporanSampahKeamananController::class, 'exportPdf'])->name('laporan.sampahkeamanan_pdf');
    Route::get('/laporan/dansos', [LaporanDansosController::class, 'index'])->name('laporan.dansos');
    Route::get('/laporan/dansos/pdf', [LaporanDansosController::class, 'exportPdf'])->name('laporan.dansos_pdf');
    Route::get('/laporan/umum', [LaporanUmumController::class, 'index'])->name('laporan.umum');
    Route::get('/laporan/umum/pdf', [LaporanUmumController::class, 'exportPdf'])->name('laporan.umum_pdf');
    });

/*
|--------------------------------------------------------------------------
| Viewer/User Routes (role: viewer)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:viewer'])->prefix('users')->name('users.')->group(function () {
    Route::get('/dashboard', [UsersDashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [UsersLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [UsersLaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan', [UsersLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf', [UsersLaporanController::class, 'exportPdf'])->name('laporan.exportPdf');
});

