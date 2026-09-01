@extends('layouts.app')

@section('title', 'Cetak QR Siswa')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | KONFIGURASI UKURAN NAME TAG
    |--------------------------------------------------------------------------
    |
    | Ukuran kartu dalam ukuran nyata.
    |
    */

    $ukuranConfig = [

        'B1' => [
            'label'  => 'B1',
            'width'  => '90mm',
            'height' => '55mm',

            'qr'     => '32mm',

            'padding'=> '3mm',
            'nama'   => '8pt',
            'info'   => '6.5pt',
            'header' => '6.5pt',
        ],

        'B2' => [
            'label'  => 'B2',
            'width'  => '73mm',
            'height' => '111mm',

            'qr'     => '47mm',

            'padding'=> '4mm',
            'nama'   => '9.5pt',
            'info'   => '7pt',
            'header' => '7pt',
        ],

        'B3' => [
            'label'  => 'B3',
            'width'  => '85mm',
            'height' => '110mm',

            'qr'     => '54mm',

            'padding'=> '4.5mm',
            'nama'   => '10pt',
            'info'   => '7.5pt',
            'header' => '7.5pt',
        ],

        'B4' => [
            'label'  => 'B4',
            'width'  => '93mm',
            'height' => '137mm',

            'qr'     => '66mm',

            'padding'=> '5mm',
            'nama'   => '11pt',
            'info'   => '8pt',
            'header' => '8pt',
        ],

    ];


    $config =
        $ukuranConfig[$ukuran]
        ?? $ukuranConfig['B4'];


    /*
    |--------------------------------------------------------------------------
    | BACKGROUND FOTO SEKOLAH
    |--------------------------------------------------------------------------
    */

    $backgroundImage =
        asset('images/Sma62.png');


    /*
    |--------------------------------------------------------------------------
    | URUTAN KELAS
    |--------------------------------------------------------------------------
    |
    | Kelas X → XI → XII.
    |
    */

    $urutanKelas = [

        'X'   => 1,
        'XI'  => 2,
        'XII' => 3,

    ];


    /*
    |--------------------------------------------------------------------------
    | URUTKAN SISWA
    |--------------------------------------------------------------------------
    |
    | 1. Tingkat kelas
    | 2. Nama kelas
    | 3. Nama siswa A-Z
    |
    */

    $siswas =
        $siswas
            ->sortBy(function ($siswa) use ($urutanKelas) {

                $namaKelas =
                    strtoupper(
                        trim(
                            $siswa->kelas?->nama ?? ''
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | Ambil tingkat kelas
                |--------------------------------------------------------------------------
                |
                | Mendukung:
                | X
                | X IPA
                | X IPS
                | XI IPA
                | XII IPS
                |
                */

                $tingkat =
                    999;


                if (
                    preg_match(
                        '/^XII\b/i',
                        $namaKelas
                    )
                ) {

                    $tingkat = 12;

                } elseif (
                    preg_match(
                        '/^XI\b/i',
                        $namaKelas
                    )
                ) {

                    $tingkat = 11;

                } elseif (
                    preg_match(
                        '/^X\b/i',
                        $namaKelas
                    )
                ) {

                    $tingkat = 10;

                }


                return [

                    $tingkat,

                    $namaKelas,

                    strtoupper(
                        trim(
                            $siswa->nama ?? ''
                        )
                    ),

                ];

            })
            ->values();


    /*
    |--------------------------------------------------------------------------
    | KELOMPOKKAN BERDASARKAN KELAS
    |--------------------------------------------------------------------------
    */

    $kelompokKelas =
        $siswas->groupBy(function ($siswa) {

            return
                $siswa->kelas?->nama
                ?? '-';

        });

@endphp


{{-- =========================================================================
     HEADER
========================================================================= --}}

<div class="page-header mb-4 d-print-none">

    <div class="row align-items-center">

        <div class="col">

            <div class="text-secondary mb-1">
                Data Siswa
            </div>

            <h2 class="page-title">
                Cetak QR Siswa
            </h2>

            <div class="text-secondary mt-1">

                Cetak seluruh QR siswa
                dalam format name tag.

            </div>

        </div>


        <div class="col-auto">

            <a
                href="{{ route('siswa.index') }}"
                class="btn btn-outline-secondary"
            >

                <i class="ti ti-arrow-left me-1"></i>

                Kembali

            </a>

        </div>

    </div>

</div>



{{-- =========================================================================
     PENGATURAN
========================================================================= --}}

<div class="card mb-4 d-print-none">

    <div class="card-body">

        <form
            action="{{ route('siswa.cetak.qr') }}"
            method="GET"
        >

            <div class="row align-items-end">


                {{-- =========================================================
                     UKURAN
                ========================================================== --}}

                <div class="col-md-5 mb-3 mb-md-0">

                    <label class="form-label fw-semibold">

                        Ukuran Name Tag

                    </label>


                    <select
                        name="ukuran"
                        class="form-select"
                        onchange="this.form.submit()"
                    >

                        @foreach($ukuranConfig as $key => $item)

                            <option
                                value="{{ $key }}"
                                @selected($ukuran === $key)
                            >

                                {{ $item['label'] }}

                                —

                                @if($key === 'B1')
                                    90 × 55 mm
                                @elseif($key === 'B2')
                                    73 × 111 mm
                                @elseif($key === 'B3')
                                    85 × 110 mm
                                @elseif($key === 'B4')
                                    93 × 137 mm
                                @endif

                            </option>

                        @endforeach

                    </select>


                    <div class="form-hint mt-2">

                        QR otomatis menyesuaikan ukuran kartu.

                    </div>

                </div>



                {{-- =========================================================
                     INFO
                ========================================================== --}}

                <div class="col-md-4 mb-3 mb-md-0">

                    <div class="small text-secondary">

                        <div>

                            <strong>
                                Ukuran kartu:
                            </strong>

                            {{ $config['width'] }}

                            ×

                            {{ $config['height'] }}

                        </div>


                        <div>

                            <strong>
                                Ukuran QR:
                            </strong>

                            {{ $config['qr'] }}

                        </div>


                        <div>

                            <strong>
                                Total:
                            </strong>

                            {{ $siswas->count() }}

                            siswa

                        </div>

                    </div>

                </div>



                {{-- =========================================================
                     BUTTON
                ========================================================== --}}

                <div class="col-md-3">

                    <button
                        type="button"
                        class="btn btn-primary w-100"
                        onclick="window.print()"
                    >

                        <i class="ti ti-printer me-1"></i>

                        Cetak

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>



{{-- =========================================================================
     INFO
========================================================================= --}}

<div class="alert alert-info d-print-none mb-4">

    <div class="d-flex">

        <i
            class="ti ti-info-circle me-2 mt-1"
        ></i>


        <div>

            <strong>
                {{ $siswas->count() }} siswa
            </strong>

            akan dicetak.


            <div class="small mt-1">

                Untuk hasil sesuai ukuran gunakan

                <strong>
                    Scale 100%
                </strong>

                atau

                <strong>
                    Actual Size
                </strong>.

                Jangan menggunakan

                <strong>
                    Fit to Page
                </strong>.

            </div>

        </div>

    </div>

</div>

{{-- =========================================================================
     KOP / IDENTITAS CETAK
========================================================================= --}}

<div class="print-letterhead">

    <div class="print-letterhead-title">
        SSIS
    </div>

    <div class="print-letterhead-school">
        SMA NEGERI 6 MALINAU
    </div>

    <div class="print-letterhead-subtitle">
        SMART SCHOOL INFORMATION SYSTEM
    </div>

    <div class="print-letterhead-line"></div>

    <div class="print-document-title">
        CETAK QR SISWA
    </div>

    <div class="print-document-info">
        Kartu QR untuk Absensi dan Peminjaman Buku
    </div>

</div>


{{-- =========================================================================
     AREA QR
========================================================================= --}}

<div
    class="qr-print-area"
    data-size="{{ $ukuran }}"
>


@if($kelompokKelas->isEmpty())

    <div class="card d-print-none">

        <div class="card-body text-center py-5">

            <i
                class="ti ti-users-off"
                style="font-size:40px;"
            ></i>


            <h3 class="mt-3">

                Tidak ada siswa

            </h3>


            <div class="text-secondary">

                Belum ada siswa aktif.

            </div>

        </div>

    </div>

@else


    {{-- =====================================================================
         PER KELAS
    ====================================================================== --}}

    @foreach($kelompokKelas as $namaKelas => $siswaKelas)


        {{-- ================================================================
             PEMISAH KELAS
        ================================================================= --}}

        <div class="class-separator">

            <span class="class-separator-line"></span>

            <span class="class-separator-title">

                KELAS
                {{ strtoupper($namaKelas) }}

            </span>

            <span class="class-separator-line"></span>

        </div>



        {{-- ================================================================
             SISWA DALAM KELAS
        ================================================================= --}}

        @foreach($siswaKelas as $siswa)

            @php

                /*
                |--------------------------------------------------------------------------
                | AMBIL QR TOKEN YANG SUDAH TERSIMPAN
                |--------------------------------------------------------------------------
                */

                $qrToken =
                    $siswa->qr_token;


                /*
                |--------------------------------------------------------------------------
                | SISWA LAMA YANG BELUM PUNYA TOKEN
                |--------------------------------------------------------------------------
                |
                | Hanya dibuat satu kali.
                |
                */

                if (! $qrToken) {

                    $qrToken =
                        \App\Models\Siswa
                            ::generateUniqueQrToken();


                    $siswa->update([

                        'qr_token' => $qrToken,

                    ]);

                }


                /*
                |--------------------------------------------------------------------------
                | PAYLOAD QR
                |--------------------------------------------------------------------------
                */

                $payload =
                    'SSIS-SISWA:' .
                    $qrToken;


                /*
                |--------------------------------------------------------------------------
                | GENERATE QR
                |--------------------------------------------------------------------------
                */

                $qrCode =
                    new \Endroid\QrCode\QrCode(

                        data:
                            $payload,

                        encoding:
                            new \Endroid\QrCode\Encoding\Encoding(
                                'UTF-8'
                            ),

                        errorCorrectionLevel:
                            \Endroid\QrCode\ErrorCorrectionLevel::Medium,

                        size:
                            600,

                        margin:
                            8,

                        roundBlockSizeMode:
                            \Endroid\QrCode\RoundBlockSizeMode::Margin

                    );


                $writer =
                    new \Endroid\QrCode\Writer\SvgWriter();


                $result =
                    $writer->write(
                        $qrCode
                    );


                $qr =
                    base64_encode(
                        $result->getString()
                    );

            @endphp



            {{-- ============================================================
                 CARD
            ============================================================= --}}

            <div
                class="
                    qr-card
                    qr-size-{{ strtolower($ukuran) }}
                "
                style="
                    --card-width: {{ $config['width'] }};
                    --card-height: {{ $config['height'] }};
                    --qr-size: {{ $config['qr'] }};
                    --card-padding: {{ $config['padding'] }};
                    --name-size: {{ $config['nama'] }};
                    --info-size: {{ $config['info'] }};
                    --header-size: {{ $config['header'] }};
                    --school-background: url('{{ $backgroundImage }}');
                "
            >


                {{-- ========================================================
                     BACKGROUND
                ========================================================= --}}

                <div class="qr-background"></div>


                {{-- ========================================================
                     OVERLAY
                ========================================================= --}}

                <div class="qr-overlay"></div>


                {{-- ========================================================
                     CONTENT
                ========================================================= --}}

                <div class="qr-card-inner">


                    {{-- ====================================================
                         HEADER
                    ===================================================== --}}

                    <div class="school-header">

                        <div class="school-title">

                            SMA NEGERI 6 MALINAU

                        </div>


                        <div class="school-subtitle">

                            KARTU ABSENSI SISWA

                        </div>

                    </div>



                    {{-- ====================================================
                         QR
                    ===================================================== --}}

                    <div class="qr-wrapper">

                        <img
                            src="data:image/svg+xml;base64,{{ $qr }}"
                            alt="QR {{ $siswa->nama }}"
                            class="qr-image"
                        >

                    </div>



                    {{-- ====================================================
                         DATA SISWA
                    ===================================================== --}}

                    <div class="student-info">


                        <div class="student-name">

                            {{ strtoupper($siswa->nama) }}

                        </div>


                        <div class="student-row">

                            <span class="student-label">

                                NIS

                            </span>


                            <span class="student-value">

                                {{ $siswa->nis }}

                            </span>

                        </div>


                        @if($siswa->nisn)

                            <div class="student-row">

                                <span class="student-label">

                                    NISN

                                </span>


                                <span class="student-value">

                                    {{ $siswa->nisn }}

                                </span>

                            </div>

                        @endif


                        <div class="student-row">

                            <span class="student-label">

                                KELAS

                            </span>


                            <span class="student-value">

                                {{ $siswa->kelas?->nama ?? '-' }}

                            </span>

                        </div>


                    </div>



                    {{-- ====================================================
                         FOOTER
                    ===================================================== --}}

                    <div class="qr-footer">

                        Scan QR untuk absensi dan Pinjam Buku

                    </div>


                </div>

            </div>

        @endforeach


    @endforeach

@endif


</div>



{{-- =========================================================================
     CSS
========================================================================= --}}

<style>


/*
|--------------------------------------------------------------------------
| AREA PREVIEW
|--------------------------------------------------------------------------
*/

.qr-print-area {

    display: flex;

    flex-wrap: wrap;

    justify-content: center;

    align-items: flex-start;

    gap: 18px;

    padding: 10px 0 40px;

}



/*
|--------------------------------------------------------------------------
| PEMISAH KELAS
|--------------------------------------------------------------------------
*/

.class-separator {

    width: 100%;

    flex-basis: 100%;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 12px;

    margin-top: 10px;

    margin-bottom: 2px;

}


.class-separator-line {

    height: 1px;

    flex: 1;

    background: #d9dee5;

}


.class-separator-title {

    flex-shrink: 0;

    padding: 5px 14px;

    border-radius: 999px;

    background: #f1f5f9;

    border: 1px solid #dbe2ea;

    color: #374151;

    font-size: 12px;

    font-weight: 800;

    letter-spacing: .4px;

}



/*
|--------------------------------------------------------------------------
| CARD
|--------------------------------------------------------------------------
*/

.qr-card {

    position: relative;

    width: var(--card-width);

    height: var(--card-height);

    padding: var(--card-padding);

    box-sizing: border-box;

    border: 1px solid #cbd5e1;

    border-radius: 8px;

    overflow: hidden;

    box-shadow:
        0 4px 16px
        rgba(0, 0, 0, .10);

    flex-shrink: 0;

    background: #ffffff;

    isolation: isolate;

}



/*
|--------------------------------------------------------------------------
| BACKGROUND FOTO
|--------------------------------------------------------------------------
*/

.qr-background {

    position: absolute;

    inset: 0;

    z-index: -2;

    background-image:
        var(--school-background);

    background-size: cover;

    background-position: center;

    background-repeat: no-repeat;

    transform: scale(1.01);

}



/*
|--------------------------------------------------------------------------
| OVERLAY
|--------------------------------------------------------------------------
*/

.qr-overlay {

    position: absolute;

    inset: 0;

    z-index: -1;

    background:
        rgba(255, 255, 255, .70);

}



/*
|--------------------------------------------------------------------------
| INNER
|--------------------------------------------------------------------------
*/

.qr-card-inner {

    position: relative;

    z-index: 1;

    width: 100%;

    height: 100%;

    box-sizing: border-box;

    display: flex;

    flex-direction: column;

    align-items: center;

    text-align: center;

}



/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.school-header {

    width: 100%;

    flex-shrink: 0;

    margin-bottom: 2mm;

}


.school-title {

    font-size:
        var(--header-size);

    font-weight: 800;

    line-height: 1.15;

    white-space: nowrap;

    color: #111827;

    text-shadow:
        0 1px 1px
        rgba(255,255,255,.8);

}


.school-subtitle {

    margin-top: 1mm;

    font-size:
        calc(var(--header-size) - 1pt);

    color: #374151;

    font-weight: 600;

    line-height: 1.1;

}



/*
|--------------------------------------------------------------------------
| QR
|--------------------------------------------------------------------------
*/

.qr-wrapper {

    width: var(--qr-size);

    height: var(--qr-size);

    flex:
        0 0 var(--qr-size);

    display: flex;

    justify-content: center;

    align-items: center;

    background: #ffffff;

    border-radius: 3mm;

    padding: 2mm;

    box-sizing: border-box;

    box-shadow:
        0 2px 8px
        rgba(0,0,0,.12);

}


.qr-image {

    display: block;

    width: 100%;

    height: 100%;

    max-width: none;

    max-height: none;

}



/*
|--------------------------------------------------------------------------
| INFO SISWA
|--------------------------------------------------------------------------
*/

.student-info {

    width: 100%;

    flex: 1;

    min-height: 0;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;

    margin-top: 1.5mm;

    overflow: hidden;

}


.student-name {

    width: 100%;

    font-size:
        var(--name-size);

    font-weight: 800;

    line-height: 1.15;

    color: #111827;

    word-break: break-word;

    overflow-wrap: anywhere;

    display: -webkit-box;

    -webkit-line-clamp: 2;

    -webkit-box-orient: vertical;

    overflow: hidden;

    margin-bottom: 1mm;

    text-shadow:
        0 1px 1px
        rgba(255,255,255,.9);

}


.student-row {

    display: flex;

    justify-content: center;

    align-items: baseline;

    gap: 1.5mm;

    width: 100%;

    font-size:
        var(--info-size);

    line-height: 1.25;

    white-space: nowrap;

}


.student-label {

    font-weight: 700;

}


.student-value {

    font-weight: 500;

    overflow: hidden;

    text-overflow: ellipsis;

}



/*
|--------------------------------------------------------------------------
| FOOTER
|--------------------------------------------------------------------------
*/

.qr-footer {

    width: 100%;

    flex-shrink: 0;

    border-top:
        1px solid
        rgba(107,114,128,.45);

    padding-top: 1mm;

    margin-top: 1mm;

    font-size:
        calc(var(--info-size) - 1pt);

    color: #374151;

    line-height: 1.1;

    font-weight: 600;

}



/*
|--------------------------------------------------------------------------
| B1
|--------------------------------------------------------------------------
*/

.qr-size-b1 .school-header {

    margin-bottom: 1mm;

}


.qr-size-b1 .student-info {

    margin-top: 1mm;

}


.qr-size-b1 .student-name {

    margin-bottom: .5mm;

    -webkit-line-clamp: 1;

}


.qr-size-b1 .qr-footer {

    display: none;

}



/*
|--------------------------------------------------------------------------
| B2
|--------------------------------------------------------------------------
*/

.qr-size-b2 .student-info {

    margin-top: 1mm;

}



/*
|--------------------------------------------------------------------------
| PRINT
|--------------------------------------------------------------------------
*/

@media print {


    /*
    |--------------------------------------------------------------------------
    | BODY
    |--------------------------------------------------------------------------
    */

    html,
    body {

        margin: 0 !important;

        padding: 0 !important;

        background:
            #ffffff !important;

    }


    /*
    |--------------------------------------------------------------------------
    | HILANGKAN UI
    |--------------------------------------------------------------------------
    */

    .navbar,
    .navbar-vertical,
    .page-header,
    .d-print-none {

        display:
            none !important;

    }


    /*
    |--------------------------------------------------------------------------
    | LAYOUT LARAVEL
    |--------------------------------------------------------------------------
    */

    .page-wrapper,
    .page,
    .page-body {

        margin: 0 !important;

        padding: 0 !important;

        background:
            #ffffff !important;

    }


    .container,
    .container-xl,
    .container-fluid {

        width:
            auto !important;

        max-width:
            none !important;

        margin: 0 !important;

        padding: 0 !important;

    }


    /*
    |--------------------------------------------------------------------------
    | AREA PRINT
    |--------------------------------------------------------------------------
    */

    .qr-print-area {

        display: flex !important;

        flex-wrap: wrap !important;

        justify-content: flex-start !important;

        align-items: flex-start !important;

        align-content: flex-start !important;

        gap: 0 !important;

        width: 100% !important;

        padding: 0 !important;

        margin: 0 !important;

    }


    /*
    |--------------------------------------------------------------------------
    | PEMISAH KELAS
    |--------------------------------------------------------------------------
    */

    .class-separator {

        width: 100% !important;

        flex-basis: 100% !important;

        display: flex !important;

        align-items: center !important;

        justify-content: center !important;

        gap: 8px !important;

        margin-top: 3mm !important;

        margin-bottom: 2mm !important;

        page-break-after: avoid !important;

        break-after: avoid !important;

    }


    .class-separator-line {

        height: .3mm !important;

        background:
            #9ca3af !important;

    }


    .class-separator-title {

        padding:
            1.5mm 4mm !important;

        border:
            .25mm solid #9ca3af !important;

        border-radius:
            4mm !important;

        background:
            #f1f5f9 !important;

        font-size:
            8pt !important;

        font-weight:
            800 !important;

    }


    /*
    |--------------------------------------------------------------------------
    | CARD
    |--------------------------------------------------------------------------
    */

    .qr-card {

        width:
            var(--card-width) !important;

        height:
            var(--card-height) !important;

        min-width:
            var(--card-width) !important;

        min-height:
            var(--card-height) !important;

        max-width:
            var(--card-width) !important;

        max-height:
            var(--card-height) !important;

        box-sizing:
            border-box !important;

        margin-right:
            3mm !important;

        margin-bottom:
            3mm !important;

        padding:
            var(--card-padding) !important;

        border:
            .25mm solid #cbd5e1 !important;

        border-radius:
            0 !important;

        box-shadow:
            none !important;

        page-break-inside:
            avoid !important;

        break-inside:
            avoid !important;

        overflow:
            hidden !important;

    }


    /*
    |--------------------------------------------------------------------------
    | BACKGROUND FOTO
    |--------------------------------------------------------------------------
    */

    .qr-background {

        position: absolute !important;

        inset: 0 !important;

        background-image:
            var(--school-background) !important;

        background-size:
            cover !important;

        background-position:
            center !important;

        background-repeat:
            no-repeat !important;

        -webkit-print-color-adjust:
            exact !important;

        print-color-adjust:
            exact !important;

    }


    /*
    |--------------------------------------------------------------------------
    | OVERLAY
    |--------------------------------------------------------------------------
    */

    .qr-overlay {

        background:
            rgba(255,255,255,.70) !important;

        -webkit-print-color-adjust:
            exact !important;

        print-color-adjust:
            exact !important;

    }


    /*
    |--------------------------------------------------------------------------
    | QR
    |--------------------------------------------------------------------------
    */

    .qr-wrapper,
    .qr-image {

        width:
            var(--qr-size) !important;

        height:
            var(--qr-size) !important;

    }


    /*
    |--------------------------------------------------------------------------
    | FORCE BACKGROUND
    |--------------------------------------------------------------------------
    */

    * {

        -webkit-print-color-adjust:
            exact !important;

        print-color-adjust:
            exact !important;

    }

}



/*
|--------------------------------------------------------------------------
| UKURAN KERTAS
|--------------------------------------------------------------------------
|
| Gunakan A4 karena kartu-kartu B1-B4 disusun di atas lembar A4.
|
*/

@media print {

    @page {

        size:
            A4 portrait;

        margin:
            5mm;

    }

}



/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 767px) {

    .qr-print-area {

        justify-content:
            center;

    }


    .class-separator {

        margin-top:
            15px;

    }

}

/*
|--------------------------------------------------------------------------
| KOP / IDENTITAS CETAK
|--------------------------------------------------------------------------
*/

.print-letterhead {
    display: none;
}


/*
|--------------------------------------------------------------------------
| PRINT KOP
|--------------------------------------------------------------------------
*/

@media print {

    .print-letterhead {

        display: block !important;

        width: 100%;

        text-align: center;

        margin-bottom: 5mm;

        page-break-after: avoid;

        break-after: avoid;

    }


    .print-letterhead-title {

        font-size: 11pt;

        font-weight: 900;

        letter-spacing: 1.5px;

        line-height: 1;

        color: #111827;

    }


    .print-letterhead-school {

        margin-top: 1.2mm;

        font-size: 15pt;

        font-weight: 900;

        letter-spacing: .3px;

        line-height: 1.1;

        color: #111827;

    }


    .print-letterhead-subtitle {

        margin-top: 1mm;

        font-size: 8pt;

        font-weight: 600;

        letter-spacing: .5px;

        color: #4b5563;

    }


    .print-letterhead-line {

        width: 100%;

        height: .35mm;

        margin-top: 2.5mm;

        margin-bottom: 2.5mm;

        background: #111827;

    }


    .print-document-title {

        font-size: 10pt;

        font-weight: 800;

        letter-spacing: .5px;

        color: #111827;

    }


    .print-document-info {

        margin-top: .8mm;

        font-size: 7.5pt;

        color: #4b5563;

    }

}

</style>



<script>

    /*
    |--------------------------------------------------------------------------
    | SET JUDUL SETELAH PRINT
    |--------------------------------------------------------------------------
    */

    window.addEventListener(
        'afterprint',
        function () {

            document.title =
                'Cetak QR Siswa';

        }
    );

</script>

@endsection