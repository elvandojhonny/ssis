@extends('layouts.app')

@section('title', 'Dashboard Operator')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <div class="page-pretitle">
                Smart School Information System
            </div>

            <h2 class="page-title">
                Dashboard Operator
            </h2>

            <div class="text-secondary mt-1">
                Selamat datang, {{ auth()->user()->name }}.
            </div>

        </div>

    </div>

</div>


<div class="row row-cards">

    {{-- Guru --}}
    <div class="col-sm-6 col-lg-3">

        <div class="card card-sm">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-auto">

                        <span class="avatar bg-blue-lt">

                            <i class="ti ti-user"></i>

                        </span>

                    </div>

                    <div class="col">

                        <div class="h2 mb-0">
                            {{ $totalGuru }}
                        </div>

                        <div class="text-secondary">
                            Guru Aktif
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Siswa --}}
    <div class="col-sm-6 col-lg-3">

        <div class="card card-sm">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-auto">

                        <span class="avatar bg-green-lt">

                            <i class="ti ti-users"></i>

                        </span>

                    </div>

                    <div class="col">

                        <div class="h2 mb-0">
                            {{ $totalSiswa }}
                        </div>

                        <div class="text-secondary">
                            Siswa Aktif
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Kelas --}}
    <div class="col-sm-6 col-lg-3">

        <div class="card card-sm">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-auto">

                        <span class="avatar bg-orange-lt">

                            <i class="ti ti-school"></i>

                        </span>

                    </div>

                    <div class="col">

                        <div class="h2 mb-0">
                            {{ $totalKelas }}
                        </div>

                        <div class="text-secondary">
                            Kelas Aktif
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Tahun Ajaran --}}
    <div class="col-sm-6 col-lg-3">

        <div class="card card-sm">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-auto">

                        <span class="avatar bg-purple-lt">

                            <i class="ti ti-calendar"></i>

                        </span>

                    </div>

                    <div class="col">

                        <div class="fw-bold">

                            {{ $tahunAjaranAktif?->nama ?? '-' }}

                        </div>

                        <div class="text-secondary">
                            Tahun Ajaran Aktif
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection