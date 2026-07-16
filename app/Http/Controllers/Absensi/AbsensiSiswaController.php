<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;

class AbsensiSiswaController extends Controller
{
    public function index()
{
    $user = auth()->user();

    $user->load(
        'siswa.kelas.tahunAjaran'
    );

    abort_unless(
        $user->siswa,
        403,
        'Data siswa tidak ditemukan.'
    );

    $siswa = $user->siswa;

    /*
    |--------------------------------------------------------------------------
    | Riwayat Absensi
    |--------------------------------------------------------------------------
    | Mengambil riwayat berdasarkan tahun ajaran kelas siswa.
    */

    $riwayat = Absensi::with([
            'sesiAbsensi.kelas.tahunAjaran',
        ])
        ->where(
            'siswa_id',
            $siswa->id
        )
        ->whereHas(
            'sesiAbsensi.kelas',
            function ($query) use ($siswa) {

                $query->where(
                    'tahun_ajaran_id',
                    $siswa
                        ->kelas
                        ->tahun_ajaran_id
                );
            }
        )
        ->get()
        ->sortByDesc(function ($absensi) {

            return $absensi
                ->sesiAbsensi
                ->tanggal;
        })
        ->values();


    /*
    |--------------------------------------------------------------------------
    | Statistik Absensi
    |--------------------------------------------------------------------------
    */

    $statistik = [
        'hadir' => $riwayat
            ->where('status', 'hadir')
            ->count(),

        'terlambat' => $riwayat
            ->where('status', 'terlambat')
            ->count(),

        'izin' => $riwayat
            ->where('status', 'izin')
            ->count(),

        'sakit' => $riwayat
            ->where('status', 'sakit')
            ->count(),

        'alpa' => $riwayat
            ->where('status', 'alpa')
            ->count(),

        'total' => $riwayat->count(),
    ];


    return view(
        'absensi.siswa.index',
        compact(
            'user',
            'riwayat',
            'statistik'
        )
    );
}
}