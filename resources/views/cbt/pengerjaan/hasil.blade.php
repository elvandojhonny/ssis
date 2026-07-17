@extends('layouts.app')

@section('title', 'Hasil Ujian')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Modul CBT
            </div>

            <h2 class="page-title">
                Hasil Ujian
            </h2>

            <div class="text-secondary mt-1">
                Ringkasan hasil pengerjaan ujian Anda.
            </div>

        </div>

    </div>

</div>


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


@if(session('info'))

    <div class="alert alert-info">

        <div class="d-flex align-items-center">

            <i class="ti ti-info-circle me-2"></i>

            <div>
                {{ session('info') }}
            </div>

        </div>

    </div>

@endif


{{-- INFORMASI UJIAN --}}

<div class="card mb-4">

    <div class="card-body">

        <div class="row align-items-center g-3">

            <div class="col-auto">

                <span class="avatar avatar-xl bg-blue-lt">

                    <i class="ti ti-file-check"></i>

                </span>

            </div>


            <div class="col">

                <h2 class="mb-1">

                    {{ $pengerjaan->ujian->judul }}

                </h2>


                <div class="text-secondary">

                    {{ $pengerjaan->ujian->kelas->nama }}

                    <span class="mx-2">
                        •
                    </span>

                    {{ $jumlahSoal }} soal

                </div>

            </div>


            <div class="col-12 col-md-auto">

                <span
                    class="badge bg-success-lt"
                    style="font-size: 14px;"
                >

                    <i class="ti ti-circle-check me-1"></i>

                    Ujian Selesai

                </span>

            </div>

        </div>

    </div>

</div>


<div class="row row-cards mb-4">


    {{-- NILAI --}}

    <div class="col-12 col-md-6 col-lg-4">

        <div class="card h-100">

            <div class="card-body text-center py-4">

                <div class="text-secondary mb-2">

                    Nilai Akhir

                </div>


                <div
                    class="fw-bold text-primary"
                    style="font-size: 48px;"
                >

                    {{
                        number_format(
                            (float) $pengerjaan->nilai,
                            2
                        )
                    }}

                </div>


                <div class="text-secondary small">

                    Total skor yang diperoleh

                </div>

            </div>

        </div>

    </div>


    {{-- JAWABAN BENAR --}}

    <div class="col-6 col-md-3 col-lg-2">

        <div class="card h-100">

            <div class="card-body text-center">

                <span class="avatar bg-success-lt mb-3">

                    <i class="ti ti-check"></i>

                </span>


                <div class="h1 mb-1">

                    {{ $jumlahBenar }}

                </div>


                <div class="text-secondary">

                    Benar

                </div>

            </div>

        </div>

    </div>


    {{-- JAWABAN SALAH --}}

    <div class="col-6 col-md-3 col-lg-2">

        <div class="card h-100">

            <div class="card-body text-center">

                <span class="avatar bg-danger-lt mb-3">

                    <i class="ti ti-x"></i>

                </span>


                <div class="h1 mb-1">

                    {{ $jumlahSalah }}

                </div>


                <div class="text-secondary">

                    Salah

                </div>

            </div>

        </div>

    </div>


    {{-- TIDAK DIJAWAB --}}

    <div class="col-6 col-md-3 col-lg-2">

        <div class="card h-100">

            <div class="card-body text-center">

                <span class="avatar bg-yellow-lt mb-3">

                    <i class="ti ti-minus"></i>

                </span>


                <div class="h1 mb-1">

                    {{ $tidakDijawab }}

                </div>


                <div class="text-secondary">

                    Kosong

                </div>

            </div>

        </div>

    </div>


    {{-- TOTAL SOAL --}}

    <div class="col-6 col-md-3 col-lg-2">

        <div class="card h-100">

            <div class="card-body text-center">

                <span class="avatar bg-blue-lt mb-3">

                    <i class="ti ti-list-numbers"></i>

                </span>


                <div class="h1 mb-1">

                    {{ $jumlahSoal }}

                </div>


                <div class="text-secondary">

                    Soal

                </div>

            </div>

        </div>

    </div>

</div>


{{-- DETAIL PENGERJAAN --}}

<div class="card mb-4">

    <div class="card-header">

        <h3 class="card-title">

            <i class="ti ti-clock me-2"></i>

            Informasi Pengerjaan

        </h3>

    </div>


    <div class="card-body">

        <div class="row g-4">


            <div class="col-12 col-md-4">

                <div class="text-secondary small mb-1">

                    Mulai Mengerjakan

                </div>

                <div class="fw-bold">

                    {{
                        $pengerjaan
                            ->waktu_mulai
                            ?->format(
                                'd/m/Y H:i'
                            )
                        ?? '-'
                    }}

                </div>

            </div>


            <div class="col-12 col-md-4">

                <div class="text-secondary small mb-1">

                    Selesai Mengerjakan

                </div>

                <div class="fw-bold">

                    {{
                        $pengerjaan
                            ->waktu_selesai
                            ?->format(
                                'd/m/Y H:i'
                            )
                        ?? '-'
                    }}

                </div>

            </div>


            <div class="col-12 col-md-4">

                <div class="text-secondary small mb-1">

                    Durasi Pengerjaan

                </div>

                <div class="fw-bold">

                    @if($durasiPengerjaan !== null)

                        {{
                            (int)
                            $durasiPengerjaan
                        }}
                        menit

                    @else

                        -

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


{{-- AKSI --}}

<div class="d-flex flex-column flex-sm-row gap-2">

    <a
        href="{{ route('cbt.siswa.index') }}"
        class="btn btn-primary"
    >

        <i class="ti ti-list me-2"></i>

        Kembali ke Daftar Ujian

    </a>


    <a
        href="{{ route('dashboard') }}"
        class="btn btn-outline-secondary"
    >

        <i class="ti ti-layout-dashboard me-2"></i>

        Dashboard

    </a>

</div>

@endsection