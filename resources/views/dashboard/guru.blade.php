@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')

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


<div class="row row-cards">

    <div class="col-md-6 col-lg-4">

        <div class="card">

            <div class="card-body">

                <div class="d-flex align-items-center mb-3">

                    <span class="avatar avatar-lg bg-blue-lt me-3">

                        <i class="ti ti-qrcode"></i>

                    </span>

                    <div>

                        <h3 class="card-title mb-1">
                            Absensi
                        </h3>

                        <div class="text-secondary">
                            Kelola sesi absensi siswa.
                        </div>

                    </div>

                </div>


                <div class="text-secondary">

                    Fitur QR Absensi akan tersedia setelah
                    modul absensi selesai dikembangkan.

                </div>

            </div>

        </div>

    </div>

</div>

@endsection