@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')

{{-- ========================================================= --}}
{{-- PAGE HEADER --}}
{{-- ========================================================= --}}

<div class="page-header mb-4">

    <div>

        <div class="page-pretitle">
            Smart School Information System
        </div>

        <h2 class="page-title">
            Dashboard Guru
        </h2>

        <div class="text-secondary mt-1">
            Selamat datang,
            {{ auth()->user()->name }}.
        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- LAYANAN GURU --}}
{{-- ========================================================= --}}

<div class="mb-3">

    <h3 class="page-title">
        Layanan Guru
    </h3>

    <div class="text-secondary mt-1">
        Pilih layanan yang ingin digunakan.
    </div>

</div>


<div class="row row-cards">

    {{-- ===================================================== --}}
    {{-- ABSENSI --}}
    {{-- ===================================================== --}}

    <div class="col-md-6 col-lg-4">

        <div class="card h-100">

            <div class="card-body">

                <div class="mb-4">

                    <span class="avatar avatar-xl bg-green-lt">

                        <i class="ti ti-calendar-check"></i>

                    </span>

                </div>


                <h2 class="card-title">
                    Absensi Siswa
                </h2>


                <p class="text-secondary">

                    Buka sesi absensi dan lakukan pemindaian
                    QR Code siswa untuk mencatat kehadiran
                    secara cepat dan otomatis.

                </p>

            </div>


            <div class="card-footer">

                <a
                    href="{{ route('absensi.sesi.index') }}"
                    class="btn btn-primary w-100"
                >

                    <i class="ti ti-qrcode me-2"></i>

                    Kelola Absensi

                </a>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- CBT --}}
    {{-- ===================================================== --}}

    <div class="col-md-6 col-lg-4">

        <div class="card h-100">

            <div class="card-body">

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-start
                        mb-4
                    "
                >

                    <span class="avatar avatar-xl bg-blue-lt">

                        <i class="ti ti-file-pencil"></i>

                    </span>


                    

                </div>


                <h2 class="card-title">
                    Ujian Online
                </h2>


                <p class="text-secondary">

                    Buat dan kelola ujian online,
                    soal ujian, token akses, serta
                    hasil ujian siswa.

                </p>

            </div>


            <div class="card-footer">

                <a
                    href="{{ route('cbt.bank-soal.index') }}"
                    class="btn btn-primary w-100"
                >
                    <i class="ti ti-file-pencil me-2"></i>
                    Kelola Ujian
                </a>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- PERPUSTAKAAN --}}
    {{-- ===================================================== --}}

    <div class="col-md-6 col-lg-4">

        <div class="card h-100">

            <div class="card-body">

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-start
                        mb-4
                    "
                >

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

                    Akses informasi koleksi buku dan
                    layanan perpustakaan sekolah.

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