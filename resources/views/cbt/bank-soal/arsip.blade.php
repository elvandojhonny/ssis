@extends('layouts.app')

@section('title', 'Arsip Bank Soal')

@section('content')


{{-- ========================================================= --}}
{{-- PAGE HEADER --}}
{{-- ========================================================= --}}

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Modul CBT
            </div>

            <h2 class="page-title">
                Arsip Bank Soal
            </h2>

            <div class="text-secondary mt-1">
                Daftar bank soal yang telah diarsipkan.
            </div>

        </div>


        <div class="col-12 col-md-auto">

            <a
                href="{{ route('cbt.bank-soal.index') }}"
                class="btn btn-outline-secondary w-100"
            >

                <i class="ti ti-arrow-left me-1"></i>

                Kembali ke Bank Soal

            </a>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- ALERT --}}
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
{{-- DAFTAR ARSIP --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-header">

        <div>

            <h3 class="card-title">

                <i class="ti ti-archive me-2"></i>

                Bank Soal Diarsipkan

            </h3>


            <div class="text-secondary small mt-1">

                Bank soal tetap tersimpan dan dapat
                dipulihkan kapan saja.

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- DESKTOP --}}
    {{-- ===================================================== --}}

    <div class="d-none d-md-block">

        <div class="table-responsive">

            <table class="table table-vcenter card-table">

                <thead>

                    <tr>

                        <th>
                            Bank Soal
                        </th>

                        <th>
                            Tingkat
                        </th>

                        <th>
                            Jumlah Soal
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

                            <span class="badge bg-secondary-lt">

                                <i class="ti ti-archive me-1"></i>

                                Diarsipkan

                            </span>

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
                                    class="
                                        btn
                                        btn-sm
                                        btn-outline-primary
                                    "
                                >

                                    <i class="ti ti-eye me-1"></i>

                                    Detail

                                </a>


                                <form
                                    action="{{
                                        route(
                                            'cbt.bank-soal.restore',
                                            $bankSoal
                                        )
                                    }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('PATCH')


                                    <button
                                        type="submit"
                                        class="
                                            btn
                                            btn-sm
                                            btn-outline-success
                                        "
                                    >

                                        <i
                                            class="
                                                ti
                                                ti-restore
                                                me-1
                                            "
                                        ></i>

                                        Pulihkan

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="
                                text-center
                                text-secondary
                                py-5
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-archive-off
                                    mb-2
                                "
                                style="font-size: 40px;"
                            ></i>


                            <div>
                                Belum ada bank soal yang diarsipkan.
                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- MOBILE --}}
    {{-- ===================================================== --}}

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


                    <span class="badge bg-secondary-lt">

                        Arsip

                    </span>

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

                            {{ $bankSoal->soals_count }}

                            soal

                        </div>

                    </div>

                </div>


                <a
                    href="{{
                        route(
                            'cbt.bank-soal.show',
                            $bankSoal
                        )
                    }}"
                    class="
                        btn
                        btn-outline-primary
                        w-100
                        mt-3
                    "
                >

                    <i class="ti ti-eye me-1"></i>

                    Lihat Detail

                </a>


                <form
                    action="{{
                        route(
                            'cbt.bank-soal.restore',
                            $bankSoal
                        )
                    }}"
                    method="POST"
                    class="mt-2"
                >

                    @csrf
                    @method('PATCH')


                    <button
                        type="submit"
                        class="
                            btn
                            btn-outline-success
                            w-100
                        "
                    >

                        <i class="ti ti-restore me-1"></i>

                        Pulihkan Bank Soal

                    </button>

                </form>

            </div>


        @empty

            <div class="text-center text-secondary py-5">

                <i
                    class="ti ti-archive-off mb-2"
                    style="font-size: 40px;"
                ></i>


                <div>

                    Belum ada bank soal yang diarsipkan.

                </div>

            </div>

        @endforelse

    </div>


    {{-- ===================================================== --}}
    {{-- PAGINATION --}}
    {{-- ===================================================== --}}

    @if($bankSoals->hasPages())

        <div class="card-footer">

            {{ $bankSoals->links() }}

        </div>

    @endif

</div>

@endsection