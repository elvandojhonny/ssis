<?php

namespace App\Http\Controllers\Perpustakaan;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Carbon\Carbon;

class PerpustakaanSiswaController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;

        abort_if(!$siswa, 404);

        /*
        |--------------------------------------------------------------------------
        | Query Dasar
        |--------------------------------------------------------------------------
        */

        $query = Peminjaman::query()
        ->where('siswa_id', $siswa->id)
        ->whereIn('status', [
            'dipinjam',
            'terlambat',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $totalTransaksi = (clone $query)->count();

        $dipinjam = (clone $query)
            ->where('status', 'dipinjam')
            ->count();

        $dikembalikan = (clone $query)
            ->where('status', 'dikembalikan')
            ->count();

        $terlambat = (clone $query)
            ->where(function ($q) {

                $q->where('status', 'terlambat')
                    ->orWhere(function ($sub) {

                        $sub->where('status', 'dipinjam')
                            ->whereDate(
                                'tanggal_jatuh_tempo',
                                '<',
                                Carbon::today()
                            );

                    });

            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Data Tabel
        |--------------------------------------------------------------------------
        */

        $transaksi = (clone $query)
            ->with([
                'detailPeminjaman.buku',
                'petugas',
            ])
            ->latest('tanggal_pinjam')
            ->paginate(10);

        return view(
            'perpustakaan.siswa.index',
            compact(
                'transaksi',
                'totalTransaksi',
                'dipinjam',
                'dikembalikan',
                'terlambat'
            )
        );
    }

public function arsip()
{
    $siswa = auth()->user()->siswa;

    $arsip = Peminjaman::with([
        'detailPeminjaman.buku',
        'petugas'
    ])
    ->where('siswa_id', $siswa->id)
    ->where('status', 'dikembalikan')
    ->orderByDesc('tanggal_kembali')
    ->paginate(10);

    return view('perpustakaan.siswa.arsip', compact('arsip'));
}

}