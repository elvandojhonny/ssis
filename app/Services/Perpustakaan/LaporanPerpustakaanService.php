<?php

namespace App\Services\Perpustakaan;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanExportService
{
    public function export(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        $periode = $request->periode ?? 'bulan';
        $bulan   = (int) ($request->bulan ?? now()->month);
        $tahun   = (int) ($request->tahun ?? now()->year);

        /*
        |--------------------------------------------------------------------------
        | Query
        |--------------------------------------------------------------------------
        */

        $query = Peminjaman::with([
            'petugas',
            'guru',
            'siswa.kelas',
            'detailPeminjaman.buku',
        ]);

        if ($periode == 'bulan') {

            $query
                ->whereYear('tanggal_pinjam', $tahun)
                ->whereMonth('tanggal_pinjam', $bulan);

        } else {

            $query
                ->whereYear('tanggal_pinjam', $tahun);

        }

        $laporan = $query
            ->latest('tanggal_pinjam')
            ->latest('id')
            ->get();

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
            ->setDescription('Laporan Transaksi Perpustakaan');

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Laporan');

        /*
        |--------------------------------------------------------------------------
        | Logo Sekolah
        |--------------------------------------------------------------------------
        */

        $logo = public_path('images/logo.png');

        if (file_exists($logo)) {

            $drawing = new Drawing();

            $drawing->setName('Logo');

            $drawing->setDescription('Logo Sekolah');

            $drawing->setPath($logo);

            $drawing->setHeight(70);

            $drawing->setCoordinates('A1');

            $drawing->setWorksheet($sheet);

        }

        /*
        |--------------------------------------------------------------------------
        | Kop Laporan
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:M1');
        $sheet->mergeCells('A2:M2');
        $sheet->mergeCells('A3:M3');
        $sheet->mergeCells('A4:M4');

        $sheet->setCellValue(
            'A1',
            'SMA NEGERI 6'
        );

        $sheet->setCellValue(
            'A2',
            'PERPUSTAKAAN SEKOLAH'
        );

        $sheet->setCellValue(
            'A3',
            'LAPORAN TRANSAKSI PERPUSTAKAAN'
        );

        if ($periode == 'bulan') {

            $sheet->setCellValue(
                'A4',
                'Periode : ' .
                Carbon::create()
                    ->month($bulan)
                    ->translatedFormat('F') .
                ' ' .
                $tahun
            );

        } else {

            $sheet->setCellValue(
                'A4',
                'Periode : Tahun ' .
                $tahun
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Style Judul
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A1:M4')

            ->getAlignment()

            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle('A1:M4')

            ->getFont()

            ->setBold(true);

        $sheet->getStyle('A1')

            ->getFont()

            ->setSize(16);

        $sheet->getStyle('A2')

            ->getFont()

            ->setSize(13);

        $sheet->getStyle('A3')

            ->getFont()

            ->setSize(14);

        /*
        |--------------------------------------------------------------------------
        | Header Tabel
        |--------------------------------------------------------------------------
        */

        $row = 6;

        $sheet->fromArray([[
            'No',
            'Kode',
            'Tanggal Pinjam',
            'Jatuh Tempo',
            'Tanggal Kembali',
            'Jenis',
            'Nama',
            'NIS / NIP',
            'Kelas',
            'Daftar Buku',
            'Jumlah',
            'Status',
            'Petugas',
            'Catatan'
        ]], null, 'A'.$row);

        $sheet->getStyle("A{$row}:N{$row}")

            ->getFont()

            ->setBold(true);

        $sheet->getStyle("A{$row}:N{$row}")

            ->getAlignment()

            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle("A{$row}:N{$row}")

            ->getFill()

            ->setFillType(Fill::FILL_SOLID)

            ->getStartColor()

            ->setARGB('1F4E78');

        $sheet->getStyle("A{$row}:N{$row}")

            ->getFont()

            ->getColor()

            ->setARGB('FFFFFF');

        $sheet->getStyle("A{$row}:N{$row}")

            ->getBorders()

            ->getAllBorders()

            ->setBorderStyle(Border::BORDER_THIN);

        $row++;
        $no = 1;

        /*
|--------------------------------------------------------------------------
| Isi Data Laporan
|--------------------------------------------------------------------------
*/

$totalTransaksi     = 0;
$totalBuku          = 0;
$totalDipinjam      = 0;
$totalDikembalikan  = 0;
$totalTerlambat     = 0;

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

        $nama = optional(
            $item->guru
        )->nama ?? '-';

        $identitas = optional(
            $item->guru
        )->nip ?? '-';

        $kelas = '-';

    }

    /*
    |--------------------------------------------------------------------------
    | Daftar Buku
    |--------------------------------------------------------------------------
    */

    $buku = [];

    foreach ($item->detailPeminjaman as $detail) {

        $buku[] =
            $detail->buku->nama_buku .
            " ({$detail->jumlah})";

    }

    $daftarBuku = implode(
        PHP_EOL,
        $buku
    );

    /*
    |--------------------------------------------------------------------------
    | Statistik
    |--------------------------------------------------------------------------
    */

    $totalTransaksi++;

    $totalBuku += $item->jumlah_buku;

    switch ($item->status) {

        case 'dipinjam':
            $totalDipinjam++;
            break;

        case 'dikembalikan':
            $totalDikembalikan++;
            break;

        case 'terlambat':
            $totalTerlambat++;
            break;

    }

    /*
    |--------------------------------------------------------------------------
    | Isi Excel
    |--------------------------------------------------------------------------
    */

    $sheet->setCellValue(
        'A'.$row,
        $no
    );

    $sheet->setCellValue(
        'B'.$row,
        $item->kode_peminjaman
    );

    $sheet->setCellValue(
        'C'.$row,
        optional($item->tanggal_pinjam)
            ? $item->tanggal_pinjam->format('d-m-Y')
            : '-'
    );

    $sheet->setCellValue(
        'D'.$row,
        optional($item->tanggal_jatuh_tempo)
            ? $item->tanggal_jatuh_tempo->format('d-m-Y')
            : '-'
    );

    $sheet->setCellValue(
        'E'.$row,
        optional($item->tanggal_kembali)
            ? $item->tanggal_kembali->format('d-m-Y')
            : '-'
    );

    $sheet->setCellValue(
        'F'.$row,
        $jenis
    );

    $sheet->setCellValue(
        'G'.$row,
        $nama
    );

    $sheet->setCellValue(
        'H'.$row,
        $identitas
    );

    $sheet->setCellValue(
        'I'.$row,
        $kelas
    );

    $sheet->setCellValue(
        'J'.$row,
        $daftarBuku
    );

    $sheet->setCellValue(
        'K'.$row,
        $item->jumlah_buku
    );

    $sheet->setCellValue(
        'L'.$row,
        ucfirst($item->status)
    );

    $sheet->setCellValue(
        'M'.$row,
        optional($item->petugas)->nama
    );

    $sheet->setCellValue(
        'N'.$row,
        $item->catatan
    );

    /*
    |--------------------------------------------------------------------------
    | Style Baris
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle(
        "A{$row}:N{$row}"
    )
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(
        Border::BORDER_THIN
    );

    $sheet->getStyle(
        "A{$row}:N{$row}"
    )
    ->getAlignment()
    ->setVertical(
        Alignment::VERTICAL_TOP
    );

    $sheet->getStyle(
        "J{$row}:N{$row}"
    )
    ->getAlignment()
    ->setWrapText(true);

    /*
    |--------------------------------------------------------------------------
    | Warna Status
    |--------------------------------------------------------------------------
    */

    $warna = match ($item->status) {

        'dipinjam'      => 'FFF3CD',

        'dikembalikan'  => 'D4EDDA',

        'terlambat'     => 'F8D7DA',

        default         => 'FFFFFF',

    };

    $sheet->getStyle(
        "L{$row}"
    )
    ->getFill()
    ->setFillType(
        Fill::FILL_SOLID
    )
    ->getStartColor()
    ->setARGB($warna);

    $sheet
        ->getRowDimension($row)
        ->setRowHeight(-1);

    $row++;

    $no++;
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
    "RINGKASAN LAPORAN"
);

$sheet->getStyle("A{$row}:D{$row}")
    ->getFont()
    ->setBold(true)
    ->setSize(12);

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

    'Total Transaksi'    => $totalTransaksi,
    'Total Buku'         => $totalBuku,
    'Masih Dipinjam'     => $totalDipinjam,
    'Sudah Dikembalikan' => $totalDikembalikan,
    'Terlambat'          => $totalTerlambat,

];

foreach ($ringkasan as $judul => $nilai) {

    $sheet->setCellValue(
        "A{$row}",
        $judul
    );

    $sheet->setCellValue(
        "B{$row}",
        $nilai
    );

    $sheet->getStyle("A{$row}:B{$row}")
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    $row++;

}

/*
|--------------------------------------------------------------------------
| Footer
|--------------------------------------------------------------------------
*/

$row += 2;

$sheet->mergeCells("K{$row}:N{$row}");

$sheet->setCellValue(
    "K{$row}",
    "Malinau, " . Carbon::now()->translatedFormat('d F Y')
);

$row++;

$sheet->mergeCells("K{$row}:N{$row}");

$sheet->setCellValue(
    "K{$row}",
    "Kepala Perpustakaan"
);

$row += 5;

$sheet->mergeCells("K{$row}:N{$row}");

$sheet->setCellValue(
    "K{$row}",
    "(........................................)"
);

$row++;

$sheet->mergeCells("K{$row}:N{$row}");

$sheet->setCellValue(
    "K{$row}",
    "NIP. ................................"
);

/*
|--------------------------------------------------------------------------
| Style Footer
|--------------------------------------------------------------------------
*/

$sheet->getStyle("K1:N{$row}")
    ->getAlignment()
    ->setHorizontal(
        Alignment::HORIZONTAL_CENTER
    );

/*
|--------------------------------------------------------------------------
| Auto Width
|--------------------------------------------------------------------------
*/

foreach (range('A', 'N') as $column) {

    $sheet
        ->getColumnDimension($column)
        ->setAutoSize(true);

}

/*
|--------------------------------------------------------------------------
| Freeze Header
|--------------------------------------------------------------------------
*/

$sheet->freezePane('A7');

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

    ->setFitToWidth(1);

$sheet->getPageMargins()

    ->setTop(0.3)
    ->setBottom(0.3)
    ->setLeft(0.25)
    ->setRight(0.25);

/*
|--------------------------------------------------------------------------
| Print Area
|--------------------------------------------------------------------------
*/

$sheet->getPageSetup()

    ->setPrintArea(
        "A1:N{$row}"
    );

/*
|--------------------------------------------------------------------------
| Nama File
|--------------------------------------------------------------------------
*/

if ($periode == 'bulan') {

    $filename =
        'Laporan-Perpustakaan-' .
        Carbon::create()
            ->month($bulan)
            ->translatedFormat('F') .
        '-' .
        $tahun .
        '.xlsx';

} else {

    $filename =
        'Laporan-Perpustakaan-Tahun-' .
        $tahun .
        '.xlsx';

}

/*
|--------------------------------------------------------------------------
| Download
|--------------------------------------------------------------------------
*/

$writer = new Xlsx($spreadsheet);

return new StreamedResponse(

    function () use ($writer) {

        $writer->save('php://output');

    },

    200,

    [

        'Content-Type' =>
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

        'Content-Disposition' =>
            'attachment; filename="' . $filename . '"',

        'Cache-Control' => 'max-age=0',

    ]

);