<?php

namespace App\Http\Controllers\CBT;

use App\Http\Controllers\Controller;
use App\Models\JawabanUjian;
use App\Models\PengerjaanUjian;
use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Services\CBT\PenilaianUjianService;

class PengerjaanUjianController extends Controller
{

public function __construct(
        private readonly
        PenilaianUjianService $penilaianService
    ) {
    }
    /*
    |--------------------------------------------------------------------------
    | Mulai Ujian
    |--------------------------------------------------------------------------
    */
    /*
|--------------------------------------------------------------------------
| Mulai Ujian
|--------------------------------------------------------------------------
*/
public function mulai(Ujian $ujian)
{
    $user = auth()->user();

    $siswa = $user->siswa;

    if (! $siswa) {
        abort(
            403,
            'Data siswa tidak ditemukan.'
        );
    }


    /*
     * Pastikan ujian ditujukan
     * untuk kelas siswa.
     */
    if (
        (int) $ujian->kelas_id !==
        (int) $siswa->kelas_id
    ) {
        abort(
            403,
            'Ujian ini bukan untuk kelas Anda.'
        );
    }


    /*
     * Cari pengerjaan siswa.
     */
    $pengerjaan = PengerjaanUjian::query()
        ->where(
            'ujian_id',
            $ujian->id
        )
        ->where(
            'siswa_id',
            $siswa->id
        )
        ->first();


    /*
     * Jika sudah selesai,
     * tidak dapat mengerjakan ulang.
     */
    if (
        $pengerjaan &&
        $pengerjaan->status === 'selesai'
    ) {
        session()->forget(
            'cbt_access_' . $ujian->id
        );

        return redirect()
            ->route(
                'cbt.siswa.index'
            )
            ->with(
                'error',
                'Anda sudah menyelesaikan ujian ini dan tidak dapat mengerjakannya kembali.'
            );
    }


    /*
     * Jika pengerjaan sedang diblokir,
     * siswa tidak dapat melanjutkan.
     */
    if (
        $pengerjaan &&
        $pengerjaan->status === 'diblokir'
    ) {
        return redirect()
            ->route(
                'cbt.siswa.index'
            )
            ->with(
                'error',
                'Pengerjaan ujian Anda sedang diblokir karena mencapai batas pelanggaran. Hubungi operator untuk membuka blokir.'
            );
    }


    /*
     * Jika pengerjaan sudah tersedia.
     */
    if ($pengerjaan) {

        /*
         * Periksa batas waktu.
         */
        if (
            now()->gte(
                $pengerjaan->batas_waktu
            )
        ) {
            $this->selesaikanOtomatis(
                $pengerjaan
            );

            return redirect()
                ->route(
                    'cbt.siswa.pengerjaan.hasil',
                    $pengerjaan
                )
                ->with(
                    'info',
                    'Waktu pengerjaan Anda telah berakhir. Ujian telah diselesaikan secara otomatis.'
                );
        }


        /*
         * Jika masih aktif,
         * lanjutkan pengerjaan.
         */
        if (
            $pengerjaan->status ===
            'mengerjakan'
        ) {
            return redirect()
                ->route(
                    'cbt.siswa.pengerjaan.show',
                    $pengerjaan
                );
        }


        return redirect()
            ->route(
                'cbt.siswa.index'
            )
            ->with(
                'error',
                'Status pengerjaan ujian tidak valid.'
            );
    }


    /*
     * Ujian harus dipublikasi.
     */
    if (
        $ujian->status !==
        'dipublikasi'
    ) {
        return redirect()
            ->route(
                'cbt.siswa.index'
            )
            ->with(
                'error',
                'Ujian tidak tersedia.'
            );
    }


    /*
     * Siswa harus melewati
     * validasi token.
     */
    abort_unless(
        session()->has(
            'cbt_access_' . $ujian->id
        ),
        403,
        'Silakan verifikasi token ujian terlebih dahulu.'
    );


    $sekarang = now();


    /*
     * Jadwal belum dimulai.
     */
    if (
        $sekarang->lt(
            $ujian->waktu_mulai
        )
    ) {
        return redirect()
            ->route(
                'cbt.siswa.index'
            )
            ->with(
                'error',
                'Ujian belum dimulai.'
            );
    }


    /*
     * Jadwal sudah berakhir.
     */
    if (
        $sekarang->gte(
            $ujian->waktu_selesai
        )
    ) {
        session()->forget(
            'cbt_access_' . $ujian->id
        );

        return redirect()
            ->route(
                'cbt.siswa.index'
            )
            ->with(
                'error',
                'Waktu ujian telah berakhir.'
            );
    }


    /*
     * Hitung batas waktu individual.
     */
    $batasDurasi = $sekarang
        ->copy()
        ->addMinutes(
            $ujian->durasi_menit
        );


    /*
     * Batas waktu tidak boleh
     * melewati akhir jadwal ujian.
     */
    $batasWaktu = $batasDurasi->lt(
        $ujian->waktu_selesai
    )
        ? $batasDurasi
        : $ujian->waktu_selesai;


    /*
    |--------------------------------------------------------------------------
    | Buat Pengerjaan dan Urutan Soal
    |--------------------------------------------------------------------------
    |
    | Pada bagian ini HANYA nomor soal yang diacak.
    |
    | Pilihan jawaban A, B, C, D, E
    | tidak lagi diacak.
    |
    */
    $pengerjaan = DB::transaction(
        function () use (
            $ujian,
            $siswa,
            $sekarang,
            $batasWaktu
        ) {

            /*
             * Cek apakah pengerjaan sudah ada.
             */
            $existing =
                PengerjaanUjian::query()
                    ->where(
                        'ujian_id',
                        $ujian->id
                    )
                    ->where(
                        'siswa_id',
                        $siswa->id
                    )
                    ->first();


            if ($existing) {
                return $existing;
            }


            /*
             * Ambil semua soal dari bank soal
             * berdasarkan nomor soal asli.
             */
            $soals =
                Soal::query()
                    ->where(
                        'bank_soal_id',
                        $ujian->bank_soal_id
                    )
                    ->orderBy('nomor')
                    ->get();


            /*
            |--------------------------------------------------------------------------
            | Urutan Soal
            |--------------------------------------------------------------------------
            |
            | Simpan ID soal berdasarkan urutan
            | yang akan diberikan kepada siswa.
            |
            | Jika acak_soal aktif:
            |
            | Contoh:
            | [5, 2, 8, 1, 4]
            |
            | Jika tidak aktif:
            |
            | [1, 2, 3, 4, 5]
            |
            */

            $urutanSoal =
                $soals
                    ->pluck('id');


            if ($ujian->acak_soal) {

                $urutanSoal =
                    $urutanSoal
                        ->shuffle();

            }


            /*
             * Pastikan index menjadi
             * array biasa mulai dari 0.
             */
            $urutanSoal =
                $urutanSoal
                    ->values()
                    ->all();


            /*
            |--------------------------------------------------------------------------
            | Buat Pengerjaan
            |--------------------------------------------------------------------------
            |
            | Tidak ada lagi:
            |
            | 'urutan_jawaban'
            |
            | karena pilihan A-E selalu mengikuti
            | urutan asli dari bank soal.
            |
            */

            return PengerjaanUjian::create([

                'ujian_id' =>
                    $ujian->id,

                'siswa_id' =>
                    $siswa->id,

                'waktu_mulai' =>
                    $sekarang,

                'batas_waktu' =>
                    $batasWaktu,

                'urutan_soal' =>
                    $urutanSoal,

                'status' =>
                    'mengerjakan',

                'jumlah_pelanggaran' =>
                    0,

            ]);

        }
    );


    /*
     * Token hanya digunakan
     * untuk memulai ujian.
     */
    session()->forget(
        'cbt_access_' . $ujian->id
    );


    /*
     * Periksa status hasil transaction.
     */
    if (
        $pengerjaan->status ===
        'selesai'
    ) {
        return redirect()
            ->route(
                'cbt.siswa.index'
            )
            ->with(
                'error',
                'Anda sudah menyelesaikan ujian ini.'
            );
    }


    if (
        $pengerjaan->status ===
        'diblokir'
    ) {
        return redirect()
            ->route(
                'cbt.siswa.index'
            )
            ->with(
                'error',
                'Pengerjaan ujian Anda sedang diblokir.'
            );
    }


    return redirect()
        ->route(
            'cbt.siswa.pengerjaan.show',
            $pengerjaan
        );
}


    /*
    |--------------------------------------------------------------------------
    | Halaman Pengerjaan Ujian
    |--------------------------------------------------------------------------
    */
    /*
|--------------------------------------------------------------------------
| Halaman Pengerjaan Ujian
|--------------------------------------------------------------------------
*/
public function show(
    PengerjaanUjian $pengerjaan
) {
    /*
    |--------------------------------------------------------------------------
    | Ambil Data Siswa
    |--------------------------------------------------------------------------
    */

    $siswa = auth()
        ->user()
        ->siswa;


    /*
     * Pastikan pengerjaan milik siswa
     * yang sedang login.
     */
    abort_unless(
        $siswa &&
        (int) $pengerjaan->siswa_id ===
        (int) $siswa->id,
        403
    );


    /*
    |--------------------------------------------------------------------------
    | Load Relasi
    |--------------------------------------------------------------------------
    */

    $pengerjaan->load([

        'ujian.bankSoal.soals' =>
            function ($query) {

                $query
                    ->orderBy('nomor');

            },

        'ujian.kelas',

        'jawabans',

    ]);


    /*
    |--------------------------------------------------------------------------
    | Jika Ujian Sudah Selesai
    |--------------------------------------------------------------------------
    */

    if (
        $pengerjaan->status ===
        'selesai'
    ) {

        return redirect()
            ->route(
                'cbt.siswa.pengerjaan.hasil',
                $pengerjaan
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Periksa Batas Waktu
    |--------------------------------------------------------------------------
    */

    if (
        $pengerjaan->batas_waktu &&
        now()->gte(
            $pengerjaan->batas_waktu
        )
    ) {

        /*
         * Selesaikan ujian otomatis.
         */
        $this
            ->penilaianService
            ->proses(
                $pengerjaan
            );


        return redirect()
            ->route(
                'cbt.siswa.pengerjaan.hasil',
                $pengerjaan
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Ambil Semua Soal
    |--------------------------------------------------------------------------
    |
    | Soal dari bank soal diubah menjadi collection
    | dengan ID soal sebagai key.
    |
    */

    $semuaSoal =
        $pengerjaan
            ->ujian
            ->bankSoal
            ->soals
            ->keyBy('id');


    /*
    |--------------------------------------------------------------------------
    | Ambil Urutan Soal Milik Siswa
    |--------------------------------------------------------------------------
    |
    | Urutan ini dibuat ketika siswa mulai ujian.
    |
    | Contoh:
    |
    | [7, 3, 10, 1, 5]
    |
    | Artinya:
    |
    | Nomor tampilan 1 = soal ID 7
    | Nomor tampilan 2 = soal ID 3
    | Nomor tampilan 3 = soal ID 10
    | Nomor tampilan 4 = soal ID 1
    | Nomor tampilan 5 = soal ID 5
    |
    */

    $urutanSoal =
        $pengerjaan
            ->urutan_soal
        ?? [];


    /*
    |--------------------------------------------------------------------------
    | Susun Soal Berdasarkan Urutan Siswa
    |--------------------------------------------------------------------------
    */

    if (
        ! empty(
            $urutanSoal
        )
    ) {

        $soals =
            collect(
                $urutanSoal
            )
                ->map(
                    function (
                        $soalId
                    ) use (
                        $semuaSoal
                    ) {

                        return $semuaSoal
                            ->get(
                                (int) $soalId
                            );

                    }
                )
                ->filter()
                ->values();

    } else {

        /*
         * Fallback untuk pengerjaan lama
         * yang belum memiliki urutan_soal.
         */
        $soals =
            $semuaSoal
                ->sortBy('nomor')
                ->values();

    }


    /*
    |--------------------------------------------------------------------------
    | Tampilkan Halaman Ujian
    |--------------------------------------------------------------------------
    */

    return view(
        'cbt.pengerjaan.show',
        compact(
            'pengerjaan',
            'soals'
        )
    );
}


    /*
    |--------------------------------------------------------------------------
    | Simpan Jawaban
    |--------------------------------------------------------------------------
    */
    public function simpanJawaban(
    Request $request,
    PengerjaanUjian $pengerjaan
) {
    $siswa = auth()->user()->siswa;

    abort_unless(
        $siswa &&
        (int)$pengerjaan->siswa_id === (int)$siswa->id,
        403
    );

    if ($pengerjaan->status === 'diblokir') {

        return response()->json([
            'success' => false,
            'blocked' => true,
            'message' => 'Pengerjaan ujian telah diblokir.'
        ],423);

    }

    if ($pengerjaan->status !== 'mengerjakan') {

        return response()->json([
            'success'=>false,
            'message'=>'Pengerjaan sudah tidak aktif.'
        ],422);

    }

    if(now()->gte($pengerjaan->batas_waktu)){

        $this->selesaikanOtomatis($pengerjaan);

        return response()->json([
            'success'=>false,
            'expired'=>true
        ],422);

    }

    /*
    |--------------------------------------------------------------------------
    | Validasi soal
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'soal_id'=>[
            'required',
            'integer',
            'exists:soals,id'
        ]

    ]);

    $pengerjaan->loadMissing('ujian');

    $soal = Soal::query()

        ->whereKey($request->soal_id)

        ->where(
            'bank_soal_id',
            $pengerjaan
                ->ujian
                ->bank_soal_id
        )

        ->firstOrFail();


    /*
    |--------------------------------------------------------------------------
    | SOAL ESSAY
    |--------------------------------------------------------------------------
    */

    if($soal->tipe === 'essay'){

        $request->validate([

            'jawaban_text'=>[
                'nullable',
                'string'
            ]

        ]);

        JawabanUjian::updateOrCreate(

            [

                'pengerjaan_ujian_id'=>$pengerjaan->id,

                'soal_id'=>$soal->id

            ],

            [

                'jawaban'=>null,

                'jawaban_text'=>$request->jawaban_text,

                'is_benar'=>null,

                'skor'=>0

            ]

        );

        return response()->json([

            'success'=>true,

            'mode'=>'essay'

        ]);

    }


    /*
    |--------------------------------------------------------------------------
    | PILIHAN GANDA
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'jawaban'=>[
            'required',
            'string',
            'in:A,B,C,D,E'
        ]

    ]);


    JawabanUjian::updateOrCreate(

        [

            'pengerjaan_ujian_id'=>$pengerjaan->id,

            'soal_id'=>$soal->id

        ],

        [

            'jawaban'=>strtoupper($request->jawaban),

            'jawaban_text'=>null,

            'is_benar'=>null,

            'skor'=>0

        ]

    );

    return response()->json([

        'success'=>true,

        'mode'=>'pg'

    ]);

}


    /*
    |--------------------------------------------------------------------------
    | Selesaikan Ujian
    |--------------------------------------------------------------------------
    */
    public function selesai(
        PengerjaanUjian $pengerjaan
    ) {
        $siswa = auth()
            ->user()
            ->siswa;


        /*
         * Pastikan pengerjaan milik siswa.
         */
        abort_unless(
            $siswa &&
            (int) $pengerjaan->siswa_id ===
            (int) $siswa->id,
            403
        );


        /*
         * Jika sudah selesai.
         */
        if (
            $pengerjaan->status ===
            'selesai'
        ) {
            return redirect()
                ->route(
                    'cbt.siswa.pengerjaan.hasil',
                    $pengerjaan
                );
        }


        /*
         * Pengerjaan yang diblokir
         * tidak dapat diselesaikan.
         */
        if (
            $pengerjaan->status ===
            'diblokir'
        ) {
            return redirect()
                ->route(
                    'cbt.siswa.index'
                )
                ->with(
                    'error',
                    'Pengerjaan ujian sedang diblokir. Hubungi operator untuk membuka blokir.'
                );
        }


        /*
         * Hanya pengerjaan aktif
         * yang boleh diselesaikan.
         */
        if (
            $pengerjaan->status !==
            'mengerjakan'
        ) {
            return redirect()
                ->route(
                    'cbt.siswa.index'
                )
                ->with(
                    'error',
                    'Pengerjaan ujian tidak dapat diselesaikan.'
                );
        }


        /*
         * Proses penilaian.
         */
        $this
        ->penilaianService
        ->proses(
            $pengerjaan
        );


        return redirect()
            ->route(
                'cbt.siswa.pengerjaan.hasil',
                $pengerjaan
            )
            ->with(
                'success',
                'Ujian berhasil diselesaikan.'
            );
    }
    /*
    |--------------------------------------------------------------------------
    | Hasil Ujian
    |--------------------------------------------------------------------------
    */
    public function hasil(
        PengerjaanUjian $pengerjaan
    ) {
        $siswa = auth()
            ->user()
            ->siswa;


        /*
         * Pastikan hasil milik siswa.
         */
        abort_unless(
            $siswa &&
            (int) $pengerjaan->siswa_id ===
            (int) $siswa->id,
            403,
            'Anda tidak memiliki akses ke hasil ujian ini.'
        );


        /*
         * Hasil hanya tersedia
         * jika sudah selesai.
         */
        if (
            $pengerjaan->status !==
            'selesai'
        ) {

            /*
             * Jika diblokir.
             */
            if (
                $pengerjaan->status ===
                'diblokir'
            ) {
                return redirect()
                    ->route(
                        'cbt.siswa.index'
                    )
                    ->with(
                        'error',
                        'Pengerjaan ujian Anda sedang diblokir.'
                    );
            }


            return redirect()
                ->route(
                    'cbt.siswa.pengerjaan.show',
                    $pengerjaan
                )
                ->with(
                    'error',
                    'Ujian belum selesai.'
                );
        }


        /*
         * Hasil hanya tersedia
         * selama 7 hari.
         */
        if (
            $pengerjaan->waktu_selesai &&
            now()->gte(
                $pengerjaan
                    ->waktu_selesai
                    ->copy()
                    ->addDays(7)
            )
        ) {
            return redirect()
                ->route(
                    'cbt.siswa.riwayat'
                )
                ->with(
                    'error',
                    'Hasil ujian ini sudah tidak tersedia karena telah melewati batas 7 hari.'
                );
        }


        /*
         * Load data.
         */
        $pengerjaan->load([

            'ujian.bankSoal.soals',

            'ujian.kelas',

            'jawabans',

        ]);


        $soals =
            $pengerjaan
                ->ujian
                ->bankSoal
                ->soals;


        $jawabans =
            $pengerjaan
                ->jawabans;


        /*
         * Statistik hasil.
         */
        $jumlahSoal =
            $soals->count();


        $jumlahDijawab =
            $jawabans->count();


        $jumlahBenar =
            $jawabans
                ->where(
                    'is_benar',
                    true
                )
                ->count();


        $jumlahSalah =
            $jawabans
                ->where(
                    'is_benar',
                    false
                )
                ->count();


        $tidakDijawab =
            max(
                0,
                $jumlahSoal -
                $jumlahDijawab
            );


        /*
         * Durasi pengerjaan aktual.
         */
        $durasiPengerjaan = null;


        if (
            $pengerjaan->waktu_mulai &&
            $pengerjaan->waktu_selesai
        ) {
            $durasiPengerjaan =
                $pengerjaan
                    ->waktu_mulai
                    ->diffInMinutes(
                        $pengerjaan
                            ->waktu_selesai
                    );
        }


        return view(
            'cbt.pengerjaan.hasil',
            compact(
                'pengerjaan',
                'jumlahSoal',
                'jumlahDijawab',
                'jumlahBenar',
                'jumlahSalah',
                'tidakDijawab',
                'durasiPengerjaan'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Riwayat Ujian Siswa
    |--------------------------------------------------------------------------
    */
    public function riwayat()
    {
        $siswa = auth()
            ->user()
            ->siswa;


        if (! $siswa) {
            abort(
                403,
                'Data siswa tidak ditemukan.'
            );
        }


        /*
         * Riwayat hanya tampil
         * selama 7 hari.
         */
        $riwayat =
            PengerjaanUjian::query()
                ->with([

                    'ujian.bankSoal',

                    'ujian.kelas',

                ])
                ->where(
                    'siswa_id',
                    $siswa->id
                )
                ->where(
                    'status',
                    'selesai'
                )
                ->whereNotNull(
                    'waktu_selesai'
                )
                ->where(
                    'waktu_selesai',
                    '>=',
                    now()->subWeek()
                )
                ->orderByDesc(
                    'waktu_selesai'
                )
                ->paginate(10);


        return view(
            'cbt.pengerjaan.riwayat',
            compact(
                'riwayat'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Selesaikan Otomatis
    |--------------------------------------------------------------------------
    */
    private function selesaikanOtomatis(
        PengerjaanUjian $pengerjaan
    ): void {

        /*
         * Sudah selesai.
         */
        if (
            $pengerjaan->status ===
            'selesai'
        ) {
            return;
        }


        /*
         * Jangan selesaikan otomatis
         * ketika sedang diblokir.
         *
         * Kebijakan timer blokir akan
         * ditangani saat operator
         * membuka blokir.
         */
        if (
            $pengerjaan->status ===
            'diblokir'
        ) {
            return;
        }


        /*
         * Hanya pengerjaan aktif.
         */
        if (
            $pengerjaan->status !==
            'mengerjakan'
        ) {
            return;
        }


        /*
         * Nilai jawaban yang sudah
         * tersimpan.
         */
        $this
        ->penilaianService
        ->proses(
            $pengerjaan
        );
    }
}