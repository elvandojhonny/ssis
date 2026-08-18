<?php

namespace App\Http\Controllers\CBT;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\Kelas;
use App\Models\Ujian;
use App\Models\PengerjaanUjian;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


use PhpOffice\PhpSpreadsheet\Cell\DataType;

use Illuminate\Support\Facades\DB;

use App\Services\CBT\FinalisasiUjianService;

class UjianController extends Controller
{
    /*
     * Daftar seluruh ujian.
     */
    /*
|--------------------------------------------------------------------------
| Daftar Seluruh Ujian
|--------------------------------------------------------------------------
*/
public function index(Request $request)
{
    app(FinalisasiUjianService::class)->handle();

    /*
    |--------------------------------------------------------------------------
    | Query Dasar
    |--------------------------------------------------------------------------
    */

    $query = Ujian::with([
        'bankSoal.soals',
        'kelas',
    ])
    ->whereIn('status', [
        'draft',
        'dipublikasi',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Filter Tingkat Kelas
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tingkat')) {

        $query->whereHas('kelas', function ($kelas) use ($request) {

            $kelas->where(
                'tingkat',
                $request->tingkat
            );

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Ambil Data
    |--------------------------------------------------------------------------
    */

    $ujians = $query
        ->orderByDesc('waktu_mulai')
        ->paginate(9)
        ->withQueryString();

    return view(
        'cbt.ujian.index',
        compact('ujians')
    );
}

/*
|--------------------------------------------------------------------------
| Form Membuat Ujian
|--------------------------------------------------------------------------
*/
public function create()
{
    /*
    |--------------------------------------------------------------------------
    | Tahun Ajaran Aktif
    |--------------------------------------------------------------------------
    */

    $tahunAjaran = TahunAjaran::query()
        ->where('is_active', true)
        ->first();


    /*
    |--------------------------------------------------------------------------
    | Kelas Aktif
    |--------------------------------------------------------------------------
    |
    | Hanya kelas dari tahun ajaran aktif
    | yang boleh dipilih untuk ujian.
    |
    */

    $kelas = Kelas::with(
        'tahunAjaran'
    )
        ->where(
            'is_active',
            true
        )
        ->whereHas(
            'tahunAjaran',
            function ($query) {

                $query->where(
                    'is_active',
                    true
                );

            }
        )
        ->orderByRaw("
            CASE tingkat
                WHEN 'X' THEN 1
                WHEN 'XI' THEN 2
                WHEN 'XII' THEN 3
                ELSE 4
            END
        ")
        ->orderBy(
            'nama'
        )
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Tampilkan halaman
    |--------------------------------------------------------------------------
    |
    | Bank Soal TIDAK lagi dikirim ke halaman.
    |
    | Operator akan mencari Bank Soal
    | berdasarkan kode melalui endpoint
    | cariBankSoal().
    |
    */

    return view(
        'cbt.ujian.create',
        compact(
            'kelas',
            'tahunAjaran'
        )
    );
}

/**
 * Cari Bank Soal berdasarkan kode.
 */
public function cariBankSoal(Request $request)
{
    $validated = $request->validate([
        'kode' => [
            'required',
            'string',
            'max:20',
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | Tahun Ajaran Aktif
    |--------------------------------------------------------------------------
    */

    $tahunAjaran = TahunAjaran::query()
        ->where('is_active', true)
        ->first();


    if (! $tahunAjaran) {

        return response()->json([
            'success' => false,
            'message' =>
                'Belum ada tahun ajaran yang aktif.',
        ], 422);

    }


    /*
    |--------------------------------------------------------------------------
    | Normalisasi Kode
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | mtk-48291
    |
    | akan dicari sebagai:
    |
    | MTK-48291
    |
    */

    $kode = strtoupper(
        trim(
            $validated['kode']
        )
    );


    /*
    |--------------------------------------------------------------------------
    | Cari Bank Soal
    |--------------------------------------------------------------------------
    */

    $bankSoal = BankSoal::query()

        ->where(
            'kode',
            $kode
        )

        ->where(
            'tahun_ajaran_id',
            $tahunAjaran->id
        )

        ->where(
            'status',
            'siap'
        )

        ->where(
            'is_archived',
            false
        )

        ->with([
            'guru',
            'tahunAjaran',
        ])

        ->withCount(
            'soals'
        )

        ->first();


    /*
    |--------------------------------------------------------------------------
    | Tidak ditemukan
    |--------------------------------------------------------------------------
    */

    if (! $bankSoal) {

        return response()->json([
            'success' => false,
            'message' =>
                'Bank soal dengan kode tersebut tidak ditemukan atau tidak tersedia pada tahun ajaran aktif.',
        ], 404);

    }


    /*
    |--------------------------------------------------------------------------
    | Berhasil
    |--------------------------------------------------------------------------
    */

    return response()->json([
        'success' => true,

        'data' => [

            'id' =>
                $bankSoal->id,

            'kode' =>
                $bankSoal->kode,

            'judul' =>
                $bankSoal->judul,

            'mata_pelajaran' =>
                $bankSoal->mata_pelajaran,

            'tingkat' =>
                $bankSoal->tingkat,

            'jumlah_soal' =>
                $bankSoal->soals_count,

            'guru' =>
                $bankSoal->guru?->nama,

            'tahun_ajaran' =>
                $bankSoal->tahunAjaran?->nama,

        ],
    ]);
}


    /*
     * Simpan ujian sebagai draft.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_soal_id' => [
                'required',
                'exists:bank_soals,id',
            ],

            'kelas_id' => [
                'required',
                'exists:kelas,id',
            ],

            'judul' => [
                'required',
                'string',
                'max:255',
            ],

            'deskripsi' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'waktu_mulai' => [
                'required',
                'date',
            ],

            'waktu_selesai' => [
                'required',
                'date',
                'after:waktu_mulai',
            ],

            'durasi_menit' => [
                'required',
                'integer',
                'min:1',
                'max:600',
            ],

            'acak_soal' => [
                'required',
                'boolean',
            ],

        ]);

        /*
         * Jangan hanya percaya exists.
         * Pastikan Bank Soal memang siap.
         */
        /*
|--------------------------------------------------------------------------
| Tahun Ajaran Aktif
|--------------------------------------------------------------------------
*/

$tahunAjaran = TahunAjaran::query()
    ->where('is_active', true)
    ->first();


if (! $tahunAjaran) {

    return back()
        ->withInput()
        ->with(
            'error',
            'Belum ada tahun ajaran yang aktif.'
        );
}


/*
|--------------------------------------------------------------------------
| Pastikan Bank Soal sesuai Tahun Ajaran Aktif
|--------------------------------------------------------------------------
*/

$bankSoal = BankSoal::query()
    ->whereKey(
        $validated['bank_soal_id']
    )
    ->where(
        'tahun_ajaran_id',
        $tahunAjaran->id
    )
    ->where(
        'status',
        'siap'
    )
    ->where(
        'is_archived',
        false
    )
    ->first();


if (! $bankSoal) {

    return back()
        ->withInput()
        ->with(
            'error',
            'Bank soal tidak tersedia untuk tahun ajaran aktif.'
        );
}

        /*
         * Pastikan kelas masih aktif.
         */
        $kelas = Kelas::query()
        ->whereKey($validated['kelas_id'])
        ->where('is_active', true)
        ->whereHas('tahunAjaran', function ($query) {
            $query->where('is_active', true);
        })
        ->firstOrFail();

        $ujian = Ujian::create([
            'bank_soal_id' =>
                $bankSoal->id,

            'kelas_id' =>
                $kelas->id,

            'dibuat_oleh' =>
                auth()->id(),

            'judul' =>
                $validated['judul'],

            'deskripsi' =>
                $validated['deskripsi']
                ?? null,

            'waktu_mulai' =>
                $validated['waktu_mulai'],

            'waktu_selesai' =>
                $validated['waktu_selesai'],

            'durasi_menit' =>
                $validated['durasi_menit'],

            'acak_soal' =>
                (bool) $validated['acak_soal'],

            /*
             * Belum langsung tampil ke siswa.
             */
            'status' => 'draft',
        ]);

        return redirect()
            ->route(
                'cbt.ujian.show',
                $ujian
            )
            ->with(
                'success',
                'Ujian berhasil dibuat sebagai draft.'
            );
    }


    /*
     * Detail ujian.
     */
    public function show(Ujian $ujian)
    {
        $ujian->load([
            'bankSoal.guru',
            'bankSoal.soals',
            'kelas.tahunAjaran',
            'pembuat',
        ]);

        return view(
            'cbt.ujian.show',
            compact('ujian')
        );
    }

/*
|--------------------------------------------------------------------------
| Form Edit Ujian
|--------------------------------------------------------------------------
*/
public function edit(Ujian $ujian)
{
    /*
    |--------------------------------------------------------------------------
    | Pastikan Ujian Memiliki Bank Soal
    |--------------------------------------------------------------------------
    */

    $ujian->load([
        'bankSoal',
        'kelas',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Tahun Ajaran Aktif
    |--------------------------------------------------------------------------
    */

    $tahunAjaran = TahunAjaran::query()
        ->where(
            'is_active',
            true
        )
        ->first();


    /*
    |--------------------------------------------------------------------------
    | Kelas Aktif
    |--------------------------------------------------------------------------
    */

    $kelas = Kelas::with(
        'tahunAjaran'
    )
        ->where(
            'is_active',
            true
        )
        ->whereHas(
            'tahunAjaran',
            function ($query) {

                $query->where(
                    'is_active',
                    true
                );

            }
        )
        ->orderByRaw("
            CASE tingkat
                WHEN 'X' THEN 1
                WHEN 'XI' THEN 2
                WHEN 'XII' THEN 3
                ELSE 4
            END
        ")
        ->orderBy(
            'nama'
        )
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Tampilkan halaman Edit
    |--------------------------------------------------------------------------
    |
    | Tidak lagi mengirim semua Bank Soal.
    |
    | Bank Soal yang sedang digunakan oleh ujian
    | sudah tersedia melalui $ujian->bankSoal.
    |
    | Jika ingin mengganti Bank Soal, operator
    | dapat mencari berdasarkan kode melalui
    | endpoint cariBankSoal().
    |
    */

    return view(
        'cbt.ujian.edit',
        compact(
            'ujian',
            'kelas',
            'tahunAjaran'
        )
    );
}

/*
|--------------------------------------------------------------------------
| Update Ujian
|--------------------------------------------------------------------------
*/
/**
 * Update ujian.
 */
public function update(
    Request $request,
    Ujian $ujian
) {
    /*
    |--------------------------------------------------------------------------
    | Hanya Draft yang boleh diedit
    |--------------------------------------------------------------------------
    */

    if (
        $ujian->status !== 'draft'
    ) {

        return redirect()
            ->route(
                'cbt.ujian.show',
                $ujian
            )
            ->with(
                'error',
                'Ujian yang sudah dipublikasikan tidak dapat diedit.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Validasi Input
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        'bank_soal_id' => [
            'required',
            'exists:bank_soals,id',
        ],

        'kelas_id' => [
            'required',
            'exists:kelas,id',
        ],

        'judul' => [
            'required',
            'string',
            'max:255',
        ],

        'deskripsi' => [
            'nullable',
            'string',
            'max:2000',
        ],

        'waktu_mulai' => [
            'required',
            'date',
        ],

        'waktu_selesai' => [
            'required',
            'date',
            'after:waktu_mulai',
        ],

        'durasi_menit' => [
            'required',
            'integer',
            'min:1',
            'max:600',
        ],

        'acak_soal' => [
            'nullable',
            'boolean',
        ],

    ]);


    /*
    |--------------------------------------------------------------------------
    | Tahun Ajaran Aktif
    |--------------------------------------------------------------------------
    */

    $tahunAjaran = TahunAjaran::query()
        ->where(
            'is_active',
            true
        )
        ->first();


    if (! $tahunAjaran) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Belum ada tahun ajaran yang aktif.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Validasi Bank Soal
    |--------------------------------------------------------------------------
    |
    | Bank Soal harus:
    | - berasal dari tahun ajaran aktif
    | - status siap
    | - tidak diarsipkan
    |
    */

    $bankSoal = BankSoal::query()

        ->whereKey(
            $validated['bank_soal_id']
        )

        ->where(
            'tahun_ajaran_id',
            $tahunAjaran->id
        )

        ->where(
            'status',
            'siap'
        )

        ->where(
            'is_archived',
            false
        )

        ->first();


    if (! $bankSoal) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Bank soal tidak tersedia untuk tahun ajaran aktif.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Validasi Kelas
    |--------------------------------------------------------------------------
    */

    $kelas = Kelas::query()

        ->whereKey(
            $validated['kelas_id']
        )

        ->where(
            'is_active',
            true
        )

        ->whereHas(
            'tahunAjaran',
            function ($query) {

                $query->where(
                    'is_active',
                    true
                );

            }
        )

        ->first();


    if (! $kelas) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Kelas tidak tersedia untuk tahun ajaran aktif.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Update Ujian
    |--------------------------------------------------------------------------
    */

    $ujian->update([

        'bank_soal_id' =>
            $bankSoal->id,

        'kelas_id' =>
            $kelas->id,

        'judul' =>
            $validated['judul'],

        'deskripsi' =>
            $validated['deskripsi']
            ?? null,

        'waktu_mulai' =>
            $validated['waktu_mulai'],

        'waktu_selesai' =>
            $validated['waktu_selesai'],

        'durasi_menit' =>
            $validated['durasi_menit'],

        'acak_soal' =>
            (bool) (
                $validated['acak_soal']
                ?? false
            ),

    ]);


    /*
    |--------------------------------------------------------------------------
    | Selesai
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'cbt.ujian.show',
            $ujian
        )
        ->with(
            'success',
            'Ujian berhasil diperbarui.'
        );
}

    public function publish(Ujian $ujian)
{
    if ($ujian->status !== 'draft') {

        return back()->with(
            'error',
            'Ujian ini sudah dipublikasikan atau telah selesai.'
        );
    }


    $ujian->load([
        'bankSoal.soals',
        'kelas',
    ]);


    if ($ujian->bankSoal->status !== 'siap') {

        return back()->with(
            'error',
            'Bank soal belum siap digunakan.'
        );
    }


    if ($ujian->bankSoal->soals->isEmpty()) {

        return back()->with(
            'error',
            'Bank soal tidak memiliki soal.'
        );
    }


    if (! $ujian->kelas->is_active) {

        return back()->with(
            'error',
            'Kelas tujuan sudah tidak aktif.'
        );
    }


    $ujian->update([

        'status' => 'dipublikasi',

        'token' =>
            $ujian->token
            ?? Ujian::generateUniqueToken(),

    ]);


    return redirect()
        ->route(
            'cbt.ujian.show',
            $ujian
        )
        ->with(
            'success',
            'Ujian berhasil dipublikasikan.'
        );
}

/*
|--------------------------------------------------------------------------
| Rekap Hasil Ujian
|--------------------------------------------------------------------------
*/
public function rekap(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Query Dasar
    |--------------------------------------------------------------------------
    */

    $query = Ujian::with([
            'kelas',
            'bankSoal',
        ])
        ->whereIn('status', [
            'dipublikasi',
            'selesai',
        ])
        ->where(
            'waktu_selesai',
            '>=',
            now()->subDays(7)
        )
        ->withCount([
            'pengerjaans',

            'pengerjaans as selesai_count' => function ($query) {
                $query->where('status', 'selesai');
            },
        ]);


    /*
    |--------------------------------------------------------------------------
    | Filter Tingkat Kelas
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tingkat')) {

        $query->whereHas('kelas', function ($kelas) use ($request) {

            $kelas->where(
                'tingkat',
                $request->tingkat
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Ambil Data
    |--------------------------------------------------------------------------
    */

    $ujians = $query
        ->orderByDesc('waktu_mulai')
        ->paginate(9)
        ->withQueryString();


    return view(
        'cbt.rekap.index',
        compact('ujians')
    );
}


/*
|--------------------------------------------------------------------------
| Arsip Ujian
|--------------------------------------------------------------------------
*/
public function arsip(Request $request)
{

app(FinalisasiUjianService::class)->handle();
    /*
    |--------------------------------------------------------------------------
    | Query Dasar
    |--------------------------------------------------------------------------
    */

    $query = Ujian::with([
        'bankSoal.soals',
        'kelas',
    ])
    ->where('status', 'selesai');

    /*
    |--------------------------------------------------------------------------
    | Filter Tingkat Kelas
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tingkat')) {

        $query->whereHas('kelas', function ($kelas) use ($request) {

            $kelas->where(
                'tingkat',
                $request->tingkat
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Pencarian
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where(
                'judul',
                'like',
                '%' . $search . '%'
            )

            ->orWhereHas(
                'bankSoal',
                function ($bankSoal) use ($search) {

                    $bankSoal->where(
                        'mata_pelajaran',
                        'like',
                        '%' . $search . '%'
                    );

                }
            )

            ->orWhereHas(
                'kelas',
                function ($kelas) use ($search) {

                    $kelas->where(
                        'nama',
                        'like',
                        '%' . $search . '%'
                    );

                }
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Filter Tahun
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tahun')) {

        $query->whereYear(
            'waktu_selesai',
            $request->tahun
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Ambil Data
    |--------------------------------------------------------------------------
    */

    $ujians = $query
        ->orderByDesc('waktu_selesai')
        ->paginate(9)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | Tahun yang Tersedia
    |--------------------------------------------------------------------------
    */

    $daftarTahun = Ujian::query()
        ->where(
            'waktu_selesai',
            '<',
            now()->subDays(7)
        )
        ->selectRaw(
            'YEAR(waktu_selesai) as tahun'
        )
        ->distinct()
        ->orderByDesc('tahun')
        ->pluck('tahun');


    return view(
        'cbt.ujian.arsip',
        compact(
            'ujians',
            'daftarTahun'
        )
    );
}

/*
|--------------------------------------------------------------------------
| Arsip Rekap Hasil Ujian
|--------------------------------------------------------------------------
*/
public function rekapArsip(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Query Dasar
    |--------------------------------------------------------------------------
    */

    $query = Ujian::with([
            'kelas',
            'bankSoal',
        ])
        ->whereIn('status', [
            'dipublikasi',
            'selesai',
        ])
        ->where(
            'waktu_selesai',
            '<',
            now()->subDays(7)
        )
        ->withCount([
            'pengerjaans',

            'pengerjaans as selesai_count' => function ($query) {
                $query->where('status', 'selesai');
            },
        ]);


    /*
    |--------------------------------------------------------------------------
    | Filter Tingkat Kelas
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tingkat')) {

        $query->whereHas('kelas', function ($kelas) use ($request) {

            $kelas->where(
                'tingkat',
                $request->tingkat
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Pencarian
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where(
                'judul',
                'like',
                '%' . $search . '%'
            )

            ->orWhereHas(
                'kelas',
                function ($kelas) use ($search) {

                    $kelas->where(
                        'nama',
                        'like',
                        '%' . $search . '%'
                    );

                }
            )

            ->orWhereHas(
                'bankSoal',
                function ($bankSoal) use ($search) {

                    $bankSoal->where(
                        'mata_pelajaran',
                        'like',
                        '%' . $search . '%'
                    );

                }
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Filter Tahun
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tahun')) {

        $query->whereYear(
            'waktu_selesai',
            $request->tahun
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Data Arsip
    |--------------------------------------------------------------------------
    */

    $ujians = $query
        ->orderByDesc('waktu_selesai')
        ->paginate(9)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | Daftar Tahun
    |--------------------------------------------------------------------------
    */

    $daftarTahun = Ujian::query()
    ->whereIn('status', [
        'dipublikasi',
        'selesai',
    ])
    ->where(
        'waktu_selesai',
        '<',
        now()->subDays(7)
    )
    ->selectRaw('YEAR(waktu_selesai) as tahun')
    ->distinct()
    ->orderByDesc('tahun')
    ->pluck('tahun');

    $daftarTahun = Ujian::query()
        ->whereIn('status', [
            'dipublikasi',
            'selesai',
        ])
        ->where(
            'waktu_selesai',
            '<',
            now()->subDays(7)
        )
        ->selectRaw(
            'YEAR(waktu_selesai) as tahun'
        )
        ->distinct()
        ->orderByDesc('tahun')
        ->pluck('tahun');


    return view(
        'cbt.rekap.arsip',
        compact(
            'ujians',
            'daftarTahun'
        )
    );
}


/*
|--------------------------------------------------------------------------
| Detail Rekap Hasil Ujian
|--------------------------------------------------------------------------
*/
public function rekapShow(Ujian $ujian)
{

app(FinalisasiUjianService::class)->handle();
    $ujian->load([
        'bankSoal.guru',
        'kelas.tahunAjaran',

        'pengerjaans' => function ($query) {
            $query
                ->with([
                    'siswa.user',
                ])
                ->orderByDesc('nilai');
        },
    ]);

    /*
     * Ambil seluruh siswa yang berada
     * pada kelas tujuan ujian.
     */
    $siswas = $ujian->kelas
        ->siswa()
        ->with('user')
        ->where('is_active', true)
        ->orderBy('nama')
        ->get();


    /*
     * Index pengerjaan berdasarkan siswa_id
     * agar mudah dicocokkan di Blade.
     */
    $pengerjaanPerSiswa = $ujian
        ->pengerjaans
        ->keyBy('siswa_id');


    /*
     * Statistik rekap.
     */
    $totalSiswa = $siswas->count();

    $sudahMengerjakan = $ujian
        ->pengerjaans
        ->where('status', 'selesai')
        ->count();

    $sedangMengerjakan = $ujian
        ->pengerjaans
        ->where('status', 'mengerjakan')
        ->count();

    /*
    * Peserta yang sedang diblokir
    * karena pelanggaran ujian.
    */
    $diblokir = $ujian
        ->pengerjaans
        ->where('status', 'diblokir')
        ->count();

    /*
    * Benar-benar belum pernah
    * memulai pengerjaan ujian.
    */
    $belumMengerjakan =
        max(
            0,
            $totalSiswa
            - $sudahMengerjakan
            - $sedangMengerjakan
            - $diblokir
        );


    /*
     * Hitung rata-rata hanya dari
     * pengerjaan yang sudah selesai.
     */
    $rataRata = $ujian
        ->pengerjaans
        ->where('status', 'selesai')
        ->avg('nilai');


    return view(
        'cbt.rekap.show',
        compact(
            'ujian',
            'siswas',
            'pengerjaanPerSiswa',
            'totalSiswa',
            'sudahMengerjakan',
            'sedangMengerjakan',
            'diblokir',
            'belumMengerjakan',
            'rataRata'
        )
    );
}

/*
|--------------------------------------------------------------------------
| Detail Hasil Peserta
|--------------------------------------------------------------------------
*/
public function rekapPeserta(
    Ujian $ujian,
    PengerjaanUjian $pengerjaan
) {
    /*
     * Pastikan pengerjaan memang
     * berasal dari ujian yang dibuka.
     */
    abort_unless(
        (int) $pengerjaan->ujian_id ===
        (int) $ujian->id,
        404
    );


    /*
     * Hanya pengerjaan yang sudah selesai
     * yang dapat dilihat hasilnya.
     */
    if ($pengerjaan->status !== 'selesai') {

        return redirect()
            ->route(
                'cbt.rekap.show',
                $ujian
            )
            ->with(
                'error',
                'Peserta belum menyelesaikan ujian.'
            );
    }


    /*
     * Load seluruh data yang dibutuhkan.
     */
    $pengerjaan->load([
        'siswa.user',

        'ujian.bankSoal.soals' => function ($query) {
            $query->orderBy('nomor');
        },

        'ujian.kelas',

        'jawabans.soal',
    ]);


    /*
     * Index jawaban berdasarkan soal_id.
     */
    $jawabanPerSoal = $pengerjaan
        ->jawabans
        ->keyBy('soal_id');


    /*
     * Statistik jawaban.
     */
    $totalSoal = $pengerjaan
        ->ujian
        ->bankSoal
        ->soals
        ->count();


    $jawabanBenar = $pengerjaan
        ->jawabans
        ->where('is_benar', true)
        ->count();


    $jawabanSalah = $pengerjaan
        ->jawabans
        ->where('is_benar', false)
        ->count();


    $tidakDijawab = max(
        0,
        $totalSoal
        - $pengerjaan->jawabans->count()
    );


    return view(
        'cbt.rekap.peserta',
        compact(
            'ujian',
            'pengerjaan',
            'jawabanPerSoal',
            'totalSoal',
            'jawabanBenar',
            'jawabanSalah',
            'tidakDijawab'
        )
    );
}

/*
|--------------------------------------------------------------------------
| Export Rekap Hasil Ujian
|--------------------------------------------------------------------------
*/
public function exportRekap(
    Ujian $ujian
): BinaryFileResponse {

    /*
     * Load data ujian.
     */
    $ujian->load([
        'bankSoal.guru',
        'kelas.tahunAjaran',

        'pengerjaans' => function ($query) {

            $query->with([
                'siswa',
            ]);

        },
    ]);


    /*
     * Ambil seluruh siswa aktif
     * dari kelas tujuan ujian.
     */
    $siswas = $ujian
        ->kelas
        ->siswa()
        ->where(
            'is_active',
            true
        )
        ->orderBy('nama')
        ->get();


    /*
     * Kelompokkan pengerjaan
     * berdasarkan siswa.
     */
    $pengerjaanPerSiswa =
        $ujian
            ->pengerjaans
            ->keyBy('siswa_id');

/*
|--------------------------------------------------------------------------
| SPREADSHEET
|--------------------------------------------------------------------------
*/

$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Rekap Ujian');


/*
|--------------------------------------------------------------------------
| PAGE SETUP
|--------------------------------------------------------------------------
*/

$sheet->getPageSetup()
    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

$sheet->getPageSetup()
    ->setPaperSize(PageSetup::PAPERSIZE_A4);

$sheet->getPageSetup()
    ->setFitToWidth(1);

$sheet->getPageSetup()
    ->setFitToHeight(0);

$sheet->getPageMargins()->setTop(0.25);
$sheet->getPageMargins()->setBottom(0.25);
$sheet->getPageMargins()->setLeft(0.20);
$sheet->getPageMargins()->setRight(0.20);


/*
|--------------------------------------------------------------------------
| DEFAULT FONT
|--------------------------------------------------------------------------
*/

$spreadsheet
    ->getDefaultStyle()
    ->getFont()
    ->setName('Times New Roman')
    ->setSize(11);


/*
|--------------------------------------------------------------------------
| COLUMN WIDTH
|--------------------------------------------------------------------------
*/

$sheet->getColumnDimension('A')->setWidth(7);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(36);
$sheet->getColumnDimension('D')->setWidth(18);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(10);
$sheet->getColumnDimension('H')->setWidth(34);


/*
|--------------------------------------------------------------------------
| ROW HEIGHT
|--------------------------------------------------------------------------
*/

for ($i = 1; $i <= 18; $i++) {

    $sheet->getRowDimension($i)->setRowHeight(18);

}

$sheet->getRowDimension(3)->setRowHeight(30);

$sheet->getRowDimension(8)->setRowHeight(4);

$sheet->getRowDimension(9)->setRowHeight(2);

$sheet->getRowDimension(10)->setRowHeight(24);

$sheet->getRowDimension(11)->setRowHeight(20);


/*
|--------------------------------------------------------------------------
| MERGE
|--------------------------------------------------------------------------
*/

$sheet->mergeCells('A1:H1');
$sheet->mergeCells('A2:H2');
$sheet->mergeCells('A3:H3');
$sheet->mergeCells('A4:H4');
$sheet->mergeCells('A5:H5');
$sheet->mergeCells('A6:H6');
$sheet->mergeCells('A7:H7');

$sheet->mergeCells('A10:H10');
$sheet->mergeCells('A11:H11');


/*
|--------------------------------------------------------------------------
| LOGO KIRI
|--------------------------------------------------------------------------
*/

$logoProvinsi =
    public_path('images/kaltara.png');

if (file_exists($logoProvinsi)) {

    $logo = new Drawing();

    $logo->setPath($logoProvinsi);

    $logo->setCoordinates('A1');

    $logo->setHeight(78);

    $logo->setOffsetX(3);

    $logo->setOffsetY(3);

    $logo->setWorksheet($sheet);

}


/*
|--------------------------------------------------------------------------
| LOGO KANAN
|--------------------------------------------------------------------------
*/

$logoSekolah =
    public_path('images/logo SMAN 6.png');

if (file_exists($logoSekolah)) {

    $logo = new Drawing();

    $logo->setPath($logoSekolah);

    $logo->setCoordinates('H1');

    $logo->setHeight(78);

    $logo->setOffsetX(40);

    $logo->setOffsetY(3);

    $logo->setWorksheet($sheet);

}


/*
|--------------------------------------------------------------------------
| KOP SURAT
|--------------------------------------------------------------------------
*/

$sheet->setCellValue(
    'A1',
    'PEMERINTAH PROVINSI KALIMANTAN UTARA'
);

$sheet->setCellValue(
    'A2',
    'DINAS PENDIDIKAN DAN KEBUDAYAAN'
);

$sheet->setCellValue(
    'A3',
    'SMA NEGERI 6 MALINAU'
);

$sheet->setCellValue(
    'A4',
    'NSS : 30.1.16.07.09.009      NPSN : 30405857'
);

$sheet->setCellValue(
    'A5',
    'Akreditasi "C" No.1337/BAN-SM/SK/2019'
);

$sheet->setCellValue(
    'A6',
    'Jl. Pendidikan RT.001 Desa Mahak Baru Kecamatan Sungai Boh'
);

$sheet->setCellValue(
    'A7',
    'Email : sman6malinau2@gmail.com'
);


/*
|--------------------------------------------------------------------------
| STYLE KOP
|--------------------------------------------------------------------------
*/

$sheet->getStyle('A1:H7')
    ->getAlignment()
    ->setHorizontal(
        Alignment::HORIZONTAL_CENTER
    );

$sheet->getStyle('A1:H7')
    ->getAlignment()
    ->setVertical(
        Alignment::VERTICAL_CENTER
    );

$sheet->getStyle('B1')
    ->getFont()
    ->setBold(true)
    ->setSize(15);

$sheet->getStyle('B2')
    ->getFont()
    ->setBold(true)
    ->setSize(13);

$sheet->getStyle('B3')
    ->getFont()
    ->setBold(true)
    ->setSize(21);

$sheet->getStyle('B4:B7')
    ->getFont()
    ->setSize(10);


/*
|--------------------------------------------------------------------------
| GARIS
|--------------------------------------------------------------------------
*/

$sheet->getStyle('A8:H8')
    ->getBorders()
    ->getBottom()
    ->setBorderStyle(
        Border::BORDER_THICK
    );

$sheet->getStyle('A9:H9')
    ->getBorders()
    ->getBottom()
    ->setBorderStyle(
        Border::BORDER_THIN
    );


/*
|--------------------------------------------------------------------------
| JUDUL LAPORAN
|--------------------------------------------------------------------------
*/

$sheet->setCellValue(
    'A10',
    'REKAP HASIL UJIAN CBT'
);

$sheet->setCellValue(
    'A11',
    strtoupper($ujian->judul)
);

$sheet->getStyle('A10:H10')
    ->getAlignment()
    ->setHorizontal(
        Alignment::HORIZONTAL_CENTER
    );

$sheet->getStyle('A11:H11')
    ->getAlignment()
    ->setHorizontal(
        Alignment::HORIZONTAL_CENTER
    );

$sheet->getStyle('A10')
    ->getFont()
    ->setBold(true)
    ->setSize(15);

$sheet->getStyle('A11')
    ->getFont()
    ->setBold(true)
    ->setSize(12);


/*
|--------------------------------------------------------------------------
| INFORMASI UJIAN
|--------------------------------------------------------------------------
*/

$sheet->setCellValue('A13','Mata Pelajaran');
$sheet->setCellValue('B13',':');
$sheet->setCellValue('C13',$ujian->bankSoal->mata_pelajaran ?? '-');

$sheet->setCellValue('A14','Guru');
$sheet->setCellValue('B14',':');
$sheet->setCellValue('C14',$ujian->bankSoal->guru->nama ?? '-');

$sheet->setCellValue('A15','Kelas');
$sheet->setCellValue('B15',':');
$sheet->setCellValue('C15',$ujian->kelas->nama ?? '-');

$sheet->setCellValue('E13','Tanggal');
$sheet->setCellValue('F13',':');
$sheet->setCellValue(
    'G13',
    optional($ujian->waktu_mulai)->format('d-m-Y')
);

$sheet->setCellValue('E14','Jam');
$sheet->setCellValue('F14',':');
$sheet->setCellValue(
    'G14',
    optional($ujian->waktu_mulai)->format('H:i')
    .' - '.
    optional($ujian->waktu_selesai)->format('H:i')
);

$sheet->setCellValue('E15','Durasi');
$sheet->setCellValue('F15',':');
$sheet->setCellValue(
    'G15',
    $ujian->durasi_menit.' Menit'
);

/*
|--------------------------------------------------------------------------
| HEADER TABEL
|--------------------------------------------------------------------------
*/

$headerRow = 18;

$sheet->getRowDimension($headerRow)->setRowHeight(28);

$headers = [
    'No',
    'NIS',
    'Nama Siswa',
    'Status',
    'Mulai',
    'Selesai',
    'Nilai',
    'Keterangan',
];

foreach ($headers as $i => $judul) {

    $kolom = chr(65 + $i);

    $sheet->setCellValue(
        $kolom.$headerRow,
        $judul
    );

}


/*
|--------------------------------------------------------------------------
| STYLE HEADER
|--------------------------------------------------------------------------
*/

$sheet->getStyle("A{$headerRow}:H{$headerRow}")
    ->getFont()
    ->setBold(true)
    ->setSize(11);

$sheet->getStyle("A{$headerRow}:H{$headerRow}")
    ->getAlignment()
    ->setHorizontal(
        Alignment::HORIZONTAL_CENTER
    );

$sheet->getStyle("A{$headerRow}:H{$headerRow}")
    ->getAlignment()
    ->setVertical(
        Alignment::VERTICAL_CENTER
    );

$sheet->getStyle("A{$headerRow}:H{$headerRow}")
    ->getFill()
    ->setFillType(
        \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
    );

$sheet->getStyle("A{$headerRow}:H{$headerRow}")
    ->getFill()
    ->getStartColor()
    ->setRGB('1F4E78');

$sheet->getStyle("A{$headerRow}:H{$headerRow}")
    ->getFont()
    ->getColor()
    ->setRGB('FFFFFF');


/*
|--------------------------------------------------------------------------
| DATA
|--------------------------------------------------------------------------
*/

$row = $headerRow + 1;

$nomor = 1;

$totalNilai = 0;

$totalSelesai = 0;

$totalBelum = 0;

$totalMengerjakan = 0;

foreach ($siswas as $siswa) {

    $pengerjaan =
        $pengerjaanPerSiswa
            ->get($siswa->id);

    if (!$pengerjaan) {

        $status = 'Belum';

        $ket = 'Belum mengikuti';

        $nilai = '-';

        $totalBelum++;

    } elseif ($pengerjaan->status == 'mengerjakan') {

        $status = 'Mengerjakan';

        $ket = 'Sedang ujian';

        $nilai = '-';

        $totalMengerjakan++;

    } else {

        $status = 'Selesai';

        $ket = 'Selesai';

        $nilai = $pengerjaan->nilai;

        $totalSelesai++;

        $totalNilai += $nilai;

    }


    $sheet->setCellValue(
        'A'.$row,
        $nomor
    );

    $sheet->setCellValueExplicit(
        'B'.$row,
        (string)$siswa->nis,
        DataType::TYPE_STRING
    );

    $sheet->setCellValue(
        'C'.$row,
        $siswa->nama
    );

    $sheet->setCellValue(
        'D'.$row,
        $status
    );

    $sheet->setCellValue(
        'E'.$row,
        optional(
            $pengerjaan?->waktu_mulai
        )->format('d/m/Y H:i')
        ?? '-'
    );

    $sheet->setCellValue(
        'F'.$row,
        optional(
            $pengerjaan?->waktu_selesai
        )->format('d/m/Y H:i')
        ?? '-'
    );

    $sheet->setCellValue(
        'G'.$row,
        $nilai
    );

    $sheet->setCellValue(
        'H'.$row,
        $ket
    );


    /*
    |--------------------------------------------------------------------------
    | ALIGN
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle("A{$row}:H{$row}")
        ->getAlignment()
        ->setVertical(
            Alignment::VERTICAL_CENTER
        );

    $sheet->getStyle("A{$row}")
        ->getAlignment()
        ->setHorizontal(
            Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle("B{$row}")
        ->getAlignment()
        ->setHorizontal(
            Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle("D{$row}:G{$row}")
        ->getAlignment()
        ->setHorizontal(
            Alignment::HORIZONTAL_CENTER
        );


    /*
    |--------------------------------------------------------------------------
    | WARNA STATUS
    |--------------------------------------------------------------------------
    */

    if ($status == 'Selesai') {

        $sheet->getStyle("D{$row}")
            ->getFill()
            ->setFillType(
                \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
            );

        $sheet->getStyle("D{$row}")
            ->getFill()
            ->getStartColor()
            ->setRGB('C6EFCE');

    }

    if ($status == 'Belum') {

        $sheet->getStyle("D{$row}")
            ->getFill()
            ->setFillType(
                \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
            );

        $sheet->getStyle("D{$row}")
            ->getFill()
            ->getStartColor()
            ->setRGB('F8CBAD');

    }

    if ($status == 'Mengerjakan') {

        $sheet->getStyle("D{$row}")
            ->getFill()
            ->setFillType(
                \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
            );

        $sheet->getStyle("D{$row}")
            ->getFill()
            ->getStartColor()
            ->setRGB('FFF2CC');

    }


    /*
    |--------------------------------------------------------------------------
    | NILAI
    |--------------------------------------------------------------------------
    */

    if (is_numeric($nilai)) {

        if ($nilai >= 75) {

            $sheet->getStyle("G{$row}")
                ->getFont()
                ->getColor()
                ->setRGB('008000');

        } else {

            $sheet->getStyle("G{$row}")
                ->getFont()
                ->getColor()
                ->setRGB('C00000');

        }

        $sheet->getStyle("G{$row}")
            ->getFont()
            ->setBold(true);

    }


    /*
    |--------------------------------------------------------------------------
    | ZEBRA
    |--------------------------------------------------------------------------
    */

    if ($row % 2 == 0) {

        $sheet->getStyle("A{$row}:H{$row}")
            ->getFill()
            ->setFillType(
                \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
            );

        $sheet->getStyle("A{$row}:H{$row}")
            ->getFill()
            ->getStartColor()
            ->setRGB('F8F9FA');

    }

    $sheet->getRowDimension($row)
        ->setRowHeight(22);

    $row++;

    $nomor++;

}


/*
|--------------------------------------------------------------------------
| BORDER
|--------------------------------------------------------------------------
*/

$lastRow = $row - 1;

$sheet->getStyle("A{$headerRow}:H{$lastRow}")
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(
        Border::BORDER_THIN
    );


/*
|--------------------------------------------------------------------------
| OUTLINE
|--------------------------------------------------------------------------
*/

$sheet->getStyle("A{$headerRow}:H{$lastRow}")
    ->getBorders()
    ->getOutline()
    ->setBorderStyle(
        Border::BORDER_MEDIUM
    );


/*
|--------------------------------------------------------------------------
| FREEZE
|--------------------------------------------------------------------------
*/

$sheet->freezePane('A19');

    /*
|--------------------------------------------------------------------------
| RINGKASAN HASIL
|--------------------------------------------------------------------------
*/

$row += 2;

$rataRata = $totalSelesai > 0
    ? round($totalNilai / $totalSelesai, 2)
    : 0;

$sheet->mergeCells("A{$row}:C{$row}");
$sheet->setCellValue(
    "A{$row}",
    "RINGKASAN HASIL UJIAN"
);

$sheet->getStyle("A{$row}:H{$row}")
    ->getFont()
    ->setBold(true)
    ->setSize(12);

$sheet->getStyle("A{$row}:H{$row}")
    ->getAlignment()
    ->setHorizontal(
        Alignment::HORIZONTAL_LEFT
    );

$row++;

$sheet->setCellValue("A{$row}", "Jumlah Peserta");
$sheet->setCellValue("B{$row}", ":");
$sheet->setCellValue("C{$row}", $siswas->count());

$row++;

$sheet->setCellValue("A{$row}", "Sudah Selesai");
$sheet->setCellValue("B{$row}", ":");
$sheet->setCellValue("C{$row}", $totalSelesai);

$row++;

$sheet->setCellValue("A{$row}", "Sedang Mengerjakan");
$sheet->setCellValue("B{$row}", ":");
$sheet->setCellValue("C{$row}", $totalMengerjakan);

$row++;

$sheet->setCellValue("A{$row}", "Belum Mengerjakan");
$sheet->setCellValue("B{$row}", ":");
$sheet->setCellValue("C{$row}", $totalBelum);

$row++;

$sheet->setCellValue("A{$row}", "Rata-rata Nilai");
$sheet->setCellValue("B{$row}", ":");
$sheet->setCellValue("C{$row}", $rataRata);

$sheet->getStyle("A".($row-4).":C{$row}")
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(
        Border::BORDER_THIN
    );


/*
|--------------------------------------------------------------------------
| TANDA TANGAN
|--------------------------------------------------------------------------
*/

$row += 5;

$ttd = max($lastRow + 6, 35);

$sheet->mergeCells("F{$ttd}:H{$ttd}");

$sheet->setCellValue(
    "F{$ttd}",
    "Malinau, ".
    now()->translatedFormat('d F Y')
);

$ttd++;

$sheet->mergeCells("F{$ttd}:H{$ttd}");

$sheet->setCellValue(
    "F{$ttd}",
    "Guru Mata Pelajaran"
);

$ttd += 5;

$sheet->mergeCells("F{$ttd}:H{$ttd}");

$sheet->setCellValue(
    "F{$ttd}",
    $ujian->bankSoal->guru->nama ?? '-'
);


/*
|--------------------------------------------------------------------------
| AUTO FILTER
|--------------------------------------------------------------------------
*/

$sheet->setAutoFilter(
    "A{$headerRow}:H{$lastRow}"
);


/*
|--------------------------------------------------------------------------
| BORDER HEADER
|--------------------------------------------------------------------------
*/

$sheet->getStyle(
    "A{$headerRow}:H{$headerRow}"
)
->getBorders()
->getBottom()
->setBorderStyle(
    Border::BORDER_MEDIUM
);


/*
|--------------------------------------------------------------------------
| WRAP TEXT
|--------------------------------------------------------------------------
*/

$sheet->getStyle(
    "A1:H{$row}"
)
->getAlignment()
->setWrapText(true);


/*
|--------------------------------------------------------------------------
| PRINT AREA
|--------------------------------------------------------------------------
*/

$sheet->getPageSetup()
    ->setPrintArea(
        "A1:H{$row}"
    );


/*
|--------------------------------------------------------------------------
| FOOTER
|--------------------------------------------------------------------------
*/

$sheet->getHeaderFooter()
    ->setOddFooter(
        '&LDicetak : '
        . now()->format('d-m-Y H:i')
        . '&RHalaman &P / &N'
    );


/*
|--------------------------------------------------------------------------
| SIMPAN FILE
|--------------------------------------------------------------------------
*/

$fileName =
    'Rekap-Ujian-'
    .
    str($ujian->judul)
        ->slug()
    .
    '.xlsx';

$tempPath =
    storage_path(
        'app/'.$fileName
    );

$writer =
    new Xlsx($spreadsheet);

$writer->save($tempPath);


/*
|--------------------------------------------------------------------------
| BERSIHKAN MEMORY
|--------------------------------------------------------------------------
*/

$spreadsheet->disconnectWorksheets();

unset($spreadsheet);


/*
|--------------------------------------------------------------------------
| DOWNLOAD
|--------------------------------------------------------------------------
*/

return response()
    ->download(
        $tempPath,
        $fileName
    )
    ->deleteFileAfterSend(true);
}


/*
|--------------------------------------------------------------------------
| Daftar Peserta yang Diblokir
|--------------------------------------------------------------------------
*/
public function blokirIndex(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Query Dasar
    |--------------------------------------------------------------------------
    */

    $query = PengerjaanUjian::with([
            'ujian.kelas',
            'ujian.bankSoal',
            'siswa.user',
        ])
        ->where('status', 'diblokir');


    /*
    |--------------------------------------------------------------------------
    | Pencarian
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->whereHas('siswa', function ($siswa) use ($search) {

                $siswa->where('nama', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");

            })

            ->orWhereHas('ujian', function ($ujian) use ($search) {

                $ujian->where('judul', 'like', "%{$search}%");

            })

            ->orWhereHas('ujian.kelas', function ($kelas) use ($search) {

                $kelas->where('nama', 'like', "%{$search}%");

            });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    $pengerjaans = $query
        ->orderByDesc('diblokir_pada')
        ->paginate(10)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | Statistik
    |--------------------------------------------------------------------------
    */

    $totalDiblokir = PengerjaanUjian::where(
        'status',
        'diblokir'
    )->count();


    return view(
        'cbt.blokir.index',
        compact(
            'pengerjaans',
            'totalDiblokir'
        )
    );
}
/*
|--------------------------------------------------------------------------
| Buka Blokir Peserta Ujian
|--------------------------------------------------------------------------
*/
public function bukaBlokir(
    PengerjaanUjian $pengerjaan
) {
    /*
    |--------------------------------------------------------------------------
    | Pastikan Peserta Memang Diblokir
    |--------------------------------------------------------------------------
    */

    if ($pengerjaan->status !== 'diblokir') {

        return redirect()
            ->route('cbt.blokir.index')
            ->with(
                'error',
                'Peserta ini tidak sedang diblokir.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Periksa Batas Waktu Pengerjaan
    |--------------------------------------------------------------------------
    */

    if (
        $pengerjaan->batas_waktu &&
        now()->gte($pengerjaan->batas_waktu)
    ) {

        return redirect()
            ->route('cbt.blokir.index')
            ->with(
                'error',
                'Blokir tidak dapat dibuka karena waktu pengerjaan peserta telah berakhir.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Proses Buka Blokir
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use ($pengerjaan) {

        /*
         * Lock data agar tidak terjadi
         * proses buka blokir bersamaan.
         */
        $attempt = PengerjaanUjian::query()
            ->whereKey($pengerjaan->id)
            ->lockForUpdate()
            ->firstOrFail();


        /*
         * Periksa kembali status setelah
         * mendapatkan database lock.
         */
        if ($attempt->status !== 'diblokir') {
            return;
        }


        /*
         * Aktifkan kembali pengerjaan.
         */
        $attempt->update([

            'status' =>
                'mengerjakan',

            'jumlah_pelanggaran' =>
                0,

            'diblokir_pada' =>
                null,

            'dibuka_blokir_oleh' =>
                auth()->id(),

        ]);

    });


    return redirect()
        ->route('cbt.blokir.index')
        ->with(
            'success',
            'Blokir peserta berhasil dibuka. Peserta dapat melanjutkan ujian.'
        );
}
}