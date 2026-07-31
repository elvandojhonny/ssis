@extends('layouts.app')

@section('title', 'Detail Peminjaman Buku')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Detail Peminjaman Buku
            </h3>

            <small class="text-muted">
                Informasi lengkap transaksi peminjaman buku perpustakaan.
            </small>
        </div>

        <a
        href="{{ route('perpustakaan.peminjaman.index') }}"
        class="btn btn-secondary w-100 w-md-auto">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>

    </div>

    {{-- Ringkasan --}}
    <div class="row mb-4">

        <div class="col-12 col-md-6 col-lg-4 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block mb-2">
                        Status Peminjaman
                    </small>

                    @php
                        $statusClass = match ($peminjaman->status) {
                            'dipinjam'     => 'bg-blue-lt text-blue',
                            'terlambat'    => 'bg-red-lt text-red',
                            'dikembalikan' => 'bg-green-lt text-green',
                            default        => 'bg-secondary-lt text-secondary',
                        };
                    @endphp

                    <span class="badge {{ $statusClass }}">
                        {{ ucfirst($peminjaman->status) }}
                    </span>

                    <hr>

                    <small class="text-muted d-block">
                        Kode Peminjaman
                    </small>

                    <h5 class="fw-bold mb-0">
                        {{ $peminjaman->kode_peminjaman }}
                    </h5>

                </div>

            </div>

        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block mb-2">
                        Jumlah Buku
                    </small>

                    <h3 class="fw-bold text-primary mb-0">
                        {{ $peminjaman->jumlah_buku }}
                    </h3>

                    <small class="text-muted">
                        Buku Dipinjam
                    </small>

                </div>

            </div>

        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block mb-2">
                        Petugas
                    </small>

                    <h5 class="fw-bold mb-0">
                        {{ optional($peminjaman->petugas->user)->name }}
                    </h5>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        {{-- Informasi Transaksi --}}
        <div class="col-12 col-lg-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0">
                        Informasi Transaksi
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row mb-3">

                        <div class="col-5 text-muted">
                            Kode
                        </div>

                        <div class="col-7 fw-semibold">
                            {{ $peminjaman->kode_peminjaman }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-5 text-muted">
                            Tanggal Pinjam
                        </div>

                        <div class="col-7">
                            {{ $peminjaman->tanggal_pinjam->format('d M Y') }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-5 text-muted">
                            Jatuh Tempo
                        </div>

                        <div class="col-7">
                            {{ $peminjaman->tanggal_jatuh_tempo->format('d M Y') }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-5 text-muted">
                            Tanggal Kembali
                        </div>

                        <div class="col-7">
                            {{ $peminjaman->tanggal_kembali?->format('d M Y') ?? '-' }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-5 text-muted">
                            Status
                        </div>

                        <div class="col-7">
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($peminjaman->status) }}
                            </span>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-5 text-muted">
                            Catatan
                        </div>

                        <div class="col-7">
                            {{ $peminjaman->catatan ?: '-' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Informasi Peminjam --}}
        <div class="col-lg-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0">
                        Informasi Peminjam
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row mb-3">

                        <div class="col-5 text-muted">
                            Nama
                        </div>

                        <div class="col-7 fw-semibold">
                            {{ $peminjaman->nama_peminjam }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-5 text-muted">
                            Jenis
                        </div>

                        <div class="col-7">
                            {{ ucfirst($peminjaman->jenis_peminjam) }}
                        </div>

                    </div>

                    @if($peminjaman->siswa)

                        <div class="row mb-3">

                            <div class="col-5 text-muted">
                                NIS
                            </div>

                            <div class="col-7">
                                {{ $peminjaman->siswa->nis }}
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-5 text-muted">
                                Kelas
                            </div>

                            <div class="col-7">
                                {{ optional($peminjaman->siswa->kelas)->nama ?? '-' }}
                            </div>

                        </div>

                    @endif

                    @if($peminjaman->guru)

                        <div class="row mb-3">

                            <div class="col-5 text-muted">
                                NIP
                            </div>

                            <div class="col-7">
                                {{ $peminjaman->guru->nip }}
                            </div>

                        </div>

                    @endif

                    <div class="row">

                        <div class="col-5 text-muted">
                            Jumlah Buku
                        </div>

                        <div class="col-7">
                            <span class="badge bg-blue-lt text-blue">
                                {{ $peminjaman->jumlah_buku }} Buku
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

        {{-- ====================================================== --}}
    {{-- DAFTAR BUKU --}}
    {{-- ====================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <div>
                <h5 class="fw-bold mb-1">
                    Daftar Buku
                </h5>

                <small class="text-muted">
                    Buku yang dipinjam pada transaksi ini.
                </small>
            </div>

            <span class="badge bg-blue-lt text-blue fs-6">
                {{ $peminjaman->detailPeminjaman->count() }} Buku
            </span>

        </div>

        <div class="table-responsive d-none d-lg-block">

            <table class="table align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="60">
                            No
                        </th>

                        <th>
                            Buku
                        </th>

                        <th width="180">
                            Kelas
                        </th>

                        <th width="120">
                            Jumlah
                        </th>

                        <th width="160">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($peminjaman->detailPeminjaman as $detail)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>

                                <div class="fw-semibold">
                                    {{ $detail->buku->nama_buku }}
                                </div>

                                <small class="text-muted">
                                    ID Buku :
                                    {{ $detail->buku->id }}
                                </small>

                            </td>

                            <td>

                                @if($detail->buku->kelas)

                                    <span class="badge bg-cyan-lt text-cyan">
                                        {{ $detail->buku->kelas->nama }}
                                    </span>

                                @else

                                    -

                                @endif

                            </td>

                            <td>

                                <span class="badge bg-blue-lt text-blue">
                                    {{ $detail->jumlah }}
                                </span>

                            </td>

                            <td>

                                @if($peminjaman->status == 'dikembalikan')

                                    <span class="badge bg-green-lt text-green">
                                        Sudah Dikembalikan
                                    </span>

                                @elseif($peminjaman->status == 'terlambat')

                                    <span class="badge bg-red-lt text-red">
                                        Terlambat
                                    </span>

                                @else

                                    <span class="badge bg-blue-lt text-blue">
                                        Sedang Dipinjam
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-5">

                                <i class="bi bi-book fs-1 text-secondary"></i>

                                <div class="mt-2 text-muted">

                                    Tidak ada buku pada transaksi ini.

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="d-lg-none">

    @forelse($peminjaman->detailPeminjaman as $detail)

        <div class="p-3 border-bottom">

                <div class="fw-bold">
                    {{ $detail->buku->nama_buku }}
                </div>

                <small class="text-muted">
                    ID Buku :
                    {{ $detail->buku->id }}
                </small>

                <hr>

                <div class="row g-2">

                    <div class="col-6">
                        <small class="text-muted d-block">
                            Kelas
                        </small>

                        {{ optional($detail->buku->kelas)->nama ?? '-' }}
                    </div>

                    <div class="col-6">
                        <small class="text-muted d-block">
                            Jumlah
                        </small>

                        {{ $detail->jumlah }}
                    </div>

                    <div class="col-12 mt-2">
    <small class="text-muted d-block">
        Status
    </small>

    @if($peminjaman->status == 'dikembalikan')
        <span class="badge bg-green-lt text-green">
            Sudah Dikembalikan
        </span>
    @elseif($peminjaman->status == 'terlambat')
        <span class="badge bg-red-lt text-red">
            Terlambat
        </span>
    @else
        <span class="badge bg-blue-lt text-blue">
            Sedang Dipinjam
        </span>
    @endif
</div>

                </div>

        </div>

    @empty

        <div class="text-center py-4">
            Tidak ada buku.
        </div>

    @endforelse

</div>

    </div>

        {{-- ====================================================== --}}
    {{-- TIMELINE TRANSAKSI --}}
    {{-- ====================================================== --}}

    <div class="row">

        <div class="col-12 col-lg-6 mb-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0">
                        Timeline Transaksi
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-flex mb-4">

                        <div class="me-3">
                            <span class="badge rounded-pill bg-blue-lt text-blue p-2">
                                <i class="bi bi-journal-check"></i>
                            </span>
                        </div>

                        <div>

                            <div class="fw-semibold">
                                Buku Dipinjam
                            </div>

                            <small class="text-muted">
                                {{ $peminjaman->tanggal_pinjam->format('d M Y H:i') }}
                            </small>

                        </div>

                    </div>

                    <div class="d-flex mb-4">

                        <div class="me-3">
                            <span class="badge rounded-pill bg-yellow-lt text-yellow p-2">
                                <i class="bi bi-calendar-event"></i>
                            </span>
                        </div>

                        <div>

                            <div class="fw-semibold">
                                Jatuh Tempo
                            </div>

                            <small class="text-muted">
                                {{ $peminjaman->tanggal_jatuh_tempo->format('d M Y') }}
                            </small>

                        </div>

                    </div>

                    <div class="d-flex">

                        <div class="me-3">

                            @if($peminjaman->tanggal_kembali)

                                <span class="badge rounded-pill bg-green-lt text-green p-2">
                                    <i class="bi bi-check-circle"></i>
                                </span>

                            @else

                                <span class="badge rounded-pill bg-secondary-lt text-secondary p-2">
                                    <i class="bi bi-hourglass-split"></i>
                                </span>

                            @endif

                        </div>

                        <div>

                            <div class="fw-semibold">
                                Pengembalian
                            </div>

                            <small class="text-muted">

                                {{ $peminjaman->tanggal_kembali?->format('d M Y H:i') ?? 'Belum dikembalikan' }}

                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- ====================================================== --}}
        {{-- CATATAN --}}
        {{-- ====================================================== --}}

        <div class="col-12 col-lg-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0">
                        Catatan
                    </h5>
                </div>

                <div class="card-body">

                    @if($peminjaman->catatan)

                        <div class="alert alert-light border mb-0">
                            {{ $peminjaman->catatan }}
                        </div>

                    @else

                        <div class="text-center text-muted py-4">

                            <i class="bi bi-chat-left-text fs-1 d-block mb-3"></i>

                            Belum ada catatan untuk transaksi ini.

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@push("styles")

<style>

@media (max-width:768px){

    .aksi-transaksi{
        display:flex;
        flex-direction:column;
        width:100%;
        gap:.75rem;
    }

    .aksi-transaksi form{
        width:100%;
    }

    .aksi-transaksi .btn,
    .aksi-transaksi a{
        width:100%;
    }

}

</style>

@endpush

@endsection