@extends('layouts.app')

@section('title', 'Buka Blokir Ujian')

@section('content')

{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Computer Based Test
            </div>

            <h2 class="page-title">
                Buka Blokir Ujian
            </h2>

            <div class="text-secondary mt-1">
                Kelola peserta yang terblokir selama mengerjakan ujian.
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
{{-- STATISTIK --}}
{{-- ========================================================= --}}

<div class="row row-cards mb-4">

    <div class="col-12 col-md-6 col-lg-4">

        <div class="card">

            <div class="card-body">

                <div class="d-flex align-items-center">

                    <div>

                        <div class="text-secondary">
                            Peserta Terblokir
                        </div>

                        <div class="h1 mb-0 mt-1 text-danger">
                            {{ $totalDiblokir }}
                        </div>

                    </div>


                    <div class="ms-auto">

                        <span
                            class="
                                avatar
                                avatar-lg
                                bg-danger-lt
                            "
                        >

                            <i class="ti ti-lock fs-1"></i>

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- PENCARIAN --}}
{{-- ========================================================= --}}

<div class="card mb-4">

    <div class="card-body">

        <form
            action="{{ route('cbt.blokir.index') }}"
            method="GET"
        >

            <div class="row g-2">

                <div class="col-12 col-md">

                    <div class="input-icon">

                        <span class="input-icon-addon">

                            <i class="ti ti-search"></i>

                        </span>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Cari nama siswa, NISN, atau nama ujian..."
                        >

                    </div>

                </div>


                <div class="col-12 col-md-auto">

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >

                        <i class="ti ti-search me-1"></i>

                        Cari

                    </button>

                </div>


                @if(request()->filled('search'))

                    <div class="col-12 col-md-auto">

                        <a
                            href="{{ route('cbt.blokir.index') }}"
                            class="btn btn-outline-secondary w-100"
                        >

                            <i class="ti ti-x me-1"></i>

                            Reset

                        </a>

                    </div>

                @endif

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- DAFTAR PESERTA TERBLOKIR --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-header">

        <div>

            <h3 class="card-title">
                Daftar Peserta Terblokir
            </h3>

            <div class="text-secondary small mt-1">
                Peserta akan hilang dari daftar setelah blokir berhasil dibuka.
            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- DESKTOP --}}
    {{-- ===================================================== --}}

    <div class="table-responsive d-none d-md-block">

        <table class="table table-vcenter card-table">

            <thead>

                <tr>

                    <th>
                        Siswa
                    </th>

                    <th>
                        NISN
                    </th>

                    <th>
                        Kelas
                    </th>

                    <th>
                        Ujian
                    </th>

                    <th>
                        Pelanggaran
                    </th>

                    <th>
                        Diblokir
                    </th>

                    <th class="w-1">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($pengerjaans as $pengerjaan)

                    <tr>


                        {{-- SISWA --}}

                        <td>

                            <div class="fw-bold">

                                {{
                                    $pengerjaan
                                        ->siswa
                                        ?->nama
                                    ?? '-'
                                }}

                            </div>

                        </td>


                        {{-- NISN --}}

                        <td>

                            {{
                                $pengerjaan
                                    ->siswa
                                    ?->nisn
                                ?? '-'
                            }}

                        </td>


                        {{-- KELAS --}}

                        <td>

                            <span class="badge bg-blue-lt">

                                {{
                                    $pengerjaan
                                        ->siswa
                                        ?->kelas
                                        ?->nama
                                    ?? '-'
                                }}

                            </span>

                        </td>


                        {{-- UJIAN --}}

                        <td>

                            <div class="fw-medium">

                                {{
                                    $pengerjaan
                                        ->ujian
                                        ?->judul
                                    ?? '-'
                                }}

                            </div>


                            <div class="text-secondary small">

                                {{
                                    $pengerjaan
                                        ->ujian
                                        ?->bankSoal
                                        ?->mata_pelajaran
                                    ?? '-'
                                }}

                            </div>

                        </td>


                        {{-- PELANGGARAN --}}

                        <td>

                            <span class="badge bg-danger-lt">

                                {{
                                    $pengerjaan
                                        ->jumlah_pelanggaran
                                    ?? 0
                                }}

                                kali

                            </span>

                        </td>


                        {{-- WAKTU DIBLOKIR --}}

                        <td>

                            @if($pengerjaan->diblokir_pada)

                                <div>

                                    {{
                                        $pengerjaan
                                            ->diblokir_pada
                                            ->format(
                                                'd/m/Y'
                                            )
                                    }}

                                </div>


                                <div class="text-secondary small">

                                    {{
                                        $pengerjaan
                                            ->diblokir_pada
                                            ->format(
                                                'H:i'
                                            )
                                    }}

                                </div>

                            @else

                                -

                            @endif

                        </td>


                        {{-- AKSI --}}

                        <td>

                            <button
                                type="button"
                                class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBukaBlokir"
                                data-action="{{ route('cbt.blokir.buka', $pengerjaan) }}"
                                data-nama="{{ $pengerjaan->siswa?->nama ?? 'Peserta' }}"
                            >
                                <i class="ti ti-lock-open me-1"></i>
                                Buka Blokir
                            </button>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="
                                text-center
                                text-secondary
                                py-5
                            "
                        >

                            <div class="mb-3">

                                <i
                                    class="
                                        ti
                                        ti-lock-open
                                        fs-1
                                    "
                                ></i>

                            </div>


                            @if(request()->filled('search'))

                                Tidak ada peserta terblokir yang sesuai dengan pencarian.

                            @else

                                Tidak ada peserta yang sedang diblokir.

                            @endif

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- ===================================================== --}}
    {{-- MOBILE --}}
    {{-- ===================================================== --}}

    <div class="d-md-none">

        @forelse($pengerjaans as $pengerjaan)

            <div class="p-3 border-bottom">


                {{-- HEADER SISWA --}}

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

                        <div class="fw-bold">

                            {{
                                $pengerjaan
                                    ->siswa
                                    ?->nama
                                ?? '-'
                            }}

                        </div>


                        <div class="text-secondary small mt-1">

                            NISN:

                            {{
                                $pengerjaan
                                    ->siswa
                                    ?->nisn
                                ?? '-'
                            }}

                        </div>

                    </div>


                    <span class="badge bg-danger-lt">

                        <i class="ti ti-lock me-1"></i>

                        Diblokir

                    </span>

                </div>


                {{-- INFORMASI UJIAN --}}

                <div class="mb-3">

                    <div class="text-secondary small">
                        Ujian
                    </div>

                    <div class="fw-medium">

                        {{
                            $pengerjaan
                                ->ujian
                                ?->judul
                            ?? '-'
                        }}

                    </div>

                </div>


                <div class="row g-3 mb-3">


                    {{-- KELAS --}}

                    <div class="col-6">

                        <div class="text-secondary small">
                            Kelas
                        </div>

                        <div class="fw-medium">

                            {{
                                $pengerjaan
                                    ->siswa
                                    ?->kelas
                                    ?->nama
                                ?? '-'
                            }}

                        </div>

                    </div>


                    {{-- PELANGGARAN --}}

                    <div class="col-6">

                        <div class="text-secondary small">
                            Pelanggaran
                        </div>

                        <div class="text-danger fw-bold">

                            {{
                                $pengerjaan
                                    ->jumlah_pelanggaran
                                ?? 0
                            }}

                            kali

                        </div>

                    </div>


                    {{-- DIBLOKIR PADA --}}

                    <div class="col-12">

                        <div class="text-secondary small">
                            Diblokir Pada
                        </div>

                        <div>

                            {{
                                $pengerjaan
                                    ->diblokir_pada
                                    ?->format(
                                        'd/m/Y H:i'
                                    )
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>


                {{-- TOMBOL BUKA BLOKIR --}}

                <button
                    type="button"
                    class="btn btn-warning w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#modalBukaBlokir"
                    data-action="{{ route('cbt.blokir.buka', $pengerjaan) }}"
                    data-nama="{{ $pengerjaan->siswa?->nama ?? 'Peserta' }}"
                >
                    <i class="ti ti-lock-open me-1"></i>
                    Buka Blokir Peserta
                </button>

            </div>


        @empty

            <div
                class="
                    text-center
                    text-secondary
                    py-5
                    px-3
                "
            >

                <div class="mb-3">

                    <i class="ti ti-lock-open fs-1"></i>

                </div>


                @if(request()->filled('search'))

                    Tidak ada peserta terblokir yang sesuai dengan pencarian.

                @else

                    Tidak ada peserta yang sedang diblokir.

                @endif

            </div>

        @endforelse

    </div>


    {{-- ===================================================== --}}
    {{-- PAGINATION --}}
    {{-- ===================================================== --}}

    @if($pengerjaans->hasPages())

        <div class="card-footer">

            {{ $pengerjaans->links() }}

        </div>

    @endif

</div>
{{-- ========================================================= --}}
{{-- MODAL KONFIRMASI BUKA BLOKIR --}}
{{-- ========================================================= --}}

<div
    class="modal modal-blur fade"
    id="modalBukaBlokir"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-body text-center py-4">

                <div class="mb-3">

                    <span class="avatar avatar-xl bg-warning-lt">

                        <i class="ti ti-lock-open fs-1"></i>

                    </span>

                </div>

                <h3>
                    Buka Blokir Peserta?
                </h3>

                <div class="text-secondary">

                    Blokir untuk

                    <strong id="namaPesertaBlokir">
                        peserta
                    </strong>

                    akan dibuka.

                    <div class="mt-2">

                        Peserta dapat kembali melanjutkan ujian selama waktu pengerjaan masih tersedia.

                    </div>

                </div>

            </div>


            <div class="modal-footer">

                <div class="w-100">

                    <div class="row g-2">

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
                                id="formBukaBlokir"
                                method="POST"
                            >

                                @csrf

                                @method('PATCH')


                                <button
                                    type="submit"
                                    class="btn btn-warning w-100"
                                >

                                    <i class="ti ti-lock-open me-1"></i>

                                    Buka Blokir

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

    const modal =
        document.getElementById('modalBukaBlokir');

    const form =
        document.getElementById('formBukaBlokir');

    const namaPeserta =
        document.getElementById('namaPesertaBlokir');


    if (!modal || !form || !namaPeserta) {
        return;
    }


    modal.addEventListener(
        'show.bs.modal',
        function (event) {

            const button =
                event.relatedTarget;


            if (!button) {
                return;
            }


            const action =
                button.getAttribute('data-action');

            const nama =
                button.getAttribute('data-nama');


            form.setAttribute(
                'action',
                action
            );


            namaPeserta.textContent =
                nama || 'Peserta';

        }
    );

});
</script>

@endpush

@endsection