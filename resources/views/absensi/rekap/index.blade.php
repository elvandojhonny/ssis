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
{{-- RINGKASAN KEHADIRAN --}}
{{-- ===================================================== --}}

<div class="card-body">

    <div class="mb-4">

        <h3 class="card-title mb-1">
            Ringkasan Kehadiran Bulan Ini
        </h3>

        <div class="text-secondary">
            Ringkasan seluruh catatan absensi siswa
            Kelas {{ $namaTingkat }} selama
            <strong>
                {{ $daftarBulan[$bulan] }} {{ $tahun }}
            </strong>.
            Setiap angka menunjukkan jumlah kejadian, bukan jumlah siswa.
        </div>

    </div>


    {{-- JUMLAH CATATAN KEHADIRAN --}}

    <div class="row row-cards">

        {{-- HADIR --}}
        <div class="col-6 col-md-4 col-lg">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <span class="avatar bg-success-lt">
                            <i class="ti ti-user-check"></i>
                        </span>

                        <div>

                            <div class="text-secondary small">
                                Kehadiran
                            </div>

                            <div class="h1 mb-0">
                                {{ $dataTingkat['statistik']['hadir'] }}
                            </div>

                            <div class="text-secondary small">
                                kali tercatat hadir
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- TERLAMBAT --}}
        <div class="col-6 col-md-4 col-lg">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <span class="avatar bg-warning-lt">
                            <i class="ti ti-clock-exclamation"></i>
                        </span>

                        <div>

                            <div class="text-secondary small">
                                Keterlambatan
                            </div>

                            <div class="h1 mb-0">
                                {{ $dataTingkat['statistik']['terlambat'] }}
                            </div>

                            <div class="text-secondary small">
                                kali tercatat terlambat
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- IZIN --}}
        <div class="col-6 col-md-4 col-lg">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <span class="avatar bg-blue-lt">
                            <i class="ti ti-file-description"></i>
                        </span>

                        <div>

                            <div class="text-secondary small">
                                Izin
                            </div>

                            <div class="h1 mb-0">
                                {{ $dataTingkat['statistik']['izin'] }}
                            </div>

                            <div class="text-secondary small">
                                kali tercatat izin
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- SAKIT --}}
        <div class="col-6 col-md-4 col-lg">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <span class="avatar bg-azure-lt">
                            <i class="ti ti-first-aid-kit"></i>
                        </span>

                        <div>

                            <div class="text-secondary small">
                                Sakit
                            </div>

                            <div class="h1 mb-0">
                                {{ $dataTingkat['statistik']['sakit'] }}
                            </div>

                            <div class="text-secondary small">
                                kali tercatat sakit
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ALPA --}}
        <div class="col-6 col-md-4 col-lg">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <span class="avatar bg-danger-lt">
                            <i class="ti ti-user-x"></i>
                        </span>

                        <div>

                            <div class="text-secondary small">
                                Tanpa Keterangan
                            </div>

                            <div class="h1 mb-0">
                                {{ $dataTingkat['statistik']['alpa'] }}
                            </div>

                            <div class="text-secondary small">
                                kali tercatat alpa
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- INFORMASI PELAKSANAAN ABSENSI --}}
    {{-- ===================================================== --}}

    <div class="mt-4 mb-3">

        <h3 class="card-title mb-1">
            Informasi Pelaksanaan Absensi
        </h3>

        <div class="text-secondary">
            Informasi hari pelaksanaan absensi pada periode yang dipilih.
        </div>

    </div>


    <div class="row row-cards">

        {{-- JUMLAH HARI --}}
        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <span class="avatar bg-primary-lt">
                            <i class="ti ti-calendar-check"></i>
                        </span>

                        <div>

                            <div class="text-secondary small">
                                Hari Absensi Dilaksanakan
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

            </div>

        </div>


        {{-- TANGGAL PERTAMA --}}
        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <span class="avatar bg-blue-lt">
                            <i class="ti ti-calendar-up"></i>
                        </span>

                        <div>

                            <div class="text-secondary small">
                                Tanggal Absensi Pertama
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

            </div>

        </div>


        {{-- TANGGAL TERAKHIR --}}
        <div class="col-md-4">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <span class="avatar bg-purple-lt">
                            <i class="ti ti-calendar-down"></i>
                        </span>

                        <div>

                            <div class="text-secondary small">
                                Tanggal Absensi Terakhir
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

    </div>

</div>


 {{-- ========================================================= --}}
{{-- REKAP KEHADIRAN PER SISWA --}}
{{-- ========================================================= --}}

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


    {{-- ===================================================== --}}
    {{-- DESKTOP / LAPTOP --}}
    {{-- ===================================================== --}}

    <div class="d-none d-md-block">

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

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>


                        <td>

                            <div class="fw-bold">

                                {{
                                    $rekap['siswa']
                                        ->user
                                        ->name
                                }}

                            </div>

                        </td>


                        <td>

                            {{
                                $rekap['siswa']
                                    ->nis
                            }}

                        </td>


                        <td>

                            <span class="badge bg-blue-lt">

                                {{
                                    $rekap['siswa']
                                        ->kelas
                                        ->nama
                                }}

                            </span>

                        </td>


                        <td class="text-center">

                            <span class="badge bg-success-lt">

                                {{ $rekap['hadir'] }}

                            </span>

                        </td>


                        <td class="text-center">

                            <span class="badge bg-warning-lt">

                                {{ $rekap['terlambat'] }}

                            </span>

                        </td>


                        <td class="text-center">

                            <span class="badge bg-blue-lt">

                                {{ $rekap['izin'] }}

                            </span>

                        </td>


                        <td class="text-center">

                            <span class="badge bg-azure-lt">

                                {{ $rekap['sakit'] }}

                            </span>

                        </td>


                        <td class="text-center">

                            <span class="badge bg-danger-lt">

                                {{ $rekap['alpa'] }}

                            </span>

                        </td>


                        <td class="text-center fw-bold">

                            {{ $rekap['total'] }}

                        </td>


                        <td>

                            <button
                                type="button"
                                class="
                                    btn
                                    btn-sm
                                    btn-outline-primary
                                "
                                data-bs-toggle="collapse"
                                data-bs-target="#riwayat-desktop-{{ $namaTingkat }}-{{ $rekap['siswa']->id }}"
                                aria-expanded="false"
                            >

                                <i class="ti ti-history me-1"></i>

                                Detail

                            </button>

                        </td>

                    </tr>


                    {{-- DETAIL DESKTOP --}}

                    <tr
                        class="collapse"
                        id="riwayat-desktop-{{ $namaTingkat }}-{{ $rekap['siswa']->id }}"
                    >

                        <td colspan="11" class="p-0">

                            <div class="bg-light p-4">

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


                                <table
                                    class="
                                        table
                                        table-sm
                                        table-vcenter
                                        bg-white
                                        mb-0
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

                                            @php

                                                $statusPagi =
                                                    $riwayat[
                                                        'pagi'
                                                    ][
                                                        'status'
                                                    ]
                                                    ?? null;


                                                $statusSiang =
                                                    $riwayat[
                                                        'siang'
                                                    ][
                                                        'status'
                                                    ]
                                                    ?? null;


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


                                            <tr>

                                                <td>

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


                                                <td>

                                                    <span
                                                        class="
                                                            badge
                                                            bg-{{ $badgePagi }}-lt
                                                        "
                                                    >

                                                        {{
                                                            $statusPagi
                                                                ? ucfirst(
                                                                    $statusPagi
                                                                )
                                                                : '-'
                                                        }}

                                                    </span>

                                                </td>


                                                <td>

                                                    {{
                                                        $riwayat[
                                                            'pagi'
                                                        ][
                                                            'waktu'
                                                        ]
                                                        ?? '-'
                                                    }}

                                                </td>


                                                <td>

                                                    <span
                                                        class="
                                                            badge
                                                            bg-{{ $badgeSiang }}-lt
                                                        "
                                                    >

                                                        {{
                                                            $statusSiang
                                                                ? ucfirst(
                                                                    $statusSiang
                                                                )
                                                                : '-'
                                                        }}

                                                    </span>

                                                </td>


                                                <td>

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

                                            <tr>

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

                        </td>

                    </tr>


                @empty

                    <tr>

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



    {{-- ===================================================== --}}
    {{-- MOBILE --}}
    {{-- ===================================================== --}}

    <div class="d-md-none">

        @forelse(
            $dataTingkat['rekap_siswa']
            as $rekap
        )

            <div class="border rounded mb-3 overflow-hidden">


                {{-- HEADER SISWA --}}

                <div class="p-3">

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

                                {{
                                    $rekap['siswa']
                                        ->user
                                        ->name
                                }}

                            </div>


                            <div class="text-secondary small mt-1">

                                NIS:

                                {{
                                    $rekap['siswa']
                                        ->nis
                                }}

                            </div>

                        </div>


                        <span
                            class="
                                badge
                                bg-blue-lt
                                flex-shrink-0
                            "
                        >

                            {{
                                $rekap['siswa']
                                    ->kelas
                                    ->nama
                            }}

                        </span>

                    </div>



                    {{-- STATISTIK SISWA --}}

                    <div class="row g-2 text-center">


                        <div class="col-4">

                            <div
                                class="
                                    border
                                    rounded
                                    p-2
                                    h-100
                                "
                            >

                                <div
                                    class="
                                        text-secondary
                                        small
                                    "
                                >
                                    Hadir
                                </div>

                                <div
                                    class="
                                        fw-bold
                                        text-success
                                        mt-1
                                    "
                                >
                                    {{ $rekap['hadir'] }}
                                </div>

                            </div>

                        </div>


                        <div class="col-4">

                            <div
                                class="
                                    border
                                    rounded
                                    p-2
                                    h-100
                                "
                            >

                                <div
                                    class="
                                        text-secondary
                                        small
                                    "
                                >
                                    Terlambat
                                </div>

                                <div
                                    class="
                                        fw-bold
                                        text-warning
                                        mt-1
                                    "
                                >
                                    {{ $rekap['terlambat'] }}
                                </div>

                            </div>

                        </div>


                        <div class="col-4">

                            <div
                                class="
                                    border
                                    rounded
                                    p-2
                                    h-100
                                "
                            >

                                <div
                                    class="
                                        text-secondary
                                        small
                                    "
                                >
                                    Izin
                                </div>

                                <div
                                    class="
                                        fw-bold
                                        text-primary
                                        mt-1
                                    "
                                >
                                    {{ $rekap['izin'] }}
                                </div>

                            </div>

                        </div>


                        <div class="col-4">

                            <div
                                class="
                                    border
                                    rounded
                                    p-2
                                    h-100
                                "
                            >

                                <div
                                    class="
                                        text-secondary
                                        small
                                    "
                                >
                                    Sakit
                                </div>

                                <div class="fw-bold mt-1">
                                    {{ $rekap['sakit'] }}
                                </div>

                            </div>

                        </div>


                        <div class="col-4">

                            <div
                                class="
                                    border
                                    rounded
                                    p-2
                                    h-100
                                "
                            >

                                <div
                                    class="
                                        text-secondary
                                        small
                                    "
                                >
                                    Alpa
                                </div>

                                <div
                                    class="
                                        fw-bold
                                        text-danger
                                        mt-1
                                    "
                                >
                                    {{ $rekap['alpa'] }}
                                </div>

                            </div>

                        </div>


                        <div class="col-4">

                            <div
                                class="
                                    border
                                    rounded
                                    p-2
                                    h-100
                                "
                            >

                                <div
                                    class="
                                        text-secondary
                                        small
                                    "
                                >
                                    Total
                                </div>

                                <div class="fw-bold mt-1">
                                    {{ $rekap['total'] }}
                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- TOMBOL DETAIL --}}

                    <button
                        type="button"
                        class="
                            btn
                            btn-outline-primary
                            w-100
                            mt-3
                        "
                        data-bs-toggle="collapse"
                        data-bs-target="#riwayat-mobile-{{ $namaTingkat }}-{{ $rekap['siswa']->id }}"
                        aria-expanded="false"
                    >

                        <i class="ti ti-history me-1"></i>

                        Lihat Riwayat Kehadiran

                    </button>

                </div>



                {{-- ========================================= --}}
                {{-- DETAIL MOBILE TANPA TABEL --}}
                {{-- ========================================= --}}

                <div
                    class="collapse"
                    id="riwayat-mobile-{{ $namaTingkat }}-{{ $rekap['siswa']->id }}"
                >

                    <div
                        class="
                            border-top
                            bg-light
                            p-3
                        "
                    >

                        <div class="fw-bold mb-3">
                            Riwayat Absensi
                        </div>


                        @forelse(
                            $rekap['riwayat']
                            as $riwayat
                        )

                            @php

                                $statusPagi =
                                    $riwayat[
                                        'pagi'
                                    ][
                                        'status'
                                    ]
                                    ?? null;


                                $statusSiang =
                                    $riwayat[
                                        'siang'
                                    ][
                                        'status'
                                    ]
                                    ?? null;


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


                            <div
                                class="
                                    bg-white
                                    border
                                    rounded
                                    p-3
                                    mb-2
                                "
                            >


                                {{-- TANGGAL --}}

                                <div
                                    class="
                                        fw-bold
                                        mb-3
                                    "
                                >

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



                                {{-- PAGI --}}

                                <div
                                    class="
                                        d-flex
                                        justify-content-between
                                        align-items-center
                                        gap-3
                                        pb-2
                                        mb-2
                                        border-bottom
                                    "
                                >

                                    <div>

                                        <div
                                            class="
                                                text-secondary
                                                small
                                            "
                                        >
                                            Absensi Pagi
                                        </div>

                                        <div class="mt-1">

                                            <span
                                                class="
                                                    badge
                                                    bg-{{ $badgePagi }}-lt
                                                "
                                            >

                                                {{
                                                    $statusPagi
                                                        ? ucfirst(
                                                            $statusPagi
                                                        )
                                                        : '-'
                                                }}

                                            </span>

                                        </div>

                                    </div>


                                    <div class="text-end">

                                        <div
                                            class="
                                                text-secondary
                                                small
                                            "
                                        >
                                            Waktu
                                        </div>

                                        <div class="fw-bold mt-1">

                                            {{
                                                $riwayat[
                                                    'pagi'
                                                ][
                                                    'waktu'
                                                ]
                                                ?? '-'
                                            }}

                                        </div>

                                    </div>

                                </div>



                                {{-- SIANG --}}

                                <div
                                    class="
                                        d-flex
                                        justify-content-between
                                        align-items-center
                                        gap-3
                                    "
                                >

                                    <div>

                                        <div
                                            class="
                                                text-secondary
                                                small
                                            "
                                        >
                                            Absensi Siang
                                        </div>

                                        <div class="mt-1">

                                            <span
                                                class="
                                                    badge
                                                    bg-{{ $badgeSiang }}-lt
                                                "
                                            >

                                                {{
                                                    $statusSiang
                                                        ? ucfirst(
                                                            $statusSiang
                                                        )
                                                        : '-'
                                                }}

                                            </span>

                                        </div>

                                    </div>


                                    <div class="text-end">

                                        <div
                                            class="
                                                text-secondary
                                                small
                                            "
                                        >
                                            Waktu
                                        </div>

                                        <div class="fw-bold mt-1">

                                            {{
                                                $riwayat[
                                                    'siang'
                                                ][
                                                    'waktu'
                                                ]
                                                ?? '-'
                                            }}

                                        </div>

                                    </div>

                                </div>

                            </div>


                        @empty

                            <div
                                class="
                                    text-center
                                    text-secondary
                                    py-4
                                "
                            >

                                Belum ada riwayat
                                absensi siswa.

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>


        @empty

            <div
                class="
                    text-center
                    text-secondary
                    py-5
                "
            >

                Tidak ada data siswa
                pada periode ini.

            </div>

        @endforelse

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