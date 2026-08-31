<?php

namespace App\Http\Middleware;

use App\Models\PengerjaanUjian;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackAfterExam
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Pastikan user login
        |--------------------------------------------------------------------------
        */

        if (! auth()->check()) {
            return $next($request);
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil siswa yang sedang login
        |--------------------------------------------------------------------------
        */

        $siswa =
            auth()
                ->user()
                ?->siswa;


        /*
        |--------------------------------------------------------------------------
        | Kalau bukan siswa
        |--------------------------------------------------------------------------
        |
        | Middleware ini hanya untuk alur CBT siswa.
        |
        */

        if (! $siswa) {
            return $next($request);
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL PARAMETER ROUTE
        |--------------------------------------------------------------------------
        */

        $pengerjaanRoute =
            $request->route('pengerjaan');

        $ujianRoute =
            $request->route('ujian');


        /*
        |--------------------------------------------------------------------------
        | TENTUKAN PENGERJAAN
        |--------------------------------------------------------------------------
        */

        $pengerjaan = null;


        /*
        |--------------------------------------------------------------------------
        | Route memiliki {pengerjaan}
        |--------------------------------------------------------------------------
        */

        if ($pengerjaanRoute) {

            if (
                $pengerjaanRoute
                instanceof PengerjaanUjian
            ) {

                $pengerjaan =
                    $pengerjaanRoute;

            } else {

                $pengerjaan =
                    PengerjaanUjian::query()
                        ->find($pengerjaanRoute);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Route hanya memiliki {ujian}
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | /cbt/ujian/{ujian}/mulai
        |
        | Cari pengerjaan milik siswa untuk ujian tersebut.
        |
        */

        if (
            ! $pengerjaan &&
            $ujianRoute
        ) {

            $ujianId =
                $ujianRoute instanceof
                \App\Models\Ujian
                    ? $ujianRoute->id
                    : $ujianRoute;


            $pengerjaan =
                PengerjaanUjian::query()
                    ->where(
                        'ujian_id',
                        $ujianId
                    )
                    ->where(
                        'siswa_id',
                        $siswa->id
                    )
                    ->first();

        }


        /*
        |--------------------------------------------------------------------------
        | CEK KEPEMILIKAN PENGERJAAN
        |--------------------------------------------------------------------------
        */

        if ($pengerjaan) {

            if (
                (int) $pengerjaan->siswa_id !==
                (int) $siswa->id
            ) {

                abort(403);

            }


            /*
            |--------------------------------------------------------------------------
            | UJIAN SUDAH SELESAI
            |--------------------------------------------------------------------------
            |
            | Tidak boleh kembali ke:
            |
            | - Halaman mulai
            | - Halaman pengerjaan
            | - Autosave jawaban
            | - Submit ulang
            |
            */

            if (
                $pengerjaan->status ===
                'selesai'
            ) {

                /*
                 * Hapus token akses CBT.
                 */

                session()->forget(
                    'cbt_access_' .
                    $pengerjaan->ujian_id
                );


                /*
                 * AJAX / JSON
                 */

                if (
                    $request->expectsJson()
                ) {

                    return response()->json([

                        'success' =>
                            false,

                        'blocked' =>
                            true,

                        'message' =>
                            'Ujian sudah selesai dan tidak dapat dikerjakan kembali.',

                    ], 403);

                }


                /*
                 * Request normal
                 */

                return redirect()
                    ->route(
                        'dashboard'
                    )
                    ->with(
                        'info',
                        'Ujian sudah selesai dan tidak dapat dikerjakan kembali.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | UJIAN DIBLOKIR
            |--------------------------------------------------------------------------
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
                        'Pengerjaan ujian sedang diblokir.'
                    );
            }

        }


        /*
        |--------------------------------------------------------------------------
        | Jalankan request normal
        |--------------------------------------------------------------------------
        */

        $response =
            $next($request);


        /*
        |--------------------------------------------------------------------------
        | Jangan simpan halaman CBT di cache browser
        |--------------------------------------------------------------------------
        */

        $response->headers->set(
            'Cache-Control',
            'no-store, no-cache, must-revalidate, max-age=0'
        );

        $response->headers->set(
            'Pragma',
            'no-cache'
        );

        $response->headers->set(
            'Expires',
            '0'
        );


        return $response;
    }
}