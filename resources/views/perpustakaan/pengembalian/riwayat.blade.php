@extends('layouts.app')

@section('title', 'Riwayat Pengembalian')

@section('content')

<div class="riwayat-pengembalian-page">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div
        class="
            d-flex
            flex-wrap
            justify-content-between
            align-items-center
            gap-3
            mb-4
        "
    >

        <div>

            <h2 class="page-title mb-1">
                Riwayat Pengembalian
            </h2>

            <div class="text-secondary">
                Daftar buku yang telah dikembalikan.
            </div>

        </div>


        <a
            href="{{ route('perpustakaan.pengembalian.index') }}"
            class="btn btn-outline-secondary"
        >

            <i class="ti ti-arrow-left me-1"></i>

            Kembali

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- ALERT --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div
            class="
                alert
                alert-success
                alert-dismissible
                mb-4
            "
            role="alert"
        >

            <div class="d-flex align-items-center">

                <i class="ti ti-circle-check me-2"></i>

                <div>
                    {{ session('success') }}
                </div>

            </div>


            <a
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="close"
            ></a>

        </div>

    @endif


    @if(session('error'))

        <div
            class="
                alert
                alert-danger
                alert-dismissible
                mb-4
            "
            role="alert"
        >

            <div class="d-flex align-items-center">

                <i class="ti ti-alert-circle me-2"></i>

                <div>
                    {{ session('error') }}
                </div>

            </div>


            <a
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="close"
            ></a>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATISTIK --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">


        {{-- ===================================================== --}}
        {{-- TOTAL PENGEMBALIAN --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="
                            d-flex
                            justify-content-between
                            align-items-center
                        "
                    >

                        <div>

                            <div class="text-secondary mb-2">
                                Total Pengembalian
                            </div>

                            <div class="h1 mb-0">

                                {{ $totalPengembalian }}

                            </div>

                        </div>


                        <span
                            class="
                                avatar
                                avatar-lg
                                bg-blue-lt
                            "
                        >

                            <i class="ti ti-books fs-2"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- HARI INI --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="
                            d-flex
                            justify-content-between
                            align-items-center
                        "
                    >

                        <div>

                            <div class="text-secondary mb-2">
                                Hari Ini
                            </div>

                            <div class="h1 mb-0">

                                {{ $pengembalianHariIni }}

                            </div>

                        </div>


                        <span
                            class="
                                avatar
                                avatar-lg
                                bg-green-lt
                            "
                        >

                            <i class="ti ti-calendar-check fs-2"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- BULAN INI --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="
                            d-flex
                            justify-content-between
                            align-items-center
                        "
                    >

                        <div>

                            <div class="text-secondary mb-2">
                                Bulan Ini
                            </div>

                            <div class="h1 mb-0">

                                {{ $pengembalianBulanIni }}

                            </div>

                        </div>


                        <span
                            class="
                                avatar
                                avatar-lg
                                bg-azure-lt
                            "
                        >

                            <i class="ti ti-calendar-month fs-2"></i>

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
                action="{{
                    route(
                        'perpustakaan.pengembalian.riwayat'
                    )
                }}"
            >

                <div class="row g-3">


                    {{-- ================================================= --}}
                    {{-- SEARCH --}}
                    {{-- ================================================= --}}

                    <div class="col-12 col-lg-6">

                        <div class="input-icon">

                            <span class="input-icon-addon">

                                <i class="ti ti-search"></i>

                            </span>


                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Cari kode, nama siswa, atau NIS..."
                                value="{{ request('search') }}"
                            >

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- TANGGAL --}}
                    {{-- ================================================= --}}

                    <div class="col-12 col-md-6 col-lg-3">

                        <input
                            type="date"
                            name="tanggal"
                            class="form-control"
                            value="{{ request('tanggal') }}"
                        >

                    </div>


                    {{-- ================================================= --}}
                    {{-- FILTER BUTTON --}}
                    {{-- ================================================= --}}

                    <div class="col-6 col-md-3 col-lg-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            <i class="ti ti-filter me-1"></i>

                            Filter

                        </button>

                    </div>


                    {{-- ================================================= --}}
                    {{-- RESET --}}
                    {{-- ================================================= --}}

                    <div class="col-6 col-md-3 col-lg-1">

                        <a
                            href="{{
                                route(
                                    'perpustakaan.pengembalian.riwayat'
                                )
                            }}"
                            class="
                                btn
                                btn-outline-secondary
                                w-100
                            "
                            title="Reset Filter"
                        >

                            <i class="ti ti-refresh"></i>

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DAFTAR RIWAYAT --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header">

            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-center
                    w-100
                "
            >

                <div class="d-flex align-items-center">

                    <span
                        class="
                            avatar
                            bg-green-lt
                            me-3
                        "
                    >

                        <i class="ti ti-history"></i>

                    </span>


                    <div>

                        <h3 class="card-title mb-0">
                            Transaksi Selesai
                        </h3>

                    </div>

                </div>


                <span class="badge bg-secondary-lt">

                    {{ $riwayat->total() }} Data

                </span>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- TABLE --}}
        {{-- ===================================================== --}}

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

                        <th>
                            Kode
                        </th>

                        <th>
                            Siswa
                        </th>

                        <th>
                            Kelas
                        </th>

                        <th>
                            Buku
                        </th>

                        <th>
                            Pinjam
                        </th>

                        <th>
                            Kembali
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

                    @forelse($riwayat as $item)


                        {{-- ================================================= --}}
                        {{-- HITUNG DATA --}}
                        {{-- ================================================= --}}

                        @php

                            $totalBuku =
                                $item
                                    ->detailPeminjaman
                                    ->sum('jumlah');


                            $terlambat = false;


                            if (
                                $item->tanggal_kembali
                                &&
                                $item->tanggal_jatuh_tempo
                            ) {

                                $terlambat =
                                    $item
                                        ->tanggal_kembali
                                        ->startOfDay()
                                        ->gt(
                                            $item
                                                ->tanggal_jatuh_tempo
                                                ->startOfDay()
                                        );

                            }

                        @endphp


                        <tr>


                            {{-- ================================================= --}}
                            {{-- KODE --}}
                            {{-- ================================================= --}}

                            <td>

                                <div class="fw-semibold">

                                    {{
                                        $item->kode_peminjaman
                                    }}

                                </div>

                            </td>


                            {{-- ================================================= --}}
                            {{-- SISWA --}}
                            {{-- ================================================= --}}

                            <td>

                                <div
                                    class="
                                        d-flex
                                        align-items-center
                                        gap-2
                                    "
                                >

                                    <span
                                        class="
                                            avatar
                                            avatar-sm
                                            bg-blue-lt
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


                                        <div
                                            class="
                                                text-secondary
                                                small
                                            "
                                        >

                                            {{
                                                $item->siswa?->nis
                                                ?? '-'
                                            }}

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- ================================================= --}}
                            {{-- KELAS --}}
                            {{-- ================================================= --}}

                            <td>

                                {{
                                    $item->siswa?->kelas?->nama_kelas
                                    ??
                                    $item->siswa?->kelas?->nama
                                    ??
                                    '-'
                                }}

                            </td>


                            {{-- ================================================= --}}
                            {{-- BUKU --}}
                            {{-- ================================================= --}}

                            <td>

                                <span class="badge bg-azure-lt">

                                    {{ $totalBuku }} Buku

                                </span>

                            </td>


                            {{-- ================================================= --}}
                            {{-- TANGGAL PINJAM --}}
                            {{-- ================================================= --}}

                            <td>

                                <div class="text-nowrap">

                                    {{
                                        optional(
                                            $item->tanggal_pinjam
                                        )->format('d M Y')
                                    }}

                                </div>

                            </td>


                            {{-- ================================================= --}}
                            {{-- TANGGAL KEMBALI --}}
                            {{-- ================================================= --}}

                            <td>

                                <div class="text-nowrap">

                                    {{
                                        optional(
                                            $item->tanggal_kembali
                                        )->format('d M Y')
                                    }}

                                </div>

                            </td>


                            {{-- ================================================= --}}
                            {{-- STATUS --}}
                            {{-- ================================================= --}}

                            <td>

                                @if($terlambat)

                                    <span class="badge bg-orange-lt">

                                        <i class="ti ti-clock me-1"></i>

                                        Kembali Terlambat

                                    </span>

                                @else

                                    <span class="badge bg-green-lt">

                                        <i
                                            class="
                                                ti
                                                ti-circle-check
                                                me-1
                                            "
                                        ></i>

                                        Tepat Waktu

                                    </span>

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- AKSI --}}
                            {{-- ================================================= --}}

                            <td class="text-end">

                                <button
                                    type="button"
                                    class="
                                        btn
                                        btn-sm
                                        btn-outline-primary
                                        btn-detail-riwayat
                                    "
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetailRiwayat"

                                    data-kode="{{
                                        $item->kode_peminjaman
                                    }}"

                                    data-siswa="{{
                                        $item->siswa?->nama
                                        ?? '-'
                                    }}"

                                    data-nis="{{
                                        $item->siswa?->nis
                                        ?? '-'
                                    }}"

                                    data-kelas="{{
                                        $item->siswa?->kelas?->nama_kelas
                                        ??
                                        $item->siswa?->kelas?->nama
                                        ??
                                        '-'
                                    }}"

                                    data-pinjam="{{
                                        optional(
                                            $item->tanggal_pinjam
                                        )->format('d M Y')
                                    }}"

                                    data-jatuh-tempo="{{
                                        optional(
                                            $item->tanggal_jatuh_tempo
                                        )->format('d M Y')
                                    }}"

                                    data-kembali="{{
                                        optional(
                                            $item->tanggal_kembali
                                        )->format('d M Y')
                                    }}"

                                    data-status="{{
                                        $terlambat
                                            ? 'Kembali Terlambat'
                                            : 'Tepat Waktu'
                                    }}"

                                    data-catatan="{{
                                        $item->catatan
                                        ?? '-'
                                    }}"

                                    data-buku="{{
                                        base64_encode(
                                            json_encode(
                                                $item
                                                    ->detailPeminjaman
                                                    ->map(
                                                        function ($detail) {

                                                            return [

                                                                'nama' =>
                                                                    $detail
                                                                        ->buku
                                                                        ?->nama_buku
                                                                    ?? '-',

                                                                'jumlah' =>
                                                                    $detail
                                                                        ->jumlah,

                                                            ];

                                                        }
                                                    )
                                                    ->values()
                                                    ->toArray()
                                            )
                                        )
                                    }}"
                                >

                                    <i class="ti ti-eye me-1"></i>

                                    Detail

                                </button>

                            </td>

                        </tr>


                    @empty


                        {{-- ================================================= --}}
                        {{-- EMPTY --}}
                        {{-- ================================================= --}}

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5"
                            >

                                <span
                                    class="
                                        avatar
                                        avatar-lg
                                        bg-secondary-lt
                                        mb-3
                                    "
                                >

                                    <i class="ti ti-history fs-2"></i>

                                </span>


                                <div class="fw-semibold">

                                    Belum ada riwayat pengembalian

                                </div>


                                <div
                                    class="
                                        text-secondary
                                        small
                                        mt-1
                                    "
                                >

                                    Transaksi yang selesai akan muncul di sini.

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ===================================================== --}}
        {{-- PAGINATION --}}
        {{-- ===================================================== --}}

        @if($riwayat->hasPages())

            <div class="card-footer">

                {{
                    $riwayat->links()
                }}

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- MODAL DETAIL RIWAYAT --}}
    {{-- ========================================================= --}}

    <div
        class="modal modal-blur fade"
        id="modalDetailRiwayat"
        tabindex="-1"
        aria-hidden="true"
    >

        <div
            class="
                modal-dialog
                modal-lg
                modal-dialog-centered
            "
        >

            <div class="modal-content">


                {{-- ================================================= --}}
                {{-- HEADER --}}
                {{-- ================================================= --}}

                <div class="modal-header">

                    <div
                        class="
                            d-flex
                            align-items-center
                            gap-3
                        "
                    >

                        <span class="avatar bg-blue-lt">

                            <i class="ti ti-receipt"></i>

                        </span>


                        <div>

                            <h3 class="modal-title">
                                Detail Pengembalian
                            </h3>


                            <div
                                id="detailKode"
                                class="text-secondary small"
                            >

                                -

                            </div>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>


                {{-- ================================================= --}}
                {{-- BODY --}}
                {{-- ================================================= --}}

                <div class="modal-body">


                    {{-- ================================================= --}}
                    {{-- SISWA --}}
                    {{-- ================================================= --}}

                    <div
                        class="
                            border
                            rounded-3
                            p-3
                            mb-4
                        "
                    >

                        <div
                            class="
                                d-flex
                                align-items-center
                            "
                        >

                            <span
                                id="detailAvatar"
                                class="
                                    avatar
                                    bg-blue-lt
                                    me-3
                                "
                            >

                                S

                            </span>


                            <div class="flex-fill">

                                <div
                                    id="detailSiswa"
                                    class="fw-semibold"
                                >

                                    -

                                </div>


                                <div class="text-secondary small">

                                    <span id="detailNis">
                                        -
                                    </span>

                                    <span class="mx-1">
                                        •
                                    </span>

                                    <span id="detailKelas">
                                        -
                                    </span>

                                </div>

                            </div>


                            <span
                                id="detailStatus"
                                class="badge bg-green-lt"
                            >

                                -

                            </span>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- TANGGAL --}}
                    {{-- ================================================= --}}

                    <div class="row g-3 mb-4">


                        {{-- PINJAM --}}

                        <div class="col-md-4">

                            <div
                                class="
                                    border
                                    rounded-3
                                    p-3
                                    h-100
                                "
                            >

                                <div
                                    class="
                                        text-secondary
                                        small
                                        mb-1
                                    "
                                >
                                    Tanggal Pinjam
                                </div>


                                <div
                                    id="detailPinjam"
                                    class="fw-semibold"
                                >
                                    -
                                </div>

                            </div>

                        </div>


                        {{-- JATUH TEMPO --}}

                        <div class="col-md-4">

                            <div
                                class="
                                    border
                                    rounded-3
                                    p-3
                                    h-100
                                "
                            >

                                <div
                                    class="
                                        text-secondary
                                        small
                                        mb-1
                                    "
                                >
                                    Jatuh Tempo
                                </div>


                                <div
                                    id="detailJatuhTempo"
                                    class="fw-semibold"
                                >
                                    -
                                </div>

                            </div>

                        </div>


                        {{-- KEMBALI --}}

                        <div class="col-md-4">

                            <div
                                class="
                                    border
                                    rounded-3
                                    p-3
                                    h-100
                                "
                            >

                                <div
                                    class="
                                        text-secondary
                                        small
                                        mb-1
                                    "
                                >
                                    Dikembalikan
                                </div>


                                <div
                                    id="detailKembali"
                                    class="fw-semibold"
                                >
                                    -
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- BUKU --}}
                    {{-- ================================================= --}}

                    <div class="mb-4">

                        <div
                            class="
                                fw-semibold
                                mb-2
                            "
                        >

                            Buku Dikembalikan

                        </div>


                        <div
                            class="
                                border
                                rounded-3
                                overflow-hidden
                            "
                        >

                            <div class="table-responsive">

                                <table
                                    class="
                                        table
                                        table-vcenter
                                        mb-0
                                    "
                                >

                                    <thead>

                                        <tr>

                                            <th>
                                                Buku
                                            </th>

                                            <th
                                                width="100"
                                                class="text-center"
                                            >
                                                Jumlah
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody
                                        id="detailDaftarBuku"
                                    >

                                        <tr>

                                            <td
                                                colspan="2"
                                                class="text-center"
                                            >
                                                -
                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- CATATAN --}}
                    {{-- ================================================= --}}

                    <div>

                        <div class="fw-semibold mb-2">
                            Catatan
                        </div>


                        <div
                            id="detailCatatan"
                            class="
                                bg-light
                                rounded-3
                                p-3
                                text-secondary
                            "
                        >

                            -

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- FOOTER --}}
                {{-- ================================================= --}}

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        Tutup

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


{{-- ========================================================= --}}
{{-- SCRIPT DETAIL --}}
{{-- ========================================================= --}}

@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function()
    {

        /*
        |--------------------------------------------------------------------------
        | BUTTON DETAIL
        |--------------------------------------------------------------------------
        */

        const buttons =
            document.querySelectorAll(
                '.btn-detail-riwayat'
            );


        /*
        |--------------------------------------------------------------------------
        | ELEMENT MODAL
        |--------------------------------------------------------------------------
        */

        const detailKode =
            document.getElementById(
                'detailKode'
            );


        const detailAvatar =
            document.getElementById(
                'detailAvatar'
            );


        const detailSiswa =
            document.getElementById(
                'detailSiswa'
            );


        const detailNis =
            document.getElementById(
                'detailNis'
            );


        const detailKelas =
            document.getElementById(
                'detailKelas'
            );


        const detailPinjam =
            document.getElementById(
                'detailPinjam'
            );


        const detailJatuhTempo =
            document.getElementById(
                'detailJatuhTempo'
            );


        const detailKembali =
            document.getElementById(
                'detailKembali'
            );


        const detailStatus =
            document.getElementById(
                'detailStatus'
            );


        const detailCatatan =
            document.getElementById(
                'detailCatatan'
            );


        const detailDaftarBuku =
            document.getElementById(
                'detailDaftarBuku'
            );


        /*
        |--------------------------------------------------------------------------
        | LOOP BUTTON
        |--------------------------------------------------------------------------
        */

        buttons.forEach(
            function(button)
            {

                button.addEventListener(
                    'click',
                    function()
                    {

                        /*
                        |--------------------------------------------------------------------------
                        | AMBIL DATA
                        |--------------------------------------------------------------------------
                        */

                        const kode =
                            button.dataset.kode
                            || '-';


                        const siswa =
                            button.dataset.siswa
                            || '-';


                        const nis =
                            button.dataset.nis
                            || '-';


                        const kelas =
                            button.dataset.kelas
                            || '-';


                        const pinjam =
                            button.dataset.pinjam
                            || '-';


                        const jatuhTempo =
                            button.dataset.jatuhTempo
                            || '-';


                        const kembali =
                            button.dataset.kembali
                            || '-';


                        const status =
                            button.dataset.status
                            || '-';


                        const catatan =
                            button.dataset.catatan
                            || '-';


                        /*
                        |--------------------------------------------------------------------------
                        | SET DATA
                        |--------------------------------------------------------------------------
                        */

                        detailKode.textContent =
                            kode;


                        detailSiswa.textContent =
                            siswa;


                        detailNis.textContent =
                            nis;


                        detailKelas.textContent =
                            kelas;


                        detailPinjam.textContent =
                            pinjam;


                        detailJatuhTempo.textContent =
                            jatuhTempo;


                        detailKembali.textContent =
                            kembali;


                        detailCatatan.textContent =
                            catatan;


                        /*
                        |--------------------------------------------------------------------------
                        | AVATAR
                        |--------------------------------------------------------------------------
                        */

                        detailAvatar.textContent =
                            siswa !== '-'
                                ? siswa
                                    .charAt(0)
                                    .toUpperCase()
                                : 'S';


                        /*
                        |--------------------------------------------------------------------------
                        | STATUS
                        |--------------------------------------------------------------------------
                        */

                        detailStatus.textContent =
                            status;


                        if (
                            status
                            ===
                            'Kembali Terlambat'
                        )
                        {

                            detailStatus.className =
                                'badge bg-orange-lt';

                        }
                        else
                        {

                            detailStatus.className =
                                'badge bg-green-lt';

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | DECODE DATA BUKU
                        |--------------------------------------------------------------------------
                        */

                        let buku = [];


                        try
                        {

                            const encoded =
                                button.dataset.buku;


                            if (encoded)
                            {

                                const decoded =
                                    atob(encoded);


                                buku =
                                    JSON.parse(
                                        decoded
                                    );

                            }

                        }
                        catch (error)
                        {

                            console.error(
                                'Gagal membaca data buku:',
                                error
                            );


                            buku = [];

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | RENDER BUKU
                        |--------------------------------------------------------------------------
                        */

                        detailDaftarBuku.innerHTML =
                            '';


                        if (
                            !Array.isArray(buku)
                            ||
                            buku.length === 0
                        )
                        {

                            detailDaftarBuku.innerHTML = `

                                <tr>

                                    <td
                                        colspan="2"
                                        class="
                                            text-center
                                            text-secondary
                                            py-4
                                        "
                                    >

                                        Data buku tidak ditemukan.

                                    </td>

                                </tr>

                            `;


                            return;

                        }


                        buku.forEach(
                            function(item)
                            {

                                const row =
                                    document.createElement(
                                        'tr'
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | NAMA
                                |--------------------------------------------------------------------------
                                */

                                const namaCell =
                                    document.createElement(
                                        'td'
                                    );


                                const wrapper =
                                    document.createElement(
                                        'div'
                                    );


                                wrapper.className =
                                    'd-flex align-items-center';


                                const icon =
                                    document.createElement(
                                        'span'
                                    );


                                icon.className =
                                    'avatar avatar-sm bg-azure-lt me-2';


                                icon.innerHTML =
                                    '<i class="ti ti-book"></i>';


                                const nama =
                                    document.createElement(
                                        'span'
                                    );


                                nama.className =
                                    'fw-medium';


                                nama.textContent =
                                    item.nama
                                    || '-';


                                wrapper.appendChild(
                                    icon
                                );


                                wrapper.appendChild(
                                    nama
                                );


                                namaCell.appendChild(
                                    wrapper
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | JUMLAH
                                |--------------------------------------------------------------------------
                                */

                                const jumlahCell =
                                    document.createElement(
                                        'td'
                                    );


                                jumlahCell.className =
                                    'text-center';


                                const jumlahBadge =
                                    document.createElement(
                                        'span'
                                    );


                                jumlahBadge.className =
                                    'badge bg-blue-lt';


                                jumlahBadge.textContent =
                                    Number(
                                        item.jumlah
                                        || 0
                                    );


                                jumlahCell.appendChild(
                                    jumlahBadge
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | APPEND
                                |--------------------------------------------------------------------------
                                */

                                row.appendChild(
                                    namaCell
                                );


                                row.appendChild(
                                    jumlahCell
                                );


                                detailDaftarBuku.appendChild(
                                    row
                                );

                            }
                        );

                    }
                );

            }
        );

    }
);

</script>

@endpush