@extends('layouts.app')

@section('title', 'Bank Soal')

@section('content')

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
                            Microsoft Word (.docx), maksimal 10 MB.

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

        <div class="card-body">

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

                                <div class="fw-medium">

                                    {{ $soal['pertanyaan'] }}

                                </div>

                            </div>


                            {{-- PILIHAN JAWABAN --}}

                            <div class="row g-2">

                                @foreach(
                                    [
                                        'A' => 'pilihan_a',
                                        'B' => 'pilihan_b',
                                        'C' => 'pilihan_c',
                                        'D' => 'pilihan_d',
                                        'E' => 'pilihan_e',
                                    ]
                                    as $huruf => $field
                                )

                                    @if(!empty($soal[$field]))

                                        <div class="col-12 col-md-6">

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


                                                    <div class="flex-fill">

                                                        <div>

                                                            {{
                                                                $soal[
                                                                    $field
                                                                ]
                                                            }}

                                                        </div>


                                                        @if(
                                                            $soal[
                                                                'jawaban_benar'
                                                            ]
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
                    Tingkat
                </label>

                <select
                    name="tingkat"
                    class="form-select"
                    required
                >

                    <option value="">
                        Pilih tingkat
                    </option>

                    <option
                        value="10"
                        @selected(old('tingkat') == 10)
                    >
                        Kelas X
                    </option>

                    <option
                        value="11"
                        @selected(old('tingkat') == 11)
                    >
                        Kelas XI
                    </option>

                    <option
                        value="12"
                        @selected(old('tingkat') == 12)
                    >
                        Kelas XII
                    </option>

                </select>

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

<div class="card mt-4">

    <div class="card-header">

        <div>

            <h3 class="card-title">
                <i class="ti ti-folder me-2"></i>
                Bank Soal Saya
            </h3>

            <div class="text-secondary small mt-1">
                Daftar bank soal yang telah Anda simpan.
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
    <div class="d-md-none">

        @forelse($bankSoals as $bankSoal)

            <div class="border-bottom p-3">

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-start
                        gap-3
                    "
                >

                    <div>

                        <div class="fw-bold">
                            {{ $bankSoal->judul }}
                        </div>

                        <div class="text-secondary small mt-1">
                            {{ $bankSoal->mata_pelajaran }}
                        </div>

                    </div>


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


                <div class="row g-2 mt-3">

                    <div class="col-6">

                        <div class="text-secondary small">
                            Tingkat
                        </div>

                        <div class="fw-medium">
                            Kelas {{ $bankSoal->tingkat }}
                        </div>

                    </div>


                    <div class="col-6">

                        <div class="text-secondary small">
                            Jumlah Soal
                        </div>

                        <div class="fw-medium">
                            {{ $bankSoal->soals_count }} soal
                        </div>

                    </div>

                </div>


                <a
                    href="{{ route(
                        'cbt.bank-soal.show',
                        $bankSoal
                    ) }}"
                    class="btn btn-outline-primary w-100 mt-3"
                >
                    <i class="ti ti-eye me-1"></i>
                    Lihat Detail
                </a>

                <button
                    type="button"
                    class="btn btn-outline-secondary w-100 mt-2"
                    data-bs-toggle="modal"
                    data-bs-target="#modalArsipBankSoal"
                    data-action="{{ route('cbt.bank-soal.archive', $bankSoal) }}"
                    data-nama="{{ $bankSoal->judul }}"
                >
                    <i class="ti ti-archive me-1"></i>
                    Arsipkan
                </button>

            </div>

        @empty

            <div class="text-center text-secondary py-5">

                <i
                    class="ti ti-folder-off mb-2"
                    style="font-size: 40px;"
                ></i>

                <div>
                    Belum ada bank soal yang disimpan.
                </div>

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

});
</script>

@endpush

@endsection