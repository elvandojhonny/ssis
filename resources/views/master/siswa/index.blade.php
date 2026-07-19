@extends('layouts.app')

@section('title', 'Siswa')

@section('content')


{{-- ========================================================= --}}
{{-- PAGE HEADER --}}
{{-- ========================================================= --}}

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <h2 class="page-title">
                Data Siswa
            </h2>

            <div class="text-secondary mt-1">
                Kelola data dan akun siswa berdasarkan kelas.
            </div>

        </div>


        {{-- ================================================= --}}
        {{-- ACTION BUTTON --}}
        {{-- ================================================= --}}

        <div class="col-12 col-md-auto">

            <div class="d-flex flex-wrap gap-2">

                {{-- Download Template --}}

                <a
                    href="{{ route('siswa.template-import') }}"
                    class="btn btn-outline-success"
                >

                    <i class="ti ti-file-spreadsheet me-1"></i>

                    Download Template

                </a>


                {{-- Import Excel --}}

                <button
                    type="button"
                    class="btn btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalImportSiswa"
                >

                    <i class="ti ti-file-upload me-1"></i>

                    Import Excel

                </button>


                {{-- Tambah Siswa --}}

                <a
                    href="{{ route('siswa.create') }}"
                    class="btn btn-primary"
                >

                    <i class="ti ti-plus me-1"></i>

                    Tambah Siswa

                </a>

            </div>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- ALERT SUCCESS --}}
{{-- ========================================================= --}}

@if(session('success'))

    <div
        class="alert alert-success alert-dismissible"
        role="alert"
    >

        <div class="d-flex">

            <div>

                <i class="ti ti-circle-check me-2"></i>

            </div>


            <div>

                {{ session('success') }}

            </div>

        </div>


        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Tutup"
        ></button>

    </div>

@endif



{{-- ========================================================= --}}
{{-- ALERT WARNING --}}
{{-- ========================================================= --}}

@if(session('warning'))

    <div
        class="alert alert-warning alert-dismissible"
        role="alert"
    >

        <div class="d-flex">

            <div>

                <i class="ti ti-alert-triangle me-2"></i>

            </div>


            <div>

                {{ session('warning') }}

            </div>

        </div>


        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Tutup"
        ></button>

    </div>

@endif



{{-- ========================================================= --}}
{{-- ALERT ERROR --}}
{{-- ========================================================= --}}

@if(session('error'))

    <div
        class="alert alert-danger alert-dismissible"
        role="alert"
    >

        <div class="d-flex">

            <div>

                <i class="ti ti-alert-circle me-2"></i>

            </div>


            <div>

                {{ session('error') }}

            </div>

        </div>


        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Tutup"
        ></button>

    </div>

@endif



{{-- ========================================================= --}}
{{-- IMPORT ERROR --}}
{{-- ========================================================= --}}

@if(session('import_errors'))

    <div class="alert alert-warning">

        <div class="fw-bold mb-2">

            <i class="ti ti-alert-triangle me-1"></i>

            Beberapa data tidak berhasil diimport:

        </div>


        <ul class="mb-0">

            @foreach(session('import_errors') as $error)

                <li>

                    {{ $error }}

                </li>

            @endforeach

        </ul>

    </div>

@endif



{{-- ========================================================= --}}
{{-- TAB PILIHAN TINGKAT KELAS --}}
{{-- ========================================================= --}}

<ul
    class="nav nav-tabs mb-4"
    id="kelasTabs"
    role="tablist"
>

    <li class="nav-item" role="presentation">

        <button
            class="nav-link active"
            id="kelas-x-tab"
            data-bs-toggle="tab"
            data-bs-target="#kelas-x"
            type="button"
            role="tab"
        >
            <i class="ti ti-school me-1"></i>
            Kelas X
        </button>

    </li>


    <li class="nav-item" role="presentation">

        <button
            class="nav-link"
            id="kelas-xi-tab"
            data-bs-toggle="tab"
            data-bs-target="#kelas-xi"
            type="button"
            role="tab"
        >
            <i class="ti ti-school me-1"></i>
            Kelas XI
        </button>

    </li>


    <li class="nav-item" role="presentation">

        <button
            class="nav-link"
            id="kelas-xii-tab"
            data-bs-toggle="tab"
            data-bs-target="#kelas-xii"
            type="button"
            role="tab"
        >
            <i class="ti ti-school me-1"></i>
            Kelas XII
        </button>

    </li>

</ul>


{{-- ========================================================= --}}
{{-- TAB CONTENT --}}
{{-- ========================================================= --}}

<div class="tab-content" id="kelasTabsContent">


    @php

        $daftarKelas = [

            'X' => [
                'X IPA',
                'X IPS',
            ],

            'XI' => [
                'XI IPA',
                'XI IPS',
            ],

            'XII' => [
                'XII IPA',
                'XII IPS',
            ],

        ];

    @endphp


    @foreach($daftarKelas as $tingkat => $kelasList)

        @php

            $tabId = match($tingkat) {

                'X' => 'kelas-x',

                'XI' => 'kelas-xi',

                'XII' => 'kelas-xii',

            };

        @endphp


        <div
            class="
                tab-pane
                fade
                {{ $tingkat === 'X' ? 'show active' : '' }}
            "
            id="{{ $tabId }}"
            role="tabpanel"
        >


            {{-- ================================================= --}}
            {{-- HEADER TINGKAT --}}
            {{-- ================================================= --}}

            <div class="mb-4">

                <h2 class="page-title">

                    Siswa Kelas {{ $tingkat }}

                </h2>

                <div class="text-secondary mt-1">

                    Data siswa jurusan IPA dan IPS
                    tingkat {{ $tingkat }}.

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- IPA DAN IPS --}}
            {{-- ================================================= --}}

            <div class="row row-cards">


                @foreach($kelasList as $namaKelas)

                    @php

                        $dataSiswa = $siswas->get(
                            strtoupper($namaKelas),
                            collect()
                        );

                    @endphp


                    <div class="col-12">


                        <div class="card">


                            {{-- ================================= --}}
                            {{-- CARD HEADER --}}
                            {{-- ================================= --}}

                            <div class="card-header">

                                <div
                                    class="
                                        d-flex
                                        justify-content-between
                                        align-items-center
                                        w-100
                                    "
                                >

                                    <div>

                                        <h3 class="card-title">

                                            {{ $namaKelas }}

                                        </h3>

                                        <div
                                            class="
                                                text-secondary
                                                small
                                                mt-1
                                            "
                                        >

                                            Daftar siswa
                                            {{ $namaKelas }}

                                        </div>

                                    </div>


                                    <span class="badge bg-blue-lt">

                                        {{ $dataSiswa->count() }}

                                        Siswa

                                    </span>

                                </div>

                            </div>


                            {{-- ================================= --}}
                            {{-- TABLE --}}
                            {{-- ================================= --}}

                            <div
                                class="
                                    table-responsive
                                    ssis-mobile-table
                                "
                            >

                                <table
                                    class="
                                        table
                                        table-vcenter
                                        card-table
                                    "
                                >

                                    <thead>

                                        <tr>

                                            <th>
                                                Siswa
                                            </th>

                                            <th>
                                                NIS / NISN
                                            </th>

                                            <th>
                                                Username
                                            </th>

                                            <th>
                                                Status
                                            </th>

                                            <th class="w-1">
                                                Aksi
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>


                                    @forelse($dataSiswa as $siswa)


                                        <tr>


                                            {{-- ================= --}}
                                            {{-- SISWA --}}
                                            {{-- ================= --}}

                                            <td data-label="Siswa">

                                                <div
                                                    class="
                                                        d-flex
                                                        align-items-center
                                                    "
                                                >

                                                    <span
                                                        class="
                                                            avatar
                                                            avatar-sm
                                                            me-3
                                                        "
                                                    >

                                                        {{
                                                            strtoupper(
                                                                substr(
                                                                    $siswa->nama,
                                                                    0,
                                                                    1
                                                                )
                                                            )
                                                        }}

                                                    </span>


                                                    <div class="text-start">

                                                        <div class="fw-bold">

                                                            {{ $siswa->nama }}

                                                        </div>


                                                        <div
                                                            class="
                                                                text-secondary
                                                                small
                                                            "
                                                        >

                                                            @if(
                                                                $siswa->jenis_kelamin
                                                                === 'L'
                                                            )

                                                                Laki-laki

                                                            @elseif(
                                                                $siswa->jenis_kelamin
                                                                === 'P'
                                                            )

                                                                Perempuan

                                                            @else

                                                                -

                                                            @endif

                                                        </div>

                                                    </div>

                                                </div>

                                            </td>


                                            {{-- ================= --}}
                                            {{-- NIS / NISN --}}
                                            {{-- ================= --}}

                                            <td data-label="NIS / NISN">

                                                <div>

                                                    {{ $siswa->nis }}

                                                </div>

                                                <div
                                                    class="
                                                        text-secondary
                                                        small
                                                    "
                                                >

                                                    NISN:

                                                    {{
                                                        $siswa->nisn
                                                        ?? '-'
                                                    }}

                                                </div>

                                            </td>


                                            {{-- ================= --}}
                                            {{-- USERNAME --}}
                                            {{-- ================= --}}

                                            <td data-label="Username">

                                                <i
                                                    class="
                                                        ti
                                                        ti-user
                                                        me-1
                                                        text-secondary
                                                    "
                                                ></i>

                                                {{
                                                    $siswa
                                                        ->user
                                                        ?->username
                                                    ?? '-'
                                                }}

                                            </td>


                                            {{-- ================= --}}
                                            {{-- STATUS --}}
                                            {{-- ================= --}}

                                            <td data-label="Status">

                                                @if($siswa->is_active)

                                                    <span
                                                        class="
                                                            badge
                                                            bg-success-lt
                                                        "
                                                    >

                                                        Aktif

                                                    </span>

                                                @else

                                                    <span
                                                        class="
                                                            badge
                                                            bg-secondary-lt
                                                        "
                                                    >

                                                        Tidak Aktif

                                                    </span>

                                                @endif

                                            </td>


                                            {{-- ================= --}}
                                            {{-- AKSI --}}
                                            {{-- ================= --}}

                                            <td data-label="Aksi">

                                                <div
                                                    class="
                                                        d-flex
                                                        gap-2
                                                        justify-content-end
                                                        ssis-table-actions
                                                    "
                                                >


                                                    {{-- EDIT --}}

                                                    <a
                                                        href="{{
                                                            route(
                                                                'siswa.edit',
                                                                $siswa
                                                            )
                                                        }}"
                                                        class="
                                                            btn
                                                            btn-sm
                                                            btn-outline-primary
                                                        "
                                                        title="Edit"
                                                    >

                                                        <i class="ti ti-edit"></i>

                                                    </a>


                                                    {{-- QR --}}

                                                    <a
                                                        href="{{
                                                            route(
                                                                'siswa.qr.show',
                                                                $siswa
                                                            )
                                                        }}"
                                                        class="
                                                            btn
                                                            btn-sm
                                                            btn-outline-success
                                                        "
                                                        title="QR Code"
                                                    >

                                                        <i
                                                            class="
                                                                ti
                                                                ti-qrcode
                                                                me-1
                                                            "
                                                        ></i>

                                                        QR

                                                    </a>


                                                    {{-- HAPUS --}}

                                                    <form
                                                        action="{{
                                                            route(
                                                                'siswa.destroy',
                                                                $siswa
                                                            )
                                                        }}"
                                                        method="POST"
                                                    >

                                                        @csrf

                                                        @method('DELETE')


                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#globalDeleteModal"
                                                            data-delete-action="{{ route('siswa.destroy', $siswa) }}"
                                                            data-delete-name="{{ $siswa->nama }}"
                                                            data-delete-warning="Jika siswa sudah memiliki riwayat absensi atau ujian, data tidak akan dihapus permanen dan akun akan dinonaktifkan."
                                                        >
                                                            <i class="ti ti-trash"></i>
                                                        </button>

                                                    </form>


                                                </div>

                                            </td>


                                        </tr>


                                    @empty


                                        <tr class="ssis-empty-row">

                                            <td
                                                colspan="5"
                                                class="
                                                    text-center
                                                    py-5
                                                "
                                            >

                                                <div class="text-secondary">

                                                    <i
                                                        class="
                                                            ti
                                                            ti-users
                                                        "
                                                        style="
                                                            font-size: 36px;
                                                        "
                                                    ></i>


                                                    <div class="mt-2">

                                                        Belum ada siswa
                                                        di kelas
                                                        {{ $namaKelas }}.

                                                    </div>

                                                </div>

                                            </td>

                                        </tr>


                                    @endforelse


                                    </tbody>

                                </table>

                            </div>


                        </div>

                    </div>


                @endforeach


            </div>


        </div>


    @endforeach


</div>



{{-- ========================================================= --}}
{{-- MODAL IMPORT SISWA --}}
{{-- ========================================================= --}}

<div
    class="modal modal-blur fade"
    id="modalImportSiswa"
    tabindex="-1"
    aria-hidden="true"
>

    <div
        class="
            modal-dialog
            modal-dialog-centered
        "
    >

        <div class="modal-content">


            <form
                action="{{ route('siswa.import') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf


                {{-- ========================================= --}}
                {{-- MODAL HEADER --}}
                {{-- ========================================= --}}

                <div class="modal-header">

                    <h5 class="modal-title">

                        Import Data Siswa

                    </h5>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"
                    ></button>

                </div>



                {{-- ========================================= --}}
                {{-- MODAL BODY --}}
                {{-- ========================================= --}}

                <div class="modal-body">


                    <div class="mb-3">

                        <label class="form-label required">

                            File Excel

                        </label>


                        <input
                            type="file"
                            name="file_import"
                            class="
                                form-control
                                @error('file_import')
                                    is-invalid
                                @enderror
                            "
                            accept=".xlsx,.xls"
                            required
                        >


                        @error('file_import')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror


                        <div class="form-hint mt-2">

                            Gunakan template yang disediakan
                            agar format data sesuai dengan sistem.

                        </div>

                    </div>



                    {{-- ===================================== --}}
                    {{-- INFORMASI --}}
                    {{-- ===================================== --}}

                    <div class="alert alert-info mb-0">

                        <div class="d-flex">

                            <div class="me-2">

                                <i
                                    class="
                                        ti
                                        ti-info-circle
                                    "
                                ></i>

                            </div>


                            <div>

                                Setiap baris pada file Excel
                                akan membuat akun login sekaligus
                                data siswa secara otomatis.

                            </div>

                        </div>

                    </div>


                </div>



                {{-- ========================================= --}}
                {{-- MODAL FOOTER --}}
                {{-- ========================================= --}}

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal"
                    >

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="ti ti-upload me-1"></i>

                        Import Data

                    </button>

                </div>


            </form>


        </div>

    </div>

</div>

@endsection