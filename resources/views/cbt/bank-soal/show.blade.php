@extends('layouts.app')

@section('title', 'Detail Bank Soal')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Modul CBT
            </div>

            <h2 class="page-title">
                {{ $bankSoal->judul }}
            </h2>

            <div class="text-secondary mt-1">
                {{ $bankSoal->mata_pelajaran }}
                •
                Kelas {{ $bankSoal->tingkat }}
            </div>

        </div>


        <div class="col-12 col-md-auto">

            <a
                href="{{ route('cbt.bank-soal.index') }}"
                class="btn btn-outline-secondary w-100"
            >
                <i class="ti ti-arrow-left me-1"></i>
                Kembali
            </a>

        </div>

    </div>

</div>


{{-- INFORMASI BANK SOAL --}}

<div class="card mb-4">

    <div class="card-body">

        <div class="row g-4">

            <div class="col-6 col-md-3">

                <div class="text-secondary small">
                    Mata Pelajaran
                </div>

                <div class="fw-bold mt-1">
                    {{ $bankSoal->mata_pelajaran }}
                </div>

            </div>


            <div class="col-6 col-md-3">

                <div class="text-secondary small">
                    Tingkat
                </div>

                <div class="fw-bold mt-1">
                    Kelas {{ $bankSoal->tingkat }}
                </div>

            </div>


            <div class="col-6 col-md-3">

                <div class="text-secondary small">
                    Jumlah Soal
                </div>

                <div class="fw-bold mt-1">
                    {{ $bankSoal->soals->count() }} soal
                </div>

            </div>


            <div class="col-6 col-md-3">

                <div class="text-secondary small">
                    Total Bobot
                </div>

                <div class="fw-bold mt-1">
                    {{ $bankSoal->soals->sum('bobot') }}
                </div>

            </div>

        </div>


        @if($bankSoal->deskripsi)

            <hr>

            <div class="text-secondary small">
                Deskripsi
            </div>

            <div class="mt-1">
                {{ $bankSoal->deskripsi }}
            </div>

        @endif

    </div>

</div>


{{-- DAFTAR SOAL --}}

<div class="mb-3">

    <h3 class="page-title">
        Daftar Soal
    </h3>

    <div class="text-secondary mt-1">
        Kunci jawaban ditampilkan khusus untuk guru.
    </div>

</div>


<div class="row g-3">

    @forelse($bankSoal->soals as $soal)

        <div class="col-12">

            <div class="card">

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

                        <span class="badge bg-blue-lt">
                            Soal {{ $soal->nomor }}
                        </span>

                        <span class="badge bg-yellow-lt">
                            {{ $soal->bobot }} Poin
                        </span>

                    </div>


                    <div class="fw-medium mb-4">
                        {{ $soal->pertanyaan }}
                    </div>


                    <div class="row g-2">

                        @foreach([
                            'A' => $soal->pilihan_a,
                            'B' => $soal->pilihan_b,
                            'C' => $soal->pilihan_c,
                            'D' => $soal->pilihan_d,
                            'E' => $soal->pilihan_e,
                        ] as $huruf => $jawaban)

                            @if($jawaban)

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
                                                        $soal->jawaban_benar
                                                        === $huruf
                                                            ? 'bg-success text-white'
                                                            : 'bg-secondary-lt'
                                                    }}
                                                "
                                            >
                                                {{ $huruf }}
                                            </span>


                                            <div class="flex-fill">

                                                {{ $jawaban }}


                                                @if(
                                                    $soal->jawaban_benar
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

        </div>

    @empty

        <div class="col-12">

            <div class="card">

                <div
                    class="
                        card-body
                        text-center
                        text-secondary
                        py-5
                    "
                >
                    Bank soal ini belum memiliki soal.
                </div>

            </div>

        </div>

    @endforelse

</div>

@endsection