@extends('layouts.app')

@section('title', 'Dashboard Siswa')


@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD SISWA
    |--------------------------------------------------------------------------
    */

    .siswa-profile-card {
        overflow: hidden;
    }


    .siswa-profile-avatar {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        font-size: 1.6rem;
        flex-shrink: 0;
    }


    .siswa-info-item {
        display: flex;
        align-items: center;
        gap: .5rem;
        color: var(--tblr-secondary);
        font-size: .875rem;
    }


    /*
    |--------------------------------------------------------------------------
    | SERVICE CARD
    |--------------------------------------------------------------------------
    */

    .siswa-service-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }


    .siswa-service-card:hover {
        transform: translateY(-4px);
        box-shadow:
            0 12px 32px
            rgba(0, 0, 0, .08);
    }


    .siswa-service-card .card-body {
        flex: 1;
    }


    .siswa-service-icon {
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        font-size: 1.6rem;
        flex-shrink: 0;
    }


    .siswa-service-title {
        font-size: 1.15rem;
        font-weight: 700;
    }


    .siswa-service-description {
        line-height: 1.65;
    }


    .siswa-service-card-disabled {
        opacity: .8;
    }


    .siswa-service-card-disabled:hover {
        transform: none;
        box-shadow: none;
    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767.98px) {

        .siswa-profile-card .card-body {
            padding: 1.25rem;
        }


        .siswa-profile-avatar {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            font-size: 1.3rem;
        }


        .siswa-profile-wrapper {
            align-items: flex-start !important;
        }


        .siswa-info-list {
            flex-direction: column;
            align-items: flex-start !important;
            gap: .4rem !important;
        }


        .siswa-info-divider {
            display: none;
        }


        .siswa-service-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            font-size: 1.35rem;
        }


        .siswa-service-card .card-body {
            padding: 1.25rem;
        }


        .siswa-service-card .card-footer {
            padding:
                0
                1.25rem
                1.25rem;

            border-top: 0;
            background: transparent;
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
                Dashboard Siswa
            </h2>


            <div class="text-secondary mt-1">

                Selamat datang,

                <strong>
                    {{ auth()->user()->name }}
                </strong>.

            </div>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- INFORMASI SISWA --}}
{{-- ========================================================= --}}

<div class="card siswa-profile-card mb-4">

    <div class="card-body">

        <div
            class="
                d-flex
                align-items-center
                gap-3
                siswa-profile-wrapper
            "
        >

            {{-- AVATAR --}}

            <div
                class="
                    siswa-profile-avatar
                    bg-primary-lt
                    text-primary
                "
            >

                <i class="ti ti-user"></i>

            </div>



            {{-- INFORMASI --}}

            <div class="min-w-0 flex-fill">

                <h3 class="mb-2">

                    {{ auth()->user()->name }}

                </h3>


                <div
                    class="
                        d-flex
                        flex-wrap
                        align-items-center
                        gap-2
                        siswa-info-list
                    "
                >


                    {{-- NIS --}}

                    <div class="siswa-info-item">

                        <i class="ti ti-id"></i>

                        <span>

                            NIS:

                            <strong class="text-body">

                                {{
                                    $user
                                        ->siswa
                                        ?->nis
                                    ?? '-'
                                }}

                            </strong>

                        </span>

                    </div>



                    <span
                        class="
                            text-secondary
                            siswa-info-divider
                        "
                    >
                        •
                    </span>



                    {{-- KELAS --}}

                    <div class="siswa-info-item">

                        <i class="ti ti-school"></i>

                        <span>

                            Kelas:

                            <strong class="text-body">

                                {{
                                    $user
                                        ->siswa
                                        ?->kelas
                                        ?->nama
                                    ?? '-'
                                }}

                            </strong>

                        </span>

                    </div>



                    <span
                        class="
                            text-secondary
                            siswa-info-divider
                        "
                    >
                        •
                    </span>



                    {{-- TAHUN AJARAN --}}

                    <div class="siswa-info-item">

                        <i class="ti ti-calendar"></i>

                        <span>

                            Tahun Ajaran:

                            <strong class="text-body">

                                {{
                                    $user
                                        ->siswa
                                        ?->kelas
                                        ?->tahunAjaran
                                        ?->nama
                                    ?? '-'
                                }}

                            </strong>

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- LAYANAN SISWA --}}
{{-- ========================================================= --}}

<div class="mb-3">

    <h3 class="mb-1">
        Layanan Siswa
    </h3>


    <div class="text-secondary">

        Akses layanan sekolah yang tersedia
        untuk Anda.

    </div>

</div>



<div class="row row-cards">


    {{-- ===================================================== --}}
    {{-- ABSENSI --}}
    {{-- ===================================================== --}}

    <div class="col-12 col-md-6 col-xl-4">

        <div class="card siswa-service-card">


            <div class="card-body">

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-start
                        gap-3
                        mb-4
                    "
                >

                    <div
                        class="
                            siswa-service-icon
                            bg-green-lt
                            text-green
                        "
                    >

                        <i class="ti ti-calendar-check"></i>

                    </div>


                    <span class="badge bg-green-lt">

                        <i class="ti ti-circle-check me-1"></i>

                        Aktif

                    </span>

                </div>



                <div class="siswa-service-title mb-2">
                    Kehadiran Saya
                </div>


                <div
                    class="
                        text-secondary
                        siswa-service-description
                    "
                >

                    Lihat ringkasan dan riwayat
                    kehadiran pagi dan siang selama
                    semester berjalan.

                </div>

            </div>



            <div class="card-footer bg-transparent">

                <a
                    href="{{ route('absensi.siswa.index') }}"
                    class="btn btn-primary w-100"
                >

                    <i
                        class="
                            ti
                            ti-report-analytics
                            me-2
                        "
                    ></i>

                    Lihat Rekap Kehadiran

                </a>

            </div>

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- CBT --}}
    {{-- ===================================================== --}}

    <div class="col-12 col-md-6 col-xl-4">

        <div class="card siswa-service-card">


            <div class="card-body">

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-start
                        gap-3
                        mb-4
                    "
                >

                    <div
                        class="
                            siswa-service-icon
                            bg-blue-lt
                            text-blue
                        "
                    >

                        <i class="ti ti-file-pencil"></i>

                    </div>


                    <span class="badge bg-blue-lt">

                        <i class="ti ti-circle-check me-1"></i>

                        Aktif

                    </span>

                </div>



                <div class="siswa-service-title mb-2">
                    Ujian Online
                </div>


                <div
                    class="
                        text-secondary
                        siswa-service-description
                    "
                >

                    Akses ujian yang tersedia,
                    masukkan token ujian, dan kerjakan
                    ujian sesuai jadwal yang ditentukan.

                </div>

            </div>



            <div class="card-footer bg-transparent">

                <a
                    href="{{ route('cbt.siswa.index') }}"
                    class="btn btn-primary w-100"
                >

                    <i class="ti ti-file-pencil me-2"></i>

                    Lihat Daftar Ujian

                </a>

            </div>

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- PERPUSTAKAAN --}}
    {{-- ===================================================== --}}

    <div class="col-12 col-md-6 col-xl-4">

        <div
            class="
                card
                siswa-service-card
                siswa-service-card-disabled
            "
        >


            <div class="card-body">

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-start
                        gap-3
                        mb-4
                    "
                >

                    <div
                        class="
                            siswa-service-icon
                            bg-orange-lt
                            text-orange
                        "
                    >

                        <i class="ti ti-books"></i>

                    </div>


                    <span class="badge bg-secondary-lt">

                        <i class="ti ti-clock me-1"></i>

                        Segera Hadir

                    </span>

                </div>



                <div class="siswa-service-title mb-2">
                    Perpustakaan
                </div>


                <div
                    class="
                        text-secondary
                        siswa-service-description
                    "
                >

                    Lihat koleksi buku, informasi
                    peminjaman, dan riwayat peminjaman
                    buku perpustakaan.

                </div>

            </div>



            <div class="card-footer bg-transparent">

                <button
                    type="button"
                    class="
                        btn
                        btn-outline-secondary
                        w-100
                    "
                    disabled
                >

                    <i class="ti ti-books me-2"></i>

                    Lihat Perpustakaan

                </button>

            </div>

        </div>

    </div>

</div>

@endsection