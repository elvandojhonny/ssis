@extends('layouts.app')

@section('title', 'Peminjaman Buku')

@section('content')
<div class="container-fluid">

    <div
    class="
        peminjaman-header
        d-flex
        justify-content-between
        align-items-start
        gap-3
        mb-4
    "
>

    {{-- ========================================================= --}}
    {{-- JUDUL --}}
    {{-- ========================================================= --}}

    <div>

        <h3 class="fw-bold mb-1">
            Peminjaman Buku
        </h3>

        <small class="text-muted">
            Kelola transaksi peminjaman buku perpustakaan.
        </small>

    </div>


    {{-- ========================================================= --}}
    {{-- TAMBAH PEMINJAMAN --}}
    {{-- ========================================================= --}}

    <div class="peminjaman-header-action">

        <a
            href="{{ route('perpustakaan.peminjaman.create') }}"
            class="btn btn-primary"
        >

            <i class="bi bi-qr-code-scan me-1"></i>

            Tambahkan Peminjaman

        </a>

    </div>

</div>

    <div class="row mb-4">

        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">
                        Sedang Dipinjam
                    </h6>

                    <h2>
                        {{ $totalDipinjaman }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">
                        Terlambat
                    </h6>

                    <h2 class="text-danger">
                        {{ $totalTerlambat }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">
                        Hari Ini
                    </h6>

                    <h2 class="text-success">
                        {{ $totalHariIni }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">
                        Transaksi
                    </h6>

                    <h2>
                        {{ $totalAktif }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('perpustakaan.peminjaman.index') }}">

                @if(request('tingkat'))

                    <input
                        type="hidden"
                        name="tingkat"
                        value="{{ request('tingkat') }}">

                @endif

                <div class="row g-3 filter-row">

                    <div class="col-md-6">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari kode / nama siswa..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <select
                            name="status"
                            class="form-select">

                            <option value="">Semua Status</option>

                            <option
                                value="dipinjam"
                                @selected(request('status') == 'dipinjam')>
                                Dipinjam
                            </option>

                            <option
                                value="terlambat"
                                @selected(request('status') == 'terlambat')>
                                Terlambat
                            </option>

                        </select>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- ========================================================= --}}
{{-- TAB KELAS --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body p-0">

        <div class="peminjaman-tabs">

            {{-- ================================================= --}}
            {{-- SEMUA --}}
            {{-- ================================================= --}}

            <a
                href="{{
                    route(
                        'perpustakaan.peminjaman.index',
                        array_filter([
                            'search' => request('search'),
                            'status' => request('status'),
                        ])
                    )
                }}"
                class="
                    peminjaman-tab
                    {{ !request('tingkat') ? 'active' : '' }}
                ">

                <i class="ti ti-books"></i>

                <span>
                    Semua
                </span>

            </a>


            {{-- ================================================= --}}
            {{-- KELAS X --}}
            {{-- ================================================= --}}

            <a
                href="{{
                    route(
                        'perpustakaan.peminjaman.index',
                        array_filter([
                            'tingkat' => 'X',
                            'search' => request('search'),
                            'status' => request('status'),
                        ])
                    )
                }}"
                class="
                    peminjaman-tab
                    {{ request('tingkat') === 'X' ? 'active' : '' }}
                ">

                <i class="ti ti-school"></i>

                <span>
                    Kelas X
                </span>

            </a>


            {{-- ================================================= --}}
            {{-- KELAS XI --}}
            {{-- ================================================= --}}

            <a
                href="{{
                    route(
                        'perpustakaan.peminjaman.index',
                        array_filter([
                            'tingkat' => 'XI',
                            'search' => request('search'),
                            'status' => request('status'),
                        ])
                    )
                }}"
                class="
                    peminjaman-tab
                    {{ request('tingkat') === 'XI' ? 'active' : '' }}
                ">

                <i class="ti ti-school"></i>

                <span>
                    Kelas XI
                </span>

            </a>


            {{-- ================================================= --}}
            {{-- KELAS XII --}}
            {{-- ================================================= --}}

            <a
                href="{{
                    route(
                        'perpustakaan.peminjaman.index',
                        array_filter([
                            'tingkat' => 'XII',
                            'search' => request('search'),
                            'status' => request('status'),
                        ])
                    )
                }}"
                class="
                    peminjaman-tab
                    {{ request('tingkat') === 'XII' ? 'active' : '' }}
                ">

                <i class="ti ti-school"></i>

                <span>
                    Kelas XII
                </span>

            </a>

        </div>

    </div>

</div>

    <div class="card border-0 shadow-sm d-none d-lg-block">

        <div class="table-responsive d-none d-lg-block">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Peminjam</th>
                        <th>Kelas</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($peminjaman as $item)
                        <tr>

                            <td>
                                <strong>{{ $item->kode_peminjaman }}</strong>
                            </td>

                            <td>
                                {{ $item->nama_peminjam }}
                            </td>

                            <td>
                                {{ optional($item->siswa?->kelas)->nama ?? '-' }}
                            </td>

                            <td>
                                {{ $item->tanggal_pinjam->format('d M Y') }}
                            </td>

                            <td>
                                {{ $item->tanggal_jatuh_tempo->format('d M Y') }}
                            </td>

                            <td>
                                @php
                                    $statusClass = match ($item->status) {
                                        'dipinjam'     => 'bg-blue-lt text-blue',
                                        'terlambat'    => 'bg-red-lt text-red',
                                        default         => 'bg-secondary-lt text-secondary',
                                    };
                                @endphp

                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <td>
                                <a
                                    href="{{ route('perpustakaan.peminjaman.show', $item) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>

                        </tr>
                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-5">
                                Belum ada transaksi.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="card-footer bg-white">
            {{ $peminjaman->links() }}
        </div>

    </div>

    <div class="d-lg-none">

    @forelse($peminjaman as $item)

        @php
            $statusClass = match ($item->status) {
                'dipinjam'     => 'bg-blue-lt text-blue',
                'terlambat'    => 'bg-red-lt text-red',
                default         => 'bg-secondary-lt text-secondary',
            };
        @endphp

        <div class="card border-0 shadow-sm mb-3">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div>
                        <h6 class="fw-bold mb-1">
                            {{ $item->nama_peminjam }}
                        </h6>

                        <small class="text-muted">
                            {{ $item->kode_peminjaman }}
                        </small>
                    </div>

                    <span class="badge {{ $statusClass }}">
                        {{ ucfirst($item->status) }}
                    </span>

                </div>

                <div class="row g-3">

                    <div class="col-6">
                        <small class="text-muted d-block">Kelas</small>
                        <strong>{{ optional($item->siswa?->kelas)->nama ?? '-' }}</strong>
                    </div>

                    <div class="col-6">
                        <small class="text-muted d-block">Tanggal Pinjam</small>
                        <strong>{{ $item->tanggal_pinjam->format('d M Y') }}</strong>
                    </div>

                    <div class="col-12">
                        <small class="text-muted d-block">Jatuh Tempo</small>
                        <strong>{{ $item->tanggal_jatuh_tempo->format('d M Y') }}</strong>
                    </div>

                </div>

                <a
                    href="{{ route('perpustakaan.peminjaman.show', $item) }}"
                    class="btn btn-primary btn-sm w-100 mt-3">
                    <i class="bi bi-eye me-1"></i>
                    Lihat Detail
                </a>

            </div>

        </div>

    @empty

        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                Belum ada transaksi.
            </div>
        </div>

    @endforelse

    <div class="mt-3 d-flex justify-content-center">
        {{ $peminjaman->links() }}
    </div>

</div>

</div>

<style>

    /*
    |--------------------------------------------------------------------------
    | TAB PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    .peminjaman-tabs {
        display: flex;
        align-items: center;
        overflow-x: auto;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .peminjaman-tabs::-webkit-scrollbar {
        display: none;
    }

    .peminjaman-tab {
        position: relative;

        display: flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        min-width: 130px;

        padding: 15px 20px;

        color: var(--tblr-secondary);

        text-decoration: none;

        font-size: 14px;
        font-weight: 500;

        white-space: nowrap;

        border-right: 1px solid var(--tblr-border-color);

        transition: .2s ease;
    }

    .peminjaman-tab:last-child {
        border-right: 0;
    }

    .peminjaman-tab:hover {
        color: var(--tblr-primary);
        background: rgba(var(--tblr-primary-rgb), .04);
        text-decoration: none;
    }

    .peminjaman-tab.active {
        color: var(--tblr-primary);
        font-weight: 600;
        background: rgba(var(--tblr-primary-rgb), .05);
    }

    .peminjaman-tab.active::after {
        content: '';

        position: absolute;

        left: 18px;
        right: 18px;
        bottom: 0;

        height: 3px;

        border-radius: 3px 3px 0 0;

        background: var(--tblr-primary);
    }


    /*
    |--------------------------------------------------------------------------
    | TABLET / MOBILE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 991px) {

        .filter-row .col-md-6,
        .filter-row .col-md-3 {
            width: 100%;
        }

        .filter-row .btn {
            width: 100%;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 575.98px) {

        .peminjaman-tabs {
            width: 100%;
        }

        .peminjaman-tab {
            min-width: 105px;

            padding:
                13px
                14px;

            font-size: 13px;

            gap: 5px;
        }

        .peminjaman-tab i {
            font-size: 16px;
        }

        .peminjaman-tab.active::after {
            left: 12px;
            right: 12px;
        }

    }

    /*
|--------------------------------------------------------------------------
| HEADER PEMINJAMAN
|--------------------------------------------------------------------------
*/

.peminjaman-header {
    width: 100%;
}

.peminjaman-header-action {
    flex: 0 0 auto;
}

.peminjaman-header-action .btn {
    width: auto;
    white-space: nowrap;
}


/*
|--------------------------------------------------------------------------
| MOBILE HEADER
|--------------------------------------------------------------------------
*/

@media (max-width: 767.98px) {

    .peminjaman-header {
        flex-direction: column;
    }

    .peminjaman-header-action {
        width: 100%;
    }

    .peminjaman-header-action .btn {
        width: 100%;
    }

}

</style>

@endsection