<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\GuruController;
use App\Http\Controllers\Master\KelasController;
use App\Http\Controllers\Master\SiswaController;
use App\Http\Controllers\Master\TahunAjaranController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Absensi\SesiAbsensiController;
use App\Http\Controllers\Absensi\AbsensiSiswaController;
use App\Http\Controllers\Absensi\RekapAbsensiController;
use App\Http\Controllers\Siswa\QrSiswaController;
use App\Http\Controllers\Absensi\ScanAbsensiController;

use App\Http\Controllers\CBT\BankSoalController;
use App\Http\Controllers\CBT\UjianController;
use App\Http\Controllers\CBT\UjianSiswaController;
use App\Http\Controllers\CBT\PengerjaanUjianController;

use App\Http\Controllers\CBT\PelanggaranUjianController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [LoginController::class, 'showLoginForm']
    )->name('login');

    Route::post(
        '/login',
        [LoginController::class, 'login']
    )->name('login.process');

    /*
    |--------------------------------------------------------------------------
    | Lupa Password
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/lupa-password',
        [
            ForgotPasswordController::class,
            'showLinkRequestForm',
        ]
    )->name('password.request');


    Route::post(
        '/lupa-password',
        [
            ForgotPasswordController::class,
            'sendResetLinkEmail',
        ]
    )->name('password.email');


    /*
    |--------------------------------------------------------------------------
    | Reset Password
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reset-password/{token}',
        [
            ResetPasswordController::class,
            'showResetForm',
        ]
    )->name('password.reset');


    Route::post(
        '/reset-password',
        [
            ResetPasswordController::class,
            'reset',
        ]
    )->name('password.update');


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

    Route::get(
        '/profil',
        [ProfileController::class, 'show']
    )->name('profile.show');

    Route::get(
        '/profil/edit',
        [ProfileController::class, 'edit']
    )->name('profile.edit');


    Route::put(
        '/profil',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::get(
        '/profil/password',
        [ProfileController::class, 'editPassword']
    )->name('profile.password.edit');


    Route::put(
        '/profil/password',
        [ProfileController::class, 'updatePassword']
    )->name('profile.password.update');


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

        Route::get('kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::get('kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::get('kelas/{kelas}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('kelas/{kelas}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');

        Route::get(
            '/guru/template-import',
            [GuruController::class, 'downloadTemplate']
        )->name('guru.template-import');

        Route::post(
            '/guru/import',
            [GuruController::class, 'import']
        )->name('guru.import');

        Route::resource(
            'guru',
            GuruController::class
        )->except('show');

        Route::get(
            '/siswa/template-import',
            [SiswaController::class, 'downloadTemplate']
        )->name('siswa.template-import');

        Route::post(
            '/siswa/import',
            [SiswaController::class, 'import']
        )->name('siswa.import');

        Route::resource(
            'siswa',
            SiswaController::class
        )->except('show');

        Route::get(
            '/siswa/{siswa}/qr',
            [QrSiswaController::class, 'show']
        )->name('siswa.qr.show');

        Route::post(
            '/siswa/{siswa}/qr/regenerate',
            [QrSiswaController::class, 'regenerate']
        )->name('siswa.qr.regenerate');

        /*
|--------------------------------------------------------------------------
| Rekap Hasil CBT
|--------------------------------------------------------------------------
*/

        Route::get(
            '/cbt/rekap',
            [UjianController::class, 'rekap']
        )->name('cbt.rekap.index');

        Route::get(
            '/rekap/arsip',
            [UjianController::class, 'rekapArsip']
        )->name('cbt.rekap.arsip');

        Route::get(
            '/cbt/rekap/{ujian}',
            [UjianController::class, 'rekapShow']
        )->name('cbt.rekap.show');

        Route::get(
            '/cbt/rekap/{ujian}/export',
            [UjianController::class, 'exportRekap']
        )->name('cbt.rekap.export');

        Route::get(
            '/cbt/rekap/{ujian}/peserta/{pengerjaan}',
            [UjianController::class, 'rekapPeserta']
        )->name('cbt.rekap.peserta');

        /*
        |--------------------------------------------------------------------------
        | Buka Blokir Ujian
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/cbt/buka-blokir',
            [UjianController::class, 'blokirIndex']
        )->name('cbt.blokir.index');

        Route::patch(
            '/cbt/buka-blokir/{pengerjaan}',
            [UjianController::class, 'bukaBlokir']
        )->name('cbt.blokir.buka');


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

        /*
        |--------------------------------------------------------------------------
        | Sesi Absensi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/sesi',
            [SesiAbsensiController::class, 'index']
        )->name('sesi.index');


        Route::get(
            '/sesi/buka',
            [SesiAbsensiController::class, 'create']
        )->name('sesi.create');


        /*
        |--------------------------------------------------------------------------
        | Arsip Sesi Absensi
        |--------------------------------------------------------------------------
        |
        | Harus diletakkan sebelum /sesi/{sesi}
        | agar "arsip" tidak dianggap sebagai ID sesi.
        |
        */

        Route::get(
            '/sesi/arsip',
            [SesiAbsensiController::class, 'arsip']
        )->name('sesi.arsip');


        Route::post(
            '/sesi',
            [SesiAbsensiController::class, 'store']
        )->name('sesi.store');


        /*
        |--------------------------------------------------------------------------
        | Detail Sesi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/sesi/{sesi}',
            [SesiAbsensiController::class, 'show']
        )->name('sesi.show');


        Route::patch(
            '/sesi/{sesi}/siswa/{siswa}/status',
            [SesiAbsensiController::class, 'updateStatus']
        )->name('sesi.status.update');


        Route::patch(
            '/sesi/{sesi}/tutup',
            [SesiAbsensiController::class, 'tutup']
        )->name('sesi.tutup');


        /*
        |--------------------------------------------------------------------------
        | Scan Absensi
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/sesi/{sesi}/scan',
            [ScanAbsensiController::class, 'scan']
        )->name('sesi.scan');


        /*
        |--------------------------------------------------------------------------
        | Rekap Absensi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/rekap',
            [RekapAbsensiController::class, 'index']
        )->name('rekap.index');


        Route::get(
            '/rekap/export',
            [RekapAbsensiController::class, 'export']
        )->name('rekap.export');

    });

    /*
|--------------------------------------------------------------------------
| CBT - Guru
|--------------------------------------------------------------------------
*/

Route::middleware('role:guru')
    ->prefix('cbt')
    ->name('cbt.')
    ->group(function () {


        Route::get(
            '/bank-soal',
            [BankSoalController::class, 'index']
        )->name('bank-soal.index');

        Route::get(
            '/bank-soal/template',
            [BankSoalController::class, 'downloadTemplate']
        )->name('bank-soal.template');

        Route::post(
            '/bank-soal/upload',
            [BankSoalController::class, 'upload']
        )->name('bank-soal.upload');

        Route::post(
            '/bank-soal/simpan',
            [BankSoalController::class, 'store']
        )->name('bank-soal.store');

        Route::get(
            '/bank-soal/arsip',
            [BankSoalController::class, 'arsip']
        )->name('bank-soal.arsip');


        Route::patch(
            '/bank-soal/{bankSoal}/archive',
            [BankSoalController::class, 'archive']
        )->name('bank-soal.archive');


        Route::patch(
            '/bank-soal/{bankSoal}/restore',
            [BankSoalController::class, 'restore']
        )->name('bank-soal.restore');

        Route::get(
            '/bank-soal/{bankSoal}',
            [BankSoalController::class, 'show']
        )->name('bank-soal.show');

    });

    /*
|--------------------------------------------------------------------------
| CBT - Operator
|--------------------------------------------------------------------------
*/

    Route::middleware('role:operator')
    ->prefix('cbt')
    ->name('cbt.')
    ->group(function () {

        Route::get(
            '/ujian',
            [UjianController::class, 'index']
        )->name('ujian.index');

        Route::get(
            '/ujian/buat',
            [UjianController::class, 'create']
        )->name('ujian.create');

        Route::get(
            '/ujian/arsip',
            [UjianController::class, 'arsip']
        )->name('ujian.arsip');

        Route::post(
            '/ujian',
            [UjianController::class, 'store']
        )->name('ujian.store');


        /*
        |--------------------------------------------------------------------------
        | Edit Ujian
        |--------------------------------------------------------------------------
        |
        | Hanya ujian berstatus draft yang diizinkan
        | oleh UjianController untuk diedit.
        |
        */

        Route::get(
            '/ujian/{ujian}/edit',
            [UjianController::class, 'edit']
        )->name('ujian.edit');


        Route::put(
            '/ujian/{ujian}',
            [UjianController::class, 'update']
        )->name('ujian.update');


        Route::get(
            '/ujian/{ujian}',
            [UjianController::class, 'show']
        )->name('ujian.show');

        Route::patch(
            '/ujian/{ujian}/publikasi',
            [UjianController::class, 'publish']
        )->name('ujian.publish');

    });

    /*
|--------------------------------------------------------------------------
| CBT Siswa
|--------------------------------------------------------------------------
*/

Route::middleware('role:siswa')
    ->prefix('cbt')
    ->name('cbt.siswa.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Daftar Ujian Siswa
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/ujian-saya',
            [UjianSiswaController::class, 'index']
        )->name('index');


        /*
        |--------------------------------------------------------------------------
        | Validasi Token
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/ujian/{ujian}/token',
            [UjianSiswaController::class, 'verifyToken']
        )
            ->middleware('throttle:5,1')
            ->name('ujian.token');


        /*
        |--------------------------------------------------------------------------
        | Halaman Persiapan Ujian
        |--------------------------------------------------------------------------
        |
        | Dibuka setelah token berhasil diverifikasi.
        |
        */

        Route::get(
            '/ujian/{ujian}/mulai',
            [UjianSiswaController::class, 'mulai']
        )->name('ujian.mulai');


        /*
        |--------------------------------------------------------------------------
        | Mulai Pengerjaan
        |--------------------------------------------------------------------------
        |
        | Dipanggil ketika siswa menekan tombol
        | "Mulai Ujian" pada halaman persiapan.
        |
        */

        Route::post(
            '/ujian/{ujian}/pengerjaan',
            [PengerjaanUjianController::class, 'mulai']
        )->name('pengerjaan.mulai');


        /*
        |--------------------------------------------------------------------------
        | Halaman Pengerjaan
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pengerjaan/{pengerjaan}',
            [PengerjaanUjianController::class, 'show']
        )->name('pengerjaan.show');


        /*
        |--------------------------------------------------------------------------
        | Autosave Jawaban
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pengerjaan/{pengerjaan}/jawaban',
            [PengerjaanUjianController::class, 'simpanJawaban']
        )->name('pengerjaan.jawaban');


        /*
        |--------------------------------------------------------------------------
        | Selesaikan Ujian
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pengerjaan/{pengerjaan}/selesai',
            [PengerjaanUjianController::class, 'selesai']
        )->name('pengerjaan.selesai');

        Route::get(
            '/pengerjaan/{pengerjaan}/hasil',
            [PengerjaanUjianController::class, 'hasil']
        )->name('pengerjaan.hasil');

        Route::get(
            '/riwayat',
            [PengerjaanUjianController::class, 'riwayat']
        )->name('riwayat');
        
        /*
        |--------------------------------------------------------------------------
        | Pelanggaran Ujian
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pengerjaan/{pengerjaan}/pelanggaran',
            [PelanggaranUjianController::class, 'store']
        )->name('pengerjaan.pelanggaran');

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