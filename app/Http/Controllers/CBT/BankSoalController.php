<?php

namespace App\Http\Controllers\CBT;

use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Section;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;

use App\Models\BankSoal;
use App\Models\Soal;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpWord\Element\Image;
use Illuminate\Support\Facades\Storage;

class BankSoalController extends Controller
{

    public function index()
    {
        $guru = auth()->user()->guru;

        abort_unless(
            $guru,
            403,
            'Akun ini tidak memiliki data guru.'
        );

        $bankSoals = BankSoal::query()
            ->where('guru_id', $guru->id)
            ->withCount('soals')
            ->latest()
            ->paginate(10);

        $bankSoals = BankSoal::where(
                'guru_id',
                auth()->user()->guru->id
            )
            ->where('is_archived', false)
            ->withCount('soals')
            ->latest()
            ->paginate(10);

        return view(
            'cbt.bank-soal.index',
            compact('bankSoals')
        );
    }

    /**
     * Download template upload soal CBT.
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $phpWord = new PhpWord();

        /*
         * Halaman landscape agar tabel lebih lebar.
         */
        $section = $phpWord->addSection([
            'orientation' => Section::ORIENTATION_LANDSCAPE,
            'marginTop' => 700,
            'marginBottom' => 700,
            'marginLeft' => 500,
            'marginRight' => 500,
        ]);

        /*
         * Judul.
         */
        $section->addText(
            'TEMPLATE SOAL CBT - SSIS',
            [
                'bold' => true,
                'size' => 16,
            ],
            [
                'alignment' => Jc::CENTER,
            ]
        );

        $section->addText(
            'Petunjuk Pengisian',
            [
                'bold' => true,
                'size' => 11,
            ]
        );

        $section->addListItem(
            'Isi satu soal pada setiap baris.'
        );

        $section->addListItem(
            'Jangan mengubah nama dan urutan kolom.'
        );

        $section->addListItem(
            'Pilihan jawaban A sampai D wajib diisi.'
        );

        $section->addListItem(
            'Pilihan E boleh dikosongkan.'
        );

        $section->addListItem(
            'Kunci jawaban hanya boleh A, B, C, D, atau E.'
        );

        $section->addListItem(
            'Skor harus berupa angka lebih dari 0.'
        );

        $section->addListItem(
            'Tambahkan atau hapus baris sesuai jumlah soal.'
        );

        $section->addTextBreak();


        /*
         * Style tabel.
         */
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 60,
        ];

        $phpWord->addTableStyle(
            'TabelSoal',
            $tableStyle
        );

        $table = $section->addTable('TabelSoal');


        /*
         * Header wajib.
         */
        $headers = [
            'NO',
            'PERTANYAAN',
            'A',
            'B',
            'C',
            'D',
            'E',
            'KUNCI',
            'SKOR',
        ];

        /*
         * Lebar masing-masing kolom.
         */
        $widths = [
            600,
            4200,
            1800,
            1800,
            1800,
            1800,
            1800,
            900,
            900,
        ];


        $table->addRow();

        foreach ($headers as $index => $header) {

            $cell = $table->addCell(
                $widths[$index],
                [
                    'valign' => 'center',
                ]
            );

            $cell->addText(
                $header,
                [
                    'bold' => true,
                    'size' => 9,
                ],
                [
                    'alignment' => Jc::CENTER,
                ]
            );
        }


        /*
         * Contoh soal.
         */
        $contoh = [
            '1',
            'Berapakah hasil dari 2 + 2?',
            '2',
            '3',
            '4',
            '5',
            '',
            'C',
            '5',
        ];

        $table->addRow();

        foreach ($contoh as $index => $value) {

            $table
                ->addCell($widths[$index])
                ->addText(
                    $value,
                    [
                        'size' => 9,
                    ]
                );
        }


        /*
         * Sediakan beberapa baris kosong.
         *
         * Guru boleh menambah atau menghapus baris
         * sesuai jumlah soal.
         */
        for ($nomor = 2; $nomor <= 10; $nomor++) {

            $table->addRow();

            $values = [
                (string) $nomor,
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];

            foreach ($values as $index => $value) {

                $table
                    ->addCell($widths[$index])
                    ->addText(
                        $value,
                        [
                            'size' => 9,
                        ]
                    );
            }
        }


        /*
         * Simpan file sementara.
         */
        $fileName = 'template-soal-cbt-ssis.docx';

        $directory = storage_path(
            'app/temp'
        );

        if (! is_dir($directory)) {
            mkdir(
                $directory,
                0755,
                true
            );
        }

        $path = $directory
            . DIRECTORY_SEPARATOR
            . uniqid('template_', true)
            . '.docx';


        $writer = IOFactory::createWriter(
            $phpWord,
            'Word2007'
        );

        $writer->save($path);


        /*
         * Download dan hapus file sementara
         * setelah selesai dikirim.
         */
        return response()
            ->download(
                $path,
                $fileName
            )
            ->deleteFileAfterSend(true);
    }

public function upload(Request $request)
{
    /*
     * Validasi file terlebih dahulu.
     */
    $request->validate([
        'file_soal' => [
            'required',
            'file',
            'mimes:docx',
            'max:10240',
        ],
    ]);


    try {

        /*
         * Baca file Word.
         */
        $phpWord = IOFactory::load(
            $request
                ->file('file_soal')
                ->getRealPath()
        );


        /*
         * Cari tabel pertama pada dokumen.
         */
        $table = null;

        foreach ($phpWord->getSections() as $section) {

            foreach ($section->getElements() as $element) {

                if ($element instanceof Table) {

                    $table = $element;

                    break 2;
                }
            }
        }


        if (! $table) {

            return back()->with(
                'error',
                'Template tidak valid. Tabel soal tidak ditemukan.'
            );
        }


        /*
         * Ambil seluruh baris tabel.
         */
        $rows = $table->getRows();


        if (count($rows) < 2) {

            return back()->with(
                'error',
                'Template tidak memiliki data soal.'
            );
        }


        /*
         * Header yang diwajibkan.
         */
        $expectedHeaders = [
            'NO',
            'PERTANYAAN',
            'A',
            'B',
            'C',
            'D',
            'E',
            'KUNCI',
            'SKOR',
        ];


        /*
         * Ambil header dari baris pertama.
         *
         * Header tidak boleh mengandung gambar.
         */
        $actualHeaders = [];

        foreach (
            $rows[0]->getCells()
            as $cell
        ) {

            $content = $this->getCellContent(
                $cell,
                'soal'
            );

            $actualHeaders[] =
                strtoupper(
                    trim(
                        $content['text']
                    )
                );
        }


        /*
         * Struktur harus sama persis.
         */
        if ($actualHeaders !== $expectedHeaders) {

            return back()->with(
                'error',
                'Struktur template tidak valid. '
                . 'Jangan mengubah nama atau urutan kolom.'
            );
        }


        /*
         * Baca seluruh soal.
         */
        $soals = [];

        $errors = [];


        foreach (
            array_slice($rows, 1)
            as $index => $row
        ) {

            $cells = $row->getCells();


            /*
             * Teks dan gambar setiap cell.
             */
            $values = [];

            $images = [];


            foreach ($cells as $cell) {

                /*
                 * Baca teks + gambar dari cell.
                 */
                $content = $this->getCellContent(
                    $cell,
                    'soal'
                );


                $values[] =
                    $content['text'];


                $images[] =
                    $content['images'];
            }


            /*
             * Pastikan selalu 9 kolom.
             */
            $values = array_pad(
                $values,
                9,
                ''
            );


            $images = array_pad(
                $images,
                9,
                []
            );


            /*
             * Abaikan baris kosong sepenuhnya.
             *
             * Baris dianggap kosong jika:
             * - tidak memiliki teks
             * - tidak memiliki gambar
             */
            $hasText = collect($values)
                ->filter(
                    fn ($value) =>
                        trim($value) !== ''
                )
                ->isNotEmpty();


            $hasImages = collect($images)
                ->filter(
                    fn ($cellImages) =>
                        ! empty($cellImages)
                )
                ->isNotEmpty();


            if (
                ! $hasText
                && ! $hasImages
            ) {
                continue;
            }


            /*
             * Ambil data teks.
             */
            [
                $nomor,
                $pertanyaan,
                $pilihanA,
                $pilihanB,
                $pilihanC,
                $pilihanD,
                $pilihanE,
                $kunci,
                $skor,
            ] = array_slice(
                $values,
                0,
                9
            );


            /*
             * Ambil data gambar.
             */
            [
                $gambarNomor,
                $gambarPertanyaan,
                $gambarA,
                $gambarB,
                $gambarC,
                $gambarD,
                $gambarE,
                $gambarKunci,
                $gambarSkor,
            ] = array_slice(
                $images,
                0,
                9
            );


            /*
             * Untuk database kita menggunakan
             * gambar pertama dari setiap cell.
             */
            $gambarPertanyaan =
                $gambarPertanyaan[0]
                ?? null;


            $gambarA =
                $gambarA[0]
                ?? null;


            $gambarB =
                $gambarB[0]
                ?? null;


            $gambarC =
                $gambarC[0]
                ?? null;


            $gambarD =
                $gambarD[0]
                ?? null;


            $gambarE =
                $gambarE[0]
                ?? null;


            /*
             * Nomor baris Word.
             */
            $baris = $index + 2;


            /*
             * Normalisasi kunci jawaban.
             */
            $kunci = strtoupper(
                trim($kunci)
            );


            /*
             * Validasi data dasar.
             *
             * Pertanyaan dan pilihan tidak lagi
             * wajib berupa teks karena sekarang
             * dapat berupa gambar.
             */
            $validator = Validator::make(
                [
                    'nomor' =>
                        $nomor,

                    'pertanyaan' =>
                        $pertanyaan,

                    'pilihan_a' =>
                        $pilihanA,

                    'pilihan_b' =>
                        $pilihanB,

                    'pilihan_c' =>
                        $pilihanC,

                    'pilihan_d' =>
                        $pilihanD,

                    'pilihan_e' =>
                        $pilihanE,

                    'kunci' =>
                        $kunci,

                    'skor' =>
                        $skor,
                ],
                [
                    'nomor' => [
                        'required',
                        'integer',
                        'min:1',
                    ],

                    'pertanyaan' => [
                        'nullable',
                        'string',
                    ],

                    'pilihan_a' => [
                        'nullable',
                        'string',
                    ],

                    'pilihan_b' => [
                        'nullable',
                        'string',
                    ],

                    'pilihan_c' => [
                        'nullable',
                        'string',
                    ],

                    'pilihan_d' => [
                        'nullable',
                        'string',
                    ],

                    'pilihan_e' => [
                        'nullable',
                        'string',
                    ],

                    'kunci' => [
                        'required',
                        'in:A,B,C,D,E',
                    ],

                    'skor' => [
                        'required',
                        'numeric',
                        'gt:0',
                    ],
                ]
            );


            if ($validator->fails()) {

                foreach (
                    $validator
                        ->errors()
                        ->all()
                    as $message
                ) {

                    $errors[] =
                        "Baris {$baris}: {$message}";
                }

                continue;
            }


            /*
             * Pertanyaan harus memiliki
             * teks ATAU gambar.
             */
            if (
                trim($pertanyaan) === ''
                && empty($gambarPertanyaan)
            ) {

                $errors[] =
                    "Baris {$baris}: "
                    . "Pertanyaan harus memiliki teks "
                    . "atau gambar.";

                continue;
            }


            /*
             * Pilihan A harus memiliki
             * teks ATAU gambar.
             */
            if (
                trim($pilihanA) === ''
                && empty($gambarA)
            ) {

                $errors[] =
                    "Baris {$baris}: "
                    . "Pilihan A harus memiliki teks "
                    . "atau gambar.";

                continue;
            }


            /*
             * Pilihan B harus memiliki
             * teks ATAU gambar.
             */
            if (
                trim($pilihanB) === ''
                && empty($gambarB)
            ) {

                $errors[] =
                    "Baris {$baris}: "
                    . "Pilihan B harus memiliki teks "
                    . "atau gambar.";

                continue;
            }


            /*
             * Pilihan C harus memiliki
             * teks ATAU gambar.
             */
            if (
                trim($pilihanC) === ''
                && empty($gambarC)
            ) {

                $errors[] =
                    "Baris {$baris}: "
                    . "Pilihan C harus memiliki teks "
                    . "atau gambar.";

                continue;
            }


            /*
             * Pilihan D harus memiliki
             * teks ATAU gambar.
             */
            if (
                trim($pilihanD) === ''
                && empty($gambarD)
            ) {

                $errors[] =
                    "Baris {$baris}: "
                    . "Pilihan D harus memiliki teks "
                    . "atau gambar.";

                continue;
            }


            /*
             * Pilihan E hanya wajib jika
             * kunci jawaban adalah E.
             */
            if (
                $kunci === 'E'
                && trim($pilihanE) === ''
                && empty($gambarE)
            ) {

                $errors[] =
                    "Baris {$baris}: "
                    . "Kunci jawaban E dipilih, "
                    . "tetapi pilihan E tidak memiliki "
                    . "teks atau gambar.";

                continue;
            }


            /*
             * Masukkan ke preview.
             */
            $soals[] = [

                'nomor' =>
                    (int) $nomor,

                /*
                 * Pertanyaan.
                 */
                'pertanyaan' =>
                    $pertanyaan,

                'gambar_pertanyaan' =>
                    $gambarPertanyaan,


                /*
                 * Pilihan A.
                 */
                'pilihan_a' =>
                    $pilihanA,

                'gambar_a' =>
                    $gambarA,


                /*
                 * Pilihan B.
                 */
                'pilihan_b' =>
                    $pilihanB,

                'gambar_b' =>
                    $gambarB,


                /*
                 * Pilihan C.
                 */
                'pilihan_c' =>
                    $pilihanC,

                'gambar_c' =>
                    $gambarC,


                /*
                 * Pilihan D.
                 */
                'pilihan_d' =>
                    $pilihanD,

                'gambar_d' =>
                    $gambarD,


                /*
                 * Pilihan E.
                 */
                'pilihan_e' =>
                    $pilihanE ?: null,

                'gambar_e' =>
                    $gambarE,


                /*
                 * Jawaban dan skor.
                 */
                'jawaban_benar' =>
                    $kunci,

                'skor' =>
                    (float) $skor,
            ];
        }


        /*
         * Jika ada error satu saja,
         * seluruh file dianggap gagal.
         */
        if (! empty($errors)) {

            return back()
                ->withInput()
                ->with(
                    'upload_errors',
                    $errors
                );
        }


        /*
         * Pastikan ada soal valid.
         */
        if (empty($soals)) {

            return back()->with(
                'error',
                'Tidak ditemukan soal yang dapat diproses.'
            );
        }


        /*
         * Pastikan nomor soal unik.
         */
        $nomorSoal = collect(
            $soals
        )->pluck('nomor');


        if (
            $nomorSoal
                ->duplicates()
                ->isNotEmpty()
        ) {

            return back()->with(
                'error',
                'Nomor soal tidak boleh duplikat.'
            );
        }


        /*
         * Urutkan berdasarkan nomor soal.
         */
        $soals = collect($soals)
            ->sortBy('nomor')
            ->values()
            ->all();


        /*
         * Simpan sementara di session.
         *
         * Belum masuk database.
         */
        session([
            'cbt_preview_soals' =>
                $soals,

            'cbt_preview_nama_file' =>
                $request
                    ->file('file_soal')
                    ->getClientOriginalName(),
        ]);


        /*
         * Redirect ke halaman bank soal.
         */
        return redirect()
            ->route(
                'cbt.bank-soal.index'
            )
            ->with(
                'success',
                count($soals)
                . ' soal berhasil dibaca. '
                . 'Silakan periksa preview sebelum disimpan.'
            );


    } catch (\Throwable $exception) {

        report($exception);

        return back()->with(
            'error',
            'File Word gagal diproses. '
            . 'Pastikan menggunakan template resmi SSIS.'
        );
    }
}

private function getCellContent($cell, string $folder = 'soal'): array
{
    $texts = [];
    $images = [];

    foreach ($cell->getElements() as $element) {

        /*
        |--------------------------------------------------------------------------
        | TEXT BIASA
        |--------------------------------------------------------------------------
        */
        if ($element instanceof Text) {

            $text = trim($element->getText());

            if ($text !== '') {
                $texts[] = $text;
            }

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | TEXT RUN
        |--------------------------------------------------------------------------
        */
        if ($element instanceof TextRun) {

            foreach ($element->getElements() as $textElement) {

                if ($textElement instanceof Text) {

                    $text = trim($textElement->getText());

                    if ($text !== '') {
                        $texts[] = $text;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | GAMBAR DI DALAM TEXT RUN
                |--------------------------------------------------------------------------
                */
                if ($textElement instanceof Image) {

                    $images[] = $this->savePhpWordImage(
                        $textElement,
                        $folder
                    );
                }
            }

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | GAMBAR LANGSUNG DI DALAM CELL
        |--------------------------------------------------------------------------
        */
        if ($element instanceof Image) {

            $images[] = $this->savePhpWordImage(
                $element,
                $folder
            );

            continue;
        }
    }


    return [
        'text' => trim(
            implode(' ', $texts)
        ),

        'images' => array_values(
            array_filter($images)
        ),
    ];
}

private function savePhpWordImage(
    Image $image,
    string $folder = 'soal'
): ?string {

    try {

        /*
        |--------------------------------------------------------------------------
        | Ambil source gambar dari PHPWord
        |--------------------------------------------------------------------------
        */
        $source = $image->getSource();

        if (! $source) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | PHPWord sudah mengetahui extension gambar
        |--------------------------------------------------------------------------
        |
        | Contoh:
        | png
        | jpg
        | gif
        | bmp
        | tif
        |
        */
        $extension = strtolower(
            $image->getImageExtension()
        );


        /*
        |--------------------------------------------------------------------------
        | Fallback extension
        |--------------------------------------------------------------------------
        */
        if (! in_array(
            $extension,
            [
                'jpg',
                'jpeg',
                'png',
                'gif',
                'bmp',
                'tif',
                'tiff',
            ],
            true
        )) {

            $extension = 'png';
        }


        /*
        |--------------------------------------------------------------------------
        | Nama file unik
        |--------------------------------------------------------------------------
        */
        $fileName =
            'soal_'
            . uniqid('', true)
            . '.'
            . $extension;


        /*
        |--------------------------------------------------------------------------
        | Folder penyimpanan
        |--------------------------------------------------------------------------
        */
        $directory =
            storage_path(
                'app/public/'
                . $folder
            );


        /*
        |--------------------------------------------------------------------------
        | Buat folder jika belum ada
        |--------------------------------------------------------------------------
        */
        if (! is_dir($directory)) {

            if (! mkdir(
                $directory,
                0755,
                true
            ) && ! is_dir($directory)) {

                return null;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Path tujuan
        |--------------------------------------------------------------------------
        */
        $destination =
            $directory
            . DIRECTORY_SEPARATOR
            . $fileName;


        /*
        |--------------------------------------------------------------------------
        | Ambil binary gambar dari PHPWord
        |--------------------------------------------------------------------------
        |
        | Ini lebih aman untuk gambar yang berasal
        | dari dalam file DOCX.
        |
        */
        $binary = null;


        /*
        |--------------------------------------------------------------------------
        | Source berupa file biasa
        |--------------------------------------------------------------------------
        */
        if (
            is_string($source)
            && is_file($source)
        ) {

            $binary =
                file_get_contents($source);

        } else {

            /*
            |--------------------------------------------------------------------------
            | Source bisa berupa:
            |
            | zip://...
            | string binary
            | archive image
            |--------------------------------------------------------------------------
            */
            $binary =
                @file_get_contents($source);
        }


        /*
        |--------------------------------------------------------------------------
        | Pastikan binary berhasil dibaca
        |--------------------------------------------------------------------------
        */
        if (
            $binary === false
            || $binary === null
            || $binary === ''
        ) {

            /*
            |--------------------------------------------------------------------------
            | Coba menggunakan API PHPWord
            |--------------------------------------------------------------------------
            */
            if (
                method_exists(
                    $image,
                    'getImageString'
                )
            ) {

                $binary =
                    $image->getImageString();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Jika tetap gagal
        |--------------------------------------------------------------------------
        */
        if (
            $binary === false
            || $binary === null
            || $binary === ''
        ) {

            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | Simpan binary gambar
        |--------------------------------------------------------------------------
        */
        $saved =
            file_put_contents(
                $destination,
                $binary
            );


        if ($saved === false) {

            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | Pastikan file benar-benar ada
        |--------------------------------------------------------------------------
        */
        if (! is_file($destination)) {

            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | Return path relatif terhadap storage/app/public
        |--------------------------------------------------------------------------
        */
        return $folder
            . '/'
            . $fileName;


    } catch (\Throwable $exception) {

        report($exception);

        return null;
    }
}

public function store(Request $request)
{
    /*
     * Pastikan data preview masih tersedia.
     */
    $previewSoals = session('cbt_preview_soals');

    if (
        ! is_array($previewSoals)
        || empty($previewSoals)
    ) {
        return redirect()
            ->route('cbt.bank-soal.index')
            ->with(
                'error',
                'Data preview soal tidak ditemukan. Silakan upload ulang file soal.'
            );
    }


    /*
     * Validasi identitas Bank Soal.
     */
    $validated = $request->validate([
        'judul' => [
            'required',
            'string',
            'max:255',
        ],

        'mata_pelajaran' => [
            'required',
            'string',
            'max:255',
        ],

        'tingkat' => [
            'required',
            'integer',
            'in:10,11,12',
        ],

        'deskripsi' => [
            'nullable',
            'string',
            'max:2000',
        ],
    ]);


    /*
     * Ambil data guru dari user login.
     */
    $guru = auth()
        ->user()
        ->guru;


    if (! $guru) {

        abort(
            403,
            'Akun ini tidak memiliki data guru.'
        );
    }


    /*
     * Simpan Bank Soal + seluruh soal
     * dalam satu transaction.
     */
    DB::transaction(
        function () use (
            $validated,
            $previewSoals,
            $guru
        ) {

            /*
             * ==========================================================
             * BUAT BANK SOAL
             * ==========================================================
             */
            $bankSoal = BankSoal::create([

                'guru_id' =>
                    $guru->id,

                'judul' =>
                    $validated['judul'],

                'mata_pelajaran' =>
                    $validated['mata_pelajaran'],

                'tingkat' =>
                    $validated['tingkat'],

                'deskripsi' =>
                    $validated['deskripsi']
                    ?? null,

                'status' =>
                    'siap',

                'nama_file' =>
                    session(
                        'cbt_preview_nama_file'
                    ),
            ]);


            /*
             * ==========================================================
             * SIMPAN SETIAP SOAL
             * ==========================================================
             */
            foreach (
                $previewSoals as $index => $data
            ) {

                Soal::create([

                    'bank_soal_id' =>
                        $bankSoal->id,


                    /*
                     * Nomor soal.
                     */
                    'nomor' =>
                        $data['nomor']
                        ?? ($index + 1),


                    /*
                     * Tipe soal.
                     */
                    'tipe' =>
                        'pilihan_ganda',


                    /*
                     * ==================================================
                     * PERTANYAAN
                     * ==================================================
                     */

                    'pertanyaan' =>
                        $data['pertanyaan']
                        ?? null,

                    'gambar_pertanyaan' =>
                        $data['gambar_pertanyaan']
                        ?? null,


                    /*
                     * ==================================================
                     * PILIHAN A
                     * ==================================================
                     */

                    'pilihan_a' =>
                        $data['pilihan_a']
                        ?? null,

                    'gambar_a' =>
                        $data['gambar_a']
                        ?? null,


                    /*
                     * ==================================================
                     * PILIHAN B
                     * ==================================================
                     */

                    'pilihan_b' =>
                        $data['pilihan_b']
                        ?? null,

                    'gambar_b' =>
                        $data['gambar_b']
                        ?? null,


                    /*
                     * ==================================================
                     * PILIHAN C
                     * ==================================================
                     */

                    'pilihan_c' =>
                        $data['pilihan_c']
                        ?? null,

                    'gambar_c' =>
                        $data['gambar_c']
                        ?? null,


                    /*
                     * ==================================================
                     * PILIHAN D
                     * ==================================================
                     */

                    'pilihan_d' =>
                        $data['pilihan_d']
                        ?? null,

                    'gambar_d' =>
                        $data['gambar_d']
                        ?? null,


                    /*
                     * ==================================================
                     * PILIHAN E
                     * ==================================================
                     */

                    'pilihan_e' =>
                        $data['pilihan_e']
                        ?? null,

                    'gambar_e' =>
                        $data['gambar_e']
                        ?? null,


                    /*
                     * ==================================================
                     * JAWABAN BENAR
                     * ==================================================
                     */

                    'jawaban_benar' =>
                        strtoupper(
                            $data['jawaban_benar']
                            ?? ''
                        ),


                    /*
                     * Preview menggunakan "skor",
                     * database menggunakan "bobot".
                     */
                    'bobot' =>
                        $data['skor'],
                ]);
            }
        }
    );


    /*
     * ==============================================================
     * HAPUS PREVIEW DARI SESSION
     * ==============================================================
     *
     * Dilakukan setelah transaction berhasil.
     */
    session()->forget([
        'cbt_preview_soals',
        'cbt_preview_nama_file',
    ]);


    /*
     * Kembali ke halaman Bank Soal.
     */
    return redirect()
        ->route(
            'cbt.bank-soal.index'
        )
        ->with(
            'success',
            'Bank soal berhasil disimpan.'
        );
}

public function show(BankSoal $bankSoal)
{
    $guru = auth()->user()->guru;

    abort_unless(
        $guru
        && (int) $bankSoal->guru_id === (int) $guru->id,
        403,
        'Anda tidak memiliki akses ke bank soal ini.'
    );

    $bankSoal->load([
        'guru.user',
        'soals' => function ($query) {
            $query->orderBy('nomor');
        },
    ]);

    return view(
        'cbt.bank-soal.show',
        compact('bankSoal')
    );
}

public function arsip()
{
    $guru = auth()
        ->user()
        ->guru;

    abort_unless($guru, 403);


    $bankSoals = BankSoal::query()

        ->where(
            'guru_id',
            $guru->id
        )

        ->where(
            'is_archived',
            true
        )

        ->withCount('soals')

        ->latest('updated_at')

        ->paginate(10);


    return view(
        'cbt.bank-soal.arsip',
        compact('bankSoals')
    );
}

public function archive(BankSoal $bankSoal)
{
    $guru = auth()
        ->user()
        ->guru;


    abort_unless(
        $guru
        && $bankSoal->guru_id === $guru->id,
        403
    );


    $bankSoal->update([
        'is_archived' => true,
    ]);


    return redirect()
        ->route('cbt.bank-soal.index')
        ->with(
            'success',
            'Bank soal berhasil dipindahkan ke arsip.'
        );
}

public function restore(BankSoal $bankSoal)
{
    $guru = auth()
        ->user()
        ->guru;


    abort_unless(
        $guru
        && $bankSoal->guru_id === $guru->id,
        403
    );


    $bankSoal->update([
        'is_archived' => false,
    ]);


    return redirect()
        ->route('cbt.bank-soal.arsip')
        ->with(
            'success',
            'Bank soal berhasil dipulihkan.'
        );
}

public function createSoal(BankSoal $bankSoal)
{
    $guru = auth()->user()->guru;

    abort_unless(
        $guru &&
        $bankSoal->guru_id == $guru->id,
        403
    );

    return view(
        'cbt.bank-soal.create-soal',
        compact('bankSoal')
    );
}

public function storeSoal(Request $request, BankSoal $bankSoal)
{
    $guru = auth()->user()->guru;

    abort_unless(
        $guru &&
        $bankSoal->guru_id == $guru->id,
        403
    );

    $rules = [
        'tipe' => 'required|in:pilihan_ganda,essay',
        'pertanyaan' => 'required|string',
        'bobot' => 'required|numeric|min:1',
    ];

    if ($request->tipe == 'pilihan_ganda') {

        $rules = array_merge($rules, [

            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'pilihan_e' => 'nullable|string',

            'jawaban_benar' => 'required|in:A,B,C,D,E',

        ]);

    }

    $validated = $request->validate($rules);

    $nomor = $bankSoal->soals()->max('nomor') + 1;

    Soal::create([

        'bank_soal_id' => $bankSoal->id,

        'nomor' => $nomor,

        'tipe' => $validated['tipe'],

        'pertanyaan' => $validated['pertanyaan'],

        'pilihan_a' => $validated['pilihan_a'] ?? null,
        'pilihan_b' => $validated['pilihan_b'] ?? null,
        'pilihan_c' => $validated['pilihan_c'] ?? null,
        'pilihan_d' => $validated['pilihan_d'] ?? null,
        'pilihan_e' => $validated['pilihan_e'] ?? null,

        'jawaban_benar' => $validated['jawaban_benar'] ?? null,

        'bobot' => $validated['bobot'],

    ]);

    return redirect()
        ->route(
            'cbt.bank-soal.show',
            $bankSoal
        )
        ->with(
            'success',
            'Soal berhasil ditambahkan.'
        );
}

}