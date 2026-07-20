@extends('layouts.app')

@section('title', 'Buka Sesi Absensi')

@section('content')

<div class="page-header mb-4">

    <div>

        <div class="page-pretitle">
            Modul Absensi
        </div>

        <h2 class="page-title">
            Buka Sesi Absensi
        </h2>

        <div class="text-secondary mt-1">
            Pilih tingkat dan jenis absensi yang akan dibuka.
        </div>

    </div>

</div>


@if(session('error'))

    <div class="alert alert-danger">

        <i class="ti ti-alert-circle me-2"></i>

        {{ session('error') }}

    </div>

@endif


<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Pengaturan Sesi
        </h3>

    </div>


    <div class="card-body">

        <form
            action="{{ route('absensi.sesi.store') }}"
            method="POST"
        >

            @csrf


            {{-- TINGKAT --}}

            <div class="mb-4">

                <label class="form-label">

                    Tingkat

                    <span class="text-danger">*</span>

                </label>


                <div class="row g-3">

                    @foreach($tingkats as $tingkat)

                        <div class="col-12 col-md-4">

                            <label
                                class="form-selectgroup-item w-100"
                            >

                                <input
                                    type="radio"
                                    name="tingkat"
                                    value="{{ $tingkat }}"
                                    class="form-selectgroup-input"
                                    @checked(
                                        old('tingkat') === $tingkat
                                    )
                                    required
                                >


                                <span
                                    class="
                                        form-selectgroup-label
                                        d-flex
                                        align-items-center
                                        justify-content-center
                                        py-3
                                    "
                                >

                                    <i
                                        class="
                                            ti
                                            ti-school
                                            me-2
                                        "
                                    ></i>

                                    Kelas {{ $tingkat }}

                                </span>

                            </label>

                        </div>

                    @endforeach

                </div>


                @error('tingkat')

                    <div class="text-danger small mt-2">

                        {{ $message }}

                    </div>

                @enderror


                <div class="form-hint mt-2">

                    Sesi berlaku untuk seluruh jurusan pada tingkat
                    yang dipilih.

                </div>

            </div>


            {{-- JENIS ABSENSI --}}

            <div class="mb-4">

                <label class="form-label">

                    Jenis Absensi

                    <span class="text-danger">*</span>

                </label>


                <div class="row g-3">


                    {{-- PAGI --}}

                    <div class="col-12 col-md-6">

                        <label
                            class="form-selectgroup-item w-100"
                        >

                            <input
                                type="radio"
                                name="jenis"
                                value="pagi"
                                class="form-selectgroup-input"
                                @checked(
                                    old('jenis') === 'pagi'
                                )
                                required
                            >


                            <span
                                class="
                                    form-selectgroup-label
                                    d-flex
                                    align-items-center
                                    py-3
                                "
                            >

                                <i
                                    class="
                                        ti
                                        ti-sun
                                        me-2
                                    "
                                ></i>

                                Absensi Pagi

                            </span>

                        </label>

                    </div>


                    {{-- SIANG --}}

                    <div class="col-12 col-md-6">

                        <label
                            class="form-selectgroup-item w-100"
                        >

                            <input
                                type="radio"
                                name="jenis"
                                value="siang"
                                class="form-selectgroup-input"
                                @checked(
                                    old('jenis') === 'siang'
                                )
                                required
                            >


                            <span
                                class="
                                    form-selectgroup-label
                                    d-flex
                                    align-items-center
                                    py-3
                                "
                            >

                                <i
                                    class="
                                        ti
                                        ti-sunset
                                        me-2
                                    "
                                ></i>

                                Absensi Siang

                            </span>

                        </label>

                    </div>

                </div>


                @error('jenis')

                    <div class="text-danger small mt-2">

                        {{ $message }}

                    </div>

                @enderror

            </div>


            {{-- PENGATURAN WAKTU --}}

            <div class="row">


                {{-- WAKTU MULAI --}}

                <div class="col-12 col-md-4 mb-3">

                    <label class="form-label">

                        Waktu Mulai

                        <span class="text-danger">*</span>

                    </label>


                    <input
                        type="time"
                        name="waktu_mulai"
                        value="{{
                            old(
                                'waktu_mulai',
                                '07:00'
                            )
                        }}"
                        class="
                            form-control
                            @error('waktu_mulai')
                            is-invalid
                            @enderror
                        "
                        required
                    >


                    @error('waktu_mulai')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                {{-- BATAS TERLAMBAT --}}

                <div class="col-12 col-md-4 mb-3">

                    <label class="form-label">

                        Batas Terlambat

                    </label>


                    <input
                        type="time"
                        name="batas_terlambat"
                        value="{{
                            old(
                                'batas_terlambat',
                                '07:15'
                            )
                        }}"
                        class="
                            form-control
                            @error('batas_terlambat')
                            is-invalid
                            @enderror
                        "
                    >


                    @error('batas_terlambat')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                {{-- WAKTU SELESAI --}}

                <div class="col-12 col-md-4 mb-3">

                    <label class="form-label">

                        Waktu Selesai

                        <span class="text-danger">*</span>

                    </label>


                    <input
                        type="time"
                        name="waktu_selesai"
                        value="{{
                            old(
                                'waktu_selesai',
                                '07:30'
                            )
                        }}"
                        class="
                            form-control
                            @error('waktu_selesai')
                            is-invalid
                            @enderror
                        "
                        required
                    >


                    @error('waktu_selesai')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

            </div>


            {{-- INFORMASI --}}

            <div class="alert alert-info mt-2">

                <div class="d-flex">

                    <i
                        class="
                            ti
                            ti-info-circle
                            me-2
                            mt-1
                        "
                    ></i>

                    <div>

                        <div class="fw-bold mb-1">
                            Satu sesi untuk seluruh jurusan
                        </div>

                        <div>
                            Jika membuka sesi untuk Kelas X,
                            seluruh siswa Kelas X dapat melakukan
                            absensi menggunakan QR yang sama,
                            termasuk siswa X IPA dan X IPS.
                        </div>

                    </div>

                </div>

            </div>


            {{-- ACTION --}}

            <div
                class="
                    mt-4
                    d-flex
                    flex-column
                    flex-sm-row
                    gap-2
                "
            >

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="ti ti-player-play me-1"></i>

                    Buka Sesi

                </button>


                <a
                    href="{{ route('absensi.sesi.index') }}"
                    class="btn btn-outline-secondary"
                >

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection