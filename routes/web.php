<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\GuruController;
use App\Http\Controllers\Master\KelasController;
use App\Http\Controllers\Master\SiswaController;
use App\Http\Controllers\Master\TahunAjaranController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Absensi\SesiAbsensiController;
use App\Http\Controllers\Absensi\QrAbsensiController;
use App\Http\Controllers\Absensi\AbsensiSiswaController;
use App\Http\Controllers\Absensi\RekapAbsensiController;

Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [LoginController::class, 'showLoginForm']
    )->name('login');

    Route::post(
        '/login',
        [LoginController::class, 'login']
    )->name('login.process');

});

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/',
        [DashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Operator
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:operator')->group(function () {

        Route::resource(
            'tahun-ajaran',
            TahunAjaranController::class
        )->except('show');

        Route::resource(
            'kelas',
            KelasController::class
        )->except('show');

        Route::resource(
            'guru',
            GuruController::class
        )->except('show');

        Route::resource(
            'siswa',
            SiswaController::class
        )->except('show');

    });

    /*
    |--------------------------------------------------------------------------
    | Siswa
    |--------------------------------------------------------------------------
    */
Route::middleware('role:siswa')
    ->prefix('absensi')
    ->name('absensi.')
    ->group(function () {

        Route::get(
            '/saya',
            [AbsensiSiswaController::class, 'index']
        )->name('siswa.index');

        Route::post(
            '/scan',
            [AbsensiSiswaController::class, 'scan']
        )->name('siswa.scan');

    });

    /*
    |--------------------------------------------------------------------------
    | Absensi
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:operator,guru')
    ->prefix('absensi')
    ->name('absensi.')
    ->group(function () {

        Route::get(
            '/sesi',
            [SesiAbsensiController::class, 'index']
        )->name('sesi.index');

        Route::get(
            '/sesi/buka',
            [SesiAbsensiController::class, 'create']
        )->name('sesi.create');

        Route::post(
            '/sesi',
            [SesiAbsensiController::class, 'store']
        )->name('sesi.store');

        Route::get(
            '/sesi/{sesi}',
            [SesiAbsensiController::class, 'show']
        )->name('sesi.show');

        Route::get(
            '/sesi/{sesi}/qr',
            [QrAbsensiController::class, 'show']
        )->name('sesi.qr');

        Route::patch(
            '/sesi/{sesi}/siswa/{siswa}/status',
            [SesiAbsensiController::class, 'updateStatus']
        )->name('sesi.status.update');

        Route::patch(
            '/sesi/{sesi}/tutup',
            [SesiAbsensiController::class, 'tutup']
        )->name('sesi.tutup');

        Route::get(
            '/rekap',
            [RekapAbsensiController::class, 'index']
        )->name('rekap.index');

        Route::get(
            '/rekap/export',
            [RekapAbsensiController::class, 'export']
        )->name('rekap.export');

    });

});

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/logout',
        [LoginController::class, 'logout']
    )->name('logout');