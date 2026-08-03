@extends('layouts.app')

@section('title', 'Detail Peminjaman Buku')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="detail-header mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <span class="avatar avatar-sm bg-blue-lt text-blue">

                    <i class="ti ti-book-2"></i>

                </span>

                <h3 class="fw-bold mb-0">
                    Detail Peminjaman
                </h3>

            </div>

            <div class="text-secondary small">
                {{ $peminjaman->kode_peminjaman }}
            </div>

        </div>


        <div class="detail-header-action">

            <a
                href="{{ route('perpustakaan.peminjaman.index') }}"
                class="btn btn-outline-secondary"
            >

                <i class="ti ti-arrow-left me-1"></i>

                Kembali

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STATUS --}}
    {{-- ========================================================= --}}

    @php

        $statusClass = match ($peminjaman->status) {

            'dipinjam' =>
                'bg-blue-lt text-blue',

            'terlambat' =>
                'bg-red-lt text-red',

            'dikembalikan' =>
                'bg-green-lt text-green',

            default =>
                'bg-secondary-lt text-secondary',

        };

        $statusLabel = match ($peminjaman->status) {

            'dipinjam' =>
                'Sedang Dipinjam',

            'terlambat' =>
                'Terlambat',

            'dikembalikan' =>
                'Dikembalikan',

            default =>
                ucfirst($peminjaman->status),

        };

        $jumlahBuku =
            $peminjaman
                ->detailPeminjaman
                ->sum('jumlah');

    @endphp


    {{-- ========================================================= --}}
    {{-- RINGKASAN --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- STATUS --}}

        <div class="col-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-between
                            gap-3
                        "
                    >

                        <div>

                            <div class="text-secondary small mb-2">
                                Status
                            </div>

                            <span
                                class="
                                    badge
                                    {{ $statusClass }}
                                "
                            >
                                {{ $statusLabel }}
                            </span>

                        </div>


                        <span
                            class="
                                avatar
                                bg-blue-lt
                                text-blue
                            "
                        >

                            <i class="ti ti-activity"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- JUMLAH BUKU --}}

        <div class="col-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-between
                            gap-3
                        "
                    >

                        <div>

                            <div class="text-secondary small mb-1">
                                Jumlah Buku
                            </div>

                            <div class="h2 fw-bold mb-0">
                                {{ $jumlahBuku }}
                            </div>

                        </div>


                        <span
                            class="
                                avatar
                                bg-cyan-lt
                                text-cyan
                            "
                        >

                            <i class="ti ti-books"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- TANGGAL PINJAM --}}

        <div class="col-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-between
                            gap-3
                        "
                    >

                        <div>

                            <div class="text-secondary small mb-1">
                                Tanggal Pinjam
                            </div>

                            <div class="fw-bold">
                                {{
                                    $peminjaman
                                        ->tanggal_pinjam
                                        ?->format('d M Y')
                                    ?? '-'
                                }}
                            </div>

                        </div>


                        <span
                            class="
                                avatar
                                bg-azure-lt
                                text-azure
                            "
                        >

                            <i class="ti ti-calendar"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- JATUH TEMPO --}}

        <div class="col-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-between
                            gap-3
                        "
                    >

                        <div>

                            <div class="text-secondary small mb-1">
                                Jatuh Tempo
                            </div>

                            <div
                                class="
                                    fw-bold
                                    {{
                                        $peminjaman->status === 'terlambat'
                                            ? 'text-danger'
                                            : ''
                                    }}
                                "
                            >
                                {{
                                    $peminjaman
                                        ->tanggal_jatuh_tempo
                                        ?->format('d M Y')
                                    ?? '-'
                                }}
                            </div>

                        </div>


                        <span
                            class="
                                avatar
                                {{
                                    $peminjaman->status === 'terlambat'
                                        ? 'bg-red-lt text-red'
                                        : 'bg-yellow-lt text-yellow'
                                }}
                            "
                        >

                            <i class="ti ti-calendar-due"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- INFORMASI UTAMA --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        {{-- ===================================================== --}}
        {{-- INFORMASI TRANSAKSI --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header">

                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="
                                avatar
                                avatar-sm
                                bg-blue-lt
                                text-blue
                            "
                        >

                            <i class="ti ti-receipt"></i>

                        </span>

                        <div>

                            <h3 class="card-title mb-0">
                                Informasi Transaksi
                            </h3>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <div class="detail-list">

                        {{-- KODE --}}

                        <div class="detail-item">

                            <div class="detail-label">
                                Kode Peminjaman
                            </div>

                            <div class="detail-value">

                                <span class="fw-semibold">
                                    {{ $peminjaman->kode_peminjaman }}
                                </span>

                            </div>

                        </div>


                        {{-- TANGGAL PINJAM --}}

                        <div class="detail-item">

                            <div class="detail-label">
                                Tanggal Pinjam
                            </div>

                            <div class="detail-value">

                                {{
                                    $peminjaman
                                        ->tanggal_pinjam
                                        ?->format('d M Y')
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        {{-- JATUH TEMPO --}}

                        <div class="detail-item">

                            <div class="detail-label">
                                Jatuh Tempo
                            </div>

                            <div class="detail-value">

                                {{
                                    $peminjaman
                                        ->tanggal_jatuh_tempo
                                        ?->format('d M Y')
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        {{-- TANGGAL KEMBALI --}}

                        <div class="detail-item">

                            <div class="detail-label">
                                Tanggal Kembali
                            </div>

                            <div class="detail-value">

                                {{
                                    $peminjaman
                                        ->tanggal_kembali
                                        ?->format('d M Y')
                                    ?? 'Belum dikembalikan'
                                }}

                            </div>

                        </div>


                        {{-- STATUS --}}

                        <div class="detail-item">

                            <div class="detail-label">
                                Status
                            </div>

                            <div class="detail-value">

                                <span
                                    class="
                                        badge
                                        {{ $statusClass }}
                                    "
                                >
                                    {{ $statusLabel }}
                                </span>

                            </div>

                        </div>


                        {{-- PETUGAS --}}

                        <div class="detail-item">

                            <div class="detail-label">
                                Petugas
                            </div>

                            <div class="detail-value">

                                {{
                                    $peminjaman
                                        ->petugas
                                        ?->nama
                                    ?? '-'
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INFORMASI PEMINJAM --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header">

                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="
                                avatar
                                avatar-sm
                                bg-cyan-lt
                                text-cyan
                            "
                        >

                            <i class="ti ti-user"></i>

                        </span>

                        <div>

                            <h3 class="card-title mb-0">
                                Informasi Peminjam
                            </h3>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    @if($peminjaman->siswa)

                        {{-- ============================================= --}}
                        {{-- SISWA --}}
                        {{-- ============================================= --}}

                        <div class="detail-list">

                            <div class="detail-item">

                                <div class="detail-label">
                                    Nama
                                </div>

                                <div class="detail-value fw-semibold">
                                    {{ $peminjaman->siswa->nama }}
                                </div>

                            </div>


                            <div class="detail-item">

                                <div class="detail-label">
                                    Jenis
                                </div>

                                <div class="detail-value">

                                    <span
                                        class="
                                            badge
                                            bg-blue-lt
                                            text-blue
                                        "
                                    >
                                        Siswa
                                    </span>

                                </div>

                            </div>


                            <div class="detail-item">

                                <div class="detail-label">
                                    NIS
                                </div>

                                <div class="detail-value">
                                    {{ $peminjaman->siswa->nis ?? '-' }}
                                </div>

                            </div>


                            <div class="detail-item">

                                <div class="detail-label">
                                    Kelas
                                </div>

                                <div class="detail-value">

                                    @if($peminjaman->siswa->kelas)

                                        <span
                                            class="
                                                badge
                                                bg-cyan-lt
                                                text-cyan
                                            "
                                        >
                                            {{
                                                $peminjaman
                                                    ->siswa
                                                    ->kelas
                                                    ->tingkat
                                                ?? ''
                                            }}
                                        </span>

                                    @else

                                        -

                                    @endif

                                </div>

                            </div>


                            <div class="detail-item">

                                <div class="detail-label">
                                    Jumlah Buku
                                </div>

                                <div class="detail-value">

                                    <span
                                        class="
                                            badge
                                            bg-blue-lt
                                            text-blue
                                        "
                                    >
                                        {{ $jumlahBuku }} Buku
                                    </span>

                                </div>

                            </div>

                        </div>


                    @elseif($peminjaman->guru)

                        {{-- ============================================= --}}
                        {{-- GURU --}}
                        {{-- ============================================= --}}

                        <div class="detail-list">

                            <div class="detail-item">

                                <div class="detail-label">
                                    Nama
                                </div>

                                <div class="detail-value fw-semibold">
                                    {{ $peminjaman->guru->nama }}
                                </div>

                            </div>


                            <div class="detail-item">

                                <div class="detail-label">
                                    Jenis
                                </div>

                                <div class="detail-value">

                                    <span
                                        class="
                                            badge
                                            bg-purple-lt
                                            text-purple
                                        "
                                    >
                                        Guru
                                    </span>

                                </div>

                            </div>


                            <div class="detail-item">

                                <div class="detail-label">
                                    NIP
                                </div>

                                <div class="detail-value">
                                    {{ $peminjaman->guru->nip ?? '-' }}
                                </div>

                            </div>


                            <div class="detail-item">

                                <div class="detail-label">
                                    Jumlah Buku
                                </div>

                                <div class="detail-value">

                                    <span
                                        class="
                                            badge
                                            bg-blue-lt
                                            text-blue
                                        "
                                    >
                                        {{ $jumlahBuku }} Buku
                                    </span>

                                </div>

                            </div>

                        </div>


                    @else

                        {{-- ============================================= --}}
                        {{-- DATA TIDAK TERSEDIA --}}
                        {{-- ============================================= --}}

                        <div class="empty py-4">

                            <div class="empty-icon">

                                <span
                                    class="
                                        avatar
                                        avatar-lg
                                        bg-secondary-lt
                                        text-secondary
                                    "
                                >
                                    <i class="ti ti-user-question"></i>
                                </span>

                            </div>

                            <p class="empty-title mt-3">
                                Data peminjam tidak tersedia
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

        {{-- ========================================================= --}}
    {{-- DAFTAR BUKU --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}

        <div class="card-header">

            <div
                class="
                    d-flex
                    align-items-center
                    justify-content-between
                    gap-3
                    w-100
                "
            >

                <div class="d-flex align-items-center gap-2">

                    <span
                        class="
                            avatar
                            avatar-sm
                            bg-blue-lt
                            text-blue
                        "
                    >

                        <i class="ti ti-books"></i>

                    </span>

                    <div>

                        <h3 class="card-title mb-0">
                            Daftar Buku
                        </h3>

                        <div class="text-secondary small">
                            Buku pada transaksi ini
                        </div>

                    </div>

                </div>


                <span
                    class="
                        badge
                        bg-blue-lt
                        text-blue
                    "
                >
                    {{ $jumlahBuku }} Buku
                </span>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DESKTOP TABLE --}}
        {{-- ===================================================== --}}

        <div class="d-none d-lg-block">

            <div class="table-responsive">

                <table
                    class="
                        table
                        table-vcenter
                        card-table
                        mb-0
                    "
                >

                    <thead>

                        <tr>

                            <th style="width: 60px;">
                                No
                            </th>

                            <th>
                                Buku
                            </th>

                            <th style="width: 180px;">
                                Kelas
                            </th>

                            <th style="width: 130px;">
                                Jumlah
                            </th>

                            <th style="width: 180px;">
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $peminjaman->detailPeminjaman
                            as $detail
                        )

                            <tr>

                                {{-- NOMOR --}}

                                <td class="text-secondary">

                                    {{ $loop->iteration }}

                                </td>


                                {{-- BUKU --}}

                                <td>

                                    <div
                                        class="
                                            d-flex
                                            align-items-center
                                            gap-3
                                        "
                                    >

                                        <span
                                            class="
                                                avatar
                                                bg-azure-lt
                                                text-azure
                                            "
                                        >

                                            <i class="ti ti-book"></i>

                                        </span>


                                        <div>

                                            <div class="fw-semibold">

                                                {{
                                                    $detail
                                                        ->buku
                                                        ?->nama_buku
                                                    ?? 'Buku tidak tersedia'
                                                }}

                                            </div>

                                            @if($detail->buku)

                                                <div
                                                    class="
                                                        text-secondary
                                                        small
                                                        mt-1
                                                    "
                                                >

                                                    ID Buku:
                                                    {{ $detail->buku->id }}

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- KELAS --}}

                                <td>

                                    @if($detail->buku?->kelas)

                                        <span
                                            class="
                                                badge
                                                bg-cyan-lt
                                                text-cyan
                                            "
                                        >

                                            {{
                                                $detail
                                                    ->buku
                                                    ->kelas
                                                    ->tingkat
                                                ?? ''
                                            }}

                                           

                                        </span>

                                    @else

                                        <span class="text-secondary">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- JUMLAH --}}

                                <td>

                                    <span
                                        class="
                                            badge
                                            bg-blue-lt
                                            text-blue
                                        "
                                    >

                                        {{ $detail->jumlah }}

                                        Buku

                                    </span>

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if(
                                        $peminjaman->status
                                        ===
                                        'terlambat'
                                    )

                                        <span
                                            class="
                                                badge
                                                bg-red-lt
                                                text-red
                                            "
                                        >

                                            <i
                                                class="
                                                    ti
                                                    ti-alert-circle
                                                    me-1
                                                "
                                            ></i>

                                            Terlambat

                                        </span>


                                    @elseif(
                                        $peminjaman->status
                                        ===
                                        'dikembalikan'
                                    )

                                        <span
                                            class="
                                                badge
                                                bg-green-lt
                                                text-green
                                            "
                                        >

                                            <i
                                                class="
                                                    ti
                                                    ti-circle-check
                                                    me-1
                                                "
                                            ></i>

                                            Dikembalikan

                                        </span>


                                    @else

                                        <span
                                            class="
                                                badge
                                                bg-blue-lt
                                                text-blue
                                            "
                                        >

                                            <i
                                                class="
                                                    ti
                                                    ti-clock
                                                    me-1
                                                "
                                            ></i>

                                            Dipinjam

                                        </span>

                                    @endif

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="
                                        text-center
                                        py-5
                                    "
                                >

                                    <span
                                        class="
                                            avatar
                                            avatar-lg
                                            bg-secondary-lt
                                            text-secondary
                                            mb-3
                                        "
                                    >

                                        <i class="ti ti-book-off"></i>

                                    </span>

                                    <div class="fw-semibold">
                                        Tidak ada buku
                                    </div>

                                    <div
                                        class="
                                            text-secondary
                                            small
                                            mt-1
                                        "
                                    >
                                        Detail buku tidak tersedia.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- MOBILE --}}
        {{-- ===================================================== --}}

        <div class="d-lg-none">

            @forelse(
                $peminjaman->detailPeminjaman
                as $detail
            )

                <div class="detail-book-mobile">

                    {{-- ========================================= --}}
                    {{-- JUDUL --}}
                    {{-- ========================================= --}}

                    <div
                        class="
                            d-flex
                            align-items-start
                            gap-3
                            mb-3
                        "
                    >

                        <span
                            class="
                                avatar
                                bg-azure-lt
                                text-azure
                                flex-shrink-0
                            "
                        >

                            <i class="ti ti-book"></i>

                        </span>


                        <div class="flex-fill min-w-0">

                            <div
                                class="
                                    fw-semibold
                                    text-break
                                "
                            >

                                {{
                                    $detail
                                        ->buku
                                        ?->nama_buku
                                    ?? 'Buku tidak tersedia'
                                }}

                            </div>


                            @if($detail->buku)

                                <div
                                    class="
                                        text-secondary
                                        small
                                        mt-1
                                    "
                                >

                                    ID Buku:
                                    {{ $detail->buku->id }}

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- ========================================= --}}
                    {{-- DETAIL --}}
                    {{-- ========================================= --}}

                    <div class="mobile-book-info">

                        {{-- KELAS --}}

                        <div>

                            <div
                                class="
                                    text-secondary
                                    small
                                    mb-1
                                "
                            >
                                Kelas
                            </div>

                            @if($detail->buku?->kelas)

                                <span
                                    class="
                                        badge
                                        bg-cyan-lt
                                        text-cyan
                                    "
                                >

                                    {{
                                        $detail
                                            ->buku
                                            ->kelas
                                            ->tingkat
                                        ?? ''
                                    }}

                                    {{
                                        $detail
                                            ->buku
                                            ->kelas
                                            ->nama
                                        ?? ''
                                    }}

                                </span>

                            @else

                                <span class="fw-medium">
                                    -
                                </span>

                            @endif

                        </div>


                        {{-- JUMLAH --}}

                        <div>

                            <div
                                class="
                                    text-secondary
                                    small
                                    mb-1
                                "
                            >
                                Jumlah
                            </div>

                            <span
                                class="
                                    badge
                                    bg-blue-lt
                                    text-blue
                                "
                            >
                                {{ $detail->jumlah }} Buku
                            </span>

                        </div>

                    </div>


                    {{-- ========================================= --}}
                    {{-- STATUS --}}
                    {{-- ========================================= --}}

                    <div class="mt-3 pt-3 border-top">

                        <div
                            class="
                                text-secondary
                                small
                                mb-2
                            "
                        >
                            Status Buku
                        </div>


                        @if(
                            $peminjaman->status
                            ===
                            'terlambat'
                        )

                            <span
                                class="
                                    badge
                                    bg-red-lt
                                    text-red
                                "
                            >

                                <i
                                    class="
                                        ti
                                        ti-alert-circle
                                        me-1
                                    "
                                ></i>

                                Terlambat

                            </span>


                        @elseif(
                            $peminjaman->status
                            ===
                            'dikembalikan'
                        )

                            <span
                                class="
                                    badge
                                    bg-green-lt
                                    text-green
                                "
                            >

                                <i
                                    class="
                                        ti
                                        ti-circle-check
                                        me-1
                                    "
                                ></i>

                                Dikembalikan

                            </span>


                        @else

                            <span
                                class="
                                    badge
                                    bg-blue-lt
                                    text-blue
                                "
                            >

                                <i
                                    class="
                                        ti
                                        ti-clock
                                        me-1
                                    "
                                ></i>

                                Sedang Dipinjam

                            </span>

                        @endif

                    </div>

                </div>


            @empty

                <div class="text-center py-5 px-3">

                    <span
                        class="
                            avatar
                            avatar-lg
                            bg-secondary-lt
                            text-secondary
                            mb-3
                        "
                    >

                        <i class="ti ti-book-off"></i>

                    </span>

                    <div class="fw-semibold">
                        Tidak ada buku
                    </div>

                    <div
                        class="
                            text-secondary
                            small
                            mt-1
                        "
                    >
                        Detail buku tidak tersedia.
                    </div>

                </div>

            @endforelse

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TIMELINE & CATATAN --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        {{-- ===================================================== --}}
        {{-- TIMELINE --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header">

                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="
                                avatar
                                avatar-sm
                                bg-purple-lt
                                text-purple
                            "
                        >

                            <i class="ti ti-timeline"></i>

                        </span>

                        <h3 class="card-title mb-0">
                            Timeline Transaksi
                        </h3>

                    </div>

                </div>


                <div class="card-body">

                    <div class="transaction-timeline">

                        {{-- ===================================== --}}
                        {{-- DIPINJAM --}}
                        {{-- ===================================== --}}

                        <div class="timeline-item">

                            <div
                                class="
                                    timeline-icon
                                    bg-blue-lt
                                    text-blue
                                "
                            >

                                <i class="ti ti-book-upload"></i>

                            </div>


                            <div class="timeline-content">

                                <div class="fw-semibold">
                                    Buku Dipinjam
                                </div>

                                <div
                                    class="
                                        text-secondary
                                        small
                                        mt-1
                                    "
                                >

                                    {{
                                        $peminjaman
                                            ->tanggal_pinjam
                                            ?->format('d M Y')
                                        ?? '-'
                                    }}

                                </div>

                            </div>

                        </div>


                        {{-- ===================================== --}}
                        {{-- JATUH TEMPO --}}
                        {{-- ===================================== --}}

                        <div class="timeline-item">

                            <div
                                class="
                                    timeline-icon
                                    {{
                                        $peminjaman->status === 'terlambat'
                                            ? 'bg-red-lt text-red'
                                            : 'bg-yellow-lt text-yellow'
                                    }}
                                "
                            >

                                <i class="ti ti-calendar-due"></i>

                            </div>


                            <div class="timeline-content">

                                <div class="fw-semibold">
                                    Jatuh Tempo
                                </div>

                                <div
                                    class="
                                        small
                                        mt-1
                                        {{
                                            $peminjaman->status === 'terlambat'
                                                ? 'text-danger'
                                                : 'text-secondary'
                                        }}
                                    "
                                >

                                    {{
                                        $peminjaman
                                            ->tanggal_jatuh_tempo
                                            ?->format('d M Y')
                                        ?? '-'
                                    }}

                                </div>

                            </div>

                        </div>


                        {{-- ===================================== --}}
                        {{-- PENGEMBALIAN --}}
                        {{-- ===================================== --}}

                        <div class="timeline-item mb-0">

                            <div
                                class="
                                    timeline-icon
                                    {{
                                        $peminjaman->tanggal_kembali
                                            ? 'bg-green-lt text-green'
                                            : 'bg-secondary-lt text-secondary'
                                    }}
                                "
                            >

                                @if($peminjaman->tanggal_kembali)

                                    <i class="ti ti-circle-check"></i>

                                @else

                                    <i class="ti ti-hourglass"></i>

                                @endif

                            </div>


                            <div class="timeline-content">

                                <div class="fw-semibold">
                                    Pengembalian
                                </div>


                                @if($peminjaman->tanggal_kembali)

                                    <div
                                        class="
                                            text-secondary
                                            small
                                            mt-1
                                        "
                                    >

                                        {{
                                            $peminjaman
                                                ->tanggal_kembali
                                                ->format('d M Y')
                                        }}

                                    </div>

                                @else

                                    <div
                                        class="
                                            text-secondary
                                            small
                                            mt-1
                                        "
                                    >
                                        Belum dikembalikan
                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- CATATAN --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header">

                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="
                                avatar
                                avatar-sm
                                bg-yellow-lt
                                text-yellow
                            "
                        >

                            <i class="ti ti-note"></i>

                        </span>

                        <h3 class="card-title mb-0">
                            Catatan
                        </h3>

                    </div>

                </div>


                <div class="card-body">

                    @if($peminjaman->catatan)

                        <div
                            class="
                                detail-note
                                p-3
                                rounded
                            "
                        >

                            <div
                                class="
                                    d-flex
                                    align-items-start
                                    gap-2
                                "
                            >

                                <i
                                    class="
                                        ti
                                        ti-message
                                        text-secondary
                                        mt-1
                                    "
                                ></i>

                                <div class="text-break">

                                    {{ $peminjaman->catatan }}

                                </div>

                            </div>

                        </div>


                    @else

                        <div
                            class="
                                text-center
                                py-4
                            "
                        >

                            <span
                                class="
                                    avatar
                                    avatar-lg
                                    bg-secondary-lt
                                    text-secondary
                                    mb-3
                                "
                            >

                                <i class="ti ti-note-off"></i>

                            </span>

                            <div class="fw-semibold">
                                Tidak ada catatan
                            </div>

                            <div
                                class="
                                    text-secondary
                                    small
                                    mt-1
                                "
                            >
                                Tidak ada catatan pada transaksi ini.
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

        {{-- ========================================================= --}}
    {{-- PENUTUP CONTAINER --}}
    {{-- ========================================================= --}}

</div>

@endsection


{{-- ============================================================= --}}
{{-- STYLE --}}
{{-- ============================================================= --}}

@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    .detail-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }

    .detail-header-action {
        flex: 0 0 auto;
    }

    .detail-header-action .btn {
        width: auto;
        white-space: nowrap;
    }


    /*
    |--------------------------------------------------------------------------
    | CARD
    |--------------------------------------------------------------------------
    */

    .card {
        border-radius: 12px;
    }

    .card-header {
        min-height: 64px;
        padding: 1rem 1.25rem;
    }

    .card-body {
        padding: 1.25rem;
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL LIST
    |--------------------------------------------------------------------------
    */

    .detail-list {
        width: 100%;
    }

    .detail-item {
        display: grid;
        grid-template-columns: minmax(130px, 42%) 1fr;
        gap: 1rem;
        align-items: center;

        padding: .9rem 0;

        border-bottom:
            1px solid
            var(--tblr-border-color);
    }

    .detail-item:first-child {
        padding-top: 0;
    }

    .detail-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .detail-label {
        color: var(--tblr-secondary);
        font-size: .875rem;
    }

    .detail-value {
        min-width: 0;
        text-align: right;
        overflow-wrap: anywhere;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    .table > :not(caption) > * > * {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .table thead th {
        font-size: .75rem;
        font-weight: 600;
        color: var(--tblr-secondary);

        text-transform: uppercase;
        letter-spacing: .03em;

        white-space: nowrap;
    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE BOOK
    |--------------------------------------------------------------------------
    */

    .detail-book-mobile {
        padding: 1rem;
        border-bottom:
            1px solid
            var(--tblr-border-color);
    }

    .detail-book-mobile:last-child {
        border-bottom: 0;
    }

    .mobile-book-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .min-w-0 {
        min-width: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | TIMELINE
    |--------------------------------------------------------------------------
    */

    .transaction-timeline {
        position: relative;
    }

    .timeline-item {
        position: relative;

        display: flex;
        align-items: flex-start;

        gap: 1rem;

        margin-bottom: 1.5rem;
    }

    .timeline-item:not(:last-child)::after {
        content: '';

        position: absolute;

        top: 38px;
        left: 18px;

        width: 2px;
        height: calc(100% + .75rem);

        background:
            var(--tblr-border-color);
    }

    .timeline-icon {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;
        justify-content: center;

        flex: 0 0 38px;

        width: 38px;
        height: 38px;

        border-radius: 50%;

        font-size: 18px;
    }

    .timeline-content {
        min-width: 0;
        padding-top: .2rem;
    }


    /*
    |--------------------------------------------------------------------------
    | CATATAN
    |--------------------------------------------------------------------------
    */

    .detail-note {
        background:
            var(--tblr-bg-surface-secondary);

        border:
            1px solid
            var(--tblr-border-color);

        line-height: 1.6;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLET
    |--------------------------------------------------------------------------
    */

    @media (max-width: 991.98px) {

        .card-header {
            padding: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767.98px) {

        /*
        |--------------------------------------------------------------------------
        | CONTAINER
        |--------------------------------------------------------------------------
        */

        .container-fluid {
            padding-left: 12px;
            padding-right: 12px;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .detail-header {
            flex-direction: column;
            align-items: stretch;
        }

        .detail-header-action {
            width: 100%;
        }

        .detail-header-action .btn {
            width: 100%;
        }


        /*
        |--------------------------------------------------------------------------
        | CARD
        |--------------------------------------------------------------------------
        */

        .card {
            border-radius: 10px;
        }

        .card-header {
            min-height: auto;
            padding: .9rem 1rem;
        }

        .card-body {
            padding: 1rem;
        }


        /*
        |--------------------------------------------------------------------------
        | DETAIL
        |--------------------------------------------------------------------------
        */

        .detail-item {
            grid-template-columns: 1fr;
            gap: .35rem;

            padding: .85rem 0;
        }

        .detail-value {
            text-align: left;
        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE BOOK
        |--------------------------------------------------------------------------
        */

        .detail-book-mobile {
            padding: 1rem;
        }

        .mobile-book-info {
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
        }


        /*
        |--------------------------------------------------------------------------
        | TIMELINE
        |--------------------------------------------------------------------------
        */

        .timeline-item {
            gap: .8rem;
        }

        .timeline-icon {
            flex-basis: 36px;

            width: 36px;
            height: 36px;

            font-size: 17px;
        }

        .timeline-item:not(:last-child)::after {
            top: 36px;
            left: 17px;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE KECIL
    |--------------------------------------------------------------------------
    */

    @media (max-width: 420px) {

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN
        |--------------------------------------------------------------------------
        |
        | Bootstrap col-6 tetap membuat 2 card per baris.
        | Kita hanya mengecilkan padding supaya tidak sesak.
        |
        */

        .row.g-3 > .col-6 .card-body {
            padding: .85rem;
        }

        .row.g-3 > .col-6 .avatar {
            width: 32px;
            height: 32px;

            font-size: 16px;
        }

        .row.g-3 > .col-6 .h2 {
            font-size: 1.35rem;
        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE BOOK
        |--------------------------------------------------------------------------
        */

        .mobile-book-info {
            grid-template-columns: 1fr;
        }


        /*
        |--------------------------------------------------------------------------
        | BADGE
        |--------------------------------------------------------------------------
        */

        .badge {
            white-space: normal;
            text-align: left;
        }

    }

</style>

@endpush