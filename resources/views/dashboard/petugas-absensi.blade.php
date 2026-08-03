@extends('layouts.app')

@section('title', 'Dashboard Petugas Absensi')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="dashboard-header mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <span class="avatar avatar-sm bg-blue-lt text-blue">
                    <i class="ti ti-clipboard-check"></i>
                </span>

                <h2 class="fw-bold mb-0">
                    Dashboard Absensi
                </h2>

            </div>

            <div class="text-secondary">
                Kelola absensi siswa hari ini.
            </div>

        </div>


        <div class="dashboard-date">

            <i class="ti ti-calendar me-1"></i>

            {{ now()->translatedFormat('d F Y') }}

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- RINGKASAN --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- TOTAL SISWA --}}

        <div class="col-6 col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between gap-3">

                        <div>

                            <div class="text-secondary small mb-1">
                                Total Siswa
                            </div>

                            <div class="h2 fw-bold mb-0">
                                {{ $totalSiswa }}
                            </div>

                        </div>

                        <span class="avatar bg-blue-lt text-blue">
                            <i class="ti ti-users"></i>
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- SESI DIBUKA --}}

        <div class="col-6 col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between gap-3">

                        <div>

                            <div class="text-secondary small mb-1">
                                Sesi Hari Ini
                            </div>

                            <div class="h2 fw-bold mb-0">
                                {{ $sesiHariIni->count() }}
                                <span class="fs-5 text-secondary">
                                    / 2
                                </span>
                            </div>

                        </div>

                        <span class="avatar bg-cyan-lt text-cyan">
                            <i class="ti ti-calendar-check"></i>
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- STATUS HARI INI --}}

        <div class="col-12 col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between gap-3">

                        <div>

                            <div class="text-secondary small mb-2">
                                Status Hari Ini
                            </div>

                            @if($sesiPagi && $sesiSiang)

                                <span class="badge bg-green-lt text-green">

                                    <i class="ti ti-circle-check me-1"></i>

                                    Lengkap

                                </span>

                            @elseif($sesiPagi || $sesiSiang)

                                <span class="badge bg-yellow-lt text-yellow">

                                    <i class="ti ti-clock me-1"></i>

                                    1 Sesi Dibuka

                                </span>

                            @else

                                <span class="badge bg-secondary-lt text-secondary">

                                    <i class="ti ti-clock-off me-1"></i>

                                    Belum Ada Sesi

                                </span>

                            @endif

                        </div>

                        <span class="avatar bg-purple-lt text-purple">
                            <i class="ti ti-activity"></i>
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ABSENSI HARI INI --}}
    {{-- ========================================================= --}}

    <div class="mb-3">

        <h3 class="fw-bold mb-1">
            Absensi Hari Ini
        </h3>

        <div class="text-secondary small">
            Absensi pagi dan siang hanya dapat dibuka satu kali setiap hari.
        </div>

    </div>


    <div class="row g-4 mb-4">

        {{-- ===================================================== --}}
        {{-- ABSENSI PAGI --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-lg-6">

            <div class="card border-0 shadow-sm h-100 absensi-session-card">

                <div class="card-body">

                    <div class="d-flex align-items-start justify-content-between gap-3 mb-4">

                        <div class="d-flex align-items-center gap-3">

                            <span class="avatar avatar-lg bg-yellow-lt text-yellow">

                                <i class="ti ti-sun fs-2"></i>

                            </span>

                            <div>

                                <div class="text-secondary small">
                                    Sesi Absensi
                                </div>

                                <h3 class="fw-bold mb-0">
                                    Absensi Pagi
                                </h3>

                            </div>

                        </div>


                        @if($sesiPagi)

                            @if($sesiPagi->status === 'aktif')

                                <span class="badge bg-green-lt text-green">

                                    <span class="status-dot status-dot-animated bg-green me-1"></span>

                                    Aktif

                                </span>

                            @else

                                <span class="badge bg-secondary-lt text-secondary">

                                    <i class="ti ti-circle-check me-1"></i>

                                    Selesai

                                </span>

                            @endif

                        @else

                            <span class="badge bg-secondary-lt text-secondary">
                                Belum Dibuka
                            </span>

                        @endif

                    </div>


                    {{-- ========================================= --}}
                    {{-- SUDAH ADA SESI PAGI --}}
                    {{-- ========================================= --}}

                    @if($sesiPagi)

                        <div class="session-information mb-4">

                            <div class="session-information-item">

                                <span class="text-secondary">
                                    Waktu
                                </span>

                                <span class="fw-semibold">

                                    {{ \Carbon\Carbon::parse($sesiPagi->waktu_mulai)->format('H:i') }}

                                    -

                                    {{ \Carbon\Carbon::parse($sesiPagi->waktu_selesai)->format('H:i') }}

                                </span>

                            </div>


                            <div class="session-information-item">

                                <span class="text-secondary">
                                    Jenis
                                </span>

                                <span class="fw-semibold">
                                    Pagi
                                </span>

                            </div>


                            <div class="session-information-item">

                                <span class="text-secondary">
                                    Dibuka Oleh
                                </span>

                                <span class="fw-semibold">
                                    {{ $sesiPagi->pembuka?->name ?? '-' }}
                                </span>

                            </div>

                        </div>


                        <a
                            href="{{ route('absensi.sesi.show', $sesiPagi) }}"
                            class="btn btn-primary w-100"
                        >

                            <i class="ti ti-eye me-1"></i>

                            Lihat Sesi

                        </a>


                    {{-- ========================================= --}}
                    {{-- BELUM ADA SESI PAGI --}}
                    {{-- ========================================= --}}

                    @else

                        <div class="session-empty mb-4">

                            <div class="text-secondary">

                                Absensi pagi belum dibuka hari ini.

                            </div>

                        </div>


                        <a
                            href="{{ route('absensi.sesi.create', ['jenis' => 'pagi']) }}"
                            class="btn btn-primary w-100"
                        >

                            <i class="ti ti-plus me-1"></i>

                            Buka Absensi Pagi

                        </a>

                    @endif

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ABSENSI SIANG --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-lg-6">

            <div class="card border-0 shadow-sm h-100 absensi-session-card">

                <div class="card-body">

                    <div class="d-flex align-items-start justify-content-between gap-3 mb-4">

                        <div class="d-flex align-items-center gap-3">

                            <span class="avatar avatar-lg bg-blue-lt text-blue">

                                <i class="ti ti-sun-high fs-2"></i>

                            </span>

                            <div>

                                <div class="text-secondary small">
                                    Sesi Absensi
                                </div>

                                <h3 class="fw-bold mb-0">
                                    Absensi Siang
                                </h3>

                            </div>

                        </div>


                        @if($sesiSiang)

                            @if($sesiSiang->status === 'aktif')

                                <span class="badge bg-green-lt text-green">

                                    <span class="status-dot status-dot-animated bg-green me-1"></span>

                                    Aktif

                                </span>

                            @else

                                <span class="badge bg-secondary-lt text-secondary">

                                    <i class="ti ti-circle-check me-1"></i>

                                    Selesai

                                </span>

                            @endif

                        @else

                            <span class="badge bg-secondary-lt text-secondary">
                                Belum Dibuka
                            </span>

                        @endif

                    </div>


                    {{-- ========================================= --}}
                    {{-- SUDAH ADA SESI SIANG --}}
                    {{-- ========================================= --}}

                    @if($sesiSiang)

                        <div class="session-information mb-4">

                            <div class="session-information-item">

                                <span class="text-secondary">
                                    Waktu
                                </span>

                                <span class="fw-semibold">

                                    {{ \Carbon\Carbon::parse($sesiSiang->waktu_mulai)->format('H:i') }}

                                    -

                                    {{ \Carbon\Carbon::parse($sesiSiang->waktu_selesai)->format('H:i') }}

                                </span>

                            </div>


                            <div class="session-information-item">

                                <span class="text-secondary">
                                    Jenis
                                </span>

                                <span class="fw-semibold">
                                    Siang
                                </span>

                            </div>


                            <div class="session-information-item">

                                <span class="text-secondary">
                                    Dibuka Oleh
                                </span>

                                <span class="fw-semibold">
                                    {{ $sesiSiang->pembuka?->name ?? '-' }}
                                </span>

                            </div>

                        </div>


                        <a
                            href="{{ route('absensi.sesi.show', $sesiSiang) }}"
                            class="btn btn-primary w-100"
                        >

                            <i class="ti ti-eye me-1"></i>

                            Lihat Sesi

                        </a>


                    {{-- ========================================= --}}
                    {{-- BELUM ADA SESI SIANG --}}
                    {{-- ========================================= --}}

                    @else

                        <div class="session-empty mb-4">

                            <div class="text-secondary">

                                Absensi siang belum dibuka hari ini.

                            </div>

                        </div>


                        <a
                            href="{{ route('absensi.sesi.create', ['jenis' => 'siang']) }}"
                            class="btn btn-primary w-100"
                        >

                            <i class="ti ti-plus me-1"></i>

                            Buka Absensi Siang

                        </a>

                    @endif

                </div>

            </div>

        </div>

    </div>

        {{-- ========================================================= --}}
    {{-- SESI HARI INI --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}

        <div class="card-header">

            <div
                class="
                    d-flex
                    align-items-center
                    justify-content-between
                    gap-3
                    w-100
                "
            >

                <div class="d-flex align-items-center gap-2">

                    <span
                        class="
                            avatar
                            avatar-sm
                            bg-blue-lt
                            text-blue
                        "
                    >

                        <i class="ti ti-list-details"></i>

                    </span>

                    <div>

                        <h3 class="card-title mb-0">
                            Sesi Hari Ini
                        </h3>

                        <div class="text-secondary small mt-1">
                            Absensi pagi dan siang.
                        </div>

                    </div>

                </div>


                <span class="badge bg-blue-lt text-blue">

                    {{ $sesiHariIni->count() }}

                    Sesi

                </span>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DESKTOP --}}
        {{-- ===================================================== --}}

        <div class="d-none d-md-block">

            <div class="table-responsive">

                <table
                    class="
                        table
                        table-vcenter
                        card-table
                        mb-0
                    "
                >

                    <thead>

                        <tr>

                            <th>
                                Sesi
                            </th>

                            <th>
                                Waktu
                            </th>

                            <th>
                                Dibuka Oleh
                            </th>

                            <th>
                                Status
                            </th>

                            <th
                                class="text-end"
                                style="width: 120px;"
                            >
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($sesiHariIni as $sesi)

                            <tr>

                                {{-- ================================= --}}
                                {{-- JENIS --}}
                                {{-- ================================= --}}

                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        @if($sesi->jenis === 'pagi')

                                            <span
                                                class="
                                                    avatar
                                                    bg-yellow-lt
                                                    text-yellow
                                                "
                                            >

                                                <i class="ti ti-sun"></i>

                                            </span>

                                        @else

                                            <span
                                                class="
                                                    avatar
                                                    bg-blue-lt
                                                    text-blue
                                                "
                                            >

                                                <i class="ti ti-sun-high"></i>

                                            </span>

                                        @endif


                                        <div>

                                            <div class="fw-semibold">

                                                Absensi
                                                {{ ucfirst($sesi->jenis) }}

                                            </div>

                                            <div
                                                class="
                                                    text-secondary
                                                    small
                                                    mt-1
                                                "
                                            >

                                                {{
                                                    $sesi
                                                        ->tanggal
                                                        ?->translatedFormat(
                                                            'd M Y'
                                                        )
                                                    ?? today()
                                                        ->translatedFormat(
                                                            'd M Y'
                                                        )
                                                }}

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- ================================= --}}
                                {{-- WAKTU --}}
                                {{-- ================================= --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{
                                            \Carbon\Carbon::parse(
                                                $sesi->waktu_mulai
                                            )->format('H:i')
                                        }}

                                        -

                                        {{
                                            \Carbon\Carbon::parse(
                                                $sesi->waktu_selesai
                                            )->format('H:i')
                                        }}

                                    </div>


                                    @if($sesi->batas_terlambat)

                                        <div
                                            class="
                                                text-secondary
                                                small
                                                mt-1
                                            "
                                        >

                                            Batas terlambat

                                            {{
                                                \Carbon\Carbon::parse(
                                                    $sesi->batas_terlambat
                                                )->format('H:i')
                                            }}

                                        </div>

                                    @endif

                                </td>


                                {{-- ================================= --}}
                                {{-- PEMBUKA --}}
                                {{-- ================================= --}}

                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        <span
                                            class="
                                                avatar
                                                avatar-xs
                                                bg-secondary-lt
                                                text-secondary
                                            "
                                        >

                                            <i class="ti ti-user"></i>

                                        </span>

                                        <span>

                                            {{
                                                $sesi
                                                    ->pembuka
                                                    ?->name
                                                ?? '-'
                                            }}

                                        </span>

                                    </div>

                                </td>


                                {{-- ================================= --}}
                                {{-- STATUS --}}
                                {{-- ================================= --}}

                                <td>

                                    @if($sesi->status === 'aktif')

                                        <span
                                            class="
                                                badge
                                                bg-green-lt
                                                text-green
                                            "
                                        >

                                            <span
                                                class="
                                                    status-dot
                                                    status-dot-animated
                                                    bg-green
                                                    me-1
                                                "
                                            ></span>

                                            Aktif

                                        </span>


                                    @else

                                        <span
                                            class="
                                                badge
                                                bg-secondary-lt
                                                text-secondary
                                            "
                                        >

                                            <i
                                                class="
                                                    ti
                                                    ti-circle-check
                                                    me-1
                                                "
                                            ></i>

                                            Selesai

                                        </span>

                                    @endif

                                </td>


                                {{-- ================================= --}}
                                {{-- AKSI --}}
                                {{-- ================================= --}}

                                <td class="text-end">

                                    <a
                                        href="{{
                                            route(
                                                'absensi.sesi.show',
                                                $sesi
                                            )
                                        }}"
                                        class="
                                            btn
                                            btn-outline-primary
                                            btn-sm
                                        "
                                        title="Lihat sesi"
                                    >

                                        <i class="ti ti-eye me-1"></i>

                                        Lihat

                                    </a>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="
                                        text-center
                                        py-5
                                    "
                                >

                                    <span
                                        class="
                                            avatar
                                            avatar-lg
                                            bg-secondary-lt
                                            text-secondary
                                            mb-3
                                        "
                                    >

                                        <i class="ti ti-calendar-off"></i>

                                    </span>

                                    <div class="fw-semibold">
                                        Belum ada sesi
                                    </div>

                                    <div
                                        class="
                                            text-secondary
                                            small
                                            mt-1
                                        "
                                    >
                                        Absensi belum dibuka hari ini.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- MOBILE --}}
        {{-- ===================================================== --}}

        <div class="d-md-none">

            @forelse($sesiHariIni as $sesi)

                <div class="session-mobile-item">

                    {{-- ========================================= --}}
                    {{-- HEADER --}}
                    {{-- ========================================= --}}

                    <div
                        class="
                            d-flex
                            align-items-start
                            justify-content-between
                            gap-3
                            mb-3
                        "
                    >

                        <div class="d-flex align-items-center gap-3">

                            @if($sesi->jenis === 'pagi')

                                <span
                                    class="
                                        avatar
                                        bg-yellow-lt
                                        text-yellow
                                        flex-shrink-0
                                    "
                                >

                                    <i class="ti ti-sun"></i>

                                </span>

                            @else

                                <span
                                    class="
                                        avatar
                                        bg-blue-lt
                                        text-blue
                                        flex-shrink-0
                                    "
                                >

                                    <i class="ti ti-sun-high"></i>

                                </span>

                            @endif


                            <div>

                                <div class="fw-semibold">

                                    Absensi
                                    {{ ucfirst($sesi->jenis) }}

                                </div>

                                <div
                                    class="
                                        text-secondary
                                        small
                                        mt-1
                                    "
                                >

                                    {{
                                        $sesi
                                            ->tanggal
                                            ?->translatedFormat(
                                                'd M Y'
                                            )
                                        ?? today()
                                            ->translatedFormat(
                                                'd M Y'
                                            )
                                    }}

                                </div>

                            </div>

                        </div>


                        @if($sesi->status === 'aktif')

                            <span
                                class="
                                    badge
                                    bg-green-lt
                                    text-green
                                    flex-shrink-0
                                "
                            >

                                <span
                                    class="
                                        status-dot
                                        status-dot-animated
                                        bg-green
                                        me-1
                                    "
                                ></span>

                                Aktif

                            </span>

                        @else

                            <span
                                class="
                                    badge
                                    bg-secondary-lt
                                    text-secondary
                                    flex-shrink-0
                                "
                            >

                                Selesai

                            </span>

                        @endif

                    </div>


                    {{-- ========================================= --}}
                    {{-- INFORMASI --}}
                    {{-- ========================================= --}}

                    <div class="session-mobile-info mb-3">

                        <div>

                            <div
                                class="
                                    text-secondary
                                    small
                                    mb-1
                                "
                            >
                                Waktu
                            </div>

                            <div class="fw-semibold">

                                {{
                                    \Carbon\Carbon::parse(
                                        $sesi->waktu_mulai
                                    )->format('H:i')
                                }}

                                -

                                {{
                                    \Carbon\Carbon::parse(
                                        $sesi->waktu_selesai
                                    )->format('H:i')
                                }}

                            </div>

                        </div>


                        <div>

                            <div
                                class="
                                    text-secondary
                                    small
                                    mb-1
                                "
                            >
                                Batas Terlambat
                            </div>

                            <div class="fw-semibold">

                                @if($sesi->batas_terlambat)

                                    {{
                                        \Carbon\Carbon::parse(
                                            $sesi->batas_terlambat
                                        )->format('H:i')
                                    }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- ========================================= --}}
                    {{-- PEMBUKA --}}
                    {{-- ========================================= --}}

                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-between
                            gap-3
                            py-3
                            border-top
                        "
                    >

                        <span class="text-secondary small">
                            Dibuka Oleh
                        </span>

                        <span class="fw-semibold text-end">

                            {{
                                $sesi
                                    ->pembuka
                                    ?->name
                                ?? '-'
                            }}

                        </span>

                    </div>


                    {{-- ========================================= --}}
                    {{-- AKSI --}}
                    {{-- ========================================= --}}

                    <a
                        href="{{
                            route(
                                'absensi.sesi.show',
                                $sesi
                            )
                        }}"
                        class="btn btn-outline-primary w-100"
                    >

                        <i class="ti ti-eye me-1"></i>

                        Lihat Sesi

                    </a>

                </div>


            @empty

                <div
                    class="
                        text-center
                        py-5
                        px-3
                    "
                >

                    <span
                        class="
                            avatar
                            avatar-lg
                            bg-secondary-lt
                            text-secondary
                            mb-3
                        "
                    >

                        <i class="ti ti-calendar-off"></i>

                    </span>

                    <div class="fw-semibold">
                        Belum ada sesi
                    </div>

                    <div
                        class="
                            text-secondary
                            small
                            mt-1
                        "
                    >
                        Absensi belum dibuka hari ini.
                    </div>

                </div>

            @endforelse

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- AKSES ABSENSI --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        <div class="col-12 col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header">

                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="
                                avatar
                                avatar-sm
                                bg-cyan-lt
                                text-cyan
                            "
                        >

                            <i class="ti ti-clipboard-data"></i>

                        </span>

                        <h3 class="card-title mb-0">
                            Kelola Absensi
                        </h3>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        {{-- ===================================== --}}
                        {{-- DAFTAR SESI --}}
                        {{-- ===================================== --}}

                        <div class="col-12 col-md-6">

                            <a
                                href="{{
                                    route(
                                        'absensi.sesi.index'
                                    )
                                }}"
                                class="
                                    dashboard-action
                                    text-decoration-none
                                "
                            >

                                <span
                                    class="
                                        avatar
                                        bg-blue-lt
                                        text-blue
                                    "
                                >

                                    <i class="ti ti-list"></i>

                                </span>


                                <div class="flex-fill">

                                    <div
                                        class="
                                            fw-semibold
                                            text-body
                                        "
                                    >
                                        Daftar Sesi
                                    </div>

                                    <div
                                        class="
                                            text-secondary
                                            small
                                            mt-1
                                        "
                                    >
                                        Lihat sesi absensi.
                                    </div>

                                </div>


                                <i
                                    class="
                                        ti
                                        ti-chevron-right
                                        text-secondary
                                    "
                                ></i>

                            </a>

                        </div>


                        {{-- ===================================== --}}
                        {{-- BUKA ABSENSI --}}
                        {{-- ===================================== --}}

                        <div class="col-12 col-md-6">

                            @if(!$sesiPagi || !$sesiSiang)

                                <a
                                    href="{{
                                        route(
                                            'absensi.sesi.create'
                                        )
                                    }}"
                                    class="
                                        dashboard-action
                                        text-decoration-none
                                    "
                                >

                                    <span
                                        class="
                                            avatar
                                            bg-green-lt
                                            text-green
                                        "
                                    >

                                        <i class="ti ti-plus"></i>

                                    </span>


                                    <div class="flex-fill">

                                        <div
                                            class="
                                                fw-semibold
                                                text-body
                                            "
                                        >
                                            Buka Absensi
                                        </div>

                                        <div
                                            class="
                                                text-secondary
                                                small
                                                mt-1
                                            "
                                        >
                                            Buat sesi hari ini.
                                        </div>

                                    </div>


                                    <i
                                        class="
                                            ti
                                            ti-chevron-right
                                            text-secondary
                                        "
                                    ></i>

                                </a>

                            @else

                                <div
                                    class="
                                        dashboard-action
                                        dashboard-action-disabled
                                    "
                                >

                                    <span
                                        class="
                                            avatar
                                            bg-green-lt
                                            text-green
                                        "
                                    >

                                        <i class="ti ti-check"></i>

                                    </span>


                                    <div class="flex-fill">

                                        <div class="fw-semibold">
                                            Absensi Lengkap
                                        </div>

                                        <div
                                            class="
                                                text-secondary
                                                small
                                                mt-1
                                            "
                                        >
                                            Pagi dan siang sudah dibuat.
                                        </div>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INFORMASI --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header">

                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="
                                avatar
                                avatar-sm
                                bg-yellow-lt
                                text-yellow
                            "
                        >

                            <i class="ti ti-info-circle"></i>

                        </span>

                        <h3 class="card-title mb-0">
                            Ketentuan
                        </h3>

                    </div>

                </div>


                <div class="card-body">

                    <div class="attendance-rule">

                        <span
                            class="
                                attendance-rule-number
                                bg-blue-lt
                                text-blue
                            "
                        >
                            1
                        </span>

                        <div>

                            <div class="fw-semibold">
                                Absensi Pagi
                            </div>

                            <div
                                class="
                                    text-secondary
                                    small
                                    mt-1
                                "
                            >
                                Maksimal satu sesi per hari.
                            </div>

                        </div>

                    </div>


                    <div class="attendance-rule">

                        <span
                            class="
                                attendance-rule-number
                                bg-cyan-lt
                                text-cyan
                            "
                        >
                            2
                        </span>

                        <div>

                            <div class="fw-semibold">
                                Absensi Siang
                            </div>

                            <div
                                class="
                                    text-secondary
                                    small
                                    mt-1
                                "
                            >
                                Maksimal satu sesi per hari.
                            </div>

                        </div>

                    </div>


                    <div class="attendance-rule mb-0">

                        <span
                            class="
                                attendance-rule-number
                                bg-green-lt
                                text-green
                            "
                        >
                            <i class="ti ti-check"></i>
                        </span>

                        <div>

                            <div class="fw-semibold">
                                Semua Tingkat
                            </div>

                            <div
                                class="
                                    text-secondary
                                    small
                                    mt-1
                                "
                            >
                                Satu sesi berlaku untuk seluruh siswa.
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

        {{-- ========================================================= --}}
    {{-- PENUTUP CONTAINER --}}
    {{-- ========================================================= --}}

</div>

@endsection


{{-- ============================================================= --}}
{{-- STYLE --}}
{{-- ============================================================= --}}

@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD HEADER
    |--------------------------------------------------------------------------
    */

    .dashboard-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }

    .dashboard-date {
        display: inline-flex;
        align-items: center;

        padding: .55rem .85rem;

        border: 1px solid var(--tblr-border-color);
        border-radius: 8px;

        color: var(--tblr-secondary);

        font-size: .875rem;
        font-weight: 500;

        white-space: nowrap;
    }


    /*
    |--------------------------------------------------------------------------
    | CARD
    |--------------------------------------------------------------------------
    */

    .card {
        border-radius: 12px;
    }

    .card-header {
        min-height: 64px;
        padding: 1rem 1.25rem;
    }

    .card-body {
        padding: 1.25rem;
    }


    /*
    |--------------------------------------------------------------------------
    | CARD SESI ABSENSI
    |--------------------------------------------------------------------------
    */

    .absensi-session-card {
        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }

    .absensi-session-card:hover {
        transform: translateY(-2px);
    }


    /*
    |--------------------------------------------------------------------------
    | INFORMASI SESI
    |--------------------------------------------------------------------------
    */

    .session-information {
        display: flex;
        flex-direction: column;
    }

    .session-information-item {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 1rem;

        padding: .8rem 0;

        border-bottom:
            1px solid
            var(--tblr-border-color);
    }

    .session-information-item:first-child {
        padding-top: 0;
    }

    .session-information-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .session-information-item > :last-child {
        text-align: right;
    }


    /*
    |--------------------------------------------------------------------------
    | SESI BELUM DIBUKA
    |--------------------------------------------------------------------------
    */

    .session-empty {
        display: flex;
        align-items: center;

        min-height: 112px;

        padding: 1rem;

        border:
            1px dashed
            var(--tblr-border-color);

        border-radius: 10px;

        background:
            var(--tblr-bg-surface-secondary);
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    .table > :not(caption) > * > * {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .table thead th {
        color: var(--tblr-secondary);

        font-size: .75rem;
        font-weight: 600;

        text-transform: uppercase;
        letter-spacing: .03em;

        white-space: nowrap;
    }


    /*
    |--------------------------------------------------------------------------
    | SESI MOBILE
    |--------------------------------------------------------------------------
    */

    .session-mobile-item {
        padding: 1rem;

        border-bottom:
            1px solid
            var(--tblr-border-color);
    }

    .session-mobile-item:last-child {
        border-bottom: 0;
    }

    .session-mobile-info {
        display: grid;
        grid-template-columns: 1fr 1fr;

        gap: 1rem;
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ACTION
    |--------------------------------------------------------------------------
    */

    .dashboard-action {
        display: flex;
        align-items: center;

        gap: 1rem;

        min-height: 82px;

        padding: 1rem;

        border:
            1px solid
            var(--tblr-border-color);

        border-radius: 10px;

        background:
            var(--tblr-bg-surface);

        transition:
            border-color .2s ease,
            background .2s ease,
            transform .2s ease;
    }

    .dashboard-action:hover {
        border-color:
            rgba(
                var(--tblr-primary-rgb),
                .35
            );

        background:
            var(--tblr-bg-surface-secondary);

        transform: translateY(-1px);
    }

    .dashboard-action-disabled {
        cursor: default;
        opacity: .85;
    }

    .dashboard-action-disabled:hover {
        border-color:
            var(--tblr-border-color);

        background:
            var(--tblr-bg-surface);

        transform: none;
    }


    /*
    |--------------------------------------------------------------------------
    | KETENTUAN
    |--------------------------------------------------------------------------
    */

    .attendance-rule {
        display: flex;
        align-items: flex-start;

        gap: .85rem;

        padding: 1rem 0;

        border-bottom:
            1px solid
            var(--tblr-border-color);
    }

    .attendance-rule:first-child {
        padding-top: 0;
    }

    .attendance-rule:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .attendance-rule-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        flex: 0 0 34px;

        width: 34px;
        height: 34px;

        border-radius: 8px;

        font-size: .875rem;
        font-weight: 700;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLET
    |--------------------------------------------------------------------------
    */

    @media (max-width: 991.98px) {

        .card-header {
            padding: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .absensi-session-card:hover {
            transform: none;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767.98px) {

        /*
        |--------------------------------------------------------------------------
        | CONTAINER
        |--------------------------------------------------------------------------
        */

        .container-fluid {
            padding-left: 12px;
            padding-right: 12px;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .dashboard-header {
            flex-direction: column;
            align-items: stretch;

            margin-bottom: 1rem !important;
        }

        .dashboard-header h2 {
            font-size: 1.25rem;
        }

        .dashboard-date {
            width: fit-content;

            padding: .45rem .7rem;

            font-size: .8rem;
        }


        /*
        |--------------------------------------------------------------------------
        | CARD
        |--------------------------------------------------------------------------
        */

        .card {
            border-radius: 10px;
        }

        .card-header {
            min-height: auto;

            padding: .9rem 1rem;
        }

        .card-body {
            padding: 1rem;
        }


        /*
        |--------------------------------------------------------------------------
        | RINGKASAN
        |--------------------------------------------------------------------------
        */

        .row.g-3 > .col-6 .card-body {
            padding: .9rem;
        }

        .row.g-3 > .col-6 .h2 {
            font-size: 1.4rem;
        }


        /*
        |--------------------------------------------------------------------------
        | SESSION CARD
        |--------------------------------------------------------------------------
        */

        .absensi-session-card
        .avatar-lg {
            width: 42px;
            height: 42px;
        }

        .absensi-session-card
        .avatar-lg
        .fs-2 {
            font-size: 1.25rem !important;
        }

        .absensi-session-card h3 {
            font-size: 1rem;
        }


        /*
        |--------------------------------------------------------------------------
        | INFORMASI SESI
        |--------------------------------------------------------------------------
        */

        .session-information-item {
            padding: .7rem 0;

            font-size: .875rem;
        }

        .session-empty {
            min-height: 90px;

            padding: .85rem;

            font-size: .875rem;
        }


        /*
        |--------------------------------------------------------------------------
        | SESI HARI INI
        |--------------------------------------------------------------------------
        */

        .session-mobile-item {
            padding: 1rem;
        }

        .session-mobile-info {
            gap: .75rem;
        }


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD ACTION
        |--------------------------------------------------------------------------
        */

        .dashboard-action {
            min-height: 74px;

            padding: .85rem;

            gap: .75rem;
        }


        /*
        |--------------------------------------------------------------------------
        | KETENTUAN
        |--------------------------------------------------------------------------
        */

        .attendance-rule {
            padding: .85rem 0;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE KECIL
    |--------------------------------------------------------------------------
    */

    @media (max-width: 420px) {

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .dashboard-header
        .avatar {
            width: 32px;
            height: 32px;
        }


        /*
        |--------------------------------------------------------------------------
        | RINGKASAN
        |--------------------------------------------------------------------------
        */

        .row.g-3 > .col-6 .card-body {
            padding: .8rem;
        }

        .row.g-3 > .col-6 .avatar {
            width: 32px;
            height: 32px;

            font-size: 16px;
        }

        .row.g-3 > .col-6 .h2 {
            font-size: 1.25rem;
        }


        /*
        |--------------------------------------------------------------------------
        | SESSION CARD HEADER
        |--------------------------------------------------------------------------
        */

        .absensi-session-card
        .card-body
        > .d-flex:first-child {
            align-items: flex-start !important;
        }

        .absensi-session-card
        .card-body
        > .d-flex:first-child
        > .d-flex {
            gap: .65rem !important;
        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE SESSION INFORMATION
        |--------------------------------------------------------------------------
        */

        .session-mobile-info {
            grid-template-columns: 1fr;
        }


        /*
        |--------------------------------------------------------------------------
        | BADGE
        |--------------------------------------------------------------------------
        */

        .badge {
            white-space: normal;
        }

    }

</style>

@endpush