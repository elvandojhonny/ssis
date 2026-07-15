<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Master\TahunAjaranController;
use App\Http\Controllers\Master\KelasController;

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');
});

Route::middleware('auth')->group(function () {

    Route::view('/', 'dashboard.index')
        ->name('dashboard');

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

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});