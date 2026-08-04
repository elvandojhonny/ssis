@extends('layouts.app')

@section('title', 'Laporan Perpustakaan')

@section('content')

<div class="container-fluid">

    {{-- ==========================================================
    | HEADER
    =========================================================== --}}

    <div class="page-header d-print-none mb-4">

        <div class="row align-items-center">

            <div class="col">

                <h2 class="page-title">

                    Laporan Transaksi Perpustakaan

                </h2>

                <div class="text-muted mt-1">

                    Rekap seluruh transaksi peminjaman buku berdasarkan periode
                    bulanan maupun tahunan.

                </div>

            </div>

            <div class="col-auto ms-auto">

                <div class="btn-list">

                    <a
                        href="{{ route('perpustakaan.laporan.export', request()->all()) }}"
                        class="btn btn-success no-loading">

                        <i class="ti ti-file-spreadsheet me-1"></i>

                        Export Excel

                    </a>

                    <button
                        onclick="window.print()"
                        class="btn btn-secondary">

                        <i class="ti ti-printer me-1"></i>

                        Print

                    </button>

                </div>

            </div>

        </div>

    </div>

    {{-- ==========================================================
    | FILTER
    =========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <h3 class="card-title">

                Filter Laporan

            </h3>

        </div>

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-lg-3">

                        <label class="form-label">

                            Periode

                        </label>

                        <select
                            name="periode"
                            id="periode"
                            class="form-select">

                            <option
                                value="bulan"
                                {{ $periode=='bulan' ? 'selected' : '' }}>

                                Bulanan

                            </option>

                            <option
                                value="tahun"
                                {{ $periode=='tahun' ? 'selected' : '' }}>

                                Tahunan

                            </option>

                        </select>

                    </div>

                    <div
                        class="col-lg-3"
                        id="bulan-wrapper">

                        <label class="form-label">

                            Bulan

                        </label>

                        <select
                            name="bulan"
                            class="form-select">

                            @foreach(range(1,12) as $bln)

                                <option
                                    value="{{ $bln }}"
                                    {{ $bulan==$bln ? 'selected' : '' }}>

                                    {{ \Carbon\Carbon::create()->month($bln)->translatedFormat('F') }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-lg-2">

                        <label class="form-label">

                            Tahun

                        </label>

                        <select
                            name="tahun"
                            class="form-select">

                            @for($i = now()->year; $i >= 2024; $i--)

                                <option
                                    value="{{ $i }}"
                                    {{ $tahun==$i ? 'selected' : '' }}>

                                    {{ $i }}

                                </option>

                            @endfor

                        </select>

                    </div>

                    <div class="col-lg-4 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary me-2">

                            <i class="ti ti-search me-1"></i>

                            Tampilkan

                        </button>

                        <a
                            href="{{ route('perpustakaan.laporan.index') }}"
                            class="btn btn-outline-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- ==========================================================
    | INFO PERIODE
    =========================================================== --}}

    <div class="alert alert-info">

        <div class="d-flex align-items-center">

            <i class="ti ti-calendar me-2 fs-4"></i>

            <div>

                @if($periode=='bulan')

                    Menampilkan laporan transaksi bulan

                    <strong>

                        {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}

                        {{ $tahun }}

                    </strong>

                @else

                    Menampilkan laporan transaksi tahun

                    <strong>

                        {{ $tahun }}

                    </strong>

                @endif

            </div>

        </div>

    </div>

        {{-- ========================================================= --}}
        {{-- RINGKASAN LAPORAN --}}
        {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">

        <div class="card stat-card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-secondary">

                            Total Transaksi

                        </small>

                        <h2 class="fw-bold mt-2 mb-0">

                            {{ $ringkasan['total_transaksi'] }}

                        </h2>

                    </div>

                    <div class="avatar avatar-lg bg-primary-lt text-primary">

                        <i class="ti ti-report fs-2"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col-12 col-sm-6 col-xl-3">

        <div class="card stat-card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-secondary">

                            Total Buku

                        </small>

                        <h2 class="fw-bold mt-2 mb-0">

                            {{ $ringkasan['total_buku'] }}

                        </h2>

                    </div>

                    <div class="avatar avatar-lg bg-success-lt text-success">

                        <i class="ti ti-books fs-2"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col-12 col-sm-6 col-xl-3">

        <div class="card stat-card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-secondary">

                            Masih Dipinjam

                        </small>

                        <h2 class="fw-bold mt-2 mb-0">

                            {{ $ringkasan['dipinjam'] }}

                        </h2>

                    </div>

                    <div class="avatar avatar-lg bg-warning-lt text-warning">

                        <i class="ti ti-book-download fs-2"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col-12 col-sm-6 col-xl-3">

        <div class="card stat-card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-secondary">

                            Terlambat

                        </small>

                        <h2 class="fw-bold mt-2 mb-0">

                            {{ $ringkasan['terlambat'] }}

                        </h2>

                    </div>

                    <div class="avatar avatar-lg bg-danger-lt text-danger">

                        <i class="ti ti-alert-triangle fs-2"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


    {{-- ========================================================= --}}
    {{-- TABEL LAPORAN --}}
    {{-- ========================================================= --}}

    <div class="card-header bg-white border-bottom">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>

            <div class="d-flex align-items-center gap-2">

                <h4 class="mb-0 fw-bold">
                    Daftar Transaksi
                </h4>

                <span class="badge bg-primary-lt text-primary">
                    {{ $laporan->total() }} Data
                </span>

            </div>

            <small class="text-secondary">

                Menampilkan seluruh transaksi sesuai periode yang dipilih.

            </small>

        </div>

        {{-- Search --}}

        <div style="width:260px;">

            <div class="input-icon">

                <span class="input-icon-addon">

                    <i class="ti ti-search"></i>

                </span>

                <input
                    type="text"
                    class="form-control"
                    placeholder="Cari transaksi..."
                >

            </div>

        </div>

    </div>

</div>

        <div class="table-responsive">

            <table class="table table-hover table-bordered align-middle mb-0">

                <thead class="table-light">

<tr>

    <th width="60">
        No
    </th>

    <th width="170">
        Kode
    </th>

    <th width="170">
        Tanggal
    </th>

    <th width="260">
        Peminjam
    </th>

    <th width="320">
        Buku
    </th>

    <th width="90" class="text-center">
        Total
    </th>

    <th width="150">
        Status
    </th>

    <th width="170">
        Petugas
    </th>

</tr>

</thead>

                <tbody>

@forelse($laporan as $item)

<tr>

    <td class="text-center fw-bold">
        {{ $loop->iteration + $laporan->firstItem() - 1 }}
    </td>

    {{-- ============================= --}}
    {{-- KODE --}}
    {{-- ============================= --}}

    <td style="min-width:170px;">

        <div class="fw-bold">

            {{ $item->kode_peminjaman }}

        </div>

    </td>

    {{-- ============================= --}}
    {{-- TANGGAL --}}
    {{-- ============================= --}}

    <td style="min-width:170px;">

        <div class="fw-semibold">

            {{ $item->tanggal_pinjam->format('d M Y') }}

        </div>

        <small class="text-secondary d-block">

            JT :

            {{ $item->tanggal_jatuh_tempo->format('d M Y') }}

        </small>

        @if($item->tanggal_kembali)

            <small class="text-success d-block">

                Kembali :

                {{ $item->tanggal_kembali->format('d M Y') }}

            </small>

        @endif

    </td>

    {{-- ============================= --}}
    {{-- PEMINJAM --}}
    {{-- ============================= --}}

    <td style="min-width:220px;">

        <div class="fw-bold">

            {{ $item->nama_peminjam }}

        </div>

        <small class="text-secondary">

            @if($item->siswa)

                Siswa •

                {{ $item->siswa->kelas->tingkat }}

                •

                NIS :

                {{ $item->siswa->nis }}

            @else

                Guru •

                NIP :

                {{ $item->guru->nip }}

            @endif

        </small>

    </td>

    {{-- ============================= --}}
    {{-- BUKU --}}
    {{-- ============================= --}}

    <td style="min-width:250px;">

        @foreach($item->detailPeminjaman as $detail)

            <div class="d-flex justify-content-between align-items-center mb-2">

                <div>

                    <i class="ti ti-book text-primary me-2"></i>

                    {{ $detail->buku->nama_buku }}

                </div>

                <span class="badge bg-blue-lt text-blue">

                    {{ $detail->jumlah }}

                </span>

            </div>

        @endforeach

    </td>

    {{-- ============================= --}}
    {{-- TOTAL --}}
    {{-- ============================= --}}

    <td class="text-center">

        <span class="badge bg-dark-lt text-dark">

            {{ $item->jumlah_buku }}

        </span>

    </td>

    {{-- ============================= --}}
    {{-- STATUS --}}
    {{-- ============================= --}}

    <td>

        @if($item->status=='dipinjam')

            <span class="badge bg-yellow-lt text-yellow">

                Dipinjam

            </span>

        @elseif($item->status=='dikembalikan')

            <span class="badge bg-green-lt text-green">

                Dikembalikan

            </span>

        @else

            <span class="badge bg-red-lt text-red">

                Terlambat

            </span>

        @endif

    </td>

    {{-- ============================= --}}
    {{-- PETUGAS --}}
    {{-- ============================= --}}

    <td>

        {{ $item->petugas->nama }}

    </td>

</tr>

@empty

<tr>

    <td colspan="8" class="text-center py-5">

        Tidak ada data.

    </td>

</tr>

@endforelse

</tbody>

            </table>

        </div>

                @if($laporan->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-end">

                    {{ $laporan->links() }}

                </div>

            </div>

        @endif

    </div>

</div>

@endsection

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const periode = document.getElementById('periode');

    const bulanWrapper = document.getElementById('bulan-wrapper');

    function toggleBulan() {

        if (periode.value === 'tahun') {

            bulanWrapper.classList.add('d-none');

        } else {

            bulanWrapper.classList.remove('d-none');

        }

    }

    toggleBulan();

    periode.addEventListener(
        'change',
        toggleBulan
    );

});

</script>

@endpush

@push('styles')

<style>

    .table thead th{

    font-size:.78rem;

    letter-spacing:.4px;

    color:#667085;

    white-space:nowrap;

    vertical-align:middle;

}

.table tbody td{

    vertical-align:top;

    padding:1rem;

}

.table tbody tr{

    transition:.25s;

}

.table tbody tr:hover{

    background:#f8fbff;

}

.badge{

    border-radius:8px;

    font-weight:600;

}

.table tbody .ti-book{

    font-size:16px;

}

</style>

@endpush