@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')

<div class="petugas-dashboard">
    {{-- ========================================================= --}}
    {{-- HEADER DASHBOARD --}}
    {{-- ========================================================= --}}

    <div class="page-header d-print-none mb-4">

        <div class="row align-items-center g-3">

            {{-- ================================================= --}}
            {{-- JUDUL --}}
            {{-- ================================================= --}}

            <div class="col">

                <div class="page-pretitle">
                    Perpustakaan
                </div>

                <h2 class="page-title">
                    Dashboard
                </h2>

            </div>


            {{-- ================================================= --}}
            {{-- TOMBOL PEMINJAMAN --}}
            {{-- ================================================= --}}

            <div class="col-auto ms-auto">

                <a
                    href="{{ route('perpustakaan.peminjaman.create') }}"
                    class="btn btn-primary"
                >

                    <i class="ti ti-qrcode me-2"></i>

                    Peminjaman Baru

                </a>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- INFORMASI RINGKAS --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div
                class="
                    d-flex
                    flex-column
                    flex-md-row
                    align-items-md-center
                    justify-content-between
                    gap-3
                "
            >

                {{-- ================================================= --}}
                {{-- INFORMASI --}}
                {{-- ================================================= --}}

                <div class="d-flex align-items-center">

                    <span
                        class="
                            avatar
                            bg-primary-lt
                            text-primary
                            me-3
                        "
                    >

                        <i class="ti ti-books"></i>

                    </span>

                    <div>

                        <div class="fw-semibold">
                            Perpustakaan
                        </div>

                        <div class="text-secondary small">
                            Ringkasan aktivitas perpustakaan hari ini.
                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TANGGAL --}}
                {{-- ================================================= --}}

                <div
                    class="
                        d-flex
                        align-items-center
                        text-secondary
                    "
                >

                    <i class="ti ti-calendar me-2"></i>

                    {{ now()->translatedFormat('d F Y') }}

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STATISTIK --}}
    {{-- ========================================================= --}}

    <div class="row row-cards mb-4">


        {{-- ===================================================== --}}
        {{-- TOTAL BUKU --}}
        {{-- ===================================================== --}}

        <div class="col-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-between
                        "
                    >

                        {{-- ========================================= --}}
                        {{-- DATA --}}
                        {{-- ========================================= --}}

                        <div>

                            <div class="text-secondary mb-2">
                                Total Buku
                            </div>

                            <div class="h1 mb-0">

                                {{
                                    number_format(
                                        $totalBuku ?? 0
                                    )
                                }}

                            </div>

                        </div>


                        {{-- ========================================= --}}
                        {{-- ICON --}}
                        {{-- ========================================= --}}

                        <span
                            class="
                                avatar
                                avatar-lg
                                bg-primary-lt
                                text-primary
                            "
                        >

                            <i class="ti ti-books fs-2"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- STOK TERSEDIA --}}
        {{-- ===================================================== --}}

        <div class="col-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-between
                        "
                    >

                        {{-- ========================================= --}}
                        {{-- DATA --}}
                        {{-- ========================================= --}}

                        <div>

                            <div class="text-secondary mb-2">
                                Stok Tersedia
                            </div>

                            <div class="h1 mb-0">

                                {{
                                    number_format(
                                        $totalStok ?? 0
                                    )
                                }}

                            </div>

                        </div>


                        {{-- ========================================= --}}
                        {{-- ICON --}}
                        {{-- ========================================= --}}

                        <span
                            class="
                                avatar
                                avatar-lg
                                bg-success-lt
                                text-success
                            "
                        >

                            <i class="ti ti-package fs-2"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- SEDANG DIPINJAM --}}
        {{-- ===================================================== --}}

        <div class="col-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-between
                        "
                    >

                        {{-- ========================================= --}}
                        {{-- DATA --}}
                        {{-- ========================================= --}}

                        <div>

                            <div class="text-secondary mb-2">
                                Sedang Dipinjam
                            </div>

                            <div class="h1 mb-0">

                                {{
                                    number_format(
                                        $dipinjam ?? 0
                                    )
                                }}

                            </div>

                        </div>


                        {{-- ========================================= --}}
                        {{-- ICON --}}
                        {{-- ========================================= --}}

                        <span
                            class="
                                avatar
                                avatar-lg
                                bg-warning-lt
                                text-warning
                            "
                        >

                            <i class="ti ti-book-upload fs-2"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- TERLAMBAT --}}
        {{-- ===================================================== --}}

        <div class="col-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-between
                        "
                    >

                        {{-- ========================================= --}}
                        {{-- DATA --}}
                        {{-- ========================================= --}}

                        <div>

                            <div class="text-secondary mb-2">
                                Terlambat
                            </div>

                            <div
                                class="
                                    h1
                                    mb-0
                                    {{
                                        ($terlambat ?? 0) > 0
                                            ? 'text-danger'
                                            : ''
                                    }}
                                "
                            >

                                {{
                                    number_format(
                                        $terlambat ?? 0
                                    )
                                }}

                            </div>

                        </div>


                        {{-- ========================================= --}}
                        {{-- ICON --}}
                        {{-- ========================================= --}}

                        <span
                            class="
                                avatar
                                avatar-lg
                                bg-danger-lt
                                text-danger
                            "
                        >

                            <i class="ti ti-clock-exclamation fs-2"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MENU CEPAT --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header">

            <h3 class="card-title">
                Menu Cepat
            </h3>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- ================================================= --}}
                {{-- PEMINJAMAN --}}
                {{-- ================================================= --}}

                <div class="col-12 col-md-4">

                    <a
                        href="{{ route('perpustakaan.peminjaman.create') }}"
                        class="
                            card
                            card-link
                            border
                            shadow-none
                            text-decoration-none
                            h-100
                        "
                    >

                        <div class="card-body">

                            <div class="d-flex align-items-center">

                                <span
                                    class="
                                        avatar
                                        bg-primary-lt
                                        text-primary
                                        me-3
                                    "
                                >

                                    <i class="ti ti-qrcode"></i>

                                </span>


                                <div class="flex-fill">

                                    <div
                                        class="
                                            fw-semibold
                                            text-body
                                        "
                                    >
                                        Peminjaman
                                    </div>

                                </div>


                                <i
                                    class="
                                        ti
                                        ti-chevron-right
                                        text-secondary
                                    "
                                ></i>

                            </div>

                        </div>

                    </a>

                </div>


                {{-- ================================================= --}}
                {{-- MASTER BUKU --}}
                {{-- ================================================= --}}

                <div class="col-12 col-md-4">

                    <a
                        href="{{ route('perpustakaan.buku.index') }}"
                        class="
                            card
                            card-link
                            border
                            shadow-none
                            text-decoration-none
                            h-100
                        "
                    >

                        <div class="card-body">

                            <div class="d-flex align-items-center">

                                <span
                                    class="
                                        avatar
                                        bg-azure-lt
                                        text-azure
                                        me-3
                                    "
                                >

                                    <i class="ti ti-books"></i>

                                </span>


                                <div class="flex-fill">

                                    <div
                                        class="
                                            fw-semibold
                                            text-body
                                        "
                                    >
                                        Master Buku
                                    </div>

                                </div>


                                <i
                                    class="
                                        ti
                                        ti-chevron-right
                                        text-secondary
                                    "
                                ></i>

                            </div>

                        </div>

                    </a>

                </div>


                {{-- ================================================= --}}
                {{-- TRANSAKSI --}}
                {{-- ================================================= --}}

                <div class="col-12 col-md-4">

                    <a
                        href="{{ route('perpustakaan.peminjaman.index') }}"
                        class="
                            card
                            card-link
                            border
                            shadow-none
                            text-decoration-none
                            h-100
                        "
                    >

                        <div class="card-body">

                            <div class="d-flex align-items-center">

                                <span
                                    class="
                                        avatar
                                        bg-success-lt
                                        text-success
                                        me-3
                                    "
                                >

                                    <i class="ti ti-list-details"></i>

                                </span>


                                <div class="flex-fill">

                                    <div
                                        class="
                                            fw-semibold
                                            text-body
                                        "
                                    >
                                        Transaksi
                                    </div>

                                </div>


                                <i
                                    class="
                                        ti
                                        ti-chevron-right
                                        text-secondary
                                    "
                                ></i>

                            </div>

                        </div>

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PART 2 LANJUT DI SINI --}}
    {{-- Jangan pasang @endsection dulu --}}
    {{-- ========================================================= --}}

        {{-- ========================================================= --}}
    {{-- PART 2 --}}
    {{-- TRANSAKSI TERBARU + BUKU SERING DIPINJAM --}}
    {{-- ========================================================= --}}


    {{-- ========================================================= --}}
    {{-- TRANSAKSI TERBARU --}}
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
                    w-100
                    gap-3
                "
            >

                <div class="d-flex align-items-center">

                    <span
                        class="
                            avatar
                            bg-primary-lt
                            text-primary
                            me-3
                        "
                    >

                        <i class="ti ti-receipt"></i>

                    </span>


                    <h3 class="card-title mb-0">
                        Transaksi Terbaru
                    </h3>

                </div>


                <a
                    href="{{ route('perpustakaan.peminjaman.index') }}"
                    class="btn btn-sm btn-outline-primary"
                >

                    Lihat Semua

                    <i class="ti ti-chevron-right ms-1"></i>

                </a>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- TABEL --}}
        {{-- ===================================================== --}}

        <div class="table-responsive">

            <table
                class="
                    table
                    table-vcenter
                    table-hover
                    card-table
                    mb-0
                "
            >

                {{-- ================================================= --}}
                {{-- HEADER TABEL --}}
                {{-- ================================================= --}}

                <thead>

                    <tr>

                        <th>
                            Kode
                        </th>

                        <th>
                            Peminjam
                        </th>

                        <th>
                            Kelas
                        </th>

                        <th>
                            Tanggal Pinjam
                        </th>

                        <th>
                            Jatuh Tempo
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="w-1">
                        </th>

                    </tr>

                </thead>


                {{-- ================================================= --}}
                {{-- DATA --}}
                {{-- ================================================= --}}

                <tbody>

                    @forelse(($transaksiTerbaru ?? collect()) as $item)

                        <tr>

                            {{-- ===================================== --}}
                            {{-- KODE PEMINJAMAN --}}
                            {{-- ===================================== --}}

                            <td>

                                <span class="fw-semibold">

                                    {{
                                        $item->kode_peminjaman
                                        ?? '-'
                                    }}

                                </span>

                            </td>


                            {{-- ===================================== --}}
                            {{-- SISWA --}}
                            {{-- ===================================== --}}

                            <td>

                                <div class="d-flex align-items-center">

                                    <span
                                        class="
                                            avatar
                                            avatar-sm
                                            bg-primary-lt
                                            text-primary
                                            me-2
                                        "
                                    >

                                        {{
                                            strtoupper(
                                                substr(
                                                    $item->siswa?->nama
                                                    ?? 'S',
                                                    0,
                                                    1
                                                )
                                            )
                                        }}

                                    </span>


                                    <div>

                                        <div class="fw-medium">

                                            {{
                                                $item->siswa?->nama
                                                ?? '-'
                                            }}

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- ===================================== --}}
                            {{-- KELAS --}}
                            {{-- ===================================== --}}

                            <td>

                                <span class="text-secondary">

                                    {{
                                        $item->siswa?->kelas?->nama
                                        ?? '-'
                                    }}

                                </span>

                            </td>


                            {{-- ===================================== --}}
                            {{-- TANGGAL PINJAM --}}
                            {{-- ===================================== --}}

                            <td>

                                @if($item->tanggal_pinjam)

                                    {{
                                        $item
                                            ->tanggal_pinjam
                                            ->format('d M Y')
                                    }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- ===================================== --}}
                            {{-- JATUH TEMPO --}}
                            {{-- ===================================== --}}

                            <td>

                                @if($item->tanggal_jatuh_tempo)

                                    {{
                                        $item
                                            ->tanggal_jatuh_tempo
                                            ->format('d M Y')
                                    }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- ===================================== --}}
                            {{-- STATUS --}}
                            {{-- ===================================== --}}

                            <td>

                                @if($item->status === 'terlambat')

                                    <span
                                        class="
                                            badge
                                            bg-danger-lt
                                            text-danger
                                        "
                                    >
                                        Terlambat
                                    </span>

                                @else

                                    <span
                                        class="
                                            badge
                                            bg-warning-lt
                                            text-warning
                                        "
                                    >
                                        Dipinjam
                                    </span>

                                @endif

                            </td>


                            {{-- ===================================== --}}
                            {{-- AKSI --}}
                            {{-- ===================================== --}}

                            <td>

                                <a
                                    href="{{
                                        route(
                                            'perpustakaan.peminjaman.show',
                                            $item
                                        )
                                    }}"
                                    class="
                                        btn
                                        btn-icon
                                        btn-sm
                                        btn-ghost-secondary
                                    "
                                    title="Detail"
                                >

                                    <i class="ti ti-chevron-right"></i>

                                </a>

                            </td>

                        </tr>


                    @empty

                        {{-- ========================================= --}}
                        {{-- KOSONG --}}
                        {{-- ========================================= --}}

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
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

                                    <i class="ti ti-receipt fs-2"></i>

                                </span>


                                <div class="fw-semibold">
                                    Belum ada transaksi
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- INFORMASI BAWAH --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">


        {{-- ===================================================== --}}
        {{-- BUKU SERING DIPINJAM --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-lg-7">

            <div class="card border-0 shadow-sm h-100">


                {{-- ================================================= --}}
                {{-- HEADER --}}
                {{-- ================================================= --}}

                <div class="card-header">

                    <div class="d-flex align-items-center">

                        <span
                            class="
                                avatar
                                bg-azure-lt
                                text-azure
                                me-3
                            "
                        >

                            <i class="ti ti-books"></i>

                        </span>


                        <h3 class="card-title mb-0">
                            Buku Sering Dipinjam
                        </h3>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- BODY --}}
                {{-- ================================================= --}}

                <div class="card-body py-2">

                    @forelse(($bukuTerpopuler ?? collect()) as $index => $buku)

                        <div
                            class="
                                d-flex
                                align-items-center
                                py-3
                                {{
                                    !$loop->last
                                        ? 'border-bottom'
                                        : ''
                                }}
                            "
                        >

                            {{-- ===================================== --}}
                            {{-- NOMOR --}}
                            {{-- ===================================== --}}

                            <span
                                class="
                                    avatar
                                    avatar-sm
                                    bg-primary-lt
                                    text-primary
                                    me-3
                                "
                            >

                                {{ $index + 1 }}

                            </span>


                            {{-- ===================================== --}}
                            {{-- INFORMASI BUKU --}}
                            {{-- ===================================== --}}

                            <div class="flex-fill">

                                <div class="fw-semibold">

                                    {{
                                        $buku->nama_buku
                                        ?? '-'
                                    }}

                                </div>

                            </div>


                            {{-- ===================================== --}}
                            {{-- JUMLAH DIPINJAM --}}
                            {{-- ===================================== --}}

                            <div class="ms-3">

                                <span
                                    class="
                                        badge
                                        bg-primary-lt
                                        text-primary
                                    "
                                >

                                    {{
                                        number_format(
                                            $buku->total_dipinjam
                                            ?? 0
                                        )
                                    }}

                                    kali

                                </span>

                            </div>

                        </div>


                    @empty

                        {{-- ========================================= --}}
                        {{-- KOSONG --}}
                        {{-- ========================================= --}}

                        <div class="text-center py-5">

                            <span
                                class="
                                    avatar
                                    avatar-lg
                                    bg-secondary-lt
                                    text-secondary
                                    mb-3
                                "
                            >

                                <i class="ti ti-books fs-2"></i>

                            </span>


                            <div class="fw-semibold">
                                Belum ada data
                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- KETERLAMBATAN --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-lg-5">

            <div class="card border-0 shadow-sm h-100">


                {{-- ================================================= --}}
                {{-- HEADER --}}
                {{-- ================================================= --}}

                <div class="card-header">

                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-between
                            w-100
                        "
                    >

                        <div class="d-flex align-items-center">

                            <span
                                class="
                                    avatar
                                    bg-danger-lt
                                    text-danger
                                    me-3
                                "
                            >

                                <i class="ti ti-clock-exclamation"></i>

                            </span>


                            <h3 class="card-title mb-0">
                                Keterlambatan
                            </h3>

                        </div>


                        @if(($terlambat ?? 0) > 0)

                            <span
                                class="
                                    badge
                                    bg-danger-lt
                                    text-danger
                                "
                            >

                                {{ $terlambat }}

                            </span>

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- BODY --}}
                {{-- ================================================= --}}

                <div class="card-body py-2">

                    @forelse(
                        ($peminjamanTerlambat ?? collect())
                        as $item
                    )

                        <div
                            class="
                                d-flex
                                align-items-center
                                py-3
                                {{
                                    !$loop->last
                                        ? 'border-bottom'
                                        : ''
                                }}
                            "
                        >

                            {{-- ===================================== --}}
                            {{-- ICON --}}
                            {{-- ===================================== --}}

                            <span
                                class="
                                    avatar
                                    bg-danger-lt
                                    text-danger
                                    me-3
                                "
                            >

                                <i class="ti ti-clock"></i>

                            </span>


                            {{-- ===================================== --}}
                            {{-- SISWA --}}
                            {{-- ===================================== --}}

                            <div class="flex-fill">

                                <div class="fw-semibold">

                                    {{
                                        $item->siswa?->nama
                                        ?? '-'
                                    }}

                                </div>


                                <div
                                    class="
                                        text-secondary
                                        small
                                    "
                                >

                                    {{
                                        $item->kode_peminjaman
                                        ?? '-'
                                    }}

                                </div>

                            </div>


                            {{-- ===================================== --}}
                            {{-- JATUH TEMPO --}}
                            {{-- ===================================== --}}

                            <div class="text-end ms-3">

                                @if($item->tanggal_jatuh_tempo)

                                    <div
                                        class="
                                            text-danger
                                            fw-medium
                                            small
                                        "
                                    >

                                        {{
                                            $item
                                                ->tanggal_jatuh_tempo
                                                ->format('d M Y')
                                        }}

                                    </div>

                                @endif

                            </div>

                        </div>


                    @empty

                        {{-- ========================================= --}}
                        {{-- TIDAK ADA KETERLAMBATAN --}}
                        {{-- ========================================= --}}

                        <div class="text-center py-5">

                            <span
                                class="
                                    avatar
                                    avatar-lg
                                    bg-success-lt
                                    text-success
                                    mb-3
                                "
                            >

                                <i class="ti ti-circle-check fs-2"></i>

                            </span>


                            <div class="fw-semibold">
                                Tidak ada keterlambatan
                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PART 3 LANJUT DI SINI --}}
    {{-- Jangan tutup container dan @endsection dulu --}}
    {{-- ========================================================= --}}

        {{-- ========================================================= --}}
    {{-- PART 3 --}}
    {{-- AKTIVITAS TERBARU --}}
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
                    w-100
                    gap-3
                "
            >

                {{-- ================================================= --}}
                {{-- JUDUL --}}
                {{-- ================================================= --}}

                <div class="d-flex align-items-center">

                    <span
                        class="
                            avatar
                            bg-success-lt
                            text-success
                            me-3
                        "
                    >

                        <i class="ti ti-history"></i>

                    </span>


                    <h3 class="card-title mb-0">
                        Aktivitas Terbaru
                    </h3>

                </div>


                {{-- ================================================= --}}
                {{-- LINK --}}
                {{-- ================================================= --}}

                <a
                    href="{{ route('perpustakaan.peminjaman.index') }}"
                    class="btn btn-sm btn-ghost-secondary"
                >

                    Riwayat

                    <i class="ti ti-chevron-right ms-1"></i>

                </a>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- BODY --}}
        {{-- ===================================================== --}}

        <div class="card-body py-2">

            @forelse(($aktivitas ?? collect()) as $item)

                <div
                    class="
                        d-flex
                        align-items-center
                        py-3
                        {{
                            !$loop->last
                                ? 'border-bottom'
                                : ''
                        }}
                    "
                >

                    {{-- ============================================= --}}
                    {{-- ICON AKTIVITAS --}}
                    {{-- ============================================= --}}

                    <span
                        class="
                            avatar
                            me-3

                            @if($item->status === 'dikembalikan')
                                bg-success-lt
                                text-success

                            @elseif($item->status === 'terlambat')
                                bg-danger-lt
                                text-danger

                            @else
                                bg-primary-lt
                                text-primary
                            @endif
                        "
                    >

                        @if($item->status === 'dikembalikan')

                            <i class="ti ti-book-download"></i>

                        @elseif($item->status === 'terlambat')

                            <i class="ti ti-clock-exclamation"></i>

                        @else

                            <i class="ti ti-book-upload"></i>

                        @endif

                    </span>


                    {{-- ============================================= --}}
                    {{-- INFORMASI --}}
                    {{-- ============================================= --}}

                    <div class="flex-fill">

                        {{-- ========================================= --}}
                        {{-- NAMA SISWA --}}
                        {{-- ========================================= --}}

                        <div class="fw-semibold">

                            {{
                                $item->siswa?->nama
                                ?? '-'
                            }}

                        </div>


                        {{-- ========================================= --}}
                        {{-- KODE + TANGGAL --}}
                        {{-- ========================================= --}}

                        <div
                            class="
                                d-flex
                                flex-wrap
                                align-items-center
                                gap-2
                                mt-1
                            "
                        >

                            <span class="text-secondary small">

                                {{
                                    $item->kode_peminjaman
                                    ?? '-'
                                }}

                            </span>


                            <span class="text-secondary small">
                                •
                            </span>


                            <span class="text-secondary small">

                                @if($item->created_at)

                                    {{
                                        $item
                                            ->created_at
                                            ->diffForHumans()
                                    }}

                                @else

                                    -

                                @endif

                            </span>

                        </div>

                    </div>


                    {{-- ============================================= --}}
                    {{-- STATUS --}}
                    {{-- ============================================= --}}

                    <div class="ms-3">

                        @if($item->status === 'dikembalikan')

                            <span
                                class="
                                    badge
                                    bg-success-lt
                                    text-success
                                "
                            >

                                Dikembalikan

                            </span>


                        @elseif($item->status === 'terlambat')

                            <span
                                class="
                                    badge
                                    bg-danger-lt
                                    text-danger
                                "
                            >

                                Terlambat

                            </span>


                        @else

                            <span
                                class="
                                    badge
                                    bg-warning-lt
                                    text-warning
                                "
                            >

                                Dipinjam

                            </span>

                        @endif

                    </div>


                    {{-- ============================================= --}}
                    {{-- DETAIL --}}
                    {{-- ============================================= --}}

                    <div class="ms-3">

                        <a
                            href="{{
                                route(
                                    'perpustakaan.peminjaman.show',
                                    $item
                                )
                            }}"
                            class="
                                btn
                                btn-icon
                                btn-sm
                                btn-ghost-secondary
                            "
                            title="Lihat Detail"
                        >

                            <i class="ti ti-chevron-right"></i>

                        </a>

                    </div>

                </div>


            @empty

                {{-- ================================================= --}}
                {{-- BELUM ADA AKTIVITAS --}}
                {{-- ================================================= --}}

                <div class="text-center py-5">

                    <span
                        class="
                            avatar
                            avatar-lg
                            bg-secondary-lt
                            text-secondary
                            mb-3
                        "
                    >

                        <i class="ti ti-history fs-2"></i>

                    </span>


                    <div class="fw-semibold">
                        Belum ada aktivitas
                    </div>

                </div>

            @endforelse

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- INFORMASI STOK --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">


        {{-- ===================================================== --}}
        {{-- TOTAL KOLEKSI --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <span
                            class="
                                avatar
                                bg-primary-lt
                                text-primary
                                me-3
                            "
                        >

                            <i class="ti ti-books"></i>

                        </span>


                        <div class="flex-fill">

                            <div class="text-secondary small">
                                Judul Buku
                            </div>

                            <div class="h2 mb-0 mt-1">

                                {{
                                    number_format(
                                        $totalBuku ?? 0
                                    )
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- STOK TERSEDIA --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <span
                            class="
                                avatar
                                bg-success-lt
                                text-success
                                me-3
                            "
                        >

                            <i class="ti ti-package"></i>

                        </span>


                        <div class="flex-fill">

                            <div class="text-secondary small">
                                Buku Tersedia
                            </div>

                            <div class="h2 mb-0 mt-1">

                                {{
                                    number_format(
                                        $totalStok ?? 0
                                    )
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- BUKU DI LUAR --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <span
                            class="
                                avatar
                                bg-warning-lt
                                text-warning
                                me-3
                            "
                        >

                            <i class="ti ti-book-upload"></i>

                        </span>


                        <div class="flex-fill">

                            <div class="text-secondary small">
                                Transaksi Aktif
                            </div>

                            <div class="h2 mb-0 mt-1">

                                {{
                                    number_format(
                                        ($dipinjam ?? 0)
                                        +
                                        ($terlambat ?? 0)
                                    )
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- AKSI BAWAH --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div
                class="
                    d-flex
                    flex-column
                    flex-md-row
                    align-items-md-center
                    justify-content-between
                    gap-3
                "
            >

                {{-- ================================================= --}}
                {{-- INFORMASI --}}
                {{-- ================================================= --}}

                <div>

                    <div class="fw-semibold">
                        Transaksi Perpustakaan
                    </div>

                    <div class="text-secondary small mt-1">
                        Kelola peminjaman dan pengembalian buku.
                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TOMBOL --}}
                {{-- ================================================= --}}

                <div
                    class="
                        d-flex
                        flex-wrap
                        gap-2
                    "
                >

                    {{-- ============================================= --}}
                    {{-- PEMINJAMAN --}}
                    {{-- ============================================= --}}

                    <a
                        href="{{ route('perpustakaan.peminjaman.create') }}"
                        class="btn btn-primary"
                    >

                        <i class="ti ti-qrcode me-2"></i>

                        Peminjaman Baru

                    </a>


                    {{-- ============================================= --}}
                    {{-- DAFTAR TRANSAKSI --}}
                    {{-- ============================================= --}}

                    <a
                        href="{{ route('perpustakaan.peminjaman.index') }}"
                        class="btn btn-outline-secondary"
                    >

                        <i class="ti ti-list-details me-2"></i>

                        Transaksi

                    </a>

                </div>

            </div>

        </div>

    </div>


</div>

@endsection

@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | Dashboard Petugas
    |--------------------------------------------------------------------------
    */

    .petugas-dashboard {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    .petugas-dashboard .page-header {
        width: 100%;
        margin-left: 0;
        margin-right: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Card
    |--------------------------------------------------------------------------
    */

    .petugas-dashboard .card {
        width: 100%;
    }


    /*
    |--------------------------------------------------------------------------
    | Row
    |--------------------------------------------------------------------------
    */

    .petugas-dashboard > .row {
        margin-left: 0;
        margin-right: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    .petugas-dashboard .table-responsive {
        width: 100%;
        overflow-x: auto;
    }


    .petugas-dashboard .table {
        width: 100%;
        margin-bottom: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    @media (max-width: 991.98px) {

        .petugas-dashboard {
            width: 100%;
        }

    }

</style>

@endpush