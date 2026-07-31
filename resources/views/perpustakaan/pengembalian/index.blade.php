@extends('layouts.app')

@section('title', 'Pengembalian Buku')

@section('content')

<style>

    /*
    |--------------------------------------------------------------------------
    | PENGEMBALIAN - RESPONSIVE
    |--------------------------------------------------------------------------
    */

    .pengembalian-page {
        width: 100%;
    }

    .pengembalian-mobile-list {
        display: none;
    }

    .pengembalian-mobile-card {
        border-bottom: 1px solid var(--tblr-border-color);
        padding: 16px;
    }

    .pengembalian-mobile-card:last-child {
        border-bottom: 0;
    }

    .pengembalian-mobile-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--tblr-body-color);
        line-height: 1.4;
    }

    .pengembalian-mobile-code {
        font-size: 12px;
        color: var(--tblr-secondary);
        margin-top: 2px;
    }

    .pengembalian-mobile-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 14px;
    }

    .pengembalian-mobile-info-item {
        border: 1px solid var(--tblr-border-color);
        border-radius: 8px;
        padding: 10px 12px;
        min-width: 0;
    }

    .pengembalian-mobile-info-label {
        font-size: 11px;
        color: var(--tblr-secondary);
        margin-bottom: 4px;
    }

    .pengembalian-mobile-info-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--tblr-body-color);
        overflow-wrap: anywhere;
    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767.98px) {

        .pengembalian-page {
            padding-bottom: 20px;
        }

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .pengembalian-page > .d-flex:first-child {
            align-items: flex-start !important;
        }

        .pengembalian-page .page-title {
            font-size: 20px;
        }

        .pengembalian-page > .d-flex:first-child > div:last-child {
            width: 100%;
        }

        .pengembalian-page > .d-flex:first-child > div:last-child .btn {
            flex: 1;
            white-space: nowrap;
        }


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        .pengembalian-page .row.g-3.mb-4 > .col-md-4 {
            width: 33.333333%;
            padding-left: 4px;
            padding-right: 4px;
        }

        .pengembalian-page .row.g-3.mb-4 {
            margin-left: -4px;
            margin-right: -4px;
            gap: 0 !important;
        }

        .pengembalian-page .row.g-3.mb-4 .card-body {
            padding: 12px 10px;
        }

        .pengembalian-page .row.g-3.mb-4 .card-body > .d-flex {
            display: block !important;
        }

        .pengembalian-page .row.g-3.mb-4 .text-secondary {
            font-size: 11px;
            margin-bottom: 5px !important;
            white-space: nowrap;
        }

        .pengembalian-page .row.g-3.mb-4 .h1 {
            font-size: 22px;
        }

        .pengembalian-page .row.g-3.mb-4 .avatar {
            display: none;
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        .pengembalian-page .card-body {
            padding: 16px;
        }


        /*
        |--------------------------------------------------------------------------
        | TABLE
        |--------------------------------------------------------------------------
        */

        .pengembalian-desktop-table {
            display: none !important;
        }

        .pengembalian-mobile-list {
            display: block;
        }


        /*
        |--------------------------------------------------------------------------
        | MODAL SCANNER
        |--------------------------------------------------------------------------
        */

        #modalScanQr .modal-dialog {
            margin: 0;
            width: 100%;
            max-width: none;
            min-height: 100%;
        }

        #modalScanQr .modal-content {
            min-height: 100vh;
            border-radius: 0;
            border: 0;
        }

        #modalScanQr .modal-header {
            padding: 14px 16px;
        }

        #modalScanQr .modal-body {
            padding: 16px;
        }

        #modalScanQr .modal-footer {
            padding: 12px 16px;
        }

        #modalScanQr #readerPengembalian {
            min-height: 240px !important;
        }

        #modalScanQr #pengembalianEmpty > .border {
            min-height: 180px !important;
        }

        #modalScanQr .row.g-4 {
            --tblr-gutter-y: 1rem;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | HP KECIL
    |--------------------------------------------------------------------------
    */

    @media (max-width: 420px) {

        .pengembalian-mobile-info {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .pengembalian-page > .d-flex:first-child > div:last-child {
            display: grid !important;
            grid-template-columns: 1fr 1fr;
        }

        .pengembalian-page > .d-flex:first-child > div:last-child .btn {
            width: 100%;
        }

    }

</style>

<div class="pengembalian-page">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h2 class="page-title mb-1">
                Pengembalian Buku
            </h2>

            <div class="text-secondary">
                Kelola buku yang sedang dipinjam siswa.
            </div>
        </div>

        <div class="d-flex gap-2">

    <a
        href="{{ route('perpustakaan.pengembalian.riwayat') }}"
        class="btn btn-outline-secondary">

        <i class="ti ti-history me-1"></i>
        Riwayat

    </a>

    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalScanQr">

        <i class="ti ti-scan me-1"></i>
        Scan QR Siswa

    </button>

</div>

    </div>


    {{-- ========================================================= --}}
    {{-- ALERT --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible mb-4" role="alert">

            <div class="d-flex">

                <div>
                    <i class="ti ti-circle-check me-2"></i>
                </div>

                <div>
                    {{ session('success') }}
                </div>

            </div>

            <a
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="close">
            </a>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible mb-4" role="alert">

            <div class="d-flex">

                <div>
                    <i class="ti ti-alert-circle me-2"></i>
                </div>

                <div>
                    {{ session('error') }}
                </div>

            </div>

            <a
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="close">
            </a>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATISTIK --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- SEDANG DIPINJAM --}}

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <div class="text-secondary mb-2">
                                Sedang Dipinjam
                            </div>

                            <div class="h1 mb-0">
                                {{ $totalDipinjam }}
                            </div>

                        </div>

                        <span class="avatar avatar-lg bg-azure-lt">

                            <i class="ti ti-book-upload fs-2"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- TERLAMBAT --}}

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <div class="text-secondary mb-2">
                                Terlambat
                            </div>

                            <div class="h1 mb-0 text-danger">
                                {{ $totalTerlambat }}
                            </div>

                        </div>

                        <span class="avatar avatar-lg bg-red-lt">

                            <i class="ti ti-clock-exclamation fs-2"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- DIKEMBALIKAN HARI INI --}}

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <div class="text-secondary mb-2">
                                Kembali Hari Ini
                            </div>

                            <div class="h1 mb-0">
                                {{ $totalDikembalikanHariIni }}
                            </div>

                        </div>

                        <span class="avatar avatar-lg bg-green-lt">

                            <i class="ti ti-circle-check fs-2"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTER --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('perpustakaan.pengembalian.index') }}">

                <div class="row g-3">

                    {{-- SEARCH --}}

                    <div class="col-lg-7">

                        <div class="input-icon">

                            <span class="input-icon-addon">

                                <i class="ti ti-search"></i>

                            </span>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Cari kode, nama siswa, atau NIS..."
                                value="{{ request('search') }}">

                        </div>

                    </div>


                    {{-- STATUS --}}

                    <div class="col-lg-3">

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                Semua Status
                            </option>

                            <option
                                value="dipinjam"
                                @selected(request('status') === 'dipinjam')>

                                Dipinjam

                            </option>

                            <option
                                value="terlambat"
                                @selected(request('status') === 'terlambat')>

                                Terlambat

                            </option>

                        </select>

                    </div>


                    {{-- BUTTON --}}

                    <div class="col-lg-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="ti ti-filter me-1"></i>
                            Filter

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
{{-- DAFTAR PEMINJAMAN AKTIF --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm">

    {{-- HEADER --}}

    <div class="card-header">

        <div class="d-flex align-items-center justify-content-between w-100">

            <div class="d-flex align-items-center gap-3">

                <span class="avatar bg-orange-lt">

                    <i class="ti ti-book-2"></i>

                </span>

                <div>

                    <h3 class="card-title mb-0">
                        Buku Belum Dikembalikan
                    </h3>

                </div>

            </div>


            <span class="badge bg-secondary-lt">

                {{ $peminjaman->total() }}

            </span>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- DESKTOP / TABLET --}}
    {{-- ===================================================== --}}

    <div class="pengembalian-desktop-table">

        <div class="table-responsive">

            <table class="table table-vcenter card-table mb-0">

                <thead>

                    <tr>

                        <th>Kode</th>

                        <th>Siswa</th>

                        <th>Kelas</th>

                        <th>Buku</th>

                        <th>Jatuh Tempo</th>

                        <th>Status</th>

                        <th class="text-end">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($peminjaman as $item)

                        @php

                            $jumlahBuku =
                                $item
                                    ->detailPeminjaman
                                    ->sum('jumlah');

                        @endphp


                        <tr>

                            {{-- KODE --}}

                            <td>

                                <span class="fw-semibold">

                                    {{ $item->kode_peminjaman }}

                                </span>

                            </td>


                            {{-- SISWA --}}

                            <td>

                                <div class="d-flex align-items-center gap-2">

                                    <span class="avatar avatar-sm bg-blue-lt">

                                        {{
                                            strtoupper(
                                                substr(
                                                    $item->siswa?->nama ?? 'S',
                                                    0,
                                                    1
                                                )
                                            )
                                        }}

                                    </span>


                                    <div>

                                        <div class="fw-medium">

                                            {{ $item->siswa?->nama ?? '-' }}

                                        </div>

                                        <div class="text-secondary small">

                                            {{ $item->siswa?->nis ?? '-' }}

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- KELAS --}}

                            <td>

                                {{
                                    $item->siswa?->kelas?->nama_kelas
                                    ??
                                    $item->siswa?->kelas?->nama
                                    ??
                                    '-'
                                }}

                            </td>


                            {{-- BUKU --}}

                            <td>

                                <span class="badge bg-azure-lt">

                                    {{ $jumlahBuku }} Buku

                                </span>

                            </td>


                            {{-- JATUH TEMPO --}}

                            <td>

                                {{
                                    optional(
                                        $item->tanggal_jatuh_tempo
                                    )->format('d M Y')
                                }}

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if($item->status === 'terlambat')

                                    <span class="badge bg-red-lt">

                                        <i class="ti ti-clock me-1"></i>

                                        Terlambat

                                    </span>

                                @else

                                    <span class="badge bg-orange-lt">

                                        Dipinjam

                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}

                            <td class="text-end">

                                <a
                                    href="{{
                                        route(
                                            'perpustakaan.pengembalian.show',
                                            $item
                                        )
                                    }}"
                                    class="btn btn-sm btn-outline-primary">

                                    Proses

                                    <i class="ti ti-chevron-right ms-1"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5">

                                <span class="avatar avatar-lg bg-green-lt mb-3">

                                    <i class="ti ti-circle-check fs-2"></i>

                                </span>

                                <div class="fw-semibold">
                                    Tidak ada peminjaman aktif
                                </div>

                                <div class="text-secondary mt-1">
                                    Semua buku telah dikembalikan.
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

    <div class="pengembalian-mobile-list">

        @forelse($peminjaman as $item)

            @php

                $jumlahBuku =
                    $item
                        ->detailPeminjaman
                        ->sum('jumlah');

                $namaKelas =
                    $item->siswa?->kelas?->nama_kelas
                    ??
                    $item->siswa?->kelas?->nama
                    ??
                    '-';

            @endphp


            <div class="pengembalian-mobile-card">


                {{-- SISWA + STATUS --}}

                <div class="d-flex align-items-start">

                    <span
                        class="avatar bg-blue-lt me-3 flex-shrink-0">

                        {{
                            strtoupper(
                                substr(
                                    $item->siswa?->nama ?? 'S',
                                    0,
                                    1
                                )
                            )
                        }}

                    </span>


                    <div class="flex-fill min-width-0">

                        <div class="pengembalian-mobile-title">

                            {{ $item->siswa?->nama ?? '-' }}

                        </div>


                        <div class="pengembalian-mobile-code">

                            {{ $item->siswa?->nis ?? '-' }}

                            <span class="mx-1">•</span>

                            {{ $namaKelas }}

                        </div>


                        <div class="pengembalian-mobile-code">

                            {{ $item->kode_peminjaman }}

                        </div>

                    </div>


                    <div class="ms-2 flex-shrink-0">

                        @if($item->status === 'terlambat')

                            <span class="badge bg-red-lt">

                                <i class="ti ti-clock me-1"></i>

                                Terlambat

                            </span>

                        @else

                            <span class="badge bg-orange-lt">

                                Dipinjam

                            </span>

                        @endif

                    </div>

                </div>


                {{-- INFORMASI --}}

                <div class="pengembalian-mobile-info">


                    {{-- BUKU --}}

                    <div class="pengembalian-mobile-info-item">

                        <div class="pengembalian-mobile-info-label">

                            Jumlah Buku

                        </div>

                        <div class="pengembalian-mobile-info-value">

                            <i class="ti ti-books me-1"></i>

                            {{ $jumlahBuku }} Buku

                        </div>

                    </div>


                    {{-- JATUH TEMPO --}}

                    <div class="pengembalian-mobile-info-item">

                        <div class="pengembalian-mobile-info-label">

                            Jatuh Tempo

                        </div>

                        <div
                            class="
                                pengembalian-mobile-info-value
                                @if($item->status === 'terlambat')
                                    text-danger
                                @endif
                            ">

                            <i class="ti ti-calendar me-1"></i>

                            {{
                                optional(
                                    $item->tanggal_jatuh_tempo
                                )->format('d M Y')
                            }}

                        </div>

                    </div>

                </div>


                {{-- ACTION --}}

                <div class="mt-3">

                    <a
                        href="{{
                            route(
                                'perpustakaan.pengembalian.show',
                                $item
                            )
                        }}"
                        class="btn btn-primary w-100">

                        <i class="ti ti-package-export me-1"></i>

                        Proses Pengembalian

                    </a>

                </div>

            </div>

        @empty

            <div class="text-center px-3 py-5">

                <span class="avatar avatar-lg bg-green-lt mb-3">

                    <i class="ti ti-circle-check fs-2"></i>

                </span>

                <div class="fw-semibold">
                    Tidak ada peminjaman aktif
                </div>

                <div class="text-secondary small mt-1">
                    Semua buku telah dikembalikan.
                </div>

            </div>

        @endforelse

    </div>


    {{-- ===================================================== --}}
    {{-- PAGINATION --}}
    {{-- ===================================================== --}}

    @if($peminjaman->hasPages())

        <div class="card-footer">

            {{ $peminjaman->links() }}

        </div>

    @endif

</div>

        {{-- ========================================================= --}}
    {{-- MODAL SCAN QR SISWA --}}
    {{-- ========================================================= --}}

    <div
        class="modal modal-blur fade"
        id="modalScanQr"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

            <div class="modal-content">

                {{-- HEADER --}}

                <div class="modal-header">

                    <div class="d-flex align-items-center gap-3">

                        <span class="avatar bg-blue-lt">

                            <i class="ti ti-scan"></i>

                        </span>

                        <div>

                            <h3 class="modal-title">
                                Scan QR Siswa
                            </h3>

                            <div class="text-secondary small">
                                Cari peminjaman aktif siswa.
                            </div>

                        </div>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>


                {{-- BODY --}}

                <div class="modal-body">

                    <div class="row g-4">


                        {{-- ================================================= --}}
                        {{-- SCANNER --}}
                        {{-- ================================================= --}}

                        <div class="col-lg-6">

                            <div
                                id="readerPengembalian"
                                class="border rounded-3 overflow-hidden"
                                style="min-height: 300px;">
                            </div>


                            {{-- STATUS SCANNER --}}

                            <div
                                id="scanStatusPengembalian"
                                class="alert alert-secondary mt-3 mb-0">

                                <div class="d-flex align-items-center">

                                    <i class="ti ti-camera me-2"></i>

                                    <span>
                                        Kamera siap digunakan.
                                    </span>

                                </div>

                            </div>


                            {{-- SCAN ULANG --}}

                            <button
                                type="button"
                                id="btnScanUlangPengembalian"
                                class="btn btn-outline-primary w-100 mt-3"
                                disabled>

                                <i class="ti ti-refresh me-1"></i>

                                Scan Ulang

                            </button>

                        </div>


                        {{-- ================================================= --}}
                        {{-- HASIL SCAN --}}
                        {{-- ================================================= --}}

                        <div class="col-lg-6">


                            {{-- BELUM SCAN --}}

                            <div
                                id="pengembalianEmpty"
                                class="h-100">

                                <div
                                    class="border rounded-3 h-100 d-flex align-items-center justify-content-center"
                                    style="min-height: 300px;">

                                    <div class="text-center p-4">

                                        <span
                                            class="avatar avatar-lg bg-secondary-lt mb-3">

                                            <i class="ti ti-user-search fs-2"></i>

                                        </span>

                                        <div class="fw-semibold">
                                            Belum ada siswa
                                        </div>

                                        <div class="text-secondary small mt-1">
                                            Scan QR untuk menampilkan transaksi.
                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- DATA SISWA --}}
                            {{-- ================================================= --}}

                            <div
                                id="pengembalianResult"
                                class="d-none">


                                {{-- SISWA --}}

                                <div class="border rounded-3 p-3 mb-3">

                                    <div class="d-flex align-items-center">

                                        <span
                                            id="avatarSiswaPengembalian"
                                            class="avatar bg-blue-lt me-3">

                                            S

                                        </span>

                                        <div class="flex-fill">

                                            <div
                                                id="namaSiswaPengembalian"
                                                class="fw-semibold">

                                                -

                                            </div>

                                            <div class="text-secondary small">

                                                <span id="nisSiswaPengembalian">
                                                    -
                                                </span>

                                                <span class="mx-1">
                                                    •
                                                </span>

                                                <span id="kelasSiswaPengembalian">
                                                    -
                                                </span>

                                            </div>

                                        </div>

                                        <span
                                            class="badge bg-green-lt">

                                            <i class="ti ti-circle-check me-1"></i>

                                            Valid

                                        </span>

                                    </div>

                                </div>


                                {{-- ================================================= --}}
                                {{-- JUMLAH TRANSAKSI --}}
                                {{-- ================================================= --}}

                                <div class="d-flex justify-content-between align-items-center mb-2">

                                    <div class="fw-semibold">

                                        Peminjaman Aktif

                                    </div>

                                    <span
                                        id="totalTransaksiPengembalian"
                                        class="badge bg-blue-lt">

                                        0

                                    </span>

                                </div>


                                {{-- ================================================= --}}
                                {{-- LIST TRANSAKSI --}}
                                {{-- ================================================= --}}

                                <div
                                    id="listTransaksiPengembalian"
                                    class="d-flex flex-column gap-2">

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- ERROR --}}
                            {{-- ================================================= --}}

                            <div
                                id="pengembalianError"
                                class="d-none">

                                <div
                                    class="alert alert-danger mb-0">

                                    <div class="d-flex">

                                        <div>

                                            <i class="ti ti-alert-circle me-2"></i>

                                        </div>

                                        <div>

                                            <div class="fw-semibold">
                                                Data tidak ditemukan
                                            </div>

                                            <div
                                                id="pengembalianErrorText"
                                                class="small mt-1">

                                                QR siswa tidak valid.

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- FOOTER --}}

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">

                        Tutup

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- SCRIPT PENGEMBALIAN --}}
{{-- ========================================================= --}}

@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function()
    {

        /*
        |--------------------------------------------------------------------------
        | ELEMENT
        |--------------------------------------------------------------------------
        */

        const modalElement =
            document.getElementById(
                'modalScanQr'
            );


        const scanStatus =
            document.getElementById(
                'scanStatusPengembalian'
            );


        const btnScanUlang =
            document.getElementById(
                'btnScanUlangPengembalian'
            );


        const emptyState =
            document.getElementById(
                'pengembalianEmpty'
            );


        const resultState =
            document.getElementById(
                'pengembalianResult'
            );


        const errorState =
            document.getElementById(
                'pengembalianError'
            );


        const errorText =
            document.getElementById(
                'pengembalianErrorText'
            );


        const namaSiswa =
            document.getElementById(
                'namaSiswaPengembalian'
            );


        const nisSiswa =
            document.getElementById(
                'nisSiswaPengembalian'
            );


        const kelasSiswa =
            document.getElementById(
                'kelasSiswaPengembalian'
            );


        const avatarSiswa =
            document.getElementById(
                'avatarSiswaPengembalian'
            );


        const totalTransaksi =
            document.getElementById(
                'totalTransaksiPengembalian'
            );


        const listTransaksi =
            document.getElementById(
                'listTransaksiPengembalian'
            );


        /*
        |--------------------------------------------------------------------------
        | VARIABLE SCANNER
        |--------------------------------------------------------------------------
        */

        let html5QrPengembalian = null;

        let qrPengembalianAktif = false;

        let sedangMemprosesQr = false;


        /*
        |--------------------------------------------------------------------------
        | RESET TAMPILAN
        |--------------------------------------------------------------------------
        */

        function resetHasilScan()
        {

            /*
            |--------------------------------------------------------------------------
            | State
            |--------------------------------------------------------------------------
            */

            emptyState.classList.remove(
                'd-none'
            );


            resultState.classList.add(
                'd-none'
            );


            errorState.classList.add(
                'd-none'
            );


            /*
            |--------------------------------------------------------------------------
            | Data Siswa
            |--------------------------------------------------------------------------
            */

            namaSiswa.textContent = '-';

            nisSiswa.textContent = '-';

            kelasSiswa.textContent = '-';

            avatarSiswa.textContent = 'S';


            /*
            |--------------------------------------------------------------------------
            | Transaksi
            |--------------------------------------------------------------------------
            */

            totalTransaksi.textContent = '0';

            listTransaksi.innerHTML = '';


            /*
            |--------------------------------------------------------------------------
            | Button
            |--------------------------------------------------------------------------
            */

            btnScanUlang.disabled = true;


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            scanStatus.className =
                'alert alert-secondary mt-3 mb-0';


            scanStatus.innerHTML = `
                <div class="d-flex align-items-center">

                    <i class="ti ti-camera me-2"></i>

                    <span>
                        Kamera siap digunakan.
                    </span>

                </div>
            `;

        }


        /*
        |--------------------------------------------------------------------------
        | START SCANNER
        |--------------------------------------------------------------------------
        */

        function startScannerPengembalian()
        {

            /*
            |--------------------------------------------------------------------------
            | Jangan Start Dua Kali
            |--------------------------------------------------------------------------
            */

            if (qrPengembalianAktif)
            {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Pastikan Library Ada
            |--------------------------------------------------------------------------
            */

            if (
                typeof Html5Qrcode
                ===
                'undefined'
            )
            {

                scanStatus.className =
                    'alert alert-danger mt-3 mb-0';


                scanStatus.innerHTML = `
                    <div class="d-flex align-items-center">

                        <i class="ti ti-alert-circle me-2"></i>

                        <span>
                            Library QR Scanner tidak ditemukan.
                        </span>

                    </div>
                `;


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Reset Reader
            |--------------------------------------------------------------------------
            */

            const reader =
                document.getElementById(
                    'readerPengembalian'
                );


            if (!reader)
            {
                return;
            }


            reader.innerHTML = '';


            /*
            |--------------------------------------------------------------------------
            | Buat Scanner
            |--------------------------------------------------------------------------
            */

            html5QrPengembalian =
                new Html5Qrcode(
                    'readerPengembalian'
                );


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            scanStatus.className =
                'alert alert-info mt-3 mb-0';


            scanStatus.innerHTML = `
                <div class="d-flex align-items-center">

                    <span
                        class="spinner-border spinner-border-sm me-2">
                    </span>

                    <span>
                        Membuka kamera...
                    </span>

                </div>
            `;


            /*
            |--------------------------------------------------------------------------
            | Start Kamera
            |--------------------------------------------------------------------------
            */

            html5QrPengembalian
                .start(

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

                    suksesScanPengembalian,

                    function()
                    {
                        /*
                        |--------------------------------------------------------------------------
                        | Error Frame QR
                        |--------------------------------------------------------------------------
                        |
                        | Sengaja dikosongkan.
                        | Html5Qrcode akan terus mencoba membaca QR.
                        |
                        */
                    }

                )

                .then(
                    function()
                    {

                        qrPengembalianAktif =
                            true;


                        scanStatus.className =
                            'alert alert-info mt-3 mb-0';


                        scanStatus.innerHTML = `
                            <div class="d-flex align-items-center">

                                <i class="ti ti-camera me-2"></i>

                                <span>
                                    Kamera aktif. Arahkan ke QR siswa.
                                </span>

                            </div>
                        `;

                    }
                )

                .catch(
                    function(error)
                    {

                        console.error(
                            'Scanner gagal:',
                            error
                        );


                        qrPengembalianAktif =
                            false;


                        scanStatus.className =
                            'alert alert-danger mt-3 mb-0';


                        scanStatus.innerHTML = `
                            <div class="d-flex align-items-center">

                                <i class="ti ti-alert-circle me-2"></i>

                                <span>
                                    Kamera gagal dibuka.
                                </span>

                            </div>
                        `;


                        btnScanUlang.disabled =
                            false;

                    }
                );

        }


        /*
        |--------------------------------------------------------------------------
        | STOP SCANNER
        |--------------------------------------------------------------------------
        */

        function stopScannerPengembalian()
        {

            /*
            |--------------------------------------------------------------------------
            | Scanner Tidak Ada
            |--------------------------------------------------------------------------
            */

            if (!html5QrPengembalian)
            {
                return Promise.resolve();
            }


            /*
            |--------------------------------------------------------------------------
            | Scanner Tidak Aktif
            |--------------------------------------------------------------------------
            */

            if (!qrPengembalianAktif)
            {
                return Promise.resolve();
            }


            /*
            |--------------------------------------------------------------------------
            | Stop
            |--------------------------------------------------------------------------
            */

            return html5QrPengembalian
                .stop()

                .then(
                    function()
                    {

                        qrPengembalianAktif =
                            false;

                    }
                )

                .catch(
                    function(error)
                    {

                        console.error(
                            'Gagal menghentikan scanner:',
                            error
                        );


                        qrPengembalianAktif =
                            false;

                    }
                );

        }


        /*
        |--------------------------------------------------------------------------
        | QR BERHASIL DIBACA
        |--------------------------------------------------------------------------
        */

        function suksesScanPengembalian(
            decodedText
        )
        {

            /*
            |--------------------------------------------------------------------------
            | Hindari Request Berulang
            |--------------------------------------------------------------------------
            */

            if (sedangMemprosesQr)
            {
                return;
            }


            sedangMemprosesQr = true;


            /*
            |--------------------------------------------------------------------------
            | Stop Kamera
            |--------------------------------------------------------------------------
            */

            stopScannerPengembalian();


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            scanStatus.className =
                'alert alert-info mt-3 mb-0';


            scanStatus.innerHTML = `
                <div class="d-flex align-items-center">

                    <span
                        class="spinner-border spinner-border-sm me-2">
                    </span>

                    <span>
                        Memeriksa data siswa...
                    </span>

                </div>
            `;


            /*
            |--------------------------------------------------------------------------
            | CSRF
            |--------------------------------------------------------------------------
            */

            const csrfMeta =
                document.querySelector(
                    'meta[name="csrf-token"]'
                );


            if (!csrfMeta)
            {

                tampilkanError(
                    'CSRF token tidak ditemukan.'
                );


                sedangMemprosesQr =
                    false;


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Request
            |--------------------------------------------------------------------------
            */

            fetch(
                "{{ route('perpustakaan.pengembalian.scanQr') }}",
                {

                    method:
                        'POST',

                    headers:
                    {

                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfMeta.content

                    },

                    body:
                        JSON.stringify(
                            {

                                qr_token:
                                    decodedText

                            }
                        )

                }
            )

            /*
            |--------------------------------------------------------------------------
            | Parse Response
            |--------------------------------------------------------------------------
            */

            .then(
                async function(response)
                {

                    let data;


                    try
                    {

                        data =
                            await response.json();

                    }
                    catch (error)
                    {

                        throw new Error(
                            'Response server tidak valid.'
                        );

                    }


                    if (!response.ok)
                    {

                        throw new Error(
                            data.message
                            ??
                            'Data siswa tidak ditemukan.'
                        );

                    }


                    return data;

                }
            )


            /*
            |--------------------------------------------------------------------------
            | Response Berhasil
            |--------------------------------------------------------------------------
            */

            .then(
                function(response)
                {

                    console.log(
                        'SCAN PENGEMBALIAN:',
                        response
                    );


                    if (!response.success)
                    {

                        throw new Error(
                            response.message
                            ??
                            'Data peminjaman tidak ditemukan.'
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Tampilkan Data
                    |--------------------------------------------------------------------------
                    */

                    tampilkanHasilScan(
                        response.data
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Status
                    |--------------------------------------------------------------------------
                    */

                    scanStatus.className =
                        'alert alert-success mt-3 mb-0';


                    scanStatus.innerHTML = `
                        <div class="d-flex align-items-center">

                            <i class="ti ti-circle-check me-2"></i>

                            <span>
                                QR berhasil dipindai.
                            </span>

                        </div>
                    `;


                    /*
                    |--------------------------------------------------------------------------
                    | Scan Ulang
                    |--------------------------------------------------------------------------
                    */

                    btnScanUlang.disabled =
                        false;

                }
            )


            /*
            |--------------------------------------------------------------------------
            | Error
            |--------------------------------------------------------------------------
            */

            .catch(
                function(error)
                {

                    console.error(
                        'Scan QR gagal:',
                        error
                    );


                    tampilkanError(
                        error.message
                        ??
                        'QR siswa tidak dapat diproses.'
                    );


                    btnScanUlang.disabled =
                        false;

                }
            )


            /*
            |--------------------------------------------------------------------------
            | Finish
            |--------------------------------------------------------------------------
            */

            .finally(
                function()
                {

                    sedangMemprosesQr =
                        false;

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN HASIL SCAN
        |--------------------------------------------------------------------------
        */

        function tampilkanHasilScan(data)
        {

            /*
            |--------------------------------------------------------------------------
            | Struktur Response
            |--------------------------------------------------------------------------
            |
            | data.siswa
            | data.total_transaksi
            | data.transaksi
            |
            */

            const siswa =
                data.siswa;


            const transaksi =
                data.transaksi
                ??
                [];


            /*
            |--------------------------------------------------------------------------
            | State
            |--------------------------------------------------------------------------
            */

            emptyState.classList.add(
                'd-none'
            );


            errorState.classList.add(
                'd-none'
            );


            resultState.classList.remove(
                'd-none'
            );


            /*
            |--------------------------------------------------------------------------
            | Nama
            |--------------------------------------------------------------------------
            */

            namaSiswa.textContent =
                siswa.nama
                ??
                '-';


            /*
            |--------------------------------------------------------------------------
            | NIS
            |--------------------------------------------------------------------------
            */

            nisSiswa.textContent =
                siswa.nis
                ??
                '-';


            /*
            |--------------------------------------------------------------------------
            | Kelas
            |--------------------------------------------------------------------------
            */

            kelasSiswa.textContent =
                siswa.kelas
                ??
                '-';


            /*
            |--------------------------------------------------------------------------
            | Avatar
            |--------------------------------------------------------------------------
            */

            let hurufAwal = 'S';


            if (
                siswa.nama
                &&
                siswa.nama.length > 0
            )
            {

                hurufAwal =
                    siswa.nama
                        .charAt(0)
                        .toUpperCase();

            }


            avatarSiswa.textContent =
                hurufAwal;


            /*
            |--------------------------------------------------------------------------
            | Total Transaksi
            |--------------------------------------------------------------------------
            */

            totalTransaksi.textContent =
                data.total_transaksi
                ??
                transaksi.length;


            /*
            |--------------------------------------------------------------------------
            | Render
            |--------------------------------------------------------------------------
            */

            renderTransaksi(
                transaksi
            );

        }


        /*
        |--------------------------------------------------------------------------
        | RENDER TRANSAKSI
        |--------------------------------------------------------------------------
        */

        function renderTransaksi(
            transaksi
        )
        {

            /*
            |--------------------------------------------------------------------------
            | Bersihkan
            |--------------------------------------------------------------------------
            */

            listTransaksi.innerHTML = '';


            /*
            |--------------------------------------------------------------------------
            | Tidak Ada Transaksi
            |--------------------------------------------------------------------------
            */

            if (
                !Array.isArray(transaksi)
                ||
                transaksi.length === 0
            )
            {

                listTransaksi.innerHTML = `

                    <div
                        class="border rounded-3 p-4 text-center">

                        <span
                            class="avatar bg-green-lt mb-2">

                            <i class="ti ti-circle-check"></i>

                        </span>

                        <div class="fw-semibold">
                            Tidak ada peminjaman
                        </div>

                        <div class="text-secondary small mt-1">
                            Siswa tidak memiliki buku yang harus dikembalikan.
                        </div>

                    </div>

                `;


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Loop Transaksi
            |--------------------------------------------------------------------------
            */

            transaksi.forEach(
                function(item)
                {

                    /*
                    |--------------------------------------------------------------------------
                    | Status
                    |--------------------------------------------------------------------------
                    */

                    let badgeStatus;


                    if (
                        item.status
                        ===
                        'terlambat'
                    )
                    {

                        badgeStatus = `

                            <span class="badge bg-red-lt">

                                <i class="ti ti-clock me-1"></i>

                                Terlambat

                            </span>

                        `;

                    }
                    else
                    {

                        badgeStatus = `

                            <span class="badge bg-orange-lt">

                                Dipinjam

                            </span>

                        `;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Daftar Buku
                    |--------------------------------------------------------------------------
                    */

                    let daftarBuku = '';


                    if (
                        Array.isArray(item.buku)
                        &&
                        item.buku.length > 0
                    )
                    {

                        item.buku.forEach(
                            function(buku)
                            {

                                daftarBuku += `

                                    <div
                                        class="d-flex justify-content-between align-items-center py-1">

                                        <span
                                            class="text-secondary small">

                                            ${escapeHtml(
                                                buku.nama_buku
                                                ?? '-'
                                            )}

                                        </span>

                                        <span
                                            class="badge bg-secondary-lt">

                                            ${Number(
                                                buku.jumlah
                                                ?? 0
                                            )}

                                        </span>

                                    </div>

                                `;

                            }
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Card
                    |--------------------------------------------------------------------------
                    */

                    const card =
                        document.createElement(
                            'div'
                        );


                    card.className =
                        'border rounded-3 p-3';


                    card.innerHTML = `

                        <div
                            class="d-flex justify-content-between align-items-start gap-2 mb-2">

                            <div>

                                <div class="fw-semibold">

                                    ${escapeHtml(
                                        item.kode_peminjaman
                                        ?? '-'
                                    )}

                                </div>

                                <div
                                    class="text-secondary small mt-1">

                                    ${escapeHtml(
                                        item.tanggal_pinjam
                                        ?? '-'
                                    )}

                                    <span class="mx-1">
                                        →
                                    </span>

                                    ${escapeHtml(
                                        item.tanggal_jatuh_tempo
                                        ?? '-'
                                    )}

                                </div>

                            </div>

                            ${badgeStatus}

                        </div>


                        <div
                            class="border-top border-bottom py-2 my-2">

                            ${daftarBuku}

                        </div>


                        <div
                            class="d-flex justify-content-between align-items-center mt-3">

                            <span class="text-secondary small">

                                ${Number(
                                    item.total_buku
                                    ?? 0
                                )}

                                buku

                            </span>


                            <a
                                href="${escapeAttribute(
                                    item.url
                                    ?? '#'
                                )}"
                                class="btn btn-sm btn-primary">

                                Proses

                                <i
                                    class="ti ti-chevron-right ms-1">
                                </i>

                            </a>

                        </div>

                    `;


                    /*
                    |--------------------------------------------------------------------------
                    | Append
                    |--------------------------------------------------------------------------
                    */

                    listTransaksi.appendChild(
                        card
                    );

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN ERROR
        |--------------------------------------------------------------------------
        */

        function tampilkanError(
            message
        )
        {

            /*
            |--------------------------------------------------------------------------
            | State
            |--------------------------------------------------------------------------
            */

            emptyState.classList.add(
                'd-none'
            );


            resultState.classList.add(
                'd-none'
            );


            errorState.classList.remove(
                'd-none'
            );


            /*
            |--------------------------------------------------------------------------
            | Message
            |--------------------------------------------------------------------------
            */

            errorText.textContent =
                message
                ??
                'QR siswa tidak dapat diproses.';


            /*
            |--------------------------------------------------------------------------
            | Scanner Status
            |--------------------------------------------------------------------------
            */

            scanStatus.className =
                'alert alert-danger mt-3 mb-0';


            scanStatus.innerHTML = `

                <div class="d-flex align-items-center">

                    <i class="ti ti-alert-circle me-2"></i>

                    <span>
                        QR tidak dapat diproses.
                    </span>

                </div>

            `;

        }


        /*
        |--------------------------------------------------------------------------
        | ESCAPE HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value)
        {

            const div =
                document.createElement(
                    'div'
                );


            div.textContent =
                String(
                    value
                    ??
                    ''
                );


            return div.innerHTML;

        }


        /*
        |--------------------------------------------------------------------------
        | ESCAPE ATTRIBUTE
        |--------------------------------------------------------------------------
        */

        function escapeAttribute(value)
        {

            return String(
                value
                ??
                ''
            )

            .replaceAll(
                '&',
                '&amp;'
            )

            .replaceAll(
                '"',
                '&quot;'
            )

            .replaceAll(
                "'",
                '&#039;'
            )

            .replaceAll(
                '<',
                '&lt;'
            )

            .replaceAll(
                '>',
                '&gt;'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MODAL DIBUKA
        |--------------------------------------------------------------------------
        */

        modalElement.addEventListener(
            'shown.bs.modal',
            function()
            {

                /*
                |--------------------------------------------------------------------------
                | Reset
                |--------------------------------------------------------------------------
                */

                resetHasilScan();


                /*
                |--------------------------------------------------------------------------
                | Delay Sedikit
                |--------------------------------------------------------------------------
                |
                | Scanner dijalankan setelah modal benar-benar tampil.
                |
                */

                setTimeout(
                    function()
                    {

                        startScannerPengembalian();

                    },
                    250
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | MODAL DITUTUP
        |--------------------------------------------------------------------------
        */

        modalElement.addEventListener(
            'hidden.bs.modal',
            function()
            {

                /*
                |--------------------------------------------------------------------------
                | Stop Kamera
                |--------------------------------------------------------------------------
                */

                stopScannerPengembalian();


                /*
                |--------------------------------------------------------------------------
                | Reset Flag
                |--------------------------------------------------------------------------
                */

                sedangMemprosesQr =
                    false;

            }
        );


        /*
        |--------------------------------------------------------------------------
        | BUTTON SCAN ULANG
        |--------------------------------------------------------------------------
        */

        btnScanUlang.addEventListener(
            'click',
            function()
            {

                /*
                |--------------------------------------------------------------------------
                | Disable Sementara
                |--------------------------------------------------------------------------
                */

                btnScanUlang.disabled =
                    true;


                /*
                |--------------------------------------------------------------------------
                | Reset Flag
                |--------------------------------------------------------------------------
                */

                sedangMemprosesQr =
                    false;


                /*
                |--------------------------------------------------------------------------
                | Reset Result
                |--------------------------------------------------------------------------
                */

                resetHasilScan();


                /*
                |--------------------------------------------------------------------------
                | Stop Scanner Lama
                |--------------------------------------------------------------------------
                */

                stopScannerPengembalian()

                    .finally(
                        function()
                        {

                            /*
                            |--------------------------------------------------------------------------
                            | Jalankan Lagi
                            |--------------------------------------------------------------------------
                            */

                            setTimeout(
                                function()
                                {

                                    startScannerPengembalian();

                                },
                                200
                            );

                        }
                    );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | DEBUG
        |--------------------------------------------------------------------------
        */

        console.log(
            'Pengembalian QR Scanner siap.'
        );

    }
);

</script>

@endpush


@endsection