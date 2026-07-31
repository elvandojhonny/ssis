@extends('layouts.app')

@section('title', 'Data Buku')

@section('content')

<div class="container-xl">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h2 class="page-title fw-bold mb-1">
                Data Buku
            </h2>

            <div class="text-secondary">
                Kelola koleksi dan stok buku perpustakaan.
            </div>
        </div>

        <a
            href="{{ route('perpustakaan.buku.create') }}"
            class="btn btn-primary">

            <i class="ti ti-plus me-1"></i>
            Tambah Buku

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- ALERT --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible" role="alert">

            <div class="d-flex align-items-center">

                <i class="ti ti-circle-check me-2"></i>

                <div>
                    {{ session('success') }}
                </div>

            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible" role="alert">

            <div class="d-flex align-items-center">

                <i class="ti ti-alert-circle me-2"></i>

                <div>
                    {{ session('error') }}
                </div>

            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- CARD UTAMA --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm">

        {{-- ===================================================== --}}
        {{-- CARD HEADER --}}
        {{-- ===================================================== --}}

        <div class="card-header bg-transparent border-bottom">

            <div class="d-flex align-items-center">

                <span class="avatar bg-primary text-white me-3">

                    <i class="ti ti-books"></i>

                </span>

                <div>

                    <h3 class="card-title fw-semibold mb-0">
                        Daftar Buku
                    </h3>

                    <div class="text-secondary small mt-1">
                        {{ $buku->total() }} buku terdaftar
                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- SEARCH --}}
        {{-- ===================================================== --}}

        <div class="card-body border-bottom">

    <form
        method="GET"
        action="{{ route('perpustakaan.buku.index') }}">

        {{-- Pertahankan tab saat melakukan pencarian --}}
        @if(request('tingkat'))

            <input
                type="hidden"
                name="tingkat"
                value="{{ request('tingkat') }}">

        @endif


        <div class="row g-2">

            <div class="col-12 col-md">

                <div class="input-icon">

                    <span class="input-icon-addon">

                        <i class="ti ti-search"></i>

                    </span>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari nama buku..."
                        value="{{ request('search') }}">

                </div>

            </div>


            <div class="col-6 col-md-auto">

                <button
                    type="submit"
                    class="btn btn-primary w-100">

                    <i class="ti ti-search me-1"></i>

                    Cari

                </button>

            </div>


            @if(request('search'))

                <div class="col-6 col-md-auto">

                    <a
                        href="{{
                            route(
                                'perpustakaan.buku.index',
                                request('tingkat')
                                    ? ['tingkat' => request('tingkat')]
                                    : []
                            )
                        }}"
                        class="btn btn-outline-secondary w-100">

                        <i class="ti ti-refresh me-1"></i>

                        Reset

                    </a>

                </div>

            @endif

        </div>

    </form>

</div>

{{-- ========================================================= --}}
{{-- TAB KELAS --}}
{{-- ========================================================= --}}

<div class="border-bottom">

    <div class="px-3 px-md-4">

        <div class="nav nav-tabs border-0 flex-nowrap overflow-auto">

            {{-- SEMUA --}}

            <a
                href="{{
                    route(
                        'perpustakaan.buku.index',
                        request('search')
                            ? ['search' => request('search')]
                            : []
                    )
                }}"
                class="
                    nav-link
                    py-3
                    {{ !request('tingkat') ? 'active' : '' }}
                ">

                <i class="ti ti-books me-2"></i>

                Semua Buku

            </a>


            {{-- KELAS X --}}

            <a
                href="{{
                    route(
                        'perpustakaan.buku.index',
                        array_filter([
                            'tingkat' => 'X',
                            'search' => request('search'),
                        ])
                    )
                }}"
                class="
                    nav-link
                    py-3
                    {{ request('tingkat') === 'X' ? 'active' : '' }}
                ">

                <i class="ti ti-school me-2"></i>

                Kelas X

            </a>


            {{-- KELAS XI --}}

            <a
                href="{{
                    route(
                        'perpustakaan.buku.index',
                        array_filter([
                            'tingkat' => 'XI',
                            'search' => request('search'),
                        ])
                    )
                }}"
                class="
                    nav-link
                    py-3
                    {{ request('tingkat') === 'XI' ? 'active' : '' }}
                ">

                <i class="ti ti-school me-2"></i>

                Kelas XI

            </a>


            {{-- KELAS XII --}}

            <a
                href="{{
                    route(
                        'perpustakaan.buku.index',
                        array_filter([
                            'tingkat' => 'XII',
                            'search' => request('search'),
                        ])
                    )
                }}"
                class="
                    nav-link
                    py-3
                    {{ request('tingkat') === 'XII' ? 'active' : '' }}
                ">

                <i class="ti ti-school me-2"></i>

                Kelas XII

            </a>

        </div>

    </div>

</div>


        {{-- ===================================================== --}}
        {{-- DESKTOP / TABLET TABLE --}}
        {{-- ===================================================== --}}

        <div class="d-none d-md-block">

            <div class="table-responsive">

                <table class="table table-vcenter table-hover card-table mb-0">

                    <thead>

                        <tr>

                            <th width="70">
                                No
                            </th>

                            <th>
                                Buku
                            </th>

                            <th>
                                Kelas
                            </th>

                            <th class="text-center" width="110">
                                Total
                            </th>

                            <th class="text-center" width="120">
                                Tersedia
                            </th>

                            <th class="text-center" width="120">
                                Status
                            </th>

                            <th class="text-end" width="120">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($buku as $item)

                            <tr>

                                {{-- NOMOR --}}
                                <td class="text-secondary">

                                    {{
                                        $loop->iteration
                                        + ($buku->firstItem() - 1)
                                    }}

                                </td>


                                {{-- BUKU --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        <span class="avatar bg-azure-lt me-3">

                                            <i class="ti ti-book"></i>

                                        </span>

                                        <div>

                                            <div class="fw-semibold text-body">

                                                {{ $item->nama_buku }}

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- KELAS --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        <i class="ti ti-school me-2 text-secondary"></i>

                                        <span class="text-body">

                                            {{ $item->kelas->tingkat ?? '-' }}

                                        </span>

                                    </div>

                                </td>


                                {{-- TOTAL --}}
                                <td class="text-center">

                                    <span class="fw-semibold text-body">

                                        {{ $item->jumlah }}

                                    </span>

                                </td>


                                {{-- TERSEDIA --}}
                                <td class="text-center">

                                    @if($item->jumlah_tersedia > 0)

                                        <span class="badge bg-success-lt">

                                            {{ $item->jumlah_tersedia }}

                                        </span>

                                    @else

                                        <span class="badge bg-danger-lt">

                                            0

                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}
                                <td class="text-center">

                                    @if($item->is_active)

                                        <span class="badge bg-success text-white">

                                            <i class="ti ti-circle-check me-1"></i>

                                            Aktif

                                        </span>

                                    @else

                                        <span class="badge bg-secondary text-white">

                                            <i class="ti ti-circle-x me-1"></i>

                                            Nonaktif

                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td>

                                    <div class="d-flex justify-content-end gap-2">

                                        <a
                                            href="{{ route('perpustakaan.buku.edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-primary btn-icon"
                                            title="Edit Buku">

                                            <i class="ti ti-edit"></i>

                                        </a>


                                        <form
                                            action="{{ route('perpustakaan.buku.destroy', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus buku {{ addslashes($item->nama_buku) }}?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger btn-icon"
                                                title="Hapus Buku">

                                                <i class="ti ti-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5">

                                    <div class="empty">

                                        <div class="empty-icon">

                                            <span class="avatar avatar-lg bg-secondary-lt">

                                                <i class="ti ti-books fs-2"></i>

                                            </span>

                                        </div>

                                        <p class="empty-title mt-3 mb-1">
                                            Belum ada buku
                                        </p>

                                        <p class="empty-subtitle text-secondary mb-3">

                                            Data buku yang ditambahkan akan tampil di sini.

                                        </p>

                                        <a
                                            href="{{ route('perpustakaan.buku.create') }}"
                                            class="btn btn-primary btn-sm">

                                            <i class="ti ti-plus me-1"></i>

                                            Tambah Buku

                                        </a>

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

        <div class="d-md-none">

            @forelse($buku as $item)

                <div class="p-3 border-bottom">

                    {{-- HEADER BUKU --}}
                    <div class="d-flex align-items-start mb-3">

                        <span class="avatar bg-azure-lt me-3 flex-shrink-0">

                            <i class="ti ti-book"></i>

                        </span>


                        <div class="flex-fill min-width-0">

                            <div class="fw-bold text-body mb-1">

                                {{ $item->nama_buku }}

                            </div>


                            <div class="small text-secondary">

                                <i class="ti ti-school me-1"></i>

                                {{ $item->kelas->tingkat ?? '-' }}

                                @if($item->kelas?->nama)
                                    - {{ $item->kelas->nama }}
                                @endif

                            </div>

                        </div>


                        <div class="ms-2">

                            @if($item->is_active)

                                <span class="badge bg-success text-white">
                                    Aktif
                                </span>

                            @else

                                <span class="badge bg-secondary text-white">
                                    Nonaktif
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- INFORMASI STOK --}}
                    <div class="row g-2 mb-3">

                        <div class="col-6">

                            <div class="border rounded-3 p-2">

                                <div class="small text-secondary mb-1">
                                    Total Buku
                                </div>

                                <div class="fw-bold text-body">

                                    <i class="ti ti-books me-1 text-primary"></i>

                                    {{ $item->jumlah }}

                                </div>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="border rounded-3 p-2">

                                <div class="small text-secondary mb-1">
                                    Tersedia
                                </div>

                                <div
                                    class="fw-bold
                                        {{ $item->jumlah_tersedia > 0
                                            ? 'text-success'
                                            : 'text-danger'
                                        }}">

                                    <i class="ti ti-book-2 me-1"></i>

                                    {{ $item->jumlah_tersedia }}

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- AKSI --}}
                    <div class="d-flex gap-2">

                        <a
                            href="{{ route('perpustakaan.buku.edit', $item->id) }}"
                            class="btn btn-outline-primary btn-sm flex-fill">

                            <i class="ti ti-edit me-1"></i>

                            Edit

                        </a>


                        <form
                            action="{{ route('perpustakaan.buku.destroy', $item->id) }}"
                            method="POST"
                            class="flex-fill"
                            onsubmit="return confirm('Yakin ingin menghapus buku {{ addslashes($item->nama_buku) }}?')">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-outline-danger btn-sm w-100">

                                <i class="ti ti-trash me-1"></i>

                                Hapus

                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="text-center px-3 py-5">

                    <span class="avatar avatar-lg bg-secondary-lt mb-3">

                        <i class="ti ti-books fs-2"></i>

                    </span>

                    <div class="fw-bold text-body mb-1">
                        Belum ada buku
                    </div>

                    <div class="text-secondary small mb-3">
                        Data buku yang ditambahkan akan tampil di sini.
                    </div>

                    <a
                        href="{{ route('perpustakaan.buku.create') }}"
                        class="btn btn-primary btn-sm">

                        <i class="ti ti-plus me-1"></i>

                        Tambah Buku

                    </a>

                </div>

            @endforelse

        </div>


        {{-- ===================================================== --}}
        {{-- PAGINATION --}}
        {{-- ===================================================== --}}

        @if($buku->hasPages())

            <div class="card-footer bg-transparent">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">

                    <div class="small text-secondary">

                        Menampilkan

                        <strong class="text-body">
                            {{ $buku->firstItem() }}
                        </strong>

                        –

                        <strong class="text-body">
                            {{ $buku->lastItem() }}
                        </strong>

                        dari

                        <strong class="text-body">
                            {{ $buku->total() }}
                        </strong>

                        buku

                    </div>


                    <div>

                        {{ $buku->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection