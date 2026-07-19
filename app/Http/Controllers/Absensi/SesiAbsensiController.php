<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Services\FinalisasiSesiAbsensiService;

use App\Models\Absensi;
use App\Models\Siswa;

class SesiAbsensiController extends Controller
{
    public function index(
    FinalisasiSesiAbsensiService $finalisasiService
) {
    /*
     * Finalisasi sesi yang waktunya
     * sudah berakhir sebelum halaman ditampilkan.
     */
    $finalisasiService
        ->finalisasiKedaluwarsa();


    $sesiAktif = SesiAbsensi::with([
            'kelas.tahunAjaran',
            'pembuka',
        ])
        ->whereDate('tanggal', today())
        ->where('status', 'aktif')
        ->latest()
        ->get();


    $riwayatSesi = SesiAbsensi::with([
        'kelas.tahunAjaran',
        'pembuka',
    ])
    ->withCount('absensis')

    // Hanya 7 hari terakhir
    ->whereDate(
        'tanggal',
        '>=',
        now()->subDays(7)->toDateString()
    )

    ->orderByDesc('tanggal')
    ->orderByDesc('id')

    ->paginate(10)
    ->withQueryString();


    return view(
        'absensi.sesi.index',
        compact(
            'sesiAktif',
            'riwayatSesi'
        )
    );
}

    public function create()
    {
        $kelas = Kelas::with('tahunAjaran')
            ->where('is_active', true)
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view(
            'absensi.sesi.create',
            compact('kelas')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => [
                'required',
                'exists:kelas,id',
            ],

            'jenis' => [
                'required',
                Rule::in(['pagi', 'siang']),
            ],

            'waktu_mulai' => [
                'required',
                'date_format:H:i',
            ],

            'batas_terlambat' => [
                'nullable',
                'date_format:H:i',
                'after_or_equal:waktu_mulai',
            ],

            'waktu_selesai' => [
                'required',
                'date_format:H:i',
                'after:waktu_mulai',
            ],
        ]);

        $kelas = Kelas::whereKey($validated['kelas_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $sudahAda = SesiAbsensi::where(
                'kelas_id',
                $kelas->id
            )
            ->whereDate('tanggal', today())
            ->where('jenis', $validated['jenis'])
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Sesi absensi '
                    . $validated['jenis']
                    . ' untuk kelas tersebut sudah dibuat hari ini.'
                );
        }

        $sesi = DB::transaction(function () use ($validated) {
            return SesiAbsensi::create([
                'kelas_id' => $validated['kelas_id'],

                'dibuka_oleh' => auth()->id(),

                'tanggal' => today(),

                'jenis' => $validated['jenis'],

                'waktu_mulai' =>
                    $validated['waktu_mulai'],

                'batas_terlambat' =>
                    $validated['batas_terlambat'] ?? null,

                'waktu_selesai' =>
                    $validated['waktu_selesai'],

                'status' => 'aktif',
            ]);
        });

        return redirect()
            ->route('absensi.sesi.show', $sesi)
            ->with(
                'success',
                'Sesi absensi berhasil dibuka.'
            );
    }

   public function show(
    SesiAbsensi $sesi,
    FinalisasiSesiAbsensiService $finalisasiService
) {
    /*
     * Finalisasi semua sesi yang waktunya
     * sudah berakhir.
     */
    $finalisasiService->finalisasiKedaluwarsa();

    /*
     * Ambil ulang data sesi setelah finalisasi.
     */
    $sesi->refresh();

    $sesi->load([
        'kelas.tahunAjaran',
        'pembuka',
        'absensis.siswa.user',
    ]);

    $daftarSiswa = Siswa::with('user')
        ->where('kelas_id', $sesi->kelas_id)
        ->where('is_active', true)
        ->orderBy('nis')
        ->get()
        ->map(function ($siswa) use ($sesi) {

            $siswa->data_absensi =
                $sesi->absensis->firstWhere(
                    'siswa_id',
                    $siswa->id
                );

            return $siswa;
        });

    $totalSiswa = $daftarSiswa->count();

    $hadir = $daftarSiswa
        ->filter(
            fn ($siswa) =>
                $siswa->data_absensi?->status === 'hadir'
        )
        ->count();

    $terlambat = $daftarSiswa
        ->filter(
            fn ($siswa) =>
                $siswa->data_absensi?->status === 'terlambat'
        )
        ->count();

    $belumAbsen = $daftarSiswa
        ->filter(
            fn ($siswa) =>
                $siswa->data_absensi === null
        )
        ->count();

    return view(
        'absensi.sesi.show',
        compact(
            'sesi',
            'daftarSiswa',
            'totalSiswa',
            'hadir',
            'terlambat',
            'belumAbsen'
        )
    );
}

public function updateStatus(
    Request $request,
    SesiAbsensi $sesi,
    Siswa $siswa
) {
    /*
     * Pastikan siswa memang berasal
     * dari kelas sesi tersebut.
     */
    abort_unless(
        (int) $siswa->kelas_id
        === (int) $sesi->kelas_id,
        403,
        'Siswa bukan anggota kelas ini.'
    );

    $validated = $request->validate([
        'status' => [
            'required',
            Rule::in([
                'hadir',
                'terlambat',
                'izin',
                'sakit',
                'alpa',
            ]),
        ],

        'keterangan' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    $waktuAbsen = null;

    if (
        in_array(
            $validated['status'],
            ['hadir', 'terlambat']
        )
    ) {
        $waktuAbsen = now();
    }

    Absensi::updateOrCreate(
        [
            'sesi_absensi_id' => $sesi->id,
            'siswa_id' => $siswa->id,
        ],
        [
            'waktu_absen' => $waktuAbsen,
            'status' => $validated['status'],
            'metode' => 'manual',
            'dicatat_oleh' => auth()->id(),
            'keterangan' =>
                $validated['keterangan'] ?? null,
        ]
    );

    return back()->with(
        'success',
        'Status absensi siswa berhasil diperbarui.'
    );
}

    public function tutup(
        SesiAbsensi $sesi,
        FinalisasiSesiAbsensiService $finalisasiService
    ) 
    {
        if ($sesi->status === 'selesai') {
            return back()->with(
                'error',
                'Sesi absensi sudah ditutup.'
            );
        }

        $finalisasiService->finalisasi($sesi);

        return redirect()
            ->route('absensi.sesi.show', $sesi)
            ->with(
                'success',
                'Sesi berhasil ditutup. Siswa yang belum absen otomatis menjadi alpa.'
            );
    }

    public function arsip(Request $request)
{
    $query = SesiAbsensi::with([
            'kelas.tahunAjaran',
            'pembuka',
        ])
        ->withCount('absensis')
        ->whereDate(
            'tanggal',
            '<',
            now()->subDays(7)->toDateString()
        );


    /*
    |--------------------------------------------------------------------------
    | Filter Pencarian
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = $request->search;

        $query->whereHas('kelas', function ($q) use ($search) {

            $q->where(
                'nama',
                'like',
                '%' . $search . '%'
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Filter Jenis Absensi
    |--------------------------------------------------------------------------
    */

    if ($request->filled('jenis')) {

        $query->where(
            'jenis',
            $request->jenis
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Filter Bulan
    |--------------------------------------------------------------------------
    */

    if ($request->filled('bulan')) {

        $query->whereMonth(
            'tanggal',
            $request->bulan
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Filter Tahun
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tahun')) {

        $query->whereYear(
            'tanggal',
            $request->tahun
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Ambil Data Arsip
    |--------------------------------------------------------------------------
    */

    $riwayatSesi = $query
        ->orderByDesc('tanggal')
        ->orderByDesc('id')
        ->paginate(20)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | Daftar Tahun untuk Filter
    |--------------------------------------------------------------------------
    */

    $daftarTahun = SesiAbsensi::query()
        ->selectRaw('YEAR(tanggal) as tahun')
        ->whereDate(
            'tanggal',
            '<',
            now()->subDays(7)->toDateString()
        )
        ->distinct()
        ->orderByDesc('tahun')
        ->pluck('tahun');


    return view(
        'absensi.sesi.arsip',
        compact(
            'riwayatSesi',
            'daftarTahun'
        )
    );
}
}