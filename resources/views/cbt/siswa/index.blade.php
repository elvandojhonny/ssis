@extends('layouts.app')

@section('title', 'Ujian Saya')

@section('content')

<div class="page-header mb-4">

    <div>

        <div class="page-pretitle">
            Modul CBT
        </div>

        <h2 class="page-title">
            Ujian Saya
        </h2>

        <div class="text-secondary mt-1">
            Daftar ujian untuk kelas
            {{ $siswa->kelas->nama ?? '-' }}.
        </div>

    </div>

</div>


@if(session('error'))

    <div class="alert alert-danger">

        <i class="ti ti-alert-circle me-2"></i>

        {{ session('error') }}

    </div>

@endif


<div class="row row-cards">

@forelse($ujians as $ujian)

    @php

        $belumMulai =
            now()->lt($ujian->waktu_mulai);

        $sedangBerlangsung =
            now()->between(
                $ujian->waktu_mulai,
                $ujian->waktu_selesai
            );

        $sudahBerakhir =
            now()->gt($ujian->waktu_selesai);

    @endphp


    <div class="col-md-6 col-xl-4">

        <div class="card h-100">


            @if($belumMulai)

                <div class="card-status-top bg-yellow"></div>

            @elseif($sedangBerlangsung)

                <div class="card-status-top bg-success"></div>

            @else

                <div class="card-status-top bg-secondary"></div>

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

                    <div>

                        <div class="text-secondary small">

                            {{
                                $ujian
                                    ->bankSoal
                                    ->mata_pelajaran
                            }}

                        </div>


                        <h3 class="card-title mt-1">

                            {{ $ujian->judul }}

                        </h3>

                    </div>


                    @if($belumMulai)

                        <span class="badge bg-yellow-lt">
                            Belum Dimulai
                        </span>

                    @elseif($sedangBerlangsung)

                        <span class="badge bg-success-lt">
                            Bisa Dikerjakan
                        </span>

                    @else

                        <span class="badge bg-secondary-lt">
                            Berakhir
                        </span>

                    @endif

                </div>


                <div class="mb-3">

                    <div class="text-secondary small">
                        Jadwal
                    </div>

                    <div class="fw-bold mt-1">

                        {{
                            $ujian
                                ->waktu_mulai
                                ->format('d/m/Y H:i')
                        }}

                    </div>

                </div>


                <div class="row g-3">

                    <div class="col-6">

                        <div class="text-secondary small">
                            Durasi
                        </div>

                        <div class="fw-bold">
                            {{ $ujian->durasi_menit }} menit
                        </div>

                    </div>


                    <div class="col-6">

                        <div class="text-secondary small">
                            Jumlah Soal
                        </div>

                        <div class="fw-bold">

                            {{
                                $ujian
                                    ->bankSoal
                                    ->soals()
                                    ->count()
                            }}

                            soal

                        </div>

                    </div>

                </div>

            </div>


            <div class="card-footer">


                @if($sedangBerlangsung)

                    <form
                        action="{{
                            route(
                                'cbt.siswa.ujian.token',
                                $ujian
                            )
                        }}"
                        method="POST"
                    >

                        @csrf


                        <div class="input-group">

                            <input
                                type="text"
                                name="token"
                                class="form-control text-uppercase"
                                placeholder="Masukkan token"
                                maxlength="10"
                                autocomplete="off"
                                required
                            >


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                Masuk

                            </button>

                        </div>

                    </form>


                @elseif($belumMulai)

                    <button
                        class="btn btn-outline-secondary w-100"
                        disabled
                    >

                        <i class="ti ti-clock me-1"></i>

                        Belum Dimulai

                    </button>


                @else

                    <button
                        class="btn btn-outline-secondary w-100"
                        disabled
                    >

                        <i class="ti ti-lock me-1"></i>

                        Ujian Berakhir

                    </button>

                @endif

            </div>

        </div>

    </div>


@empty

    <div class="col-12">

        <div class="card">

            <div class="card-body text-center py-5">

                <span
                    class="avatar avatar-xl bg-blue-lt mb-3"
                >

                    <i class="ti ti-file-pencil"></i>

                </span>


                <h3>
                    Belum Ada Ujian
                </h3>


                <div class="text-secondary">

                    Belum ada ujian yang dipublikasikan
                    untuk kelas Anda.

                </div>

            </div>

        </div>

    </div>

@endforelse

</div>

@endsection