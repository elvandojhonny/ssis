@extends('layouts.app')

@section('title', 'Absensi Saya')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <div class="page-pretitle">
            Absensi
            </div>

            <h2 class="page-title">
                Kehadiran Saya
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
                    Rekap Kehadiran Semester
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
                    Total Data Kehadiran
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


            {{-- ================================================= --}}
            {{-- HEADER --}}
            {{-- ================================================= --}}

            <div class="modal-header">

                <div>

                    <div class="text-secondary small mb-1">
                        Riwayat Absensi
                    </div>

                    <h2 class="modal-title mb-1">
                        Detail Kehadiran
                    </h2>

                    <div class="text-secondary">

                        {{ $user->name }}

                        <span class="mx-1">
                            •
                        </span>

                        {{ $user->siswa->kelas->nama }}

                    </div>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>

            </div>


            {{-- ================================================= --}}
            {{-- BODY --}}
            {{-- ================================================= --}}

            <div class="modal-body bg-light">


                {{-- ================================================= --}}
                {{-- DESKTOP --}}
                {{-- ================================================= --}}

                <div class="d-none d-md-block">

                    @forelse(
                        $riwayat->groupBy(function ($absensi) {

                            return $absensi
                                ->sesiAbsensi
                                ->tanggal
                                ->format('Y-m-d');

                        })
                        as $tanggal => $absensiTanggal
                    )

                        @php

                            $tanggalObj =
                                $absensiTanggal
                                    ->first()
                                    ->sesiAbsensi
                                    ->tanggal;

                        @endphp


                        {{-- ================================================= --}}
                        {{-- PEMBATAS TANGGAL --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">


                            {{-- HEADER TANGGAL --}}

                            <div
                                class="
                                    d-flex
                                    align-items-center
                                    gap-3
                                    mb-3
                                "
                            >

                                <div
                                    class="
                                        avatar
                                        avatar-md
                                        bg-primary-lt
                                        flex-shrink-0
                                    "
                                >

                                    <i
                                        class="
                                            ti
                                            ti-calendar
                                        "
                                    ></i>

                                </div>


                                <div>

                                    <div
                                        class="
                                            text-secondary
                                            small
                                        "
                                    >
                                        Tanggal Kehadiran
                                    </div>

                                    <div
                                        class="
                                            fw-bold
                                            fs-3
                                        "
                                    >

                                        {{
                                            $tanggalObj->translatedFormat(
                                                'l, d F Y'
                                            )
                                        }}

                                    </div>

                                </div>

                            </div>


                            {{-- GARIS PEMBATAS --}}

                            <div
                                class="
                                    border-top
                                    mb-3
                                "
                            ></div>


                            {{-- ================================================= --}}
                            {{-- SESI PADA TANGGAL TERSEBUT --}}
                            {{-- ================================================= --}}

                            <div class="row g-3">


                                @foreach(
                                    $absensiTanggal
                                    ->sortBy(function ($absensi) {

                                        return
                                            $absensi
                                                ->sesiAbsensi
                                                ->jenis
                                            === 'pagi'
                                            ? 1
                                            : 2;

                                    })
                                    as $absensi
                                )

                                    @php

                                        $badgeStatus =
                                            match(
                                                $absensi->status
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


                                        $waktuAbsen =
                                            $absensi->waktu_absen
                                            ??
                                            $absensi->created_at;


                                        $isPagi =
                                            $absensi
                                                ->sesiAbsensi
                                                ->jenis
                                            === 'pagi';

                                    @endphp


                                    <div class="col-12 col-lg-6">


                                        {{-- ================================================= --}}
                                        {{-- CARD SESI --}}
                                        {{-- ================================================= --}}

                                        <div
                                            class="
                                                card
                                                h-100
                                                border
                                            "
                                        >

                                            {{-- HEADER SESI --}}

                                            <div
                                                class="
                                                    card-header
                                                    {{
                                                        $isPagi
                                                            ? 'bg-yellow-lt'
                                                            : 'bg-blue-lt'
                                                    }}
                                                "
                                            >

                                                <div
                                                    class="
                                                        d-flex
                                                        align-items-center
                                                        justify-content-between
                                                        w-100
                                                    "
                                                >

                                                    <div
                                                        class="
                                                            d-flex
                                                            align-items-center
                                                            gap-2
                                                        "
                                                    >

                                                        <span
                                                            class="
                                                                avatar
                                                                avatar-sm
                                                                {{
                                                                    $isPagi
                                                                        ? 'bg-yellow'
                                                                        : 'bg-blue'
                                                                }}
                                                            "
                                                        >

                                                            <i
                                                                class="
                                                                    ti
                                                                    {{
                                                                        $isPagi
                                                                            ? 'ti-sun'
                                                                            : 'ti-sunset'
                                                                    }}
                                                                    text-white
                                                                "
                                                            ></i>

                                                        </span>


                                                        <div>

                                                            <div
                                                                class="
                                                                    fw-bold
                                                                    fs-3
                                                                "
                                                            >

                                                                Sesi
                                                                {{
                                                                    $isPagi
                                                                        ? 'Pagi'
                                                                        : 'Siang'
                                                                }}

                                                            </div>

                                                            <div
                                                                class="
                                                                    text-secondary
                                                                    small
                                                                "
                                                            >

                                                                {{
                                                                    $isPagi
                                                                        ? 'Jam masuk pagi'
                                                                        : 'Jam masuk siang'
                                                                }}

                                                            </div>

                                                        </div>

                                                    </div>


                                                    {{-- STATUS --}}

                                                    <span
                                                        class="
                                                            badge
                                                            bg-{{
                                                                $badgeStatus
                                                            }}-lt
                                                        "
                                                    >

                                                        @if(
                                                            $absensi->status
                                                            === 'hadir'
                                                        )

                                                            <i
                                                                class="
                                                                    ti
                                                                    ti-check
                                                                    me-1
                                                                "
                                                            ></i>

                                                        @elseif(
                                                            $absensi->status
                                                            === 'terlambat'
                                                        )

                                                            <i
                                                                class="
                                                                    ti
                                                                    ti-clock
                                                                    me-1
                                                                "
                                                            ></i>

                                                        @elseif(
                                                            $absensi->status
                                                            === 'izin'
                                                        )

                                                            <i
                                                                class="
                                                                    ti
                                                                    ti-file-description
                                                                    me-1
                                                                "
                                                            ></i>

                                                        @elseif(
                                                            $absensi->status
                                                            === 'sakit'
                                                        )

                                                            <i
                                                                class="
                                                                    ti
                                                                    ti-medical-cross
                                                                    me-1
                                                                "
                                                            ></i>

                                                        @elseif(
                                                            $absensi->status
                                                            === 'alpa'
                                                        )

                                                            <i
                                                                class="
                                                                    ti
                                                                    ti-x
                                                                    me-1
                                                                "
                                                            ></i>

                                                        @endif

                                                        {{
                                                            ucfirst(
                                                                $absensi->status
                                                            )
                                                        }}

                                                    </span>

                                                </div>

                                            </div>


                                            {{-- DETAIL SESI --}}

                                            <div class="card-body">


                                                <div
                                                    class="
                                                        row
                                                        g-3
                                                    "
                                                >

                                                    {{-- WAKTU --}}

                                                    <div
                                                        class="
                                                            col-6
                                                        "
                                                    >

                                                        <div
                                                            class="
                                                                text-secondary
                                                                small
                                                                mb-1
                                                            "
                                                        >
                                                            Waktu Absensi
                                                        </div>

                                                        <div
                                                            class="
                                                                fw-bold
                                                                fs-2
                                                            "
                                                        >

                                                            {{
                                                                $waktuAbsen
                                                                    ?->format(
                                                                        'H:i:s'
                                                                    )
                                                                ?? '-'
                                                            }}

                                                        </div>

                                                    </div>


                                                    {{-- STATUS --}}

                                                    <div
                                                        class="
                                                            col-6
                                                        "
                                                    >

                                                        <div
                                                            class="
                                                                text-secondary
                                                                small
                                                                mb-1
                                                            "
                                                        >
                                                            Status Kehadiran
                                                        </div>

                                                        <div
                                                            class="
                                                                fw-bold
                                                            "
                                                        >

                                                            {{
                                                                ucfirst(
                                                                    $absensi->status
                                                                )
                                                            }}

                                                        </div>

                                                    </div>

                                                </div>


                                                {{-- INFORMASI TAMBAHAN --}}

                                                <div
                                                    class="
                                                        mt-3
                                                        pt-3
                                                        border-top
                                                    "
                                                >

                                                    <div
                                                        class="
                                                            d-flex
                                                            align-items-center
                                                            gap-2
                                                            text-secondary
                                                            small
                                                        "
                                                    >

                                                        <i
                                                            class="
                                                                ti
                                                                ti-info-circle
                                                            "
                                                        ></i>

                                                        <span>

                                                            Absensi
                                                            {{
                                                                $isPagi
                                                                    ? 'sesi pagi'
                                                                    : 'sesi siang'
                                                                }}
                                                            pada tanggal
                                                            {{
                                                                $tanggalObj
                                                                    ->format(
                                                                        'd/m/Y'
                                                                    )
                                                            }}

                                                        </span>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @empty


                        {{-- ================================================= --}}
                        {{-- TIDAK ADA DATA --}}
                        {{-- ================================================= --}}

                        <div
                            class="
                                text-center
                                text-secondary
                                py-5
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-calendar-off
                                    fs-1
                                    d-block
                                    mb-3
                                "
                            ></i>

                            <div class="fw-bold mb-1">
                                Belum Ada Riwayat
                            </div>

                            <div>
                                Belum ada data kehadiran
                                yang tercatat.
                            </div>

                        </div>

                    @endforelse

                </div>



                {{-- ================================================= --}}
                {{-- MOBILE --}}
                {{-- ================================================= --}}

                <div class="d-md-none">

                    @forelse(
                        $riwayat->groupBy(function ($absensi) {

                            return $absensi
                                ->sesiAbsensi
                                ->tanggal
                                ->format('Y-m-d');

                        })
                        as $tanggal => $absensiTanggal
                    )

                        @php

                            $tanggalObj =
                                $absensiTanggal
                                    ->first()
                                    ->sesiAbsensi
                                    ->tanggal;

                        @endphp


                        {{-- ================================================= --}}
                        {{-- PEMBATAS TANGGAL MOBILE --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">


                            {{-- TANGGAL --}}

                            <div
                                class="
                                    d-flex
                                    align-items-center
                                    gap-2
                                    mb-3
                                "
                            >

                                <span
                                    class="
                                        avatar
                                        avatar-sm
                                        bg-primary-lt
                                    "
                                >

                                    <i class="ti ti-calendar"></i>

                                </span>


                                <div>

                                    <div
                                        class="
                                            text-secondary
                                            small
                                        "
                                    >
                                        Tanggal
                                    </div>

                                    <div class="fw-bold">

                                        {{
                                            $tanggalObj->translatedFormat(
                                                'l, d F Y'
                                            )
                                        }}

                                    </div>

                                </div>

                            </div>


                            {{-- SESI --}}

                            @foreach(
                                $absensiTanggal
                                ->sortBy(function ($absensi) {

                                    return
                                        $absensi
                                            ->sesiAbsensi
                                            ->jenis
                                        === 'pagi'
                                        ? 1
                                        : 2;

                                })
                                as $absensi
                            )

                                @php

                                    $badgeStatus =
                                        match(
                                            $absensi->status
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


                                    $waktuAbsen =
                                        $absensi->waktu_absen
                                        ??
                                        $absensi->created_at;


                                    $isPagi =
                                        $absensi
                                            ->sesiAbsensi
                                            ->jenis
                                        === 'pagi';

                                @endphp


                                <div
                                    class="
                                        card
                                        mb-3
                                        border
                                    "
                                >

                                    {{-- SESI --}}

                                    <div
                                        class="
                                            card-header
                                            {{
                                                $isPagi
                                                    ? 'bg-yellow-lt'
                                                    : 'bg-blue-lt'
                                            }}
                                        "
                                    >

                                        <div
                                            class="
                                                d-flex
                                                align-items-center
                                                justify-content-between
                                                w-100
                                            "
                                        >

                                            <div
                                                class="
                                                    d-flex
                                                    align-items-center
                                                    gap-2
                                                "
                                            >

                                                <span
                                                    class="
                                                        avatar
                                                        avatar-sm
                                                        {{
                                                            $isPagi
                                                                ? 'bg-yellow'
                                                                : 'bg-blue'
                                                        }}
                                                    "
                                                >

                                                    <i
                                                        class="
                                                            ti
                                                            {{
                                                                $isPagi
                                                                    ? 'ti-sun'
                                                                    : 'ti-sunset'
                                                            }}
                                                            text-white
                                                        "
                                                    ></i>

                                                </span>


                                                <div>

                                                    <div class="fw-bold">

                                                        Sesi
                                                        {{
                                                            $isPagi
                                                                ? 'Pagi'
                                                                : 'Siang'
                                                        }}

                                                    </div>

                                                    <div
                                                        class="
                                                            text-secondary
                                                            small
                                                        "
                                                    >

                                                        {{
                                                            $isPagi
                                                                ? 'Jam masuk pagi'
                                                                : 'Jam masuk siang'
                                                        }}

                                                    </div>

                                                </div>

                                            </div>


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
                                                        $absensi->status
                                                    )
                                                }}

                                            </span>

                                        </div>

                                    </div>


                                    {{-- DETAIL --}}

                                    <div class="card-body">

                                        <div
                                            class="
                                                text-secondary
                                                small
                                                mb-1
                                            "
                                        >
                                            Waktu Absensi
                                        </div>

                                        <div
                                            class="
                                                fw-bold
                                                fs-2
                                            "
                                        >

                                            {{
                                                $waktuAbsen
                                                    ?->format(
                                                        'H:i:s'
                                                    )
                                                ?? '-'
                                            }}

                                        </div>

                                    </div>

                                </div>

                            @endforeach


                            {{-- PEMBATAS ANTAR TANGGAL --}}

                            <div
                                class="
                                    border-bottom
                                    mt-4
                                "
                            ></div>

                        </div>

                    @empty

                        <div
                            class="
                                text-center
                                text-secondary
                                py-5
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-calendar-off
                                    fs-1
                                    d-block
                                    mb-3
                                "
                            ></i>

                            <div class="fw-bold">
                                Belum Ada Riwayat
                            </div>

                            <div class="small mt-1">
                                Belum ada data kehadiran.
                            </div>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- FOOTER --}}
            {{-- ================================================= --}}

            <div class="modal-footer">

                <div
                    class="
                        w-100
                        d-flex
                        justify-content-between
                        align-items-center
                        gap-3
                    "
                >

                    <div class="text-secondary small">

                        Total:
                        <strong>
                            {{ $riwayat->count() }}
                        </strong>
                        data absensi

                    </div>


                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        <i class="ti ti-x me-1"></i>

                        Tutup

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection