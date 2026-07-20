@extends('layouts.app')

@section('title', 'Detail Sesi Absensi')

@section('content')

<div class="page-header d-print-none mb-4">
    <div class="row align-items-center g-3">

        <div class="col">
            <div class="page-pretitle">
                Tingkat {{ $sesi->tingkat }}
            </div>

            <h2 class="page-title">
                Absensi {{ ucfirst($sesi->jenis) }}
            </h2>

            <div class="text-secondary mt-1">
                {{ $sesi->tanggal->format('d/m/Y') }}
            </div>
        </div>

        <div class="col-auto">
            <div class="d-flex gap-2">

                <a
                    href="{{ route('absensi.sesi.index') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="ti ti-arrow-left me-1"></i>
                    Kembali
                </a>

                @if($sesi->status === 'aktif')

                    <form
                        action="{{ route('absensi.sesi.tutup', $sesi) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PATCH')

                        @if($sesi->status === 'aktif')

                            <button
                                type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalTutupSesi"
                            >
                                <i class="ti ti-lock me-1"></i>
                                Tutup Sesi
                            </button>

                        @endif
                    </form>

                @endif

            </div>
        </div>

    </div>
</div>

@if($sesi->status === 'aktif')

<div
    class="modal modal-blur fade"
    id="modalTutupSesi"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body text-center py-4">

                <span class="avatar avatar-xl bg-danger-lt mb-3">
                    <i class="ti ti-alert-triangle fs-1"></i>
                </span>

                <h3>
                    Tutup Sesi Absensi?
                </h3>

                <div class="text-secondary">
                    Sesi absensi akan ditutup dan siswa yang belum
                    melakukan absensi akan otomatis tercatat sebagai
                    <strong>Alpa</strong>.
                </div>

            </div>

            <div class="modal-footer">

                <div class="row w-100 g-2">

                    <div class="col">

                        <button
                            type="button"
                            class="btn w-100"
                            data-bs-dismiss="modal"
                        >
                            Batal
                        </button>

                    </div>

                    <div class="col">

                        <form
                            action="{{ route('absensi.sesi.tutup', $sesi) }}"
                            method="POST"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-danger w-100"
                            >
                                <i class="ti ti-lock me-1"></i>
                                Ya, Tutup
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

@endif


{{-- ALERT --}}
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


{{-- ========================================================= --}}
{{-- SCANNER QR SISWA --}}
{{-- ========================================================= --}}

@if($sesi->status === 'aktif')

<div class="card mb-4">

    <div class="card-header">

        <div>
            <h3 class="card-title">
                <i class="ti ti-scan me-2"></i>
                Scanner Absensi Siswa
            </h3>

            <div class="text-secondary small mt-1">
                Scan QR permanen milik siswa untuk mencatat kehadiran.
            </div>
        </div>

    </div>

    <div class="card-body">

        <div class="row g-4">

            {{-- CAMERA --}}
            <div class="col-lg-7">

                <div class="scanner-wrapper">

                    <div
                        id="reader"
                        class="scanner-reader"
                    ></div>

                </div>

                <div class="text-secondary small mt-2">
                    <i class="ti ti-camera me-1"></i>
                    Izinkan akses kamera ketika diminta oleh browser.
                </div>

            </div>


            {{-- HASIL SCAN --}}
            <div class="col-lg-5">

                <div class="scanner-wrapper">

                        <div
                            id="reader"
                            class="scanner-reader"
                        ></div>

                        {{-- POPUP HASIL SCAN --}}
                        <div
                            id="scan-popup"
                            class="scan-popup"
                        >
                            <div
                                id="scan-popup-icon"
                                class="scan-popup-icon"
                            >
                                <i class="ti ti-check"></i>
                            </div>

                            <div
                                id="scan-popup-title"
                                class="scan-popup-title"
                            >
                                Berhasil
                            </div>

                            <div
                                id="scan-popup-message"
                                class="scan-popup-message"
                            >
                                Absensi berhasil dicatat.
                            </div>
                        </div>

                    </div>


                <div class="card bg-light">

                    <div class="card-body">

                        <div class="text-secondary small mb-2">
                            Hasil Scan Terakhir
                        </div>

                        <div class="d-flex align-items-center">

                            <div
                                id="last-student-avatar"
                                class="
                                    avatar
                                    avatar-lg
                                    me-3
                                    bg-primary-lt
                                "
                            >
                                -
                            </div>

                            <div>

                                <h3
                                    id="last-student-name"
                                    class="mb-1"
                                >
                                    Belum ada siswa
                                </h3>

                                <div
                                    id="last-student-nis"
                                    class="text-secondary"
                                >
                                    -
                                </div>

                            </div>

                        </div>


                        <div class="mt-4">

                            <div class="text-secondary small mb-2">
                                Status Kehadiran
                            </div>

                            <span
                                id="last-student-status"
                                class="badge bg-secondary-lt"
                            >
                                Belum ada scan
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@else

<div class="alert alert-secondary mb-4">

    <div class="d-flex align-items-center">

        <i class="ti ti-lock me-2"></i>

        <div>
            <strong>Sesi telah ditutup.</strong>

            Scanner tidak dapat digunakan lagi pada sesi ini.
        </div>

    </div>

</div>

@endif



{{-- ========================================================= --}}
{{-- INFORMASI DAN STATISTIK --}}
{{-- ========================================================= --}}

<div class="row row-cards mb-4">

    {{-- INFORMASI SESI --}}
    <div class="col-lg-5">

        <div class="card h-100">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="ti ti-info-circle me-2"></i>
                    Informasi Sesi
                </h3>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Tingkat
                        </div>

                        <div class="fw-bold mt-1">
                            Kelas {{ $sesi->tingkat }}
                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Cakupan Absensi
                        </div>

                        <div class="fw-bold mt-1">
                            Seluruh Kelas Tingkat {{ $sesi->tingkat }}
                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Jenis Absensi
                        </div>

                        <div class="fw-bold mt-1">
                            {{ ucfirst($sesi->jenis) }}
                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Status Sesi
                        </div>

                        <div class="mt-1">

                            @if($sesi->status === 'aktif')

                                <span class="badge bg-success-lt">
                                    Aktif
                                </span>

                            @else

                                <span class="badge bg-secondary-lt">
                                    Selesai
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Tanggal
                        </div>

                        <div class="fw-bold mt-1">
                            {{ $sesi->tanggal->format('d/m/Y') }}
                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Waktu Absensi
                        </div>

                        <div class="fw-bold mt-1">

                            {{ $sesi->waktu_mulai }}

                            <span class="text-secondary">
                                -
                            </span>

                            {{ $sesi->waktu_selesai }}

                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Batas Terlambat
                        </div>

                        <div class="fw-bold mt-1">
                            {{ $sesi->batas_terlambat ?? '-' }}
                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Dibuka Oleh
                        </div>

                        <div class="fw-bold mt-1">
                            {{ $sesi->pembuka->name ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- STATISTIK --}}
    <div class="col-lg-7">

        <div class="card h-100">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="ti ti-chart-bar me-2"></i>
                    Statistik Kehadiran
                </h3>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- TOTAL --}}
                    <div class="col-sm-6 col-xl-3">

                        <div class="stat-card">

                            <div class="text-secondary small">
                                Total Siswa
                            </div>

                            <div
                                id="stat-total"
                                class="stat-number"
                            >
                                {{ $totalSiswa }}
                            </div>

                            <div class="text-secondary small">
                                siswa
                            </div>

                        </div>

                    </div>


                    {{-- HADIR --}}
                    <div class="col-sm-6 col-xl-3">

                        <div class="stat-card">

                            <div class="text-secondary small">
                                Hadir
                            </div>

                            <div
                                id="stat-hadir"
                                class="stat-number text-success"
                            >
                                {{ $hadir }}
                            </div>

                            <div class="text-secondary small">
                                siswa
                            </div>

                        </div>

                    </div>


                    {{-- TERLAMBAT --}}
                    <div class="col-sm-6 col-xl-3">

                        <div class="stat-card">

                            <div class="text-secondary small">
                                Terlambat
                            </div>

                            <div
                                id="stat-terlambat"
                                class="stat-number text-warning"
                            >
                                {{ $terlambat }}
                            </div>

                            <div class="text-secondary small">
                                siswa
                            </div>

                        </div>

                    </div>


                    {{-- BELUM --}}
                    <div class="col-sm-6 col-xl-3">

                        <div class="stat-card">

                            <div class="text-secondary small">
                                Belum Absen
                            </div>

                            <div
                                id="stat-belum"
                                class="stat-number text-secondary"
                            >
                                {{ $belumAbsen }}
                            </div>

                            <div class="text-secondary small">
                                siswa
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- DAFTAR KEHADIRAN --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-header">

        <div>

            <h3 class="card-title">

                <i class="ti ti-users me-2"></i>

                Daftar Kehadiran Siswa

            </h3>

            <div class="text-secondary small mt-1">

                Seluruh siswa aktif yang terdaftar
                pada tingkat ini.

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- DESKTOP / LAPTOP --}}
    {{-- ===================================================== --}}

    <div class="d-none d-md-block">

        <table class="table table-vcenter card-table mb-0">

            <thead>

                <tr>

                    <th>Siswa</th>

                    <th>NIS</th>

                    <th>Kelas</th>

                    <th>Waktu Absen</th>

                    <th>Metode</th>

                    <th>Status</th>

                    <th>Keterangan</th>

                    <th class="text-end">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($daftarSiswa as $siswa)

                    @php

                        $absensi =
                            $siswa->data_absensi;

                    @endphp


                    <tr
                        id="siswa-row-{{ $siswa->id }}"
                    >


                        {{-- SISWA --}}

                        <td>

                            <div
                                class="
                                    d-flex
                                    align-items-center
                                "
                            >

                                <span
                                    class="
                                        avatar
                                        avatar-sm
                                        me-2
                                        bg-primary-lt
                                    "
                                >

                                    {{
                                        strtoupper(
                                            substr(
                                                $siswa
                                                    ->user
                                                    ->name
                                                ?? '?',
                                                0,
                                                1
                                            )
                                        )
                                    }}

                                </span>


                                <div class="fw-bold">

                                    {{
                                        $siswa
                                            ->user
                                            ->name
                                        ?? '-'
                                    }}

                                </div>

                            </div>

                        </td>


                        {{-- NIS --}}

                        <td>

                            {{ $siswa->nis }}

                        </td>

                        {{-- KELAS --}}

                        <td>
                            {{ $siswa->kelas->nama ?? '-' }}
                        </td>


                        {{-- WAKTU --}}

                        <td class="absensi-waktu">

                            {{
                                $absensi
                                    ?->waktu_absen
                                    ?->format('H:i:s')
                                ?? '-'
                            }}

                        </td>


                        {{-- METODE --}}

                        <td class="absensi-metode">

                            @if($absensi)

                                <span
                                    class="
                                        badge
                                        bg-secondary-lt
                                    "
                                >

                                    {{
                                        strtoupper(
                                            $absensi->metode
                                        )
                                    }}

                                </span>

                            @else

                                <span class="text-secondary">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- STATUS --}}

                        <td class="absensi-status">

                            @if(!$absensi)

                                <span
                                    class="
                                        badge
                                        bg-secondary-lt
                                    "
                                >
                                    Belum Absen
                                </span>


                            @elseif(
                                $absensi->status ===
                                'hadir'
                            )

                                <span
                                    class="
                                        badge
                                        bg-success-lt
                                    "
                                >
                                    Hadir
                                </span>


                            @elseif(
                                $absensi->status ===
                                'terlambat'
                            )

                                <span
                                    class="
                                        badge
                                        bg-warning-lt
                                    "
                                >
                                    Terlambat
                                </span>


                            @elseif(
                                $absensi->status ===
                                'izin'
                            )

                                <span
                                    class="
                                        badge
                                        bg-blue-lt
                                    "
                                >
                                    Izin
                                </span>


                            @elseif(
                                $absensi->status ===
                                'sakit'
                            )

                                <span
                                    class="
                                        badge
                                        bg-azure-lt
                                    "
                                >
                                    Sakit
                                </span>


                            @elseif(
                                $absensi->status ===
                                'alpa'
                            )

                                <span
                                    class="
                                        badge
                                        bg-danger-lt
                                    "
                                >
                                    Alpa
                                </span>

                            @endif

                        </td>


                        {{-- KETERANGAN --}}

                        <td class="absensi-keterangan">

                            {{
                                $absensi
                                    ?->keterangan
                                ?? '-'
                            }}

                        </td>


                        {{-- AKSI --}}

                        <td class="text-end">

                            @if(
                                $sesi->status ===
                                'aktif'
                            )

                                <button
                                    type="button"
                                    class="
                                        btn
                                        btn-sm
                                        btn-outline-primary
                                    "
                                    data-bs-toggle="modal"
                                    data-bs-target="#statusModal{{ $siswa->id }}"
                                >

                                    <i
                                        class="
                                            ti
                                            ti-edit
                                            me-1
                                        "
                                    ></i>

                                    Ubah Status

                                </button>

                            @else

                                <span
                                    class="
                                        text-secondary
                                        small
                                    "
                                >

                                    <i
                                        class="
                                            ti
                                            ti-lock
                                            me-1
                                        "
                                    ></i>

                                    Sesi ditutup

                                </span>

                            @endif

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="
                                text-center
                                text-secondary
                                py-5
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-users-off
                                    fs-1
                                    d-block
                                    mb-2
                                "
                            ></i>

                            Belum ada siswa aktif yang
                            terdaftar di tingkat ini.

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

        @forelse($daftarSiswa as $siswa)

            @php

                $absensi =
                    $siswa->data_absensi;

            @endphp


            <div
                id="siswa-card-{{ $siswa->id }}"
                class="
                    p-3
                    border-bottom
                    absensi-mobile-card
                "
            >


                {{-- HEADER SISWA --}}

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-start
                        gap-3
                        mb-3
                    "
                >

                    <div
                        class="
                            d-flex
                            align-items-center
                            min-w-0
                        "
                    >

                        <span
                            class="
                                avatar
                                avatar-sm
                                me-2
                                bg-primary-lt
                                flex-shrink-0
                            "
                        >

                            {{
                                strtoupper(
                                    substr(
                                        $siswa
                                            ->user
                                            ->name
                                        ?? '?',
                                        0,
                                        1
                                    )
                                )
                            }}

                        </span>


                        <div class="min-w-0">

                            <div
                                class="
                                    fw-bold
                                    text-truncate
                                "
                            >

                                {{
                                    $siswa
                                        ->user
                                        ->name
                                    ?? '-'
                                }}

                            </div>

                            <div
                                class="
                                    text-secondary
                                    small
                                    mt-1
                                "
                            >

                                NIS:

                                {{ $siswa->nis }}

                            </div>

                            <div class="text-secondary small mt-1">

                                <i class="ti ti-school me-1"></i>

                                {{ $siswa->kelas->nama ?? '-' }}

                            </div>

                        </div>

                    </div>


                    {{-- STATUS --}}

                    <div
                        class="
                            absensi-status
                            flex-shrink-0
                        "
                    >

                        @if(!$absensi)

                            <span
                                class="
                                    badge
                                    bg-secondary-lt
                                "
                            >
                                Belum Absen
                            </span>


                        @elseif(
                            $absensi->status ===
                            'hadir'
                        )

                            <span
                                class="
                                    badge
                                    bg-success-lt
                                "
                            >
                                Hadir
                            </span>


                        @elseif(
                            $absensi->status ===
                            'terlambat'
                        )

                            <span
                                class="
                                    badge
                                    bg-warning-lt
                                "
                            >
                                Terlambat
                            </span>


                        @elseif(
                            $absensi->status ===
                            'izin'
                        )

                            <span
                                class="
                                    badge
                                    bg-blue-lt
                                "
                            >
                                Izin
                            </span>


                        @elseif(
                            $absensi->status ===
                            'sakit'
                        )

                            <span
                                class="
                                    badge
                                    bg-azure-lt
                                "
                            >
                                Sakit
                            </span>


                        @elseif(
                            $absensi->status ===
                            'alpa'
                        )

                            <span
                                class="
                                    badge
                                    bg-danger-lt
                                "
                            >
                                Alpa
                            </span>

                        @endif

                    </div>

                </div>



                {{-- DETAIL ABSENSI --}}

                <div
                    class="
                        row
                        g-3
                        mb-3
                    "
                >


                    {{-- WAKTU --}}

                    <div class="col-6">

                        <div
                            class="
                                text-secondary
                                small
                                mb-1
                            "
                        >
                            Waktu Absen
                        </div>


                        <div
                            class="
                                fw-bold
                                absensi-waktu
                            "
                        >

                            {{
                                $absensi
                                    ?->waktu_absen
                                    ?->format('H:i:s')
                                ?? '-'
                            }}

                        </div>

                    </div>



                    {{-- METODE --}}

                    <div class="col-6">

                        <div
                            class="
                                text-secondary
                                small
                                mb-1
                            "
                        >
                            Metode
                        </div>


                        <div class="absensi-metode">

                            @if($absensi)

                                <span
                                    class="
                                        badge
                                        bg-secondary-lt
                                    "
                                >

                                    {{
                                        strtoupper(
                                            $absensi->metode
                                        )
                                    }}

                                </span>

                            @else

                                <span class="text-secondary">
                                    -
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- KETERANGAN --}}

                <div
                    class="
                        pt-3
                        border-top
                    "
                >

                    <div
                        class="
                            text-secondary
                            small
                            mb-1
                        "
                    >
                        Keterangan
                    </div>


                    <div class="absensi-keterangan">

                        {{
                            $absensi
                                ?->keterangan
                            ?? '-'
                        }}

                    </div>

                </div>



                {{-- AKSI MOBILE --}}

                <div class="mt-3">

                    @if(
                        $sesi->status ===
                        'aktif'
                    )

                        <button
                            type="button"
                            class="
                                btn
                                btn-outline-primary
                                w-100
                            "
                            data-bs-toggle="modal"
                            data-bs-target="#statusModal{{ $siswa->id }}"
                        >

                            <i
                                class="
                                    ti
                                    ti-edit
                                    me-1
                                "
                            ></i>

                            Ubah Status Kehadiran

                        </button>

                    @else

                        <div
                            class="
                                text-secondary
                                small
                                text-center
                                py-2
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-lock
                                    me-1
                                "
                            ></i>

                            Sesi telah ditutup

                        </div>

                    @endif

                </div>

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

                <i
                    class="
                        ti
                        ti-users-off
                        fs-1
                        d-block
                        mb-2
                    "
                ></i>

                Belum ada siswa aktif yang
                terdaftar di kelas ini.

            </div>

        @endforelse

    </div>

</div>



{{-- ========================================================= --}}
{{-- MODAL EDIT STATUS --}}
{{-- ========================================================= --}}

@if($sesi->status === 'aktif')

    @foreach($daftarSiswa as $siswa)

        @php

            $absensi =
                $siswa->data_absensi;

        @endphp


        <div
            class="modal modal-blur fade"
            id="statusModal{{ $siswa->id }}"
            tabindex="-1"
            aria-hidden="true"
        >

            <div
                class="
                    modal-dialog
                    modal-dialog-centered
                "
                role="document"
            >

                <div class="modal-content">

                    <form
                        action="{{
                            route(
                                'absensi.sesi.status.update',
                                [
                                    $sesi,
                                    $siswa
                                ]
                            )
                        }}"
                        method="POST"
                    >

                        @csrf

                        @method('PATCH')


                        <div class="modal-header">

                            <h5 class="modal-title">
                                Ubah Status Absensi
                            </h5>


                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>

                        </div>



                        <div class="modal-body">


                            {{-- SISWA --}}

                            <div class="mb-4">

                                <div
                                    class="
                                        text-secondary
                                        small
                                    "
                                >
                                    Siswa
                                </div>


                                <div class="fw-bold mt-1">

                                    {{
                                        $siswa
                                            ->user
                                            ->name
                                        ?? '-'
                                    }}

                                </div>


                                <div class="text-secondary">

                                    NIS:

                                    {{ $siswa->nis }}

                                </div>

                            </div>



                            {{-- STATUS --}}

                            <div class="mb-3">

                                <label class="form-label">

                                    Status Kehadiran

                                </label>


                                <select
                                    name="status"
                                    class="form-select"
                                    required
                                >

                                    <option
                                        value="hadir"
                                        @selected(
                                            $absensi
                                                ?->status
                                            === 'hadir'
                                        )
                                    >
                                        Hadir
                                    </option>


                                    <option
                                        value="terlambat"
                                        @selected(
                                            $absensi
                                                ?->status
                                            === 'terlambat'
                                        )
                                    >
                                        Terlambat
                                    </option>


                                    <option
                                        value="izin"
                                        @selected(
                                            $absensi
                                                ?->status
                                            === 'izin'
                                        )
                                    >
                                        Izin
                                    </option>


                                    <option
                                        value="sakit"
                                        @selected(
                                            $absensi
                                                ?->status
                                            === 'sakit'
                                        )
                                    >
                                        Sakit
                                    </option>


                                    <option
                                        value="alpa"
                                        @selected(
                                            $absensi
                                                ?->status
                                            === 'alpa'
                                        )
                                    >
                                        Alpa
                                    </option>

                                </select>

                            </div>



                            {{-- KETERANGAN --}}

                            <div>

                                <label class="form-label">
                                    Keterangan
                                </label>


                                <textarea
                                    name="keterangan"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Tambahkan keterangan jika diperlukan..."
                                >{{ $absensi?->keterangan }}</textarea>

                            </div>

                        </div>



                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-link"
                                data-bs-dismiss="modal"
                            >
                                Batal
                            </button>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i
                                    class="
                                        ti
                                        ti-device-floppy
                                        me-1
                                    "
                                ></i>

                                Simpan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach

@endif

@endsection



{{-- ========================================================= --}}
{{-- SCRIPT SCANNER --}}
{{-- ========================================================= --}}

@push('scripts')

@if($sesi->status === 'aktif')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const readerElement =
            document.getElementById('reader');

        if (
            !readerElement
            || typeof Html5Qrcode === 'undefined'
        ) {

            console.error(
                'Html5Qrcode tidak tersedia.'
            );

            return;
        }


        const scanner =
            new Html5Qrcode('reader');


        let sedangMemproses = false;


        const scanPopup =
            document.getElementById('scan-popup');

        const scanPopupIcon =
            document.getElementById('scan-popup-icon');

        const scanPopupTitle =
            document.getElementById('scan-popup-title');

        const scanPopupMessage =
            document.getElementById('scan-popup-message');

        let popupTimeout;

        const studentName =
            document.getElementById(
                'last-student-name'
            );

        const studentNis =
            document.getElementById(
                'last-student-nis'
            );

        const studentStatus =
            document.getElementById(
                'last-student-status'
            );

        const studentAvatar =
            document.getElementById(
                'last-student-avatar'
            );


        function tampilkanHasil(
                message,
                type = 'info'
            ) {

                clearTimeout(popupTimeout);

                scanPopup.className =
                    'scan-popup show scan-popup-' + type;

                let icon = 'ti-info-circle';
                let title = 'Informasi';


                if (type === 'success') {

                    icon = 'ti-circle-check';
                    title = 'Absensi Berhasil';

                }

                else if (type === 'warning') {

                    icon = 'ti-alert-triangle';
                    title = 'Perhatian';

                }

                else if (type === 'danger') {

                    icon = 'ti-circle-x';
                    title = 'Absensi Gagal';

                }


                scanPopupIcon.innerHTML =
                    '<i class="ti ' + icon + '"></i>';

                scanPopupTitle.textContent =
                    title;

                scanPopupMessage.textContent =
                    message;


                popupTimeout = setTimeout(
                    function () {

                        scanPopup.classList.remove('show');

                    },

                    2500
                );

            }


        function escapeHtml(value) {

            const div =
                document.createElement('div');

            div.textContent =
                value ?? '';

            return div.innerHTML;

        }


        function updateSiswa(data) {

            if (!data) {
                return;
            }

            studentName.textContent =
                data.nama ?? '-';

            studentNis.textContent =
                data.nis
                    ? 'NIS: ' + data.nis
                    : '-';

            studentAvatar.textContent =
                data.nama
                    ? data.nama
                        .charAt(0)
                        .toUpperCase()
                    : '-';

            studentStatus.textContent =
                data.status
                    ? data.status.toUpperCase()
                    : '-';


            studentStatus.className =
                'badge';


            if (data.status === 'hadir') {

                studentStatus.classList.add(
                    'bg-success-lt'
                );

            }

            else if (
                data.status === 'terlambat'
            ) {

                studentStatus.classList.add(
                    'bg-warning-lt'
                );

            }

            else {

                studentStatus.classList.add(
                    'bg-secondary-lt'
                );

            }

        }


        function updateBarisAbsensi(siswa)
{
    if (!siswa?.id) {
        return;
    }


    const containers = [

        document.getElementById(
            'siswa-row-' + siswa.id
        ),

        document.getElementById(
            'siswa-card-' + siswa.id
        ),

    ];


    containers.forEach(
        function (container) {

            if (!container) {
                return;
            }


            const waktu =
                container.querySelector(
                    '.absensi-waktu'
                );


            const metode =
                container.querySelector(
                    '.absensi-metode'
                );


            const status =
                container.querySelector(
                    '.absensi-status'
                );


            if (waktu) {

                waktu.textContent =
                    siswa.waktu ?? '-';

            }


            if (metode) {

                metode.innerHTML =
                    '<span class="badge bg-secondary-lt">'
                    + 'QR'
                    + '</span>';

            }


            if (status) {

                let badgeClass =
                    'bg-secondary-lt';


                if (
                    siswa.status ===
                    'hadir'
                ) {

                    badgeClass =
                        'bg-success-lt';

                }


                else if (
                    siswa.status ===
                    'terlambat'
                ) {

                    badgeClass =
                        'bg-warning-lt';

                }


                status.innerHTML =
                    '<span class="badge '
                    + badgeClass
                    + '">'
                    + escapeHtml(
                        siswa.status
                            ?.toUpperCase()
                        ?? '-'
                    )
                    + '</span>';

            }

        }
    );
}


        function updateStatistik(status) {

            const hadirElement =
                document.getElementById(
                    'stat-hadir'
                );

            const terlambatElement =
                document.getElementById(
                    'stat-terlambat'
                );

            const belumElement =
                document.getElementById(
                    'stat-belum'
                );


            if (status === 'hadir') {

                hadirElement.textContent =
                    parseInt(
                        hadirElement.textContent
                    ) + 1;

            }


            if (
                status === 'terlambat'
            ) {

                terlambatElement.textContent =
                    parseInt(
                        terlambatElement.textContent
                    ) + 1;

            }


            const jumlahBelum =
                parseInt(
                    belumElement.textContent
                );


            if (jumlahBelum > 0) {

                belumElement.textContent =
                    jumlahBelum - 1;

            }

        }


        async function prosesScan(
            qrText
        ) {

            if (sedangMemproses) {
                return;
            }


            sedangMemproses = true;


            try {

                const response =
                    await fetch(

                        @json(
                            route(
                                'absensi.sesi.scan',
                                $sesi
                            )
                        ),

                        {

                            method: 'POST',


                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .content,

                            },


                            body:
                                JSON.stringify({

                                    qr:
                                        qrText,

                                }),

                        }

                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    tampilkanHasil(

                        data.message
                            ?? 'Absensi gagal.',

                        response.status === 409
                            ? 'warning'
                            : 'danger'

                    );


                    if (data.siswa) {

                        updateSiswa(
                            data.siswa
                        );

                    }


                    return;

                }


                tampilkanHasil(
                    data.message,
                    'success'
                );


                updateSiswa(
                    data.siswa
                );


                updateBarisAbsensi(
                    data.siswa
                );


                updateStatistik(
                    data.siswa.status
                );


            }

            catch (error) {

                console.error(
                    error
                );


                tampilkanHasil(

                    'Terjadi kesalahan saat memproses QR.',

                    'danger'

                );

            }

            finally {

                /*
                 * Jeda agar QR yang sama
                 * tidak diproses berulang.
                 */

                setTimeout(
                    function () {

                        sedangMemproses =
                            false;

                    },

                    2000

                );

            }

        }


        scanner.start(

            {
                facingMode:
                    'environment'
            },

            {
                fps: 10,

                qrbox: {
                    width: 250,
                    height: 250
                }
            },

            prosesScan,

            function () {

                /*
                 * Error frame scanner
                 * sengaja diabaikan.
                 */

            }

        )

        .catch(
            function (error) {

                console.error(
                    error
                );


                tampilkanHasil(

                    'Kamera tidak dapat dibuka. Pastikan izin kamera diberikan dan gunakan HTTPS saat mengakses dari perangkat lain.',

                    'danger'

                );

            }
        );

    }
);

</script>

@endif

@endpush



{{-- ========================================================= --}}
{{-- STYLE --}}
{{-- ========================================================= --}}

@push('styles')

<style>

.scanner-wrapper {
    position: relative;
}


.scan-popup {

    position: absolute;

    top: 50%;
    left: 50%;

    width: calc(100% - 40px);
    max-width: 360px;

    padding: 24px;

    text-align: center;

    background: rgba(255, 255, 255, 0.96);

    border-radius: 16px;

    box-shadow:
        0 15px 50px
        rgba(0, 0, 0, 0.25);

    transform:
        translate(-50%, -50%)
        scale(0.85);

    opacity: 0;

    visibility: hidden;

    z-index: 20;

    transition:
        opacity .2s ease,
        transform .2s ease,
        visibility .2s ease;

}


.scan-popup.show {

    opacity: 1;

    visibility: visible;

    transform:
        translate(-50%, -50%)
        scale(1);

}


.scan-popup-icon {

    display: flex;

    align-items: center;
    justify-content: center;

    width: 64px;
    height: 64px;

    margin: 0 auto 14px;

    border-radius: 50%;

    font-size: 34px;

}


.scan-popup-success
.scan-popup-icon {

    background: var(--tblr-success-lt);

    color: var(--tblr-success);

}


.scan-popup-warning
.scan-popup-icon {

    background: var(--tblr-warning-lt);

    color: var(--tblr-warning);

}


.scan-popup-danger
.scan-popup-icon {

    background: var(--tblr-danger-lt);

    color: var(--tblr-danger);

}


.scan-popup-info
.scan-popup-icon {

    background: var(--tblr-primary-lt);

    color: var(--tblr-primary);

}


.scan-popup-title {

    margin-bottom: 6px;

    font-size: 1.15rem;

    font-weight: 700;

    color: var(--tblr-body-color);

}


.scan-popup-message {

    font-size: .875rem;

    color: var(--tblr-secondary);

}

.scanner-wrapper {

    width: 100%;

    max-width: 650px;

    margin: 0 auto;

    overflow: hidden;

    border: 1px solid
        var(--tblr-border-color);

    border-radius: 12px;

    background: #000;

}


.scanner-reader {

    width: 100%;

    min-height: 350px;

}


#reader video {

    width: 100% !important;

    border-radius: 12px;

}


.stat-card {

    height: 100%;

    padding: 20px;

    border: 1px solid
        var(--tblr-border-color);

    border-radius: 12px;

    background:
        var(--tblr-bg-surface);

}


.stat-number {

    margin-top: 8px;

    font-size: 2rem;

    font-weight: 700;

    line-height: 1;

}


@media (
    max-width: 768px
) {

    .scanner-reader {

        min-height: 280px;

    }


    .page-header
    .col-auto {

        width: 100%;

    }


    .page-header
    .col-auto
    .d-flex {

        width: 100%;

        flex-wrap: wrap;

    }

}

</style>

@endpush
