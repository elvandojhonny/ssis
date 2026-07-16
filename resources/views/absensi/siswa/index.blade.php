@extends('layouts.app')

@section('title', 'Absensi Saya')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <div class="page-pretitle">
                Modul Absensi
            </div>

            <h2 class="page-title">
                Absensi Saya
            </h2>

            <div class="text-secondary mt-1">

                {{ $user->siswa->kelas->nama }}

                —

                {{
                    $user
                        ->siswa
                        ->kelas
                        ->tahunAjaran
                        ->nama
                }}

            </div>

        </div>

    </div>

</div>


<div class="row row-cards">

    {{-- SCANNER --}}

    <div class="col-lg-7">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    Scan QR Absensi

                </h3>

            </div>


            <div class="card-body">

                <div
                    id="scanner-start"
                    class="text-center py-5"
                >

                    <span
                        class="avatar avatar-xl
                               bg-blue-lt mb-3"
                    >

                        <i class="ti ti-scan"></i>

                    </span>


                    <h2>

                        Scan QR Absensi

                    </h2>


                    <p class="text-secondary">

                        Aktifkan kamera kemudian arahkan
                        ke QR Code yang ditampilkan oleh guru.

                    </p>


                    <button
                        type="button"
                        id="start-camera"
                        class="btn btn-primary"
                    >

                        <i class="ti ti-camera me-2"></i>

                        Buka Kamera

                    </button>

                </div>


                <div
                    id="scanner-container"
                    class="d-none"
                >

                    <div
                        id="qr-reader"
                        style="width: 100%;"
                    ></div>


                    <div class="text-center mt-3">

                        <button
                            type="button"
                            id="stop-camera"
                            class="btn btn-outline-danger"
                        >

                            <i
                                class="ti
                                       ti-camera-off
                                       me-2"
                            ></i>

                            Tutup Kamera

                        </button>

                    </div>

                </div>


                {{-- HASIL --}}

                <div
                    id="scan-result"
                    class="d-none mt-4"
                >

                    <div
                        id="scan-alert"
                        class="alert"
                    >

                        <div
                            id="scan-message"
                            class="fw-bold"
                        ></div>

                        <div
                            id="scan-detail"
                            class="small mt-1"
                        ></div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- INFORMASI --}}

    <div class="col-lg-5">

        <div class="card mb-3">

            <div class="card-header">

                <h3 class="card-title">
                    Informasi Siswa
                </h3>

            </div>


            <div class="card-body">

                <div class="mb-3">

                    <div class="text-secondary">
                        Nama
                    </div>

                    <div class="fw-bold">
                        {{ $user->name }}
                    </div>

                </div>


                <div class="mb-3">

                    <div class="text-secondary">
                        NIS
                    </div>

                    <div class="fw-bold">
                        {{ $user->siswa->nis }}
                    </div>

                </div>


                <div>

                    <div class="text-secondary">
                        Kelas
                    </div>

                    <div class="fw-bold">

                        {{ $user->siswa->kelas->nama }}

                    </div>

                </div>

            </div>

        </div>


        <div class="alert alert-info">

            <div class="fw-bold mb-1">

                Cara melakukan absensi

            </div>

            Scan QR sesuai kelas Anda yang
            ditampilkan oleh guru atau operator.

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- REKAP ABSENSI SEMESTER --}}
{{-- ========================================================= --}}

<div class="card mt-4">

    <div class="card-header">

        <div class="row align-items-center w-100">

            <div class="col">

                <h3 class="card-title mb-1">
                    Rekap Absensi Semester
                </h3>

                <div class="text-secondary">
                    Ringkasan kehadiran selama tahun ajaran
                    {{
                        $user
                            ->siswa
                            ->kelas
                            ->tahunAjaran
                            ->nama
                    }}.
                </div>

            </div>


            <div class="col-auto">

                <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modal-riwayat-absensi"
                >

                    <i class="ti ti-history me-1"></i>

                    Lihat Detail

                </button>

            </div>

        </div>

    </div>


    <div class="card-body">

        <div class="row row-cards">


            {{-- HADIR --}}

            <div class="col-6 col-md">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="text-secondary mb-2">
                            Hadir
                        </div>

                        <div class="h1 mb-0 text-success">

                            {{ $statistik['hadir'] }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- TERLAMBAT --}}

            <div class="col-6 col-md">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="text-secondary mb-2">
                            Terlambat
                        </div>

                        <div class="h1 mb-0 text-warning">

                            {{ $statistik['terlambat'] }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- IZIN --}}

            <div class="col-6 col-md">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="text-secondary mb-2">
                            Izin
                        </div>

                        <div class="h1 mb-0">

                            {{ $statistik['izin'] }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- SAKIT --}}

            <div class="col-6 col-md">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="text-secondary mb-2">
                            Sakit
                        </div>

                        <div class="h1 mb-0">

                            {{ $statistik['sakit'] }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- ALPA --}}

            <div class="col-6 col-md">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="text-secondary mb-2">
                            Alpa
                        </div>

                        <div class="h1 mb-0 text-danger">

                            {{ $statistik['alpa'] }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- TOTAL --}}

        <div class="mt-4">

            <div class="d-flex justify-content-between">

                <span class="text-secondary">
                    Total Data Absensi
                </span>

                <strong>
                    {{ $statistik['total'] }}
                </strong>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL DETAIL RIWAYAT --}}
{{-- ========================================================= --}}

<div
    class="modal modal-blur fade"
    id="modal-riwayat-absensi"
    tabindex="-1"
    aria-hidden="true"
>

    <div
        class="
            modal-dialog
            modal-xl
            modal-dialog-centered
            modal-dialog-scrollable
        "
        role="document"
    >

        <div class="modal-content">


            {{-- HEADER --}}

            <div class="modal-header">

                <div>

                    <h2 class="modal-title">
                        Detail Riwayat Absensi
                    </h2>

                    <div class="text-secondary mt-1">

                        {{ $user->name }}

                        ·

                        {{
                            $user
                                ->siswa
                                ->kelas
                                ->nama
                        }}

                    </div>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>

            </div>


            {{-- BODY --}}

            <div class="modal-body p-0">

                <div class="table-responsive">

                    <table
                        class="
                            table
                            table-vcenter
                            card-table
                        "
                    >

                        <thead>

                            <tr>

                                <th>
                                    Tanggal
                                </th>

                                <th>
                                    Sesi
                                </th>

                                <th>
                                    Waktu
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @forelse(
                            $riwayat
                            as $absensi
                        )

                            <tr>

                                {{-- TANGGAL --}}

                                <td>

                                    <div class="fw-bold">

                                        {{
                                            $absensi
                                                ->sesiAbsensi
                                                ->tanggal
                                                ->format(
                                                    'd/m/Y'
                                                )
                                        }}

                                    </div>

                                </td>


                                {{-- SESI --}}

                                <td>

                                    @if(
                                        $absensi
                                            ->sesiAbsensi
                                            ->jenis
                                        === 'pagi'
                                    )

                                        <span
                                            class="
                                                badge
                                                bg-yellow-lt
                                            "
                                        >

                                            <i
                                                class="
                                                    ti
                                                    ti-sun
                                                    me-1
                                                "
                                            ></i>

                                            Pagi

                                        </span>

                                    @else

                                        <span
                                            class="
                                                badge
                                                bg-blue-lt
                                            "
                                        >

                                            <i
                                                class="
                                                    ti
                                                    ti-sunset
                                                    me-1
                                                "
                                            ></i>

                                            Siang

                                        </span>

                                    @endif

                                </td>


                                {{-- WAKTU --}}

                                <td>

                                    {{
                                        (
                                            $absensi
                                                ->waktu_absen

                                            ??

                                            $absensi
                                                ->created_at
                                        )
                                        ?->format(
                                            'H:i:s'
                                        )

                                        ?? '-'
                                    }}

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @php

                                        $badgeStatus =
                                            match(
                                                $absensi
                                                    ->status
                                            ) {

                                                'hadir'
                                                    => 'success',

                                                'terlambat'
                                                    => 'warning',

                                                'izin'
                                                    => 'blue',

                                                'sakit'
                                                    => 'azure',

                                                'alpa'
                                                    => 'danger',

                                                default
                                                    => 'secondary',

                                            };

                                    @endphp


                                    <span
                                        class="
                                            badge
                                            bg-{{
                                                $badgeStatus
                                            }}-lt
                                        "
                                    >

                                        {{
                                            ucfirst(
                                                $absensi
                                                    ->status
                                            )
                                        }}

                                    </span>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="
                                        text-center
                                        text-secondary
                                        py-5
                                    "
                                >

                                    Belum ada riwayat
                                    absensi.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- FOOTER --}}

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >

                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script
    src="https://unpkg.com/html5-qrcode"
></script>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const startButton =
            document.getElementById(
                'start-camera'
            );

        const stopButton =
            document.getElementById(
                'stop-camera'
            );

        const startContainer =
            document.getElementById(
                'scanner-start'
            );

        const scannerContainer =
            document.getElementById(
                'scanner-container'
            );

        const resultContainer =
            document.getElementById(
                'scan-result'
            );

        const alertElement =
            document.getElementById(
                'scan-alert'
            );

        const messageElement =
            document.getElementById(
                'scan-message'
            );

        const detailElement =
            document.getElementById(
                'scan-detail'
            );


        const scanUrl =
            @json(
                route(
                    'absensi.siswa.scan'
                )
            );


        let scanner = null;

        let processing = false;


        async function startScanner() {

            resultContainer
                .classList
                .add('d-none');


            scanner =
                new Html5Qrcode(
                    'qr-reader'
                );


            try {

                await scanner.start(

                    {
                        facingMode:
                            'environment'
                    },

                    {
                        fps: 10,

                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },

                    onScanSuccess,

                    function () {
                        // Abaikan kegagalan frame.
                    }

                );


                startContainer
                    .classList
                    .add('d-none');


                scannerContainer
                    .classList
                    .remove('d-none');


            } catch (error) {

                showResult(
                    false,
                    'Kamera tidak dapat dibuka.',
                    error
                );

            }

        }


        async function stopScanner() {

            if (!scanner) {
                return;
            }


            try {

                if (scanner.isScanning) {

                    await scanner.stop();

                }


                scanner.clear();


            } catch (error) {

                console.error(error);

            }


            scanner = null;


            scannerContainer
                .classList
                .add('d-none');


            startContainer
                .classList
                .remove('d-none');

        }


        async function onScanSuccess(
            decodedText
        ) {

            if (processing) {
                return;
            }


            processing = true;


            /*
             * Kamera dihentikan agar QR yang sama
             * tidak dikirim berkali-kali.
             */
            await stopScanner();


            try {

                const response =
                    await fetch(
                        scanUrl,
                        {
                            method: 'POST',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .getAttribute(
                                            'content'
                                        )
                            },

                            body: JSON.stringify({
                                token:
                                    decodedText
                            })
                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message
                        ?? 'Absensi gagal.'
                    );

                }


                showResult(
                    true,

                    data.message,

                    'Absensi '
                    + data.jenis
                    + ' • '
                    + data.waktu
                );


                /*
                 * Refresh setelah beberapa detik
                 * agar riwayat terbaru tampil.
                 */
                setTimeout(
                    function () {

                        window.location.reload();

                    },
                    2500
                );


            } catch (error) {

                showResult(
                    false,

                    error.message,

                    'Silakan coba kembali.'
                );


                processing = false;

            }

        }


        function showResult(
            success,
            message,
            detail
        ) {

            resultContainer
                .classList
                .remove('d-none');


            alertElement.className =
                success
                    ? 'alert alert-success'
                    : 'alert alert-danger';


            messageElement.textContent =
                message;


            detailElement.textContent =
                detail ?? '';

        }


        startButton.addEventListener(
            'click',
            startScanner
        );


        stopButton.addEventListener(
            'click',
            stopScanner
        );

    }
);

</script>

@endpush