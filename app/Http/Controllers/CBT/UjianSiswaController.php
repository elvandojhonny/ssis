<?php

namespace App\Http\Controllers\CBT;

use App\Http\Controllers\Controller;
use App\Models\PengerjaanUjian;
use App\Models\Ujian;
use Illuminate\Http\Request;

class UjianSiswaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Daftar Ujian Milik Siswa
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user = auth()->user();

        $siswa = $user->siswa;


        /*
         * Pastikan akun memiliki
         * data siswa.
         */
        abort_unless(
            $siswa,
            403,
            'Data siswa tidak ditemukan.'
        );


        /*
         * Ambil seluruh ujian yang:
         *
         * 1. Ditujukan untuk kelas siswa.
         * 2. Sudah dipublikasikan.
         * 3. Memuat pengerjaan milik
         *    siswa yang sedang login.
         */
        $ujians = Ujian::query()
            ->with([
                'bankSoal',

                'kelas.tahunAjaran',

                'pengerjaans' =>
                    function ($query) use ($siswa) {

                        $query->where(
                            'siswa_id',
                            $siswa->id
                        );

                    },
            ])
            ->where(
                'kelas_id',
                $siswa->kelas_id
            )
            ->where(
                'status',
                'dipublikasi'
            )
            ->latest(
                'waktu_mulai'
            )
            ->get();


        return view(
            'cbt.siswa.index',
            compact(
                'siswa',
                'ujians'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validasi Token Ujian
    |--------------------------------------------------------------------------
    */
    public function verifyToken(
        Request $request,
        Ujian $ujian
    ) {
        $user = auth()->user();

        $siswa = $user->siswa;


        /*
         * Pastikan akun memiliki
         * data siswa.
         */
        abort_unless(
            $siswa,
            403,
            'Data siswa tidak ditemukan.'
        );


        /*
         * Pastikan ujian memang
         * ditujukan untuk kelas siswa.
         */
        abort_unless(
            (int) $ujian->kelas_id ===
            (int) $siswa->kelas_id,
            403,
            'Anda tidak memiliki akses ke ujian ini.'
        );


        /*
         * Cari pengerjaan siswa
         * untuk ujian ini.
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
         * Jika sudah selesai,
         * ujian tidak boleh dikerjakan ulang.
         */
        if (
            $pengerjaan &&
            $pengerjaan->status ===
            'selesai'
        ) {
            /*
             * Bersihkan session akses
             * jika masih tersimpan.
             */
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
         * Jika sudah memiliki pengerjaan
         * yang masih aktif, tidak perlu
         * memasukkan token kembali.
         */
        if (
            $pengerjaan &&
            $pengerjaan->status ===
            'mengerjakan'
        ) {
            /*
             * Jika batas waktu individual
             * sudah habis, biarkan controller
             * pengerjaan melakukan finalisasi
             * otomatis.
             */
            return redirect()
                ->route(
                    'cbt.siswa.pengerjaan.show',
                    $pengerjaan
                );
        }


        /*
         * Ujian harus sudah
         * dipublikasikan.
         */
        if (
            $ujian->status !==
            'dipublikasi'
        ) {
            return back()->with(
                'error',
                'Ujian belum tersedia.'
            );
        }


        /*
         * Ujian belum dimulai.
         */
        if (
            now()->lt(
                $ujian->waktu_mulai
            )
        ) {
            return back()->with(
                'error',
                'Ujian belum dimulai.'
            );
        }


        /*
         * Waktu pelaksanaan ujian
         * sudah berakhir.
         */
        if (
            now()->gte(
                $ujian->waktu_selesai
            )
        ) {
            return back()->with(
                'error',
                'Waktu pelaksanaan ujian telah berakhir.'
            );
        }


        /*
         * Validasi input token.
         */
        $validated =
            $request->validate([

                'token' => [
                    'required',
                    'string',
                    'max:10',
                ],

            ]);


        /*
         * Validasi token ujian.
         */
        if (
            strtoupper(
                trim(
                    $validated['token']
                )
            )
            !==
            strtoupper(
                trim(
                    (string)
                    $ujian->token
                )
            )
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Token ujian tidak valid.'
                );
        }


        /*
         * Simpan izin sementara
         * untuk membuka halaman
         * konfirmasi mulai ujian.
         */
        session()->put(
            'cbt_access_' . $ujian->id,
            true
        );


        return redirect()
            ->route(
                'cbt.siswa.ujian.mulai',
                $ujian
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Halaman Konfirmasi Mulai Ujian
    |--------------------------------------------------------------------------
    */
    public function mulai(
        Ujian $ujian
    ) {
        $user = auth()->user();

        $siswa = $user->siswa;


        /*
         * Pastikan akun memiliki
         * data siswa.
         */
        abort_unless(
            $siswa,
            403,
            'Data siswa tidak ditemukan.'
        );


        /*
         * Pastikan ujian ditujukan
         * untuk kelas siswa.
         */
        abort_unless(
            (int) $siswa->kelas_id ===
            (int) $ujian->kelas_id,
            403,
            'Anda tidak memiliki akses ke ujian ini.'
        );


        /*
         * Cari pengerjaan yang mungkin
         * sudah dimiliki siswa.
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
         * Jika sudah selesai,
         * tidak boleh membuka halaman
         * mulai ujian kembali.
         */
        if (
            $pengerjaan &&
            $pengerjaan->status ===
            'selesai'
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
         * Jika sudah memiliki pengerjaan
         * aktif, langsung lanjutkan.
         */
        if (
            $pengerjaan &&
            $pengerjaan->status ===
            'mengerjakan'
        ) {
            return redirect()
                ->route(
                    'cbt.siswa.pengerjaan.show',
                    $pengerjaan
                );
        }


        /*
         * Ujian harus masih berstatus
         * dipublikasi.
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
         * Jadwal ujian belum dimulai.
         */
        if (
            now()->lt(
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
         * Jadwal ujian sudah berakhir.
         */
        if (
            now()->gte(
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
                    'Waktu pelaksanaan ujian telah berakhir.'
                );
        }


        /*
         * Pastikan siswa sudah berhasil
         * memvalidasi token.
         */
        abort_unless(
            session()->has(
                'cbt_access_' . $ujian->id
            ),
            403,
            'Masukkan token ujian terlebih dahulu.'
        );


        /*
         * Tampilkan halaman konfirmasi
         * sebelum waktu pengerjaan dimulai.
         */
        return view(
            'cbt.siswa.mulai',
            compact(
                'ujian'
            )
        );
    }
}