@extends('layouts.app')

@section('title', 'Data Petugas')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <h2 class="page-title">
                Data Petugas
            </h2>

            <div class="text-secondary mt-1">
                Kelola data, akun, dan hak akses petugas.
            </div>

        </div>

        <div class="col-12 col-md-auto">

            <a
                href="{{ route('petugas.create') }}"
                class="btn btn-primary w-100 w-md-auto"
            >
                <i class="ti ti-plus me-1"></i>
                Tambah Petugas
            </a>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- ALERT --}}
{{-- ========================================================= --}}

@if(session('success'))

    <div
        class="alert alert-success alert-dismissible"
        role="alert"
    >

        <div class="d-flex align-items-center">

            <i class="ti ti-circle-check me-2"></i>

            <div>
                {{ session('success') }}
            </div>

        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


@if(session('warning'))

    <div
        class="alert alert-warning alert-dismissible"
        role="alert"
    >

        <div class="d-flex align-items-center">

            <i class="ti ti-alert-triangle me-2"></i>

            <div>
                {{ session('warning') }}
            </div>

        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


@if(session('error'))

    <div
        class="alert alert-danger alert-dismissible"
        role="alert"
    >

        <div class="d-flex align-items-center">

            <i class="ti ti-alert-circle me-2"></i>

            <div>
                {{ session('error') }}
            </div>

        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- ========================================================= --}}
{{-- DATA PETUGAS --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm">

    <div class="card-header">

        <div>

            <h3 class="card-title mb-1">
                Daftar Petugas
            </h3>

            <div class="text-secondary small">
                Petugas perpustakaan dan petugas absensi.
            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- DESKTOP --}}
    {{-- ===================================================== --}}

    <div class="table-responsive d-none d-lg-block">

        <table class="table table-vcenter card-table">

            <thead>

                <tr>

                    <th>
                        Petugas
                    </th>

                    <th>
                        NIP
                    </th>

                    <th>
                        Username
                    </th>

                    <th>
                        Jenis Petugas
                    </th>

                    <th>
                        No. HP
                    </th>

                    <th>
                        Status
                    </th>

                    <th class="text-end">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($petugas as $item)

                    <tr>

                        {{-- ================================= --}}
                        {{-- PETUGAS --}}
                        {{-- ================================= --}}

                        <td>

                            <div class="d-flex align-items-center">

                                <span
                                    class="
                                        avatar
                                        avatar-sm
                                        me-3
                                        bg-blue-lt
                                        text-blue
                                    "
                                >
                                    {{ strtoupper(substr($item->nama, 0, 1)) }}
                                </span>

                                <div>

                                    <div class="fw-semibold">
                                        {{ $item->nama }}
                                    </div>

                                    <div class="text-secondary small">
                                        {{ $item->user?->email ?? '-' }}
                                    </div>

                                </div>

                            </div>

                        </td>


                        {{-- ================================= --}}
                        {{-- NIP --}}
                        {{-- ================================= --}}

                        <td>
                            {{ $item->nip ?? '-' }}
                        </td>


                        {{-- ================================= --}}
                        {{-- USERNAME --}}
                        {{-- ================================= --}}

                        <td>

                            <div class="d-flex align-items-center gap-1">

                                <i class="ti ti-user text-secondary"></i>

                                <span>
                                    {{ $item->user?->username ?? '-' }}
                                </span>

                            </div>

                        </td>


                        {{-- ================================= --}}
                        {{-- JENIS PETUGAS --}}
                        {{-- ================================= --}}

                        <td>

                            @if($item->user?->role === 'petugas_absensi')

                                <span class="badge bg-cyan-lt text-cyan">

                                    <i class="ti ti-clipboard-check me-1"></i>

                                    Petugas Absensi

                                </span>

                            @elseif($item->user?->role === 'petugas')

                                <span class="badge bg-blue-lt text-blue">

                                    <i class="ti ti-books me-1"></i>

                                    Petugas Perpustakaan

                                </span>

                            @else

                                <span class="badge bg-secondary-lt text-secondary">
                                    {{ $item->user?->role ?? '-' }}
                                </span>

                            @endif

                        </td>


                        {{-- ================================= --}}
                        {{-- NO HP --}}
                        {{-- ================================= --}}

                        <td>
                            {{ $item->no_hp ?? '-' }}
                        </td>


                        {{-- ================================= --}}
                        {{-- STATUS --}}
                        {{-- ================================= --}}

                        <td>

                            @if(
                                $item->is_active &&
                                $item->user?->is_active
                            )

                                <span class="badge bg-green-lt text-green">

                                    <span class="status-dot bg-green me-1"></span>

                                    Aktif

                                </span>

                            @else

                                <span class="badge bg-secondary-lt text-secondary">

                                    <span class="status-dot bg-secondary me-1"></span>

                                    Tidak Aktif

                                </span>

                            @endif

                        </td>


                        {{-- ================================= --}}
                        {{-- AKSI --}}
                        {{-- ================================= --}}

                        <td>

                            <div
                                class="
                                    d-flex
                                    gap-2
                                    justify-content-end
                                "
                            >

                                <a
                                    href="{{ route('petugas.edit', $item) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >

                                    <i class="ti ti-edit me-1"></i>

                                    Edit

                                </a>


                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#globalDeleteModal"
                                    data-delete-action="{{ route('petugas.destroy', $item) }}"
                                    data-delete-name="{{ $item->nama }}"
                                    data-delete-warning="Data petugas beserta akun login akan dihapus secara permanen."
                                >

                                    <i class="ti ti-trash me-1"></i>

                                    Hapus

                                </button>

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center text-secondary py-5"
                        >

                            <i
                                class="ti ti-users"
                                style="font-size:40px"
                            ></i>

                            <div class="mt-2">
                                Belum ada data petugas.
                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- ===================================================== --}}
    {{-- MOBILE / TABLET --}}
    {{-- ===================================================== --}}

    <div class="d-lg-none">

        @forelse($petugas as $item)

            <div class="petugas-mobile-card">

                {{-- ========================================= --}}
                {{-- HEADER --}}
                {{-- ========================================= --}}

                <div
                    class="
                        d-flex
                        align-items-start
                        justify-content-between
                        gap-3
                        mb-3
                    "
                >

                    <div class="d-flex align-items-center gap-3">

                        <span
                            class="
                                avatar
                                bg-blue-lt
                                text-blue
                                flex-shrink-0
                            "
                        >
                            {{ strtoupper(substr($item->nama, 0, 1)) }}
                        </span>


                        <div>

                            <div class="fw-bold">
                                {{ $item->nama }}
                            </div>

                            <div class="text-secondary small mt-1">
                                {{ $item->user?->email ?? '-' }}
                            </div>

                        </div>

                    </div>


                    @if(
                        $item->is_active &&
                        $item->user?->is_active
                    )

                        <span
                            class="
                                badge
                                bg-green-lt
                                text-green
                                flex-shrink-0
                            "
                        >
                            Aktif
                        </span>

                    @else

                        <span
                            class="
                                badge
                                bg-secondary-lt
                                text-secondary
                                flex-shrink-0
                            "
                        >
                            Tidak Aktif
                        </span>

                    @endif

                </div>


                {{-- ========================================= --}}
                {{-- ROLE --}}
                {{-- ========================================= --}}

                <div class="mb-3">

                    @if($item->user?->role === 'petugas_absensi')

                        <span class="badge bg-cyan-lt text-cyan">

                            <i class="ti ti-clipboard-check me-1"></i>

                            Petugas Absensi

                        </span>

                    @elseif($item->user?->role === 'petugas')

                        <span class="badge bg-blue-lt text-blue">

                            <i class="ti ti-books me-1"></i>

                            Petugas Perpustakaan

                        </span>

                    @else

                        <span class="badge bg-secondary-lt text-secondary">
                            {{ $item->user?->role ?? '-' }}
                        </span>

                    @endif

                </div>


                {{-- ========================================= --}}
                {{-- DETAIL --}}
                {{-- ========================================= --}}

                <div class="petugas-mobile-info">

                    <div>

                        <div class="text-secondary small mb-1">
                            NIP
                        </div>

                        <div class="fw-semibold">
                            {{ $item->nip ?? '-' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-secondary small mb-1">
                            Username
                        </div>

                        <div class="fw-semibold">
                            {{ $item->user?->username ?? '-' }}
                        </div>

                    </div>


                    <div>

                        <div class="text-secondary small mb-1">
                            No. HP
                        </div>

                        <div class="fw-semibold">
                            {{ $item->no_hp ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- ========================================= --}}
                {{-- AKSI --}}
                {{-- ========================================= --}}

                <div class="petugas-mobile-actions">

                    <a
                        href="{{ route('petugas.edit', $item) }}"
                        class="btn btn-outline-primary"
                    >

                        <i class="ti ti-edit me-1"></i>

                        Edit

                    </a>


                    <button
                        type="button"
                        class="btn btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#globalDeleteModal"
                        data-delete-action="{{ route('petugas.destroy', $item) }}"
                        data-delete-name="{{ $item->nama }}"
                        data-delete-warning="Data petugas beserta akun login akan dihapus secara permanen."
                    >

                        <i class="ti ti-trash me-1"></i>

                        Hapus

                    </button>

                </div>

            </div>


        @empty

            <div class="text-center text-secondary py-5">

                <i
                    class="ti ti-users"
                    style="font-size:40px"
                ></i>

                <div class="mt-2">
                    Belum ada data petugas.
                </div>

            </div>

        @endforelse

    </div>


    {{-- ===================================================== --}}
    {{-- PAGINATION --}}
    {{-- ===================================================== --}}

    @if($petugas->hasPages())

        <div class="card-footer">

            {{ $petugas->links() }}

        </div>

    @endif

</div>

@endsection


@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | MOBILE PETUGAS
    |--------------------------------------------------------------------------
    */

    .petugas-mobile-card {
        padding: 1rem;

        border-bottom:
            1px solid
            var(--tblr-border-color);
    }

    .petugas-mobile-card:last-child {
        border-bottom: 0;
    }


    .petugas-mobile-info {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 1rem;

        padding: 1rem 0;

        border-top:
            1px solid
            var(--tblr-border-color);

        border-bottom:
            1px solid
            var(--tblr-border-color);
    }


    .petugas-mobile-actions {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: .75rem;

        margin-top: 1rem;
    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE KECIL
    |--------------------------------------------------------------------------
    */

    @media (max-width: 480px) {

        .petugas-mobile-info {
            grid-template-columns: 1fr;
            gap: .75rem;
        }

        .petugas-mobile-actions {
            grid-template-columns: 1fr;
        }

    }

</style>

@endpush