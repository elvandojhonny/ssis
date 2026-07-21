@extends('layouts.app')

@section('title', 'Detail Hasil Peserta')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Detail Hasil Ujian
            </div>

            <h2 class="page-title">
                {{ $pengerjaan->siswa->nama }}
            </h2>

            <div class="text-secondary mt-1">

                {{ $ujian->judul }}

                <span class="mx-2">•</span>

                {{ $ujian->kelas->nama }}

            </div>

        </div>


        <div class="col-12 col-md-auto">

            <a
                href="{{ route('cbt.rekap.show', $ujian) }}"
                class="btn btn-outline-secondary w-100"
            >
                <i class="ti ti-arrow-left me-2"></i>

                Kembali ke Rekap
            </a>

        </div>

    </div>

</div>


{{-- INFORMASI PESERTA --}}

<div class="card mb-4">

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">

                <div class="text-secondary small">
                    Nama Peserta
                </div>

                <div class="fw-bold">
                    {{ $pengerjaan->siswa->nama }}
                </div>

            </div>


            <div class="col-md-4">

                <div class="text-secondary small">
                    NISN
                </div>

                <div class="fw-bold">
                    {{ $pengerjaan->siswa->nisn ?? '-' }}
                </div>

            </div>


            <div class="col-md-4">

                <div class="text-secondary small">
                    Nilai Akhir
                </div>

                <div class="h2 text-primary mb-0">

                    {{
                        number_format(
                            (float) $pengerjaan->nilai,
                            2
                        )
                    }}

                </div>

            </div>

        </div>

    </div>

</div>


{{-- STATISTIK --}}

<div class="row row-cards mb-4">

    <div class="col-6 col-lg-3">

        <div class="card">

            <div class="card-body">

                <div class="text-secondary">
                    Total Soal
                </div>

                <div class="h1 mb-0">
                    {{ $totalSoal }}
                </div>

            </div>

        </div>

    </div>


    <div class="col-6 col-lg-3">

        <div class="card">

            <div class="card-body">

                <div class="text-secondary">
                    Benar
                </div>

                <div class="h1 text-success mb-0">
                    {{ $jawabanBenar }}
                </div>

            </div>

        </div>

    </div>


    <div class="col-6 col-lg-3">

        <div class="card">

            <div class="card-body">

                <div class="text-secondary">
                    Salah
                </div>

                <div class="h1 text-danger mb-0">
                    {{ $jawabanSalah }}
                </div>

            </div>

        </div>

    </div>


    <div class="col-6 col-lg-3">

        <div class="card">

            <div class="card-body">

                <div class="text-secondary">
                    Tidak Dijawab
                </div>

                <div class="h1 mb-0">
                    {{ $tidakDijawab }}
                </div>

            </div>

        </div>

    </div>

</div>


{{-- DETAIL JAWABAN --}}

<div class="mb-3">

    <h3 class="page-title">
        Detail Jawaban
    </h3>

    <div class="text-secondary mt-1">
        Jawaban peserta dan hasil penilaian setiap soal.
    </div>

</div>


<div class="row row-cards">

    @foreach(
        $pengerjaan
            ->ujian
            ->bankSoal
            ->soals
        as $soal
    )

        @php

            $jawaban =
                $jawabanPerSoal
                    ->get($soal->id);

            $pilihanSiswa =
                $jawaban
                    ?->jawaban;

            $benar =
                $jawaban
                    ?->is_benar;

        @endphp


        <div class="col-12">

            <div class="card">

                @if(! $jawaban)

                    <div class="card-status-top bg-secondary"></div>

                @elseif($benar)

                    <div class="card-status-top bg-success"></div>

                @else

                    <div class="card-status-top bg-danger"></div>

                @endif


                <div class="card-body">

                    <div
                        class="
                            d-flex
                            justify-content-between
                            align-items-start
                            gap-3
                            mb-3
                        "
                    >

                        <div class="fw-bold">

                            Soal {{ $soal->nomor }}

                        </div>


                        @if(! $jawaban)

                            <span class="badge bg-secondary-lt">
                                Tidak Dijawab
                            </span>

                        @elseif($benar)

                            <span class="badge bg-success-lt">

                                <i class="ti ti-check me-1"></i>

                                Benar

                            </span>

                        @else

                            <span class="badge bg-danger-lt">

                                <i class="ti ti-x me-1"></i>

                                Salah

                            </span>

                        @endif

                    </div>


                    <div class="mb-4">

                        {{ $soal->pertanyaan }}

                    </div>


                    <div class="row g-3">

                        <div class="col-md-4">

                            <div
                                class="
                                    border
                                    rounded
                                    p-3
                                    h-100
                                "
                            >

                                <div class="text-secondary small mb-1">
                                    Jawaban Peserta
                                </div>

                                <div class="fw-bold">

                                    {{
                                        $pilihanSiswa
                                        ?? 'Tidak dijawab'
                                    }}

                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div
                                class="
                                    border
                                    rounded
                                    p-3
                                    h-100
                                "
                            >

                                <div class="text-secondary small mb-1">
                                    Kunci Jawaban
                                </div>

                                <div class="fw-bold text-success">

                                    {{ $soal->jawaban_benar }}

                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div
                                class="
                                    border
                                    rounded
                                    p-3
                                    h-100
                                "
                            >

                                <div class="text-secondary small mb-1">
                                    Skor Soal
                                </div>

                                <div class="fw-bold">

                                    @if($benar)

                                        {{
                                            number_format(
                                                (float) $soal->bobot,
                                                2
                                            )
                                        }}

                                    @else

                                        0

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

</div>

@endsection