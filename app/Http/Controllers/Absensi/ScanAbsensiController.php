<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\SesiAbsensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScanAbsensiController extends Controller
{
    public function scan(
        Request $request,
        SesiAbsensi $sesi
    ) {
        /*
         * Pastikan sesi masih aktif.
         */
        if ($sesi->status !== 'aktif') {
            return response()->json([
                'message' =>
                    'Sesi absensi sudah ditutup.',
            ], 422);
        }

        /*
         * Validasi QR yang dikirim scanner.
         */
        $validated = $request->validate([
            'qr' => [
                'required',
                'string',
                'max:500',
            ],
        ]);

        /*
         * Format QR:
         *
         * SSIS-SISWA:xxxxxxxxxxxxxxxx
         */
        $prefix = 'SSIS-SISWA:';

        if (
            ! str_starts_with(
                $validated['qr'],
                $prefix
            )
        ) {
            return response()->json([
                'message' =>
                    'QR bukan merupakan QR siswa SSIS.',
            ], 422);
        }

        /*
         * Ambil token siswa.
         */
        $token = substr(
            $validated['qr'],
            strlen($prefix)
        );

        /*
         * Cari siswa berdasarkan token.
         */
        $siswa = Siswa::with([
                'user',
                'kelas',
            ])
            ->where(
                'qr_token',
                $token
            )
            ->first();

        if (! $siswa) {
            return response()->json([
                'message' =>
                    'QR siswa tidak ditemukan atau sudah tidak berlaku.',
            ], 404);
        }

        /*
         * Pastikan siswa aktif.
         */
        if (! $siswa->is_active) {
            return response()->json([
                'message' =>
                    'Data siswa sudah tidak aktif.',
            ], 403);
        }

        /*
         * Pastikan siswa berasal dari
         * kelas sesi yang sedang dibuka.
         */
        if (
            (int) $siswa->kelas_id
            !== (int) $sesi->kelas_id
        ) {
            return response()->json([
                'message' =>
                    'Siswa bukan anggota kelas pada sesi absensi ini.',
            ], 403);
        }

        /*
         * Pastikan sesi untuk hari ini.
         */
        if (! $sesi->tanggal->isToday()) {
            return response()->json([
                'message' =>
                    'Sesi absensi bukan untuk hari ini.',
            ], 422);
        }

        $now = now();

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
         * Scanner belum dapat digunakan
         * sebelum sesi dimulai.
         */
        if ($now->lt($waktuMulai)) {
            return response()->json([
                'message' =>
                    'Sesi absensi belum dimulai.',
            ], 422);
        }

        /*
         * Scanner tidak dapat digunakan
         * setelah sesi selesai.
         */
        if ($now->gt($waktuSelesai)) {
            return response()->json([
                'message' =>
                    'Waktu sesi absensi telah berakhir.',
            ], 422);
        }

        /*
         * Cegah absensi ganda.
         */
        $absensi = Absensi::where(
                'sesi_absensi_id',
                $sesi->id
            )
            ->where(
                'siswa_id',
                $siswa->id
            )
            ->first();

        if ($absensi) {
            return response()->json([
                'message' =>
                    $siswa->user->name
                    . ' sudah tercatat pada sesi ini.',

                'sudah_absen' => true,

                'siswa' => [
                    'nama' =>
                        $siswa->user->name,

                    'nis' =>
                        $siswa->nis,

                    'status' =>
                        $absensi->status,
                ],
            ], 409);
        }

        /*
         * Default status adalah hadir.
         */
        $status = 'hadir';

        /*
         * Jika melewati batas terlambat,
         * status otomatis menjadi terlambat.
         */
        if ($sesi->batas_terlambat) {

            $batasTerlambat = $sesi->tanggal
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
         */
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

                    /*
                     * Sekarang QR discan oleh guru,
                     * jadi pencatatnya adalah user
                     * yang sedang login.
                     */
                    'dicatat_oleh' =>
                        auth()->id(),

                    'keterangan' =>
                        null,
                ]);
            }
        );

        return response()->json([
            'message' =>
                $siswa->user->name
                . ' berhasil melakukan absensi.',

            'siswa' => [
                'id' =>
                    $siswa->id,

                'nama' =>
                    $siswa->user->name,

                'nis' =>
                    $siswa->nis,

                'status' =>
                    $absensi->status,

                'waktu' =>
                    $absensi
                        ->waktu_absen
                        ->format('H:i:s'),
            ],
        ]);
    }
}