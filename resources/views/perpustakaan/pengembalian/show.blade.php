@extends('layouts.app')

@section('title', 'Proses Pengembalian')

@section('content')

<style>

    /*
    |--------------------------------------------------------------------------
    | BASE
    |--------------------------------------------------------------------------
    */

    .pengembalian-show {
        width: 100%;
    }

    .pengembalian-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }

    .mobile-book-list {
        display: none;
    }


    /*
    |--------------------------------------------------------------------------
    | INFORMASI TRANSAKSI
    |--------------------------------------------------------------------------
    */

    .transaction-info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        padding: 12px 0;
        border-bottom: 1px solid var(--tblr-border-color);
    }

    .transaction-info-row:first-child {
        padding-top: 0;
    }

    .transaction-info-row:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .transaction-info-label {
        font-size: 13px;
        color: var(--tblr-secondary);
        flex-shrink: 0;
    }

    .transaction-info-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--tblr-body-color);
        text-align: right;
        min-width: 0;
        overflow-wrap: anywhere;
    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767.98px) {

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .pengembalian-header {
            align-items: flex-start;
            margin-bottom: 18px;
        }

        .pengembalian-header .page-title {
            font-size: 20px;
            line-height: 1.3;
        }

        .pengembalian-header .btn {
            flex-shrink: 0;
        }


        /*
        |--------------------------------------------------------------------------
        | GRID
        |--------------------------------------------------------------------------
        */

        .pengembalian-show .row.g-4 {
            --tblr-gutter-y: 1rem;
        }


        /*
        |--------------------------------------------------------------------------
        | CARD
        |--------------------------------------------------------------------------
        */

        .pengembalian-show .card {
            margin-bottom: 16px !important;
        }

        .pengembalian-show .card-header {
            padding: 14px 16px;
            min-height: auto;
        }

        .pengembalian-show .card-body {
            padding: 16px;
        }

        .pengembalian-show .card-title {
            font-size: 15px;
        }


        /*
        |--------------------------------------------------------------------------
        | PEMINJAM
        |--------------------------------------------------------------------------
        */

        .peminjam-row {
            align-items: flex-start !important;
        }

        .peminjam-avatar {
            width: 42px;
            height: 42px;
            min-width: 42px;
        }

        .peminjam-name {
            font-size: 15px;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 3px;
        }

        .peminjam-detail {
            font-size: 12px;
            line-height: 1.6;
        }


        /*
        |--------------------------------------------------------------------------
        | BUKU
        |--------------------------------------------------------------------------
        */

        .desktop-book-table {
            display: none;
        }

        .mobile-book-list {
            display: block;
        }

        .mobile-book-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--tblr-border-color);
        }

        .mobile-book-item:last-child {
            border-bottom: 0;
        }

        .mobile-book-icon {
            flex-shrink: 0;
        }

        .mobile-book-name {
            flex: 1;
            min-width: 0;
            font-size: 14px;
            font-weight: 600;
            color: var(--tblr-body-color);
            overflow-wrap: anywhere;
        }

        .mobile-book-qty {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            flex-shrink: 0;
        }

        .mobile-book-qty span {
            font-size: 11px;
        }

        .mobile-book-qty strong {
            font-size: 14px;
        }


        /*
        |--------------------------------------------------------------------------
        | FORM ACTION
        |--------------------------------------------------------------------------
        */

        .form-action-pengembalian {
            display: flex !important;
            flex-direction: column;
            gap: 8px !important;
        }

        .form-action-pengembalian .btn {
            width: 100%;
            min-height: 42px;
        }

        .form-action-pengembalian #btnKonfirmasiPengembalian {
            order: 1;
        }

        .form-action-pengembalian .btn-outline-secondary {
            order: 2;
        }


        /*
        |--------------------------------------------------------------------------
        | ALERT
        |--------------------------------------------------------------------------
        */

        .pengembalian-show .alert {
            font-size: 13px;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE KECIL
    |--------------------------------------------------------------------------
    */

    @media (max-width: 420px) {

        .pengembalian-header {
            gap: 10px;
        }

        .pengembalian-header .page-title {
            font-size: 18px;
        }

        .pengembalian-header .btn {
            padding-left: 10px;
            padding-right: 10px;
        }

        .peminjam-row {
            gap: 10px !important;
        }

        .peminjam-status .badge {
            font-size: 10px;
        }

        .mobile-book-item {
            padding: 12px 14px;
            gap: 10px;
        }

    }

</style>


<div class="pengembalian-show">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="pengembalian-header">

        <div>

            <h2 class="page-title mb-1">
                Proses Pengembalian
            </h2>

            <div class="text-secondary small">
                {{ $peminjaman->kode_peminjaman }}
            </div>

        </div>


        <a
            href="{{ route('perpustakaan.pengembalian.index') }}"
            class="btn btn-outline-secondary">

            <i class="ti ti-arrow-left me-1"></i>

            Kembali

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- ALERT ERROR --}}
    {{-- ========================================================= --}}

    @if(session('error'))

        <div
            class="alert alert-danger alert-dismissible mb-4"
            role="alert">

            <div class="d-flex align-items-start">

                <i class="ti ti-alert-circle me-2 mt-1"></i>

                <div>
                    {{ session('error') }}
                </div>

            </div>


            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- VALIDATION ERROR --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger mb-4">

            <div class="d-flex align-items-start">

                <i class="ti ti-alert-circle me-2 mt-1"></i>

                <div>

                    <div class="fw-semibold mb-1">
                        Data belum dapat diproses.
                    </div>

                    @foreach($errors->all() as $error)

                        <div>
                            {{ $error }}
                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- GRID UTAMA --}}
    {{-- ========================================================= --}}

    <div class="row g-4">


        {{-- ===================================================== --}}
        {{-- KOLOM KIRI --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-lg-8">


            {{-- ================================================= --}}
            {{-- DATA PEMINJAM --}}
            {{-- ================================================= --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header">

                    <div class="d-flex align-items-center">

                        <span class="avatar bg-blue-lt me-3">

                            <i class="ti ti-user"></i>

                        </span>

                        <h3 class="card-title mb-0">
                            Data Peminjam
                        </h3>

                    </div>

                </div>


                <div class="card-body">

                    <div class="d-flex align-items-center gap-3 peminjam-row">

                        {{-- AVATAR --}}

                        <span
                            class="
                                avatar
                                avatar-lg
                                bg-primary-lt
                                flex-shrink-0
                                peminjam-avatar
                            ">

                            {{
                                strtoupper(
                                    substr(
                                        $peminjaman->siswa?->nama ?? 'S',
                                        0,
                                        1
                                    )
                                )
                            }}

                        </span>


                        {{-- IDENTITAS --}}

                        <div class="flex-fill min-width-0">

                            <div class="peminjam-name">

                                {{ $peminjaman->siswa?->nama ?? '-' }}

                            </div>


                            <div class="text-secondary peminjam-detail">

                                <div>

                                    NIS:
                                    {{ $peminjaman->siswa?->nis ?? '-' }}

                                </div>

                                <div>

                                    Kelas:
                                    {{
                                        $peminjaman->siswa?->kelas?->nama_kelas
                                        ??
                                        $peminjaman->siswa?->kelas?->nama
                                        ??
                                        '-'
                                    }}

                                </div>

                            </div>

                        </div>


                        {{-- STATUS --}}

                        <div class="flex-shrink-0 peminjam-status">

                            @if($peminjaman->status === 'terlambat')

                                <span class="badge bg-red-lt">

                                    <i class="ti ti-clock me-1"></i>

                                    Terlambat

                                </span>

                            @else

                                <span class="badge bg-orange-lt">

                                    <i class="ti ti-book-upload me-1"></i>

                                    Dipinjam

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- DAFTAR BUKU --}}
            {{-- ================================================= --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header">

                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-between
                            w-100
                        ">

                        <div class="d-flex align-items-center">

                            <span class="avatar bg-azure-lt me-3">

                                <i class="ti ti-books"></i>

                            </span>

                            <h3 class="card-title mb-0">
                                Buku Dikembalikan
                            </h3>

                        </div>


                        <span class="badge bg-blue-lt">

                            {{ $totalBuku }} Buku

                        </span>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- DESKTOP TABLE --}}
                {{-- ================================================= --}}

                <div class="table-responsive desktop-book-table">

                    <table class="table table-vcenter card-table mb-0">

                        <thead>

                            <tr>

                                <th width="60">
                                    No
                                </th>

                                <th>
                                    Nama Buku
                                </th>

                                <th class="text-center" width="120">
                                    Jumlah
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

                                    <td>

                                        <span
                                            class="
                                                avatar
                                                avatar-sm
                                                bg-secondary-lt
                                            ">

                                            {{ $loop->iteration }}

                                        </span>

                                    </td>


                                    {{-- BUKU --}}

                                    <td>

                                        <div class="d-flex align-items-center">

                                            <span class="avatar bg-azure-lt me-3">

                                                <i class="ti ti-book"></i>

                                            </span>


                                            <div class="fw-semibold">

                                                {{
                                                    $detail->buku?->nama_buku
                                                    ?? 'Buku tidak ditemukan'
                                                }}

                                            </div>

                                        </div>

                                    </td>


                                    {{-- JUMLAH --}}

                                    <td class="text-center">

                                        <span class="badge bg-primary-lt">

                                            {{ $detail->jumlah }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="text-center py-5">

                                        <span
                                            class="
                                                avatar
                                                avatar-lg
                                                bg-secondary-lt
                                                mb-3
                                            ">

                                            <i class="ti ti-books fs-2"></i>

                                        </span>

                                        <div class="fw-semibold">
                                            Detail buku tidak ditemukan
                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- ================================================= --}}
                {{-- MOBILE BOOK LIST --}}
                {{-- ================================================= --}}

                <div class="mobile-book-list">

                    @forelse(
                        $peminjaman->detailPeminjaman
                        as $detail
                    )

                        <div class="mobile-book-item">

                            <span
                                class="
                                    avatar
                                    avatar-sm
                                    bg-azure-lt
                                    mobile-book-icon
                                ">

                                <i class="ti ti-book"></i>

                            </span>


                            <div class="mobile-book-name">

                                {{
                                    $detail->buku?->nama_buku
                                    ?? 'Buku tidak ditemukan'
                                }}

                            </div>


                            <div class="mobile-book-qty">

                                <span class="text-secondary">
                                    Jumlah
                                </span>

                                <strong>
                                    {{ $detail->jumlah }}
                                </strong>

                            </div>

                        </div>

                    @empty

                        <div class="text-center text-secondary py-4">

                            <i class="ti ti-books fs-2 mb-2 d-block"></i>

                            Detail buku tidak ditemukan.

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- FORM PENGEMBALIAN --}}
            {{-- ================================================= --}}

            <div class="card border-0 shadow-sm mb-0">

                <div class="card-header">

                    <div class="d-flex align-items-center">

                        <span class="avatar bg-green-lt me-3">

                            <i class="ti ti-book-download"></i>

                        </span>

                        <h3 class="card-title mb-0">
                            Konfirmasi Pengembalian
                        </h3>

                    </div>

                </div>


                <div class="card-body">

                    <form
                        id="formPengembalian"
                        class="no-loading"
                        action="{{
                            route(
                                'perpustakaan.pengembalian.store',
                                $peminjaman
                            )
                        }}"
                        method="POST">

                        @csrf


                        {{-- ========================================= --}}
                        {{-- TANGGAL --}}
                        {{-- ========================================= --}}

                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal Pengembalian
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ now()->format('d M Y') }}"
                                readonly>

                            <div class="form-hint">
                                Tanggal dicatat otomatis oleh sistem.
                            </div>

                        </div>


                        {{-- ========================================= --}}
                        {{-- CATATAN --}}
                        {{-- ========================================= --}}

                        <div class="mb-4">

                            <label
                                for="catatan_pengembalian"
                                class="form-label">

                                Catatan

                            </label>


                            <textarea
                                id="catatan_pengembalian"
                                name="catatan_pengembalian"
                                rows="3"
                                maxlength="1000"
                                class="
                                    form-control
                                    @error('catatan_pengembalian')
                                        is-invalid
                                    @enderror
                                "
                                placeholder="Tambahkan catatan jika diperlukan..."
                            >{{ old('catatan_pengembalian') }}</textarea>


                            @error('catatan_pengembalian')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror


                            <div class="form-hint">
                                Opsional.
                            </div>

                        </div>


                        {{-- ========================================= --}}
                        {{-- INFO --}}
                        {{-- ========================================= --}}

                        <div class="alert alert-success mb-4">

                            <div class="d-flex align-items-start">

                                <i class="ti ti-info-circle me-2 mt-1"></i>

                                <div>

                                    Setelah dikonfirmasi,

                                    <strong>
                                        {{ $totalBuku }} buku
                                    </strong>

                                    akan dikembalikan ke stok perpustakaan.

                                </div>

                            </div>

                        </div>


                        {{-- ========================================= --}}
                        {{-- BUTTON --}}
                        {{-- ========================================= --}}

                        <div
                            class="
                                d-flex
                                justify-content-end
                                gap-2
                                form-action-pengembalian
                            ">

                            <a
                                href="{{
                                    route(
                                        'perpustakaan.pengembalian.index'
                                    )
                                }}"
                                class="btn btn-outline-secondary">

                                <i class="ti ti-x me-1"></i>

                                Batal

                            </a>


                            <button
                                type="submit"
                                id="btnKonfirmasiPengembalian"
                                class="btn btn-success">

                                <i class="ti ti-circle-check me-2"></i>

                                Konfirmasi Pengembalian

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- KOLOM KANAN --}}
        {{-- ===================================================== --}}

        <div class="col-12 col-lg-4">


            {{-- ================================================= --}}
            {{-- INFORMASI TRANSAKSI --}}
            {{-- ================================================= --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header">

                    <h3 class="card-title mb-0">
                        Informasi Transaksi
                    </h3>

                </div>


                <div class="card-body">

                    <div class="transaction-info-row">

                        <div class="transaction-info-label">
                            Kode Peminjaman
                        </div>

                        <div class="transaction-info-value">
                            {{ $peminjaman->kode_peminjaman }}
                        </div>

                    </div>


                    <div class="transaction-info-row">

                        <div class="transaction-info-label">
                            Tanggal Pinjam
                        </div>

                        <div class="transaction-info-value">

                            {{
                                optional(
                                    $peminjaman->tanggal_pinjam
                                )->format('d M Y')
                            }}

                        </div>

                    </div>


                    <div class="transaction-info-row">

                        <div class="transaction-info-label">
                            Jatuh Tempo
                        </div>

                        <div
                            class="
                                transaction-info-value
                                {{
                                    $hariTerlambat > 0
                                    ? 'text-danger'
                                    : ''
                                }}
                            ">

                            {{
                                optional(
                                    $peminjaman->tanggal_jatuh_tempo
                                )->format('d M Y')
                            }}

                        </div>

                    </div>


                    <div class="transaction-info-row">

                        <div class="transaction-info-label">
                            Total Buku
                        </div>

                        <div class="transaction-info-value">
                            {{ $totalBuku }} Buku
                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- STATUS KETERLAMBATAN --}}
            {{-- ================================================= --}}

            @if($hariTerlambat > 0)

                <div
                    class="
                        card
                        border-danger
                        shadow-sm
                        mb-4
                    ">

                    <div class="card-body">

                        <div class="d-flex align-items-start">

                            <span class="avatar bg-red-lt me-3">

                                <i class="ti ti-clock-exclamation"></i>

                            </span>


                            <div>

                                <div class="fw-semibold text-danger">
                                    Terlambat
                                </div>

                                <div class="h2 mb-1 mt-1 text-danger">

                                    {{ $hariTerlambat }} Hari

                                </div>

                                <div class="text-secondary small">

                                    Buku telah melewati tanggal jatuh tempo.

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @else

                <div
                    class="
                        card
                        border-success
                        shadow-sm
                        mb-4
                    ">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <span class="avatar bg-green-lt me-3">

                                <i class="ti ti-circle-check"></i>

                            </span>


                            <div>

                                <div class="fw-semibold text-success">
                                    Tepat Waktu
                                </div>

                                <div class="text-secondary small mt-1">

                                    Belum melewati jatuh tempo.

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- PETUGAS --}}
            {{-- ================================================= --}}

            <div class="card border-0 shadow-sm mb-0">

                <div class="card-body">

                    <div class="text-secondary small mb-2">
                        Petugas Peminjaman
                    </div>


                    <div class="d-flex align-items-center">

                        <span class="avatar bg-secondary-lt me-3">

                            <i class="ti ti-user-shield"></i>

                        </span>


                        <div class="fw-semibold">

                            {{
                                $peminjaman->petugas?->nama
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL KONFIRMASI PENGEMBALIAN --}}
{{-- ========================================================= --}}

<div
    class="modal modal-blur fade"
    id="modalKonfirmasiPengembalian"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-status bg-success"></div>

            <div class="modal-body text-center py-4">

                <span
                    class="
                        avatar
                        avatar-xl
                        bg-success-lt
                        mb-3
                    "
                >
                    <i class="ti ti-book-download fs-1"></i>
                </span>

                <h3 class="mb-2">
                    Konfirmasi Pengembalian
                </h3>

                <p class="text-secondary mb-0">

                    Yakin ingin mengembalikan

                    <strong>
                        {{ $totalBuku }} buku
                    </strong>

                    dari

                    <strong>
                        {{ $peminjaman->siswa?->nama ?? 'siswa ini' }}
                    </strong>?

                </p>

                <div class="alert alert-success text-start mt-3 mb-0">

                    <div class="d-flex">

                        <i class="ti ti-info-circle me-2 mt-1"></i>

                        <div class="small">

                            Stok buku akan otomatis dikembalikan
                            ke perpustakaan setelah transaksi
                            dikonfirmasi.

                        </div>

                    </div>

                </div>

            </div>


            <div class="modal-footer">

                <div class="row g-2 w-100">

                    <div class="col-6">

                        <button
                            type="button"
                            id="btnBatalPengembalian"
                            class="btn btn-outline-secondary w-100"
                        >
                            <i class="ti ti-x me-1"></i>

                            Batal
                        </button>

                    </div>


                    <div class="col-6">

                        <button
                            type="button"
                            id="btnProsesPengembalian"
                            class="btn btn-success w-100"
                        >
                            <i class="ti ti-check me-1"></i>

                            Ya, Kembalikan
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>


@endsection


{{-- ========================================================= --}}
{{-- SCRIPT KONFIRMASI --}}
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

        const form =
            document.getElementById(
                'formPengembalian'
            );

        const modal =
            document.getElementById(
                'modalKonfirmasiPengembalian'
            );

        const btnBatal =
            document.getElementById(
                'btnBatalPengembalian'
            );

        const btnProses =
            document.getElementById(
                'btnProsesPengembalian'
            );


        if (
            !form ||
            !modal ||
            !btnBatal ||
            !btnProses
        ) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        let sudahKonfirmasi = false;

        let backdrop = null;


        /*
        |--------------------------------------------------------------------------
        | BUKA MODAL
        |--------------------------------------------------------------------------
        */

        function bukaModal()
        {
            modal.style.display =
                'block';

            modal.classList.add(
                'show'
            );

            modal.removeAttribute(
                'aria-hidden'
            );

            modal.setAttribute(
                'aria-modal',
                'true'
            );

            document.body.classList.add(
                'modal-open'
            );


            /*
            |--------------------------------------------------------------------------
            | BACKDROP
            |--------------------------------------------------------------------------
            */

            if (!backdrop) {

                backdrop =
                    document.createElement(
                        'div'
                    );

                backdrop.className =
                    'modal-backdrop fade show';

                document.body.appendChild(
                    backdrop
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | TUTUP MODAL
        |--------------------------------------------------------------------------
        */

        function tutupModal()
        {
            modal.classList.remove(
                'show'
            );

            modal.style.display =
                'none';

            modal.setAttribute(
                'aria-hidden',
                'true'
            );

            modal.removeAttribute(
                'aria-modal'
            );

            document.body.classList.remove(
                'modal-open'
            );


            if (backdrop) {

                backdrop.remove();

                backdrop = null;

            }
        }


        /*
        |--------------------------------------------------------------------------
        | SUBMIT FORM PERTAMA
        |--------------------------------------------------------------------------
        */

        form.addEventListener(
            'submit',
            function(e)
            {

                /*
                |--------------------------------------------------------------------------
                | SUDAH DIKONFIRMASI
                |--------------------------------------------------------------------------
                */

                if (sudahKonfirmasi) {

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | TAHAN SUBMIT
                |--------------------------------------------------------------------------
                */

                e.preventDefault();

                bukaModal();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | BATAL
        |--------------------------------------------------------------------------
        */

        btnBatal.addEventListener(
            'click',
            function()
            {

                tutupModal();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | YA, KEMBALIKAN
        |--------------------------------------------------------------------------
        */

        btnProses.addEventListener(
            'click',
            function()
            {

                /*
                |--------------------------------------------------------------------------
                | TANDAI SUDAH KONFIRMASI
                |--------------------------------------------------------------------------
                */

                sudahKonfirmasi = true;


                /*
                |--------------------------------------------------------------------------
                | TUTUP MODAL
                |--------------------------------------------------------------------------
                */

                tutupModal();


                /*
                |--------------------------------------------------------------------------
                | SUBMIT NORMAL
                |--------------------------------------------------------------------------
                */

                form.requestSubmit();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | KLIK AREA LUAR MODAL
        |--------------------------------------------------------------------------
        */

        modal.addEventListener(
            'click',
            function(e)
            {

                if (e.target === modal) {

                    tutupModal();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | ESC
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            function(e)
            {

                if (
                    e.key === 'Escape' &&
                    modal.classList.contains('show')
                ) {

                    tutupModal();

                }

            }
        );

    }
);

</script>

@endpush