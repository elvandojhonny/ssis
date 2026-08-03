<?php

namespace App\Http\Controllers\Perpustakaan;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Guru;
use App\Models\Peminjaman;
use App\Models\Petugas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Daftar Peminjaman
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
{

/*
    |--------------------------------------------------------------------------
    | UPDATE STATUS TERLAMBAT
    |--------------------------------------------------------------------------
    |
    | Jatuh tempo hari ini masih dianggap DIPINJAM.
    | Baru TERLAMBAT mulai hari berikutnya.
    |
    */

    /*
|--------------------------------------------------------------------------
| SINKRONISASI STATUS PEMINJAMAN
|--------------------------------------------------------------------------
|
| Hari jatuh tempo masih DIPINJAM.
| TERLAMBAT dimulai pada hari berikutnya.
|
*/

/*
| Yang belum melewati jatuh tempo = DIPINJAM
*/

Peminjaman::whereIn(
    'status',
    [
        'dipinjam',
        'terlambat',
    ]
)
->whereDate(
    'tanggal_jatuh_tempo',
    '>=',
    today()
)
->update([
    'status' => 'dipinjam',
]);


/*
| Yang sudah melewati jatuh tempo = TERLAMBAT
*/

Peminjaman::whereIn(
    'status',
    [
        'dipinjam',
        'terlambat',
    ]
)
->whereDate(
    'tanggal_jatuh_tempo',
    '<',
    today()
)
->update([
    'status' => 'terlambat',
]);


    $status = $request->status;
    $search = $request->search;
    $tingkat = $request->tingkat;
    /*
    |--------------------------------------------------------------------------
    | Filter
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Query Peminjaman
    |--------------------------------------------------------------------------
    */

    $peminjaman = Peminjaman::with([
        'petugas',
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

    /*
    |--------------------------------------------------------------------------
    | Filter Status
    |--------------------------------------------------------------------------
    */

    ->when($status, function ($query) use ($status) {

        $query->where(
            'status',
            $status
        );

    })


    /*
    |--------------------------------------------------------------------------
    | Filter Tingkat Kelas
    |--------------------------------------------------------------------------
    */

    ->when($tingkat, function ($query) use ($tingkat) {

        $query->whereHas(
            'siswa.kelas',
            function ($q) use ($tingkat) {

                $q->where(
                    'tingkat',
                    $tingkat
                );

            }
        );

    })


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    ->when($search, function ($query) use ($search) {

        $query->where(function ($q) use ($search) {

            $q->where(
                'kode_peminjaman',
                'like',
                "%{$search}%"
            )

            /*
            |--------------------------------------------------------------------------
            | Cari Nama Siswa / NIS
            |--------------------------------------------------------------------------
            */

            ->orWhereHas(
                'siswa',
                function ($q) use ($search) {

                    $q->where(
                        'nama',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'nis',
                        'like',
                        "%{$search}%"
                    );

                }
            )


            /*
            |--------------------------------------------------------------------------
            | Cari Kelas
            |--------------------------------------------------------------------------
            */

            ->orWhereHas(
                'siswa.kelas',
                function ($q) use ($search) {

                    $q->where(
                        'nama_kelas',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'tingkat',
                        'like',
                        "%{$search}%"
                    );

                }
            )


            /*
            |--------------------------------------------------------------------------
            | Cari Guru
            |--------------------------------------------------------------------------
            */

            ->orWhereHas(
                'guru',
                function ($q) use ($search) {

                    $q->where(
                        'nama',
                        'like',
                        "%{$search}%"
                    );

                }
            );

        });

    })


    /*
    |--------------------------------------------------------------------------
    | Urutan + Pagination
    |--------------------------------------------------------------------------
    */

    ->latest()

    ->paginate(10)

    ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | Statistik
    |--------------------------------------------------------------------------
    */

    $totalDipinjaman =
        Peminjaman::where(
            'status',
            'dipinjam'
        )->count();


    $totalTerlambat =
        Peminjaman::where(
            'status',
            'terlambat'
        )->count();


    $totalHariIni =
    Peminjaman::whereDate(
        'tanggal_pinjam',
        today()
    )
    ->whereIn(
        'status',
        [
            'dipinjam',
            'terlambat',
        ]
    )
    ->count();


    $totalAktif =
    Peminjaman::whereIn(
        'status',
        [
            'dipinjam',
            'terlambat',
        ]
    )
    ->count();


    /*
    |--------------------------------------------------------------------------
    | View
    |--------------------------------------------------------------------------
    */

    return view(
        'perpustakaan.peminjaman.index',
        compact(
            'peminjaman',
            'status',
            'search',
            'tingkat',
            'totalDipinjaman',
            'totalTerlambat',
            'totalHariIni',
            'totalAktif'
        )
    );
}

    /*
    |--------------------------------------------------------------------------
    | Form Peminjaman
    |--------------------------------------------------------------------------
    */

    public function create()
{
    return view('perpustakaan.peminjaman.create');
}

public function bukuByKelas($kelasId)
{
    $buku = Buku::with('kelas')
        ->where('kelas_id', $kelasId)
        ->where('is_active', true)
        ->where('jumlah_tersedia', '>', 0)
        ->orderBy('nama_buku')
        ->get()
        ->map(function ($item) {

            return [
                'id' => $item->id,
                'nama_buku' => $item->nama_buku,
                'kelas_id' => $item->kelas_id,
                'kelas' => optional($item->kelas)->nama,
                'jumlah_tersedia' => $item->jumlah_tersedia,
            ];

        });

    return response()->json($buku);
}

    /*
    |--------------------------------------------------------------------------
    | Scan QR Siswa
    |--------------------------------------------------------------------------
    */

    public function scanQr(Request $request)
{
    $validated = $request->validate([
        'qr_token' => ['required', 'string'],
    ]);

    $prefix = 'SSIS-SISWA:';

    if (! str_starts_with($validated['qr_token'], $prefix)) {

        return response()->json([
            'success' => false,
            'message' => 'QR bukan merupakan QR siswa SSIS.'
        ], 422);

    }

    $token = substr(
        $validated['qr_token'],
        strlen($prefix)
    );

    $siswa = Siswa::with([
            'user',
            'kelas',
        ])
        ->where('qr_token', $token)
        ->where('is_active', true)
        ->first();

    if (! $siswa) {

        return response()->json([
            'success' => false,
            'message' => 'Data siswa tidak ditemukan.'
        ], 404);

    }

    return response()->json([
    'success' => true,
    'data' => [
        'id'       => $siswa->id,
        'nama'     => $siswa->nama,
        'nis'      => $siswa->nis,
        'kelas'    => optional($siswa->kelas)->nama,
        'kelas_id' => $siswa->kelas_id,
    ]
]);
}

    /*
    |--------------------------------------------------------------------------
    | Generate Kode Peminjaman
    |--------------------------------------------------------------------------
    */

    private function generateKode(): string
    {
        $prefix = 'PMJ-' . now()->format('Ymd');

        $last = Peminjaman::whereDate(
                'created_at',
                today()
            )
            ->count() + 1;

        return $prefix . '-' . str_pad(
            $last,
            4,
            '0',
            STR_PAD_LEFT
        );
    }
    /*
    |--------------------------------------------------------------------------
    | Simpan Transaksi Peminjaman
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => [
                'required',
                'exists:siswa,id',
            ],

            'tanggal_pinjam' => [
                'required',
                'date',
            ],

            'tanggal_jatuh_tempo' => [
                'required',
                'date',
                'after_or_equal:tanggal_pinjam',
            ],

            'buku' => [
                'required',
                'array',
                'min:1',
            ],

            'buku.*' => [
                'exists:buku,id',
            ],

            'jumlah' => [
                'required',
                'array',
            ],

            'jumlah.*' => [
                'integer',
                'min:1',
            ],
        ]);

        $petugas = Auth::user()->petugas;

        if (!$petugas) {

            return back()
                ->with('error', 'Data petugas tidak ditemukan.');
        }

        DB::beginTransaction();

        try {

            $peminjaman = Peminjaman::create([

                'kode_peminjaman'      => $this->generateKode(),

                'petugas_id'           => $petugas->id,

                'siswa_id'             => $request->siswa_id,

                'guru_id'              => null,

                'tanggal_pinjam'       => $request->tanggal_pinjam,

                'tanggal_jatuh_tempo'  => $request->tanggal_jatuh_tempo,

                'status'               => 'dipinjam',

                'catatan'              => $request->catatan,

            ]);

            if (
                count($request->buku)
                !=
                count(array_unique($request->buku))
            ) {

                return back()

                    ->withInput()

                    ->with(
                        'error',
                        'Buku yang sama tidak boleh dipilih lebih dari satu kali.'
                    );

            }

            foreach ($request->buku as $index => $bukuId) {

                $buku = Buku::findOrFail($bukuId);

                $jumlah = (int) $request->jumlah[$index];

                if ($buku->jumlah_tersedia < $jumlah) {

                    throw new \Exception(
                        "Stok buku {$buku->nama_buku} tidak mencukupi."
                    );
                }

                DetailPeminjaman::create([

                    'peminjaman_id' => $peminjaman->id,

                    'buku_id'       => $buku->id,

                    'jumlah'        => $jumlah,

                ]);

                $buku->decrement(
                    'jumlah_tersedia',
                    $jumlah
                );
            }

            DB::commit();

return redirect()
    ->route('perpustakaan.peminjaman.index')
    ->with(
        'success',
        'Peminjaman berhasil disimpan.'
    );

        } catch (\Throwable $e) {

    DB::rollBack();

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
    | Detail Peminjaman
    |--------------------------------------------------------------------------
    */

   /*
|--------------------------------------------------------------------------
| Detail Peminjaman
|--------------------------------------------------------------------------
*/

public function show(Peminjaman $peminjaman)
{
    /*
    |--------------------------------------------------------------------------
    | Load Relasi
    |--------------------------------------------------------------------------
    */

    $peminjaman->load([
        'petugas.user',
        'siswa.kelas',
        'guru',
        'detailPeminjaman.buku.kelas',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Sinkronisasi Status
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | Jatuh tempo : 31 Juli
    | 31 Juli     : Dipinjam
    | 1 Agustus   : Terlambat
    |
    */

    if (
        in_array(
            $peminjaman->status,
            [
                'dipinjam',
                'terlambat',
            ]
        )
    ) {

        $statusBaru =
            $peminjaman
                ->tanggal_jatuh_tempo
                ->startOfDay()
                ->lt(today())

                ? 'terlambat'
                : 'dipinjam';


        if (
            $peminjaman->status
            !==
            $statusBaru
        ) {

            $peminjaman->update([
                'status' => $statusBaru,
            ]);

            $peminjaman->refresh();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Tampilkan Detail
    |--------------------------------------------------------------------------
    */

    return view(
        'perpustakaan.peminjaman.show',
        compact('peminjaman')
    );
}

    /*
    |--------------------------------------------------------------------------
    | Hapus Transaksi
    |--------------------------------------------------------------------------
    |
    | Hanya boleh menghapus transaksi yang belum dikembalikan.
    | Sebelum dihapus stok buku akan dikembalikan.
    |
    */

    public function destroy(Peminjaman $peminjaman)
    {
        DB::beginTransaction();

        try {

            if ($peminjaman->status === 'dikembalikan') {

                return back()->with(
                    'error',
                    'Transaksi yang sudah dikembalikan tidak dapat dihapus.'
                );
            }

            $peminjaman->load('detailPeminjaman.buku');

            foreach ($peminjaman->detailPeminjaman as $detail) {

                $detail->buku->increment(
                    'jumlah_tersedia',
                    $detail->jumlah
                );
            }

            $peminjaman
                ->detailPeminjaman()
                ->delete();

            $peminjaman->delete();

            DB::commit();

            return redirect()

                ->route('perpustakaan.peminjaman.index')

                ->with(
                    'success',
                    'Transaksi berhasil dihapus.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()

                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}