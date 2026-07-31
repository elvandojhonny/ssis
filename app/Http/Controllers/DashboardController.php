<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Petugas;
use App\Models\TahunAjaran;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Operator
        |--------------------------------------------------------------------------
        */

        if ($user->isOperator()) {

            return $this->operatorDashboard();

        }


        /*
        |--------------------------------------------------------------------------
        | Guru
        |--------------------------------------------------------------------------
        */

        if ($user->isGuru()) {

            return view('dashboard.guru');

        }


        /*
        |--------------------------------------------------------------------------
        | Petugas
        |--------------------------------------------------------------------------
        */

        if ($user->isPetugas()) {

            return $this->petugasDashboard();

        }


        /*
        |--------------------------------------------------------------------------
        | Siswa
        |--------------------------------------------------------------------------
        */

        if ($user->isSiswa()) {

            $user->load(
                'siswa.kelas.tahunAjaran'
            );

            return view(
                'dashboard.siswa',
                compact('user')
            );

        }


        abort(403);
    }


    /*
    |--------------------------------------------------------------------------
    | Dashboard Operator
    |--------------------------------------------------------------------------
    */

    private function operatorDashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | Total Guru
        |--------------------------------------------------------------------------
        */

        $totalGuru = Guru::where(
            'is_active',
            true
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Total Siswa
        |--------------------------------------------------------------------------
        */

        $totalSiswa = Siswa::where(
            'is_active',
            true
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Total Kelas
        |--------------------------------------------------------------------------
        */

        $totalKelas = Kelas::where(
            'is_active',
            true
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Tahun Ajaran Aktif
        |--------------------------------------------------------------------------
        */

        $tahunAjaranAktif = TahunAjaran::where(
            'is_active',
            true
        )->first();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard.operator',
            compact(
                'totalGuru',
                'totalSiswa',
                'totalKelas',
                'tahunAjaranAktif'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Dashboard Petugas
    |--------------------------------------------------------------------------
    */

   private function petugasDashboard()
{
    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS TERLAMBAT
    |--------------------------------------------------------------------------
    |
    | Jatuh tempo hari ini masih diperbolehkan.
    | Status terlambat baru diberikan mulai hari berikutnya.
    |
    */

    Peminjaman::where(
        'status',
        'dipinjam'
    )
    ->whereDate(
        'tanggal_jatuh_tempo',
        '<',
        today()
    )
    ->update([
        'status' => 'terlambat',
    ]);


    /*
    |--------------------------------------------------------------------------
    | TOTAL BUKU YANG MASIH DI LUAR
    |--------------------------------------------------------------------------
    |
    | Hanya menghitung jumlah buku dari transaksi:
    | - dipinjam
    | - terlambat
    |
    | Transaksi dikembalikan tidak dihitung.
    |
    */

    $totalBuku =
        DetailPeminjaman::whereHas(
            'peminjaman',
            function ($query) {

                $query->whereIn(
                    'status',
                    [
                        'dipinjam',
                        'terlambat',
                    ]
                );

            }
        )
        ->sum('jumlah');


    /*
    |--------------------------------------------------------------------------
    | STOK TERSEDIA
    |--------------------------------------------------------------------------
    */

    $totalStok =
        Buku::where(
            'is_active',
            true
        )
        ->sum('jumlah_tersedia');


    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI SEDANG DIPINJAM
    |--------------------------------------------------------------------------
    */

    $dipinjam =
        Peminjaman::where(
            'status',
            'dipinjam'
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI TERLAMBAT
    |--------------------------------------------------------------------------
    */

    $terlambat =
        Peminjaman::where(
            'status',
            'terlambat'
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI TERBARU
    |--------------------------------------------------------------------------
    |
    | Tidak menampilkan transaksi yang sudah dikembalikan.
    |
    */

    $transaksiTerbaru =
        Peminjaman::with([
            'siswa.kelas',
            'guru',
            'detailPeminjaman.buku',
        ])
        ->whereIn(
            'status',
            [
                'dipinjam',
                'terlambat',
            ]
        )
        ->latest()
        ->take(5)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | PEMINJAMAN TERLAMBAT
    |--------------------------------------------------------------------------
    */

    $peminjamanTerlambat =
        Peminjaman::with([
            'siswa.kelas',
        ])
        ->where(
            'status',
            'terlambat'
        )
        ->orderBy(
            'tanggal_jatuh_tempo'
        )
        ->take(5)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | BUKU SERING DIPINJAM
    |--------------------------------------------------------------------------
    */

    $bukuTerpopuler =
        Buku::query()
        ->select('buku.*')
        ->selectSub(
            function ($query) {

                $query
                    ->from('detail_peminjaman')
                    ->selectRaw(
                        'COALESCE(SUM(jumlah), 0)'
                    )
                    ->whereColumn(
                        'detail_peminjaman.buku_id',
                        'buku.id'
                    );

            },
            'total_dipinjam'
        )
        ->orderByDesc(
            'total_dipinjam'
        )
        ->take(4)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | AKTIVITAS AKTIF
    |--------------------------------------------------------------------------
    */

    $aktivitas =
        Peminjaman::with([
            'siswa.kelas',
        ])
        ->whereIn(
            'status',
            [
                'dipinjam',
                'terlambat',
            ]
        )
        ->latest(
            'updated_at'
        )
        ->take(5)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'dashboard.petugas',
        compact(
            'totalBuku',
            'totalStok',
            'dipinjam',
            'terlambat',
            'transaksiTerbaru',
            'peminjamanTerlambat',
            'bukuTerpopuler',
            'aktivitas'
        )
    );
}
}