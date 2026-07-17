<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Request;

class QrSiswaController extends Controller
{
    public function show(Siswa $siswa)
    {
        abort_unless(
            $siswa->is_active,
            404,
            'Data siswa tidak aktif.'
        );

        $siswa->load([
            'user',
            'kelas.tahunAjaran',
        ]);

        /*
         * Generate token jika siswa lama
         * belum memilikinya.
         */
        if (! $siswa->qr_token) {
            $siswa->update([
                'qr_token' =>
                    Siswa::generateUniqueQrToken(),
            ]);
        }

        /*
         * Payload yang disimpan dalam QR.
         */
        $payload = 'SSIS-SISWA:' . $siswa->qr_token;

        $qrCode = new QrCode(
            data: $payload,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel:
                ErrorCorrectionLevel::Medium,
            size: 350,
            margin: 10,
            roundBlockSizeMode:
                RoundBlockSizeMode::Margin
        );

        $writer = new SvgWriter();

        $result = $writer->write($qrCode);

        $qr = base64_encode(
            $result->getString()
        );

        return view(
            'master.siswa.qr.show',
            compact(
                'siswa',
                'qr'
            )
        );
    }


    public function regenerate(
        Request $request,
        Siswa $siswa
    ) {
        $siswa->update([
            'qr_token' =>
                Siswa::generateUniqueQrToken(),
        ]);

        return redirect()
            ->route('siswa.qr.show', $siswa)
            ->with(
                'success',
                'QR siswa berhasil dibuat ulang. QR lama sudah tidak dapat digunakan.'
            );
    }
}