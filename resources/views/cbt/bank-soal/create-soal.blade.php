@extends('layouts.app')

@section('title', 'Tambah Soal')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <div class="page-pretitle">
                Modul CBT
            </div>

            <h2 class="page-title">
                Tambah Soal
            </h2>

            <div class="text-secondary">
                {{ $bankSoal->judul }}
            </div>

        </div>

        <div class="col-auto">

            <a
                href="{{ route('cbt.bank-soal.show', $bankSoal) }}"
                class="btn btn-outline-secondary"
            >
                <i class="ti ti-arrow-left me-1"></i>
                Kembali
            </a>

        </div>

    </div>

</div>


<form
    action="{{ route('cbt.bank-soal.soal.store', $bankSoal) }}"
    method="POST"
>

    @csrf

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">

                Tambah Soal Manual

            </h3>

        </div>

        <div class="card-body">

            {{-- Jenis Soal --}}

            <div class="mb-4">

                <label class="form-label required">

                    Jenis Soal

                </label>

                <div class="d-flex gap-4">

                    <label class="form-check">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="tipe"
                            value="pilihan_ganda"
                            checked
                        >

                        <span class="form-check-label">
                            Pilihan Ganda
                        </span>

                    </label>

                    <label class="form-check">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="tipe"
                            value="essay"
                        >

                        <span class="form-check-label">
                            Essay
                        </span>

                    </label>

                </div>

            </div>


            {{-- Pertanyaan --}}

            <div class="mb-4">

                <label class="form-label required">

                    Pertanyaan

                </label>

                <textarea
                    name="pertanyaan"
                    rows="5"
                    class="form-control"
                    placeholder="Masukkan pertanyaan..."
                ></textarea>

            </div>


            {{-- Pilihan Jawaban --}}

            <div id="pilihan-container">

                <div class="row">

                    @foreach([
                        'A',
                        'B',
                        'C',
                        'D',
                        'E'
                    ] as $huruf)

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Pilihan {{ $huruf }}

                            </label>

                            <input
                                type="text"
                                name="pilihan_{{ strtolower($huruf) }}"
                                class="form-control"
                            >

                        </div>

                    @endforeach

                </div>


                <div class="mb-4">

                    <label class="form-label">

                        Jawaban Benar

                    </label>

                    <select
                        name="jawaban_benar"
                        class="form-select"
                    >

                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>

                    </select>

                </div>

            </div>


            {{-- Bobot --}}

            <div class="mb-4">

                <label class="form-label">

                    Bobot

                </label>

                <input
                    type="number"
                    name="bobot"
                    class="form-control"
                    value="5"
                    min="1"
                >

            </div>

        </div>

        <div class="card-footer text-end">

            <button
                class="btn btn-primary"
                type="submit"
            >

                <i class="ti ti-device-floppy me-1"></i>

                Simpan Soal

            </button>

        </div>

    </div>

</form>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const radios =
        document.querySelectorAll(
            'input[name="tipe"]'
        );

    const pilihan =
        document.getElementById(
            'pilihan-container'
        );

    function togglePilihan()
    {
        const tipe =
            document.querySelector(
                'input[name="tipe"]:checked'
            ).value;

        if (tipe === 'essay') {

            pilihan.style.display = 'none';

        } else {

            pilihan.style.display = 'block';

        }
    }

    radios.forEach(function(item){

        item.addEventListener(
            'change',
            togglePilihan
        );

    });

    togglePilihan();

});

</script>

@endpush