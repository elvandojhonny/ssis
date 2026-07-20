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


        {{-- ================================================= --}}
        {{-- BANK SOAL --}}
        {{-- ================================================= --}}

        <div class="col-lg-5">

            <div class="card h-100">

                <div class="card-header">

                    <h3 class="card-title">
                        Pilih Bank Soal
                    </h3>

                </div>


                <div class="card-body">

                    {{-- BANK SOAL --}}

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


                    {{-- KELAS PESERTA --}}

<div class="mb-3">

    <label class="form-label required">
        Kelas Peserta
    </label>

    <select
        name="kelas_id"
        class="form-select @error('kelas_id') is-invalid @enderror"
        required
    >

        <option value="">
            Pilih kelas
        </option>

        @foreach(['X', 'XI', 'XII'] as $tingkat)

            @php
                $kelasTingkat = $kelas->where('tingkat', $tingkat);
            @endphp

            @if($kelasTingkat->isNotEmpty())

                <optgroup label="Tingkat {{ $tingkat }}">

                    @foreach($kelasTingkat as $item)

                        <option
                            value="{{ $item->id }}"
                            @selected(
                                old('kelas_id') == $item->id
                            )
                        >
                            {{ $item->nama }}
                            — {{ $item->tahunAjaran->nama }}
                        </option>

                    @endforeach

                </optgroup>

            @endif

        @endforeach

    </select>

    @error('kelas_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

    <div class="form-hint">
        Pilih kelas yang akan mengikuti ujian.
    </div>

</div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- PENGATURAN UJIAN --}}
        {{-- ================================================= --}}

        <div class="col-lg-7">

            <div class="card">

                <div class="card-header">

                    <h3 class="card-title">
                        Pengaturan Ujian
                    </h3>

                </div>


                <div class="card-body">

                    {{-- JUDUL --}}

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


                    {{-- DESKRIPSI --}}

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


                    {{-- JADWAL --}}

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
                                    value="{{
                                        old(
                                            'durasi_menit',
                                            90
                                        )
                                    }}"
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


                    {{-- ================================================= --}}
                    {{-- PENGACAKAN SOAL --}}
                    {{-- ================================================= --}}

                    <hr class="my-4">


                    <div class="mb-3">

                        <div class="d-flex align-items-center gap-2 mb-1">

                            <i class="ti ti-arrows-shuffle text-primary"></i>

                            <label class="form-label mb-0">
                                Pengacakan Ujian
                            </label>

                        </div>

                        <div class="text-secondary small">

                            Atur pengacakan soal dan pilihan jawaban
                            untuk setiap peserta ujian.

                        </div>

                    </div>


                    {{-- ACAK SOAL --}}

                    <div class="border rounded p-3 mb-3">

                        <div
                            class="
                                d-flex
                                justify-content-between
                                align-items-center
                                gap-3
                            "
                        >

                            <div>

                                <div class="fw-bold">
                                    Acak Urutan Soal
                                </div>

                                <div class="text-secondary small mt-1">

                                    Setiap siswa mendapatkan urutan
                                    nomor soal yang berbeda.

                                </div>

                            </div>


                            <label class="form-check form-switch m-0">

                                <input
                                    type="hidden"
                                    name="acak_soal"
                                    value="0"
                                >

                                <input
                                    type="checkbox"
                                    name="acak_soal"
                                    value="1"
                                    class="form-check-input"
                                    @checked(
                                        old(
                                            'acak_soal',
                                            1
                                        )
                                    )
                                >

                            </label>

                        </div>

                    </div>


                    {{-- ACAK JAWABAN --}}

                    <div class="border rounded p-3">

                        <div
                            class="
                                d-flex
                                justify-content-between
                                align-items-center
                                gap-3
                            "
                        >

                            <div>

                                <div class="fw-bold">
                                    Acak Pilihan Jawaban
                                </div>

                                <div class="text-secondary small mt-1">

                                    Posisi pilihan A, B, C, D, dan E
                                    dapat berbeda pada setiap siswa.

                                </div>

                            </div>


                            <label class="form-check form-switch m-0">

                                <input
                                    type="hidden"
                                    name="acak_jawaban"
                                    value="0"
                                >

                                <input
                                    type="checkbox"
                                    name="acak_jawaban"
                                    value="1"
                                    class="form-check-input"
                                    @checked(
                                        old(
                                            'acak_jawaban',
                                            1
                                        )
                                    )
                                >

                            </label>

                        </div>

                    </div>


                    <div class="alert alert-info mt-3 mb-0">

                        <div class="d-flex">

                            <div class="me-2">

                                <i class="ti ti-info-circle"></i>

                            </div>

                            <div>

                                Pengacakan dilakukan secara berbeda
                                untuk setiap peserta, tetapi urutan
                                yang diterima satu peserta akan tetap
                                sama selama pengerjaan berlangsung.

                            </div>

                        </div>

                    </div>

                </div>


                {{-- FOOTER --}}

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