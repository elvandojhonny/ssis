<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;

class AbsensiSiswaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $user->load([
            'siswa.kelas.tahunAjaran',
        ]);

        abort_unless(
            $user->siswa,
            403,
            'Data siswa tidak ditemukan.'
        );

        $siswa = $user->siswa;

        /*
        |--------------------------------------------------------------------------
        | Ambil seluruh riwayat absensi siswa
        |--------------------------------------------------------------------------
        */

        $riwayat = Absensi::with([
                'sesiAbsensi'
            ])
            ->where('siswa_id', $siswa->id)
            ->get()
            ->filter(function ($absensi) {
                return $absensi->sesiAbsensi != null;
            })
            ->sortByDesc(function ($absensi) {
                return $absensi->sesiAbsensi->tanggal;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $statistik = [
            'hadir'      => $riwayat->where('status', 'hadir')->count(),
            'terlambat'  => $riwayat->where('status', 'terlambat')->count(),
            'izin'       => $riwayat->where('status', 'izin')->count(),
            'sakit'      => $riwayat->where('status', 'sakit')->count(),
            'alpa'       => $riwayat->where('status', 'alpa')->count(),
            'total'      => $riwayat->count(),
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