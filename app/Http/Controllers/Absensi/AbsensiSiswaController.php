<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Services\AbsensiQrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $riwayat = Absensi::with([
                'sesiAbsensi.kelas',
            ])
            ->where(
                'siswa_id',
                $user->siswa->id
            )
            ->latest('waktu_absen')
            ->paginate(10);

        return view(
            'absensi.siswa.index',
            compact(
                'user',
                'riwayat'
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

                    'dicatat_oleh' =>
                        null,

                    'keterangan' =>
                        null,
                ]);
            }
        );

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