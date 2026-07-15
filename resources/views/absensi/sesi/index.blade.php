@extends('layouts.app')

@section('title', 'Sesi Absensi')

@section('content')

<div class="page-header mb-4">
    <div class="row align-items-center">

        <div class="col">

            <div class="page-pretitle">
                Modul Absensi
            </div>

            <h2 class="page-title">
                Sesi Absensi
            </h2>

            <div class="text-secondary mt-1">
                Kelola sesi absensi pagi dan siang setiap kelas.
            </div>

        </div>

        <div class="col-auto">

            <a
                href="{{ route('absensi.sesi.create') }}"
                class="btn btn-primary"
            >
                <i class="ti ti-plus me-1"></i>
                Buka Sesi Absensi
            </a>

        </div>

    </div>
</div>


@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger">
        {{ session('error') }}
    </div>

@endif


{{-- SESI AKTIF --}}

<div class="mb-3">

    <h3 class="page-title">
        Sesi Aktif Hari Ini
    </h3>

</div>


<div class="row row-cards mb-4">

    @forelse($sesiAktif as $sesi)

        <div class="col-md-6 col-lg-4">

            <div class="card h-100">

                <div class="card-status-top bg-success"></div>

                <div class="card-body">

                    <div
                        class="d-flex
                               justify-content-between
                               align-items-start"
                    >

                        <div>

                            <div class="text-secondary">
                                {{ $sesi->kelas->nama }}
                            </div>

                            <h2 class="mb-1">

                                Absensi
                                {{ ucfirst($sesi->jenis) }}

                            </h2>

                        </div>


                        <span class="badge bg-success-lt">

                            Aktif

                        </span>

                    </div>


                    <div class="mt-4">

                        <div class="mb-2">

                            <i class="ti ti-clock me-1"></i>

                            {{ $sesi->waktu_mulai }}

                            -

                            {{ $sesi->waktu_selesai }}

                        </div>


                        <div class="text-secondary">

                            Dibuka oleh:

                            {{ $sesi->pembuka->name }}

                        </div>

                    </div>

                </div>


                <div class="card-footer">

                    <a
                        href="{{ route(
                            'absensi.sesi.show',
                            $sesi
                        ) }}"
                        class="btn btn-primary w-100"
                    >

                        Buka Sesi

                    </a>

                </div>

            </div>

        </div>


    @empty

        <div class="col-12">

            <div class="card">

                <div
                    class="card-body
                           text-center
                           text-secondary
                           py-5"
                >

                    Belum ada sesi absensi aktif hari ini.

                </div>

            </div>

        </div>

    @endforelse

</div>


{{-- RIWAYAT --}}

<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Riwayat Sesi Absensi
        </h3>

    </div>


    <div class="table-responsive">

        <table class="table table-vcenter card-table">

            <thead>

                <tr>

                    <th>Tanggal</th>

                    <th>Kelas</th>

                    <th>Jenis</th>

                    <th>Waktu</th>

                    <th>Hadir</th>

                    <th>Status</th>

                    <th>Aksi</th>

                </tr>

            </thead>


            <tbody>

            @forelse($riwayatSesi as $sesi)

                <tr>

                    <td>

                        {{ $sesi->tanggal->format('d/m/Y') }}

                    </td>


                    <td>

                        <div class="fw-bold">

                            {{ $sesi->kelas->nama }}

                        </div>

                        <div class="text-secondary small">

                            {{
                                $sesi
                                    ->kelas
                                    ->tahunAjaran
                                    ->nama
                            }}

                        </div>

                    </td>


                    <td>

                        @if($sesi->jenis === 'pagi')

                            <span class="badge bg-yellow-lt">
                                Pagi
                            </span>

                        @else

                            <span class="badge bg-blue-lt">
                                Siang
                            </span>

                        @endif

                    </td>


                    <td>

                        {{ $sesi->waktu_mulai }}

                        -

                        {{ $sesi->waktu_selesai }}

                    </td>


                    <td>

                        {{ $sesi->absensis_count }}

                    </td>


                    <td>

                        @if($sesi->status === 'aktif')

                            <span class="badge bg-success-lt">

                                Aktif

                            </span>

                        @else

                            <span class="badge bg-secondary-lt">

                                Selesai

                            </span>

                        @endif

                    </td>


                    <td>

                        <a
                            href="{{ route(
                                'absensi.sesi.show',
                                $sesi
                            ) }}"
                            class="btn btn-sm
                                   btn-outline-primary"
                        >

                            Detail

                        </a>

                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="7"
                        class="text-center
                               text-secondary
                               py-5"
                    >

                        Belum ada riwayat sesi absensi.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    @if($riwayatSesi->hasPages())

        <div class="card-footer">

            {{ $riwayatSesi->links() }}

        </div>

    @endif

</div>

@endsection