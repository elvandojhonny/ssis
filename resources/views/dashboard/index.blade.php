@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">

        <div class="col">

            <div class="page-pretitle">
                Overview
            </div>

            <h2 class="page-title ssis-page-title">
                Dashboard
            </h2>

        </div>

    </div>
</div>

<div class="row row-cards">

    <div class="col-sm-6 col-lg-3">

        <div class="card card-sm">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-auto">

                        <span class="bg-primary text-white avatar">
                            <i class="ti ti-users"></i>
                        </span>

                    </div>

                    <div class="col">

                        <div class="font-weight-medium">
                            Total Siswa
                        </div>

                        <div class="text-secondary">
                            Belum ada data
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-sm-6 col-lg-3">

        <div class="card card-sm">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-auto">

                        <span class="bg-success text-white avatar">
                            <i class="ti ti-user-check"></i>
                        </span>

                    </div>

                    <div class="col">

                        <div class="font-weight-medium">
                            Hadir Hari Ini
                        </div>

                        <div class="text-secondary">
                            Belum ada data
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection