@extends('layouts.app')

@section('title', 'Arsip Rekap Ujian')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Modul CBT
            </div>

            <h2 class="page-title">
                Arsip Rekap Ujian
            </h2>

            <div class="text-secondary mt-1">
                Rekap hasil ujian yang telah selesai lebih dari 7 hari.
            </div>

        </div>


        <div class="col-12 col-md-auto">

            <a
                href="{{ route('cbt.rekap.index') }}"
                class="btn btn-outline-secondary w-100"
            >
                <i class="ti ti-arrow-left me-1"></i>
                Kembali ke Rekap
            </a>

        </div>

    </div>

</div>


{{-- FILTER --}}

<div class="card mb-4">

    <div class="card-body">

        <form
            action="{{ route('cbt.rekap.arsip') }}"
            method="GET"
        >

            <div class="row g-3 align-items-end">

                <div class="col-md-8">

                    <label class="form-label">
                        Cari Ujian
                    </label>

                    <div class="input-icon">

                        <span class="input-icon-addon">
                            <i class="ti ti-search"></i>
                        </span>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Cari judul, mata pelajaran, atau kelas..."
                        >

                    </div>

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Tahun
                    </label>

                    <select
                        name="tahun"
                        class="form-select"
                    >

                        <option value="">
                            Semua Tahun
                        </option>

                        @foreach($daftarTahun as $tahun)

                            <option
                                value="{{ $tahun }}"
                                @selected(
                                    (string) request('tahun')
                                    === (string) $tahun
                                )
                            >
                                {{ $tahun }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-2">

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        <i class="ti ti-filter me-1"></i>
                        Filter
                    </button>

                </div>

            </div>


            @if(
                request()->filled('search')
                || request()->filled('tahun')
            )

                <div class="mt-3">

                    <a
                        href="{{ route('cbt.rekap.arsip') }}"
                        class="btn btn-sm btn-outline-secondary"
                    >
                        <i class="ti ti-x me-1"></i>
                        Reset Filter
                    </a>

                </div>

            @endif

        </form>

    </div>

</div>


{{-- INFORMASI JUMLAH --}}

<div class="d-flex justify-content-between align-items-center mb-3">

    <div class="text-secondary">

        Ditemukan

        <strong class="text-body">
            {{ $ujians->total() }}
        </strong>

        rekap ujian.

    </div>


    <span class="badge bg-secondary-lt">

        <i class="ti ti-archive me-1"></i>

        Arsip

    </span>

</div>


{{-- DAFTAR REKAP --}}

<div class="row row-cards">

    @forelse($ujians as $ujian)

        <div class="col-12 col-md-6 col-xl-4">

            <div class="card h-100">

                <div class="card-status-top bg-secondary"></div>


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


                        <span class="badge bg-secondary-lt">

                            <i class="ti ti-archive me-1"></i>

                            Diarsipkan

                        </span>

                    </div>


                    <h3 class="card-title mb-1">

                        {{ $ujian->judul }}

                    </h3>


                    <div class="text-secondary small mb-4">

                        <div class="mb-2">

                            <i class="ti ti-school me-2"></i>

                            {{
                                $ujian
                                    ->kelas
                                    ?->nama
                                ?? '-'
                            }}

                        </div>


                        <div class="mb-2">

                            <i class="ti ti-book me-2"></i>

                            {{
                                $ujian
                                    ->bankSoal
                                    ?->mata_pelajaran
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


    @empty

        <div class="col-12">

            <div class="card">

                <div class="card-body text-center py-5">

                    <span
                        class="
                            avatar
                            avatar-xl
                            bg-secondary-lt
                            mb-3
                        "
                    >
                        <i class="ti ti-archive"></i>
                    </span>


                    <h3>
                        Arsip Rekap Masih Kosong
                    </h3>


                    <div class="text-secondary">

                        Belum ada rekap ujian yang selesai
                        lebih dari 7 hari.

                    </div>

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