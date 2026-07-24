<?php

namespace App\Console\Commands;

use App\Models\PengerjaanUjian;
use App\Models\Siswa;
use App\Models\Ujian;
use App\Services\CBT\PenilaianUjianService;
use Illuminate\Console\Command;

class FinalisasiUjianKedaluwarsa extends Command
{
    protected $signature =
        'cbt:finalisasi-kedaluwarsa';


    protected $description =
        'Finalisasi otomatis pengerjaan CBT yang telah berakhir.';


    public function handle(
        PenilaianUjianService $penilaianService
    ): int {

        $jumlahDiproses = 0;

        $jumlahDibuat = 0;

        $jumlahDilewati = 0;


        /*
        |--------------------------------------------------------------------------
        | Ambil Ujian yang Jadwalnya Sudah Berakhir
        |--------------------------------------------------------------------------
        */

        Ujian::query()
            ->where(
                'waktu_selesai',
                '<=',
                now()
            )
            ->chunkById(
                100,
                function (
                    $ujians
                ) use (
                    $penilaianService,
                    &$jumlahDiproses,
                    &$jumlahDibuat,
                    &$jumlahDilewati
                ) {

                    foreach (
                        $ujians
                        as $ujian
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | Ambil Seluruh Siswa dari Kelas Ujian
                        |--------------------------------------------------------------------------
                        */

                        Siswa::query()
                            ->where(
                                'kelas_id',
                                $ujian->kelas_id
                            )
                            ->chunkById(
                                100,
                                function (
                                    $siswas
                                ) use (
                                    $ujian,
                                    $penilaianService,
                                    &$jumlahDiproses,
                                    &$jumlahDibuat,
                                    &$jumlahDilewati
                                ) {

                                    foreach (
                                        $siswas
                                        as $siswa
                                    ) {

                                        $pengerjaan =
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


                                        /*
                                        |--------------------------------------------------------------------------
                                        | Belum Pernah Memulai
                                        |--------------------------------------------------------------------------
                                        */

                                        if (! $pengerjaan) {

                                            PengerjaanUjian::create([

                                                'ujian_id' =>
                                                    $ujian->id,

                                                'siswa_id' =>
                                                    $siswa->id,

                                                'waktu_mulai' =>
                                                    $ujian->waktu_mulai,

                                                'batas_waktu' =>
                                                    $ujian->waktu_selesai,

                                                'waktu_selesai' =>
                                                    $ujian->waktu_selesai,

                                                'status' =>
                                                    'selesai',

                                                'nilai' =>
                                                    0,

                                                'jumlah_pelanggaran' =>
                                                    0,

                                                'urutan_soal' =>
                                                    [],

                                                'urutan_jawaban' =>
                                                    [],

                                            ]);


                                            $jumlahDibuat++;

                                            continue;

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | Sudah Selesai
                                        |--------------------------------------------------------------------------
                                        */

                                        if (
                                            $pengerjaan->status ===
                                            'selesai'
                                        ) {

                                            $jumlahDilewati++;

                                            continue;

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | Mengerjakan atau Diblokir
                                        |--------------------------------------------------------------------------
                                        */

                                        if (
                                            in_array(
                                                $pengerjaan->status,
                                                [
                                                    'mengerjakan',
                                                    'diblokir',
                                                ],
                                                true
                                            )
                                        ) {

                                            $penilaianService
                                                ->proses(
                                                    $pengerjaan,
                                                    true
                                                );


                                            $jumlahDiproses++;

                                            continue;

                                        }


                                        $jumlahDilewati++;

                                    }

                                }
                            );

                    }

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Informasi Hasil
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Finalisasi ujian kedaluwarsa selesai.'
        );


        $this->table(

            [
                'Keterangan',
                'Jumlah',
            ],

            [
                [
                    'Pengerjaan difinalisasi',
                    $jumlahDiproses,
                ],

                [
                    'Siswa tidak mengerjakan dibuatkan hasil',
                    $jumlahDibuat,
                ],

                [
                    'Data dilewati',
                    $jumlahDilewati,
                ],
            ]

        );


        return self::SUCCESS;
    }
}