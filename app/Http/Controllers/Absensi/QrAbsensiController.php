<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use App\Models\SesiAbsensi;
use App\Services\AbsensiQrService;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;

class QrAbsensiController extends Controller
{
    public function show(
        SesiAbsensi $sesi,
        AbsensiQrService $qrService
    ) {
        if ($sesi->status !== 'aktif') {
            return response()->json([
                'message' => 'Sesi absensi sudah ditutup.',
            ], 422);
        }

        $now = now();

$mulai = $sesi->tanggal
    ->copy()
    ->setTimeFromTimeString(
        $sesi->waktu_mulai
    );

$selesai = $sesi->tanggal
    ->copy()
    ->setTimeFromTimeString(
        $sesi->waktu_selesai
    );

if ($now->lt($mulai)) {
    return response()->json([
        'message' => 'Sesi absensi belum dimulai.',
    ], 422);
}

if ($now->gt($selesai)) {
    return response()->json([
        'message' => 'Waktu sesi absensi telah berakhir.',
    ], 422);
}

        $token = $qrService->generateToken($sesi);

        $qrCode = new QrCode(
            data: $token,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel:
                ErrorCorrectionLevel::Medium,
            size: 300,
            margin: 10,
            roundBlockSizeMode:
                RoundBlockSizeMode::Margin
        );

        $writer = new SvgWriter();

        $result = $writer->write($qrCode);

        return response()->json([
            'qr' => base64_encode(
                $result->getString()
            ),

            'expires_in' =>
                $qrService->secondsRemaining(),
        ]);
    }
}