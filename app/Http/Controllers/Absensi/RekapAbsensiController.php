<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

use PhpOffice\PhpSpreadsheet\Style\Border;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
{
    $bulan = (int) $request->input(
        'bulan',
        now()->month
    );

    $tahun = (int) $request->input(
        'tahun',
        now()->year
    );

    /*
     * Jika tingkat kosong,
     * semua tingkat ditampilkan,
     * tetapi tetap dipisahkan per bagian.
     */
    $tingkat = $request->input('tingkat');

    /*
     * Ambil tingkat yang tersedia.
     *
     * Contoh:
     * X
     * XI
     * XII
     */
    $daftarTingkat = Kelas::query()
        ->where('is_active', true)
        ->select('tingkat')
        ->distinct()
        ->pluck('tingkat');

    /*
     * Ambil data absensi periode terpilih.
     */
    $absensis = Absensi::with([
            'siswa.user',
            'siswa.kelas',
            'sesiAbsensi.kelas',
        ])
        ->whereHas(
            'sesiAbsensi',
            function ($query) use (
                $bulan,
                $tahun,
                $tingkat
            ) {
                $query
                    ->whereMonth(
                        'tanggal',
                        $bulan
                    )
                    ->whereYear(
                        'tanggal',
                        $tahun
                    );

                if ($tingkat) {

                    $query->whereHas(
                        'kelas',
                        function ($q) use (
                            $tingkat
                        ) {
                            $q->where(
                                'tingkat',
                                $tingkat
                            );
                        }
                    );
                }
            }
        )
        ->get();

    /*
     * Kelompokkan data berdasarkan tingkat.
     *
     * Hasil:
     *
     * X
     * ├── X IPA 1
     * └── X IPS 1
     *
     * XI
     * └── XI IPA 1
     */
    $rekapPerTingkat = $absensis
        ->groupBy(
            fn ($absensi) =>
                $absensi
                    ->siswa
                    ->kelas
                    ->tingkat
        )
        ->map(function ($absensiTingkat) {

            /*
             * Rekap siswa dalam tingkat tersebut.
             */
            $rekapSiswa = $absensiTingkat
    ->groupBy('siswa_id')
    ->map(function ($items) {

        $siswa = $items->first()->siswa;

        /*
         * Riwayat absensi siswa
         * dikelompokkan berdasarkan tanggal.
         */
        $riwayat = $items
            ->groupBy(function ($absensi) {
                return $absensi
                    ->sesiAbsensi
                    ->tanggal
                    ->format('Y-m-d');
            })
            ->map(function ($absensiTanggal, $tanggal) {

                /*
                 * Cari absensi sesi pagi.
                 */
                $pagi = $absensiTanggal
                    ->first(function ($item) {
                        return strtolower(
                            $item->sesiAbsensi->jenis
                        ) === 'pagi';
                    });

                /*
                 * Cari absensi sesi siang.
                 */
                $siang = $absensiTanggal
                    ->first(function ($item) {
                        return strtolower(
                            $item->sesiAbsensi->jenis
                        ) === 'siang';
                    });

                return [
                    'tanggal' => $tanggal,

                    'pagi' => $pagi
                        ? [
                            'status' => $pagi->status,
                            'waktu' => $pagi->waktu_absen
                                ?->format('H:i'),
                        ]
                        : null,

                    'siang' => $siang
                        ? [
                            'status' => $siang->status,
                            'waktu' => $siang->waktu_absen
                                ?->format('H:i'),
                        ]
                        : null,
                ];
            })
            ->sortBy('tanggal')
            ->values();

        return [
            'siswa' => $siswa,

            'hadir' => $items
                ->where('status', 'hadir')
                ->count(),

            'terlambat' => $items
                ->where('status', 'terlambat')
                ->count(),

            'izin' => $items
                ->where('status', 'izin')
                ->count(),

            'sakit' => $items
                ->where('status', 'sakit')
                ->count(),

            'alpa' => $items
                ->where('status', 'alpa')
                ->count(),

            'total' => $items->count(),

            'riwayat' => $riwayat,
        ];
    })
    ->sortBy([
        fn ($a, $b) => strcmp(
            $a['siswa']->kelas->nama,
            $b['siswa']->kelas->nama
        ),

        fn ($a, $b) => strcmp(
            $a['siswa']->user->name,
            $b['siswa']->user->name
        ),
    ])
    ->values();

                /*
 * Rekap absensi berdasarkan tanggal.
 */
$rekapHarian = $absensiTingkat
    ->groupBy(function ($absensi) {
        return $absensi
            ->sesiAbsensi
            ->tanggal
            ->format('Y-m-d');
    })
    ->map(function ($items, $tanggal) {

        /*
         * Kelompokkan juga berdasarkan sesi.
         * Karena satu hari bisa memiliki
         * sesi pagi dan siang.
         */
        $sesiPagi = $items->filter(
            fn ($item) =>
                $item->sesiAbsensi->jenis === 'pagi'
        );

        $sesiSiang = $items->filter(
            fn ($item) =>
                $item->sesiAbsensi->jenis === 'siang'
        );

        return [
            'tanggal' => $tanggal,

            'pagi_terisi' =>
                $sesiPagi->isNotEmpty(),

            'siang_terisi' =>
                $sesiSiang->isNotEmpty(),

            'hadir' =>
                $items
                    ->where('status', 'hadir')
                    ->count(),

            'terlambat' =>
                $items
                    ->where('status', 'terlambat')
                    ->count(),

            'izin' =>
                $items
                    ->where('status', 'izin')
                    ->count(),

            'sakit' =>
                $items
                    ->where('status', 'sakit')
                    ->count(),

            'alpa' =>
                $items
                    ->where('status', 'alpa')
                    ->count(),

            'total' =>
                $items->count(),
        ];
    })
    ->sortBy('tanggal')
    ->values();

            return [
                'rekap_siswa' => $rekapSiswa,

                'rekap_harian' => $rekapHarian,

                'statistik' => [

                

                    'hadir' =>
                        $absensiTingkat
                            ->where(
                                'status',
                                'hadir'
                            )
                            ->count(),

                    'terlambat' =>
                        $absensiTingkat
                            ->where(
                                'status',
                                'terlambat'
                            )
                            ->count(),

                    'izin' =>
                        $absensiTingkat
                            ->where(
                                'status',
                                'izin'
                            )
                            ->count(),

                    'sakit' =>
                        $absensiTingkat
                            ->where(
                                'status',
                                'sakit'
                            )
                            ->count(),

                    'alpa' =>
                        $absensiTingkat
                            ->where(
                                'status',
                                'alpa'
                            )
                            ->count(),
                ],
            ];
        });

    return view(
        'absensi.rekap.index',
        compact(
            'bulan',
            'tahun',
            'tingkat',
            'daftarTingkat',
            'rekapPerTingkat'
        )
    );
}

public function export(Request $request): StreamedResponse
{
    $validated = $request->validate([
        'bulan' => [
            'required',
            'integer',
            'between:1,12',
        ],

        'tahun' => [
            'required',
            'integer',
            'min:2020',
            'max:2100',
        ],

        'tingkat' => [
            'required',
            'string',
            'max:20',
        ],
    ]);

    $bulan = (int) $validated['bulan'];
    $tahun = (int) $validated['tahun'];
    $tingkat = $validated['tingkat'];

    /*
    |--------------------------------------------------------------------------
    | Ambil Data Absensi
    |--------------------------------------------------------------------------
    */

    $absensis = Absensi::with([
            'siswa.user',
            'siswa.kelas',
            'sesiAbsensi',
        ])
        ->whereHas(
            'sesiAbsensi',
            function ($query) use (
                $bulan,
                $tahun,
                $tingkat
            ) {
                $query
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->whereHas(
                        'kelas',
                        function ($q) use ($tingkat) {
                            $q->where(
                                'tingkat',
                                $tingkat
                            );
                        }
                    );
            }
        )
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Rekap Per Siswa
    |--------------------------------------------------------------------------
    */

    $rekapSiswa = $absensis
        ->groupBy('siswa_id')
        ->map(function ($items) {

            $siswa = $items->first()->siswa;

            return [
                'siswa' => $siswa,

                'hadir' => $items
                    ->where('status', 'hadir')
                    ->count(),

                'terlambat' => $items
                    ->where('status', 'terlambat')
                    ->count(),

                'izin' => $items
                    ->where('status', 'izin')
                    ->count(),

                'sakit' => $items
                    ->where('status', 'sakit')
                    ->count(),

                'alpa' => $items
                    ->where('status', 'alpa')
                    ->count(),

                'total' => $items->count(),

                /*
                 * Detail riwayat per tanggal.
                 */
                'riwayat' => $items
                    ->groupBy(function ($item) {
                        return $item
                            ->sesiAbsensi
                            ->tanggal
                            ->format('Y-m-d');
                    })
                    ->map(function (
                        $absensiTanggal,
                        $tanggal
                    ) {

                        $pagi = $absensiTanggal
                            ->first(function ($item) {
                                return strtolower(
                                    $item
                                        ->sesiAbsensi
                                        ->jenis
                                ) === 'pagi';
                            });

                        $siang = $absensiTanggal
                            ->first(function ($item) {
                                return strtolower(
                                    $item
                                        ->sesiAbsensi
                                        ->jenis
                                ) === 'siang';
                            });

                        return [
                            'tanggal' => $tanggal,

                            'pagi' => $pagi,

                            'siang' => $siang,
                        ];
                    })
                    ->sortBy('tanggal')
                    ->values(),
            ];
        })
        ->sortBy(function ($item) {
            return
                $item['siswa']->kelas->nama
                . '-'
                . $item['siswa']->user->name;
        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Nama Bulan
    |--------------------------------------------------------------------------
    */

    $namaBulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /*
    |--------------------------------------------------------------------------
    | Buat Spreadsheet
    |--------------------------------------------------------------------------
    */

    $spreadsheet = new Spreadsheet();

    /*
    |--------------------------------------------------------------------------
    | SHEET 1 — REKAP BULANAN
    |--------------------------------------------------------------------------
    */

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('Rekap Bulanan');

    $sheet->setCellValue(
        'A1',
        'REKAP ABSENSI SISWA'
    );

    $sheet->setCellValue(
        'A2',
        'Kelas ' . $tingkat
    );

    $sheet->setCellValue(
        'A3',
        'Periode '
        . $namaBulan[$bulan]
        . ' '
        . $tahun
    );

    $sheet->mergeCells('A1:J1');
    $sheet->mergeCells('A2:J2');
    $sheet->mergeCells('A3:J3');

    /*
     * Header.
     */
    $headers = [
        'No',
        'Nama Siswa',
        'NIS',
        'Kelas',
        'Hadir',
        'Terlambat',
        'Izin',
        'Sakit',
        'Alpa',
        'Total',
    ];

    $column = 'A';

    foreach ($headers as $header) {

        $sheet->setCellValue(
            $column . '5',
            $header
        );

        $column++;
    }

    /*
     * Data siswa.
     */
    $row = 6;

    foreach (
        $rekapSiswa
        as $index => $rekap
    ) {

        $sheet->setCellValue(
            'A' . $row,
            $index + 1
        );

        $sheet->setCellValue(
            'B' . $row,
            $rekap['siswa']
                ->user
                ->name
        );

        $sheet->setCellValue(
            'C' . $row,
            $rekap['siswa']->nis
        );

        $sheet->setCellValue(
            'D' . $row,
            $rekap['siswa']
                ->kelas
                ->nama
        );

        $sheet->setCellValue(
            'E' . $row,
            $rekap['hadir']
        );

        $sheet->setCellValue(
            'F' . $row,
            $rekap['terlambat']
        );

        $sheet->setCellValue(
            'G' . $row,
            $rekap['izin']
        );

        $sheet->setCellValue(
            'H' . $row,
            $rekap['sakit']
        );

        $sheet->setCellValue(
            'I' . $row,
            $rekap['alpa']
        );

        $sheet->setCellValue(
            'J' . $row,
            $rekap['total']
        );

        $row++;
    }

    /*
    |--------------------------------------------------------------------------
    | SHEET 2 — DETAIL HARIAN
    |--------------------------------------------------------------------------
    */

    $detailSheet = $spreadsheet
        ->createSheet();

    $detailSheet->setTitle(
        'Detail Harian'
    );

    $detailSheet->setCellValue(
        'A1',
        'DETAIL ABSENSI HARIAN SISWA'
    );

    $detailSheet->setCellValue(
        'A2',
        'Kelas ' . $tingkat
    );

    $detailSheet->setCellValue(
        'A3',
        'Periode '
        . $namaBulan[$bulan]
        . ' '
        . $tahun
    );

    $detailSheet->mergeCells(
        'A1:J1'
    );

    $detailSheet->mergeCells(
        'A2:J2'
    );

    $detailSheet->mergeCells(
        'A3:J3'
    );

    /*
     * Header detail.
     */
    $detailHeaders = [
        'No',
        'Tanggal',
        'Nama Siswa',
        'NIS',
        'Kelas',
        'Status Pagi',
        'Waktu Pagi',
        'Status Siang',
        'Waktu Siang',
        'Keterangan',
    ];

    $column = 'A';

    foreach (
        $detailHeaders
        as $header
    ) {

        $detailSheet->setCellValue(
            $column . '5',
            $header
        );

        $column++;
    }

    /*
     * Isi detail.
     */
    $detailRow = 6;
    $nomorDetail = 1;

    foreach ($rekapSiswa as $rekap) {

        foreach (
            $rekap['riwayat']
            as $riwayat
        ) {

            $pagi =
                $riwayat['pagi'];

            $siang =
                $riwayat['siang'];

            $detailSheet->setCellValue(
                'A' . $detailRow,
                $nomorDetail
            );

            $detailSheet->setCellValue(
                'B' . $detailRow,
                \Carbon\Carbon::parse(
                    $riwayat['tanggal']
                )->format('d/m/Y')
            );

            $detailSheet->setCellValue(
                'C' . $detailRow,
                $rekap['siswa']
                    ->user
                    ->name
            );

            $detailSheet->setCellValue(
                'D' . $detailRow,
                $rekap['siswa']->nis
            );

            $detailSheet->setCellValue(
                'E' . $detailRow,
                $rekap['siswa']
                    ->kelas
                    ->nama
            );

            /*
             * Status pagi.
             */
            $detailSheet->setCellValue(
                'F' . $detailRow,
                $pagi
                    ? ucfirst(
                        $pagi->status
                    )
                    : '-'
            );

            /*
             * Waktu pagi.
             */
            $detailSheet->setCellValue(
                'G' . $detailRow,
                $pagi
                    ? $pagi
                        ->waktu_absen
                        ?->format('H:i')
                    : '-'
            );

            /*
             * Status siang.
             */
            $detailSheet->setCellValue(
                'H' . $detailRow,
                $siang
                    ? ucfirst(
                        $siang->status
                    )
                    : '-'
            );

            /*
             * Waktu siang.
             */
            $detailSheet->setCellValue(
                'I' . $detailRow,
                $siang
                    ? $siang
                        ->waktu_absen
                        ?->format('H:i')
                    : '-'
            );

            /*
             * Keterangan.
             *
             * Jika tabel absensis kamu
             * mempunyai kolom keterangan,
             * bagian ini bisa disesuaikan.
             */
            $detailSheet->setCellValue(
                'J' . $detailRow,
                '-'
            );

            $detailRow++;
            $nomorDetail++;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Styling Kedua Sheet
    |--------------------------------------------------------------------------
    */

    foreach (
        [$sheet, $detailSheet]
        as $worksheet
    ) {

        /*
         * Judul.
         */
        $worksheet
            ->getStyle('A1:J1')
            ->getFont()
            ->setBold(true)
            ->setSize(16);

        $worksheet
            ->getStyle('A1:J3')
            ->getAlignment()
            ->setHorizontal('center');

        /*
         * Header tabel.
         */
        $worksheet
            ->getStyle('A5:J5')
            ->getFont()
            ->setBold(true);

        $worksheet
            ->getStyle('A5:J5')
            ->getAlignment()
            ->setHorizontal('center');

        /*
         * Auto size.
         */
        foreach (
            range('A', 'J')
            as $column
        ) {

            $worksheet
                ->getColumnDimension(
                    $column
                )
                ->setAutoSize(true);
        }

        /*
         * Freeze header.
         */
        $worksheet
            ->freezePane('A6');

        /*
         * Filter.
         */
        $worksheet
            ->setAutoFilter(
                'A5:J5'
            );
    }

    /*
     * Border Rekap Bulanan.
     */
    $lastRekapRow =
        max(5, $row - 1);

    $sheet
        ->getStyle(
            'A5:J' . $lastRekapRow
        )
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    /*
     * Border Detail.
     */
    $lastDetailRow =
        max(5, $detailRow - 1);

    $detailSheet
        ->getStyle(
            'A5:J' . $lastDetailRow
        )
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    /*
     * Kembali ke sheet pertama.
     */
    $spreadsheet
        ->setActiveSheetIndex(0);

    /*
    |--------------------------------------------------------------------------
    | Download
    |--------------------------------------------------------------------------
    */

    $namaFile =
        'Rekap-Absensi-Kelas-'
        . $tingkat
        . '-'
        . str_pad(
            $bulan,
            2,
            '0',
            STR_PAD_LEFT
        )
        . '-'
        . $tahun
        . '.xlsx';

    return response()->streamDownload(
        function () use ($spreadsheet) {

            $writer = new Xlsx(
                $spreadsheet
            );

            $writer->save(
                'php://output'
            );

            $spreadsheet
                ->disconnectWorksheets();

        },
        $namaFile,
        [
            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]
    );
}

}