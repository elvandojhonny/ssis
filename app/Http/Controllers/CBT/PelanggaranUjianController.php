<?php

namespace App\Http\Controllers\CBT;

use App\Http\Controllers\Controller;
use App\Models\PengerjaanUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelanggaranUjianController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Catat Pelanggaran Ujian
    |--------------------------------------------------------------------------
    */
    public function store(
        Request $request,
        PengerjaanUjian $pengerjaan
    ) {
        $siswa = auth()
            ->user()
            ->siswa;


        /*
         * Pastikan pengerjaan merupakan
         * milik siswa yang sedang login.
         */
        abort_unless(
            $siswa &&
            (int) $pengerjaan->siswa_id ===
            (int) $siswa->id,
            403,
            'Anda tidak memiliki akses ke pengerjaan ini.'
        );


        /*
         * Validasi jenis pelanggaran.
         */
        $validated = $request->validate([

            'jenis' => [
                'required',
                'string',
                'in:pindah_tab,keluar_fullscreen,kehilangan_fokus',
            ],

        ]);


        /*
         * Proses menggunakan transaction.
         */
        $hasil = DB::transaction(
            function () use (
                $pengerjaan,
                $validated
            ) {

                /*
                 * Ambil ulang data pengerjaan
                 * dan kunci row.
                 */
                $attempt =
                    PengerjaanUjian::query()
                        ->whereKey(
                            $pengerjaan->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();


                /*
                 * Jika ujian sudah selesai,
                 * jangan catat pelanggaran.
                 */
                if (
                    $attempt->status ===
                    'selesai'
                ) {

                    return [

                        'status' =>
                            'selesai',

                        'jumlah_pelanggaran' =>
                            (int)
                            $attempt
                                ->jumlah_pelanggaran,

                        'message' =>
                            'Ujian sudah selesai.',

                    ];

                }


                /*
                 * Jika sudah diblokir,
                 * jangan tambah pelanggaran.
                 */
                if (
                    $attempt->status ===
                    'diblokir'
                ) {

                    return [

                        'status' =>
                            'diblokir',

                        'jumlah_pelanggaran' =>
                            (int)
                            $attempt
                                ->jumlah_pelanggaran,

                        'message' =>
                            'Pengerjaan ujian telah diblokir.',

                    ];

                }


                /*
                 * Hanya status mengerjakan
                 * yang boleh menerima pelanggaran.
                 */
                if (
                    $attempt->status !==
                    'mengerjakan'
                ) {

                    return [

                        'status' =>
                            $attempt->status,

                        'jumlah_pelanggaran' =>
                            (int)
                            $attempt
                                ->jumlah_pelanggaran,

                        'message' =>
                            'Pengerjaan ujian tidak sedang aktif.',

                    ];

                }


                /*
                 * Tambah jumlah pelanggaran.
                 */
                $jumlahPelanggaran =
                    min(
                        3,
                        ((int)
                            $attempt
                                ->jumlah_pelanggaran
                        ) + 1
                    );


                /*
                 * PELANGGARAN KETIGA
                 *
                 * Langsung blokir pengerjaan.
                 */
                if (
                    $jumlahPelanggaran >= 3
                ) {

                    $attempt->update([

                        'jumlah_pelanggaran' =>
                            3,

                        'status' =>
                            'diblokir',

                        'diblokir_pada' =>
                            now(),

                    ]);


                    return [

                        'status' =>
                            'diblokir',

                        'jumlah_pelanggaran' =>
                            3,

                        'sisa_pelanggaran' =>
                            0,

                        'jenis' =>
                            $validated['jenis'],

                        'message' =>
                            'Anda telah mencapai batas maksimal pelanggaran. Ujian diblokir dan harus dibuka kembali oleh operator.',

                    ];

                }


                /*
                 * PELANGGARAN PERTAMA / KEDUA
                 */
                $attempt->update([

                    'jumlah_pelanggaran' =>
                        $jumlahPelanggaran,

                ]);


                return [

                    'status' =>
                        'peringatan',

                    'jumlah_pelanggaran' =>
                        $jumlahPelanggaran,

                    'sisa_pelanggaran' =>
                        3 -
                        $jumlahPelanggaran,

                    'jenis' =>
                        $validated['jenis'],

                    'message' =>
                        'Pelanggaran ujian terdeteksi.',

                ];

            }
        );


        /*
         * Kirim response ke JavaScript.
         */
        return response()->json(
            $hasil
        );
    }
}