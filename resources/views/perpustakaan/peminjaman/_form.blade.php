{{-- ========================================================= --}}
{{-- PAGE HEADER --}}
{{-- ========================================================= --}}

<div class="page-header d-print-none mb-4">

    <div class="row align-items-center">

        <div class="col">

            <div class="page-pretitle">

                Perpustakaan

            </div>

            <h2 class="page-title">

                Transaksi Peminjaman Buku

            </h2>

            <div class="text-secondary mt-1">

                Scan QR siswa kemudian pilih buku yang akan dipinjam.

            </div>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- SCANNER + INFORMASI SISWA --}}
{{-- ========================================================= --}}

<div class="row g-4 mb-4">

    {{-- ===================================================== --}}
    {{-- SCANNER --}}
    {{-- ===================================================== --}}

    <div class="col-lg-5">

        <div class="card shadow-sm h-100">

            <div class="card-header">

                <div class="d-flex align-items-center">

                    <span
                        class="avatar bg-primary-lt me-3">

                        <i class="ti ti-qrcode"></i>

                    </span>

                    <div>

                        <h3 class="card-title mb-1">

                            Scan QR

                        </h3>

                        <small class="text-secondary">

                            Scan QR Code siswa.

                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div
                    id="reader"
                    class="border rounded-3"
                    style="
                        min-height:320px;
                        overflow:hidden;
                    ">
                </div>

                <div
                    id="scanStatus"
                    class="alert alert-secondary mt-3 mb-0">

                    <div class="d-flex align-items-center">

                        <i class="ti ti-camera me-2"></i>

                        <span>

                            Kamera siap digunakan.

                        </span>

                    </div>

                </div>

                <div class="mt-3 d-grid">

                    <button

                        id="btnScanUlang"

                        type="button"

                        class="btn btn-outline-primary"

                        disabled>

                        <i class="ti ti-refresh me-1"></i>

                        Scan Ulang

                    </button>

                </div>

            </div>

        </div>

    </div>

    {{-- ===================================================== --}}
    {{-- INFORMASI SISWA --}}
    {{-- ===================================================== --}}

    <div class="col-lg-7">

        <div class="card shadow-sm h-100">

            <div class="card-header">

                <div class="d-flex align-items-center">

                    <span
                        class="avatar bg-success-lt me-3">

                        <i class="ti ti-user"></i>

                    </span>

                    <div>

                        <h3 class="card-title mb-1">

                            Informasi Siswa

                        </h3>

                        <small class="text-secondary">

                            Data akan muncul otomatis setelah QR berhasil dipindai.

                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <input
                    type="hidden"
                    id="siswa_id"
                    name="siswa_id"
                    value="{{ old('siswa_id') }}">

                {{-- =============================== --}}
                {{-- Placeholder --}}
                {{-- =============================== --}}

                <div
                    id="previewSiswa"
                    class="text-center py-5">

                    <span
                        class="avatar avatar-xl bg-secondary-lt mb-3">

                        <i class="ti ti-user-scan fs-1"></i>

                    </span>

                    <h3 class="mb-2">

                        Belum Ada Data

                    </h3>

                    <p class="text-secondary mb-0">

                        Silakan scan QR Code siswa terlebih dahulu.

                    </p>

                </div>

                {{-- =============================== --}}
                {{-- Detail --}}
                {{-- =============================== --}}

                <div
                    id="detailSiswa"
                    style="display:none;">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="mb-3">

                                <label class="form-label">

                                    Nama Siswa

                                </label>

                                <input
                                    id="nama_siswa"
                                    class="form-control"
                                    readonly>

                                <div class="form-hint">

                                    Terisi otomatis.

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="mb-3">

                                <label class="form-label">

                                    NIS

                                </label>

                                <input
                                    id="nis"
                                    class="form-control"
                                    readonly>

                                <div class="form-hint">

                                    Nomor induk siswa.

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="mb-3">

                                <label class="form-label">

                                    Kelas

                                </label>

                                <input
                                    id="kelas"
                                    class="form-control"
                                    readonly>

                                <div class="form-hint">

                                    Kelas aktif siswa.

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">

                                Status

                            </label>

                            <div
                                class="alert alert-success mb-0">

                                <i class="ti ti-circle-check me-2"></i>

                                QR berhasil dipindai.

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Alert Success --}}

                <div
                    id="alertSuccess"
                    class="alert alert-success d-none mt-3">

                    <i class="ti ti-circle-check me-2"></i>

                    QR berhasil diverifikasi.

                </div>

                {{-- Alert Error --}}

                <div
                    id="alertError"
                    class="alert alert-danger d-none mt-3">

                    <i class="ti ti-alert-circle me-2"></i>

                    <span id="alertErrorText">

                        QR tidak valid.

                    </span>

                </div>

                @error('siswa_id')

                    <div class="alert alert-danger mt-3">

                        {{ $message }}

                    </div>

                @enderror

            </div>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- INFORMASI PEMINJAMAN --}}
{{-- ========================================================= --}}

<div class="card shadow-sm border-0 mb-4">

    <div class="card-header">

        <div class="d-flex align-items-center">

            <span class="avatar bg-blue-lt me-3">

                <i class="ti ti-book-upload"></i>

            </span>

            <div>

                <h3 class="card-title mb-1">

                    Informasi Peminjaman

                </h3>

                <small class="text-secondary">

                    Lengkapi informasi transaksi sebelum menyimpan peminjaman.

                </small>

            </div>

        </div>

    </div>

    <div class="card-body">

        <div class="row">

            {{-- ========================================= --}}
            {{-- TANGGAL PINJAM --}}
            {{-- ========================================= --}}

            <div class="col-lg-4">

                <div class="mb-3">

                    <label class="form-label">

                        Tanggal Pinjam

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="date"
                        name="tanggal_pinjam"
                        class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                        value="{{ old('tanggal_pinjam', now()->format('Y-m-d')) }}"
                        required>

                    @error('tanggal_pinjam')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                    <div class="form-hint">

                        Tanggal saat transaksi peminjaman dilakukan.

                    </div>

                </div>

            </div>

            {{-- ========================================= --}}
            {{-- JATUH TEMPO --}}
            {{-- ========================================= --}}

            <div class="col-lg-4">

                <div class="mb-3">

                    <label class="form-label">

                        Tanggal Jatuh Tempo

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="date"
                        name="tanggal_jatuh_tempo"
                        class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror"
                        value="{{ old('tanggal_jatuh_tempo') }}"
                        required>

                    @error('tanggal_jatuh_tempo')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                    <div class="form-hint">

                        Tentukan batas akhir pengembalian buku.

                    </div>

                </div>

            </div>

            {{-- ========================================= --}}
            {{-- TOTAL BUKU --}}
            {{-- ========================================= --}}

            <div class="col-lg-4">

                <div class="mb-3">

                    <label class="form-label">

                        Total Buku

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="ti ti-books"></i>

                        </span>

                        <input
                            type="text"
                            id="totalBuku"
                            class="form-control fw-semibold bg-light"
                            value="0 Buku"
                            readonly>

                    </div>

                    <div class="form-hint">

                        Jumlah buku yang dipilih pada tabel di bawah.

                    </div>

                </div>

            </div>

        </div>

        {{-- ========================================= --}}
        {{-- CATATAN --}}
        {{-- ========================================= --}}

        <div class="mb-0">

            <label class="form-label">

                Catatan

            </label>

            <textarea
                name="catatan"
                rows="4"
                class="form-control @error('catatan') is-invalid @enderror"
                placeholder="Contoh: Buku dipinjam untuk tugas kelompok...">{{ old('catatan') }}</textarea>

            @error('catatan')

                <div class="invalid-feedback">

                    {{ $message }}

                </div>

            @enderror

            <div class="form-hint">

                Catatan bersifat opsional dan dapat digunakan untuk informasi tambahan.

            </div>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- DAFTAR BUKU --}}
{{-- ========================================================= --}}

<div class="card shadow-sm border-0 card-daftar-buku">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}

    <div class="card-header daftar-buku-header">

        <div class="daftar-buku-header-content">

            {{-- ICON --}}
            <div class="daftar-buku-icon">

                <i class="ti ti-books"></i>

            </div>

            {{-- JUDUL --}}
            <div class="daftar-buku-title">

                <h3 class="card-title mb-1">

                    Daftar Buku

                </h3>

                <div class="text-secondary daftar-buku-description">

                    Pilih satu atau lebih buku yang akan dipinjam oleh siswa.

                </div>

            </div>

            {{-- TAMBAH --}}
            <button
                type="button"
                id="btnTambahBuku"
                class="btn btn-primary daftar-buku-btn-tambah">

                <i class="ti ti-plus me-1"></i>

                <span>Tambah Buku</span>

            </button>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- ISI --}}
    {{-- ===================================================== --}}

    <div class="daftar-buku-wrapper">

        <table
            class="table table-vcenter mb-0 tabel-daftar-buku">

            {{-- ================================================= --}}
            {{-- HEADER TABLE --}}
            {{-- ================================================= --}}

            <thead>

                <tr>

                    <th class="kolom-buku">
                        Buku
                    </th>

                    <th class="kolom-kelas">
                        Kelas
                    </th>

                    <th class="kolom-stok text-center">
                        Stok
                    </th>

                    <th class="kolom-jumlah text-center">
                        Jumlah
                    </th>

                    <th class="kolom-aksi text-center">
                        Aksi
                    </th>

                </tr>

            </thead>


            {{-- ================================================= --}}
            {{-- BODY --}}
            {{-- ================================================= --}}

            <tbody id="tbodyBuku">

                {{-- JavaScript mengisi baris di sini --}}

            </tbody>

        </table>

    </div>


    {{-- ===================================================== --}}
    {{-- FOOTER --}}
    {{-- ===================================================== --}}

    <div class="card-footer daftar-buku-footer">

        <div class="daftar-buku-footer-info">

            <div class="daftar-buku-footer-text">

                <i class="ti ti-info-circle me-1"></i>

                <span>
                    Pilih minimal satu buku sebelum transaksi disimpan.
                </span>

            </div>

            <span
                id="jumlahBaris"
                class="badge bg-primary-lt text-primary">

                0 Buku Dipilih

            </span>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- TEMPLATE BARIS BUKU --}}
{{-- ========================================================= --}}

<template id="templateBuku">

<tr class="baris-buku">

    {{-- ===================================================== --}}
    {{-- BUKU --}}
    {{-- ===================================================== --}}

    <td
        class="kolom-buku"
        data-label="Buku">

        <div class="field-buku">

            <label class="mobile-field-label">
                Buku
            </label>

            <select
                class="form-select buku-select"
                name="buku[]"
                required>

                <option value="">
                    -- Pilih Buku --
                </option>

            </select>

        </div>

    </td>


    {{-- ===================================================== --}}
    {{-- KELAS --}}
    {{-- ===================================================== --}}

    <td
        class="kolom-kelas"
        data-label="Kelas">

        <div class="field-buku">

            <label class="mobile-field-label">
                Kelas
            </label>

            <input
                type="text"
                class="form-control kelas-buku bg-light"
                readonly>

        </div>

    </td>


    {{-- ===================================================== --}}
    {{-- STOK --}}
    {{-- ===================================================== --}}

    <td
        class="kolom-stok"
        data-label="Stok">

        <div class="field-buku">

            <label class="mobile-field-label">
                Stok
            </label>

            <input
                type="text"
                class="form-control stok-buku text-center fw-semibold bg-light"
                readonly>

        </div>

    </td>


    {{-- ===================================================== --}}
    {{-- JUMLAH --}}
    {{-- ===================================================== --}}

    <td
        class="kolom-jumlah"
        data-label="Jumlah">

        <div class="field-buku">

            <label class="mobile-field-label">
                Jumlah
            </label>

            <div class="quantity-control">

                {{-- KURANG --}}
                <button
                    type="button"
                    class="quantity-btn quantity-minus"
                    aria-label="Kurangi jumlah">

                    <i class="ti ti-minus"></i>

                </button>


                {{-- NILAI --}}
                <input
                    type="number"
                    class="form-control jumlah-buku"
                    name="jumlah[]"
                    value="1"
                    min="1"
                    readonly
                    required>


                {{-- TAMBAH --}}
                <button
                    type="button"
                    class="quantity-btn quantity-plus"
                    aria-label="Tambah jumlah">

                    <i class="ti ti-plus"></i>

                </button>

            </div>

        </div>

    </td>


    {{-- ===================================================== --}}
    {{-- AKSI --}}
    {{-- ===================================================== --}}

    <td
        class="kolom-aksi"
        data-label="Aksi">

        <div class="field-buku field-aksi">

            <label class="mobile-field-label">
                Aksi
            </label>

            <button
                type="button"
                class="btn btn-outline-danger hapus-buku">

                <i class="ti ti-trash me-1"></i>

                <span>Hapus</span>

            </button>

        </div>

    </td>

</tr>

</template>

{{-- ========================================================= --}}
{{-- ACTION BUTTON --}}
{{-- ========================================================= --}}

<div class="d-flex justify-content-end mt-4 gap-2">

    <a
        href="{{ route('perpustakaan.peminjaman.index') }}"
        class="btn btn-outline-secondary">

        <i class="ti ti-arrow-left me-1"></i>

        Kembali

    </a>

    <button
        type="submit"
        id="btnSimpan"
        class="btn btn-success">

        <i class="ti ti-device-floppy me-1"></i>

        Simpan Peminjaman

    </button>

</div>

{{-- ========================================================= --}}
{{-- MODAL INFORMASI PEMINJAMAN --}}
{{-- ========================================================= --}}

<div
    class="modal modal-blur fade"
    id="modalInformasiPeminjaman"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            <div
                id="statusModalPeminjaman"
                class="modal-status bg-warning"
            ></div>

            <div class="modal-body text-center py-4">

                <span
                    id="iconModalPeminjaman"
                    class="avatar avatar-xl bg-warning-lt mb-3"
                >
                    <i class="ti ti-alert-triangle"></i>
                </span>

                <h3
                    id="judulModalPeminjaman"
                    class="mb-2"
                >
                    Perhatian
                </h3>

                <p
                    id="pesanModalPeminjaman"
                    class="text-secondary mb-0"
                >
                </p>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    id="btnTutupModalPeminjaman"
                    class="btn btn-primary w-100"
                >
                    <i class="ti ti-check me-1"></i>
                    Mengerti
                </button>

            </div>

        </div>

    </div>
</div>

@push('styles')

<style>

/* ==========================================================
   DAFTAR BUKU
========================================================== */

.card-daftar-buku {

    border-radius: 16px;

    overflow: hidden;

}


/* ==========================================================
   HEADER
========================================================== */

.daftar-buku-header {

    padding: 1.25rem;

}


.daftar-buku-header-content {

    display: flex;

    align-items: center;

    gap: 1rem;

}


.daftar-buku-icon {

    width: 52px;

    height: 52px;

    flex: 0 0 52px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 10px;

    background: var(--tblr-primary-lt);

    color: var(--tblr-primary);

    font-size: 1.5rem;

}


.daftar-buku-title {

    min-width: 0;

    flex: 1;

}


.daftar-buku-title .card-title {

    font-size: 1.15rem;

    font-weight: 600;

}


.daftar-buku-description {

    line-height: 1.45;

}


.daftar-buku-btn-tambah {

    flex: 0 0 auto;

    border-radius: 10px;

    min-height: 42px;

    padding-left: 1rem;

    padding-right: 1rem;

}


/* ==========================================================
   TABLE WRAPPER
========================================================== */

.daftar-buku-wrapper {

    width: 100%;

}


/* ==========================================================
   TABLE
========================================================== */

.tabel-daftar-buku {

    width: 100%;

    table-layout: fixed;

}


.tabel-daftar-buku thead th {

    background: var(--tblr-bg-surface-secondary);

    color: var(--tblr-secondary);

    font-size: .8rem;

    font-weight: 600;

    text-transform: uppercase;

    letter-spacing: .02em;

    padding: .85rem 1rem;

    border-bottom: 1px solid var(--tblr-border-color);

}


.tabel-daftar-buku tbody td {

    padding: 1rem;

    vertical-align: middle;

    border-bottom: 1px solid var(--tblr-border-color-translucent);

}


/* ==========================================================
   LEBAR KOLOM DESKTOP
========================================================== */

.kolom-buku {

    width: 35%;

}


.kolom-kelas {

    width: 17%;

}


.kolom-stok {

    width: 12%;

}


.kolom-jumlah {

    width: 18%;

}


.kolom-aksi {

    width: 18%;

}


/* ==========================================================
   SELECT BUKU
========================================================== */

.buku-select {

    width: 100%;

    min-height: 42px;

    border-radius: 10px;

}


.kelas-buku,
.stok-buku {

    min-height: 42px;

    border-radius: 10px;

}


.stok-buku {

    color: var(--tblr-success);

}


/* ==========================================================
   LABEL MOBILE
========================================================== */

.mobile-field-label {

    display: none;

}


/* ==========================================================
   QUANTITY
========================================================== */

.quantity-control {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: .4rem;

}


.quantity-btn {

    width: 40px;

    height: 40px;

    padding: 0;

    flex: 0 0 40px;

    border-radius: 10px;

    border: 1px solid var(--tblr-border-color);

    background: var(--tblr-bg-surface);

    color: var(--tblr-secondary);

    display: flex;

    align-items: center;

    justify-content: center;

    cursor: pointer;

    transition: .15s ease;

}


.quantity-btn:hover {

    background: var(--tblr-bg-surface-secondary);

}


.quantity-btn:active {

    transform: scale(.95);

}


.quantity-btn:disabled {

    opacity: .45;

    cursor: not-allowed;

}


.quantity-plus {

    color: var(--tblr-primary);

}


.quantity-minus {

    color: var(--tblr-secondary);

}


.jumlah-buku {

    width: 55px;

    min-height: 40px;

    padding: .375rem .25rem;

    text-align: center;

    font-weight: 600;

    border-radius: 10px;

}


/*
   Hilangkan spinner bawaan browser
*/

.jumlah-buku::-webkit-inner-spin-button,
.jumlah-buku::-webkit-outer-spin-button {

    -webkit-appearance: none;

    margin: 0;

}


.jumlah-buku {

    -moz-appearance: textfield;

}


/* ==========================================================
   AKSI
========================================================== */

.hapus-buku {

    min-height: 40px;

    border-radius: 10px;

    white-space: nowrap;

}


.field-aksi {

    display: flex;

    align-items: center;

    justify-content: center;

}


/* ==========================================================
   FOOTER
========================================================== */

.daftar-buku-footer {

    padding: 1rem 1.25rem;

}


.daftar-buku-footer-info {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 1rem;

}


.daftar-buku-footer-text {

    color: var(--tblr-secondary);

    font-size: .875rem;

}


#jumlahBaris {

    white-space: nowrap;

    font-size: .75rem;

    padding: .5rem .75rem;

    border-radius: 8px;

}


/* ==========================================================
   TABLET
========================================================== */

@media (max-width: 991.98px) {

    .daftar-buku-header {

        padding: 1rem;

    }


    .daftar-buku-header-content {

        gap: .75rem;

    }


    .daftar-buku-icon {

        width: 48px;

        height: 48px;

        flex-basis: 48px;

    }


    .tabel-daftar-buku tbody td {

        padding: .75rem;

    }


    .buku-select {

        min-width: 0;

    }

}


/* ==========================================================
   MOBILE
========================================================== */

@media (max-width: 767.98px) {


    /* ------------------------------------------------------
       CARD
    ------------------------------------------------------ */

    .card-daftar-buku {

        border-radius: 14px;

    }


    /* ------------------------------------------------------
       HEADER
    ------------------------------------------------------ */

    .daftar-buku-header {

        padding: 1rem;

    }


    .daftar-buku-header-content {

        display: grid;

        grid-template-columns: 48px 1fr;

        grid-template-areas:

            "icon title"

            "button button";

        gap: .7rem .75rem;

        align-items: center;

    }


    .daftar-buku-icon {

        grid-area: icon;

        width: 48px;

        height: 48px;

        flex-basis: auto;

    }


    .daftar-buku-title {

        grid-area: title;

    }


    .daftar-buku-title .card-title {

        font-size: 1.05rem;

    }


    .daftar-buku-description {

        font-size: .875rem;

    }


    .daftar-buku-btn-tambah {

        grid-area: button;

        width: fit-content;

        min-height: 42px;

        padding-left: 1rem;

        padding-right: 1rem;

    }


    /* ------------------------------------------------------
       TABLE DIUBAH MENJADI CARD
    ------------------------------------------------------ */

    .daftar-buku-wrapper {

        padding: .75rem;

    }


    .tabel-daftar-buku {

        display: block;

        width: 100%;

    }


    .tabel-daftar-buku thead {

        display: none;

    }


    .tabel-daftar-buku tbody {

        display: block;

        width: 100%;

    }


    /* ------------------------------------------------------
       SETIAP BARIS = CARD
    ------------------------------------------------------ */

    .tabel-daftar-buku tbody tr.baris-buku {

        display: block;

        width: 100%;

        margin-bottom: .75rem;

        padding: 1rem;

        border: 1px solid var(--tblr-border-color);

        border-radius: 14px;

        background: var(--tblr-bg-surface);

        box-shadow: 0 2px 6px rgba(0,0,0,.04);

    }


    .tabel-daftar-buku tbody tr.baris-buku:last-child {

        margin-bottom: 0;

    }


    /* ------------------------------------------------------
       TD
    ------------------------------------------------------ */

    .tabel-daftar-buku tbody tr.baris-buku td {

        display: block;

        width: 100%;

        padding: 0;

        border: 0;

        margin-bottom: 1rem;

    }


    .tabel-daftar-buku tbody tr.baris-buku td:last-child {

        margin-bottom: 0;

    }


    /* ------------------------------------------------------
       LABEL
    ------------------------------------------------------ */

    .mobile-field-label {

        display: block;

        margin-bottom: .4rem;

        color: var(--tblr-secondary);

        font-size: .82rem;

        font-weight: 500;

    }


    /* ------------------------------------------------------
       FIELD
    ------------------------------------------------------ */

    .field-buku {

        width: 100%;

    }


    .buku-select,
    .kelas-buku,
    .stok-buku {

        width: 100%;

        min-height: 44px;

        border-radius: 10px;

    }


    /* ------------------------------------------------------
       JUMLAH
    ------------------------------------------------------ */

    .quantity-control {

        width: 100%;

        justify-content: flex-start;

        gap: .5rem;

    }


    .quantity-btn {

        width: 44px;

        height: 44px;

        flex-basis: 44px;

        border-radius: 10px;

    }


    .jumlah-buku {

        width: 64px;

        min-height: 44px;

        font-size: 1rem;

    }


    /* ------------------------------------------------------
       AKSI
    ------------------------------------------------------ */

    .field-aksi {

        display: block;

    }


    .hapus-buku {

        width: 100%;

        min-height: 44px;

    }


    /* ------------------------------------------------------
       FOOTER
    ------------------------------------------------------ */

    .daftar-buku-footer {

        padding: .9rem 1rem;

    }


    .daftar-buku-footer-info {

        display: flex;

        flex-direction: column;

        align-items: flex-start;

        gap: .6rem;

    }


    .daftar-buku-footer-text {

        font-size: .8rem;

        line-height: 1.4;

    }


}


/* ==========================================================
   HP KECIL
========================================================== */

@media (max-width: 400px) {

    .daftar-buku-header {

        padding: .85rem;

    }


    .daftar-buku-wrapper {

        padding: .6rem;

    }


    .tabel-daftar-buku tbody tr.baris-buku {

        padding: .85rem;

    }


    .daftar-buku-description {

        font-size: .82rem;

    }


    .daftar-buku-btn-tambah {

        width: 100%;

        justify-content: center;

    }

}

</style>

@endpush

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {
const form = document.getElementById('formPeminjaman');

/*
|--------------------------------------------------------------------------
| MODAL INFORMASI
|--------------------------------------------------------------------------
*/

const modalInformasiElement =
    document.getElementById(
        'modalInformasiPeminjaman'
    );

const statusModal =
    document.getElementById(
        'statusModalPeminjaman'
    );

const iconModal =
    document.getElementById(
        'iconModalPeminjaman'
    );

const judulModal =
    document.getElementById(
        'judulModalPeminjaman'
    );

const pesanModal =
    document.getElementById(
        'pesanModalPeminjaman'
    );


/*
|--------------------------------------------------------------------------
| BUKA MODAL
|--------------------------------------------------------------------------
*/

function bukaModalInformasi()
{
    if (!modalInformasiElement) {
        return;
    }

    modalInformasiElement
        .classList
        .add('show');

    modalInformasiElement.style.display =
        'block';

    modalInformasiElement.removeAttribute(
        'aria-hidden'
    );

    modalInformasiElement.setAttribute(
        'aria-modal',
        'true'
    );

    document.body
        .classList
        .add('modal-open');
}


/*
|--------------------------------------------------------------------------
| TUTUP MODAL
|--------------------------------------------------------------------------
*/

function tutupModalInformasi()
{
    if (!modalInformasiElement) {
        return;
    }

    modalInformasiElement
        .classList
        .remove('show');

    modalInformasiElement.style.display =
        'none';

    modalInformasiElement.setAttribute(
        'aria-hidden',
        'true'
    );

    modalInformasiElement.removeAttribute(
        'aria-modal'
    );

    document.body
        .classList
        .remove('modal-open');
}


/*
|--------------------------------------------------------------------------
| TAMPILKAN INFORMASI
|--------------------------------------------------------------------------
*/

function tampilkanInformasi(
    judul,
    pesan,
    tipe = 'warning'
)
{
    if (
        !modalInformasiElement ||
        !statusModal ||
        !iconModal ||
        !judulModal ||
        !pesanModal
    ) {
        return;
    }


    judulModal.textContent =
        judul;

    pesanModal.textContent =
        pesan;


    /*
    |--------------------------------------------------------------------------
    | WARNING
    |--------------------------------------------------------------------------
    */

    if (tipe === 'warning') {

        statusModal.className =
            'modal-status bg-warning';

        iconModal.className =
            'avatar avatar-xl bg-warning-lt mb-3';

        iconModal.innerHTML =
            '<i class="ti ti-alert-triangle"></i>';

    }


    /*
    |--------------------------------------------------------------------------
    | DANGER
    |--------------------------------------------------------------------------
    */

    else if (tipe === 'danger') {

        statusModal.className =
            'modal-status bg-danger';

        iconModal.className =
            'avatar avatar-xl bg-danger-lt mb-3';

        iconModal.innerHTML =
            '<i class="ti ti-alert-circle"></i>';

    }


    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    else {

        statusModal.className =
            'modal-status bg-success';

        iconModal.className =
            'avatar avatar-xl bg-success-lt mb-3';

        iconModal.innerHTML =
            '<i class="ti ti-circle-check"></i>';

    }


    bukaModalInformasi();
}

console.log(form);
    /*
    |--------------------------------------------------------------------------
    | Element
    |--------------------------------------------------------------------------
    */

    const tbody =
        document.getElementById('tbodyBuku');

    const template =
        document.getElementById('templateBuku');

    const btnTambah =
        document.getElementById('btnTambahBuku');

    const totalBuku =
        document.getElementById('totalBuku');

    const jumlahBaris =
        document.getElementById('jumlahBaris');

    let html5Qr = null;

    let qrAktif = false;

    let kelasSiswaId = null;

    if (!tbody || !template) {
        return;
    }

    /*
|--------------------------------------------------------------------------
| TOMBOL TUTUP MODAL
|--------------------------------------------------------------------------
*/

const btnTutupModalPeminjaman =
    document.getElementById(
        'btnTutupModalPeminjaman'
    );

if (btnTutupModalPeminjaman) {

    btnTutupModalPeminjaman.addEventListener(
        'click',
        function()
        {
            tutupModalInformasi();
        }
    );

}

/*
|--------------------------------------------------------------------------
| KONTROL JUMLAH BUKU
|--------------------------------------------------------------------------
*/

tbody.addEventListener(
    'click',
    function(e) {

        /*
        |--------------------------------------------------------------------------
        | CARI TOMBOL
        |--------------------------------------------------------------------------
        */

        const btn =
            e.target.closest('.quantity-btn');

        if (!btn) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CARI BARIS
        |--------------------------------------------------------------------------
        */

        const row =
            btn.closest('tr');

        if (!row) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | INPUT JUMLAH
        |--------------------------------------------------------------------------
        */

        const input =
            row.querySelector('.jumlah-buku');

        if (!input) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | STOK
        |--------------------------------------------------------------------------
        */

        const stokInput =
            row.querySelector('.stok-buku');

        const stok =
            parseInt(
                stokInput?.value || 0
            );


        /*
        |--------------------------------------------------------------------------
        | JUMLAH SAAT INI
        |--------------------------------------------------------------------------
        */

        let jumlah =
            parseInt(
                input.value || 1
            );


        /*
        |--------------------------------------------------------------------------
        | TOMBOL KURANG
        |--------------------------------------------------------------------------
        */

        if (
            btn.classList.contains('quantity-minus')
        ) {

            if (jumlah > 1) {

                jumlah--;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | TOMBOL TAMBAH
        |--------------------------------------------------------------------------
        */

        if (
            btn.classList.contains('quantity-plus')
        ) {

            /*
            | Jika stok belum tersedia
            */

            if (stok <= 0) {

                tampilkanInformasi(
                    'Stok Tidak Tersedia',
                    'Stok buku belum tersedia.',
                    'warning'
                );

                return;
            }


            /*
            | Tidak boleh melebihi stok
            */

            if (jumlah < stok) {

                jumlah++;

            } else {

                tampilkanInformasi(
                    'Stok Maksimal',
                    'Jumlah buku tidak dapat melebihi stok yang tersedia.',
                    'warning'
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN NILAI
        |--------------------------------------------------------------------------
        */

        input.value = jumlah;


        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS TOMBOL
        |--------------------------------------------------------------------------
        */

        updateQuantityButtons(row);


        /*
        |--------------------------------------------------------------------------
        | TRIGGER INPUT
        |--------------------------------------------------------------------------
        |
        | Supaya sistem validasi jumlah yang sudah kamu punya
        | tetap berjalan.
        |
        */

        input.dispatchEvent(
            new Event(
                'input',
                {
                    bubbles: true
                }
            )
        );

    }
);


/*
|--------------------------------------------------------------------------
| UPDATE TOMBOL JUMLAH
|--------------------------------------------------------------------------
*/

function updateQuantityButtons(row)
{

    const input =
        row.querySelector('.jumlah-buku');

    const stokInput =
        row.querySelector('.stok-buku');

    const btnMinus =
        row.querySelector('.quantity-minus');

    const btnPlus =
        row.querySelector('.quantity-plus');


    if (
        !input ||
        !btnMinus ||
        !btnPlus
    ) {
        return;
    }


    const jumlah =
        parseInt(
            input.value || 1
        );


    const stok =
        parseInt(
            stokInput?.value || 0
        );


    /*
    |--------------------------------------------------------------------------
    | MINIMAL 1
    |--------------------------------------------------------------------------
    */

    btnMinus.disabled =
        jumlah <= 1;


    /*
    |--------------------------------------------------------------------------
    | MAKSIMAL STOK
    |--------------------------------------------------------------------------
    */

    btnPlus.disabled =
        stok > 0 &&
        jumlah >= stok;

}

    /*
    |--------------------------------------------------------------------------
    | Tambah Baris Buku
    |--------------------------------------------------------------------------
    */

    function tambahBaris()
    {
        const clone =
            template.content.cloneNode(true);

        tbody.appendChild(clone);

        updateCounter();

        if (kelasSiswaId) {
            filterBuku(kelasSiswaId);
        }

        refreshDropdown();
    }

    /*
    |--------------------------------------------------------------------------
    | Hitung Total Buku
    |--------------------------------------------------------------------------
    */

    function updateCounter()
    {
        const total =
            tbody.querySelectorAll('tr').length;

        totalBuku.value =
            total + ' Buku';

        jumlahBaris.textContent =
            total + ' Buku Dipilih';
    }

    /*
    |--------------------------------------------------------------------------
    | Refresh Dropdown
    |--------------------------------------------------------------------------
    |
    | Buku yang sudah dipilih tidak akan
    | muncul lagi pada dropdown lainnya.
    |
    |--------------------------------------------------------------------------
    */

    function refreshDropdown()
{
    const selected = [];

    document.querySelectorAll('.buku-select')
        .forEach(function(select){

            if(select.value){
                selected.push(select.value);
            }

        });

    document.querySelectorAll('.buku-select')
        .forEach(function(select){

            const current = select.value;

            select.innerHTML =
                '<option value="">-- Pilih Buku --</option>';

            daftarBuku.forEach(function(item){

                if(
                    selected.includes(String(item.id))
                    &&
                    current != item.id
                ){
                    return;
                }

                select.innerHTML += `
                <option
                    value="${item.id}"
                    data-kelas-id="${item.kelas_id}"
                    data-kelas="${item.kelas}"
                    data-stok="${item.jumlah_tersedia}">
                    ${item.nama_buku}
                </option>
                `;

            });

            select.value = current;

        });

}


    function filterBuku(kelasId)
{
    document
        .querySelectorAll('.buku-select')
        .forEach(function(select){

            Array.from(select.options).forEach(function(option){

                if(option.value === ''){
                    option.hidden = false;
                    return;
                }

                option.hidden = String(option.dataset.kelasId) !== String(kelasId);

            });

            

            const row = select.closest('tr');


        });

    refreshDropdown();
}

let daftarBuku = [];

function loadBuku(kelasId)
{
    const url =
    "{{ route('perpustakaan.peminjaman.buku', ':id') }}"
        .replace(':id', kelasId);

fetch(url)

    .then(res => res.json())

    .then(data => {

    daftarBuku = data;

    refreshDropdown();

    filterBuku(kelasId);

});
}

    /*
    |--------------------------------------------------------------------------
    | Pilih Buku
    |--------------------------------------------------------------------------
    */

    tbody.addEventListener(
        'change',
        function(e){

            if(
                !e.target.classList.contains('buku-select')
            ){
                return;
            }

            const select =
                e.target;

            const row =
                select.closest('tr');

            const option =
                select.selectedOptions[0];

                console.log(daftarBuku);

            row.querySelector('.kelas-buku').value =
                option.dataset.kelas || '';

            row.querySelector('.stok-buku').value =
                option.dataset.stok || '';

            const jumlah =
    row.querySelector('.jumlah-buku');

const stok =
    parseInt(option.dataset.stok || 0);

jumlah.max = stok;

/*
|--------------------------------------------------------------------------
| Set jumlah awal
|--------------------------------------------------------------------------
*/

if (stok > 0) {

    jumlah.value = 1;

} else {

    jumlah.value = 1;

}

            refreshDropdown();

        }
    );

    /*
    |--------------------------------------------------------------------------
    | Validasi Jumlah
    |--------------------------------------------------------------------------
    */

    tbody.addEventListener(
        'input',
        function(e){

            if(
                !e.target.classList.contains('jumlah-buku')
            ){
                return;
            }

            const input =
                e.target;

            const row =
                input.closest('tr');

            const stok =
                parseInt(
                    row.querySelector('.stok-buku').value || 0
                );

            let jumlah =
                parseInt(input.value || 1);

            if(jumlah<1){

                jumlah=1;

            }

            if (jumlah > stok) {

                jumlah = stok;

                tampilkanInformasi(
                    'Stok Tidak Mencukupi',
                    'Jumlah buku yang dipilih melebihi stok yang tersedia.',
                    'warning'
                );

            }

            input.value=jumlah;

        }
    );

    /*
|--------------------------------------------------------------------------
| Tombol Tambah / Kurang Jumlah Buku
|--------------------------------------------------------------------------
*/

tbody.addEventListener(
    'click',
    function(e) {

        /*
        |--------------------------------------------------------------------------
        | TOMBOL PLUS
        |--------------------------------------------------------------------------
        */

        const btnPlus =
            e.target.closest('.btn-jumlah-plus');

        if (btnPlus) {

            const row =
                btnPlus.closest('tr');

            const input =
                row.querySelector('.jumlah-buku');

            const stok =
                parseInt(
                    row.querySelector('.stok-buku').value || 0
                );

            let jumlah =
                parseInt(input.value || 1);

            /*
            |--------------------------------------------------------------------------
            | Jika buku belum dipilih
            |--------------------------------------------------------------------------
            */

            if (stok <= 0) {

                tampilkanInformasi(
                    'Buku Belum Dipilih',
                    'Silakan pilih buku terlebih dahulu.',
                    'warning'
                );

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Tambah jumlah
            |--------------------------------------------------------------------------
            */

            if (jumlah < stok) {

                jumlah++;

                input.value = jumlah;

            } else {

                input.value = stok;

                tampilkanInformasi(
                    'Stok Maksimal',
                    'Jumlah peminjaman sudah mencapai stok buku yang tersedia.',
                    'warning'
                );

            }

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | TOMBOL MINUS
        |--------------------------------------------------------------------------
        */

        const btnMinus =
            e.target.closest('.btn-jumlah-minus');

        if (btnMinus) {

            const row =
                btnMinus.closest('tr');

            const input =
                row.querySelector('.jumlah-buku');

            let jumlah =
                parseInt(input.value || 1);

            /*
            |--------------------------------------------------------------------------
            | Minimal jumlah = 1
            |--------------------------------------------------------------------------
            */

            if (jumlah > 1) {

                jumlah--;

                input.value = jumlah;

            } else {

                input.value = 1;

            }

        }

    }
);

    /*
    |--------------------------------------------------------------------------
    | Hapus Baris
    |--------------------------------------------------------------------------
    */

    tbody.addEventListener(
        'click',
        function(e){

            const btn =
                e.target.closest('.hapus-buku');

            if(!btn){
                return;
            }

            if (
                tbody.querySelectorAll('tr').length === 1
            ) {

                tampilkanInformasi(
                    'Buku Tidak Dapat Dihapus',
                    'Transaksi peminjaman harus memiliki minimal satu buku.',
                    'warning'
                );

                return;

            }

            btn.closest('tr').remove();

            updateCounter();

            refreshDropdown();

        }
    );

    /*
    |--------------------------------------------------------------------------
    | Tambah Buku
    |--------------------------------------------------------------------------
    */

    btnTambah.addEventListener(
        'click',
        tambahBaris
    );

    /*
    |--------------------------------------------------------------------------
    | Baris Awal
    |--------------------------------------------------------------------------
    */

    tambahBaris();

    /*
|--------------------------------------------------------------------------
| QR SCANNER
|--------------------------------------------------------------------------
*/

const scanStatus =
    document.getElementById('scanStatus');

const btnScanUlang =
    document.getElementById('btnScanUlang');

const previewSiswa =
    document.getElementById('previewSiswa');

const detailSiswa =
    document.getElementById('detailSiswa');

const siswaId =
    document.getElementById('siswa_id');

const namaSiswa =
    document.getElementById('nama_siswa');

const nis =
    document.getElementById('nis');

const kelas =
    document.getElementById('kelas');

const alertSuccess =
    document.getElementById('alertSuccess');

const alertError =
    document.getElementById('alertError');

const alertErrorText =
    document.getElementById('alertErrorText');

const btnSimpan =
    document.getElementById('btnSimpan');


/*
|--------------------------------------------------------------------------
| Start Scanner
|--------------------------------------------------------------------------
*/

function startScanner()
{
    if(qrAktif){
        return;
    }

    html5Qr = new Html5Qrcode("reader");

    html5Qr.start(

        {
            facingMode:"environment"
        },

        {
            fps:10,
            qrbox:250
        },

        suksesScan,

        function(){}

    ).then(function(){

        qrAktif = true;

        scanStatus.className =
            'alert alert-info mt-3 mb-0';

        scanStatus.innerHTML =
            '<i class="ti ti-camera"></i> Kamera aktif.';

    })

    .catch(function(){

        scanStatus.className =
            'alert alert-danger mt-3 mb-0';

        scanStatus.innerHTML =
            '<i class="ti ti-alert-circle"></i> Kamera gagal dibuka.';

    });

}

/*
|--------------------------------------------------------------------------
| Stop Scanner
|--------------------------------------------------------------------------
*/

function stopScanner()
{
    if(!html5Qr || !qrAktif){
        return;
    }

    html5Qr.stop()

    .then(function(){

        qrAktif = false;

    });

}

/*
|--------------------------------------------------------------------------
| QR Berhasil Dibaca
|--------------------------------------------------------------------------
*/

function suksesScan(decodedText)
{
    stopScanner();

    fetch(
        "{{ route('perpustakaan.peminjaman.scanQr') }}",
        {

            method:'POST',

            headers:{

                'Content-Type':'application/json',

                'X-CSRF-TOKEN':
                document
                    .querySelector('meta[name="csrf-token"]')
                    .content

            },

            body:JSON.stringify({

                qr_token:decodedText

            })

        }

    )

    .then(res=>res.json())

    .then(function(response){

        if(!response.success){

            throw response;

        }

        const data=response.data;

        siswaId.value=data.id;

        namaSiswa.value=data.nama;

        nis.value=data.nis;

        kelas.value = data.kelas;

        kelasSiswaId = data.kelas_id;

        loadBuku(kelasSiswaId);

        console.log(data);
console.log(data.kelas_id);

        console.log('Kelas Siswa:', kelasSiswaId);

        previewSiswa.style.display='none';

        detailSiswa.style.display='block';

        alertSuccess.classList.remove('d-none');

        alertError.classList.add('d-none');

        scanStatus.className=
            'alert alert-success mt-3 mb-0';

        scanStatus.innerHTML=
            '<i class="ti ti-circle-check"></i> QR berhasil dipindai.';

        btnScanUlang.disabled=false;

    })

    .catch(function(error){

        siswaId.value='';

        previewSiswa.style.display='block';

        detailSiswa.style.display='none';

        alertSuccess.classList.add('d-none');

        alertError.classList.remove('d-none');

        alertErrorText.textContent=
            error.message ??
            'QR tidak valid.';

        btnScanUlang.disabled=false;

    });

}

/*
|--------------------------------------------------------------------------
| Scan Ulang
|--------------------------------------------------------------------------
*/

btnScanUlang.addEventListener(
    'click',
    function(){

        alertSuccess.classList.add('d-none');

        alertError.classList.add('d-none');

        siswaId.value='';

        namaSiswa.value='';

        nis.value='';

        kelas.value='';

        previewSiswa.style.display='block';

        detailSiswa.style.display='none';

        btnScanUlang.disabled=true;

        startScanner();

    }
);

/*
|--------------------------------------------------------------------------
| VALIDASI SUBMIT
|--------------------------------------------------------------------------
*/

form.addEventListener(
    'submit',
    function(e)
    {

        /*
        |--------------------------------------------------------------------------
        | SISWA BELUM DIPILIH
        |--------------------------------------------------------------------------
        */

        if (!siswaId.value) {

            e.preventDefault();

            tampilkanInformasi(
                'Siswa Belum Dipilih',
                'Silakan scan QR siswa terlebih dahulu sebelum menyimpan transaksi.',
                'warning'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | BUKU BELUM DIPILIH
        |--------------------------------------------------------------------------
        */

        const bukuDipilih =
            [
                ...document.querySelectorAll(
                    '.buku-select'
                )
            ]
            .filter(
                select =>
                    select.value !== ''
            );


        if (bukuDipilih.length === 0) {

            e.preventDefault();

            tampilkanInformasi(
                'Buku Belum Dipilih',
                'Pilih minimal satu buku yang akan dipinjam.',
                'warning'
            );

            return;
        }

    }
);

/*
|--------------------------------------------------------------------------
| Jalankan Kamera
|--------------------------------------------------------------------------
*/

startScanner();

});

</script>

@endpush