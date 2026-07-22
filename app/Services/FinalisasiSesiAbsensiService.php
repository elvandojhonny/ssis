<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\SesiAbsensi;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class FinalisasiSesiAbsensiService
{
    /**
     * Menutup seluruh sesi aktif
     * yang waktu absensinya sudah berakhir.
     */
    public function finalisasiKedaluwarsa(): int
    {
        $sesiKedaluwarsa = SesiAbsensi::query()
            ->where('status', 'aktif')
            ->where(function ($query) {

                /*
                 * Semua sesi sebelum hari ini
                 * pasti sudah kedaluwarsa.
                 */
                $query->whereDate(
                    'tanggal',
                    '<',
                    today()
                );

                /*
                 * Atau sesi hari ini yang
                 * waktu selesainya sudah lewat.
                 */
                $query->orWhere(function ($query) {

                    $query
                        ->whereDate(
                            'tanggal',
                            today()
                        )
                        ->whereTime(
                            'waktu_selesai',
                            '<',
                            now()->format('H:i:s')
                        );
                });
            })
            ->get();

        foreach ($sesiKedaluwarsa as $sesi) {
            $this->finalisasi($sesi);
        }

        return $sesiKedaluwarsa->count();
    }


    /**
     * Finalisasi satu sesi.
     */
    public function finalisasi(
    SesiAbsensi $sesi
): void {

    DB::transaction(function () use ($sesi) {

        $sesiTerkunci = SesiAbsensi::query()
            ->lockForUpdate()
            ->findOrFail($sesi->id);

        /*
         * Ambil semua siswa aktif
         * pada kelas sesi ini.
         */
        $siswaKelas = Siswa::query()
        ->where('is_active', true)
        ->whereHas('kelas', function ($query) use ($sesiTerkunci) {
            $query->where(
                'tingkat',
                $sesiTerkunci->tingkat
            );
        })
        ->get();


        foreach ($siswaKelas as $siswa) {

            /*
             * Jika belum memiliki absensi,
             * buat sebagai ALPA.
             */
            Absensi::firstOrCreate(
                [
                    'sesi_absensi_id' =>
                        $sesiTerkunci->id,

                    'siswa_id' =>
                        $siswa->id,
                ],
                [
                    'waktu_absen' => null,
                    'status' => 'alpa',
                    'metode' => 'sistem',
                    'dicatat_oleh' => null,
                    'keterangan' =>
                        'Tidak melakukan absensi sampai sesi ditutup.',
                ]
            );
        }

        $sesiTerkunci->update([
            'status' => 'selesai',
        ]);
    });
}
}