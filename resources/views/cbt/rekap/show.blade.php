@extends('layouts.app')

@section('title', 'Detail Rekap Ujian')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Rekap Hasil Ujian
            </div>

            <h2 class="page-title">
                {{ $ujian->judul }}
            </h2>

            <div class="text-secondary mt-1">

                {{ $ujian->kelas->nama }}

                <span class="mx-2">•</span>

                {{ $ujian->bankSoal->mata_pelajaran }}

            </div>

        </div>


        <div class="col-12 col-md-auto">

            <div class="d-flex flex-column flex-sm-row gap-2">

                <a
                    href="{{ route('cbt.rekap.export', $ujian) }}"
                    class="btn btn-success"
                >
                    <i class="ti ti-file-spreadsheet me-2"></i>

                    Export Excel
                </a>


                <a
                    href="{{ route('cbt.rekap.index') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="ti ti-arrow-left me-2"></i>

                    Kembali
                </a>

            </div>

        </div>

    </div>

</div>


{{-- ALERT SUCCESS --}}

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


{{-- ALERT ERROR --}}

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


{{-- STATISTIK --}}

<div class="row row-cards mb-4">

    <div class="col-6 col-lg-3">

        <div class="card">

            <div class="card-body">

                <div class="text-secondary">
                    Total Siswa
                </div>

                <div class="h1 mb-0">
                    {{ $totalSiswa }}
                </div>

            </div>

        </div>

    </div>


    <div class="col-6 col-lg-3">

        <div class="card">

            <div class="card-body">

                <div class="text-secondary">
                    Selesai
                </div>

                <div class="h1 mb-0 text-success">
                    {{ $sudahMengerjakan }}
                </div>

            </div>

        </div>

    </div>


    <div class="col-6 col-lg-3">

        <div class="card">

            <div class="card-body">

                <div class="text-secondary">
                    Belum Selesai
                </div>

                <div class="h1 mb-0">

                    {{
                        $sedangMengerjakan
                        +
                        $diblokir
                        +
                        $belumMengerjakan
                    }}

                </div>

            </div>

        </div>

    </div>


    <div class="col-6 col-lg-3">

        <div class="card">

            <div class="card-body">

                <div class="text-secondary">
                    Rata-rata Nilai
                </div>

                <div class="h1 mb-0 text-primary">

                    {{
                        $rataRata !== null
                            ? number_format(
                                $rataRata,
                                2
                            )
                            : '-'
                    }}

                </div>

            </div>

        </div>

    </div>

</div>


{{-- DATA SISWA --}}

<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Hasil Peserta
        </h3>

    </div>


    <div class="table-responsive">

        <table class="table table-vcenter card-table">

            <thead>

                <tr>

                    <th>Siswa</th>

                    <th>NIS</th>

                    <th>Status</th>

                    <th>Mulai</th>

                    <th>Selesai</th>

                    <th>Nilai</th>

                    <th class="w-1">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($siswas as $siswa)

                    @php

                        $pengerjaan =
                            $pengerjaanPerSiswa
                                ->get($siswa->id);

                    @endphp


                    <tr>

                        {{-- SISWA --}}

                        <td>

                            <div class="fw-bold">
                                {{ $siswa->nama }}
                            </div>

                        </td>


                        {{-- NIS --}}

                        <td>

                            {{ $siswa->nis ?? '-' }}

                        </td>


                        {{-- STATUS --}}

                        <td>

                            @if(! $pengerjaan)

                                <span class="badge bg-secondary-lt">

                                    Belum Mengerjakan

                                </span>


                            @elseif(
                                $pengerjaan->status
                                === 'mengerjakan'
                            )

                                <span class="badge bg-yellow-lt">

                                    <i class="ti ti-pencil me-1"></i>

                                    Mengerjakan

                                </span>


                            @elseif(
                                $pengerjaan->status
                                === 'diblokir'
                            )

                                <div
                                    class="
                                        d-flex
                                        flex-column
                                        align-items-start
                                        gap-1
                                    "
                                >

                                    <span class="badge bg-danger-lt">

                                        <i class="ti ti-lock me-1"></i>

                                        Diblokir

                                    </span>


                                    <span class="text-secondary small">

                                        {{
                                            $pengerjaan
                                                ->jumlah_pelanggaran
                                        }}

                                        pelanggaran

                                    </span>

                                </div>


                            @elseif(
                                $pengerjaan->status
                                === 'selesai'
                            )

                                <span class="badge bg-success-lt">

                                    <i class="ti ti-circle-check me-1"></i>

                                    Selesai

                                </span>


                            @else

                                <span class="badge bg-secondary-lt">

                                    {{
                                        ucfirst(
                                            $pengerjaan->status
                                        )
                                    }}

                                </span>

                            @endif

                        </td>


                        {{-- WAKTU MULAI --}}

                        <td>

                            {{
                                $pengerjaan
                                    ?->waktu_mulai
                                    ?->format(
                                        'd/m/Y H:i'
                                    )
                                ?? '-'
                            }}

                        </td>


                        {{-- WAKTU SELESAI --}}

                        <td>

                            {{
                                $pengerjaan
                                    ?->waktu_selesai
                                    ?->format(
                                        'd/m/Y H:i'
                                    )
                                ?? '-'
                            }}

                        </td>


                        {{-- NILAI --}}

                        <td>

                            @if(
                                $pengerjaan &&
                                $pengerjaan->status
                                === 'selesai'
                            )

                                <span
                                    class="
                                        fw-bold
                                        text-primary
                                    "
                                >

                                    {{
                                        number_format(
                                            (float)
                                            $pengerjaan->nilai,
                                            2
                                        )
                                    }}

                                </span>

                            @else

                                <span class="text-secondary">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- AKSI --}}

                        <td>

                            {{-- PESERTA DIBLOKIR --}}

                            @if(
                                $pengerjaan &&
                                $pengerjaan->status
                                === 'diblokir'
                            )

                                <form
                                    action="{{
                                        route(
                                            'cbt.rekap.buka-blokir',
                                            [
                                                'ujian' =>
                                                    $ujian,

                                                'pengerjaan' =>
                                                    $pengerjaan,
                                            ]
                                        )
                                    }}"
                                    method="POST"
                                    onsubmit="
                                        return confirm(
                                            'Buka blokir peserta ini? Jumlah pelanggaran akan direset dan peserta dapat melanjutkan ujian selama waktu masih tersedia.'
                                        );
                                    "
                                >

                                    @csrf

                                    @method('PATCH')


                                    <button
                                        type="submit"
                                        class="
                                            btn
                                            btn-sm
                                            btn-danger
                                        "
                                    >

                                        <i
                                            class="
                                                ti
                                                ti-lock-open
                                                me-1
                                            "
                                        ></i>

                                        Buka Blokir

                                    </button>

                                </form>


                            {{-- PESERTA SELESAI --}}

                            @elseif(
                                $pengerjaan &&
                                $pengerjaan->status
                                === 'selesai'
                            )

                                <a
                                    href="{{
                                        route(
                                            'cbt.rekap.peserta',
                                            [
                                                'ujian' =>
                                                    $ujian,

                                                'pengerjaan' =>
                                                    $pengerjaan,
                                            ]
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


                            {{-- BELUM / SEDANG MENGERJAKAN --}}

                            @else

                                <span class="text-secondary">
                                    -
                                </span>

                            @endif

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

                            Tidak ada siswa pada kelas ini.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection