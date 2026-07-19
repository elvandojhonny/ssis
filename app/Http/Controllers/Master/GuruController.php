<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')
            ->latest()
            ->paginate(10);

        return view('master.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('master.guru.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => ['nullable', 'string', 'max:50', 'unique:guru,nip'],
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated) {
            $isActive = (bool) ($validated['is_active'] ?? false);

            $user = User::create([
                'name' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
                'password' => $validated['password'],
                'role' => 'guru',
                'is_active' => $isActive,
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'] ?? null,
                'nama' => $validated['nama'],
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'is_active' => $isActive,
            ]);
        });

        return redirect()
            ->route('guru.index')
            ->with('success', 'Data dan akun guru berhasil dibuat.');
    }

    public function edit(Guru $guru)
    {
        $guru->load('user');

        return view('master.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nip' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('guru', 'nip')->ignore($guru->id),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($guru->user_id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($guru->user_id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $guru) {
            $isActive = (bool) ($validated['is_active'] ?? false);

            $userData = [
                'name' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
                'is_active' => $isActive,
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }

            $guru->user->update($userData);

            $guru->update([
                'nip' => $validated['nip'] ?? null,
                'nama' => $validated['nama'],
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'is_active' => $isActive,
            ]);
        });

        return redirect()
            ->route('guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function downloadTemplate()
{
    $spreadsheet = new Spreadsheet();

    /*
    |--------------------------------------------------------------------------
    | Sheet Data Guru
    |--------------------------------------------------------------------------
    */

    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Data Guru');


    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    $headers = [
        'Nama Guru',
        'NIP',
        'Username',
        'Email',
        'Password',
        'Jenis Kelamin',
        'Nomor HP',
        'Alamat',
        'Status',
    ];

    foreach ($headers as $index => $header) {

        $column = chr(65 + $index);

        $sheet->setCellValue(
            $column . '1',
            $header
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Style Header
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getStyle('A1:I1')
        ->getFont()
        ->setBold(true);

    $sheet
        ->getStyle('A1:I1')
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet
        ->getStyle('A1:I1')
        ->getAlignment()
        ->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );

    $sheet
        ->getRowDimension(1)
        ->setRowHeight(25);


    /*
    |--------------------------------------------------------------------------
    | Freeze Header
    |--------------------------------------------------------------------------
    */

    $sheet->freezePane('A2');


    /*
    |--------------------------------------------------------------------------
    | Lebar Kolom
    |--------------------------------------------------------------------------
    */

    $columnWidths = [
        'A' => 28,
        'B' => 24,
        'C' => 22,
        'D' => 32,
        'E' => 20,
        'F' => 18,
        'G' => 20,
        'H' => 35,
        'I' => 18,
    ];

    foreach ($columnWidths as $column => $width) {

        $sheet
            ->getColumnDimension($column)
            ->setWidth($width);

    }


    /*
    |--------------------------------------------------------------------------
    | Format NIP dan Nomor HP sebagai Text
    |--------------------------------------------------------------------------
    |
    | Mencegah angka panjang berubah menjadi format scientific notation
    | dan menjaga angka 0 di awal nomor HP.
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getStyle('B2:B1000')
        ->getNumberFormat()
        ->setFormatCode('@');

    $sheet
        ->getStyle('G2:G1000')
        ->getNumberFormat()
        ->setFormatCode('@');


    /*
    |--------------------------------------------------------------------------
    | Dropdown Jenis Kelamin
    |--------------------------------------------------------------------------
    */

    for ($row = 2; $row <= 1000; $row++) {

        $validation = $sheet
            ->getCell('F' . $row)
            ->getDataValidation();

        $validation->setType(
            DataValidation::TYPE_LIST
        );

        $validation->setErrorStyle(
            DataValidation::STYLE_STOP
        );

        $validation->setAllowBlank(true);

        $validation->setShowDropDown(true);

        $validation->setShowErrorMessage(true);

        $validation->setErrorTitle(
            'Jenis kelamin tidak valid'
        );

        $validation->setError(
            'Pilih L untuk Laki-laki atau P untuk Perempuan.'
        );

        $validation->setFormula1(
            '"L,P"'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Dropdown Status
    |--------------------------------------------------------------------------
    */

    for ($row = 2; $row <= 1000; $row++) {

        $validation = $sheet
            ->getCell('I' . $row)
            ->getDataValidation();

        $validation->setType(
            DataValidation::TYPE_LIST
        );

        $validation->setErrorStyle(
            DataValidation::STYLE_STOP
        );

        $validation->setAllowBlank(false);

        $validation->setShowDropDown(true);

        $validation->setShowErrorMessage(true);

        $validation->setErrorTitle(
            'Status tidak valid'
        );

        $validation->setError(
            'Pilih status Aktif atau Tidak Aktif.'
        );

        $validation->setFormula1(
            '"Aktif,Tidak Aktif"'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Sheet Petunjuk
    |--------------------------------------------------------------------------
    */

    $referensi = $spreadsheet->createSheet();

    $referensi->setTitle('Petunjuk');


    $referensi->setCellValue(
        'A1',
        'Petunjuk Pengisian Template Guru'
    );

    $referensi
        ->getStyle('A1')
        ->getFont()
        ->setBold(true);


    /*
    |--------------------------------------------------------------------------
    | Petunjuk Pengisian
    |--------------------------------------------------------------------------
    */

    $petunjuk = [
        'Nama Guru wajib diisi.',
        'NIP boleh dikosongkan, tetapi jika diisi harus unik.',
        'Username wajib diisi dan harus unik.',
        'Email boleh dikosongkan, tetapi jika diisi harus unik.',
        'Password wajib diisi minimal 8 karakter.',
        'Jenis Kelamin menggunakan L atau P.',
        'Nomor HP boleh dikosongkan.',
        'Alamat boleh dikosongkan.',
        'Status menggunakan Aktif atau Tidak Aktif.',
        'Mulai isi data guru pada baris ke-2 di sheet Data Guru.',
    ];


    foreach ($petunjuk as $index => $text) {

        $referensi->setCellValue(
            'A' . ($index + 3),
            ($index + 1) . '. ' . $text
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Lebar Sheet Petunjuk
    |--------------------------------------------------------------------------
    */

    $referensi
        ->getColumnDimension('A')
        ->setWidth(85);


    /*
    |--------------------------------------------------------------------------
    | Kembali ke Sheet Data Guru
    |--------------------------------------------------------------------------
    */

    $spreadsheet->setActiveSheetIndex(0);


    /*
    |--------------------------------------------------------------------------
    | Download File
    |--------------------------------------------------------------------------
    */

    $filename =
        'template_import_guru_'
        . date('Y-m-d')
        . '.xlsx';


    $writer = new Xlsx(
        $spreadsheet
    );


    $tempFile = tempnam(
        sys_get_temp_dir(),
        'guru_'
    );


    $writer->save(
        $tempFile
    );


    return response()
        ->download(
            $tempFile,
            $filename
        )
        ->deleteFileAfterSend(true);
}


public function import(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Validasi File
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'file_import' => [
            'required',
            'file',
            'mimes:xlsx,xls',
            'max:5120',
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | Baca Excel
    |--------------------------------------------------------------------------
    */

    try {
        $spreadsheet = IOFactory::load(
            $request->file('file_import')->getRealPath()
        );

        $rows = $spreadsheet
            ->getSheetByName('Data Guru')
            ?->toArray();

        if (! $rows) {
            return back()->with(
                'error',
                'Sheet "Data Guru" tidak ditemukan atau file kosong.'
            );
        }
    } catch (\Throwable $e) {
        return back()->with(
            'error',
            'File Excel tidak dapat dibaca.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Proses Data
    |--------------------------------------------------------------------------
    */

    $importErrors = [];

    $berhasil = 0;

    // Lewati header.
    unset($rows[0]);


    foreach ($rows as $index => $row) {
        $nomorBaris = $index + 1;

        /*
         * Lewati baris kosong.
         */

        if (
            empty(trim((string) ($row[0] ?? ''))) &&
            empty(trim((string) ($row[1] ?? ''))) &&
            empty(trim((string) ($row[2] ?? '')))
        ) {
            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil Data
        |--------------------------------------------------------------------------
        */

        $data = [
            'nama' => trim(
                (string) ($row[0] ?? '')
            ),

            'nip' => trim(
                (string) ($row[1] ?? '')
            ) ?: null,

            'username' => trim(
                (string) ($row[2] ?? '')
            ),

            'email' => trim(
                (string) ($row[3] ?? '')
            ) ?: null,

            'password' => (string) (
                $row[4] ?? ''
            ),

            'jenis_kelamin' => strtoupper(
                trim(
                    (string) ($row[5] ?? '')
                )
            ) ?: null,

            'no_hp' => trim(
                (string) ($row[6] ?? '')
            ) ?: null,

            'alamat' => trim(
                (string) ($row[7] ?? '')
            ) ?: null,

            'status' => strtolower(
                trim(
                    (string) ($row[8] ?? '')
                )
            ),
        ];


        /*
        |--------------------------------------------------------------------------
        | Validasi Per Baris
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make(
            $data,
            [
                'nama' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'nip' => [
                    'nullable',
                    'string',
                    'max:50',
                    'unique:guru,nip',
                ],

                'username' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:users,username',
                ],

                'email' => [
                    'nullable',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:8',
                ],

                'jenis_kelamin' => [
                    'nullable',
                    Rule::in([
                        'L',
                        'P',
                    ]),
                ],

                'no_hp' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'alamat' => [
                    'nullable',
                    'string',
                ],

                'status' => [
                    'required',
                    Rule::in([
                        'aktif',
                        'tidak aktif',
                    ]),
                ],
            ]
        );


        if ($validator->fails()) {
            $importErrors[] =
                'Baris '
                . $nomorBaris
                . ': '
                . implode(
                    ', ',
                    $validator
                        ->errors()
                        ->all()
                );

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Simpan User + Guru
        |--------------------------------------------------------------------------
        */

        try {
            DB::transaction(
                function () use ($data) {
                    $isActive =
                        $data['status']
                        === 'aktif';

                    $user = User::create([
                        'name' =>
                            $data['nama'],

                        'username' =>
                            $data['username'],

                        'email' =>
                            $data['email'],

                        'password' =>
                            $data['password'],

                        'role' =>
                            'guru',

                        'is_active' =>
                            $isActive,
                    ]);


                    Guru::create([
                        'user_id' =>
                            $user->id,

                        'nip' =>
                            $data['nip'],

                        'nama' =>
                            $data['nama'],

                        'jenis_kelamin' =>
                            $data['jenis_kelamin'],

                        'no_hp' =>
                            $data['no_hp'],

                        'alamat' =>
                            $data['alamat'],

                        'is_active' =>
                            $isActive,
                    ]);
                }
            );

            $berhasil++;
        } catch (\Throwable $e) {
            $importErrors[] =
                'Baris '
                . $nomorBaris
                . ': gagal disimpan.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Hasil Import
    |--------------------------------------------------------------------------
    */

    if ($berhasil === 0) {
        return redirect()
            ->route('guru.index')
            ->with(
                'error',
                'Tidak ada data guru yang berhasil diimport.'
            )
            ->with(
                'import_errors',
                $importErrors
            );
    }


    return redirect()
        ->route('guru.index')
        ->with(
            'success',
            $berhasil
            . ' data guru berhasil diimport.'
        )
        ->with(
            'import_errors',
            $importErrors
        );
}

    public function destroy(Guru $guru)
{
    $guru->load('user');

    /*
    |--------------------------------------------------------------------------
    | Cek Data yang Masih Terhubung
    |--------------------------------------------------------------------------
    */

    $punyaBankSoal = $guru
        ->bankSoals()
        ->exists();

    $punyaUjian = DB::table('ujians')
        ->where(
            'dibuat_oleh',
            $guru->user_id
        )
        ->exists();


    /*
    |--------------------------------------------------------------------------
    | Guru Sudah Memiliki Riwayat CBT
    |--------------------------------------------------------------------------
    |
    | Jangan hapus permanen karena bank soal dan ujian harus tetap tersimpan.
    | Nonaktifkan akun guru saja.
    |--------------------------------------------------------------------------
    */

    if ($punyaBankSoal || $punyaUjian) {

        DB::transaction(function () use ($guru) {

            $guru->update([
                'is_active' => false,
            ]);

            if ($guru->user) {

                $guru->user->update([
                    'is_active' => false,
                ]);

            }

        });


        return redirect()
            ->route('guru.index')
            ->with(
                'warning',
                'Guru tidak dapat dihapus karena sudah memiliki bank soal atau riwayat ujian. Akun guru telah dinonaktifkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Guru Belum Memiliki Data CBT
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use ($guru) {

        $user = $guru->user;

        /*
         * Hapus profil guru terlebih dahulu.
         */

        $guru->delete();


        /*
         * Setelah profil guru terhapus,
         * baru hapus akun user.
         */

        if ($user) {

            $user->delete();

        }

    });


    return redirect()
        ->route('guru.index')
        ->with(
            'success',
            'Data dan akun guru berhasil dihapus.'
        );
}
}