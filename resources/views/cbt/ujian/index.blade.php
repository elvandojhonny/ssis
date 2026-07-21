@extends('layouts.app')

@section('title', 'Daftar Ujian')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Computer Based Test
            </div>

            <h2 class="page-title">
                Daftar Ujian
            </h2>

            <div class="text-secondary mt-1">
                Kelola ujian yang akan diberikan kepada siswa.
            </div>

        </div>


        <div class="col-12 col-md-auto">

            <div class="d-flex flex-column flex-sm-row gap-2">

                <a
                    href="{{ route('cbt.ujian.arsip') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="ti ti-archive me-1"></i>
                    Arsip Ujian
                </a>


                <a
                    href="{{ route('cbt.ujian.create') }}"
                    class="btn btn-primary"
                >
                    <i class="ti ti-plus me-1"></i>
                    Buat Ujian
                </a>

            </div>

        </div>

    </div>

</div>


@if(session('success'))

    <div class="alert alert-success">

        <i class="ti ti-circle-check me-2"></i>

        {{ session('success') }}

    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger">

        <i class="ti ti-alert-circle me-2"></i>

        {{ session('error') }}

    </div>

@endif


<div class="row row-cards">

    @forelse($ujians as $ujian)

        <div class="col-md-6 col-xl-4">

            <div class="card h-100">

                @if($ujian->status === 'draft')

                    <div class="card-status-top bg-secondary"></div>

                @elseif($ujian->status === 'dipublikasi')

                    <div class="card-status-top bg-success"></div>

                @else

                    <div class="card-status-top bg-blue"></div>

                @endif


                <div class="card-body">

                    <div
                        class="
                            d-flex
                            justify-content-between
                            align-items-start
                            gap-3
                            mb-3
                        "
                    >

                        <div>

                            <div class="text-secondary small mb-1">

                                {{
                                    $ujian
                                        ->bankSoal
                                        ->mata_pelajaran
                                }}

                            </div>


                            <h3 class="card-title mb-0">

                                {{ $ujian->judul }}

                            </h3>

                        </div>


                        @if($ujian->status === 'draft')

                            <span class="badge bg-secondary-lt">
                                Draft
                            </span>

                        @elseif($ujian->status === 'dipublikasi')

                            <span class="badge bg-success-lt">
                                Dipublikasi
                            </span>

                        @else

                            <span class="badge bg-blue-lt">
                                Selesai
                            </span>

                        @endif

                    </div>


                    <div class="mb-3">

                        <div class="text-secondary small">
                            Kelas
                        </div>

                        <div class="fw-bold">

                            {{ $ujian->kelas->nama }}

                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-6">

                            <div class="text-secondary small">
                                Jumlah Soal
                            </div>

                            <div class="fw-bold">

                                {{
                                    $ujian
                                        ->bankSoal
                                        ->soals
                                        ->count()
                                }}

                                soal

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="text-secondary small">
                                Durasi
                            </div>

                            <div class="fw-bold">

                                {{ $ujian->durasi_menit }}

                                menit

                            </div>

                        </div>

                    </div>


                    <hr>


                    <div class="small">

                        <div class="mb-2">

                            <i
                                class="
                                    ti
                                    ti-calendar
                                    me-2
                                    text-secondary
                                "
                            ></i>

                            {{
                                $ujian
                                    ->waktu_mulai
                                    ->format('d/m/Y H:i')
                            }}

                        </div>


                        <div>

                            <i
                                class="
                                    ti
                                    ti-clock
                                    me-2
                                    text-secondary
                                "
                            ></i>

                            Sampai

                            {{
                                $ujian
                                    ->waktu_selesai
                                    ->format('d/m/Y H:i')
                            }}

                        </div>

                    </div>

                </div>


                <div class="card-footer">

                    <a
                        href="{{
                            route(
                                'cbt.ujian.show',
                                $ujian
                            )
                        }}"
                        class="btn btn-outline-primary w-100"
                    >

                        <i class="ti ti-eye me-1"></i>

                        Lihat Detail

                    </a>

                </div>

            </div>

        </div>


    @empty

        <div class="col-12">

            <div class="card">

                <div class="card-body text-center py-5">

                    <span
                        class="
                            avatar
                            avatar-xl
                            bg-blue-lt
                            mb-3
                        "
                    >

                        <i class="ti ti-file-pencil"></i>

                    </span>


                    <h3>
                        Belum Ada Ujian
                    </h3>


                    <div class="text-secondary mb-4">

                        Buat ujian menggunakan bank soal
                        yang telah diupload oleh guru.

                    </div>


                    <a
                        href="{{ route('cbt.ujian.create') }}"
                        class="btn btn-primary"
                    >

                        <i class="ti ti-plus me-1"></i>

                        Buat Ujian

                    </a>

                </div>

            </div>

        </div>

    @endforelse

</div>


@if($ujians->hasPages())

    <div class="mt-4">

        {{ $ujians->links() }}

    </div>

@endif

@endsection