<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Services\AbsensiQrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\QueryException;

class AbsensiSiswaController extends Controller
{
    public function index()
{
    $user = auth()->user();

    $user->load(
        'siswa.kelas.tahunAjaran'
    );

    abort_unless(
        $user->siswa,
        403,
        'Data siswa tidak ditemukan.'
    );

    $siswa = $user->siswa;

    /*
    |--------------------------------------------------------------------------
    | Riwayat Absensi
    |--------------------------------------------------------------------------
    | Mengambil riwayat berdasarkan tahun ajaran kelas siswa.
    */

    $riwayat = Absensi::with([
            'sesiAbsensi.kelas.tahunAjaran',
        ])
        ->where(
            'siswa_id',
            $siswa->id
        )
        ->whereHas(
            'sesiAbsensi.kelas',
            function ($query) use ($siswa) {

                $query->where(
                    'tahun_ajaran_id',
                    $siswa
                        ->kelas
                        ->tahun_ajaran_id
                );
            }
        )
        ->get()
        ->sortByDesc(function ($absensi) {

            return $absensi
                ->sesiAbsensi
                ->tanggal;
        })
        ->values();


    /*
    |--------------------------------------------------------------------------
    | Statistik Absensi
    |--------------------------------------------------------------------------
    */

    $statistik = [
        'hadir' => $riwayat
            ->where('status', 'hadir')
            ->count(),

        'terlambat' => $riwayat
            ->where('status', 'terlambat')
            ->count(),

        'izin' => $riwayat
            ->where('status', 'izin')
            ->count(),

        'sakit' => $riwayat
            ->where('status', 'sakit')
            ->count(),

        'alpa' => $riwayat
            ->where('status', 'alpa')
            ->count(),

        'total' => $riwayat->count(),
    ];


    return view(
        'absensi.siswa.index',
        compact(
            'user',
            'riwayat',
            'statistik'
        )
    );
}

    public function scan(
        Request $request,
        AbsensiQrService $qrService
    ) {
        $validated = $request->validate([
            'token' => [
                'required',
                'string',
                'max:500',
            ],
        ]);

        $user = auth()->user();

        $user->load('siswa.kelas');

        $siswa = $user->siswa;

        if (! $siswa) {
            return response()->json([
                'message' =>
                    'Data siswa tidak ditemukan.',
            ], 403);
        }

        if (! $siswa->is_active) {
            return response()->json([
                'message' =>
                    'Akun siswa tidak aktif.',
            ], 403);
        }

        /*
         * Validasi signature dan masa berlaku QR.
         */
        $sesi = $qrService->validateToken(
            $validated['token']
        );

        if (! $sesi) {
            return response()->json([
                'message' =>
                    'QR Code tidak valid atau sudah kedaluwarsa.',
            ], 422);
        }

        /*
         * Pastikan siswa berasal dari kelas
         * yang sama dengan sesi absensi.
         */
        if (
            (int) $siswa->kelas_id
            !== (int) $sesi->kelas_id
        ) {
            return response()->json([
                'message' =>
                    'QR Code ini bukan untuk kelas Anda.',
            ], 403);
        }

        /*
         * Validasi tanggal sesi.
         */
        if (! $sesi->tanggal->isToday()) {
            return response()->json([
                'message' =>
                    'Sesi absensi bukan untuk hari ini.',
            ], 422);
        }

        $now = now();

        /*
         * Membentuk waktu mulai dan selesai
         * berdasarkan tanggal sesi.
         */
        $waktuMulai = $sesi->tanggal
            ->copy()
            ->setTimeFromTimeString(
                $sesi->waktu_mulai
            );

        $waktuSelesai = $sesi->tanggal
            ->copy()
            ->setTimeFromTimeString(
                $sesi->waktu_selesai
            );

        /*
         * Sesi belum dimulai.
         */
        if ($now->lt($waktuMulai)) {
            return response()->json([
                'message' =>
                    'Sesi absensi belum dimulai.',
            ], 422);
        }

        /*
         * Sesi sudah berakhir.
         */
        if ($now->gt($waktuSelesai)) {
            return response()->json([
                'message' =>
                    'Waktu absensi telah berakhir.',
            ], 422);
        }

        /*
         * Cek absensi sebelumnya.
         */
        $sudahAbsen = Absensi::where(
                'sesi_absensi_id',
                $sesi->id
            )
            ->where(
                'siswa_id',
                $siswa->id
            )
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'message' =>
                    'Anda sudah melakukan absensi pada sesi ini.',
            ], 409);
        }

        /*
         * Tentukan status hadir / terlambat.
         */
        $status = 'hadir';

        if ($sesi->batas_terlambat) {

            $batasTerlambat =
                $sesi->tanggal
                    ->copy()
                    ->setTimeFromTimeString(
                        $sesi->batas_terlambat
                    );

            if ($now->gt($batasTerlambat)) {
                $status = 'terlambat';
            }
        }

        /*
 * Simpan absensi.
 *
 * Database memiliki UNIQUE constraint pada:
 * sesi_absensi_id + siswa_id.
 *
 * Jadi jika dua request masuk hampir bersamaan,
 * database tetap akan menolak data duplikat.
 */
try {

    $absensi = DB::transaction(
        function () use (
            $sesi,
            $siswa,
            $status,
            $now
        ) {

            return Absensi::create([
                'sesi_absensi_id' =>
                    $sesi->id,

                'siswa_id' =>
                    $siswa->id,

                'waktu_absen' =>
                    $now,

                'status' =>
                    $status,

                'metode' =>
                    'qr',

                'dicatat_oleh' =>
                    null,

                'keterangan' =>
                    null,
            ]);
        }
    );

} catch (QueryException $exception) {

    /*
     * SQLSTATE 23000 berarti terjadi
     * pelanggaran integrity constraint.
     *
     * Dalam kasus ini, kemungkinan data
     * absensi siswa untuk sesi tersebut
     * sudah tersimpan.
     */
    if ($exception->getCode() === '23000') {

        return response()->json([
            'message' =>
                'Anda sudah melakukan absensi pada sesi ini.',
        ], 409);

    }

    /*
     * Jika error bukan karena constraint,
     * biarkan Laravel menangani error aslinya.
     */
    throw $exception;
}

        return response()->json([
            'message' =>
                $status === 'hadir'
                    ? 'Absensi berhasil. Anda tercatat hadir.'
                    : 'Absensi berhasil. Anda tercatat terlambat.',

            'status' =>
                $absensi->status,

            'waktu' =>
                $absensi
                    ->waktu_absen
                    ->format('H:i:s'),

            'jenis' =>
                $sesi->jenis,
        ]);
    }
}