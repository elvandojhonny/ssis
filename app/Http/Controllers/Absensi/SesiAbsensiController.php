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
        now()->subDays(2)->toDateString()
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
        return view('absensi.sesi.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
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

    /*
     * Pastikan minimal ada satu kelas aktif.
     */
    $kelasTersedia = Kelas::where('is_active', true)->exists();

    if (! $kelasTersedia) {
        return back()
            ->withInput()
            ->with(
                'error',
                'Belum ada kelas aktif.'
            );
    }

    /*
     * Hanya boleh ada satu sesi aktif
     * untuk jenis yang sama dalam satu hari.
     */
    /*
|--------------------------------------------------------------------------
| CEK SESI HARI INI
|--------------------------------------------------------------------------
|
| Dalam satu hari hanya boleh:
|
| - 1 sesi pagi
| - 1 sesi siang
|
| Status sesi tidak diperhitungkan.
| Walaupun sesi sebelumnya sudah selesai,
| sesi dengan jenis yang sama tidak boleh dibuat lagi
| pada tanggal yang sama.
|
*/

$sudahAda = SesiAbsensi::whereDate(
        'tanggal',
        today()
    )
    ->where(
        'jenis',
        $validated['jenis']
    )
    ->exists();


if ($sudahAda) {

    $jenisLabel =
        $validated['jenis'] === 'pagi'
            ? 'pagi'
            : 'siang';

    return back()
        ->withInput()
        ->with(
            'error',
            'Absensi ' .
            $jenisLabel .
            ' sudah pernah dibuka hari ini.'
        );
}

    if ($sudahAda) {
        return back()
            ->withInput()
            ->with(
                'error',
                'Sesi absensi '
                . $validated['jenis']
                . ' sudah dibuka hari ini.'
            );
    }

    $sesi = DB::transaction(function () use ($validated) {

        return SesiAbsensi::create([

            'kelas_id' => null,

            'tingkat' => null,

            'dibuka_oleh' => auth()->id(),

            'tanggal' => today(),

            'jenis' => $validated['jenis'],

            'waktu_mulai' => $validated['waktu_mulai'],

            'batas_terlambat' =>
                $validated['batas_terlambat'],

            'waktu_selesai' =>
                $validated['waktu_selesai'],

            'status' => 'aktif',

        ]);
    });

    return redirect()
        ->route('absensi.sesi.show', $sesi)
        ->with(
            'success',
            'Sesi absensi berhasil dibuka untuk seluruh sekolah.'
        );
}

public function show(
    SesiAbsensi $sesi,
    FinalisasiSesiAbsensiService $finalisasiService
) {
    /*
    |--------------------------------------------------------------------------
    | FINALISASI SESI KEDALUWARSA
    |--------------------------------------------------------------------------
    */

    $finalisasiService->finalisasiKedaluwarsa();


    /*
    |--------------------------------------------------------------------------
    | REFRESH SESI
    |--------------------------------------------------------------------------
    */

    $sesi->refresh();


    /*
    |--------------------------------------------------------------------------
    | LOAD RELASI SESI
    |--------------------------------------------------------------------------
    */

    $sesi->load([
        'kelas.tahunAjaran',
        'pembuka',
        'absensis.siswa.user',
    ]);


    /*
    |--------------------------------------------------------------------------
    | AMBIL SEMUA SISWA AKTIF
    |--------------------------------------------------------------------------
    |
    | Satu sesi absensi berlaku untuk seluruh tingkat:
    |
    | X
    | XI
    | XII
    |
    | Pemisahan tingkat dilakukan pada Blade menggunakan tab.
    |
    */

    $daftarSiswa = Siswa::with([
        'user',
        'kelas',
    ])
    ->where('is_active', true)

    /*
    |--------------------------------------------------------------------------
    | SISWA HARUS MEMILIKI KELAS AKTIF
    |--------------------------------------------------------------------------
    */

    ->whereHas('kelas', function ($query) {

        $query->where(
            'is_active',
            true
        );

    })

    /*
    |--------------------------------------------------------------------------
    | URUTKAN BERDASARKAN TINGKAT
    |--------------------------------------------------------------------------
    */

    ->get()

    ->sortBy(function ($siswa) {

        /*
         * Supaya urutannya:
         *
         * X
         * XI
         * XII
         */

        $urutanTingkat = [
            'X'   => 1,
            'XI'  => 2,
            'XII' => 3,
        ];

        return [
            $urutanTingkat[
                $siswa->kelas?->tingkat
            ] ?? 99,

            $siswa->kelas?->nama ?? '',

            $siswa->nis ?? '',
        ];

    })

    ->values()

    /*
    |--------------------------------------------------------------------------
    | PASANG DATA ABSENSI KE MASING-MASING SISWA
    |--------------------------------------------------------------------------
    */

    ->map(function ($siswa) use ($sesi) {

        $siswa->data_absensi =
            $sesi->absensis->firstWhere(
                'siswa_id',
                $siswa->id
            );

        return $siswa;

    });


    /*
    |--------------------------------------------------------------------------
    | STATISTIK
    |--------------------------------------------------------------------------
    */

    $totalSiswa =
        $daftarSiswa->count();


    $hadir =
        $daftarSiswa
            ->filter(
                fn ($siswa) =>
                    $siswa
                        ->data_absensi
                        ?->status
                    === 'hadir'
            )
            ->count();


    $terlambat =
        $daftarSiswa
            ->filter(
                fn ($siswa) =>
                    $siswa
                        ->data_absensi
                        ?->status
                    === 'terlambat'
            )
            ->count();


    $belumAbsen =
        $daftarSiswa
            ->filter(
                fn ($siswa) =>
                    $siswa->data_absensi
                    === null
            )
            ->count();


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN HALAMAN
    |--------------------------------------------------------------------------
    */

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
    $siswa->loadMissing('kelas');

    abort_unless(
        $siswa->kelas,
        403,
        'Siswa belum memiliki kelas.'
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
            now()->subDays(2)->toDateString()
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