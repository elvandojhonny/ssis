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

<div class="card shadow-sm border-0">

    <div class="card-header">

    <div class="d-flex align-items-center w-100">

        <div class="d-flex align-items-center">

            <span class="avatar bg-azure-lt me-3">
                <i class="ti ti-books"></i>
            </span>

            <div>

                <h3 class="card-title mb-1">
                    Daftar Buku
                </h3>

                <div class="text-secondary">
                    Pilih satu atau lebih buku yang akan dipinjam oleh siswa.
                </div>

            </div>

        </div>

        <button
            type="button"
            id="btnTambahBuku"
            class="btn btn-primary ms-auto">

            <i class="ti ti-plus me-1"></i>
            Tambah Buku

        </button>

    </div>

</div>

    <div class="table-responsive">

        <table
            class="table table-vcenter table-hover card-table mb-0">

            <thead>

                <tr>

                    <th width="38%">

                        Buku

                    </th>

                    <th width="20%">

                        Kelas

                    </th>

                    <th
                        width="12%"
                        class="text-center">

                        Stok

                    </th>

                    <th
                        width="12%"
                        class="text-center">

                        Jumlah

                    </th>

                    <th
                        width="18%"
                        class="text-center">

                        Aksi

                    </th>

                </tr>

            </thead>

            <tbody id="tbodyBuku">

                {{-- JavaScript akan mengisi di sini --}}

            </tbody>

        </table>

    </div>

    <div class="card-footer">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <small class="text-secondary">

                    Pilih minimal satu buku sebelum transaksi disimpan.

                </small>

            </div>

            <div>

                <span
                    id="jumlahBaris"
                    class="badge bg-primary-lt text-primary">

                    0 Buku Dipilih

                </span>

            </div>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- TEMPLATE BARIS BUKU --}}
{{-- ========================================================= --}}

<template id="templateBuku">

<tr>

    {{-- ========================= --}}
    {{-- BUKU --}}
    {{-- ========================= --}}

    <td>

        <select
            class="form-select buku-select"
            name="buku[]"
            required>

            <option value="">

                -- Pilih Buku --

            </option>

        </select>

    </td>

    {{-- ========================= --}}
    {{-- KELAS --}}
    {{-- ========================= --}}

    <td>

        <input
            type="text"
            class="form-control kelas-buku bg-light"
            readonly>

    </td>

    {{-- ========================= --}}
    {{-- STOK --}}
    {{-- ========================= --}}

    <td>

        <input
            type="text"
            class="form-control stok-buku text-center fw-bold bg-light"
            readonly>

    </td>

    {{-- ========================= --}}
    {{-- JUMLAH --}}
    {{-- ========================= --}}

    <td>

        <input
            type="number"
            class="form-control jumlah-buku text-center"
            name="jumlah[]"
            value="1"
            min="1"
            required>

    </td>

    {{-- ========================= --}}
    {{-- AKSI --}}
    {{-- ========================= --}}

    <td class="text-center">

        <button
            type="button"
            class="btn btn-outline-danger hapus-buku">

            <i class="ti ti-trash"></i>

        </button>

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
   PEMINJAMAN BUKU
========================================================== */

#reader{

    border:2px dashed var(--tblr-border-color);

    border-radius:14px;

    overflow:hidden;

    background:#fff;

}

#reader video{

    border-radius:12px;

}

/* ========================================== */

.card{

    transition:.2s ease;

}

.card:hover{

    box-shadow:0 .5rem 1rem rgba(0,0,0,.06);

}

/* ========================================== */

.card-header{

    padding:1rem 1.25rem;

}

.card-title{

    font-weight:600;

}

/* ========================================== */

.form-control[readonly]{

    background:var(--tblr-bg-surface-secondary);

    cursor:default;

}

/* ========================================== */

.table thead th{

    font-weight:600;

    white-space:nowrap;

}

.table tbody td{

    vertical-align:middle;

}

/* ========================================== */

.buku-select{

    min-width:260px;

}

.kelas-buku{

    font-weight:500;

}

.stok-buku{

    text-align:center;

    font-weight:600;

    color:var(--tblr-success);

}

.jumlah-buku{

    text-align:center;

    width:90px;

    margin:auto;

}

/* ========================================== */

#jumlahBaris{

    font-size:.75rem;

    padding:.55rem .9rem;

}

/* ========================================== */

.btn{

    border-radius:10px;

}

.btn-outline-danger{

    width:40px;

    height:40px;

    padding:0;

    display:flex;

    align-items:center;

    justify-content:center;

}

/* ========================================== */

#previewSiswa{

    min-height:230px;

    display:flex;

    flex-direction:column;

    justify-content:center;

    align-items:center;

}

#detailSiswa{

    animation:fadeIn .25s ease;

}

@keyframes fadeIn{

    from{

        opacity:0;

        transform:translateY(8px);

    }

    to{

        opacity:1;

        transform:none;

    }

}

/* ========================================== */

@media(max-width:991px){

    .buku-select{

        min-width:220px;

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

            jumlah.max =
                option.dataset.stok || 1;

            if(
                parseInt(jumlah.value)
                >
                parseInt(option.dataset.stok)
            ){

                jumlah.value =
                    option.dataset.stok;

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