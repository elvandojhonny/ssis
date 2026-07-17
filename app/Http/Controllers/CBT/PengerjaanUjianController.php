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
         * Pastikan ujian untuk kelas siswa.
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
         * Ujian harus dipublikasi.
         */
        if ($ujian->status !== 'dipublikasi') {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Ujian tidak tersedia.'
                );
        }

        /*
         * Cari attempt siswa.
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
         * Jika sudah selesai.
         */
        if (
            $pengerjaan &&
            $pengerjaan->status === 'selesai'
        ) {
            return back()->with(
                'error',
                'Anda sudah menyelesaikan ujian ini.'
            );
        }

        /*
         * Jika attempt masih aktif,
         * langsung lanjutkan.
         */
        if ($pengerjaan) {

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
                        'Waktu pengerjaan Anda telah berakhir.'
                    );
            }

            return redirect()
                ->route(
                    'cbt.siswa.pengerjaan.show',
                    $pengerjaan
                );
        }

        /*
         * Siswa wajib melewati
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
            return back()->with(
                'error',
                'Ujian belum dimulai.'
            );
        }

        /*
         * Jadwal sudah selesai.
         */
        if (
            $sekarang->gte(
                $ujian->waktu_selesai
            )
        ) {
            return back()->with(
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

        $batasWaktu = $batasDurasi->lt(
            $ujian->waktu_selesai
        )
            ? $batasDurasi
            : $ujian->waktu_selesai;

        /*
         * Buat attempt.
         */
        $pengerjaan = DB::transaction(
            function () use (
                $ujian,
                $siswa,
                $sekarang,
                $batasWaktu
            ) {
                return PengerjaanUjian::create([
                    'ujian_id' =>
                        $ujian->id,

                    'siswa_id' =>
                        $siswa->id,

                    'waktu_mulai' =>
                        $sekarang,

                    'batas_waktu' =>
                        $batasWaktu,

                    'status' =>
                        'mengerjakan',
                ]);
            }
        );

        /*
         * Hapus akses token setelah
         * attempt berhasil dibuat.
         */
        session()->forget(
            'cbt_access_' . $ujian->id
        );

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
        $siswa = auth()
            ->user()
            ->siswa;

        /*
         * Pastikan attempt milik siswa.
         */
        abort_unless(
            $siswa &&
            (int) $pengerjaan->siswa_id ===
            (int) $siswa->id,
            403,
            'Anda tidak memiliki akses ke pengerjaan ini.'
        );

        /*
         * Jika sudah selesai,
         * arahkan ke hasil.
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
         * Status selain mengerjakan.
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
                    'Pengerjaan ujian tidak tersedia.'
                );
        }

        /*
         * Waktu habis.
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
                    'Waktu pengerjaan ujian telah berakhir.'
                );
        }

        /*
         * Load data ujian.
         */
        $pengerjaan->load([
            'ujian.bankSoal.soals' =>
                function ($query) {
                    $query->orderBy(
                        'nomor'
                    );
                },

            'ujian.kelas',

            'jawabans',
        ]);

        return view(
            'cbt.pengerjaan.show',
            compact('pengerjaan')
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
         * Pastikan attempt milik siswa.
         */
        abort_unless(
            $siswa &&
            (int) $pengerjaan->siswa_id ===
            (int) $siswa->id,
            403
        );

        /*
         * Attempt harus aktif.
         */
        if (
            $pengerjaan->status !==
            'mengerjakan'
        ) {
            return response()->json([
                'success' => false,

                'message' =>
                    'Pengerjaan ujian sudah selesai.',
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
                'success' => false,

                'expired' => true,

                'message' =>
                    'Waktu pengerjaan telah habis.',
            ], 422);
        }

        /*
         * Validasi jawaban.
         */
        $validated = $request->validate([
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
         * bank soal ujian.
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

                /*
                 * Hasil penilaian baru
                 * ditentukan saat selesai.
                 */
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
         * Pastikan attempt milik siswa.
         */
        abort_unless(
            $siswa &&
            (int) $pengerjaan->siswa_id ===
            (int) $siswa->id,
            403
        );

        /*
         * Jika sudah selesai,
         * langsung tampilkan hasil.
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
         * Proses penilaian.
         */
        $this->prosesPenilaian(
            $pengerjaan
        );

        /*
         * Setelah selesai,
         * jangan kembali ke dashboard.
         */
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
                 * Lock attempt agar
                 * tidak dinilai dua kali.
                 */
                $attempt =
                    PengerjaanUjian::query()
                        ->lockForUpdate()
                        ->findOrFail(
                            $pengerjaan->id
                        );

                /*
                 * Jika request selesai
                 * masuk dua kali.
                 */
                if (
                    $attempt->status ===
                    'selesai'
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
                    $jawaban = $pengerjaan
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
                    $skor = $benar
                        ? (float) $soal->bobot
                        : 0;

                    /*
                     * Simpan hasil penilaian.
                     */
                    $jawaban->update([
                        'is_benar' =>
                            $benar,

                        'skor' =>
                            $skor,
                    ]);

                    $totalNilai += $skor;
                }

                /*
                 * Simpan nilai akhir.
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

        /*
         * Refresh agar data terbaru
         * tersedia setelah transaksi.
         */
        $pengerjaan->refresh();
    }


    /*
    |--------------------------------------------------------------------------
    | Selesaikan Otomatis
    |--------------------------------------------------------------------------
    */
    private function selesaikanOtomatis(
        PengerjaanUjian $pengerjaan
    ): void {

        if (
            $pengerjaan->status ===
            'selesai'
        ) {
            return;
        }

        /*
         * Jawaban yang sudah tersimpan
         * tetap dinilai.
         */
        $this->prosesPenilaian(
            $pengerjaan
        );
    }
}