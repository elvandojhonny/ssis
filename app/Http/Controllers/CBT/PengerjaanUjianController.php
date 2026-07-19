<?php

namespace App\Http\Controllers\CBT;

use App\Http\Controllers\Controller;
use App\Models\JawabanUjian;
use App\Models\PengerjaanUjian;
use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengerjaanUjianController extends Controller
{
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
| Buat Pengerjaan dan Urutan Acak
|--------------------------------------------------------------------------
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
         * Ambil semua soal dari bank soal.
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
        | Yang disimpan adalah ID soal.
        |
        | Contoh:
        |
        | Siswa A:
        | [1, 5, 3, 2, 4]
        |
        | Siswa B:
        | [4, 2, 1, 5, 3]
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


        $urutanSoal =
            $urutanSoal
                ->values()
                ->all();


        /*
        |--------------------------------------------------------------------------
        | Urutan Pilihan Jawaban
        |--------------------------------------------------------------------------
        |
        | Kita menyimpan urutan huruf jawaban asli.
        |
        | Contoh soal ID 10:
        |
        | ["C", "A", "D", "B"]
        |
        | Artinya:
        |
        | Tampilan A = pilihan_c asli
        | Tampilan B = pilihan_a asli
        | Tampilan C = pilihan_d asli
        | Tampilan D = pilihan_b asli
        |
        */

        $urutanJawaban = [];


        foreach ($soals as $soal) {

            /*
             * Hanya masukkan pilihan
             * yang benar-benar tersedia.
             */
            $pilihan = [];


            foreach (
                [
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                ]
                as $huruf
            ) {

                $kolom =
                    'pilihan_' .
                    strtolower($huruf);


                if (
                    ! is_null(
                        $soal->{$kolom}
                    ) &&
                    trim(
                        (string)
                        $soal->{$kolom}
                    ) !== ''
                ) {

                    $pilihan[] =
                        $huruf;

                }

            }


            /*
             * Acak jika pengaturan
             * ujian mengaktifkannya.
             */
            if ($ujian->acak_jawaban) {

                shuffle($pilihan);

            }


            /*
             * Simpan berdasarkan ID soal.
             */
            $urutanJawaban[
                (string) $soal->id
            ] = $pilihan;

        }


        /*
         * Buat pengerjaan.
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

            'urutan_jawaban' =>
                $urutanJawaban,

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
        $this->prosesPenilaian(
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
    | Soal dari bank soal kita ubah menjadi collection
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
    */

    $urutanSoal =
        $pengerjaan
            ->urutan_soal
        ?? [];


    /*
    |--------------------------------------------------------------------------
    | Susun Soal Berdasarkan Urutan Acak
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | urutan_soal:
    |
    | [4, 3, 5]
    |
    | Maka:
    |
    | Nomor tampilan 1 = Soal ID 4
    | Nomor tampilan 2 = Soal ID 3
    | Nomor tampilan 3 = Soal ID 5
    |
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
         * Pengerjaan diblokir.
         */
        if (
            $pengerjaan->status ===
            'diblokir'
        ) {
            return response()->json([

                'success' =>
                    false,

                'blocked' =>
                    true,

                'message' =>
                    'Pengerjaan ujian telah diblokir.',

            ], 423);
        }


        /*
         * Hanya pengerjaan aktif
         * yang dapat menyimpan jawaban.
         */
        if (
            $pengerjaan->status !==
            'mengerjakan'
        ) {
            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'Pengerjaan ujian sudah tidak aktif.',

            ], 422);
        }


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


            return response()->json([

                'success' =>
                    false,

                'expired' =>
                    true,

                'message' =>
                    'Waktu pengerjaan telah habis.',

            ], 422);
        }


        /*
         * Validasi jawaban.
         */
        $validated =
            $request->validate([

                'soal_id' => [
                    'required',
                    'integer',
                    'exists:soals,id',
                ],

                'jawaban' => [
                    'required',
                    'string',
                    'in:A,B,C,D,E',
                ],

            ]);


        /*
         * Load ujian.
         */
        $pengerjaan->loadMissing(
            'ujian'
        );


        /*
         * Pastikan soal berasal dari
         * bank soal ujian ini.
         */
        $soal = Soal::query()
            ->whereKey(
                $validated['soal_id']
            )
            ->where(
                'bank_soal_id',
                $pengerjaan
                    ->ujian
                    ->bank_soal_id
            )
            ->firstOrFail();


        /*
         * Simpan jawaban.
         */
        JawabanUjian::updateOrCreate(

            [
                'pengerjaan_ujian_id' =>
                    $pengerjaan->id,

                'soal_id' =>
                    $soal->id,
            ],

            [
                'jawaban' =>
                    strtoupper(
                        $validated['jawaban']
                    ),

                'is_benar' =>
                    null,

                'skor' =>
                    0,
            ]

        );


        return response()->json([

            'success' =>
                true,

            'soal_id' =>
                $soal->id,

            'jawaban' =>
                strtoupper(
                    $validated['jawaban']
                ),

            'message' =>
                'Jawaban berhasil disimpan.',

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
        $this->prosesPenilaian(
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
    | Proses Penilaian
    |--------------------------------------------------------------------------
    */
    private function prosesPenilaian(
        PengerjaanUjian $pengerjaan
    ): void {

        /*
         * Load soal dan jawaban.
         */
        $pengerjaan->load([

            'ujian.bankSoal.soals',

            'jawabans',

        ]);


        DB::transaction(
            function () use (
                $pengerjaan
            ) {

                /*
                 * Lock pengerjaan.
                 */
                $attempt =
                    PengerjaanUjian::query()
                        ->lockForUpdate()
                        ->findOrFail(
                            $pengerjaan->id
                        );


                /*
                 * Jika sudah selesai.
                 */
                if (
                    $attempt->status ===
                    'selesai'
                ) {
                    return;
                }


                /*
                 * Jangan proses penilaian
                 * jika sedang diblokir.
                 */
                if (
                    $attempt->status ===
                    'diblokir'
                ) {
                    return;
                }


                /*
                 * Hanya status mengerjakan
                 * yang boleh dinilai.
                 */
                if (
                    $attempt->status !==
                    'mengerjakan'
                ) {
                    return;
                }


                $totalNilai = 0;


                foreach (
                    $pengerjaan
                        ->ujian
                        ->bankSoal
                        ->soals
                    as $soal
                ) {

                    /*
                     * Cari jawaban siswa.
                     */
                    $jawaban =
                        $pengerjaan
                            ->jawabans
                            ->firstWhere(
                                'soal_id',
                                $soal->id
                            );


                    /*
                     * Tidak dijawab.
                     */
                    if (! $jawaban) {
                        continue;
                    }


                    /*
                     * Bandingkan jawaban.
                     */
                    $benar =
                        strtoupper(
                            trim(
                                (string)
                                $jawaban->jawaban
                            )
                        )
                        ===
                        strtoupper(
                            trim(
                                (string)
                                $soal->jawaban_benar
                            )
                        );


                    /*
                     * Tentukan skor.
                     */
                    $skor =
                        $benar
                            ? (float)
                                $soal->bobot
                            : 0;


                    /*
                     * Simpan hasil jawaban.
                     */
                    $jawaban->update([

                        'is_benar' =>
                            $benar,

                        'skor' =>
                            $skor,

                    ]);


                    $totalNilai +=
                        $skor;
                }


                /*
                 * Simpan hasil akhir.
                 */
                $attempt->update([

                    'status' =>
                        'selesai',

                    'waktu_selesai' =>
                        now(),

                    'nilai' =>
                        $totalNilai,

                ]);
            }
        );


        $pengerjaan->refresh();
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
        $this->prosesPenilaian(
            $pengerjaan
        );
    }
}