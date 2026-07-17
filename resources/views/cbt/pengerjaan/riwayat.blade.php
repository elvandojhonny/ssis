@extends('layouts.app')

@section('title', 'Riwayat Ujian')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Modul CBT
            </div>

            <h2 class="page-title">
                Riwayat Ujian
            </h2>

            <div class="text-secondary mt-1">
                Daftar ujian yang telah Anda selesaikan.
            </div>

        </div>


        <div class="col-12 col-sm-auto">

            <a
                href="{{ route('cbt.siswa.index') }}"
                class="btn btn-outline-primary w-100"
            >
                <i class="ti ti-file-pencil me-2"></i>

                Daftar Ujian
            </a>

        </div>

    </div>

</div>


@if(session('success'))

    <div class="alert alert-success">

        <div class="d-flex align-items-center">

            <i class="ti ti-circle-check me-2"></i>

            <div>
                {{ session('success') }}
            </div>

        </div>

    </div>

@endif

@if(session('error'))

    <div class="alert alert-danger">

        <div class="d-flex align-items-center">

            <i class="ti ti-alert-circle me-2"></i>

            <div>
                {{ session('error') }}
            </div>

        </div>

    </div>

@endif


@if($riwayat->isEmpty())

    <div class="card">

        <div class="card-body text-center py-5">

            <span class="avatar avatar-xl bg-blue-lt mb-3">

                <i class="ti ti-history"></i>

            </span>


            <h3>
                Belum Ada Riwayat Ujian
            </h3>


            <p class="text-secondary mb-4">

                Ujian yang telah selesai dikerjakan
                akan muncul pada halaman ini.

            </p>


            <a
                href="{{ route('cbt.siswa.index') }}"
                class="btn btn-primary"
            >
                <i class="ti ti-file-pencil me-2"></i>

                Lihat Daftar Ujian
            </a>

        </div>

    </div>

@else


    <div class="row row-cards">

        @foreach($riwayat as $pengerjaan)

            <div class="col-12 col-md-6 col-xl-4">

                <div class="card h-100">

                    <div class="card-status-top bg-success"></div>


                    <div class="card-body">

                        {{-- HEADER --}}

                        <div
                            class="
                                d-flex
                                justify-content-between
                                align-items-start
                                gap-3
                                mb-3
                            "
                        >

                            <span class="avatar bg-success-lt">

                                <i class="ti ti-file-check"></i>

                            </span>


                            <span class="badge bg-success-lt">

                                <i class="ti ti-check me-1"></i>

                                Selesai

                            </span>

                        </div>


                        {{-- JUDUL UJIAN --}}

                        <h3 class="card-title mb-2">

                            {{
                                $pengerjaan
                                    ->ujian
                                    ->judul
                            }}

                        </h3>


                        {{-- INFORMASI --}}

                        <div class="text-secondary small mb-4">

                            <div class="mb-2">

                                <i class="ti ti-school me-2"></i>

                                {{
                                    $pengerjaan
                                        ->ujian
                                        ->kelas
                                        ->nama
                                    ?? '-'
                                }}

                            </div>


                            <div>

                                <i class="ti ti-calendar me-2"></i>

                                {{
                                    $pengerjaan
                                        ->waktu_selesai
                                        ?->format(
                                            'd/m/Y H:i'
                                        )
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        {{-- NILAI --}}

                        <div
                            class="
                                border
                                rounded
                                p-3
                                d-flex
                                justify-content-between
                                align-items-center
                            "
                        >

                            <div>

                                <div class="text-secondary small">

                                    Nilai

                                </div>


                                <div class="fw-bold">

                                    Hasil Ujian

                                </div>

                            </div>


                            <div
                                class="
                                    h1
                                    text-primary
                                    mb-0
                                "
                            >

                                {{
                                    number_format(
                                        (float)
                                        $pengerjaan
                                            ->nilai,
                                        2
                                    )
                                }}

                            </div>

                        </div>

                    </div>


                    <div class="card-footer">

                        <a
                            href="{{
                                route(
                                    'cbt.siswa.pengerjaan.hasil',
                                    $pengerjaan
                                )
                            }}"
                            class="btn btn-primary w-100"
                        >

                            <i class="ti ti-report-analytics me-2"></i>

                            Lihat Hasil

                        </a>

                    </div>

                </div>

            </div>

        @endforeach

    </div>


    @if($riwayat->hasPages())

        <div class="mt-4">

            {{
                $riwayat->links()
            }}

        </div>

    @endif

@endif

@endsection