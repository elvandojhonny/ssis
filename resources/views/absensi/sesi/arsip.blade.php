@extends('layouts.app')

@section('title', 'Arsip Sesi Absensi')

@section('content')

{{-- ========================================================= --}}
{{-- PAGE HEADER --}}
{{-- ========================================================= --}}

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Modul Absensi
            </div>

            <h2 class="page-title">
                Arsip Sesi Absensi
            </h2>

            <div class="text-secondary mt-1">
                Riwayat sesi absensi yang telah tersimpan lebih dari 7 hari.
            </div>

        </div>


        <div class="col-12 col-md-auto">

            <a
                href="{{ route('absensi.sesi.index') }}"
                class="btn btn-outline-secondary w-100"
            >
                <i class="ti ti-arrow-left me-1"></i>

                Kembali ke Sesi
            </a>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- FILTER --}}
{{-- ========================================================= --}}

<div class="card mb-4">

    <div class="card-header">

        <h3 class="card-title">
            Cari Arsip
        </h3>

    </div>


    <div class="card-body">

        <form
            method="GET"
            action="{{ route('absensi.sesi.arsip') }}"
        >

            <div class="row g-3">


                {{-- Cari Kelas --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Cari Kelas
                    </label>

                    <div class="input-icon">

                        <span class="input-icon-addon">
                            <i class="ti ti-search"></i>
                        </span>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Contoh: X IPA"
                        >

                    </div>

                </div>


                {{-- Jenis --}}
                <div class="col-md-2">

                    <label class="form-label">
                        Jenis
                    </label>

                    <select
                        name="jenis"
                        class="form-select"
                    >

                        <option value="">
                            Semua
                        </option>

                        <option
                            value="pagi"
                            @selected(request('jenis') === 'pagi')
                        >
                            Pagi
                        </option>

                        <option
                            value="siang"
                            @selected(request('jenis') === 'siang')
                        >
                            Siang
                        </option>

                    </select>

                </div>


                {{-- Bulan --}}
                <div class="col-md-2">

                    <label class="form-label">
                        Bulan
                    </label>

                    <select
                        name="bulan"
                        class="form-select"
                    >

                        <option value="">
                            Semua
                        </option>

                        @php
                            $namaBulan = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ];
                        @endphp

                        @foreach($namaBulan as $nomor => $nama)

                            <option
                                value="{{ $nomor }}"
                                @selected(
                                    (string) request('bulan')
                                    === (string) $nomor
                                )
                            >
                                {{ $nama }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Tahun --}}
                <div class="col-md-2">

                    <label class="form-label">
                        Tahun
                    </label>

                    <select
                        name="tahun"
                        class="form-select"
                    >

                        <option value="">
                            Semua
                        </option>

                        @foreach($daftarTahun as $tahun)

                            <option
                                value="{{ $tahun }}"
                                @selected(
                                    (string) request('tahun')
                                    === (string) $tahun
                                )
                            >
                                {{ $tahun }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Tombol --}}
                <div class="col-md-2">

                    <label class="form-label">
                        &nbsp;
                    </label>

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        <i class="ti ti-filter me-1"></i>

                        Filter
                    </button>

                </div>

            </div>


            @if(
                request()->filled('search')
                || request()->filled('jenis')
                || request()->filled('bulan')
                || request()->filled('tahun')
            )

                <div class="mt-3">

                    <a
                        href="{{ route('absensi.sesi.arsip') }}"
                        class="btn btn-outline-secondary btn-sm"
                    >
                        <i class="ti ti-x me-1"></i>

                        Reset Filter
                    </a>

                </div>

            @endif

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- TABEL ARSIP --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-header">

        <div class="row align-items-center w-100 g-2">

            <div class="col">

                <h3 class="card-title mb-1">
                    Data Arsip
                </h3>

                <div class="text-secondary small">

                    Menampilkan

                    <strong>
                        {{ $riwayatSesi->total() }}
                    </strong>

                    sesi absensi.

                </div>

            </div>


            <div class="col-auto">

                <span class="badge bg-secondary-lt">

                    <i class="ti ti-archive me-1"></i>

                    Arsip

                </span>

            </div>

        </div>

    </div>


    <div class="table-responsive ssis-mobile-table">

        <table class="table table-vcenter card-table">

            <thead>

                <tr>

                    <th>
                        Tanggal
                    </th>

                    <th>
                        Kelas
                    </th>

                    <th>
                        Jenis
                    </th>

                    <th>
                        Waktu
                    </th>

                    <th>
                        Jumlah Absensi
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

            @forelse($riwayatSesi as $sesi)

                <tr>


                    {{-- Tanggal --}}
                    <td data-label="Tanggal">

                        <span>

                            <i
                                class="
                                    ti
                                    ti-calendar
                                    me-1
                                    text-secondary
                                "
                            ></i>

                            {{
                                $sesi
                                    ->tanggal
                                    ->format('d/m/Y')
                            }}

                        </span>

                    </td>


                    {{-- Kelas --}}
                    <td data-label="Kelas">

                        <div class="text-end text-md-start">

                            <div class="fw-bold">

                                {{
                                    $sesi
                                        ->kelas
                                        ?->nama
                                    ?? '-'
                                }}

                            </div>

                            <div class="text-secondary small">

                                {{
                                    $sesi
                                        ->kelas
                                        ?->tahunAjaran
                                        ?->nama
                                    ?? '-'
                                }}

                            </div>

                        </div>

                    </td>


                    {{-- Jenis --}}
                    <td data-label="Jenis">

                        @if($sesi->jenis === 'pagi')

                            <span class="badge bg-yellow-lt">

                                <i class="ti ti-sun me-1"></i>

                                Pagi

                            </span>

                        @else

                            <span class="badge bg-blue-lt">

                                <i class="ti ti-sunset me-1"></i>

                                Siang

                            </span>

                        @endif

                    </td>


                    {{-- Waktu --}}
                    <td data-label="Waktu">

                        <span>

                            <i
                                class="
                                    ti
                                    ti-clock
                                    me-1
                                    text-secondary
                                "
                            ></i>

                            {{ $sesi->waktu_mulai }}

                            -

                            {{ $sesi->waktu_selesai }}

                        </span>

                    </td>


                    {{-- Jumlah --}}
                    <td data-label="Jumlah Absensi">

                        <span>

                            <i
                                class="
                                    ti
                                    ti-users
                                    me-1
                                    text-secondary
                                "
                            ></i>

                            {{ $sesi->absensis_count }}

                            siswa

                        </span>

                    </td>


                    {{-- Status --}}
                    <td data-label="Status">

                        @if($sesi->status === 'aktif')

                            <span class="badge bg-success-lt">

                                <i class="ti ti-circle-check me-1"></i>

                                Aktif

                            </span>

                        @else

                            <span class="badge bg-secondary-lt">

                                <i class="ti ti-circle-check me-1"></i>

                                Selesai

                            </span>

                        @endif

                    </td>


                    {{-- Aksi --}}
                    <td data-label="Aksi">

                        <div
                            class="
                                d-flex
                                justify-content-end
                                ssis-table-actions
                            "
                        >

                            <a
                                href="{{
                                    route(
                                        'absensi.sesi.show',
                                        $sesi
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

                        </div>

                    </td>

                </tr>


            @empty

                <tr class="ssis-empty-row">

                    <td
                        colspan="7"
                        class="
                            text-center
                            text-secondary
                            py-5
                        "
                    >

                        <i
                            class="ti ti-archive"
                            style="font-size: 40px;"
                        ></i>

                        <div class="mt-2">
                            Belum ada data arsip yang ditemukan.
                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    {{-- ===================================================== --}}
    {{-- PAGINATION --}}
    {{-- ===================================================== --}}

    @if($riwayatSesi->hasPages())

        <div class="card-footer">

            {{ $riwayatSesi->links() }}

        </div>

    @endif

</div>

@endsection