@extends('layouts.app')

@section('title', 'Dashboard Operator')


@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD OPERATOR
    |--------------------------------------------------------------------------
    */

    .operator-welcome-card {
        position: relative;
        overflow: hidden;
        border: 0;
        background:
            linear-gradient(
                135deg,
                var(--tblr-primary) 0%,
                #4c6ef5 100%
            );
        color: #fff;
    }


    .operator-welcome-card::before {
        content: '';
        position: absolute;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
        top: -140px;
        right: -70px;
    }


    .operator-welcome-card::after {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .06);
        bottom: -100px;
        right: 130px;
    }


    .operator-welcome-content {
        position: relative;
        z-index: 2;
    }


    .operator-welcome-icon {
        width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        background: rgba(255, 255, 255, .15);
        font-size: 2rem;
        backdrop-filter: blur(8px);
    }


    /*
    |--------------------------------------------------------------------------
    | STATISTIC CARD
    |--------------------------------------------------------------------------
    */

    .operator-stat-card {
        height: 100%;
        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }


    .operator-stat-card:hover {
        transform: translateY(-3px);
        box-shadow:
            0 10px 30px
            rgba(0, 0, 0, .08);
    }


    .operator-stat-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 1.4rem;
        flex-shrink: 0;
    }


    .operator-stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1.1;
    }


    /*
    |--------------------------------------------------------------------------
    | QUICK ACCESS
    |--------------------------------------------------------------------------
    */

    .operator-menu-card {
        display: block;
        height: 100%;
        padding: 1.25rem;
        color: inherit;
        text-decoration: none;
        border: 1px solid var(--tblr-border-color);
        border-radius: var(--tblr-border-radius-lg);
        background: var(--tblr-bg-surface);
        transition:
            transform .2s ease,
            border-color .2s ease,
            box-shadow .2s ease;
    }


    .operator-menu-card:hover {
        color: inherit;
        text-decoration: none;
        transform: translateY(-3px);
        border-color:
            color-mix(
                in srgb,
                var(--tblr-primary) 40%,
                var(--tblr-border-color)
            );
        box-shadow:
            0 10px 30px
            rgba(0, 0, 0, .07);
    }


    .operator-menu-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 1.35rem;
        margin-bottom: 1rem;
    }


    .operator-menu-arrow {
        transition: transform .2s ease;
    }


    .operator-menu-card:hover
    .operator-menu-arrow {
        transform: translateX(4px);
    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767.98px) {

        .operator-welcome-card .card-body {
            padding: 1.25rem;
        }


        .operator-welcome-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            font-size: 1.5rem;
        }


        .operator-welcome-title {
            font-size: 1.35rem;
        }


        .operator-stat-value {
            font-size: 1.5rem;
        }


        .operator-stat-icon {
            width: 46px;
            height: 46px;
        }


        .operator-menu-card {
            padding: 1rem;
        }

    }

</style>

@endpush


@section('content')


{{-- ========================================================= --}}
{{-- PAGE HEADER --}}
{{-- ========================================================= --}}

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Smart School Information System
            </div>

            <h2 class="page-title">
                Dashboard Operator (QR SISWA TIDAK BISA DIBUKA)
            </h2>

            <div class="text-secondary mt-1">
                Ringkasan dan pusat pengelolaan sistem sekolah.
            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- STATISTIK UTAMA --}}
{{-- ========================================================= --}}

<div class="row row-cards mb-4">


    {{-- GURU --}}

    <div class="col-6 col-lg-3">

        <div class="card operator-stat-card">

            <div class="card-body">

                <div
                    class="
                        d-flex
                        align-items-center
                        justify-content-between
                        gap-3
                    "
                >

                    <div>

                        <div class="text-secondary small mb-2">
                            Guru Aktif
                        </div>

                        <div class="operator-stat-value">
                            {{ $totalGuru }}
                        </div>

                    </div>


                    <div
                        class="
                            operator-stat-icon
                            bg-blue-lt
                            text-blue
                        "
                    >

                        <i class="ti ti-user-check"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- SISWA --}}

    <div class="col-6 col-lg-3">

        <div class="card operator-stat-card">

            <div class="card-body">

                <div
                    class="
                        d-flex
                        align-items-center
                        justify-content-between
                        gap-3
                    "
                >

                    <div>

                        <div class="text-secondary small mb-2">
                            Siswa Aktif
                        </div>

                        <div class="operator-stat-value">
                            {{ $totalSiswa }}
                        </div>

                    </div>


                    <div
                        class="
                            operator-stat-icon
                            bg-green-lt
                            text-green
                        "
                    >

                        <i class="ti ti-users"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- KELAS --}}

    <div class="col-6 col-lg-3">

        <div class="card operator-stat-card">

            <div class="card-body">

                <div
                    class="
                        d-flex
                        align-items-center
                        justify-content-between
                        gap-3
                    "
                >

                    <div>

                        <div class="text-secondary small mb-2">
                            Kelas Aktif
                        </div>

                        <div class="operator-stat-value">
                            {{ $totalKelas }}
                        </div>

                    </div>


                    <div
                        class="
                            operator-stat-icon
                            bg-orange-lt
                            text-orange
                        "
                    >

                        <i class="ti ti-school"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- TAHUN AJARAN --}}

    <div class="col-6 col-lg-3">

        <div class="card operator-stat-card">

            <div class="card-body">

                <div
                    class="
                        d-flex
                        align-items-center
                        justify-content-between
                        gap-3
                    "
                >

                    <div class="min-w-0">

                        <div class="text-secondary small mb-2">
                            Tahun Ajaran
                        </div>

                        <div
                            class="
                                fw-bold
                                text-truncate
                            "
                        >

                            {{
                                $tahunAjaranAktif?->nama
                                ?? '-'
                            }}

                        </div>

                    </div>


                    <div
                        class="
                            operator-stat-icon
                            bg-purple-lt
                            text-purple
                        "
                    >

                        <i class="ti ti-calendar-stats"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- AKSES CEPAT --}}
{{-- ========================================================= --}}

<div class="mb-3">

    <h3 class="mb-1">
        Akses Cepat
    </h3>

    <div class="text-secondary">
        Kelola fitur utama sistem dari sini.
    </div>

</div>


<div class="row row-cards">


    {{-- ABSENSI --}}

    <div class="col-12 col-sm-6 col-xl-4">

        <a
            href="{{ route('absensi.sesi.index') }}"
            class="operator-menu-card"
        >

            <div
                class="
                    operator-menu-icon
                    bg-green-lt
                    text-green
                "
            >

                <i class="ti ti-calendar-check"></i>

            </div>


            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-end
                    gap-3
                "
            >

                <div>

                    <div class="fw-bold mb-1">
                        Absensi Siswa
                    </div>

                    <div class="text-secondary small">
                        Kelola sesi dan pantau kehadiran siswa.
                    </div>

                </div>


                <i
                    class="
                        ti
                        ti-arrow-right
                        operator-menu-arrow
                    "
                ></i>

            </div>

        </a>

    </div>



    {{-- CBT --}}

    <div class="col-12 col-sm-6 col-xl-4">

        <a
            href="{{ route('cbt.ujian.index') }}"
            class="operator-menu-card"
        >

            <div
                class="
                    operator-menu-icon
                    bg-blue-lt
                    text-blue
                "
            >

                <i class="ti ti-device-desktop-check"></i>

            </div>


            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-end
                    gap-3
                "
            >

                <div>

                    <div class="fw-bold mb-1">
                        Computer Based Test
                    </div>

                    <div class="text-secondary small">
                        Kelola ujian dan pelaksanaan CBT.
                    </div>

                </div>


                <i
                    class="
                        ti
                        ti-arrow-right
                        operator-menu-arrow
                    "
                ></i>

            </div>

        </a>

    </div>



    {{-- REKAP --}}

    <div class="col-12 col-sm-6 col-xl-4">

        <a
            href="{{ route('absensi.rekap.index') }}"
            class="operator-menu-card"
        >

            <div
                class="
                    operator-menu-icon
                    bg-purple-lt
                    text-purple
                "
            >

                <i class="ti ti-chart-bar"></i>

            </div>


            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-end
                    gap-3
                "
            >

                <div>

                    <div class="fw-bold mb-1">
                        Rekap Kehadiran
                    </div>

                    <div class="text-secondary small">
                        Lihat statistik dan laporan absensi.
                    </div>

                </div>


                <i
                    class="
                        ti
                        ti-arrow-right
                        operator-menu-arrow
                    "
                ></i>

            </div>

        </a>

    </div>



    {{-- DATA GURU --}}

    <div class="col-12 col-sm-6 col-xl-4">

        <a
            href="{{ route('guru.index') }}"
            class="operator-menu-card"
        >

            <div
                class="
                    operator-menu-icon
                    bg-azure-lt
                    text-azure
                "
            >

                <i class="ti ti-user"></i>

            </div>


            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-end
                    gap-3
                "
            >

                <div>

                    <div class="fw-bold mb-1">
                        Data Guru
                    </div>

                    <div class="text-secondary small">
                        Kelola akun dan data guru sekolah.
                    </div>

                </div>


                <i
                    class="
                        ti
                        ti-arrow-right
                        operator-menu-arrow
                    "
                ></i>

            </div>

        </a>

    </div>



    {{-- DATA SISWA --}}

    <div class="col-12 col-sm-6 col-xl-4">

        <a
            href="{{ route('siswa.index') }}"
            class="operator-menu-card"
        >

            <div
                class="
                    operator-menu-icon
                    bg-orange-lt
                    text-orange
                "
            >

                <i class="ti ti-users-group"></i>

            </div>


            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-end
                    gap-3
                "
            >

                <div>

                    <div class="fw-bold mb-1">
                        Data Siswa
                    </div>

                    <div class="text-secondary small">
                        Kelola akun dan data siswa sekolah.
                    </div>

                </div>


                <i
                    class="
                        ti
                        ti-arrow-right
                        operator-menu-arrow
                    "
                ></i>

            </div>

        </a>

    </div>



    {{-- KELAS --}}

    <div class="col-12 col-sm-6 col-xl-4">

        <a
            href="{{ route('kelas.index') }}"
            class="operator-menu-card"
        >

            <div
                class="
                    operator-menu-icon
                    bg-yellow-lt
                    text-yellow
                "
            >

                <i class="ti ti-building"></i>

            </div>


            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-end
                    gap-3
                "
            >

                <div>

                    <div class="fw-bold mb-1">
                        Data Kelas
                    </div>

                    <div class="text-secondary small">
                        Kelola kelas dan tahun ajaran sekolah.
                    </div>

                </div>


                <i
                    class="
                        ti
                        ti-arrow-right
                        operator-menu-arrow
                    "
                ></i>

            </div>

        </a>

    </div>

</div>

@endsection
