<?php

namespace App\Services;

use App\Models\SesiAbsensi;

class AbsensiQrService
{
    private const INTERVAL = 15;

    /**
     * Menghasilkan interval waktu 15 detik saat ini.
     */
    public function currentInterval(): int
    {
        return (int) floor(
            now()->timestamp / self::INTERVAL
        );
    }

    /**
     * Menghasilkan signature QR.
     */
    public function generateSignature(
        SesiAbsensi $sesi,
        ?int $interval = null
    ): string {
        $interval ??= $this->currentInterval();

        $payload = implode('|', [
            $sesi->id,
            $sesi->tingkat,
            $sesi->tanggal->format('Y-m-d'),
            $sesi->jenis,
            $interval,
        ]);

        return hash_hmac(
            'sha256',
            $payload,
            config('app.key')
        );
    }

    /**
     * Menghasilkan token yang akan dimasukkan ke QR.
     */
    public function generateToken(
        SesiAbsensi $sesi
    ): string {
        $interval = $this->currentInterval();

        return implode('.', [
            $sesi->id,
            $interval,
            $this->generateSignature(
                $sesi,
                $interval
            ),
        ]);
    }

    /**
     * Validasi token.
     *
     * Digunakan nanti pada Sprint Scanner QR.
     */
    public function validateToken(
    string $token
): ?SesiAbsensi {
    $parts = explode('.', $token);

    if (count($parts) !== 3) {
        return null;
    }

    [$sesiId, $interval, $signature] = $parts;

    if (
        ! ctype_digit($sesiId)
        || ! ctype_digit($interval)
    ) {
        return null;
    }

    $sesi = SesiAbsensi::find($sesiId);

    if (! $sesi) {
        return null;
    }

    if ($sesi->status !== 'aktif') {
        return null;
    }

    $interval = (int) $interval;

    $currentInterval =
        $this->currentInterval();

    /*
    * Token hanya berlaku pada interval
    * 15 detik yang sedang aktif.
    *
    * Begitu interval berganti,
    * token sebelumnya langsung tidak berlaku.
    */
    if ($interval !== $currentInterval) {
        return null;
    }

    $expectedSignature =
        $this->generateSignature(
            $sesi,
            $interval
        );

    if (
        ! hash_equals(
            $expectedSignature,
            $signature
        )
    ) {
        return null;
    }

    return $sesi;
}

    /**
     * Sisa waktu QR saat ini.
     */
    public function secondsRemaining(): int
    {
        $remainder =
            now()->timestamp % self::INTERVAL;

        return self::INTERVAL - $remainder;
    }
}