<?php

namespace App\Http\Controllers\CBT;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use Illuminate\Http\Request;

class UjianSiswaController extends Controller
{
    /*
     * Daftar ujian milik siswa.
     */
    public function index()
    {
        $user = auth()->user();

        $siswa = $user->siswa;


        abort_unless(
            $siswa,
            403,
            'Data siswa tidak ditemukan.'
        );


        $ujians = Ujian::with([
                'bankSoal',
                'kelas.tahunAjaran',
            ])
            ->where(
                'kelas_id',
                $siswa->kelas_id
            )
            ->where(
                'status',
                'dipublikasi'
            )
            ->latest('waktu_mulai')
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
     * Validasi token sebelum masuk ujian.
     */
    public function verifyToken(
        Request $request,
        Ujian $ujian
    ) {

        $user = auth()->user();

        $siswa = $user->siswa;


        abort_unless(
            $siswa,
            403,
            'Data siswa tidak ditemukan.'
        );


        /*
         * Pastikan ujian memang untuk kelas siswa.
         */
        abort_unless(
            (int) $ujian->kelas_id
            === (int) $siswa->kelas_id,
            403,
            'Anda tidak memiliki akses ke ujian ini.'
        );


        /*
         * Pastikan ujian sudah dipublikasi.
         */
        if ($ujian->status !== 'dipublikasi') {

            return back()->with(
                'error',
                'Ujian belum tersedia.'
            );
        }


        /*
         * Periksa jadwal ujian.
         */
        if (now()->lt($ujian->waktu_mulai)) {

            return back()->with(
                'error',
                'Ujian belum dimulai.'
            );
        }


        if (now()->gt($ujian->waktu_selesai)) {

            return back()->with(
                'error',
                'Waktu pelaksanaan ujian telah berakhir.'
            );
        }


        $validated = $request->validate([

            'token' => [
                'required',
                'string',
                'max:10',
            ],

        ]);


        /*
         * Validasi token.
         */
        if (
            strtoupper(
                trim($validated['token'])
            )
            !== strtoupper($ujian->token)
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Token ujian tidak valid.'
                );
        }


        /*
         * Simpan izin sementara ke session.
         *
         * Pada V2.7 session ini akan digunakan
         * sebelum siswa dapat membuka halaman soal.
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
     * Placeholder halaman pengerjaan.
     *
     * Akan dikembangkan penuh pada Sprint V2.7.
     */
    public function mulai(Ujian $ujian)
    {
        $siswa = auth()->user()->siswa;


        abort_unless(
            $siswa
            && (int) $siswa->kelas_id
                === (int) $ujian->kelas_id,
            403
        );


        abort_unless(
            session()->has(
                'cbt_access_' . $ujian->id
            ),
            403,
            'Masukkan token ujian terlebih dahulu.'
        );


        return view(
            'cbt.siswa.mulai',
            compact('ujian')
        );
    }
}