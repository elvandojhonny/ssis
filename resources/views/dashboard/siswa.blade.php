@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">
                Smart School Information System
            </div>

            <h2 class="page-title">
                Dashboard Siswa
            </h2>

            <div class="text-secondary mt-1">
                Selamat datang, {{ auth()->user()->name }}.
            </div>
        </div>
    </div>
</div>

{{-- Informasi Siswa --}}
<div class="card mb-4">
    <div class="card-body">

        <div class="row align-items-center">

            <div class="col-auto">
                <span class="avatar avatar-lg">
                    <i class="ti ti-user"></i>
                </span>
            </div>

            <div class="col">
                <h3 class="mb-1">
                    {{ auth()->user()->name }}
                </h3>

                <div class="text-secondary">

                    NIS:
                    {{ $user->siswa?->nis ?? '-' }}

                    <span class="mx-2">•</span>

                    Kelas:
                    {{ $user->siswa?->kelas?->nama ?? '-' }}

                    <span class="mx-2">•</span>

                    Tahun Ajaran:
                    {{
                        $user
                            ->siswa
                            ?->kelas
                            ?->tahunAjaran
                            ?->nama
                        ?? '-'
                    }}

                </div>
            </div>

        </div>

    </div>
</div>


{{-- Fitur Utama --}}
<div class="mb-3">
    <h3 class="page-title">
        Layanan Siswa
    </h3>

    <div class="text-secondary mt-1">
        Pilih layanan yang ingin digunakan.
    </div>
</div>


<div class="row row-cards">

    {{-- ABSENSI --}}
<div class="col-md-6 col-lg-4">

    <div class="card h-100">

        <div class="card-body">

            <div class="mb-4">

                <span class="avatar avatar-xl bg-green-lt">

                    <i class="ti ti-calendar-check"></i>

                </span>

            </div>

            <h2 class="card-title">
                Kehadiran Saya
            </h2>

            <p class="text-secondary">

                Lihat ringkasan dan riwayat kehadiran
                pagi dan siang selama semester berjalan.

            </p>

        </div>


        <div class="card-footer">

            <a
                href="{{ route('absensi.siswa.index') }}"
                class="btn btn-primary w-100"
            >

                <i class="ti ti-report-analytics me-2"></i>

                Lihat Rekap Kehadiran

            </a>

        </div>

    </div>

</div>


    {{-- CBT --}}
    <div class="col-md-6 col-lg-4">

        <div class="card h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between mb-4">

                    <span class="avatar avatar-xl bg-blue-lt">

                        <i class="ti ti-file-pencil"></i>

                    </span>


                    <span class="badge bg-secondary-lt">

                        Segera Hadir

                    </span>

                </div>


                <h2 class="card-title">

                    Ujian Online

                </h2>


                <p class="text-secondary">

                    Akses dan kerjakan ujian online sesuai
                    dengan mata pelajaran dan kelas.

                </p>

            </div>


            <div class="card-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary w-100"
                    disabled
                >

                    <i class="ti ti-file-pencil me-2"></i>

                    Lihat Daftar Ujian

                </button>

            </div>

        </div>

    </div>


    {{-- PERPUSTAKAAN --}}
    <div class="col-md-6 col-lg-4">

        <div class="card h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between mb-4">

                    <span class="avatar avatar-xl bg-orange-lt">

                        <i class="ti ti-books"></i>

                    </span>


                    <span class="badge bg-secondary-lt">

                        Segera Hadir

                    </span>

                </div>


                <h2 class="card-title">

                    Perpustakaan

                </h2>


                <p class="text-secondary">

                    Lihat koleksi buku, informasi peminjaman,
                    dan riwayat peminjaman buku perpustakaan.

                </p>

            </div>


            <div class="card-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary w-100"
                    disabled
                >

                    <i class="ti ti-books me-2"></i>

                    Lihat Perpustakaan

                </button>

            </div>

        </div>

    </div>

</div>

@endsection