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
            Pilih kelas dan jenis absensi yang akan dibuka.
        </div>

    </div>

</div>


@if(session('error'))

    <div class="alert alert-danger">

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


            {{-- KELAS --}}

            <div class="mb-3">

                <label class="form-label">

                    Kelas

                    <span class="text-danger">*</span>

                </label>


                <select
                    name="kelas_id"
                    class="form-select
                           @error('kelas_id')
                           is-invalid
                           @enderror"
                    required
                >

                    <option value="">

                        Pilih Kelas

                    </option>


                    @foreach($kelas as $item)

                        <option
                            value="{{ $item->id }}"
                            @selected(
                                old('kelas_id')
                                == $item->id
                            )
                        >

                            {{ $item->nama }}

                            —

                            {{ $item->tahunAjaran->nama }}

                        </option>

                    @endforeach

                </select>


                @error('kelas_id')

                    <div class="invalid-feedback">

                        {{ $message }}

                    </div>

                @enderror

            </div>


            {{-- JENIS --}}

            <div class="mb-3">

                <label class="form-label">

                    Jenis Absensi

                    <span class="text-danger">*</span>

                </label>


                <div class="row">


                    <div class="col-md-6">

                        <label
                            class="form-selectgroup-item
                                   w-100"
                        >

                            <input
                                type="radio"
                                name="jenis"
                                value="pagi"
                                class="form-selectgroup-input"
                                @checked(
                                    old('jenis')
                                    === 'pagi'
                                )
                                required
                            >


                            <span
                                class="form-selectgroup-label
                                       d-flex
                                       align-items-center"
                            >

                                <i
                                    class="ti
                                           ti-sun
                                           me-2"
                                ></i>

                                Absensi Pagi

                            </span>

                        </label>

                    </div>


                    <div class="col-md-6">

                        <label
                            class="form-selectgroup-item
                                   w-100"
                        >

                            <input
                                type="radio"
                                name="jenis"
                                value="siang"
                                class="form-selectgroup-input"
                                @checked(
                                    old('jenis')
                                    === 'siang'
                                )
                                required
                            >


                            <span
                                class="form-selectgroup-label
                                       d-flex
                                       align-items-center"
                            >

                                <i
                                    class="ti
                                           ti-sunset
                                           me-2"
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


            {{-- WAKTU --}}

            <div class="row">


                <div class="col-md-4 mb-3">

                    <label class="form-label">

                        Waktu Mulai

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
                        class="form-control"
                        required
                    >

                </div>


                <div class="col-md-4 mb-3">

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
                        class="form-control"
                    >

                </div>


                <div class="col-md-4 mb-3">

                    <label class="form-label">

                        Waktu Selesai

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
                        class="form-control"
                        required
                    >

                </div>

            </div>


            <div class="alert alert-info">

                <i class="ti ti-info-circle me-2"></i>

                Setiap kelas hanya dapat memiliki satu sesi
                pagi dan satu sesi siang dalam satu hari.

            </div>


            <div class="mt-4">

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="ti ti-player-play me-1"></i>

                    Buka Sesi

                </button>


                <a
                    href="{{
                        route(
                            'absensi.sesi.index'
                        )
                    }}"
                    class="btn btn-outline-secondary"
                >

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection