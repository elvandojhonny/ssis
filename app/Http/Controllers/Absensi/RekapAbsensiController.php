<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RekapAbsensiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN REKAP ABSENSI
    |--------------------------------------------------------------------------
    */

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
         * Jika tingkat kosong:
         * semua tingkat ditampilkan.
         *
         * Jika tingkat dipilih:
         * hanya tingkat tersebut yang ditampilkan.
         */
        $tingkat = $request->input('tingkat');


        /*
        |--------------------------------------------------------------------------
        | Daftar Tingkat
        |--------------------------------------------------------------------------
        |
        | Contoh:
        | X
        | XI
        | XII
        |
        */

        $daftarTingkat = Kelas::query()
    ->where('is_active', true)
    ->whereHas('tahunAjaran', function ($query) {
        $query->where('is_active', true);
    })
    ->whereNotNull('tingkat')
    ->select('tingkat')
    ->distinct()
    ->orderByRaw("
        CASE tingkat
            WHEN 'X' THEN 1
            WHEN 'XI' THEN 2
            WHEN 'XII' THEN 3
            ELSE 4
        END
    ")
    ->pluck('tingkat');


        /*
        |--------------------------------------------------------------------------
        | Ambil Data Absensi
        |--------------------------------------------------------------------------
        |
        | Sesi sekarang menggunakan tingkat.
        |
        | Contoh:
        |
        | Sesi Tingkat X
        | ├── X 
        | ├── X 
        | └── X 
        |
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
                        ->whereMonth(
                            'tanggal',
                            $bulan
                        )
                        ->whereYear(
                            'tanggal',
                            $tahun
                        );


                    /*
                     * Filter langsung berdasarkan
                     * kolom tingkat pada sesi_absensis.
                     */
                    if ($tingkat) {

                        $query->where(
                            'tingkat',
                            $tingkat
                        );
                    }
                }
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Kelompokkan Rekap Berdasarkan Tingkat
        |--------------------------------------------------------------------------
        */

        $rekapPerTingkat = $absensis

            /*
             * Kelompokkan berdasarkan tingkat
             * siswa saat ini.
             */
            ->groupBy(
                fn ($absensi) =>
                    $absensi
                        ->siswa
                        ?->kelas
                        ?->tingkat
            )

            ->filter(
                fn ($items, $key) =>
                    ! empty($key)
            )

            ->map(function ($absensiTingkat) {


                /*
                |--------------------------------------------------------------------------
                | Rekap Per Siswa
                |--------------------------------------------------------------------------
                */

                $rekapSiswa = $absensiTingkat

                    ->groupBy('siswa_id')

                    ->map(function ($items) {

                        $siswa =
                            $items
                                ->first()
                                ->siswa;


                        /*
                        |--------------------------------------------------------------------------
                        | Riwayat Absensi Per Tanggal
                        |--------------------------------------------------------------------------
                        */

                        $riwayat = $items

                            ->groupBy(
                                function ($absensi) {

                                    return $absensi
                                        ->sesiAbsensi
                                        ->tanggal
                                        ->format(
                                            'Y-m-d'
                                        );
                                }
                            )

                            ->map(
                                function (
                                    $absensiTanggal,
                                    $tanggal
                                ) {


                                    /*
                                     * Cari sesi pagi.
                                     */
                                    $pagi =
                                        $absensiTanggal
                                            ->first(
                                                function (
                                                    $item
                                                ) {

                                                    return strtolower(
                                                        $item
                                                            ->sesiAbsensi
                                                            ->jenis
                                                    ) === 'pagi';
                                                }
                                            );


                                    /*
                                     * Cari sesi siang.
                                     */
                                    $siang =
                                        $absensiTanggal
                                            ->first(
                                                function (
                                                    $item
                                                ) {

                                                    return strtolower(
                                                        $item
                                                            ->sesiAbsensi
                                                            ->jenis
                                                    ) === 'siang';
                                                }
                                            );


                                    return [

                                        'tanggal' =>
                                            $tanggal,


                                        'pagi' =>
                                            $pagi
                                                ? [
                                                    'status' =>
                                                        $pagi
                                                            ->status,

                                                    'waktu' =>
                                                        $pagi
                                                            ->waktu_absen
                                                            ?->format(
                                                                'H:i'
                                                            ),
                                                ]
                                                : null,


                                        'siang' =>
                                            $siang
                                                ? [
                                                    'status' =>
                                                        $siang
                                                            ->status,

                                                    'waktu' =>
                                                        $siang
                                                            ->waktu_absen
                                                            ?->format(
                                                                'H:i'
                                                            ),
                                                ]
                                                : null,
                                    ];
                                }
                            )

                            ->sortBy('tanggal')

                            ->values();


                        /*
                        |--------------------------------------------------------------------------
                        | Statistik Per Siswa
                        |--------------------------------------------------------------------------
                        */

                        return [

                            'siswa' =>
                                $siswa,


                            'hadir' =>
                                $items
                                    ->where(
                                        'status',
                                        'hadir'
                                    )
                                    ->count(),


                            'terlambat' =>
                                $items
                                    ->where(
                                        'status',
                                        'terlambat'
                                    )
                                    ->count(),


                            'izin' =>
                                $items
                                    ->where(
                                        'status',
                                        'izin'
                                    )
                                    ->count(),


                            'sakit' =>
                                $items
                                    ->where(
                                        'status',
                                        'sakit'
                                    )
                                    ->count(),


                            'alpa' =>
                                $items
                                    ->where(
                                        'status',
                                        'alpa'
                                    )
                                    ->count(),


                            'total' =>
                                $items
                                    ->count(),


                            'riwayat' =>
                                $riwayat,
                        ];
                    })


                    /*
                     * Urutkan berdasarkan kelas
                     * kemudian nama siswa.
                     */
                    ->sortBy(
                        function ($item) {

                            return
                                (
                                    $item['siswa']
                                        ->kelas
                                        ?->nama
                                    ?? ''
                                )
                                . '-'
                                .
                                (
                                    $item['siswa']
                                        ->user
                                        ?->name
                                    ?? ''
                                );
                        }
                    )

                    ->values();


                /*
                |--------------------------------------------------------------------------
                | Rekap Harian
                |--------------------------------------------------------------------------
                */

                $rekapHarian = $absensiTingkat

                    ->groupBy(
                        function ($absensi) {

                            return $absensi
                                ->sesiAbsensi
                                ->tanggal
                                ->format(
                                    'Y-m-d'
                                );
                        }
                    )

                    ->map(
                        function (
                            $items,
                            $tanggal
                        ) {


                            /*
                             * Data sesi pagi.
                             */
                            $sesiPagi =
                                $items->filter(
                                    fn ($item) =>
                                        strtolower(
                                            $item
                                                ->sesiAbsensi
                                                ->jenis
                                        ) === 'pagi'
                                );


                            /*
                             * Data sesi siang.
                             */
                            $sesiSiang =
                                $items->filter(
                                    fn ($item) =>
                                        strtolower(
                                            $item
                                                ->sesiAbsensi
                                                ->jenis
                                        ) === 'siang'
                                );


                            return [

                                'tanggal' =>
                                    $tanggal,


                                'pagi_terisi' =>
                                    $sesiPagi
                                        ->isNotEmpty(),


                                'siang_terisi' =>
                                    $sesiSiang
                                        ->isNotEmpty(),


                                'hadir' =>
                                    $items
                                        ->where(
                                            'status',
                                            'hadir'
                                        )
                                        ->count(),


                                'terlambat' =>
                                    $items
                                        ->where(
                                            'status',
                                            'terlambat'
                                        )
                                        ->count(),


                                'izin' =>
                                    $items
                                        ->where(
                                            'status',
                                            'izin'
                                        )
                                        ->count(),


                                'sakit' =>
                                    $items
                                        ->where(
                                            'status',
                                            'sakit'
                                        )
                                        ->count(),


                                'alpa' =>
                                    $items
                                        ->where(
                                            'status',
                                            'alpa'
                                        )
                                        ->count(),


                                'total' =>
                                    $items
                                        ->count(),
                            ];
                        }
                    )

                    ->sortBy('tanggal')

                    ->values();


                /*
                |--------------------------------------------------------------------------
                | Data Akhir Per Tingkat
                |--------------------------------------------------------------------------
                */

                return [

                    'rekap_siswa' =>
                        $rekapSiswa,


                    'rekap_harian' =>
                        $rekapHarian,


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


        /*
        |--------------------------------------------------------------------------
        | Tampilkan View
        |--------------------------------------------------------------------------
        */

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


    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function export(
        Request $request
    ): StreamedResponse
    {

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate([

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


        $bulan =
            (int) $validated['bulan'];

        $tahun =
            (int) $validated['tahun'];

        $tingkat =
            $validated['tingkat'];


        /*
        |--------------------------------------------------------------------------
        | Ambil Data Absensi
        |--------------------------------------------------------------------------
        |
        | Filter langsung menggunakan tingkat
        | dari tabel sesi_absensis.
        |
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

                        ->whereMonth(
                            'tanggal',
                            $bulan
                        )

                        ->whereYear(
                            'tanggal',
                            $tahun
                        )

                        ->where(
                            'tingkat',
                            $tingkat
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

                $siswa =
                    $items
                        ->first()
                        ->siswa;


                return [

                    'siswa' =>
                        $siswa,


                    'hadir' =>
                        $items
                            ->where(
                                'status',
                                'hadir'
                            )
                            ->count(),


                    'terlambat' =>
                        $items
                            ->where(
                                'status',
                                'terlambat'
                            )
                            ->count(),


                    'izin' =>
                        $items
                            ->where(
                                'status',
                                'izin'
                            )
                            ->count(),


                    'sakit' =>
                        $items
                            ->where(
                                'status',
                                'sakit'
                            )
                            ->count(),


                    'alpa' =>
                        $items
                            ->where(
                                'status',
                                'alpa'
                            )
                            ->count(),


                    'total' =>
                        $items
                            ->count(),


                    /*
                     * Detail riwayat
                     * berdasarkan tanggal.
                     */
                    'riwayat' =>
                        $items

                            ->groupBy(
                                function ($item) {

                                    return $item
                                        ->sesiAbsensi
                                        ->tanggal
                                        ->format(
                                            'Y-m-d'
                                        );
                                }
                            )

                            ->map(
                                function (
                                    $absensiTanggal,
                                    $tanggal
                                ) {


                                    $pagi =
                                        $absensiTanggal
                                            ->first(
                                                function (
                                                    $item
                                                ) {

                                                    return strtolower(
                                                        $item
                                                            ->sesiAbsensi
                                                            ->jenis
                                                    ) === 'pagi';
                                                }
                                            );


                                    $siang =
                                        $absensiTanggal
                                            ->first(
                                                function (
                                                    $item
                                                ) {

                                                    return strtolower(
                                                        $item
                                                            ->sesiAbsensi
                                                            ->jenis
                                                    ) === 'siang';
                                                }
                                            );


                                    return [

                                        'tanggal' =>
                                            $tanggal,

                                        'pagi' =>
                                            $pagi,

                                        'siang' =>
                                            $siang,
                                    ];
                                }
                            )

                            ->sortBy('tanggal')

                            ->values(),
                ];
            })


            /*
             * Urut berdasarkan kelas
             * kemudian nama siswa.
             */
            ->sortBy(
                function ($item) {

                    return
                        (
                            $item['siswa']
                                ->kelas
                                ?->nama
                            ?? ''
                        )
                        . '-'
                        .
                        (
                            $item['siswa']
                                ->user
                                ?->name
                            ?? ''
                        );
                }
            )

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

        $spreadsheet =
            new Spreadsheet();


        /*
        |--------------------------------------------------------------------------
        | SHEET 1 — REKAP BULANAN
        |--------------------------------------------------------------------------
        */

        $sheet =
            $spreadsheet
                ->getActiveSheet();


        $sheet->setTitle(
            'Rekap Bulanan'
        );


        $sheet->setCellValue(
            'A1',
            'REKAP ABSENSI SISWA'
        );


        $sheet->setCellValue(
            'A2',
            'Tingkat ' . $tingkat
        );


        $sheet->setCellValue(
            'A3',
            'Periode '
            . $namaBulan[$bulan]
            . ' '
            . $tahun
        );


        $sheet->mergeCells(
            'A1:J1'
        );

        $sheet->mergeCells(
            'A2:J2'
        );

        $sheet->mergeCells(
            'A3:J3'
        );


        /*
        |--------------------------------------------------------------------------
        | Header Rekap
        |--------------------------------------------------------------------------
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


        foreach (
            $headers
            as $header
        ) {

            $sheet->setCellValue(
                $column . '5',
                $header
            );

            $column++;
        }


        /*
        |--------------------------------------------------------------------------
        | Data Rekap Siswa
        |--------------------------------------------------------------------------
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
                    ?->name
                ?? '-'
            );


            $sheet->setCellValue(
                'C' . $row,
                $rekap['siswa']
                    ->nis
            );


            $sheet->setCellValue(
                'D' . $row,
                $rekap['siswa']
                    ->kelas
                    ?->nama
                ?? '-'
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

        $detailSheet =
            $spreadsheet
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
            'Tingkat ' . $tingkat
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
        |--------------------------------------------------------------------------
        | Header Detail
        |--------------------------------------------------------------------------
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

            $detailSheet
                ->setCellValue(
                    $column . '5',
                    $header
                );

            $column++;
        }


        /*
        |--------------------------------------------------------------------------
        | Isi Detail Harian
        |--------------------------------------------------------------------------
        */

        $detailRow = 6;

        $nomorDetail = 1;


        foreach (
            $rekapSiswa
            as $rekap
        ) {


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
                    )->format(
                        'd/m/Y'
                    )
                );


                $detailSheet->setCellValue(
                    'C' . $detailRow,
                    $rekap['siswa']
                        ->user
                        ?->name
                    ?? '-'
                );


                $detailSheet->setCellValue(
                    'D' . $detailRow,
                    $rekap['siswa']
                        ->nis
                );


                $detailSheet->setCellValue(
                    'E' . $detailRow,
                    $rekap['siswa']
                        ->kelas
                        ?->nama
                    ?? '-'
                );


                /*
                 * Status Pagi
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
                 * Waktu Pagi
                 */
                $detailSheet->setCellValue(
                    'G' . $detailRow,
                    $pagi
                        ? (
                            $pagi
                                ->waktu_absen
                                ?->format(
                                    'H:i'
                                )
                            ?? '-'
                        )
                        : '-'
                );


                /*
                 * Status Siang
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
                 * Waktu Siang
                 */
                $detailSheet->setCellValue(
                    'I' . $detailRow,
                    $siang
                        ? (
                            $siang
                                ->waktu_absen
                                ?->format(
                                    'H:i'
                                )
                            ?? '-'
                        )
                        : '-'
                );


                /*
                |--------------------------------------------------------------------------
                | Keterangan
                |--------------------------------------------------------------------------
                */

                $keterangan = [];


                if (
                    $pagi &&
                    ! empty(
                        $pagi->keterangan
                    )
                ) {

                    $keterangan[] =
                        'Pagi: '
                        . $pagi
                            ->keterangan;
                }


                if (
                    $siang &&
                    ! empty(
                        $siang->keterangan
                    )
                ) {

                    $keterangan[] =
                        'Siang: '
                        . $siang
                            ->keterangan;
                }


                $detailSheet->setCellValue(
                    'J' . $detailRow,

                    ! empty($keterangan)

                        ? implode(
                            ' | ',
                            $keterangan
                        )

                        : '-'
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
            [
                $sheet,
                $detailSheet,
            ]
            as $worksheet
        ) {


            /*
             * Judul.
             */
            $worksheet

                ->getStyle(
                    'A1:J1'
                )

                ->getFont()

                ->setBold(true)

                ->setSize(16);


            $worksheet

                ->getStyle(
                    'A1:J3'
                )

                ->getAlignment()

                ->setHorizontal(
                    'center'
                );


            /*
             * Header tabel.
             */
            $worksheet

                ->getStyle(
                    'A5:J5'
                )

                ->getFont()

                ->setBold(true);


            $worksheet

                ->getStyle(
                    'A5:J5'
                )

                ->getAlignment()

                ->setHorizontal(
                    'center'
                );


            /*
             * Auto Size.
             */
            foreach (
                range(
                    'A',
                    'J'
                )
                as $column
            ) {

                $worksheet

                    ->getColumnDimension(
                        $column
                    )

                    ->setAutoSize(
                        true
                    );
            }


            /*
             * Freeze Header.
             */
            $worksheet
                ->freezePane(
                    'A6'
                );


            /*
             * Filter.
             */
            $worksheet
                ->setAutoFilter(
                    'A5:J5'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Border Rekap Bulanan
        |--------------------------------------------------------------------------
        */

        $lastRekapRow =
            max(
                5,
                $row - 1
            );


        $sheet

            ->getStyle(
                'A5:J'
                . $lastRekapRow
            )

            ->getBorders()

            ->getAllBorders()

            ->setBorderStyle(
                Border::BORDER_THIN
            );


        /*
        |--------------------------------------------------------------------------
        | Border Detail Harian
        |--------------------------------------------------------------------------
        */

        $lastDetailRow =
            max(
                5,
                $detailRow - 1
            );


        $detailSheet

            ->getStyle(
                'A5:J'
                . $lastDetailRow
            )

            ->getBorders()

            ->getAllBorders()

            ->setBorderStyle(
                Border::BORDER_THIN
            );


        /*
         * Kembali ke sheet pertama.
         */
        $spreadsheet
            ->setActiveSheetIndex(0);


        /*
        |--------------------------------------------------------------------------
        | Nama File
        |--------------------------------------------------------------------------
        */

        $namaFile =

            'Rekap-Absensi-Tingkat-'

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


        /*
        |--------------------------------------------------------------------------
        | Download Excel
        |--------------------------------------------------------------------------
        */

        return response()->streamDownload(

            function () use (
                $spreadsheet
            ) {

                $writer =
                    new Xlsx(
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