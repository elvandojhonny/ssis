@extends('layouts.app')

@section('title', 'Dashboard Guru')


@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD GURU
    |--------------------------------------------------------------------------
    */

    .guru-service-card {
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        transition:
            transform .2s ease,
            box-shadow .2s ease,
            border-color .2s ease;
    }


    .guru-service-card:hover {
        transform: translateY(-4px);
        box-shadow:
            0 12px 32px
            rgba(0, 0, 0, .08);
    }


    .guru-service-card .card-body {
        flex: 1;
    }


    .guru-service-icon {
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        font-size: 1.6rem;
    }


    .guru-service-title {
        font-size: 1.15rem;
        font-weight: 700;
    }


    .guru-service-description {
        line-height: 1.65;
    }


    .guru-service-card-disabled {
        opacity: .8;
    }


    .guru-service-card-disabled:hover {
        transform: none;
        box-shadow: none;
    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767.98px) {

        .guru-service-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            font-size: 1.35rem;
        }


        .guru-service-card .card-body {
            padding: 1.25rem;
        }


        .guru-service-card .card-footer {
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
                Dashboard Guru
            </h2>


            <div class="text-secondary mt-1">

                Selamat datang,

                <strong>
                    {{ auth()->user()->name }}
                </strong>.

                Kelola aktivitas pembelajaran melalui
                layanan yang tersedia.

            </div>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- LAYANAN UTAMA --}}
{{-- ========================================================= --}}

<div class="mb-3">

    <h3 class="mb-1">
        Layanan Guru
    </h3>

    <div class="text-secondary">
        Pilih layanan yang ingin Anda gunakan.
    </div>

</div>


    {{-- ===================================================== --}}
    {{-- CBT --}}
    {{-- ===================================================== --}}

    <div class="col-12 col-md-6 col-xl-4">

        <div class="card guru-service-card">


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
                            guru-service-icon
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



                <div class="guru-service-title mb-2">
                    Computer Based Test
                </div>


                <div
                    class="
                        text-secondary
                        guru-service-description
                    "
                >

                    Buat bank soal, susun ujian,
                    kelola token akses, pantau
                    pelaksanaan, dan lihat hasil siswa.

                </div>

            </div>



            <div class="card-footer bg-transparent">

                <a
                    href="{{ route('cbt.bank-soal.index') }}"
                    class="btn btn-primary w-100"
                >

                    <i class="ti ti-file-pencil me-2"></i>

                    Kelola Ujian

                </a>

            </div>

        </div>

    </div>
    
</div>

@endsection