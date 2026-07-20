<?php

namespace App\Http\Controllers\CBT;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\Kelas;
use App\Models\Ujian;
use App\Models\PengerjaanUjian;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    /*
     * Daftar seluruh ujian.
     */
    public function index()
    {
        $ujians = Ujian::with([
                'bankSoal.soals',
                'kelas',
            ])
            ->where(
                'waktu_selesai',
                '>=',
                now()->subDays(7)
            )
            ->orderByDesc('waktu_mulai')
            ->paginate(9)
            ->withQueryString();

        return view(
            'cbt.ujian.index',
            compact('ujians')
        );
    }


    /*
     * Form membuat ujian.
     */
    public function create()
{
    /*
     * Hanya bank soal yang sudah siap.
     */
    $bankSoals = BankSoal::with('guru')
        ->withCount('soals')
        ->where('status', 'siap')
        ->latest()
        ->get();

    /*
     * Hanya kelas aktif dari
     * tahun ajaran yang sedang aktif.
     */
    $kelas = Kelas::with('tahunAjaran')
        ->where('is_active', true)
        ->whereHas('tahunAjaran', function ($query) {
            $query->where('is_active', true);
        })
        ->orderByRaw("
            CASE tingkat
                WHEN 'X' THEN 1
                WHEN 'XI' THEN 2
                WHEN 'XII' THEN 3
                ELSE 4
            END
        ")
        ->orderBy('nama')
        ->get();

    /*
     * Tampilkan halaman buat ujian.
     */
    return view(
        'cbt.ujian.create',
        compact(
            'bankSoals',
            'kelas'
        )
    );
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

            'acak_jawaban' => [
                'required',
                'boolean',
            ],
        ]);

        /*
         * Jangan hanya percaya exists.
         * Pastikan Bank Soal memang siap.
         */
        $bankSoal = BankSoal::query()
            ->whereKey(
                $validated['bank_soal_id']
            )
            ->where(
                'status',
                'siap'
            )
            ->firstOrFail();

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

            'acak_jawaban' =>
                (bool) $validated['acak_jawaban'],

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
     * Hanya ujian draft yang dapat diedit.
     */
    if ($ujian->status !== 'draft') {

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
     * Ambil bank soal yang siap digunakan.
     */
    $bankSoals = BankSoal::with([
            'guru',
        ])
        ->withCount('soals')
        ->where(
            'status',
            'siap'
        )
        ->latest()
        ->get();


    /*
     * Ambil kelas yang masih aktif.
     */
    $kelas = Kelas::with('tahunAjaran')
    ->where('is_active', true)
    ->whereHas('tahunAjaran', function ($query) {
        $query->where('is_active', true);
    })
    ->orderByRaw("
        CASE tingkat
            WHEN 'X' THEN 1
            WHEN 'XI' THEN 2
            WHEN 'XII' THEN 3
            ELSE 4
        END
    ")
    ->orderBy('nama')
    ->get();


    return view(
        'cbt.ujian.edit',
        compact(
            'ujian',
            'bankSoals',
            'kelas'
        )
    );
}


/*
|--------------------------------------------------------------------------
| Update Ujian
|--------------------------------------------------------------------------
*/
public function update(
    Request $request,
    Ujian $ujian
) {
    /*
     * Keamanan backend.
     *
     * Walaupun URL update dipanggil manual,
     * ujian yang sudah dipublikasikan
     * tetap tidak boleh diubah.
     */
    if ($ujian->status !== 'draft') {

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

        'acak_jawaban' => [
            'required',
            'boolean',
        ],

    ]);


    /*
     * Pastikan bank soal masih siap.
     */
    $bankSoal = BankSoal::query()
        ->whereKey(
            $validated['bank_soal_id']
        )
        ->where(
            'status',
            'siap'
        )
        ->firstOrFail();


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


    /*
     * Update data ujian.
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
            (bool) $validated['acak_soal'],

        'acak_jawaban' =>
            (bool) $validated['acak_jawaban'],

    ]);


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
public function rekap()
{
    $ujians = Ujian::with([
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
        ])
        ->orderByDesc('waktu_mulai')
        ->paginate(9)
        ->withQueryString();

    return view(
        'cbt.rekap.index',
        compact('ujians')
    );
}

public function arsip(Request $request)
{
    $query = Ujian::with([
            'bankSoal.soals',
            'kelas',
        ])
        ->where(
            'waktu_selesai',
            '<',
            now()->subDays(7)
        );


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

public function rekapArsip(Request $request)
{
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
     * Buat spreadsheet.
     */
    $spreadsheet =
        new Spreadsheet();

    $sheet =
        $spreadsheet
            ->getActiveSheet();


    /*
     * Nama sheet maksimal
     * 31 karakter.
     */
    $sheet->setTitle(
        'Rekap Hasil Ujian'
    );


    /*
    |--------------------------------------------------------------------------
    | JUDUL
    |--------------------------------------------------------------------------
    */

    $sheet->mergeCells(
        'A1:H1'
    );

    $sheet->setCellValue(
        'A1',
        'REKAP HASIL UJIAN'
    );


    $sheet->mergeCells(
        'A2:H2'
    );

    $sheet->setCellValue(
        'A2',
        $ujian->judul
    );


    /*
    |--------------------------------------------------------------------------
    | INFORMASI UJIAN
    |--------------------------------------------------------------------------
    */

    $sheet->setCellValue(
        'A4',
        'Mata Pelajaran'
    );

    $sheet->setCellValue(
        'B4',
        $ujian
            ->bankSoal
            ->mata_pelajaran
        ?? '-'
    );


    $sheet->setCellValue(
        'A5',
        'Kelas'
    );

    $sheet->setCellValue(
        'B5',
        $ujian
            ->kelas
            ->nama
        ?? '-'
    );


    $sheet->setCellValue(
        'A6',
        'Tahun Ajaran'
    );

    $sheet->setCellValue(
        'B6',
        $ujian
            ->kelas
            ->tahunAjaran
            ->nama
        ?? '-'
    );


    $sheet->setCellValue(
        'D4',
        'Tanggal Ujian'
    );

    $sheet->setCellValue(
        'E4',
        $ujian
            ->waktu_mulai
            ?->format(
                'd/m/Y'
            )
        ?? '-'
    );


    $sheet->setCellValue(
        'D5',
        'Waktu'
    );

    $sheet->setCellValue(
        'E5',
        (
            $ujian
                ->waktu_mulai
                ?->format('H:i')
            ?? '-'
        )
        .
        ' - '
        .
        (
            $ujian
                ->waktu_selesai
                ?->format('H:i')
            ?? '-'
        )
    );


    $sheet->setCellValue(
        'D6',
        'Durasi'
    );

    $sheet->setCellValue(
        'E6',
        $ujian->durasi_menit
        . ' menit'
    );


    /*
    |--------------------------------------------------------------------------
    | HEADER TABEL
    |--------------------------------------------------------------------------
    */

    $headerRow = 8;

    $headers = [
        'No',
        'NIS',
        'Nama Siswa',
        'Status',
        'Waktu Mulai',
        'Waktu Selesai',
        'Nilai',
        'Keterangan',
    ];


    foreach (
        $headers
        as $index => $header
    ) {

        $column =
            chr(
                65 + $index
            );

        $sheet->setCellValue(
            $column
            . $headerRow,
            $header
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DATA SISWA
    |--------------------------------------------------------------------------
    */

    $row = 9;

    $nomor = 1;


    foreach (
        $siswas
        as $siswa
    ) {

        $pengerjaan =
            $pengerjaanPerSiswa
                ->get(
                    $siswa->id
                );


        /*
         * Tentukan status.
         */
        if (! $pengerjaan) {

            $status =
                'Belum Mengerjakan';

        } elseif (
            $pengerjaan->status
            === 'mengerjakan'
        ) {

            $status =
                'Mengerjakan';

        } elseif (
            $pengerjaan->status
            === 'selesai'
        ) {

            $status =
                'Selesai';

        } else {

            $status =
                ucfirst(
                    $pengerjaan
                        ->status
                );

        }


        /*
         * Tentukan keterangan.
         */
        if (! $pengerjaan) {

            $keterangan =
                'Belum mengikuti ujian';

        } elseif (
            $pengerjaan->status
            === 'mengerjakan'
        ) {

            $keterangan =
                'Belum menyelesaikan ujian';

        } else {

            $keterangan =
                'Telah menyelesaikan ujian';

        }


        $sheet->setCellValue(
            'A' . $row,
            $nomor
        );


        $sheet->setCellValueExplicit(
            'B' . $row,
            (string) (
                $siswa->nis
                ?? '-'
            ),
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
        );


        $sheet->setCellValue(
            'C' . $row,
            $siswa->nama
        );


        $sheet->setCellValue(
            'D' . $row,
            $status
        );


        $sheet->setCellValue(
            'E' . $row,

            $pengerjaan
                ?->waktu_mulai
                ?->format(
                    'd/m/Y H:i'
                )
            ?? '-'
        );


        $sheet->setCellValue(
            'F' . $row,

            $pengerjaan
                ?->waktu_selesai
                ?->format(
                    'd/m/Y H:i'
                )
            ?? '-'
        );


        /*
         * Nilai hanya ditampilkan
         * jika ujian sudah selesai.
         */
        if (
            $pengerjaan &&
            $pengerjaan->status
            === 'selesai'
        ) {

            $sheet->setCellValue(
                'G' . $row,
                (float)
                $pengerjaan->nilai
            );

        } else {

            $sheet->setCellValue(
                'G' . $row,
                '-'
            );

        }


        $sheet->setCellValue(
            'H' . $row,
            $keterangan
        );


        $row++;

        $nomor++;

    }


    /*
    |--------------------------------------------------------------------------
    | STYLE JUDUL
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getStyle('A1:H1')
        ->getFont()
        ->setBold(true)
        ->setSize(16);


    $sheet
        ->getStyle('A2:H2')
        ->getFont()
        ->setBold(true)
        ->setSize(13);


    $sheet
        ->getStyle('A1:H2')
        ->getAlignment()
        ->setHorizontal(
            Alignment::HORIZONTAL_CENTER
        );


    /*
    |--------------------------------------------------------------------------
    | STYLE HEADER
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getStyle(
            'A8:H8'
        )
        ->getFont()
        ->setBold(true);


    $sheet
        ->getStyle(
            'A8:H8'
        )
        ->getAlignment()
        ->setHorizontal(
            Alignment::HORIZONTAL_CENTER
        );


    /*
    |--------------------------------------------------------------------------
    | BORDER TABEL
    |--------------------------------------------------------------------------
    */

    $lastRow =
        max(
            8,
            $row - 1
        );


    $sheet
        ->getStyle(
            'A8:H'
            . $lastRow
        )
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(
            Border::BORDER_THIN
        );


    /*
    |--------------------------------------------------------------------------
    | ALIGNMENT
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getStyle(
            'A9:A'
            . $lastRow
        )
        ->getAlignment()
        ->setHorizontal(
            Alignment::HORIZONTAL_CENTER
        );


    $sheet
        ->getStyle(
            'D9:G'
            . $lastRow
        )
        ->getAlignment()
        ->setHorizontal(
            Alignment::HORIZONTAL_CENTER
        );


    /*
    |--------------------------------------------------------------------------
    | LEBAR KOLOM
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getColumnDimension('A')
        ->setWidth(7);

    $sheet
        ->getColumnDimension('B')
        ->setWidth(18);

    $sheet
        ->getColumnDimension('C')
        ->setWidth(30);

    $sheet
        ->getColumnDimension('D')
        ->setWidth(22);

    $sheet
        ->getColumnDimension('E')
        ->setWidth(22);

    $sheet
        ->getColumnDimension('F')
        ->setWidth(22);

    $sheet
        ->getColumnDimension('G')
        ->setWidth(12);

    $sheet
        ->getColumnDimension('H')
        ->setWidth(30);


    /*
    |--------------------------------------------------------------------------
    | SIMPAN FILE SEMENTARA
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
            'app/'
            . $fileName
        );


    $writer =
        new Xlsx(
            $spreadsheet
        );

    $writer->save(
        $tempPath
    );


    /*
     * Bersihkan object spreadsheet
     * dari memory.
     */
    $spreadsheet
        ->disconnectWorksheets();


    unset(
        $spreadsheet
    );


    /*
     * Download dan hapus file
     * setelah dikirim.
     */
    return response()
        ->download(
            $tempPath,
            $fileName
        )
        ->deleteFileAfterSend(
            true
        );
}

public function bukaBlokir(
    Ujian $ujian,
    PengerjaanUjian $pengerjaan
) {
    /*
     * Pastikan pengerjaan benar-benar
     * berasal dari ujian yang dibuka.
     */
    abort_unless(
        (int) $pengerjaan->ujian_id ===
        (int) $ujian->id,
        404
    );


    /*
     * Hanya pengerjaan dengan status
     * diblokir yang dapat dibuka kembali.
     */
    if ($pengerjaan->status !== 'diblokir') {

        return back()->with(
            'error',
            'Peserta ini tidak sedang diblokir.'
        );
    }


    /*
     * Jika batas waktu pengerjaan sudah habis,
     * siswa tidak dapat melanjutkan lagi.
     */
    if (
        now()->gte(
            $pengerjaan->batas_waktu
        )
    ) {

        return back()->with(
            'error',
            'Blokir tidak dapat dibuka karena waktu pengerjaan peserta telah berakhir.'
        );
    }


    DB::transaction(function () use ($pengerjaan) {

        /*
         * Lock row agar tidak terjadi
         * proses buka blokir bersamaan.
         */
        $attempt =
            PengerjaanUjian::query()
                ->whereKey(
                    $pengerjaan->id
                )
                ->lockForUpdate()
                ->firstOrFail();


        /*
         * Periksa kembali status setelah
         * mendapatkan database lock.
         */
        if ($attempt->status !== 'diblokir') {
            return;
        }


        $attempt->update([

            /*
             * Aktifkan kembali pengerjaan.
             */
            'status' =>
                'mengerjakan',

            /*
             * Reset jumlah pelanggaran.
             */
            'jumlah_pelanggaran' =>
                0,

            /*
             * Hapus status waktu blokir aktif.
             */
            'diblokir_pada' =>
                null,

            /*
             * Catat user/operator
             * yang membuka blokir.
             */
            'dibuka_blokir_oleh' =>
                auth()->id(),

        ]);

    });


    return back()->with(
        'success',
        'Blokir peserta berhasil dibuka. Peserta dapat melanjutkan ujian.'
    );
}
}