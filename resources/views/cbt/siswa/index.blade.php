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


{{-- ALERT SUCCESS --}}

@if(session('success'))

    <div class="alert alert-success">

        <i class="ti ti-circle-check me-2"></i>

        {{ session('success') }}

    </div>

@endif


{{-- ALERT INFO --}}

@if(session('info'))

    <div class="alert alert-info">

        <i class="ti ti-info-circle me-2"></i>

        {{ session('info') }}

    </div>

@endif


{{-- ALERT ERROR --}}

@if(session('error'))

    <div class="alert alert-danger">

        <i class="ti ti-alert-circle me-2"></i>

        {{ session('error') }}

    </div>

@endif


<div class="row row-cards">

    @forelse($ujians as $ujian)

        @php

            /*
             * Ambil pengerjaan milik siswa
             * untuk ujian ini.
             */
            $pengerjaan =
                $ujian
                    ->pengerjaans
                    ->first();


            /*
             * Tentukan kondisi jadwal ujian.
             */
            $belumMulai =
                now()->lt(
                    $ujian->waktu_mulai
                );


            $sedangBerlangsung =
                now()->gte(
                    $ujian->waktu_mulai
                )
                &&
                now()->lt(
                    $ujian->waktu_selesai
                );


            $sudahBerakhir =
                now()->gte(
                    $ujian->waktu_selesai
                );


            /*
             * Tentukan status pengerjaan.
             */
            $sudahSelesai =
                $pengerjaan &&
                $pengerjaan->status ===
                    'selesai';


            $sedangMengerjakan =
                $pengerjaan &&
                $pengerjaan->status ===
                    'mengerjakan';

        @endphp


        <div class="col-md-6 col-xl-4">

            <div class="card h-100">


                {{-- STATUS WARNA CARD --}}

                @if($sudahSelesai)

                    <div class="card-status-top bg-blue"></div>

                @elseif($sedangMengerjakan)

                    <div class="card-status-top bg-yellow"></div>

                @elseif($belumMulai)

                    <div class="card-status-top bg-yellow"></div>

                @elseif($sedangBerlangsung)

                    <div class="card-status-top bg-success"></div>

                @else

                    <div class="card-status-top bg-secondary"></div>

                @endif


                <div class="card-body">


                    {{-- JUDUL DAN STATUS --}}

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


                        {{-- BADGE STATUS --}}

                        <div>

                            @if($sudahSelesai)

                                <span class="badge bg-blue-lt">

                                    Selesai

                                </span>


                            @elseif($sedangMengerjakan)

                                <span class="badge bg-yellow-lt">

                                    Sedang Dikerjakan

                                </span>


                            @elseif($belumMulai)

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

                    </div>


                    {{-- JADWAL --}}

                    <div class="mb-3">

                        <div class="text-secondary small">

                            Jadwal

                        </div>


                        <div class="fw-bold mt-1">

                            {{
                                $ujian
                                    ->waktu_mulai
                                    ->format(
                                        'd/m/Y H:i'
                                    )
                            }}

                        </div>


                        <div class="text-secondary small mt-1">

                            sampai

                            {{
                                $ujian
                                    ->waktu_selesai
                                    ->format(
                                        'd/m/Y H:i'
                                    )
                            }}

                        </div>

                    </div>


                    {{-- INFORMASI UJIAN --}}

                    <div class="row g-3">


                        {{-- DURASI --}}

                        <div class="col-6">

                            <div class="text-secondary small">

                                Durasi

                            </div>


                            <div class="fw-bold">

                                {{
                                    $ujian
                                        ->durasi_menit
                                }}

                                menit

                            </div>

                        </div>


                        {{-- JUMLAH SOAL --}}

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


                    {{-- INFORMASI PENGERJAAN --}}

                    @if($sedangMengerjakan)

                        <div class="alert alert-warning mt-4 mb-0">

                            <div class="d-flex">

                                <div class="me-2">

                                    <i class="ti ti-clock"></i>

                                </div>


                                <div>

                                    <div class="fw-bold">

                                        Ujian sedang berlangsung

                                    </div>


                                    <div class="small">

                                        Anda sudah memulai ujian ini.
                                        Silakan lanjutkan pengerjaan
                                        sebelum waktu berakhir.

                                    </div>

                                </div>

                            </div>

                        </div>

                    @elseif($sudahSelesai)

                        <div class="alert alert-success mt-4 mb-0">

                            <div class="d-flex">

                                <div class="me-2">

                                    <i class="ti ti-circle-check"></i>

                                </div>


                                <div>

                                    <div class="fw-bold">

                                        Ujian telah diselesaikan

                                    </div>


                                    <div class="small">

                                        Anda tidak dapat mengerjakan
                                        kembali ujian ini.

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endif


                </div>


                {{-- CARD FOOTER --}}

                <div class="card-footer">


                    {{-- SUDAH MENYELESAIKAN UJIAN --}}

                    @if($sudahSelesai)

                        <button
                            type="button"
                            class="
                                btn
                                btn-success
                                w-100
                            "
                            disabled
                        >

                            <i
                                class="
                                    ti
                                    ti-circle-check
                                    me-1
                                "
                            ></i>

                            Ujian Telah Selesai

                        </button>


                    {{-- SEDANG MENGERJAKAN --}}

                    @elseif($sedangMengerjakan)

                        <a
                            href="{{
                                route(
                                    'cbt.siswa.pengerjaan.show',
                                    $pengerjaan
                                )
                            }}"
                            class="
                                btn
                                btn-warning
                                w-100
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-player-play
                                    me-1
                                "
                            ></i>

                            Lanjutkan Ujian

                        </a>


                    {{-- UJIAN SEDANG BERLANGSUNG --}}

                    @elseif($sedangBerlangsung)

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
                                    class="
                                        form-control
                                        text-uppercase
                                    "
                                    placeholder="Masukkan token"
                                    maxlength="10"
                                    autocomplete="off"
                                    required
                                >


                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >

                                    <i
                                        class="
                                            ti
                                            ti-login
                                            me-1
                                        "
                                    ></i>

                                    Masuk

                                </button>

                            </div>

                        </form>


                    {{-- UJIAN BELUM DIMULAI --}}

                    @elseif($belumMulai)

                        <button
                            type="button"
                            class="
                                btn
                                btn-outline-secondary
                                w-100
                            "
                            disabled
                        >

                            <i
                                class="
                                    ti
                                    ti-clock
                                    me-1
                                "
                            ></i>

                            Belum Dimulai

                        </button>


                    {{-- UJIAN SUDAH BERAKHIR --}}

                    @else

                        <button
                            type="button"
                            class="
                                btn
                                btn-outline-secondary
                                w-100
                            "
                            disabled
                        >

                            <i
                                class="
                                    ti
                                    ti-lock
                                    me-1
                                "
                            ></i>

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
                        class="
                            avatar
                            avatar-xl
                            bg-blue-lt
                            mb-3
                        "
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