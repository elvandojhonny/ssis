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
         */
        $actualHeaders = [];

        foreach (
            $rows[0]->getCells()
            as $cell
        ) {

            $actualHeaders[] =
                strtoupper(
                    trim(
                        $this->getCellText($cell)
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

            $values = [];

            foreach ($cells as $cell) {

                $values[] = trim(
                    $this->getCellText($cell)
                );
            }


            /*
             * Pastikan selalu 9 kolom.
             */
            $values = array_pad(
                $values,
                9,
                ''
            );


            /*
             * Abaikan baris kosong sepenuhnya.
             */
            if (
                collect($values)
                    ->filter(
                        fn ($value) =>
                            $value !== ''
                    )
                    ->isEmpty()
            ) {
                continue;
            }


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


            $baris = $index + 2;

            $kunci = strtoupper(
                trim($kunci)
            );


            /*
             * Validasi setiap soal.
             */
            $validator = Validator::make(
                [
                    'nomor' => $nomor,

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
                        'required',
                        'string',
                    ],

                    'pilihan_a' => [
                        'required',
                        'string',
                    ],

                    'pilihan_b' => [
                        'required',
                        'string',
                    ],

                    'pilihan_c' => [
                        'required',
                        'string',
                    ],

                    'pilihan_d' => [
                        'required',
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
             * Jika jawaban benar E,
             * pilihan E wajib tersedia.
             */
            if (
                $kunci === 'E'
                && $pilihanE === ''
            ) {

                $errors[] =
                    "Baris {$baris}: "
                    . "Kunci jawaban E dipilih, "
                    . "tetapi pilihan E kosong.";

                continue;
            }


            /*
             * Masukkan ke preview.
             */
            $soals[] = [

                'nomor' =>
                    (int) $nomor,

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
                    $pilihanE ?: null,

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
            $nomorSoal->duplicates()->isNotEmpty()
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
        ]);


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

    private function getCellText(
    $cell
): string {

    $texts = [];


    foreach (
        $cell->getElements()
        as $element
    ) {

        /*
         * Teks biasa.
         */
        if ($element instanceof Text) {

            $texts[] =
                $element->getText();

            continue;
        }


        /*
         * TextRun.
         */
        if ($element instanceof TextRun) {

            foreach (
                $element->getElements()
                as $textElement
            ) {

                if (
                    $textElement
                    instanceof Text
                ) {

                    $texts[] =
                        $textElement
                            ->getText();
                }
            }
        }
    }


    return trim(
        implode(
            ' ',
            $texts
        )
    );
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

    DB::transaction(
        function () use (
            $validated,
            $previewSoals,
            $guru
        ) {

            /*
             * Buat Bank Soal.
             */
            $bankSoal = BankSoal::create([
                'guru_id' => $guru->id,

                'judul' =>
                    $validated['judul'],

                'mata_pelajaran' =>
                    $validated['mata_pelajaran'],

                'tingkat' =>
                    $validated['tingkat'],

                'deskripsi' =>
                    $validated['deskripsi'] ?? null,

                'status' => 'siap',

                'nama_file' =>
                    session('cbt_preview_nama_file'),
            ]);

            /*
             * Simpan setiap soal dari preview.
             */
            foreach (
                $previewSoals as $index => $data
            ) {

                Soal::create([
                    'bank_soal_id' =>
                        $bankSoal->id,

                    'nomor' =>
                        $data['nomor']
                        ?? ($index + 1),

                    /*
                     * Untuk sekarang template
                     * menggunakan pilihan ganda.
                     */
                    'tipe' =>
                        'pilihan_ganda',

                    'pertanyaan' =>
                        $data['pertanyaan'],

                    'pilihan_a' =>
                        $data['pilihan_a'],

                    'pilihan_b' =>
                        $data['pilihan_b'],

                    'pilihan_c' =>
                        $data['pilihan_c'],

                    'pilihan_d' =>
                        $data['pilihan_d'],

                    'pilihan_e' =>
                        $data['pilihan_e']
                        ?? null,

                    'jawaban_benar' =>
                        strtoupper(
                            $data['jawaban_benar']
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
     * Hapus data preview setelah
     * berhasil disimpan.
     */
    session()->forget([
        'cbt_preview_soals',
        'cbt_preview_nama_file',
    ]);

    return redirect()
        ->route('cbt.bank-soal.index')
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

}