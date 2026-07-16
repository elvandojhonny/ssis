@extends('layouts.app')

@section('title', 'Absensi Saya')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <div class="page-pretitle">
                Modul Kehadiran
            </div>

            <h2 class="page-title">
                Kehadiran Saya
            </h2>

            <div class="text-secondary mt-1">

                {{ $user->siswa->kelas->nama }}

                —

                {{
                    $user
                        ->siswa
                        ->kelas
                        ->tahunAjaran
                        ->nama
                }}

            </div>

        </div>

    </div>

</div>


<div class="row row-cards">


    {{-- INFORMASI --}}

    <div class="col-lg-5">

        <div class="card mb-3">

            <div class="card-header">

                <h3 class="card-title">
                    Informasi Siswa
                </h3>

            </div>


            <div class="card-body">

                <div class="mb-3">

                    <div class="text-secondary">
                        Nama
                    </div>

                    <div class="fw-bold">
                        {{ $user->name }}
                    </div>

                </div>


                <div class="mb-3">

                    <div class="text-secondary">
                        NIS
                    </div>

                    <div class="fw-bold">
                        {{ $user->siswa->nis }}
                    </div>

                </div>


                <div>

                    <div class="text-secondary">
                        Kelas
                    </div>

                    <div class="fw-bold">

                        {{ $user->siswa->kelas->nama }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- REKAP ABSENSI SEMESTER --}}
{{-- ========================================================= --}}

<div class="card mt-4">

    <div class="card-header">

        <div class="row align-items-center w-100">

            <div class="col">

                <h3 class="card-title mb-1">
                    Rekap Kehadiran Semester
                </h3>

                <div class="text-secondary">
                    Ringkasan kehadiran selama tahun ajaran
                    {{
                        $user
                            ->siswa
                            ->kelas
                            ->tahunAjaran
                            ->nama
                    }}.
                </div>

            </div>


            <div class="col-auto">

                <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modal-riwayat-absensi"
                >

                    <i class="ti ti-history me-1"></i>

                    Lihat Detail

                </button>

            </div>

        </div>

    </div>


    <div class="card-body">

        <div class="row row-cards">


            {{-- HADIR --}}

            <div class="col-6 col-md">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="text-secondary mb-2">
                            Hadir
                        </div>

                        <div class="h1 mb-0 text-success">

                            {{ $statistik['hadir'] }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- TERLAMBAT --}}

            <div class="col-6 col-md">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="text-secondary mb-2">
                            Terlambat
                        </div>

                        <div class="h1 mb-0 text-warning">

                            {{ $statistik['terlambat'] }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- IZIN --}}

            <div class="col-6 col-md">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="text-secondary mb-2">
                            Izin
                        </div>

                        <div class="h1 mb-0">

                            {{ $statistik['izin'] }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- SAKIT --}}

            <div class="col-6 col-md">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="text-secondary mb-2">
                            Sakit
                        </div>

                        <div class="h1 mb-0">

                            {{ $statistik['sakit'] }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- ALPA --}}

            <div class="col-6 col-md">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="text-secondary mb-2">
                            Alpa
                        </div>

                        <div class="h1 mb-0 text-danger">

                            {{ $statistik['alpa'] }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- TOTAL --}}

        <div class="mt-4">

            <div class="d-flex justify-content-between">

                <span class="text-secondary">
                    Total Data Kehadiran
                </span>

                <strong>
                    {{ $statistik['total'] }}
                </strong>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL DETAIL RIWAYAT --}}
{{-- ========================================================= --}}

<div
    class="modal modal-blur fade"
    id="modal-riwayat-absensi"
    tabindex="-1"
    aria-hidden="true"
>

    <div
        class="
            modal-dialog
            modal-xl
            modal-dialog-centered
            modal-dialog-scrollable
        "
        role="document"
    >

        <div class="modal-content">


            {{-- HEADER --}}

            <div class="modal-header">

                <div>

                    <h2 class="modal-title">
                        Detail Riwayat kehadiran
                    </h2>

                    <div class="text-secondary mt-1">

                        {{ $user->name }}

                        ·

                        {{
                            $user
                                ->siswa
                                ->kelas
                                ->nama
                        }}

                    </div>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>

            </div>


            {{-- BODY --}}

            <div class="modal-body p-0">

                <div class="table-responsive">

                    <table
                        class="
                            table
                            table-vcenter
                            card-table
                        "
                    >

                        <thead>

                            <tr>

                                <th>
                                    Tanggal
                                </th>

                                <th>
                                    Sesi
                                </th>

                                <th>
                                    Waktu
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @forelse(
                            $riwayat
                            as $absensi
                        )

                            <tr>

                                {{-- TANGGAL --}}

                                <td>

                                    <div class="fw-bold">

                                        {{
                                            $absensi
                                                ->sesiAbsensi
                                                ->tanggal
                                                ->format(
                                                    'd/m/Y'
                                                )
                                        }}

                                    </div>

                                </td>


                                {{-- SESI --}}

                                <td>

                                    @if(
                                        $absensi
                                            ->sesiAbsensi
                                            ->jenis
                                        === 'pagi'
                                    )

                                        <span
                                            class="
                                                badge
                                                bg-yellow-lt
                                            "
                                        >

                                            <i
                                                class="
                                                    ti
                                                    ti-sun
                                                    me-1
                                                "
                                            ></i>

                                            Pagi

                                        </span>

                                    @else

                                        <span
                                            class="
                                                badge
                                                bg-blue-lt
                                            "
                                        >

                                            <i
                                                class="
                                                    ti
                                                    ti-sunset
                                                    me-1
                                                "
                                            ></i>

                                            Siang

                                        </span>

                                    @endif

                                </td>


                                {{-- WAKTU --}}

                                <td>

                                    {{
                                        (
                                            $absensi
                                                ->waktu_absen

                                            ??

                                            $absensi
                                                ->created_at
                                        )
                                        ?->format(
                                            'H:i:s'
                                        )

                                        ?? '-'
                                    }}

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @php

                                        $badgeStatus =
                                            match(
                                                $absensi
                                                    ->status
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
                                            bg-{{
                                                $badgeStatus
                                            }}-lt
                                        "
                                    >

                                        {{
                                            ucfirst(
                                                $absensi
                                                    ->status
                                            )
                                        }}

                                    </span>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="
                                        text-center
                                        text-secondary
                                        py-5
                                    "
                                >

                                    Belum ada riwayat
                                    kehadiran.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- FOOTER --}}

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >

                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>

@endsection