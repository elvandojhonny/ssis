<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Cetak QR Siswa
    </title>

    <style>

        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #eee;
        }


        /*
        |--------------------------------------------------------------------------
        | TOOLBAR
        |--------------------------------------------------------------------------
        */

        .toolbar {

            position: sticky;
            top: 0;

            z-index: 1000;

            background: #fff;

            padding: 15px 20px;

            border-bottom: 1px solid #ddd;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

        }


        .toolbar-left {

            display: flex;

            align-items: center;

            gap: 10px;

            flex-wrap: wrap;

        }


        .toolbar-title {

            font-size: 18px;

            font-weight: 700;

        }


        .toolbar select {

            height: 38px;

            padding: 0 12px;

            border: 1px solid #ccc;

            border-radius: 6px;

            background: #fff;

        }


        .btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 9px 15px;

            border-radius: 6px;

            border: 1px solid #ccc;

            background: #fff;

            text-decoration: none;

            color: #222;

            cursor: pointer;

            font-size: 14px;

        }


        .btn-primary {

            background: #206bc4;

            border-color: #206bc4;

            color: #fff;

        }


        /*
        |--------------------------------------------------------------------------
        | AREA CETAK
        |--------------------------------------------------------------------------
        */

        .print-area {

            padding: 20px;

        }


        /*
        |--------------------------------------------------------------------------
        | GRID
        |--------------------------------------------------------------------------
        */

        .qr-grid {

            display: grid;

            gap: 10mm;

            justify-content: center;

        }


        /*
        |--------------------------------------------------------------------------
        | CARD QR
        |--------------------------------------------------------------------------
        */

        .qr-card {

            background: #fff;

            border: 1px solid #222;

            border-radius: 4px;

            padding: 8mm;

            text-align: center;

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            break-inside: avoid;

            page-break-inside: avoid;

        }


        .school-name {

            font-size: 14px;

            font-weight: 700;

            margin-bottom: 2mm;

            text-transform: uppercase;

        }


        .school-subtitle {

            font-size: 10px;

            color: #555;

            margin-bottom: 4mm;

        }


        .qr-wrapper {

            display: flex;

            align-items: center;

            justify-content: center;

            background: #fff;

        }


        .qr-wrapper svg {

            display: block;

            width: 100%;

            height: 100%;

        }


        .student-name {

            margin-top: 4mm;

            font-size: 14px;

            font-weight: 700;

            text-transform: uppercase;

        }


        .student-info {

            margin-top: 1mm;

            font-size: 11px;

            line-height: 1.5;

        }


        /*
        |--------------------------------------------------------------------------
        | B1
        |--------------------------------------------------------------------------
        */

        .size-B1 .qr-grid {

            grid-template-columns: repeat(2, 1fr);

        }

        .size-B1 .qr-card {

            min-height: 120mm;

        }

        .size-B1 .qr-wrapper {

            width: 75mm;

            height: 75mm;

        }


        /*
        |--------------------------------------------------------------------------
        | B2
        |--------------------------------------------------------------------------
        */

        .size-B2 .qr-grid {

            grid-template-columns: repeat(3, 1fr);

        }

        .size-B2 .qr-card {

            min-height: 90mm;

        }

        .size-B2 .qr-wrapper {

            width: 55mm;

            height: 55mm;

        }


        /*
        |--------------------------------------------------------------------------
        | B3
        |--------------------------------------------------------------------------
        */

        .size-B3 .qr-grid {

            grid-template-columns: repeat(4, 1fr);

        }

        .size-B3 .qr-card {

            min-height: 70mm;

            padding: 5mm;

        }

        .size-B3 .qr-wrapper {

            width: 42mm;

            height: 42mm;

        }

        .size-B3 .school-name {

            font-size: 11px;

        }

        .size-B3 .student-name {

            font-size: 11px;

        }

        .size-B3 .student-info {

            font-size: 9px;

        }


        /*
        |--------------------------------------------------------------------------
        | B4
        |--------------------------------------------------------------------------
        */

        .size-B4 .qr-grid {

            grid-template-columns: repeat(3, 1fr);

        }

        .size-B4 .qr-card {

            min-height: 70mm;

            padding: 5mm;

        }

        .size-B4 .qr-wrapper {

            width: 42mm;

            height: 42mm;

        }

        .size-B4 .school-name {

            font-size: 11px;

        }

        .size-B4 .student-name {

            font-size: 11px;

        }

        .size-B4 .student-info {

            font-size: 9px;

        }


        /*
        |--------------------------------------------------------------------------
        | PRINT
        |--------------------------------------------------------------------------
        */

        @media print {

            @page {

                size: A4 portrait;

                margin: 10mm;

            }


            body {

                background: #fff;

            }


            .toolbar {

                display: none !important;

            }


            .print-area {

                padding: 0;

            }


            .qr-card {

                box-shadow: none;

            }


            .qr-grid {

                gap: 5mm;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE PREVIEW
        |--------------------------------------------------------------------------
        */

        @media(max-width: 768px) {

            .toolbar {

                position: relative;

                flex-direction: column;

                align-items: stretch;

            }


            .toolbar-left {

                flex-direction: column;

                align-items: stretch;

            }


            .toolbar .btn {

                width: 100%;

            }


            .print-area {

                padding: 10px;

            }


            .qr-grid {

                grid-template-columns: 1fr !important;

            }


            .qr-card {

                min-height: auto !important;

            }

        }

    </style>

</head>


<body>

    {{-- ===================================================== --}}
    {{-- TOOLBAR --}}
    {{-- ===================================================== --}}

    <div class="toolbar">

        <div class="toolbar-left">

            <div class="toolbar-title">

                Cetak QR Siswa

            </div>

            <form
                method="GET"
                action="{{ route('siswa.qr.cetakSemua') }}"
            >

                <select
                    name="ukuran"
                    onchange="this.form.submit()"
                >

                    <option
                        value="B1"
                        @selected($ukuran === 'B1')
                    >
                        B1 - Besar
                    </option>

                    <option
                        value="B2"
                        @selected($ukuran === 'B2')
                    >
                        B2
                    </option>

                    <option
                        value="B3"
                        @selected($ukuran === 'B3')
                    >
                        B3
                    </option>

                    <option
                        value="B4"
                        @selected($ukuran === 'B4')
                    >
                        B4 - Ringkas
                    </option>

                </select>

            </form>

            <span>

                {{ $siswas->count() }} siswa

            </span>

        </div>


        <div>

            <a
                href="{{ route('siswa.index') }}"
                class="btn"
            >

                Kembali

            </a>

            <button
                type="button"
                class="btn btn-primary"
                onclick="window.print()"
            >

                🖨️ Cetak / Simpan PDF

            </button>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- PRINT AREA --}}
    {{-- ===================================================== --}}

    <main class="print-area size-{{ $ukuran }}">

        <div class="qr-grid">

            @foreach($siswas as $siswa)

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | Payload QR
                    |--------------------------------------------------------------------------
                    |
                    | HARUS sama dengan QR siswa yang sekarang.
                    |
                    */

                    $payload =
                        'SSIS-SISWA:'
                        . $siswa->qr_token;

                    /*
                    |--------------------------------------------------------------------------
                    | Generate QR
                    |--------------------------------------------------------------------------
                    */

                    $qrCode = new \Endroid\QrCode\QrCode(
                        data: $payload,
                        encoding: new \Endroid\QrCode\Encoding\Encoding('UTF-8'),
                        errorCorrectionLevel:
                            \Endroid\QrCode\ErrorCorrectionLevel::Medium,
                        size: 500,
                        margin: 10,
                        roundBlockSizeMode:
                            \Endroid\QrCode\RoundBlockSizeMode::Margin
                    );

                    $writer =
                        new \Endroid\QrCode\Writer\SvgWriter();

                    $result =
                        $writer->write($qrCode);

                    $qr =
                        base64_encode(
                            $result->getString()
                        );

                @endphp


                <div class="qr-card">

                    <div class="school-name">

                        SMA NEGERI 6 MALINAU

                    </div>


                    <div class="school-subtitle">

                        QR IDENTITAS SISWA

                    </div>


                    <div class="qr-wrapper">

                        <img
                            src="data:image/svg+xml;base64,{{ $qr }}"
                            alt="QR {{ $siswa->nama }}"
                        >

                    </div>


                    <div class="student-name">

                        {{ $siswa->nama }}

                    </div>


                    <div class="student-info">

                        NIS: {{ $siswa->nis }}

                        <br>

                        Kelas:
                        {{ $siswa->kelas?->nama ?? '-' }}

                    </div>

                </div>

            @endforeach

        </div>

    </main>

</body>

</html>