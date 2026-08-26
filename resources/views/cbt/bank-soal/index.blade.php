@extends('layouts.app')

@section('title', 'Bank Soal')

@section('content')

<style>
    /* ==========================================================
       PREVIEW SOAL - RESPONSIVE IMAGE
    ========================================================== */

    .soal-preview-image-wrapper {
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    .soal-preview-image {
        display: block;
        width: auto;
        max-width: 100%;
        height: auto;
        max-height: 450px;
        object-fit: contain;
        margin: 0;
    }

    .soal-pilihan-content {
        min-width: 0;
        max-width: 100%;
        overflow-wrap: anywhere;
    }

    .soal-pilihan-image-wrapper {
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    .soal-pilihan-image {
        display: block;
        width: auto;
        max-width: 100%;
        height: auto;
        max-height: 350px;
        object-fit: contain;
    }


    /* ==========================================================
       MOBILE
    ========================================================== */

    @media (max-width: 767.98px) {

        .soal-preview-image {
            max-width: 100%;
            max-height: 300px;
            width: auto;
            height: auto;
        }

        .soal-pilihan-image {
            max-width: 100%;
            max-height: 250px;
            width: auto;
            height: auto;
        }

        /*
         * Pilihan jawaban dibuat satu kolom di HP.
         * Jadi gambar tidak dipaksa masuk ke kotak
         * kiri/kanan yang sempit.
         */
        .preview-pilihan-row {
            margin-left: 0;
            margin-right: 0;
        }

        .preview-pilihan-item {
            width: 100%;
        }

        /*
         * Isi pilihan tidak boleh membuat
         * halaman melebar ke samping.
         */
        .soal-pilihan-content {
            min-width: 0;
            width: 100%;
        }
    }

    /* =========================================================
   BANK SOAL - MOBILE TABLE STYLE
   ========================================================= */

@media (max-width: 767.98px) {

    /* Hilangkan kesan card besar */
    .bank-soal-wrapper {
        margin-top: 1rem !important;
        border-radius: 0 !important;
        border-left: 0 !important;
        border-right: 0 !important;
        box-shadow: none !important;
        background: transparent !important;
    }


    /* Header Bank Soal */
    .bank-soal-wrapper .card-header {
        padding: 1rem 0.25rem;
        background: transparent;
        border-bottom: 1px solid var(--tblr-border-color);
    }


    /* Daftar */
    .bank-soal-mobile-list {
        background: transparent;
    }


    /* Setiap bank soal = satu baris besar */
    .bank-soal-mobile-row {
        padding: 1rem 0.25rem;
        border-bottom: 1px solid var(--tblr-border-color);
    }


    /* Header bank soal */
    .bank-soal-mobile-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
    }


    .bank-soal-mobile-title {
        min-width: 0;
        flex: 1;
    }


    .bank-soal-mobile-title .fw-bold {
        font-size: 0.98rem;
        line-height: 1.35;
    }


    .bank-soal-mobile-code {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 0.55rem;
    }


    .bank-soal-mobile-code .btn {
        width: 36px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }


    /* Tingkat + jumlah soal */
    .bank-soal-mobile-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1rem;
        padding-top: 0.85rem;
        border-top: 1px dashed var(--tblr-border-color);
    }


    .bank-soal-mobile-info .fw-medium {
        margin-top: 0.15rem;
    }


    /* Tombol */
    .bank-soal-mobile-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }


    .bank-soal-mobile-actions .btn {
        flex: 1;
        min-height: 40px;
        margin: 0 !important;
    }

}

/* ==========================================================
   PREVIEW SOAL - SCROLL AREA
   ========================================================== */

.preview-soal-scroll {
    max-height: 900px;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 0.25rem 0.5rem 0.5rem 0.25rem;
    scroll-behavior: smooth;
}

/* Scrollbar */
.preview-soal-scroll::-webkit-scrollbar {
    width: 8px;
}

.preview-soal-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.preview-soal-scroll::-webkit-scrollbar-thumb {
    background: var(--tblr-border-color);
    border-radius: 10px;
}

.preview-soal-scroll::-webkit-scrollbar-thumb:hover {
    background: var(--tblr-secondary);
}

/* Firefox */
.preview-soal-scroll {
    scrollbar-width: thin;
    scrollbar-color: var(--tblr-border-color) transparent;
}


/* ==========================================================
   MOBILE
   ========================================================== */

@media (max-width: 767.98px) {

    .preview-soal-scroll {
        max-height: 750px;
        padding-right: 0.25rem;
    }

}
</style>

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Computer Based Test
            </div>

            <h2 class="page-title">
    Bank Soal
</h2>

<div class="text-secondary mt-1">
    Kelola dan upload soal ujian melalui template Word.
</div>

@if($tahunAjaran)

    <div class="mt-2">

        <span class="badge bg-blue-lt">

            <i class="ti ti-calendar me-1"></i>

            Tahun Ajaran:
            {{ $tahunAjaran->nama }}

        </span>

    </div>

@endif

        </div>

        <div class="col-12 col-md-auto">

            <div
                class="
                    d-flex
                    flex-column
                    flex-md-row
                    gap-2
                "
            >

                <a
                    href="{{ route('cbt.bank-soal.arsip') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="ti ti-archive me-1"></i>

                    Arsip Bank Soal
                </a>


                <a
                    href="{{ route('cbt.bank-soal.template') }}"
                    class="btn btn-outline-primary no-loading"
                >
                    <i class="ti ti-download me-1"></i>

                    Download Template Soal
                </a>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- ALERT SUCCESS --}}
{{-- ========================================================= --}}

@if(session('success'))

    <div class="alert alert-success">

        <div class="d-flex align-items-center">

            <i class="ti ti-circle-check me-2"></i>

            <div>
                {{ session('success') }}
            </div>

        </div>

    </div>

@endif


{{-- ========================================================= --}}
{{-- ALERT ERROR --}}
{{-- ========================================================= --}}

@if(session('error'))

    <div class="alert alert-danger">

        <div class="d-flex align-items-center">

            <i class="ti ti-alert-circle me-2"></i>

            <div>
                {{ session('error') }}
            </div>

        </div>

    </div>

@endif


{{-- ========================================================= --}}
{{-- ERROR ISI FILE WORD --}}
{{-- ========================================================= --}}

@if(session('upload_errors'))

    <div class="alert alert-danger">

        <div class="d-flex align-items-start">

            <div class="me-2">

                <i class="ti ti-alert-triangle"></i>

            </div>

            <div class="flex-fill">

                <div class="fw-bold mb-2">
                    File soal belum dapat diproses
                </div>

                <div class="mb-2">
                    Periksa kembali data berikut:
                </div>

                <ul class="mb-0 ps-3">

                    @foreach(session('upload_errors') as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    </div>

@endif


{{-- ========================================================= --}}
{{-- UPLOAD DAN INFORMASI --}}
{{-- ========================================================= --}}

<div class="row row-cards">


    {{-- ===================================================== --}}
    {{-- UPLOAD SOAL --}}
    {{-- ===================================================== --}}

    <div class="col-lg-5">

        <div class="card h-100">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="ti ti-file-upload me-2"></i>

                    Upload Soal

                </h3>

            </div>


            <div class="card-body">

                <div class="mb-4">

                    <div class="fw-bold mb-2">
                        Petunjuk
                    </div>

                    <div class="text-secondary">

                        Gunakan template Word resmi SSIS untuk
                        mengupload soal ke dalam Bank Soal.

                    </div>

                </div>


                <div class="alert alert-info">

                    <div class="d-flex">

                        <div class="me-2">

                            <i class="ti ti-info-circle"></i>

                        </div>

                        <div>

                            Jangan mengubah nama atau urutan kolom
                            pada template.

                        </div>

                    </div>

                </div>


                <form
                    action="{{ route('cbt.bank-soal.upload') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf


                    <div class="mb-3">

                        <label
                            for="file_soal"
                            class="form-label required"
                        >
                            File Soal
                        </label>


                        <input
                            type="file"
                            id="file_soal"
                            name="file_soal"
                            class="
                                form-control
                                @error('file_soal')
                                    is-invalid
                                @enderror
                            "
                            accept=".docx"
                            required
                        >


                        @error('file_soal')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <div class="form-hint">

                            Format file yang diterima:
                            Microsoft Word (.docx), maksimal 50 MB.

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >

                        <i class="ti ti-upload me-1"></i>

                        Upload dan Preview Soal

                    </button>

                </form>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- INFORMASI TEMPLATE --}}
    {{-- ===================================================== --}}

    <div class="col-lg-7">

        <div class="card h-100">

            <div class="card-header">

                <h3 class="card-title">
                    Format Template Soal
                </h3>

            </div>


            <div class="card-body">

                <p class="text-secondary">

                    Setiap baris pada tabel Word mewakili
                    satu soal pilihan ganda.

                </p>


                <div class="row g-3 mt-1">


                    {{-- DATA SOAL --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="fw-bold mb-2">

                                <i class="ti ti-list-numbers me-1"></i>

                                Data Soal

                            </div>

                            <div class="text-secondary small">

                                Nomor soal, pertanyaan,
                                dan pilihan jawaban A sampai E.

                            </div>

                        </div>

                    </div>


                    {{-- KUNCI JAWABAN --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="fw-bold mb-2">

                                <i class="ti ti-key me-1"></i>

                                Kunci Jawaban

                            </div>

                            <div class="text-secondary small">

                                Tentukan satu jawaban benar
                                dari pilihan A sampai E.

                            </div>

                        </div>

                    </div>


                    {{-- SKOR --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="fw-bold mb-2">

                                <i class="ti ti-star me-1"></i>

                                Skor Soal

                            </div>

                            <div class="text-secondary small">

                                Guru menentukan skor masing-masing
                                soal sesuai bobotnya.

                            </div>

                        </div>

                    </div>


                    {{-- FORMAT DOCX --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="fw-bold mb-2">

                                <i class="ti ti-file-type-docx me-1"></i>

                                Format DOCX

                            </div>

                            <div class="text-secondary small">

                                Sistem hanya menerima template
                                dalam format Microsoft Word DOCX.

                            </div>

                        </div>

                    </div>

                </div>


                <hr class="my-4">


                <div class="d-flex align-items-start">

                    <span class="avatar bg-blue-lt me-3">

                        <i class="ti ti-download"></i>

                    </span>


                    <div class="flex-fill">

                        <div class="fw-bold">
                            Belum memiliki template?
                        </div>

                        <div class="text-secondary small mb-3">

                            Download template resmi sebelum
                            membuat dan mengupload soal.

                        </div>


                        <a
                            href="{{ route('cbt.bank-soal.template') }}"
                            class="btn btn-outline-primary btn-sm"
                        >

                            <i class="ti ti-download me-1"></i>

                            Download Template

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- PREVIEW SOAL HASIL UPLOAD --}}
{{-- ========================================================= --}}

@if(session()->has('cbt_preview_soals'))

    @php

        $previewSoals = session('cbt_preview_soals');

        $totalSkor = collect($previewSoals)
            ->sum('skor');

    @endphp


    <div class="card mt-4">

        {{-- HEADER PREVIEW --}}

        <div class="card-header">

            <div class="row align-items-center w-100 g-3">

                <div class="col">

                    <h3 class="card-title">

                        <i class="ti ti-eye me-2"></i>

                        Preview Soal

                    </h3>

                    <div class="text-secondary small mt-1">

                        Periksa kembali soal sebelum
                        disimpan ke Bank Soal.

                    </div>

                </div>


                <div class="col-auto">

                    <div class="d-flex gap-2">

                        <span class="badge bg-blue-lt">

                            <i class="ti ti-list-numbers me-1"></i>

                            {{ count($previewSoals) }}
                            Soal

                        </span>


                        <span class="badge bg-green-lt">

                            <i class="ti ti-star me-1"></i>

                            Total Skor:
                            {{ $totalSkor }}

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- DAFTAR SOAL --}}

        {{-- DAFTAR SOAL --}}

<div class="card-body">

    <div class="preview-soal-scroll">

        <div class="row g-3">

            @foreach($previewSoals as $soal)

                <div class="col-12">

                    <div class="border rounded p-3">

                        {{-- NOMOR DAN SKOR --}}
                        <div
                            class="
                                d-flex
                                justify-content-between
                                align-items-start
                                gap-3
                                mb-3
                            "
                        >

                            <div>

                                <span class="badge bg-blue-lt">

                                    Soal
                                    {{ $soal['nomor'] }}

                                </span>

                            </div>

                            <span class="badge bg-yellow-lt">

                                <i class="ti ti-star me-1"></i>

                                {{ $soal['skor'] }}
                                Poin

                            </span>

                        </div>


                        {{-- PERTANYAAN --}}
                        <div class="mb-4">

                            <div
                                class="
                                    text-secondary
                                    small
                                    mb-1
                                "
                            >
                                Pertanyaan
                            </div>


                            @if(!empty($soal['pertanyaan']))

                                <div class="fw-medium">

                                    {{ $soal['pertanyaan'] }}

                                </div>

                            @endif


                            @if(!empty($soal['gambar_pertanyaan']))

                                <div class="mt-3 soal-preview-image-wrapper">

                                    <img
                                        src="{{ asset('storage/' . $soal['gambar_pertanyaan']) }}"
                                        alt="Gambar Pertanyaan"
                                        class="soal-preview-image rounded border"
                                    >

                                </div>

                            @endif

                        </div>


                        {{-- PILIHAN JAWABAN --}}
                        <div class="row g-2 preview-pilihan-row">

                            @foreach(
                                [
                                    'A' => [
                                        'text' => 'pilihan_a',
                                        'image' => 'gambar_a',
                                    ],

                                    'B' => [
                                        'text' => 'pilihan_b',
                                        'image' => 'gambar_b',
                                    ],

                                    'C' => [
                                        'text' => 'pilihan_c',
                                        'image' => 'gambar_c',
                                    ],

                                    'D' => [
                                        'text' => 'pilihan_d',
                                        'image' => 'gambar_d',
                                    ],

                                    'E' => [
                                        'text' => 'pilihan_e',
                                        'image' => 'gambar_e',
                                    ],
                                ]
                                as $huruf => $data
                            )

                                @php

                                    $text =
                                        $soal[$data['text']]
                                        ?? '';

                                    $image =
                                        $soal[$data['image']]
                                        ?? null;

                                @endphp


                                @if(
                                    !empty($text)
                                    || !empty($image)
                                )

                                    <div class="col-12 col-md-6 preview-pilihan-item">

                                        <div
                                            class="
                                                border
                                                rounded
                                                p-3
                                                h-100
                                            "
                                        >

                                            <div
                                                class="
                                                    d-flex
                                                    align-items-start
                                                    gap-2
                                                "
                                            >

                                                {{-- HURUF JAWABAN --}}

                                                <span
                                                    class="
                                                        avatar
                                                        avatar-sm
                                                        {{
                                                            $soal['jawaban_benar']
                                                            === $huruf
                                                                ? 'bg-success text-white'
                                                                : 'bg-secondary-lt'
                                                        }}
                                                    "
                                                >
                                                    {{ $huruf }}
                                                </span>


                                                {{-- ISI JAWABAN --}}

                                                <div class="flex-fill soal-pilihan-content">

                                                    @if(!empty($text))

                                                        <div>
                                                            {{ $text }}
                                                        </div>

                                                    @endif


                                                    @if(!empty($image))

                                                        <div class="mt-3 soal-pilihan-image-wrapper">

                                                            <img
                                                                src="{{ asset('storage/' . $image) }}"
                                                                alt="Gambar Pilihan {{ $huruf }}"
                                                                class="soal-pilihan-image rounded border"
                                                            >

                                                        </div>

                                                    @endif


                                                    @if(
                                                        $soal['jawaban_benar']
                                                        === $huruf
                                                    )

                                                        <div class="mt-2">

                                                            <span
                                                                class="
                                                                    badge
                                                                    bg-success-lt
                                                                "
                                                            >

                                                                <i
                                                                    class="
                                                                        ti
                                                                        ti-check
                                                                        me-1
                                                                    "
                                                                ></i>

                                                                Jawaban Benar

                                                            </span>

                                                        </div>

                                                    @endif

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                @endif

                            @endforeach

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>


        {{-- FOOTER PREVIEW --}}

        <div class="card-footer">

    <form
        action="{{ route('cbt.bank-soal.store') }}"
        method="POST"
    >

        @csrf

        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label required">
                    Judul Bank Soal
                </label>

                <input
                    type="text"
                    name="judul"
                    class="form-control"
                    value="{{ old('judul') }}"
                    placeholder="Contoh: Bank Soal Matematika UTS"
                    required
                >

            </div>


            <div class="col-md-6">

                <label class="form-label required">
                    Mata Pelajaran
                </label>

                <input
                    type="text"
                    name="mata_pelajaran"
                    class="form-control"
                    value="{{ old('mata_pelajaran') }}"
                    placeholder="Contoh: Matematika"
                    required
                >

            </div>


            <div class="col-md-4">

    <label class="form-label required">
        Kelas
    </label>

    <select
        name="kelas_id"
        class="form-select"
        required
    >

        <option value="">
            Pilih kelas
        </option>

        @forelse($kelas as $item)

            <option
                value="{{ $item->id }}"
                @selected(old('kelas_id') == $item->id)
            >
                Kelas {{ $item->tingkat }} 
            </option>

        @empty

            <option value="" disabled>
                Belum ada kelas aktif
            </option>

        @endforelse

    </select>

    @error('kelas_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>


            <div class="col-md-8">

                <label class="form-label">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    class="form-control"
                    rows="3"
                    placeholder="Keterangan tambahan tentang bank soal..."
                >{{ old('deskripsi') }}</textarea>

            </div>


            <div class="col-12">

                <div
                    class="
                        d-flex
                        flex-column
                        flex-md-row
                        justify-content-between
                        align-items-md-center
                        gap-3
                    "
                >

                    <div class="text-secondary small">

                        <i class="ti ti-info-circle me-1"></i>

                        Pastikan soal, kunci jawaban,
                        dan bobot sudah benar sebelum disimpan.

                    </div>


                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        <i class="ti ti-device-floppy me-1"></i>

                        Simpan ke Bank Soal

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

    </div>

@endif

{{-- ========================================================= --}}
{{-- DAFTAR BANK SOAL --}}
{{-- ========================================================= --}}

<div class="card mt-4 bank-soal-wrapper">

    <div class="card-header">

        <div>

            <h3 class="card-title">
    <i class="ti ti-folder me-2"></i>
    Bank Soal Saya
</h3>

<div class="text-secondary small mt-1">

    @if($tahunAjaran)

        Bank soal untuk tahun ajaran
        <strong>{{ $tahunAjaran->nama }}</strong>.

    @else

        Belum ada tahun ajaran aktif.

    @endif

</div>

        </div>

    </div>


    {{-- DESKTOP --}}
    <div class="d-none d-md-block">

        <div class="table-responsive">

            <table class="table table-vcenter card-table">

                <thead>

                    <tr>
                        <th>Bank Soal</th>
                        <th>Tingkat</th>
                        <th>Jumlah Soal</th>
                        <th>Status</th>
                        <th class="w-1">Aksi</th>
                    </tr>

                </thead>


                <tbody>

                @forelse($bankSoals as $bankSoal)

                    <tr>

                        <td>

                            <div class="fw-bold">
    {{ $bankSoal->judul }}
</div>

<div class="text-secondary small">
    {{ $bankSoal->mata_pelajaran }}
</div>

@if($bankSoal->kode)

    <div class="mt-2 d-flex align-items-center gap-2">

        <span
            class="badge bg-blue-lt"
            id="kode-bank-soal-{{ $bankSoal->id }}"
        >

            <i class="ti ti-key me-1"></i>

            {{ $bankSoal->kode }}

        </span>

        <button
            type="button"
            class="btn btn-sm btn-outline-primary btn-copy-kode"
            data-kode="{{ $bankSoal->kode }}"
            title="Salin kode"
        >

            <i class="ti ti-copy"></i>

        </button>

    </div>

@endif

                        </td>


                        <td>

                            <span class="badge bg-blue-lt">
                                Kelas {{ $bankSoal->tingkat }}
                            </span>

                        </td>


                        <td>

                            {{ $bankSoal->soals_count }}
                            soal

                        </td>


                        <td>

                            @if($bankSoal->status === 'siap')

                                <span class="badge bg-success-lt">
                                    Siap
                                </span>

                            @elseif($bankSoal->status === 'diproses')

                                <span class="badge bg-yellow-lt">
                                    Diproses
                                </span>

                            @else

                                <span class="badge bg-danger-lt">
                                    Gagal
                                </span>

                            @endif

                        </td>


                        <td>

                            <div class="d-flex gap-2">

                                <a
                                    href="{{
                                        route(
                                            'cbt.bank-soal.show',
                                            $bankSoal
                                        )
                                    }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="ti ti-eye me-1"></i>

                                    Detail
                                </a>


                                <form
                                    action="{{
                                        route(
                                            'cbt.bank-soal.archive',
                                            $bankSoal
                                        )
                                    }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('PATCH')


                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalArsipBankSoal"
                                        data-action="{{ route('cbt.bank-soal.archive', $bankSoal) }}"
                                        data-nama="{{ $bankSoal->judul }}"
                                    >
                                        <i class="ti ti-archive me-1"></i>
                                        Arsip
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center text-secondary py-5"
                        >
                            Belum ada bank soal yang disimpan.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- MOBILE --}}
    {{-- ========================================================= --}}
{{-- MOBILE --}}
{{-- ========================================================= --}}

<div class="d-md-none bank-soal-mobile-list">

    @forelse($bankSoals as $bankSoal)

        <div class="bank-soal-mobile-row">

            {{-- HEADER --}}
            <div class="bank-soal-mobile-header">

                <div class="bank-soal-mobile-title">

                    <div class="fw-bold">
                        {{ $bankSoal->judul }}
                    </div>

                    <div class="text-secondary small">
                        {{ $bankSoal->mata_pelajaran }}
                    </div>

                    @if($bankSoal->kode)

                        <div class="bank-soal-mobile-code">

                            <span class="badge bg-blue-lt">
                                <i class="ti ti-key me-1"></i>
                                {{ $bankSoal->kode }}
                            </span>

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary btn-copy-kode"
                                data-kode="{{ $bankSoal->kode }}"
                                title="Salin kode"
                            >
                                <i class="ti ti-copy"></i>
                            </button>

                        </div>

                    @endif

                </div>


                {{-- STATUS --}}

                @if($bankSoal->status === 'siap')

                    <span class="badge bg-success-lt">
                        Siap
                    </span>

                @elseif($bankSoal->status === 'diproses')

                    <span class="badge bg-yellow-lt">
                        Diproses
                    </span>

                @else

                    <span class="badge bg-danger-lt">
                        Gagal
                    </span>

                @endif

            </div>


            {{-- INFORMASI --}}

            <div class="bank-soal-mobile-info">

                <div>
                    <div class="text-secondary small">
                        Tingkat
                    </div>

                    <div class="fw-medium">
                        Kelas {{ $bankSoal->tingkat }}
                    </div>
                </div>


                <div>
                    <div class="text-secondary small">
                        Jumlah Soal
                    </div>

                    <div class="fw-medium">
                        {{ $bankSoal->soals_count }} soal
                    </div>
                </div>

            </div>


            {{-- AKSI --}}

            <div class="bank-soal-mobile-actions">

                <a
                    href="{{ route(
                        'cbt.bank-soal.show',
                        $bankSoal
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="ti ti-eye me-1"></i>
                    Lihat Detail
                </a>

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalArsipBankSoal"
                    data-action="{{ route(
                        'cbt.bank-soal.archive',
                        $bankSoal
                    ) }}"
                    data-nama="{{ $bankSoal->judul }}"
                >
                    <i class="ti ti-archive me-1"></i>
                    Arsipkan
                </button>

            </div>

        </div>

    @empty

        <div class="text-center text-secondary py-4">
            Belum ada bank soal.
        </div>

    @endforelse

</div>


    @if($bankSoals->hasPages())

        <div class="card-footer">
            {{ $bankSoals->links() }}
        </div>

    @endif

</div>

<div
    class="modal modal-blur fade"
    id="modalArsipBankSoal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-status bg-warning"></div>

            <div class="modal-body text-center py-4">

                <span class="avatar avatar-xl bg-warning-lt mb-3">
                    <i class="ti ti-archive"></i>
                </span>

                <h3>
                    Arsipkan Bank Soal?
                </h3>

                <div class="text-secondary">

                    Bank soal

                    <strong id="namaBankSoalArsip"></strong>

                    akan dipindahkan ke arsip.

                    <div class="mt-2">
                        Data soal tidak akan dihapus dan dapat
                        dipulihkan kembali kapan saja.
                    </div>

                </div>

            </div>


            <div class="modal-footer">

                <div class="w-100">

                    <div class="row">

                        <div class="col">

                            <button
                                type="button"
                                class="btn w-100"
                                data-bs-dismiss="modal"
                            >
                                Batal
                            </button>

                        </div>


                        <div class="col">

                            <form
                                id="formArsipBankSoal"
                                method="POST"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="btn btn-warning w-100"
                                >
                                    <i class="ti ti-archive me-1"></i>

                                    Ya, Arsipkan
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById(
        'modalArsipBankSoal'
    );

    if (!modal) {
        return;
    }

    modal.addEventListener(
        'show.bs.modal',
        function (event) {

            const button = event.relatedTarget;

            const action = button.getAttribute(
                'data-action'
            );

            const nama = button.getAttribute(
                'data-nama'
            );


            const form = document.getElementById(
                'formArsipBankSoal'
            );

            const namaElement = document.getElementById(
                'namaBankSoalArsip'
            );


            form.action = action;

            namaElement.textContent = nama;

        }
    );

    // ==========================================================
// COPY KODE BANK SOAL
// ==========================================================

document.querySelectorAll('.btn-copy-kode').forEach(function (button) {

    button.addEventListener('click', function () {

        const kode = this.getAttribute('data-kode');

        if (!kode) {
            return;
        }

        navigator.clipboard.writeText(kode)
            .then(function () {

                const icon = button.querySelector('i');

                icon.classList.remove('ti-copy');
                icon.classList.add('ti-check');

                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-success');

                button.setAttribute(
                    'title',
                    'Kode berhasil disalin'
                );

                setTimeout(function () {

                    icon.classList.remove('ti-check');
                    icon.classList.add('ti-copy');

                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-primary');

                    button.setAttribute(
                        'title',
                        'Salin kode'
                    );

                }, 1500);

            })
            .catch(function () {

                alert(
                    'Kode gagal disalin. Silakan copy secara manual.'
                );

            });

    });

});

});
</script>

@endpush

@endsection