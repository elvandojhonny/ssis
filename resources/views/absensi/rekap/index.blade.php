@extends('layouts.app')

@section('title', 'Rekap Absensi')

@section('content')

<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">Modul Absensi</div>

            <h2 class="page-title">
                Rekap Absensi
            </h2>

            <div class="text-secondary mt-1">
                Rekap kehadiran siswa berdasarkan tingkat kelas dan periode.
            </div>
        </div>
    </div>
</div>


{{-- ========================================================= --}}
{{-- FILTER --}}
{{-- ========================================================= --}}

<div class="card mb-4">

    <div class="card-header">
        <h3 class="card-title">
            Filter Rekap Absensi
        </h3>
    </div>

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('absensi.rekap.index') }}"
        >

            <div class="row align-items-end">

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Tingkat Kelas
                    </label>

                    <select
                        name="tingkat"
                        class="form-select"
                    >

                        <option value="">
                            Semua Tingkat
                        </option>

                        @foreach($daftarTingkat as $item)

                            <option
                                value="{{ $item }}"
                                @selected($tingkat == $item)
                            >
                                Kelas {{ $item }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Bulan
                    </label>

                    @php
                        $daftarBulan = [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ];
                    @endphp

                    <select
                        name="bulan"
                        class="form-select"
                    >

                        @foreach($daftarBulan as $nomor => $nama)

                            <option
                                value="{{ $nomor }}"
                                @selected($bulan == $nomor)
                            >
                                {{ $nama }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Tahun
                    </label>

                    <select
                        name="tahun"
                        class="form-select"
                    >

                        @for(
                            $year = now()->year;
                            $year >= now()->year - 5;
                            $year--
                        )

                            <option
                                value="{{ $year }}"
                                @selected($tahun == $year)
                            >
                                {{ $year }}
                            </option>

                        @endfor

                    </select>

                </div>


                <div class="col-md-2 mb-3">

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        <i class="ti ti-filter me-1"></i>
                        Filter
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- REKAP PER TINGKAT --}}
{{-- ========================================================= --}}

@forelse(
    $rekapPerTingkat
    as $namaTingkat => $dataTingkat
)

<div class="card mb-5">

    {{-- HEADER --}}
    <div class="card-header">

        <div class="row align-items-center w-100 g-3">

            <div class="col">

                <div class="text-secondary">
                    Rekap Absensi
                </div>

                <h2 class="card-title mb-0">
                    Kelas {{ $namaTingkat }}
                </h2>

                <div class="text-secondary mt-1">
                    Periode
                    <strong>
                        {{ $daftarBulan[$bulan] }}
                        {{ $tahun }}
                    </strong>
                </div>

            </div>


            <div class="col-auto">

                <a
                    href="{{
                        route(
                            'absensi.rekap.export',
                            [
                                'tingkat' => $namaTingkat,
                                'bulan' => $bulan,
                                'tahun' => $tahun,
                            ]
                        )
                    }}"
                    class="btn btn-success"
                >
                    <i class="ti ti-file-spreadsheet me-1"></i>

                    Export Kelas {{ $namaTingkat }}
                </a>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- STATISTIK --}}
    {{-- ===================================================== --}}

    <div class="card-body">

        <div class="mb-3">

            <h3 class="card-title">
                Statistik Kehadiran
            </h3>

            <div class="text-secondary">
                Total status absensi pada periode yang dipilih.
            </div>

        </div>


        <div class="row row-cards">

            <div class="col-6 col-md-4 col-lg">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-secondary">
                            Hadir
                        </div>

                        <div class="h1 mb-0">
                            {{ $dataTingkat['statistik']['hadir'] }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-secondary">
                            Terlambat
                        </div>

                        <div class="h1 mb-0">
                            {{ $dataTingkat['statistik']['terlambat'] }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-secondary">
                            Izin
                        </div>

                        <div class="h1 mb-0">
                            {{ $dataTingkat['statistik']['izin'] }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-secondary">
                            Sakit
                        </div>

                        <div class="h1 mb-0">
                            {{ $dataTingkat['statistik']['sakit'] }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-6 col-md-4 col-lg">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-secondary">
                            Alpa
                        </div>

                        <div class="h1 mb-0">
                            {{ $dataTingkat['statistik']['alpa'] }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- INFORMASI PERIODE --}}

        <div class="row row-cards mt-2">

            <div class="col-md-4">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-secondary mb-1">
                            Hari Absensi Terisi
                        </div>

                        <div class="h2 mb-0">

                            {{
                                $dataTingkat[
                                    'rekap_harian'
                                ]->count()
                            }}

                            Hari

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-secondary mb-1">
                            Absensi Pertama
                        </div>

                        <div class="h3 mb-0">

                            @if(
                                $dataTingkat[
                                    'rekap_harian'
                                ]->isNotEmpty()
                            )

                                {{
                                    \Carbon\Carbon::parse(
                                        $dataTingkat[
                                            'rekap_harian'
                                        ]
                                        ->first()['tanggal']
                                    )
                                    ->locale('id')
                                    ->translatedFormat('d F Y')
                                }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="text-secondary mb-1">
                            Absensi Terakhir
                        </div>

                        <div class="h3 mb-0">

                            @if(
                                $dataTingkat[
                                    'rekap_harian'
                                ]->isNotEmpty()
                            )

                                {{
                                    \Carbon\Carbon::parse(
                                        $dataTingkat[
                                            'rekap_harian'
                                        ]
                                        ->last()['tanggal']
                                    )
                                    ->locale('id')
                                    ->translatedFormat('d F Y')
                                }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- REKAP KEHADIRAN PER SISWA --}}
    {{-- ===================================================== --}}

    <div class="card-body border-top">

        <div class="mb-4">

            <h3 class="card-title">
                Rekap Kehadiran Per Siswa
            </h3>

            <div class="text-secondary">

                Rekap dan riwayat kehadiran setiap siswa selama

                {{ $daftarBulan[$bulan] }}
                {{ $tahun }}.

            </div>

        </div>


        <div class="table-responsive ssis-mobile-table">

            <table class="table table-vcenter">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>

                        <th class="text-center">
                            Hadir
                        </th>

                        <th class="text-center">
                            Terlambat
                        </th>

                        <th class="text-center">
                            Izin
                        </th>

                        <th class="text-center">
                            Sakit
                        </th>

                        <th class="text-center">
                            Alpa
                        </th>

                        <th class="text-center">
                            Total
                        </th>

                        <th class="w-1">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse(
                    $dataTingkat['rekap_siswa']
                    as $rekap
                )

                    {{-- BARIS SISWA --}}

                    <tr>

                        <td data-label="No">

                            {{ $loop->iteration }}

                        </td>


                        <td data-label="Nama Siswa">

                            <div class="fw-bold">

                                {{
                                    $rekap['siswa']
                                        ->user
                                        ->name
                                }}

                            </div>

                        </td>


                        <td data-label="NIS">

                            {{
                                $rekap['siswa']
                                    ->nis
                            }}

                        </td>


                        <td data-label="Kelas">

                            <span class="badge bg-blue-lt">

                                {{
                                    $rekap['siswa']
                                        ->kelas
                                        ->nama
                                }}

                            </span>

                        </td>


                        <td
                            data-label="Hadir"
                            class="text-center"
                        >

                            <span class="badge bg-success-lt">

                                {{ $rekap['hadir'] }}

                            </span>

                        </td>


                        <td
                            data-label="Terlambat"
                            class="text-center"
                        >

                            <span class="badge bg-warning-lt">

                                {{ $rekap['terlambat'] }}

                            </span>

                        </td>


                        <td
                            data-label="Izin"
                            class="text-center"
                        >

                            <span class="badge bg-blue-lt">

                                {{ $rekap['izin'] }}

                            </span>

                        </td>


                        <td
                            data-label="Sakit"
                            class="text-center"
                        >

                            <span class="badge bg-azure-lt">

                                {{ $rekap['sakit'] }}

                            </span>

                        </td>


                        <td
                            data-label="Alpa"
                            class="text-center"
                        >

                            <span class="badge bg-danger-lt">

                                {{ $rekap['alpa'] }}

                            </span>

                        </td>


                        <td
                            data-label="Total"
                            class="text-center fw-bold"
                        >

                            {{ $rekap['total'] }}

                        </td>


                        <td data-label="Aksi">

                            <div
                                class="
                                    d-flex
                                    justify-content-end
                                    ssis-table-actions
                                "
                            >

                                <button
                                    type="button"
                                    class="
                                        btn
                                        btn-sm
                                        btn-outline-primary
                                    "
                                    data-bs-toggle="collapse"
                                    data-bs-target="#riwayat-{{ $namaTingkat }}-{{ $rekap['siswa']->id }}"
                                    aria-expanded="false"
                                >

                                    <i class="ti ti-history me-1"></i>

                                    Detail

                                </button>

                            </div>

                        </td>

                    </tr>


                    {{-- ===================================== --}}
                    {{-- DETAIL RIWAYAT SISWA --}}
                    {{-- ===================================== --}}

                    <tr
                        class="collapse"
                        id="riwayat-{{ $namaTingkat }}-{{ $rekap['siswa']->id }}"
                    >

                        <td colspan="11" class="p-0">

                            <div class="bg-light p-3 p-md-4">

                                <div class="mb-3">

                                    <h4 class="mb-1">

                                        Riwayat Absensi

                                        {{
                                            $rekap['siswa']
                                                ->user
                                                ->name
                                        }}

                                    </h4>

                                    <div class="text-secondary">

                                        {{
                                            $rekap['siswa']
                                                ->kelas
                                                ->nama
                                        }}

                                        ·

                                        {{ $daftarBulan[$bulan] }}

                                        {{ $tahun }}

                                    </div>

                                </div>


                                {{-- TABEL DETAIL --}}

                                <div
                                    class="
                                        table-responsive
                                        ssis-mobile-table
                                    "
                                >

                                    <table
                                        class="
                                            table
                                            table-sm
                                            table-vcenter
                                            bg-white
                                        "
                                    >

                                        <thead>

                                            <tr>

                                                <th>
                                                    Tanggal
                                                </th>

                                                <th>
                                                    Absensi Pagi
                                                </th>

                                                <th>
                                                    Waktu Pagi
                                                </th>

                                                <th>
                                                    Absensi Siang
                                                </th>

                                                <th>
                                                    Waktu Siang
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                        @forelse(
                                            $rekap['riwayat']
                                            as $riwayat
                                        )

                                            <tr>

                                                {{-- TANGGAL --}}

                                                <td data-label="Tanggal">

                                                    <div class="fw-bold">

                                                        {{
                                                            \Carbon\Carbon::parse(
                                                                $riwayat[
                                                                    'tanggal'
                                                                ]
                                                            )
                                                            ->locale('id')
                                                            ->translatedFormat(
                                                                'l, d F Y'
                                                            )
                                                        }}

                                                    </div>

                                                </td>


                                                {{-- STATUS PAGI --}}

                                                <td data-label="Absensi Pagi">

                                                    @if($riwayat['pagi'])

                                                        @php

                                                            $statusPagi =
                                                                $riwayat[
                                                                    'pagi'
                                                                ][
                                                                    'status'
                                                                ];

                                                            $badgePagi =
                                                                match(
                                                                    $statusPagi
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

                                                        @endphp


                                                        <span
                                                            class="
                                                                badge
                                                                bg-{{ $badgePagi }}-lt
                                                            "
                                                        >

                                                            {{
                                                                ucfirst(
                                                                    $statusPagi
                                                                )
                                                            }}

                                                        </span>

                                                    @else

                                                        <span
                                                            class="
                                                                badge
                                                                bg-secondary-lt
                                                            "
                                                        >
                                                            -
                                                        </span>

                                                    @endif

                                                </td>


                                                {{-- WAKTU PAGI --}}

                                                <td data-label="Waktu Pagi">

                                                    {{
                                                        $riwayat[
                                                            'pagi'
                                                        ][
                                                            'waktu'
                                                        ]
                                                        ?? '-'
                                                    }}

                                                </td>


                                                {{-- STATUS SIANG --}}

                                                <td data-label="Absensi Siang">

                                                    @if($riwayat['siang'])

                                                        @php

                                                            $statusSiang =
                                                                $riwayat[
                                                                    'siang'
                                                                ][
                                                                    'status'
                                                                ];

                                                            $badgeSiang =
                                                                match(
                                                                    $statusSiang
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

                                                        @endphp


                                                        <span
                                                            class="
                                                                badge
                                                                bg-{{ $badgeSiang }}-lt
                                                            "
                                                        >

                                                            {{
                                                                ucfirst(
                                                                    $statusSiang
                                                                )
                                                            }}

                                                        </span>

                                                    @else

                                                        <span
                                                            class="
                                                                badge
                                                                bg-secondary-lt
                                                            "
                                                        >
                                                            -
                                                        </span>

                                                    @endif

                                                </td>


                                                {{-- WAKTU SIANG --}}

                                                <td data-label="Waktu Siang">

                                                    {{
                                                        $riwayat[
                                                            'siang'
                                                        ][
                                                            'waktu'
                                                        ]
                                                        ?? '-'
                                                    }}

                                                </td>

                                            </tr>


                                        @empty

                                            <tr class="ssis-empty-row">

                                                <td
                                                    colspan="5"
                                                    class="
                                                        text-center
                                                        text-secondary
                                                        py-4
                                                    "
                                                >

                                                    Belum ada riwayat
                                                    absensi siswa.

                                                </td>

                                            </tr>

                                        @endforelse

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr class="ssis-empty-row">

                        <td
                            colspan="11"
                            class="
                                text-center
                                text-secondary
                                py-5
                            "
                        >

                            Tidak ada data siswa
                            pada periode ini.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


@empty

    {{-- ===================================================== --}}
    {{-- DATA KOSONG --}}
    {{-- ===================================================== --}}

    <div class="card">

        <div class="card-body text-center py-5">

            <div class="mb-3">

                <i
                    class="
                        ti
                        ti-calendar-off
                        fs-1
                        text-secondary
                    "
                ></i>

            </div>

            <h3>
                Belum Ada Data Absensi
            </h3>

            <div class="text-secondary">

                Tidak ditemukan data absensi untuk

                @if($tingkat)

                    Kelas {{ $tingkat }}

                @else

                    semua tingkat kelas

                @endif

                pada periode

                {{ $daftarBulan[$bulan] }}
                {{ $tahun }}.

            </div>

        </div>

    </div>

@endforelse

@endsection