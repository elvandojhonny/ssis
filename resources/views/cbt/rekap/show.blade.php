@extends('layouts.app')

@section('title', 'Detail Rekap Ujian')


@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | MOBILE PARTICIPANT CARD
    |--------------------------------------------------------------------------
    */

    .rekap-mobile-list {
        display: none;
    }


    @media (max-width: 767.98px) {

        /*
         * Header
         */
        .rekap-page-title {
            font-size: 1.35rem;
            line-height: 1.4;
        }


        .rekap-header-actions {
            width: 100%;
        }


        .rekap-header-actions .btn {
            width: 100%;
        }


        /*
         * Statistik
         */
        .rekap-stat-card .card-body {
            padding: 1rem;
        }


        .rekap-stat-label {
            font-size: 0.75rem;
            line-height: 1.3;
            min-height: 32px;
        }


        .rekap-stat-value {
            font-size: 1.5rem;
        }


        /*
         * Sembunyikan tabel desktop
         */
        .rekap-desktop-table {
            display: none;
        }


        /*
         * Tampilkan card mobile
         */
        .rekap-mobile-list {
            display: block;
        }


        .rekap-mobile-card {
            border-bottom:
                1px solid
                var(--tblr-border-color);
        }


        .rekap-mobile-card:last-child {
            border-bottom: 0;
        }


        .rekap-mobile-info {
            display: grid;
            grid-template-columns:
                minmax(90px, 110px)
                minmax(0, 1fr);

            gap: 10px 12px;

            align-items: start;
        }


        .rekap-mobile-label {
            color:
                var(
                    --tblr-secondary,
                    #626976
                );

            font-size: 0.8rem;
        }


        .rekap-mobile-value {
            min-width: 0;

            text-align: right;

            word-break: break-word;
        }


        .rekap-mobile-action .btn {
            width: 100%;
        }

    }

</style>

@endpush


@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col-12 col-md">

            <div class="page-pretitle">
                Rekap Hasil Ujian
            </div>


            <h2 class="page-title rekap-page-title">
                {{ $ujian->judul }}
            </h2>


            <div
                class="
                    text-secondary
                    mt-1
                    d-flex
                    flex-wrap
                    align-items-center
                    gap-1
                "
            >

                <span>
                    {{ $ujian->kelas->nama }}
                </span>

                <span class="mx-1">
                    •
                </span>

                <span>
                    {{ $ujian->bankSoal->mata_pelajaran }}
                </span>

            </div>

        </div>


        <div class="col-12 col-md-auto">

            <div
                class="
                    d-flex
                    flex-column
                    flex-sm-row
                    gap-2
                    rekap-header-actions
                "
            >

                <a
                    href="{{ route('cbt.rekap.export', $ujian) }}"
                    class="btn btn-success"
                >

                    <i class="ti ti-file-spreadsheet me-2"></i>

                    Export Excel

                </a>


                <a
                    href="{{ route('cbt.rekap.index') }}"
                    class="btn btn-outline-secondary"
                >

                    <i class="ti ti-arrow-left me-2"></i>

                    Kembali

                </a>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- ALERT SUCCESS --}}
{{-- ========================================================= --}}

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


{{-- ========================================================= --}}
{{-- ALERT ERROR --}}
{{-- ========================================================= --}}

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


{{-- ========================================================= --}}
{{-- STATISTIK --}}
{{-- ========================================================= --}}

<div class="row row-cards mb-4">


    {{-- TOTAL SISWA --}}

    <div class="col-6 col-lg-3">

        <div class="card h-100 rekap-stat-card">

            <div class="card-body">

                <div class="text-secondary rekap-stat-label">
                    Total Siswa
                </div>

                <div class="h1 mb-0 rekap-stat-value">
                    {{ $totalSiswa }}
                </div>

            </div>

        </div>

    </div>


    {{-- SELESAI --}}

    <div class="col-6 col-lg-3">

        <div class="card h-100 rekap-stat-card">

            <div class="card-body">

                <div class="text-secondary rekap-stat-label">
                    Selesai
                </div>

                <div
                    class="
                        h1
                        mb-0
                        text-success
                        rekap-stat-value
                    "
                >
                    {{ $sudahMengerjakan }}
                </div>

            </div>

        </div>

    </div>


    {{-- BELUM SELESAI --}}

    <div class="col-6 col-lg-3">

        <div class="card h-100 rekap-stat-card">

            <div class="card-body">

                <div class="text-secondary rekap-stat-label">
                    Belum Selesai
                </div>

                <div class="h1 mb-0 rekap-stat-value">

                    {{
                        $sedangMengerjakan
                        +
                        $diblokir
                        +
                        $belumMengerjakan
                    }}

                </div>

            </div>

        </div>

    </div>


    {{-- RATA-RATA --}}

    <div class="col-6 col-lg-3">

        <div class="card h-100 rekap-stat-card">

            <div class="card-body">

                <div class="text-secondary rekap-stat-label">
                    Rata-rata Nilai
                </div>

                <div
                    class="
                        h1
                        mb-0
                        text-primary
                        rekap-stat-value
                    "
                >

                    {{
                        $rataRata !== null
                            ? number_format(
                                $rataRata,
                                2
                            )
                            : '-'
                    }}

                </div>

            </div>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- DATA SISWA --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Hasil Peserta
        </h3>

    </div>



    {{-- ===================================================== --}}
    {{-- TABEL DESKTOP --}}
    {{-- ===================================================== --}}

    <div class="table-responsive rekap-desktop-table">

        <table class="table table-vcenter card-table">

            <thead>

                <tr>

                    <th>Siswa</th>

                    <th>NIS</th>

                    <th>Status</th>

                    <th>Mulai</th>

                    <th>Selesai</th>

                    <th>Nilai</th>

                    <th class="w-1">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($siswas as $siswa)

                    @php

                        $pengerjaan =
                            $pengerjaanPerSiswa
                                ->get($siswa->id);

                    @endphp


                    <tr>

                        {{-- SISWA --}}

                        <td>

                            <div class="fw-bold">
                                {{ $siswa->nama }}
                            </div>

                        </td>


                        {{-- NIS --}}

                        <td>
                            {{ $siswa->nis ?? '-' }}
                        </td>


                        {{-- STATUS --}}

                        <td>

                            @if(! $pengerjaan)

                                <span class="badge bg-secondary-lt">
                                    Belum Mengerjakan
                                </span>


                            @elseif(
                                $pengerjaan->status ===
                                'mengerjakan'
                            )

                                <span class="badge bg-yellow-lt">

                                    <i class="ti ti-pencil me-1"></i>

                                    Mengerjakan

                                </span>


                            @elseif(
                                $pengerjaan->status ===
                                'diblokir'
                            )

                                <div
                                    class="
                                        d-flex
                                        flex-column
                                        align-items-start
                                        gap-1
                                    "
                                >

                                    <span class="badge bg-danger-lt">

                                        <i class="ti ti-lock me-1"></i>

                                        Diblokir

                                    </span>


                                    <span class="text-secondary small">

                                        {{
                                            $pengerjaan
                                                ->jumlah_pelanggaran
                                        }}

                                        pelanggaran

                                    </span>

                                </div>


                            @elseif(
                                $pengerjaan->status ===
                                'selesai'
                            )

                                <span class="badge bg-success-lt">

                                    <i class="ti ti-circle-check me-1"></i>

                                    Selesai

                                </span>


                            @else

                                <span class="badge bg-secondary-lt">

                                    {{
                                        ucfirst(
                                            $pengerjaan->status
                                        )
                                    }}

                                </span>

                            @endif

                        </td>


                        {{-- MULAI --}}

                        <td>

                            {{
                                $pengerjaan
                                    ?->waktu_mulai
                                    ?->format(
                                        'd/m/Y H:i'
                                    )
                                ?? '-'
                            }}

                        </td>


                        {{-- SELESAI --}}

                        <td>

                            {{
                                $pengerjaan
                                    ?->waktu_selesai
                                    ?->format(
                                        'd/m/Y H:i'
                                    )
                                ?? '-'
                            }}

                        </td>


                        {{-- NILAI --}}

                        <td>

                            @if(
                                $pengerjaan &&
                                $pengerjaan->status ===
                                'selesai'
                            )

                                <span
                                    class="
                                        fw-bold
                                        text-primary
                                    "
                                >

                                    {{
                                        number_format(
                                            (float)
                                            $pengerjaan->nilai,
                                            2
                                        )
                                    }}

                                </span>

                            @else

                                <span class="text-secondary">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- AKSI --}}

                        <td>

                            <div class="d-flex gap-2 flex-wrap">

                                @if(
                                    $pengerjaan &&
                                    $pengerjaan->status ===
                                    'diblokir'
                                )

                                    <form
                                        action="{{
                                            route(
                                                'cbt.rekap.buka-blokir',
                                                [
                                                    'ujian' =>
                                                        $ujian,

                                                    'pengerjaan' =>
                                                        $pengerjaan,
                                                ]
                                            )
                                        }}"
                                        method="POST"
                                        onsubmit="
                                            return confirm(
                                                'Buka blokir peserta ini? Jumlah pelanggaran akan direset dan peserta dapat melanjutkan ujian selama waktu masih tersedia.'
                                            );
                                        "
                                    >

                                        @csrf

                                        @method('PATCH')


                                        <button
                                            type="submit"
                                            class="
                                                btn
                                                btn-sm
                                                btn-warning
                                            "
                                        >

                                            <i
                                                class="
                                                    ti
                                                    ti-lock-open
                                                    me-1
                                                "
                                            ></i>

                                            Buka Blokir

                                        </button>

                                    </form>


                                @elseif(
                                    $pengerjaan &&
                                    $pengerjaan->status ===
                                    'selesai'
                                )

                                    <a
                                        href="{{
                                            route(
                                                'cbt.rekap.peserta',
                                                [
                                                    'ujian' =>
                                                        $ujian,

                                                    'pengerjaan' =>
                                                        $pengerjaan,
                                                ]
                                            )
                                        }}"
                                        class="
                                            btn
                                            btn-sm
                                            btn-outline-primary
                                        "
                                    >

                                        <i class="ti ti-eye me-1"></i>

                                        Detail

                                    </a>


                                @else

                                    <span class="text-secondary">
                                        -
                                    </span>

                                @endif

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="
                                text-center
                                text-secondary
                                py-5
                            "
                        >

                            Tidak ada siswa pada kelas ini.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>



    {{-- ===================================================== --}}
    {{-- CARD MOBILE --}}
    {{-- ===================================================== --}}

    <div class="rekap-mobile-list">

        @forelse($siswas as $siswa)

            @php

                $pengerjaan =
                    $pengerjaanPerSiswa
                        ->get($siswa->id);

            @endphp


            <div class="rekap-mobile-card p-3">


                {{-- HEADER PESERTA --}}

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-start
                        gap-3
                        mb-3
                    "
                >

                    <div class="min-w-0">

                        <div class="fw-bold">
                            {{ $siswa->nama }}
                        </div>

                        <div class="text-secondary small mt-1">

                            NIS:

                            {{ $siswa->nis ?? '-' }}

                        </div>

                    </div>


                    <div class="flex-shrink-0">

                        @if(! $pengerjaan)

                            <span class="badge bg-secondary-lt">
                                Belum Mengerjakan
                            </span>


                        @elseif(
                            $pengerjaan->status ===
                            'mengerjakan'
                        )

                            <span class="badge bg-yellow-lt">

                                <i class="ti ti-pencil me-1"></i>

                                Mengerjakan

                            </span>


                        @elseif(
                            $pengerjaan->status ===
                            'diblokir'
                        )

                            <span class="badge bg-danger-lt">

                                <i class="ti ti-lock me-1"></i>

                                Diblokir

                            </span>


                        @elseif(
                            $pengerjaan->status ===
                            'selesai'
                        )

                            <span class="badge bg-success-lt">

                                <i class="ti ti-circle-check me-1"></i>

                                Selesai

                            </span>


                        @else

                            <span class="badge bg-secondary-lt">

                                {{
                                    ucfirst(
                                        $pengerjaan->status
                                    )
                                }}

                            </span>

                        @endif

                    </div>

                </div>



                {{-- DETAIL PESERTA --}}

                <div class="rekap-mobile-info">


                    <div class="rekap-mobile-label">
                        Mulai
                    </div>

                    <div class="rekap-mobile-value">

                        {{
                            $pengerjaan
                                ?->waktu_mulai
                                ?->format(
                                    'd/m/Y H:i'
                                )
                            ?? '-'
                        }}

                    </div>


                    <div class="rekap-mobile-label">
                        Selesai
                    </div>

                    <div class="rekap-mobile-value">

                        {{
                            $pengerjaan
                                ?->waktu_selesai
                                ?->format(
                                    'd/m/Y H:i'
                                )
                            ?? '-'
                        }}

                    </div>


                    <div class="rekap-mobile-label">
                        Nilai
                    </div>

                    <div
                        class="
                            rekap-mobile-value
                            fw-bold
                            {{
                                $pengerjaan &&
                                $pengerjaan->status === 'selesai'
                                    ? 'text-primary'
                                    : 'text-secondary'
                            }}
                        "
                    >

                        @if(
                            $pengerjaan &&
                            $pengerjaan->status ===
                            'selesai'
                        )

                            {{
                                number_format(
                                    (float)
                                    $pengerjaan->nilai,
                                    2
                                )
                            }}

                        @else

                            -

                        @endif

                    </div>


                    @if(
                        $pengerjaan &&
                        $pengerjaan->status ===
                        'diblokir'
                    )

                        <div class="rekap-mobile-label">
                            Pelanggaran
                        </div>

                        <div
                            class="
                                rekap-mobile-value
                                text-danger
                                fw-bold
                            "
                        >

                            {{
                                $pengerjaan
                                    ->jumlah_pelanggaran
                            }}

                            kali

                        </div>

                    @endif

                </div>



                {{-- AKSI MOBILE --}}

                @if(
                    $pengerjaan &&
                    in_array(
                        $pengerjaan->status,
                        [
                            'diblokir',
                            'selesai',
                        ]
                    )
                )

                    <div
                        class="
                            rekap-mobile-action
                            mt-3
                            pt-3
                            border-top
                        "
                    >

                        @if(
                            $pengerjaan->status ===
                            'diblokir'
                        )

                            <form
                                action="{{
                                    route(
                                        'cbt.rekap.buka-blokir',
                                        [
                                            'ujian' =>
                                                $ujian,

                                            'pengerjaan' =>
                                                $pengerjaan,
                                        ]
                                    )
                                }}"
                                method="POST"
                                onsubmit="
                                    return confirm(
                                        'Buka blokir peserta ini? Jumlah pelanggaran akan direset dan peserta dapat melanjutkan ujian selama waktu masih tersedia.'
                                    );
                                "
                            >

                                @csrf

                                @method('PATCH')


                                <button
                                    type="submit"
                                    class="
                                        btn
                                        btn-warning
                                        w-100
                                    "
                                >

                                    <i
                                        class="
                                            ti
                                            ti-lock-open
                                            me-1
                                        "
                                    ></i>

                                    Buka Blokir Peserta

                                </button>

                            </form>


                        @elseif(
                            $pengerjaan->status ===
                            'selesai'
                        )

                            <a
                                href="{{
                                    route(
                                        'cbt.rekap.peserta',
                                        [
                                            'ujian' =>
                                                $ujian,

                                            'pengerjaan' =>
                                                $pengerjaan,
                                        ]
                                    )
                                }}"
                                class="
                                    btn
                                    btn-outline-primary
                                    w-100
                                "
                            >

                                <i class="ti ti-eye me-1"></i>

                                Lihat Detail Hasil

                            </a>

                        @endif

                    </div>

                @endif

            </div>


        @empty

            <div
                class="
                    text-center
                    text-secondary
                    py-5
                    px-3
                "
            >

                Tidak ada siswa pada kelas ini.

            </div>

        @endforelse

    </div>

</div>

@endsection