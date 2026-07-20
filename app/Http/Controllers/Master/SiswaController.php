<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class SiswaController extends Controller
{
    public function index()
{
    $siswas = Siswa::query()
        ->with([
            'user',
            'kelas.tahunAjaran',
        ])

        ->whereHas('kelas', function ($query) {
            $query->where('is_active', true);
        })

        ->whereHas('kelas.tahunAjaran', function ($query) {
            $query->where('is_active', true);
        })

        ->orderBy('nama')

        ->get()

        ->groupBy(function ($siswa) {
            return $siswa->kelas?->tingkat ?? 'Tanpa Kelas';
        });

    return view(
        'master.siswa.index',
        compact('siswas')
    );
}

    public function create()
{
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
        'master.siswa.create',
        compact('kelas')
    );
}

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        DB::transaction(function () use ($validated) {
            $isActive = (bool) ($validated['is_active'] ?? false);

            $user = User::create([
                'name' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
                'password' => $validated['password'],
                'role' => 'siswa',
                'is_active' => $isActive,
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'kelas_id' => $validated['kelas_id'],
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'] ?? null,
                'nama' => $validated['nama'],
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'is_active' => $isActive,
            ]);
        });

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data dan akun siswa berhasil dibuat.');
    }

    public function edit(Siswa $siswa)
{
    $siswa->load('user', 'kelas.tahunAjaran');

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
        'master.siswa.edit',
        compact('siswa', 'kelas')
    );
}

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $this->validateData(
            $request,
            $siswa
        );

        DB::transaction(function () use ($validated, $siswa) {
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

            $siswa->user->update($userData);

            $siswa->update([
                'kelas_id' => $validated['kelas_id'],
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'] ?? null,
                'nama' => $validated['nama'],
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'is_active' => $isActive,
            ]);
        });

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
{
    $siswa->load('user');

    $punyaAbsensi = DB::table('absensis')
        ->where('siswa_id', $siswa->id)
        ->exists();

    $punyaPengerjaanUjian = DB::table('pengerjaan_ujians')
        ->where('siswa_id', $siswa->id)
        ->exists();

    if ($punyaAbsensi || $punyaPengerjaanUjian) {

        DB::transaction(function () use ($siswa) {

            $siswa->update([
                'is_active' => false,
            ]);

            if ($siswa->user) {
                $siswa->user->update([
                    'is_active' => false,
                ]);
            }

        });

        return redirect()
            ->route('siswa.index')
            ->with(
                'warning',
                'Siswa tidak dapat dihapus karena sudah memiliki riwayat absensi atau ujian. Akun siswa telah dinonaktifkan.'
            );
    }

    DB::transaction(function () use ($siswa) {

        $user = $siswa->user;

        // Hapus siswa lebih dahulu agar relasi user_id terlepas.
        $siswa->delete();

        if ($user) {
            $user->delete();
        }

    });

    return redirect()
        ->route('siswa.index')
        ->with(
            'success',
            'Data dan akun siswa berhasil dihapus.'
        );
}

    private function validateData(
        Request $request,
        ?Siswa $siswa = null
    ): array {
        return $request->validate([
            'kelas_id' => [
                'required',
                'exists:kelas,id',
            ],

            'nis' => [
                'required',
                'string',
                'max:50',
                Rule::unique('siswa', 'nis')
                    ->ignore($siswa?->id),
            ],

            'nisn' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('siswa', 'nisn')
                    ->ignore($siswa?->id),
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')
                    ->ignore($siswa?->user_id),
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'password' => [
                $siswa ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'jenis_kelamin' => [
                'nullable',
                Rule::in(['L', 'P']),
            ],

            'alamat' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);
    }

public function downloadTemplate()
{
    /*
    |--------------------------------------------------------------------------
    | Ambil Kelas Aktif dari Tahun Ajaran Aktif
    |--------------------------------------------------------------------------
    */

    $kelas = Kelas::query()
        ->with('tahunAjaran')
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
    |--------------------------------------------------------------------------
    | Buat Spreadsheet
    |--------------------------------------------------------------------------
    */

    $spreadsheet = new Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('Data Siswa');


    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    $headers = [
        'Nama Siswa',
        'Kelas',
        'NIS',
        'NISN',
        'Jenis Kelamin',
        'Alamat',
        'Username',
        'Email',
        'Password',
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
        ->getStyle('A1:J1')
        ->getFont()
        ->setBold(true);

    $sheet
        ->getStyle('A1:J1')
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet
        ->getStyle('A1:J1')
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
        'B' => 22,
        'C' => 18,
        'D' => 20,
        'E' => 18,
        'F' => 35,
        'G' => 22,
        'H' => 30,
        'I' => 22,
        'J' => 18,
    ];

    foreach ($columnWidths as $column => $width) {

        $sheet
            ->getColumnDimension($column)
            ->setWidth($width);
    }


    /*
    |--------------------------------------------------------------------------
    | Format NIS dan NISN sebagai Text
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getStyle('C2:D1000')
        ->getNumberFormat()
        ->setFormatCode('@');


    /*
    |--------------------------------------------------------------------------
    | Buat Sheet Referensi
    |--------------------------------------------------------------------------
    */

    $referensi = $spreadsheet->createSheet();

    $referensi->setTitle('Referensi');


    /*
    |--------------------------------------------------------------------------
    | Daftar Kelas
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | X-1
    | X-2
    | X-3
    | XI-1
    | XI-2
    | XII-1
    |
    */

    $referensi->setCellValue(
        'A1',
        'Daftar Kelas'
    );

    foreach ($kelas as $index => $item) {

        $referensi->setCellValue(
            'A' . ($index + 2),
            $item->nama
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Jenis Kelamin
    |--------------------------------------------------------------------------
    */

    $referensi->setCellValue(
        'B1',
        'Jenis Kelamin'
    );

    $referensi->setCellValue(
        'B2',
        'L'
    );

    $referensi->setCellValue(
        'B3',
        'P'
    );


    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    $referensi->setCellValue(
        'C1',
        'Status'
    );

    $referensi->setCellValue(
        'C2',
        'Aktif'
    );

    $referensi->setCellValue(
        'C3',
        'Tidak Aktif'
    );


    /*
    |--------------------------------------------------------------------------
    | Petunjuk Pengisian
    |--------------------------------------------------------------------------
    */

    $referensi->setCellValue(
        'E1',
        'Petunjuk Pengisian'
    );

    $petunjuk = [
        'Nama Siswa wajib diisi.',
        'Kelas wajib dipilih dari daftar kelas aktif.',
        'NIS wajib diisi dan harus unik.',
        'NISN boleh dikosongkan.',
        'Jenis Kelamin: L atau P.',
        'Alamat boleh dikosongkan.',
        'Username wajib diisi dan harus unik.',
        'Email boleh dikosongkan.',
        'Password wajib minimal 8 karakter.',
        'Status wajib dipilih.',
    ];

    foreach ($petunjuk as $index => $text) {

        $referensi->setCellValue(
            'E' . ($index + 2),
            ($index + 1) . '. ' . $text
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Lebar Kolom Sheet Referensi
    |--------------------------------------------------------------------------
    */

    $referensi
        ->getColumnDimension('A')
        ->setWidth(25);

    $referensi
        ->getColumnDimension('B')
        ->setWidth(20);

    $referensi
        ->getColumnDimension('C')
        ->setWidth(20);

    $referensi
        ->getColumnDimension('E')
        ->setWidth(55);


    /*
    |--------------------------------------------------------------------------
    | Dropdown Kelas
    |--------------------------------------------------------------------------
    */

    if ($kelas->isNotEmpty()) {

        $barisTerakhirKelas =
            $kelas->count() + 1;

        for ($row = 2; $row <= 1000; $row++) {

            $validation = $sheet
                ->getCell('B' . $row)
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
                'Kelas tidak valid'
            );

            $validation->setError(
                'Pilih kelas dari daftar yang tersedia.'
            );

            $validation->setFormula1(
                "'Referensi'!\$A\$2:\$A\$$barisTerakhirKelas"
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Dropdown Jenis Kelamin
    |--------------------------------------------------------------------------
    */

    for ($row = 2; $row <= 1000; $row++) {

        $validation = $sheet
            ->getCell('E' . $row)
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
            'Pilih L untuk laki-laki atau P untuk perempuan.'
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
            ->getCell('J' . $row)
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
            'Pilih Aktif atau Tidak Aktif.'
        );

        $validation->setFormula1(
            '"Aktif,Tidak Aktif"'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Catatan Header
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getComment('B1')
        ->getText()
        ->createTextRun(
            'Pilih kelas menggunakan dropdown yang tersedia.'
        );

    $sheet
        ->getComment('C1')
        ->getText()
        ->createTextRun(
            'NIS wajib unik untuk setiap siswa.'
        );

    $sheet
        ->getComment('D1')
        ->getText()
        ->createTextRun(
            'NISN boleh dikosongkan.'
        );

    $sheet
        ->getComment('E1')
        ->getText()
        ->createTextRun(
            'Gunakan L untuk laki-laki dan P untuk perempuan.'
        );

    $sheet
        ->getComment('H1')
        ->getText()
        ->createTextRun(
            'Email boleh dikosongkan.'
        );

    $sheet
        ->getComment('I1')
        ->getText()
        ->createTextRun(
            'Password minimal 8 karakter.'
        );


    /*
    |--------------------------------------------------------------------------
    | Kembali ke Sheet Data Siswa
    |--------------------------------------------------------------------------
    */

    $spreadsheet->setActiveSheetIndex(0);


    /*
    |--------------------------------------------------------------------------
    | Download File
    |--------------------------------------------------------------------------
    */

    $filename =
        'template_import_siswa_'
        . date('Y-m-d')
        . '.xlsx';

    $writer = new Xlsx(
        $spreadsheet
    );

    $tempFile = tempnam(
        sys_get_temp_dir(),
        'template_siswa_'
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
            'max:10240',
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | Baca Excel
    |--------------------------------------------------------------------------
    */

    try {

        $spreadsheet = IOFactory::load(
            $request
                ->file('file_import')
                ->getRealPath()
        );

        $sheet = $spreadsheet->getSheetByName('Data Siswa')
            ?? $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray(
            null,
            true,
            true,
            true
        );

    } catch (\Throwable $error) {

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

    $berhasil = 0;
    $gagal = [];


    foreach ($rows as $nomorBaris => $row) {

        /*
         * Lewati header.
         */
        if ($nomorBaris === 1) {
            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil Data Excel
        |--------------------------------------------------------------------------
        */

        $nama = trim(
            (string) ($row['A'] ?? '')
        );

        $namaKelas = trim(
            (string) ($row['B'] ?? '')
        );

        $nis = trim(
            (string) ($row['C'] ?? '')
        );

        $nisn = trim(
            (string) ($row['D'] ?? '')
        );

        $jenisKelamin = strtoupper(
            trim(
                (string) ($row['E'] ?? '')
            )
        );

        $alamat = trim(
            (string) ($row['F'] ?? '')
        );

        $username = trim(
            (string) ($row['G'] ?? '')
        );

        $email = trim(
            (string) ($row['H'] ?? '')
        );

        $password = (string) ($row['I'] ?? '');

        $status = trim(
            (string) ($row['J'] ?? '')
        );


        /*
        |--------------------------------------------------------------------------
        | Lewati Baris Kosong
        |--------------------------------------------------------------------------
        */

        if (
            $nama === '' &&
            $namaKelas === '' &&
            $nis === '' &&
            $username === ''
        ) {
            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Cari Kelas Aktif pada Tahun Ajaran Aktif
        |--------------------------------------------------------------------------
        |
        | Contoh nama kelas:
        |
        | X-1
        | X-2
        | XI-1
        | XI-2
        | XII-1
        |
        */

        $kelas = Kelas::query()
            ->where('nama', $namaKelas)
            ->where('is_active', true)
            ->whereHas('tahunAjaran', function ($query) {
                $query->where('is_active', true);
            })
            ->first();


        if (! $kelas) {

            $gagal[] =
                "Baris {$nomorBaris}: kelas '{$namaKelas}' tidak ditemukan atau tidak aktif pada tahun ajaran aktif.";

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Validasi Data Siswa
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make(
            [
                'nama' => $nama,

                'nis' => $nis,

                'nisn' => $nisn !== ''
                    ? $nisn
                    : null,

                'jenis_kelamin' => $jenisKelamin !== ''
                    ? $jenisKelamin
                    : null,

                'alamat' => $alamat !== ''
                    ? $alamat
                    : null,

                'username' => $username,

                'email' => $email !== ''
                    ? $email
                    : null,

                'password' => $password,

                'status' => $status,
            ],
            [
                'nama' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'nis' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:siswa,nis',
                ],

                'nisn' => [
                    'nullable',
                    'string',
                    'max:50',
                    'unique:siswa,nisn',
                ],

                'jenis_kelamin' => [
                    'nullable',
                    Rule::in([
                        'L',
                        'P',
                    ]),
                ],

                'alamat' => [
                    'nullable',
                    'string',
                ],

                'username' => [
                    'required',
                    'string',
                    'max:255',
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

                'status' => [
                    'required',
                    Rule::in([
                        'Aktif',
                        'Tidak Aktif',
                    ]),
                ],
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Jika Validasi Gagal
        |--------------------------------------------------------------------------
        */

        if ($validator->fails()) {

            $gagal[] =
                "Baris {$nomorBaris}: "
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
        | Simpan User dan Siswa
        |--------------------------------------------------------------------------
        */

        try {

            DB::transaction(
                function () use (
                    $nama,
                    $nis,
                    $nisn,
                    $jenisKelamin,
                    $alamat,
                    $username,
                    $email,
                    $password,
                    $status,
                    $kelas
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Buat Akun User
                    |--------------------------------------------------------------------------
                    */

                    $user = User::create([
                        'name' => $nama,

                        'username' => $username,

                        'email' => $email !== ''
                            ? $email
                            : null,

                        'password' => Hash::make(
                            $password
                        ),

                        'role' => 'siswa',
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Buat Data Siswa
                    |--------------------------------------------------------------------------
                    */

                    Siswa::create([
                        'user_id' => $user->id,

                        'kelas_id' => $kelas->id,

                        'nama' => $nama,

                        'nis' => $nis,

                        'nisn' => $nisn !== ''
                            ? $nisn
                            : null,

                        'jenis_kelamin' => $jenisKelamin !== ''
                            ? $jenisKelamin
                            : null,

                        'alamat' => $alamat !== ''
                            ? $alamat
                            : null,

                        'is_active' => $status === 'Aktif',
                    ]);
                }
            );


            $berhasil++;

        } catch (\Throwable $error) {

            $gagal[] =
                "Baris {$nomorBaris}: gagal menyimpan data.";
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Hasil Import
    |--------------------------------------------------------------------------
    */

    if (
        $berhasil === 0 &&
        count($gagal) > 0
    ) {

        return back()
            ->with(
                'error',
                'Tidak ada data siswa yang berhasil diimport.'
            )
            ->with(
                'import_errors',
                $gagal
            );
    }


    return redirect()
        ->route('siswa.index')
        ->with(
            'success',
            "{$berhasil} data siswa berhasil diimport."
        )
        ->with(
            'import_errors',
            $gagal
        );
}
}