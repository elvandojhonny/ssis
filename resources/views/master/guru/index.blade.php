@extends('layouts.app')

@section('title', 'Guru')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <h2 class="page-title">
                Data Guru
            </h2>

            <div class="text-secondary mt-1">
                Kelola data dan akun guru.
            </div>

        </div>


        <div class="col-12 col-md-auto">

    <div class="d-flex flex-wrap gap-2">

        <a
            href="{{ route('guru.template-import') }}"
            class="btn btn-outline-success"
        >
            <i class="ti ti-file-spreadsheet me-1"></i>
            Download Template
        </a>

        <button
            type="button"
            class="btn btn-outline-primary"
            data-bs-toggle="modal"
            data-bs-target="#modalImportGuru"
        >
            <i class="ti ti-file-upload me-1"></i>
            Import Excel
        </button>

        <a
            href="{{ route('guru.create') }}"
            class="btn btn-primary"
        >
            <i class="ti ti-plus me-1"></i>
            Tambah Guru
        </a>

    </div>

</div>

    </div>

</div>


{{-- Alert Success --}}
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
            aria-label="Close"
        ></button>

    </div>

@endif

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
            aria-label="Close"
        ></button>

    </div>

@endif


{{-- Alert Error --}}
@if(session('error'))

    <div
        class="alert alert-danger"
        role="alert"
    >
        {{ session('error') }}
    </div>

@endif


<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Daftar Guru
        </h3>

    </div>


    {{-- Responsive Table --}}
    <div class="table-responsive ssis-mobile-table">

        <table class="table table-vcenter card-table">

            <thead>

                <tr>

                    <th>
                        Guru
                    </th>

                    <th>
                        NIP
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

            @forelse($gurus as $guru)

                <tr>

                    {{-- Guru --}}
                    <td data-label="Guru">

                        <div class="d-flex align-items-center">

                            {{-- Avatar --}}
                            <span class="avatar avatar-sm me-3">

                                {{
                                    strtoupper(
                                        substr(
                                            $guru->nama,
                                            0,
                                            1
                                        )
                                    )
                                }}

                            </span>


                            {{-- Informasi Guru --}}
                            <div class="text-start">

                                <div class="fw-bold">
                                    {{ $guru->nama }}
                                </div>

                                <div class="text-secondary small">

                                    {{
                                        $guru
                                            ->user
                                            ?->email
                                        ?? '-'
                                    }}

                                </div>

                            </div>

                        </div>

                    </td>


                    {{-- NIP --}}
                    <td data-label="NIP">

                        {{
                            $guru->nip
                            ?? '-'
                        }}

                    </td>


                    {{-- Username --}}
                    <td data-label="Username">

                        <span>

                            <i
                                class="
                                    ti
                                    ti-user
                                    me-1
                                    text-secondary
                                "
                            ></i>

                            {{
                                $guru
                                    ->user
                                    ?->username
                                ?? '-'
                            }}

                        </span>

                    </td>


                    {{-- Status --}}
                    <td data-label="Status">

                        @if($guru->is_active)

                            <span class="badge bg-success-lt">
                                Aktif
                            </span>

                        @else

                            <span class="badge bg-secondary-lt">
                                Tidak Aktif
                            </span>

                        @endif

                    </td>


                    {{-- Aksi --}}
                    <td data-label="Aksi">

                        <div
                            class="
                                d-flex
                                gap-2
                                justify-content-end
                                ssis-table-actions
                            "
                        >

                            {{-- Edit --}}
                            <a
                                href="{{
                                    route(
                                        'guru.edit',
                                        $guru
                                    )
                                }}"
                                class="
                                    btn
                                    btn-sm
                                    btn-outline-primary
                                "
                            >

                                <i class="ti ti-edit me-1"></i>

                                Edit

                            </a>


                            {{-- Hapus --}}
                            <form
                                action="{{
                                    route(
                                        'guru.destroy',
                                        $guru
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
                                data-delete-action="{{ route('guru.destroy', $guru) }}"
                                data-delete-name="{{ $guru->nama }}"
                                data-delete-warning="Jika guru sudah memiliki bank soal atau riwayat ujian, data tidak akan dihapus permanen dan akun akan dinonaktifkan."
                            >
                                <i class="ti ti-trash me-1"></i>
                                Hapus
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
                            text-secondary
                            py-5
                        "
                    >

                        <i
                            class="ti ti-users"
                            style="font-size: 40px;"
                        ></i>

                        <div class="mt-2">
                            Belum ada data guru.
                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    @if($gurus->hasPages())

        <div class="card-footer">

            {{ $gurus->links() }}

        </div>

    @endif

</div>

<div
    class="modal modal-blur fade"
    id="modalImportGuru"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form
                action="{{ route('guru.import') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Import Data Guru
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label required">
                            File Excel
                        </label>

                        <input
                            type="file"
                            name="file_import"
                            class="form-control"
                            accept=".xlsx,.xls"
                            required
                        >

                        <div class="form-hint mt-2">
                            Gunakan template guru yang telah
                            disediakan agar format data sesuai.
                        </div>

                    </div>

                    <div class="alert alert-info mb-0">

                        <div class="d-flex">

                            <div class="me-2">
                                <i class="ti ti-info-circle"></i>
                            </div>

                            <div>
                                Setiap baris akan otomatis membuat
                                akun login dengan role guru sekaligus
                                data profil guru.
                            </div>

                        </div>

                    </div>

                </div>

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