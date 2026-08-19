<?php

namespace App\Http\Controllers\Perpustakaan;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN LAPORAN TRANSAKSI PERPUSTAKAAN
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        $periode = $request->input(
            'periode',
            'bulan'
        );

        $bulan = (int) $request->input(
            'bulan',
            now()->month
        );

        $tahun = (int) $request->input(
            'tahun',
            now()->year
        );

        /*
        |--------------------------------------------------------------------------
        | Query Transaksi
        |--------------------------------------------------------------------------
        */

        $query = Peminjaman::with([

            'petugas',

            'guru',

            'siswa.kelas',

            'detailPeminjaman.buku',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Filter Bulanan
        |--------------------------------------------------------------------------
        */

        if ($periode === 'bulan') {

            $query

                ->whereMonth(
                    'tanggal_pinjam',
                    $bulan
                )

                ->whereYear(
                    'tanggal_pinjam',
                    $tahun
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Tahunan
        |--------------------------------------------------------------------------
        */

        if ($periode === 'tahun') {

            $query

                ->whereYear(
                    'tanggal_pinjam',
                    $tahun
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Data Laporan
        |--------------------------------------------------------------------------
        */

        $laporan = $query

            ->latest('tanggal_pinjam')

            ->latest('id')

            ->paginate(15)

            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $semuaData = (clone $query)->get();

        $totalTransaksi = $semuaData->count();

        $totalDipinjam = $semuaData

            ->where(
                'status',
                'dipinjam'
            )

            ->count();

        $totalDikembalikan = $semuaData

            ->where(
                'status',
                'dikembalikan'
            )

            ->count();

        $totalTerlambat = $semuaData

            ->where(
                'status',
                'terlambat'
            )

            ->count();

        /*
        |--------------------------------------------------------------------------
        | Total Buku Dipinjam
        |--------------------------------------------------------------------------
        */

        $totalBuku = 0;

        foreach ($semuaData as $item) {

            $totalBuku +=
                $item->jumlah_buku;
        }

        /*
        |--------------------------------------------------------------------------
        | Ringkasan
        |--------------------------------------------------------------------------
        */

        $ringkasan = [

            'total_transaksi' =>

                $totalTransaksi,

            'total_buku' =>

                $totalBuku,

            'dipinjam' =>

                $totalDipinjam,

            'dikembalikan' =>

                $totalDikembalikan,

            'terlambat' =>

                $totalTerlambat,

        ];

        /*
        |--------------------------------------------------------------------------
        | Tampilkan Halaman
        |--------------------------------------------------------------------------
        */

        return view(

            'perpustakaan.laporan.index',

            compact(

                'laporan',

                'periode',

                'bulan',

                'tahun',

                'ringkasan'

            )

        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

        /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function export(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Validasi
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'periode' => 'required|in:bulan,tahun',
        'bulan'   => 'nullable|integer|between:1,12',
        'tahun'   => 'required|integer|min:2020|max:2100',
    ]);

    $periode = $validated['periode'];
    $bulan   = (int) ($validated['bulan'] ?? now()->month);
    $tahun   = (int) $validated['tahun'];

    /*
    |--------------------------------------------------------------------------
    | Query Data
    |--------------------------------------------------------------------------
    */

    $query = Peminjaman::with([
        'petugas',
        'guru',
        'siswa.kelas',
        'detailPeminjaman.buku',
    ]);

    if ($periode === 'bulan') {

        $query->whereMonth('tanggal_pinjam', $bulan)
              ->whereYear('tanggal_pinjam', $tahun);

    } else {

        $query->whereYear('tanggal_pinjam', $tahun);

    }

    $laporan = $query
        ->latest('tanggal_pinjam')
        ->latest('id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Statistik
    |--------------------------------------------------------------------------
    */

    $totalTransaksi    = $laporan->count();

    $totalDipinjam     = $laporan
                            ->where('status', 'dipinjam')
                            ->count();

    $totalDikembalikan = $laporan
                            ->where('status', 'dikembalikan')
                            ->count();

    $totalTerlambat    = $laporan
                            ->where('status', 'terlambat')
                            ->count();

    $totalBuku = $laporan->sum('jumlah_buku');

    /*
    |--------------------------------------------------------------------------
    | Nama Bulan
    |--------------------------------------------------------------------------
    */

    $namaBulan = [

        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',

    ];

    /*
    |--------------------------------------------------------------------------
    | Spreadsheet
    |--------------------------------------------------------------------------
    */

    $spreadsheet = new Spreadsheet();

    $spreadsheet->getProperties()

        ->setCreator(config('app.name'))
        ->setTitle('Laporan Transaksi Perpustakaan')
        ->setSubject('Laporan Perpustakaan')
        ->setDescription('Laporan Transaksi Perpustakaan SMA Negeri 6 Malinau');

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('Laporan');

    /*
|--------------------------------------------------------------------------
| Lebar Kolom
|--------------------------------------------------------------------------
*/

$sheet->getColumnDimension('A')->setWidth(6);     // No
$sheet->getColumnDimension('B')->setWidth(18);    // Kode
$sheet->getColumnDimension('C')->setWidth(15);    // Tgl Pinjam
$sheet->getColumnDimension('D')->setWidth(15);    // Tgl Kembali
$sheet->getColumnDimension('E')->setWidth(12);    // Jenis
$sheet->getColumnDimension('F')->setWidth(28);    // Nama
$sheet->getColumnDimension('G')->setWidth(18);    // NIS/NIP
$sheet->getColumnDimension('H')->setWidth(14);    // Kelas
$sheet->getColumnDimension('I')->setWidth(45);    // Judul Buku
$sheet->getColumnDimension('J')->setWidth(12);    // Jumlah
$sheet->getColumnDimension('K')->setWidth(16);    // Status
$sheet->getColumnDimension('L')->setWidth(25);    // Petugas
$sheet->getColumnDimension('M')->setWidth(35);    // Catatan
$sheet->getColumnDimension('N')->setWidth(16);    // Jatuh Tempo

    /*
    |--------------------------------------------------------------------------
    | Tinggi Baris Kop Surat
    |--------------------------------------------------------------------------
    */

   $sheet->getRowDimension(1)->setRowHeight(22);
$sheet->getRowDimension(2)->setRowHeight(22);
$sheet->getRowDimension(3)->setRowHeight(28);
$sheet->getRowDimension(4)->setRowHeight(18);
$sheet->getRowDimension(5)->setRowHeight(18);
$sheet->getRowDimension(6)->setRowHeight(18);
$sheet->getRowDimension(7)->setRowHeight(13);

$sheet->getRowDimension(8)->setRowHeight(3);
$sheet->getRowDimension(9)->setRowHeight(3);

$sheet->getRowDimension(10)->setRowHeight(28);
$sheet->getRowDimension(11)->setRowHeight(22);

    /*
|--------------------------------------------------------------------------
| Logo Kiri (Provinsi)
|--------------------------------------------------------------------------
*/

$logoKiri = public_path('images/kaltara.png');

if (file_exists($logoKiri)) {

    $drawing = new Drawing();

    $drawing->setName('Logo Provinsi');
    $drawing->setDescription('Logo Provinsi');
    $drawing->setPath($logoKiri);

    $drawing->setCoordinates('A1');
$drawing->setHeight(82);
$drawing->setOffsetX(8);
$drawing->setOffsetY(8);

    $drawing->setWorksheet($sheet);
}

/*
|--------------------------------------------------------------------------
| Logo Kanan (SMAN 6)
|--------------------------------------------------------------------------
*/

$logoKanan = public_path('images/logo SMAN 6.png');

if (file_exists($logoKanan)) {

    $drawing2 = new Drawing();

    $drawing2->setName('Logo SMAN 6');
    $drawing2->setDescription('Logo Sekolah');
    $drawing2->setPath($logoKanan);

    $drawing2->setCoordinates('N1');

    $drawing2->setHeight(82);

    $drawing2->setOffsetX(12);
    $drawing2->setOffsetY(8);

    $drawing2->setWorksheet($sheet);
}

    /*
    |--------------------------------------------------------------------------
    | Merge Kop Surat
    |--------------------------------------------------------------------------
    */

    $sheet->mergeCells('B1:M1');
$sheet->mergeCells('B2:M2');
$sheet->mergeCells('B3:M3');
$sheet->mergeCells('B4:M4');
$sheet->mergeCells('B5:M5');
$sheet->mergeCells('B6:M6');
$sheet->mergeCells('B7:M7');

$sheet->mergeCells('A8:N8');
$sheet->mergeCells('A9:N9');

$sheet->mergeCells('A10:N10');
$sheet->mergeCells('A11:N11');

    /*
    |--------------------------------------------------------------------------
    | Isi Kop Surat
    |--------------------------------------------------------------------------
    */

    $sheet->setCellValue('B1','PEMERINTAH PROVINSI KALIMANTAN UTARA');

$sheet->setCellValue('B2','DINAS PENDIDIKAN DAN KEBUDAYAAN');

$sheet->setCellValue('B3','SMA NEGERI 6 MALINAU');

$sheet->setCellValue(
    'B4',
    'NSS : 30.1.16.07.09.009 : NPSN : 30405857 : Akreditasi "C" No SK Akreditasi : 1337/BAN-SM/SK/2019'
);

$sheet->setCellValue(
    'B5',
    'Alamat : Jl. Pendidikan, Mahak Baru, Kec. Sungai Boh'
);

$sheet->setCellValue(
    'B6',
    'Email : sman6malinau2@gmail.com'
);

$sheet->setCellValue(
    'B7',
    'MALINAU'
);

    $sheet->setCellValue(
        'A10',
        'LAPORAN TRANSAKSI PERPUSTAKAAN'
    );

    if ($periode == 'bulan') {

        $sheet->setCellValue(
            'A11',
            'Periode Bulan ' . $namaBulan[$bulan] . ' Tahun ' . $tahun
        );

    } else {

        $sheet->setCellValue(
            'A11',
            'Periode Tahun ' . $tahun
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Style Kop Surat
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('B1:M7')
      ->getAlignment()
      ->setHorizontal(Alignment::HORIZONTAL_CENTER)
      ->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->getStyle('B1:M7')
        ->getFont()
        ->setName('Arial');

    $sheet->getStyle('B1')
        ->getFont()
        ->setBold(true)
        ->setSize(14);

    $sheet->getStyle('B2')
        ->getFont()
        ->setBold(true)
        ->setSize(13);

    $sheet->getStyle('B3')
        ->getFont()
        ->setBold(true)
        ->setSize(20);

    $sheet->getStyle('B4')
        ->getFont()
        ->setBold(true)
        ->setSize(14);

    $sheet->getStyle('B5')
        ->getFont()
        ->setSize(10);

    $sheet->getStyle('B6')
        ->getFont()
        ->setItalic(true)
        ->setSize(10);

    $sheet->getStyle('B7')
        ->getFont()
        ->setBold(true)
        ->setSize(11)
        ->setName('Arial');

    $sheet->getStyle('B7:M7')
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->getStyle('B1:M7')
        ->getAlignment()
        ->setIndent(1);

    /*
    |--------------------------------------------------------------------------
    | Garis Ganda Kop Surat
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('A8:N8')
        ->getBorders()
        ->getBottom()
        ->setBorderStyle(Border::BORDER_THICK);

    $sheet->getStyle('A9:N9')
        ->getBorders()
        ->getBottom()
        ->setBorderStyle(Border::BORDER_THIN);

    /*
    |--------------------------------------------------------------------------
    | Judul Laporan
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('A10:N11')
    ->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);

$sheet->getStyle('A10')
    ->getFont()
    ->setBold(true)
    ->setSize(16);

$sheet->getStyle('A11')
    ->getFont()
    ->setBold(true)
    ->setSize(12);
    /*
    |--------------------------------------------------------------------------
    | Header Tabel
    |--------------------------------------------------------------------------
    */

        $headerRow = 13;

    $headers = [

        'No',
        'Kode',
        'Tanggal Pinjam',
        'Tanggal Kembali',
        'Jenis',
        'Nama',
        'NIS / NIP',
        'Kelas',
        'Judul Buku',
        'Jumlah Buku',
        'Status',
        'Petugas',
        'Catatan',
        'Jatuh Tempo',

    ];

    $column = 'A';

    foreach ($headers as $header) {

        $sheet->setCellValue($column . $headerRow, $header);

        $column++;

    }

    /*
    |--------------------------------------------------------------------------
    | Style Header Tabel
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle("A{$headerRow}:N{$headerRow}")

        ->getFont()

        ->setBold(true)

        ->setSize(11)

        ->setName('Calibri');

    $sheet->getStyle("A{$headerRow}:N{$headerRow}")

        ->getAlignment()

        ->setHorizontal(Alignment::HORIZONTAL_CENTER)

        ->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->getStyle("A{$headerRow}:N{$headerRow}")

        ->getFill()

        ->setFillType(Fill::FILL_SOLID)

        ->getStartColor()

        ->setARGB('1F4E78');

    $sheet->getStyle("A{$headerRow}:N{$headerRow}")

        ->getFont()

        ->getColor()

        ->setARGB('FFFFFF');

    $sheet->getRowDimension($headerRow)

        ->setRowHeight(32);

    /*
    |--------------------------------------------------------------------------
    | Isi Data
    |--------------------------------------------------------------------------
    */

    $row = $headerRow + 1;

    $no = 1;

    foreach ($laporan as $item) {

        /*
        |--------------------------------------------------------------------------
        | Jenis Peminjam
        |--------------------------------------------------------------------------
        */

        if ($item->siswa) {

            $jenis = 'Siswa';

            $nama = $item->siswa->nama;

            $identitas = $item->siswa->nis;

            $kelas = optional(
                $item->siswa->kelas
            )->nama ?? '-';

        } else {

            $jenis = 'Guru';

            $nama = optional($item->guru)->nama ?? '-';

            $identitas = optional($item->guru)->nip ?? '-';

            $kelas = '-';

        }

        /*
        |--------------------------------------------------------------------------
        | Daftar Buku
        |--------------------------------------------------------------------------
        */

        $daftarBuku = $item

            ->detailPeminjaman

            ->map(function ($detail) {

                return $detail->buku->nama_buku .
                    ' (' . $detail->jumlah . ')';

            })

            ->implode(PHP_EOL);

        /*
        |--------------------------------------------------------------------------
        | Isi Kolom
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue("A{$row}", $no);

        $sheet->setCellValue("B{$row}", $item->kode_peminjaman);

        $sheet->setCellValue(
            "C{$row}",
            optional($item->tanggal_pinjam)?->format('d-m-Y')
        );

        $sheet->setCellValue(
            "D{$row}",
            optional($item->tanggal_kembali)?->format('d-m-Y')
        );

        $sheet->setCellValue("E{$row}", $jenis);

        $sheet->setCellValue("F{$row}", $nama);

        $sheet->setCellValue("G{$row}", $identitas);

        $sheet->setCellValue("H{$row}", $kelas);

        $sheet->setCellValue("I{$row}", $daftarBuku);

        $sheet->setCellValue("J{$row}", $item->jumlah_buku);

        $sheet->setCellValue(
            "K{$row}",
            ucfirst($item->status)
        );

        $sheet->setCellValue(
            "L{$row}",
            optional($item->petugas)->nama
        );

        $sheet->setCellValue(
            "M{$row}",
            $item->catatan
        );

        $sheet->setCellValue(
            "N{$row}",
            optional($item->tanggal_jatuh_tempo)?->format('d-m-Y')
        );

        /*
        |--------------------------------------------------------------------------
        | Tinggi Baris Otomatis
        |--------------------------------------------------------------------------
        */

        $sheet->getRowDimension($row)

            ->setRowHeight(-1);

        $row++;

        $no++;

    }

    /*
    |--------------------------------------------------------------------------
    | Border Data
    |--------------------------------------------------------------------------
    */

    $lastRow = $row - 1;

    $sheet->getStyle("A{$headerRow}:N{$lastRow}")

        ->getBorders()

        ->getAllBorders()

        ->setBorderStyle(Border::BORDER_THIN);


    /*
|--------------------------------------------------------------------------
| Font Seluruh Tabel
|--------------------------------------------------------------------------
*/

$sheet->getStyle("A{$headerRow}:N{$lastRow}")
    ->getFont()
    ->setName('Calibri')
    ->setSize(10);

    /*
    |--------------------------------------------------------------------------
    | Alignment
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle("A{$headerRow}:N{$lastRow}")

        ->getAlignment()

        ->setVertical(Alignment::VERTICAL_TOP);

    $sheet->getStyle("A{$headerRow}:N{$lastRow}")

        ->getAlignment()

        ->setWrapText(true);

    $sheet->getStyle("A{$headerRow}:A{$lastRow}")

        ->getAlignment()

        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->getStyle("C{$headerRow}:E{$lastRow}")

        ->getAlignment()

        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->getStyle("G{$headerRow}:H{$lastRow}")

        ->getAlignment()

        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->getStyle("J{$headerRow}:N{$lastRow}")

        ->getAlignment()

        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    /*
    |--------------------------------------------------------------------------
    | Zebra Stripe
    |--------------------------------------------------------------------------
    */

    for ($i = $headerRow + 1; $i <= $lastRow; $i++) {

        if ($i % 2 == 0) {

            $sheet->getStyle("A{$i}:N{$i}")

                ->getFill()

                ->setFillType(Fill::FILL_SOLID)

                ->getStartColor()

                ->setARGB('F8F9FA');

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Warna Status
    |--------------------------------------------------------------------------
    */

    for ($i = $headerRow + 1; $i <= $lastRow; $i++) {

        $status = strtolower(
            $sheet->getCell("K{$i}")->getValue()
        );

        switch ($status) {

            case 'dipinjam':

                $bg = 'FFF3CD';
                $font = '856404';

                break;

            case 'dikembalikan':

                $bg = 'D4EDDA';
                $font = '155724';

                break;

            case 'terlambat':

                $bg = 'F8D7DA';
                $font = '721C24';

                break;

            default:

                $bg = 'FFFFFF';
                $font = '000000';

        }

        $sheet->getStyle("K{$i}")

            ->getFill()

            ->setFillType(Fill::FILL_SOLID)

            ->getStartColor()

            ->setARGB($bg);

        $sheet->getStyle("K{$i}")

            ->getFont()

            ->getColor()

            ->setARGB($font);

        $sheet->getStyle("K{$i}")

            ->getFont()

            ->setBold(true);

    }

        /*
    |--------------------------------------------------------------------------
    | Ringkasan Laporan
    |--------------------------------------------------------------------------
    */

    $row += 2;

    $sheet->mergeCells("A{$row}:D{$row}");

    $sheet->setCellValue(
        "A{$row}",
        'RINGKASAN LAPORAN'
    );

    $sheet->getStyle("A{$row}:D{$row}")
        ->getFont()
        ->setBold(true)
        ->setSize(12);

    $sheet->getStyle("A{$row}:D{$row}")
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->getStyle("A{$row}:D{$row}")
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB('D9EAD3');

    $sheet->getStyle("A{$row}:D{$row}")
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    $row++;

    $ringkasan = [

        'Total Transaksi'        => $totalTransaksi,
        'Total Buku Dipinjam'    => $totalBuku,
        'Masih Dipinjam'         => $totalDipinjam,
        'Sudah Dikembalikan'     => $totalDikembalikan,
        'Terlambat'              => $totalTerlambat,

    ];

    foreach ($ringkasan as $judul => $nilai) {

        $sheet->setCellValue("A{$row}", $judul);
        $sheet->setCellValue("B{$row}", $nilai);

        $sheet->getStyle("A{$row}:B{$row}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle("A{$row}")
            ->getFont()
            ->setBold(true);

        $row++;

    }

    /*
    |--------------------------------------------------------------------------
    | Footer Tanda Tangan
    |--------------------------------------------------------------------------
    */

    $row += 3;

    $sheet->mergeCells("K{$row}:N{$row}");

    $sheet->setCellValue(
        "K{$row}",
        'Malinau, ' .
        Carbon::now()->translatedFormat('d F Y')
    );

    $sheet->getStyle("K{$row}:N{$row}")
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;

    $sheet->mergeCells("K{$row}:N{$row}");

    $sheet->setCellValue(
        "K{$row}",
        'Petugas Perpustakaan'
    );

    $sheet->getStyle("K{$row}:N{$row}")
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->getStyle("K{$row}")
        ->getFont()
        ->setBold(true);

    /*
    |--------------------------------------------------------------------------
    | Ruang Tanda Tangan
    |--------------------------------------------------------------------------
    */

    $row += 5;

    $sheet->mergeCells("K{$row}:N{$row}");

    $sheet->setCellValue(
        "K{$row}",
        '(....................................................)'
    );

    $sheet->getStyle("K{$row}:N{$row}")
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->getStyle("K{$row}")
        ->getFont()
        ->setBold(true);

    $row++;

    $sheet->mergeCells("K{$row}:N{$row}");

    $sheet->setCellValue(
        "K{$row}",
        'NIP. ................................................'
    );

    $sheet->getStyle("K{$row}:N{$row}")
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    /*
    |--------------------------------------------------------------------------
    | Freeze Pane
    |--------------------------------------------------------------------------
    */

    

    /*
    |--------------------------------------------------------------------------
    | Auto Filter
    |--------------------------------------------------------------------------
    */

    $sheet->setAutoFilter(
        "A{$headerRow}:N{$headerRow}"
    );

    /*
    |--------------------------------------------------------------------------
    | Print Area
    |--------------------------------------------------------------------------
    */

    $sheet->getPageSetup()
        ->setPrintArea(
            "A1:N{$lastRow}"
        );

    /*
    |--------------------------------------------------------------------------
    | Margin Halaman
    |--------------------------------------------------------------------------
    */

    $sheet->getPageMargins()
        ->setTop(0.4)
        ->setRight(0.3)
        ->setLeft(0.3)
        ->setBottom(0.4);

    /*
    |--------------------------------------------------------------------------
    | Page Setup
    |--------------------------------------------------------------------------
    */

    $sheet->getPageSetup()

        ->setOrientation(
            \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
        )

        ->setPaperSize(
            \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
        )

        ->setFitToWidth(1)

        ->setFitToHeight(0);

    /*
    |--------------------------------------------------------------------------
    | Repeat Header Saat Print
    |--------------------------------------------------------------------------
    */

    $sheet->getPageSetup()
        ->setRowsToRepeatAtTopByStartAndEnd(
            $headerRow,
            $headerRow
        );

    /*
    |--------------------------------------------------------------------------
    | Nama File
    |--------------------------------------------------------------------------
    */

    if ($periode === 'bulan') {

        $namaFile =
            'Laporan-Perpustakaan-' .
            $namaBulan[$bulan] .
            '-' .
            $tahun .
            '.xlsx';

    } else {

        $namaFile =
            'Laporan-Perpustakaan-Tahun-' .
            $tahun .
            '.xlsx';

    }

    /*
    |--------------------------------------------------------------------------
    | Download Excel
    |--------------------------------------------------------------------------
    */

    return response()->streamDownload(

        function () use ($spreadsheet) {

            $writer = new Xlsx($spreadsheet);

            $writer->save('php://output');

            $spreadsheet->disconnectWorksheets();

            unset($spreadsheet);

        },

        $namaFile,

        [
            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]

    );
}
}
