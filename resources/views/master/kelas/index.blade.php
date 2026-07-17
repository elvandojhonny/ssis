@extends('layouts.app')

@section('title', 'Kelas')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <h2 class="page-title">
                Data Kelas
            </h2>

            <div class="text-secondary mt-1">
                Kelola kelas yang digunakan dalam sistem.
            </div>

        </div>

        <div class="col-auto">

            <a
                href="{{ route('kelas.create') }}"
                class="btn btn-primary"
            >
                <i class="ti ti-plus me-1"></i>
                Tambah Kelas
            </a>

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
            Daftar Kelas
        </h3>

    </div>


    {{-- Responsive Table --}}
    <div class="table-responsive ssis-mobile-table">

        <table class="table table-vcenter card-table">

            <thead>

                <tr>

                    <th>
                        Nama Kelas
                    </th>

                    <th>
                        Tingkat
                    </th>

                    <th>
                        Tahun Ajaran
                    </th>

                    <th>
                        Jumlah Siswa
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

            @forelse($kelas as $item)

                <tr>

                    {{-- Nama Kelas --}}
                    <td
                        data-label="Nama Kelas"
                        class="fw-bold"
                    >

                        <span>
                            {{ $item->nama }}
                        </span>

                    </td>


                    {{-- Tingkat --}}
                    <td data-label="Tingkat">

                        <span class="badge bg-blue-lt">

                            {{ $item->tingkat }}

                        </span>

                    </td>


                    {{-- Tahun Ajaran --}}
                    <td data-label="Tahun Ajaran">

                        {{
                            $item
                                ->tahunAjaran
                                ?->nama
                            ?? '-'
                        }}

                    </td>


                    {{-- Jumlah Siswa --}}
                    <td data-label="Jumlah Siswa">

                        <span>

                            <i
                                class="
                                    ti
                                    ti-users
                                    me-1
                                    text-secondary
                                "
                            ></i>

                            {{ $item->siswa_count }}
                            siswa

                        </span>

                    </td>


                    {{-- Status --}}
                    <td data-label="Status">

                        @if($item->is_active)

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
                                        'kelas.edit',
                                        $item
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
                                        'kelas.destroy',
                                        $item
                                    )
                                }}"
                                method="POST"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="
                                        btn
                                        btn-sm
                                        btn-outline-danger
                                    "
                                    onclick="
                                        return confirm(
                                            'Yakin ingin menghapus kelas ini?'
                                        )
                                    "
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
                        colspan="6"
                        class="
                            text-center
                            text-secondary
                            py-5
                        "
                    >

                        <i
                            class="ti ti-school"
                            style="font-size: 40px;"
                        ></i>

                        <div class="mt-2">
                            Belum ada data kelas.
                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    @if($kelas->hasPages())

        <div class="card-footer">

            {{ $kelas->links() }}

        </div>

    @endif

</div>

@endsection