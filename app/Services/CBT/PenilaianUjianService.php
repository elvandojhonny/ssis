<?php

namespace App\Services\CBT;

use App\Models\PengerjaanUjian;
use Illuminate\Support\Facades\DB;

class PenilaianUjianService
{
    /*
    |--------------------------------------------------------------------------
    | Proses Penilaian
    |--------------------------------------------------------------------------
    |
    | $izinkanDiblokir:
    |
    | false = proses normal dari siswa
    | true  = finalisasi otomatis setelah ujian berakhir
    |
    */
    public function proses(
        PengerjaanUjian $pengerjaan,
        bool $izinkanDiblokir = false
    ): void {

        $pengerjaan->load([
            'ujian.bankSoal.soals',
            'jawabans',
        ]);


        DB::transaction(
            function () use (
                $pengerjaan,
                $izinkanDiblokir
            ) {

                $attempt =
                    PengerjaanUjian::query()
                        ->lockForUpdate()
                        ->findOrFail(
                            $pengerjaan->id
                        );


                /*
                 * Sudah selesai.
                 */
                if (
                    $attempt->status ===
                    'selesai'
                ) {
                    return;
                }


                /*
                 * Status yang boleh dinilai.
                 */
                $statusDiizinkan = [
                    'mengerjakan',
                ];


                /*
                 * Ketika ujian benar-benar
                 * berakhir, pengerjaan yang
                 * diblokir juga boleh dinilai.
                 */
                if ($izinkanDiblokir) {

                    $statusDiizinkan[] =
                        'diblokir';

                }


                if (
                    ! in_array(
                        $attempt->status,
                        $statusDiizinkan,
                        true
                    )
                ) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Total Bobot
                |--------------------------------------------------------------------------
                */

                $totalBobot =
                    $pengerjaan
                        ->ujian
                        ->bankSoal
                        ->soals
                        ->sum(
                            function ($soal) {

                                return max(
                                    0,
                                    (float) $soal->bobot
                                );

                            }
                        );


                $totalBobotBenar = 0;


                /*
                |--------------------------------------------------------------------------
                | Nilai Setiap Jawaban
                |--------------------------------------------------------------------------
                */

                foreach (
                    $pengerjaan
                        ->ujian
                        ->bankSoal
                        ->soals
                    as $soal
                ) {

                    $jawaban =
                        $pengerjaan
                            ->jawabans
                            ->firstWhere(
                                'soal_id',
                                $soal->id
                            );


                    /*
                     * Tidak dijawab = 0.
                     */
                    if (! $jawaban) {
                        continue;
                    }


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


                    $bobotSoal =
                        max(
                            0,
                            (float) $soal->bobot
                        );


                    $skor =
                        $benar
                            ? $bobotSoal
                            : 0;


                    $jawaban->update([

                        'is_benar' =>
                            $benar,

                        'skor' =>
                            $skor,

                    ]);


                    $totalBobotBenar +=
                        $skor;
                }


                /*
                |--------------------------------------------------------------------------
                | Normalisasi Nilai ke 100
                |--------------------------------------------------------------------------
                */

                $nilaiAkhir =
                    $totalBobot > 0
                        ? (
                            $totalBobotBenar
                            /
                            $totalBobot
                        ) * 100
                        : 0;


                $nilaiAkhir =
                    round(
                        min(
                            100,
                            max(
                                0,
                                $nilaiAkhir
                            )
                        ),
                        2
                    );


                /*
                |--------------------------------------------------------------------------
                | Selesaikan Pengerjaan
                |--------------------------------------------------------------------------
                */

                $attempt->update([

                    'status' =>
                        'selesai',

                    'waktu_selesai' =>
                        now(),

                    'nilai' =>
                        $nilaiAkhir,

                ]);

            }
        );


        $pengerjaan->refresh();
    }
}