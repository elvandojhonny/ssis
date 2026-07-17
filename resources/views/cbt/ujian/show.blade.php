@extends('layouts.app')

@section('title', 'Detail Ujian')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Modul CBT
            </div>

            <h2 class="page-title">
                {{ $ujian->judul }}
            </h2>

            <div class="text-secondary mt-1">
                Detail dan pengaturan publikasi ujian.
            </div>

        </div>


        <div class="col-12 col-md-auto">

            <a
                href="{{ route('cbt.ujian.index') }}"
                class="btn btn-outline-secondary w-100"
            >
                <i class="ti ti-arrow-left me-1"></i>
                Kembali
            </a>

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


<div class="row row-cards">


    {{-- ===================================================== --}}
    {{-- INFORMASI UJIAN --}}
    {{-- ===================================================== --}}

    <div class="col-lg-8">

        <div class="card mb-4">

            <div class="card-header">

                <h3 class="card-title">
                    Informasi Ujian
                </h3>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    {{-- JUDUL UJIAN --}}
                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Judul Ujian
                        </div>

                        <div class="fw-bold mt-1">
                            {{ $ujian->judul }}
                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Status
                        </div>

                        <div class="mt-1">

                            @if($ujian->status === 'draft')

                                <span class="badge bg-secondary-lt">
                                    Draft
                                </span>

                            @elseif($ujian->status === 'dipublikasi')

                                <span class="badge bg-success-lt">
                                    Dipublikasi
                                </span>

                            @else

                                <span class="badge bg-blue-lt">
                                    Selesai
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- MATA PELAJARAN --}}
                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Mata Pelajaran
                        </div>

                        <div class="fw-bold mt-1">

                            {{
                                $ujian
                                    ->bankSoal
                                    ->mata_pelajaran
                            }}

                        </div>

                    </div>


                    {{-- KELAS --}}
                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Kelas
                        </div>

                        <div class="fw-bold mt-1">

                            {{ $ujian->kelas->nama }}

                        </div>

                        <div class="text-secondary small">

                            {{
                                $ujian
                                    ->kelas
                                    ->tahunAjaran
                                    ->nama
                            }}

                        </div>

                    </div>


                    {{-- WAKTU MULAI --}}
                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Waktu Mulai
                        </div>

                        <div class="fw-bold mt-1">

                            {{
                                $ujian
                                    ->waktu_mulai
                                    ->format('d/m/Y H:i')
                            }}

                        </div>

                    </div>


                    {{-- WAKTU SELESAI --}}
                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Waktu Selesai
                        </div>

                        <div class="fw-bold mt-1">

                            {{
                                $ujian
                                    ->waktu_selesai
                                    ->format('d/m/Y H:i')
                            }}

                        </div>

                    </div>


                    {{-- DURASI --}}
                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Durasi Pengerjaan
                        </div>

                        <div class="fw-bold mt-1">

                            {{ $ujian->durasi_menit }}

                            menit

                        </div>

                    </div>


                    {{-- JUMLAH SOAL --}}
                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Jumlah Soal
                        </div>

                        <div class="fw-bold mt-1">

                            {{
                                $ujian
                                    ->bankSoal
                                    ->soals
                                    ->count()
                            }}

                            soal

                        </div>

                    </div>

                </div>


                {{-- DESKRIPSI --}}
                @if($ujian->deskripsi)

                    <hr class="my-4">

                    <div>

                        <div class="text-secondary small mb-2">
                            Deskripsi
                        </div>

                        <div>
                            {{ $ujian->deskripsi }}
                        </div>

                    </div>

                @endif

            </div>

        </div>



        {{-- ================================================= --}}
        {{-- BANK SOAL --}}
        {{-- ================================================= --}}

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Bank Soal
                </h3>

            </div>


            <div class="card-body">

                <div class="d-flex align-items-start gap-3">

                    <span class="avatar avatar-lg bg-blue-lt">

                        <i class="ti ti-files"></i>

                    </span>


                    <div class="flex-fill">

                        <h3 class="mb-1">

                            {{ $ujian->bankSoal->judul }}

                        </h3>


                        <div class="text-secondary">

                            {{
                                $ujian
                                    ->bankSoal
                                    ->mata_pelajaran
                            }}

                            ·

                            {{
                                $ujian
                                    ->bankSoal
                                    ->soals
                                    ->count()
                            }}

                            soal

                        </div>


                        <div class="text-secondary mt-2">

                            Diunggah oleh:

                            {{
                                $ujian
                                    ->bankSoal
                                    ->guru
                                    ->nama
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- PUBLIKASI UJIAN --}}
    {{-- ===================================================== --}}

    <div class="col-lg-4">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Publikasi Ujian
                </h3>

            </div>


            <div class="card-body">


                {{-- ========================================= --}}
                {{-- STATUS DRAFT --}}
                {{-- ========================================= --}}

                @if($ujian->status === 'draft')


                    <div class="text-center py-3">

                        <span
                            class="
                                avatar
                                avatar-xl
                                bg-yellow-lt
                                mb-3
                            "
                        >
                            <i class="ti ti-lock"></i>
                        </span>


                        <h3>
                            Belum Dipublikasikan
                        </h3>


                        <p class="text-secondary mb-0">

                            Ujian masih berupa draft dan
                            belum dapat dilihat oleh siswa.

                        </p>

                    </div>


                    <div class="alert alert-warning mt-3">

                        <div class="d-flex">

                            <div class="me-2">

                                <i class="ti ti-alert-triangle"></i>

                            </div>


                            <div>

                                <div class="fw-bold mb-1">
                                    Periksa sebelum publikasi
                                </div>

                                <div>

                                    Setelah dipublikasikan,
                                    ujian akan tersedia untuk
                                    siswa pada kelas yang telah
                                    dipilih.

                                </div>

                            </div>

                        </div>

                    </div>


                    <form
                        action="{{
                            route(
                                'cbt.ujian.publish',
                                $ujian
                            )
                        }}"
                        method="POST"
                    >

                        @csrf

                        @method('PATCH')


                        <button
                            type="submit"
                            class="btn btn-success w-100"
                            onclick="
                                return confirm(
                                    'Publikasikan ujian ini?'
                                )
                            "
                        >

                            <i class="ti ti-send me-1"></i>

                            Publikasikan Ujian

                        </button>

                    </form>



                {{-- ========================================= --}}
                {{-- STATUS DIPUBLIKASI --}}
                {{-- ========================================= --}}

                @elseif($ujian->status === 'dipublikasi')


                    <div class="text-center py-3">

                        <span
                            class="
                                avatar
                                avatar-xl
                                bg-success-lt
                                mb-3
                            "
                        >

                            <i class="ti ti-circle-check"></i>

                        </span>


                        <h3>
                            Ujian Dipublikasikan
                        </h3>


                        <p class="text-secondary mb-0">

                            Ujian sudah tersedia untuk siswa
                            pada kelas yang ditentukan.

                        </p>

                    </div>



                    {{-- TOKEN UJIAN --}}

                    <div class="border rounded p-4 text-center mt-3">

                        <div class="mb-3">

                            <span class="avatar bg-blue-lt">

                                <i class="ti ti-key"></i>

                            </span>

                        </div>


                        <div class="text-secondary mb-2">

                            Token Ujian

                        </div>


                        <div
                            class="
                                fw-bold
                                font-monospace
                                text-primary
                            "
                            style="
                                font-size: 2rem;
                                letter-spacing: .25rem;
                                word-break: break-all;
                            "
                        >

                            {{ $ujian->token ?? '-' }}

                        </div>


                        <div class="text-secondary small mt-3">

                            Bagikan token ini kepada siswa
                            ketika ujian akan dimulai.

                        </div>

                    </div>



                    {{-- INFORMASI AKSES --}}

                    <div class="alert alert-info mt-3 mb-0">

                        <div class="d-flex">

                            <div class="me-2">

                                <i class="ti ti-info-circle"></i>

                            </div>


                            <div>

                                Siswa hanya dapat masuk ke ujian
                                menggunakan token ini selama
                                jadwal ujian masih berlangsung.

                            </div>

                        </div>

                    </div>



                {{-- ========================================= --}}
                {{-- STATUS SELESAI --}}
                {{-- ========================================= --}}

                @else


                    <div class="text-center py-4">

                        <span
                            class="
                                avatar
                                avatar-xl
                                bg-blue-lt
                                mb-3
                            "
                        >

                            <i class="ti ti-flag"></i>

                        </span>


                        <h3>
                            Ujian Selesai
                        </h3>


                        <p class="text-secondary mb-0">

                            Periode pelaksanaan ujian ini
                            telah selesai.

                        </p>

                    </div>


                    {{-- TOKEN TETAP DITAMPILKAN SEBAGAI ARSIP --}}

                    @if($ujian->token)

                        <div
                            class="
                                border
                                rounded
                                p-3
                                text-center
                                mt-3
                            "
                        >

                            <div class="text-secondary small mb-1">

                                Token Ujian

                            </div>


                            <div
                                class="
                                    fw-bold
                                    font-monospace
                                "
                                style="
                                    font-size: 1.25rem;
                                    letter-spacing: .15rem;
                                "
                            >

                                {{ $ujian->token }}

                            </div>

                        </div>

                    @endif


                @endif

            </div>

        </div>

    </div>

</div>

@endsection