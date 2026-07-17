@extends('layouts.app')

@section('title', 'Tahun Ajaran')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <h2 class="page-title">
                Tahun Ajaran
            </h2>

            <div class="text-secondary mt-1">
                Kelola periode tahun ajaran yang digunakan dalam sistem.
            </div>

        </div>


        <div class="col-auto">

            <a
                href="{{ route('tahun-ajaran.create') }}"
                class="btn btn-primary"
            >
                <i class="ti ti-plus me-1"></i>
                Tambah
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
            Daftar Tahun Ajaran
        </h3>

    </div>


    {{-- Responsive Table --}}
    <div class="table-responsive ssis-mobile-table">

        <table class="table table-vcenter card-table">

            <thead>

                <tr>

                    <th>
                        Tahun Ajaran
                    </th>

                    <th>
                        Periode
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

            @forelse($tahunAjarans as $tahun)

                <tr>


                    {{-- Tahun Ajaran --}}
                    <td
                        data-label="Tahun Ajaran"
                        class="fw-bold"
                    >

                        <span>
                            {{ $tahun->nama }}
                        </span>

                    </td>


                    {{-- Periode --}}
                    <td data-label="Periode">

                        <div>

                            <i
                                class="
                                    ti
                                    ti-calendar
                                    me-1
                                    text-secondary
                                "
                            ></i>

                            {{
                                $tahun
                                    ->tanggal_mulai
                                    ?->format('d/m/Y')
                                ?? '-'
                            }}

                            <span class="mx-1">
                                —
                            </span>

                            {{
                                $tahun
                                    ->tanggal_selesai
                                    ?->format('d/m/Y')
                                ?? '-'
                            }}

                        </div>

                    </td>


                    {{-- Status --}}
                    <td data-label="Status">

                        @if($tahun->is_active)

                            <span class="badge bg-success-lt">

                                <i class="ti ti-circle-check me-1"></i>

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
                                        'tahun-ajaran.edit',
                                        $tahun
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
                                        'tahun-ajaran.destroy',
                                        $tahun
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
                                            'Yakin ingin menghapus tahun ajaran ini?'
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
                        colspan="4"
                        class="
                            text-center
                            text-secondary
                            py-5
                        "
                    >

                        <i
                            class="ti ti-calendar-off"
                            style="font-size: 40px;"
                        ></i>

                        <div class="mt-2">
                            Belum ada tahun ajaran.
                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    @if($tahunAjarans->hasPages())

        <div class="card-footer">

            {{ $tahunAjarans->links() }}

        </div>

    @endif

</div>

@endsection