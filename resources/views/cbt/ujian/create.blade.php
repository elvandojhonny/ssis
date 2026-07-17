@extends('layouts.app')

@section('title', 'Buat Ujian')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Modul CBT
            </div>

            <h2 class="page-title">
                Buat Ujian
            </h2>

            <div class="text-secondary mt-1">
                Pilih bank soal dan tentukan pelaksanaan ujian.
            </div>

        </div>

        <div class="col-12 col-md-auto">

            <a
                href="{{ route('cbt.ujian.index') }}"
                class="btn btn-outline-secondary w-100"
            >
                <i class="ti ti-arrow-left me-1"></i>
                Kembali
            </a>

        </div>

    </div>

</div>


@if($errors->any())

    <div class="alert alert-danger">

        <div class="fw-bold mb-2">
            Data ujian belum dapat disimpan.
        </div>

        <ul class="mb-0">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


<form
    action="{{ route('cbt.ujian.store') }}"
    method="POST"
>

    @csrf


    <div class="row row-cards">


        {{-- BANK SOAL --}}

        <div class="col-lg-5">

            <div class="card h-100">

                <div class="card-header">

                    <h3 class="card-title">
                        Pilih Bank Soal
                    </h3>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <label class="form-label required">
                            Bank Soal
                        </label>

                        <select
                            name="bank_soal_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Pilih bank soal
                            </option>

                            @foreach($bankSoals as $bankSoal)

                                <option
                                    value="{{ $bankSoal->id }}"
                                    @selected(
                                        old('bank_soal_id')
                                        == $bankSoal->id
                                    )
                                >

                                    {{ $bankSoal->judul }}

                                    —

                                    {{ $bankSoal->mata_pelajaran }}

                                    —

                                    {{ $bankSoal->soals_count }}
                                    soal

                                </option>

                            @endforeach

                        </select>

                        <div class="form-hint">
                            Hanya bank soal berstatus siap yang
                            dapat digunakan.
                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="form-label required">
                            Kelas Peserta
                        </label>

                        <select
                            name="kelas_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Pilih kelas
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

                                    {{
                                        $item
                                            ->tahunAjaran
                                            ->nama
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- PENGATURAN UJIAN --}}

        <div class="col-lg-7">

            <div class="card">

                <div class="card-header">

                    <h3 class="card-title">
                        Pengaturan Ujian
                    </h3>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <label class="form-label required">
                            Judul Ujian
                        </label>

                        <input
                            type="text"
                            name="judul"
                            class="form-control"
                            value="{{ old('judul') }}"
                            placeholder="Contoh: Ujian Tengah Semester Matematika"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Deskripsi
                        </label>

                        <textarea
                            name="deskripsi"
                            class="form-control"
                            rows="3"
                        >{{ old('deskripsi') }}</textarea>

                    </div>


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label required">
                                Waktu Mulai
                            </label>

                            <input
                                type="datetime-local"
                                name="waktu_mulai"
                                class="form-control"
                                value="{{ old('waktu_mulai') }}"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label required">
                                Waktu Selesai
                            </label>

                            <input
                                type="datetime-local"
                                name="waktu_selesai"
                                class="form-control"
                                value="{{ old('waktu_selesai') }}"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label required">
                                Durasi Pengerjaan
                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    name="durasi_menit"
                                    class="form-control"
                                    value="{{ old(
                                        'durasi_menit',
                                        90
                                    ) }}"
                                    min="1"
                                    max="600"
                                    required
                                >

                                <span class="input-group-text">
                                    menit
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="card-footer">

                    <div
                        class="
                            d-flex
                            flex-column
                            flex-md-row
                            justify-content-between
                            align-items-md-center
                            gap-3
                        "
                    >

                        <div class="text-secondary small">

                            <i class="ti ti-info-circle me-1"></i>

                            Ujian akan disimpan sebagai draft
                            dan belum terlihat oleh siswa.

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="ti ti-device-floppy me-1"></i>

                            Simpan Draft Ujian

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</form>

@endsection