<?php

namespace App\Http\Controllers\CBT;

use App\Http\Controllers\Controller;
use App\Models\PengerjaanUjian;
use App\Models\Ujian;
use Illuminate\Support\Facades\DB;

class PengerjaanUjianController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Memulai Pengerjaan Ujian
    |--------------------------------------------------------------------------
    */
    public function mulai(Ujian $ujian)
    {
        $user = auth()->user();

        $siswa = $user->siswa;


        /*
         * Pastikan user memiliki data siswa.
         */
        if (! $siswa) {
            abort(403);
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
         * Ujian harus sudah dipublikasi.
         */
        if ($ujian->status !== 'dipublikasi') {

            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Ujian tidak tersedia.'
                );
        }


        $sekarang = now();


        /*
         * Pastikan jadwal ujian
         * sudah dimulai.
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
         * Pastikan jadwal ujian
         * belum berakhir.
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
         * Cari pengerjaan yang mungkin
         * sudah pernah dibuat.
         */
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
         * Jika siswa sudah selesai,
         * tidak boleh mengerjakan ulang.
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
         * Jika pengerjaan sedang diblokir,
         * arahkan ke halaman pengerjaan.
         *
         * Halaman tersebut akan menampilkan
         * overlay bahwa ujian diblokir.
         */
        if (
            $pengerjaan &&
            $pengerjaan->status === 'diblokir'
        ) {

            return redirect()
                ->route(
                    'cbt.siswa.pengerjaan.show',
                    $pengerjaan
                );
        }


        /*
         * Jika pengerjaan sudah ada
         * dan masih aktif.
         */
        if ($pengerjaan) {

            /*
             * Jika waktu individual
             * sudah habis.
             */
            if (
                now()->gte(
                    $pengerjaan->batas_waktu
                )
            ) {

                $pengerjaan->update([

                    'status' =>
                        'selesai',

                    'waktu_selesai' =>
                        now(),

                ]);


                return back()->with(
                    'error',
                    'Waktu pengerjaan Anda telah berakhir.'
                );
            }


            /*
             * Lanjutkan pengerjaan
             * yang sebelumnya.
             */
            return redirect()
                ->route(
                    'cbt.siswa.pengerjaan.show',
                    $pengerjaan
                );
        }


        /*
         * Hitung batas waktu individual.
         *
         * Batas waktu adalah:
         *
         * waktu mulai siswa + durasi ujian
         *
         * tetapi tidak boleh melewati
         * waktu selesai ujian.
         */
        $batasDurasi =
            now()
                ->copy()
                ->addMinutes(
                    $ujian->durasi_menit
                );


        $batasWaktu =
            $batasDurasi->lt(
                $ujian->waktu_selesai
            )
                ? $batasDurasi
                : $ujian->waktu_selesai;


        /*
         * Buat pengerjaan baru.
         */
        $pengerjaan =
            DB::transaction(
                function () use (
                    $ujian,
                    $siswa,
                    $batasWaktu
                ) {

                    /*
                     * Cek kembali di dalam transaction
                     * untuk mencegah pengerjaan ganda.
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
                            ->lockForUpdate()
                            ->first();


                    if ($existing) {
                        return $existing;
                    }


                    return PengerjaanUjian::create([

                        'ujian_id' =>
                            $ujian->id,

                        'siswa_id' =>
                            $siswa->id,

                        'waktu_mulai' =>
                            now(),

                        'batas_waktu' =>
                            $batasWaktu,

                        'status' =>
                            'mengerjakan',

                        /*
                         * Nilai awal pelanggaran.
                         */
                        'jumlah_pelanggaran' =>
                            0,

                    ]);

                }
            );


        /*
         * Jika ternyata pengerjaan yang ditemukan
         * di transaction sudah selesai.
         */
        if (
            $pengerjaan->status ===
            'selesai'
        ) {

            return back()->with(
                'error',
                'Anda sudah menyelesaikan ujian ini.'
            );
        }


        /*
         * Arahkan ke halaman pengerjaan.
         */
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
        $siswa =
            auth()
                ->user()
                ->siswa;


        /*
         * Siswa hanya boleh membuka
         * pengerjaan miliknya sendiri.
         */
        abort_unless(
            $siswa &&
            (int) $pengerjaan->siswa_id ===
            (int) $siswa->id,
            403
        );


        /*
         * Jika pengerjaan sudah selesai,
         * halaman ujian tidak boleh dibuka lagi.
         */
        if (
            $pengerjaan->status ===
            'selesai'
        ) {

            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Ujian ini sudah selesai.'
                );
        }


        /*
         * Jika pengerjaan sedang diblokir,
         * tetap buka halaman pengerjaan.
         *
         * Blade akan menampilkan overlay
         * blokir sehingga siswa tidak dapat
         * berinteraksi dengan soal.
         */
        if (
            $pengerjaan->status ===
            'diblokir'
        ) {

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
                compact(
                    'pengerjaan'
                )
            );
        }


        /*
         * Selain status mengerjakan,
         * halaman pengerjaan tidak dapat dibuka.
         */
        if (
            $pengerjaan->status !==
            'mengerjakan'
        ) {

            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Pengerjaan ujian tidak aktif.'
                );
        }


        /*
         * Jika batas waktu individual
         * sudah habis.
         */
        if (
            now()->gte(
                $pengerjaan->batas_waktu
            )
        ) {

            $pengerjaan->update([

                'status' =>
                    'selesai',

                'waktu_selesai' =>
                    now(),

            ]);


            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Waktu pengerjaan ujian telah berakhir.'
                );
        }


        /*
         * Load seluruh data ujian.
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
            compact(
                'pengerjaan'
            )
        );
    }
}