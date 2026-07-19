@extends('layouts.app')

@section('title', 'Rekap Hasil Ujian')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Modul CBT
            </div>

            <h2 class="page-title">
                Rekap Hasil Ujian
            </h2>

            <div class="text-secondary mt-1">
                Pantau peserta dan hasil ujian terbaru.
            </div>

        </div>


        <div class="col-12 col-md-auto">

            <a
                href="{{ route('cbt.rekap.arsip') }}"
                class="btn btn-outline-secondary w-100"
            >
                <i class="ti ti-archive me-1"></i>
                Arsip Rekap
            </a>

        </div>

    </div>

</div>


@if($ujians->isEmpty())

    <div class="card">

        <div class="card-body text-center py-5">

            <span class="avatar avatar-xl bg-blue-lt mb-3">
                <i class="ti ti-report-analytics"></i>
            </span>

            <h3>
                Belum Ada Rekap Ujian
            </h3>

            <div class="text-secondary">
                Ujian yang telah dipublikasikan akan muncul di halaman ini.
            </div>

        </div>

    </div>

@else

    <div class="row row-cards">

        @foreach($ujians as $ujian)

            <div class="col-12 col-md-6 col-xl-4">

                <div class="card h-100">

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

                            <span class="avatar bg-blue-lt">
                                <i class="ti ti-file-analytics"></i>
                            </span>


                            @if($ujian->status === 'dipublikasi')

                                <span class="badge bg-success-lt">
                                    Dipublikasi
                                </span>

                            @else

                                <span class="badge bg-secondary-lt">
                                    Selesai
                                </span>

                            @endif

                        </div>


                        <h3 class="card-title mb-1">
                            {{ $ujian->judul }}
                        </h3>


                        <div class="text-secondary small mb-4">

                            <div class="mb-2">

                                <i class="ti ti-school me-2"></i>

                                {{ $ujian->kelas->nama ?? '-' }}

                            </div>


                            <div class="mb-2">

                                <i class="ti ti-book me-2"></i>

                                {{
                                    $ujian
                                        ->bankSoal
                                        ->mata_pelajaran
                                    ?? '-'
                                }}

                            </div>


                            <div>

                                <i class="ti ti-calendar me-2"></i>

                                {{
                                    $ujian
                                        ->waktu_mulai
                                        ?->format('d/m/Y H:i')
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        <div class="row g-2">

                            <div class="col-6">

                                <div class="border rounded p-3">

                                    <div class="text-secondary small">
                                        Peserta Masuk
                                    </div>

                                    <div class="h2 mb-0">
                                        {{ $ujian->pengerjaans_count }}
                                    </div>

                                </div>

                            </div>


                            <div class="col-6">

                                <div class="border rounded p-3">

                                    <div class="text-secondary small">
                                        Selesai
                                    </div>

                                    <div class="h2 mb-0">
                                        {{ $ujian->selesai_count }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-footer">

                        <a
                            href="{{
                                route(
                                    'cbt.rekap.show',
                                    $ujian
                                )
                            }}"
                            class="btn btn-primary w-100"
                        >

                            <i class="ti ti-report-analytics me-2"></i>

                            Lihat Rekap

                        </a>

                    </div>

                </div>

            </div>

        @endforeach

    </div>


    @if($ujians->hasPages())

        <div class="mt-4">
            {{ $ujians->links() }}
        </div>

    @endif

@endif

@endsection