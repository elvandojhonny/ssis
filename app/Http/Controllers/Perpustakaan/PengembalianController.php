<?php

namespace App\Http\Controllers\Perpustakaan;

use App\Http\Controllers\Controller;

use App\Models\Siswa;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    |
    | Menampilkan seluruh transaksi peminjaman yang masih aktif.
    | Transaksi aktif adalah:
    |
    | - dipinjam
    | - terlambat
    |
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Update Status Terlambat
        |--------------------------------------------------------------------------
        |
        | Sebelum data ditampilkan, transaksi yang sudah melewati
        | tanggal jatuh tempo akan ditandai sebagai terlambat.
        |
        */

        Peminjaman::query()
            ->where('status', 'dipinjam')
            ->whereDate(
                'tanggal_jatuh_tempo',
                '<',
                now()->toDateString()
            )
            ->update([
                'status' => 'terlambat',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Query Peminjaman Aktif
        |--------------------------------------------------------------------------
        */

        $query = Peminjaman::query()

            ->with([

                'siswa.kelas',

                'detailPeminjaman.buku',

            ])

            ->whereIn(
                'status',
                [
                    'dipinjam',
                    'terlambat',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        |
        | Bisa mencari berdasarkan:
        |
        | - kode peminjaman
        | - nama siswa
        | - NIS
        |
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(function ($q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Kode Peminjaman
                |--------------------------------------------------------------------------
                */

                $q->where(
                    'kode_peminjaman',
                    'like',
                    '%' . $search . '%'
                );


                /*
                |--------------------------------------------------------------------------
                | Data Siswa
                |--------------------------------------------------------------------------
                */

                $q->orWhereHas(
                    'siswa',
                    function ($siswaQuery) use ($search) {

                        $siswaQuery
                            ->where(
                                'nama',
                                'like',
                                '%' . $search . '%'
                            )

                            ->orWhere(
                                'nis',
                                'like',
                                '%' . $search . '%'
                            );

                    }
                );

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status')
            &&
            in_array(
                $request->status,
                [
                    'dipinjam',
                    'terlambat',
                ]
            )
        ) {

            $query->where(
                'status',
                $request->status
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Ambil Data
        |--------------------------------------------------------------------------
        */

        $peminjaman = $query

            ->latest('tanggal_pinjam')

            ->latest('id')

            ->paginate(10)

            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $totalDipinjam = Peminjaman::query()

            ->where(
                'status',
                'dipinjam'
            )

            ->count();


        $totalTerlambat = Peminjaman::query()

            ->where(
                'status',
                'terlambat'
            )

            ->count();


        $totalDikembalikanHariIni = Peminjaman::query()

            ->where(
                'status',
                'dikembalikan'
            )

            ->whereDate(
                'tanggal_kembali',
                now()->toDateString()
            )

            ->count();


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'perpustakaan.pengembalian.index',
            compact(

                'peminjaman',

                'totalDipinjam',

                'totalTerlambat',

                'totalDikembalikanHariIni'

            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scan QR
    |--------------------------------------------------------------------------
    |
    | QR yang digunakan sama dengan QR siswa pada sistem peminjaman.
    |
    | Fungsi ini TIDAK langsung mengembalikan buku.
    |
    | QR hanya digunakan untuk:
    |
    | 1. menemukan siswa
    | 2. mencari transaksi aktif siswa
    | 3. mengirim data ke frontend
    |
    */

    public function scanQr(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Validasi QR
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        'qr_token' => [
            'required',
            'string',
        ],

    ]);


    /*
    |--------------------------------------------------------------------------
    | Validasi Prefix QR SSIS
    |--------------------------------------------------------------------------
    */

    $prefix = 'SSIS-SISWA:';


    if (
        ! str_starts_with(
            $validated['qr_token'],
            $prefix
        )
    ) {

        return response()->json([

            'success' => false,

            'message' =>
                'QR bukan merupakan QR siswa SSIS.',

        ], 422);

    }


    /*
    |--------------------------------------------------------------------------
    | Ambil Token Asli
    |--------------------------------------------------------------------------
    |
    | QR:
    |
    | SSIS-SISWA:xxxxxxxxxxxxxxxx
    |
    | Menjadi:
    |
    | xxxxxxxxxxxxxxxx
    |
    */

    $token = substr(

        $validated['qr_token'],

        strlen($prefix)

    );


    /*
    |--------------------------------------------------------------------------
    | Cari Siswa
    |--------------------------------------------------------------------------
    */

    $siswa = Siswa::with([

            'user',

            'kelas',

        ])

        ->where(
            'qr_token',
            $token
        )

        ->where(
            'is_active',
            true
        )

        ->first();


    /*
    |--------------------------------------------------------------------------
    | Siswa Tidak Ditemukan
    |--------------------------------------------------------------------------
    */

    if (! $siswa) {

        return response()->json([

            'success' => false,

            'message' =>
                'Data siswa tidak ditemukan.',

        ], 404);

    }


    /*
    |--------------------------------------------------------------------------
    | Update Status Terlambat Siswa
    |--------------------------------------------------------------------------
    |
    | Jika ada peminjaman siswa yang jatuh temponya sudah lewat,
    | status diperbarui sebelum transaksi diambil.
    |
    */

    Peminjaman::query()

        ->where(
            'siswa_id',
            $siswa->id
        )

        ->where(
            'status',
            'dipinjam'
        )

        ->whereDate(
            'tanggal_jatuh_tempo',
            '<',
            now()->toDateString()
        )

        ->update([

            'status' => 'terlambat',

        ]);


    /*
    |--------------------------------------------------------------------------
    | Cari Peminjaman Aktif Siswa
    |--------------------------------------------------------------------------
    */

    $peminjaman = Peminjaman::query()

        ->with([

            'detailPeminjaman.buku',

        ])

        ->where(
            'siswa_id',
            $siswa->id
        )

        ->whereIn(
            'status',
            [
                'dipinjam',
                'terlambat',
            ]
        )

        ->latest(
            'tanggal_pinjam'
        )

        ->latest(
            'id'
        )

        ->get();


    /*
    |--------------------------------------------------------------------------
    | Tidak Ada Peminjaman Aktif
    |--------------------------------------------------------------------------
    */

    if ($peminjaman->isEmpty()) {

        return response()->json([

            'success' => false,

            'message' =>
                'Siswa tidak memiliki buku yang sedang dipinjam.',

        ], 404);

    }


    /*
    |--------------------------------------------------------------------------
    | Format Transaksi
    |--------------------------------------------------------------------------
    */

    $transaksi = $peminjaman->map(

        function ($item) {

            /*
            |--------------------------------------------------------------------------
            | Total Buku
            |--------------------------------------------------------------------------
            */

            $totalBuku =
                $item
                    ->detailPeminjaman
                    ->sum('jumlah');


            /*
            |--------------------------------------------------------------------------
            | Daftar Buku
            |--------------------------------------------------------------------------
            */

            $buku =
                $item
                    ->detailPeminjaman
                    ->map(

                        function ($detail) {

                            return [

                                'id' =>
                                    $detail->buku?->id,

                                'nama_buku' =>
                                    $detail->buku?->nama_buku
                                    ?? '-',

                                'jumlah' =>
                                    $detail->jumlah,

                            ];

                        }

                    )

                    ->values();


            /*
            |--------------------------------------------------------------------------
            | Data Transaksi
            |--------------------------------------------------------------------------
            */

            return [

                'id' =>
                    $item->id,


                'kode_peminjaman' =>
                    $item->kode_peminjaman,


                'tanggal_pinjam' =>
                    $item->tanggal_pinjam
                        ? $item
                            ->tanggal_pinjam
                            ->format('d M Y')
                        : '-',


                'tanggal_jatuh_tempo' =>
                    $item->tanggal_jatuh_tempo
                        ? $item
                            ->tanggal_jatuh_tempo
                            ->format('d M Y')
                        : '-',


                'status' =>
                    $item->status,


                'total_buku' =>
                    $totalBuku,


                'buku' =>
                    $buku,


                'url' =>
                    route(
                        'perpustakaan.pengembalian.show',
                        $item
                    ),

            ];

        }

    )->values();


    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'success' => true,

        'message' =>
            'Data peminjaman siswa ditemukan.',


        'data' => [

            /*
            |--------------------------------------------------------------------------
            | Data Siswa
            |--------------------------------------------------------------------------
            */

            'siswa' => [

                'id' =>
                    $siswa->id,

                'nama' =>
                    $siswa->nama,

                'nis' =>
                    $siswa->nis,

                'kelas' =>
                    optional(
                        $siswa->kelas
                    )->nama
                    ?? '-',

                'kelas_id' =>
                    $siswa->kelas_id,

            ],


            /*
            |--------------------------------------------------------------------------
            | Transaksi
            |--------------------------------------------------------------------------
            */

            'total_transaksi' =>
                $peminjaman->count(),


            'transaksi' =>
                $transaksi,

        ],

    ]);
}


    /*
    |--------------------------------------------------------------------------
    | Peminjaman Siswa
    |--------------------------------------------------------------------------
    |
    | Endpoint alternatif untuk mengambil transaksi aktif berdasarkan
    | ID siswa tanpa melakukan scan ulang.
    |
    */

    public function peminjamanSiswa(Siswa $siswa)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan Siswa Aktif
        |--------------------------------------------------------------------------
        */

        if (!$siswa->is_active) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Siswa sudah tidak aktif.',

            ], 404);

        }


        /*
        |--------------------------------------------------------------------------
        | Ambil Peminjaman
        |--------------------------------------------------------------------------
        */

        $peminjaman = Peminjaman::query()

            ->with([

                'detailPeminjaman.buku',

            ])

            ->where(
                'siswa_id',
                $siswa->id
            )

            ->whereIn(
                'status',
                [
                    'dipinjam',
                    'terlambat',
                ]
            )

            ->latest(
                'tanggal_pinjam'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'data' => $peminjaman,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    |
    | Menampilkan halaman konfirmasi pengembalian.
    |
    */

    public function show(Peminjaman $peminjaman)
    {
        /*
        |--------------------------------------------------------------------------
        | Validasi Status
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $peminjaman->status,
                [
                    'dipinjam',
                    'terlambat',
                ]
            )
        ) {

            return redirect()

                ->route(
                    'perpustakaan.pengembalian.index'
                )

                ->with(
                    'error',
                    'Transaksi ini sudah dikembalikan.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Load Relasi
        |--------------------------------------------------------------------------
        */

        $peminjaman->load([

            'siswa.kelas',

            'petugas',

            'detailPeminjaman.buku',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Total Buku
        |--------------------------------------------------------------------------
        */

        $totalBuku = $peminjaman
            ->detailPeminjaman
            ->sum('jumlah');


        /*
        |--------------------------------------------------------------------------
        | Hitung Keterlambatan
        |--------------------------------------------------------------------------
        */

        $hariTerlambat = 0;


        if (
            $peminjaman->tanggal_jatuh_tempo
            &&
            now()->startOfDay()->gt(
                $peminjaman
                    ->tanggal_jatuh_tempo
                    ->copy()
                    ->startOfDay()
            )
        ) {

            $hariTerlambat =
                $peminjaman
                    ->tanggal_jatuh_tempo
                    ->copy()
                    ->startOfDay()
                    ->diffInDays(
                        now()->startOfDay()
                    );

        }


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'perpustakaan.pengembalian.show',
            compact(

                'peminjaman',

                'totalBuku',

                'hariTerlambat'

            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    |
    | Menyelesaikan transaksi pengembalian.
    |
    */

    public function store(
        Request $request,
        Peminjaman $peminjaman
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validasi Input
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'catatan_pengembalian' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Pastikan Belum Dikembalikan
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $peminjaman->status,
                [
                    'dipinjam',
                    'terlambat',
                ]
            )
        ) {

            return redirect()

                ->route(
                    'perpustakaan.pengembalian.index'
                )

                ->with(
                    'error',
                    'Transaksi ini sudah dikembalikan.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Load Detail
        |--------------------------------------------------------------------------
        */

        $peminjaman->load([

            'detailPeminjaman.buku',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Pastikan Ada Buku
        |--------------------------------------------------------------------------
        */

        if (
            $peminjaman
                ->detailPeminjaman
                ->isEmpty()
        ) {

            return back()

                ->with(
                    'error',
                    'Detail buku pada transaksi tidak ditemukan.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();


        try {

            /*
            |--------------------------------------------------------------------------
            | Kembalikan Stok Buku
            |--------------------------------------------------------------------------
            */

            foreach (
                $peminjaman->detailPeminjaman
                as $detail
            ) {

                /*
                |--------------------------------------------------------------------------
                | Buku
                |--------------------------------------------------------------------------
                */

                $buku = $detail->buku;


                /*
                |--------------------------------------------------------------------------
                | Pastikan Buku Masih Ada
                |--------------------------------------------------------------------------
                */

                if (!$buku) {

                    throw new \Exception(
                        'Data buku pada transaksi tidak ditemukan.'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Tambahkan Stok
                |--------------------------------------------------------------------------
                */

                $buku->increment(
                    'jumlah_tersedia',
                    $detail->jumlah
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Catatan
            |--------------------------------------------------------------------------
            |
            | Karena tabel peminjaman Anda saat ini hanya mempunyai
            | satu kolom "catatan", catatan pengembalian digabungkan
            | dengan catatan transaksi yang sudah ada.
            |
            */

            $catatan = $peminjaman->catatan;


            if (
                $request->filled(
                    'catatan_pengembalian'
                )
            ) {

                $catatanPengembalian =
                    'Pengembalian: '
                    .
                    trim(
                        $request->catatan_pengembalian
                    );


                if ($catatan) {

                    $catatan .=
                        PHP_EOL
                        .
                        $catatanPengembalian;

                } else {

                    $catatan =
                        $catatanPengembalian;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Update Peminjaman
            |--------------------------------------------------------------------------
            */

            $peminjaman->update([

                'tanggal_kembali' =>
                    now()->toDateString(),

                'status' =>
                    'dikembalikan',

                'catatan' =>
                    $catatan,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            return redirect()

                ->route(
                    'perpustakaan.pengembalian.index'
                )

                ->with(
                    'success',
                    'Buku berhasil dikembalikan.'
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Rollback
            |--------------------------------------------------------------------------
            */

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | Kembali
            |--------------------------------------------------------------------------
            */

            return back()

                ->withInput()

                ->with(
                    'error',
                    $e->getMessage()
                );

        }
    }

    /*
|--------------------------------------------------------------------------
| Riwayat Pengembalian
|--------------------------------------------------------------------------
*/

public function riwayat(Request $request)
{
    $query = Peminjaman::query()
        ->with([
            'siswa.kelas',
            'petugas',
            'detailPeminjaman.buku',
        ])
        ->where('status', 'dikembalikan')
        ->whereNotNull('tanggal_kembali');


    /*
    |--------------------------------------------------------------------------
    | Pencarian
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where(
                'kode_peminjaman',
                'like',
                '%' . $search . '%'
            );

            $q->orWhereHas(
                'siswa',
                function ($siswa) use ($search) {

                    $siswa
                        ->where(
                            'nama',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'nis',
                            'like',
                            '%' . $search . '%'
                        );

                }
            );

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Filter Tanggal Pengembalian
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tanggal')) {

        $query->whereDate(
            'tanggal_kembali',
            $request->tanggal
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Data Riwayat
    |--------------------------------------------------------------------------
    */

    $riwayat = $query
        ->latest('tanggal_kembali')
        ->latest('id')
        ->paginate(10)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | Statistik
    |--------------------------------------------------------------------------
    */

    $totalPengembalian = Peminjaman::query()
        ->where('status', 'dikembalikan')
        ->whereNotNull('tanggal_kembali')
        ->count();


    $pengembalianHariIni = Peminjaman::query()
        ->where('status', 'dikembalikan')
        ->whereDate(
            'tanggal_kembali',
            now()->toDateString()
        )
        ->count();


    $pengembalianBulanIni = Peminjaman::query()
        ->where('status', 'dikembalikan')
        ->whereYear(
            'tanggal_kembali',
            now()->year
        )
        ->whereMonth(
            'tanggal_kembali',
            now()->month
        )
        ->count();


    return view(
        'perpustakaan.pengembalian.riwayat',
        compact(
            'riwayat',
            'totalPengembalian',
            'pengembalianHariIni',
            'pengembalianBulanIni'
        )
    );
}

}