@extends('layouts.app')

@section('title', 'Pengerjaan Ujian')

@section('content')

@php
    $ujian = $pengerjaan->ujian;
    $soals = $ujian->bankSoal->soals;

    $jawabanTersimpan = $pengerjaan
        ->jawabans
        ->keyBy('soal_id');
@endphp


<div class="row g-4">

    {{-- ===================================================== --}}
    {{-- AREA SOAL --}}
    {{-- ===================================================== --}}

    <div class="col-lg-9">

        {{-- HEADER UJIAN --}}
        <div class="card mb-4">

            <div class="card-body">

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

                    <div>

                        <div class="page-pretitle">
                            Computer Based Test
                        </div>

                        <h2 class="mb-1">
                            {{ $ujian->judul }}
                        </h2>

                        <div class="text-secondary">

                            {{ $ujian->kelas->nama ?? '-' }}

                            <span class="mx-2">•</span>

                            {{ $soals->count() }} Soal

                        </div>

                    </div>


                    {{-- TIMER --}}
                    <div>

                        <div
                            id="timer-container"
                            class="
                                border
                                rounded
                                px-4
                                py-3
                                text-center
                            "
                        >

                            <div class="text-secondary small mb-1">
                                Sisa Waktu
                            </div>

                            <div
                                id="timer"
                                class="fw-bold fs-2"
                            >
                                --:--:--
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- DAFTAR SOAL --}}
        {{-- ================================================= --}}

        @forelse($soals as $index => $soal)

            @php
                $jawaban = $jawabanTersimpan
                    ->get($soal->id)
                    ?->jawaban;

                $pilihanJawaban = [
                    'A' => $soal->pilihan_a,
                    'B' => $soal->pilihan_b,
                    'C' => $soal->pilihan_c,
                    'D' => $soal->pilihan_d,
                    'E' => $soal->pilihan_e,
                ];
            @endphp


            <div
                class="
                    card
                    soal-container
                    {{ $index === 0 ? '' : 'd-none' }}
                "
                data-index="{{ $index }}"
                data-soal-id="{{ $soal->id }}"
            >

                <div class="card-header">

                    <div
                        class="
                            d-flex
                            justify-content-between
                            align-items-center
                            w-100
                        "
                    >

                        <h3 class="card-title mb-0">
                            Soal {{ $index + 1 }}
                        </h3>


                        <span class="badge bg-blue-lt">

                            Bobot:

                            {{
                                rtrim(
                                    rtrim(
                                        number_format(
                                            $soal->bobot,
                                            2,
                                            '.',
                                            ''
                                        ),
                                        '0'
                                    ),
                                    '.'
                                )
                            }}

                        </span>

                    </div>

                </div>


                <div class="card-body">

                    {{-- PERTANYAAN --}}
                    <div
                        class="
                            fs-3
                            fw-medium
                            mb-4
                            soal-pertanyaan
                        "
                    >
                        {!! nl2br(e($soal->pertanyaan)) !!}
                    </div>


                    {{-- PILIHAN JAWABAN --}}
                    <div class="jawaban-list">

                        @foreach($pilihanJawaban as $huruf => $pilihan)

                            @if(!is_null($pilihan) && $pilihan !== '')

                                <label
                                    class="
                                        jawaban-option
                                        d-flex
                                        align-items-start
                                        gap-3
                                        border
                                        rounded
                                        p-3
                                        mb-3
                                    "
                                >

                                    <input
                                        type="radio"
                                        class="
                                            form-check-input
                                            mt-1
                                            jawaban-radio
                                        "
                                        name="jawaban_{{ $soal->id }}"
                                        value="{{ $huruf }}"
                                        data-soal-id="{{ $soal->id }}"
                                        {{ $jawaban === $huruf ? 'checked' : '' }}
                                    >


                                    <span
                                        class="
                                            jawaban-huruf
                                            fw-bold
                                        "
                                    >
                                        {{ $huruf }}
                                    </span>


                                    <span class="flex-fill">
                                        {{ $pilihan }}
                                    </span>

                                </label>

                            @endif

                        @endforeach

                    </div>

                </div>


                {{-- NAVIGASI SOAL --}}
                <div class="card-footer">

                    <div
                        class="
                            d-flex
                            justify-content-between
                            gap-2
                        "
                    >

                        <button
                            type="button"
                            class="
                                btn
                                btn-outline-secondary
                                btn-sebelumnya
                            "
                            data-index="{{ $index }}"
                            {{ $index === 0 ? 'disabled' : '' }}
                        >

                            <i class="ti ti-chevron-left me-1"></i>

                            Sebelumnya

                        </button>


                        @if($index < $soals->count() - 1)

                            <button
                                type="button"
                                class="
                                    btn
                                    btn-primary
                                    btn-selanjutnya
                                "
                                data-index="{{ $index }}"
                            >

                                Selanjutnya

                                <i class="ti ti-chevron-right ms-1"></i>

                            </button>

                        @else

                            <button
                                type="button"
                                class="
                                    btn
                                    btn-success
                                    btn-buka-submit
                                "
                            >

                                <i class="ti ti-circle-check me-1"></i>

                                Selesai Ujian

                            </button>

                        @endif

                    </div>

                </div>

            </div>


        @empty

            <div class="card">

                <div
                    class="
                        card-body
                        text-center
                        text-secondary
                        py-5
                    "
                >

                    Tidak ada soal pada ujian ini.

                </div>

            </div>

        @endforelse

    </div>



    {{-- ===================================================== --}}
    {{-- NAVIGASI NOMOR SOAL --}}
    {{-- ===================================================== --}}

    <div class="col-lg-3">

        <div
            class="card"
            style="
                position: sticky;
                top: 90px;
            "
        >

            <div class="card-header">

                <h3 class="card-title">
                    Navigasi Soal
                </h3>

            </div>


            <div class="card-body">

                <div
                    id="navigasi-soal"
                    class="d-grid gap-2"
                    style="
                        grid-template-columns:
                        repeat(5, 1fr);
                    "
                >

                    @foreach($soals as $index => $soal)

                        @php
                            $sudahDijawab = $jawabanTersimpan
                                ->has($soal->id);
                        @endphp


                        <button
                            type="button"
                            class="
                                btn
                                btn-soal
                                {{
                                    $sudahDijawab
                                        ? 'btn-success'
                                        : 'btn-outline-secondary'
                                }}
                            "
                            data-index="{{ $index }}"
                            data-soal-id="{{ $soal->id }}"
                        >

                            {{ $index + 1 }}

                        </button>

                    @endforeach

                </div>


                <hr class="my-4">


                {{-- KETERANGAN --}}
                <div class="small">

                    <div
                        class="
                            d-flex
                            align-items-center
                            mb-2
                        "
                    >

                        <span class="badge bg-success me-2">
                            &nbsp;
                        </span>

                        Sudah dijawab

                    </div>


                    <div
                        class="
                            d-flex
                            align-items-center
                        "
                    >

                        <span class="badge bg-secondary me-2">
                            &nbsp;
                        </span>

                        Belum dijawab

                    </div>

                </div>


                <hr class="my-4">


                {{-- PROGRESS --}}
                <div>

                    <div
                        class="
                            d-flex
                            justify-content-between
                            mb-2
                        "
                    >

                        <span class="text-secondary">
                            Terjawab
                        </span>


                        <strong id="jumlah-terjawab">

                            {{ $jawabanTersimpan->count() }}

                            /

                            {{ $soals->count() }}

                        </strong>

                    </div>


                    <div class="progress">

                        <div
                            id="progress-jawaban"
                            class="progress-bar"
                            style="
                                width:
                                {{
                                    $soals->count() > 0
                                        ? (
                                            $jawabanTersimpan->count()
                                            /
                                            $soals->count()
                                        ) * 100
                                        : 0
                                }}%;
                            "
                        ></div>

                    </div>

                </div>


                <button
                    type="button"
                    class="
                        btn
                        btn-success
                        w-100
                        mt-4
                        btn-buka-submit
                    "
                >

                    <i class="ti ti-circle-check me-1"></i>

                    Selesai Ujian

                </button>

            </div>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- FORM SUBMIT FINAL --}}
{{-- ========================================================= --}}

<form
    id="form-selesai"
    action="{{
        route(
            'cbt.siswa.pengerjaan.selesai',
            $pengerjaan
        )
    }}"
    method="POST"
    class="d-none"
>

    @csrf

</form>

@endsection



@push('styles')

<style>

    /*
     * Pilihan jawaban
     */

    .jawaban-option {
        cursor: pointer;
        transition:
            border-color .2s ease,
            background-color .2s ease,
            transform .15s ease;
    }


    .jawaban-option:hover {
        border-color:
            var(--tblr-primary) !important;

        background:
            rgba(
                var(--tblr-primary-rgb),
                .04
            );
    }


    .jawaban-option:has(
        .jawaban-radio:checked
    ) {
        border-color:
            var(--tblr-primary) !important;

        background:
            rgba(
                var(--tblr-primary-rgb),
                .08
            );
    }


    .jawaban-huruf {
        min-width: 24px;
    }


    /*
     * Navigasi soal
     */

    .btn-soal {
        padding-left: 0;
        padding-right: 0;
    }


    /*
     * Mobile
     */

    @media (max-width: 767.98px) {

        .soal-pertanyaan {
            font-size: 1.1rem !important;
            line-height: 1.7;
        }


        .jawaban-option {
            padding: 1rem !important;
        }


        #timer-container {
            width: 100%;
        }


        #timer {
            font-size: 1.5rem !important;
        }

    }

</style>

@endpush



@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | DATA UTAMA
        |--------------------------------------------------------------------------
        */

        const soalContainers =
            document.querySelectorAll(
                '.soal-container'
            );

        const tombolSoal =
            document.querySelectorAll(
                '.btn-soal'
            );

        const totalSoal =
            {{ $soals->count() }};


        let soalAktif = 0;

        let sedangSubmit = false;



        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN SOAL
        |--------------------------------------------------------------------------
        */

        function tampilkanSoal(index) {

            if (
                index < 0 ||
                index >= totalSoal
            ) {
                return;
            }


            soalContainers.forEach(
                function (container) {

                    container
                        .classList
                        .add('d-none');

                }
            );


            const target =
                document.querySelector(
                    '.soal-container[data-index="' +
                    index +
                    '"]'
                );


            if (target) {

                target
                    .classList
                    .remove('d-none');

            }


            soalAktif = index;


            window.scrollTo({
                top: 0,
                behavior: 'smooth',
            });

        }



        /*
        |--------------------------------------------------------------------------
        | NAVIGASI NOMOR SOAL
        |--------------------------------------------------------------------------
        */

        tombolSoal.forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        tampilkanSoal(
                            parseInt(
                                this.dataset.index
                            )
                        );

                    }
                );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | TOMBOL SEBELUMNYA
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.btn-sebelumnya'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            tampilkanSoal(
                                parseInt(
                                    this.dataset.index
                                ) - 1
                            );

                        }
                    );

                }
            );



        /*
        |--------------------------------------------------------------------------
        | TOMBOL SELANJUTNYA
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.btn-selanjutnya'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            tampilkanSoal(
                                parseInt(
                                    this.dataset.index
                                ) + 1
                            );

                        }
                    );

                }
            );



        /*
        |--------------------------------------------------------------------------
        | UPDATE PROGRESS
        |--------------------------------------------------------------------------
        */

        function updateProgress() {

            const terjawab =
                document.querySelectorAll(
                    '.jawaban-radio:checked'
                ).length;


            const jumlahElement =
                document.getElementById(
                    'jumlah-terjawab'
                );


            const progressElement =
                document.getElementById(
                    'progress-jawaban'
                );


            if (jumlahElement) {

                jumlahElement.textContent =
                    terjawab +
                    ' / ' +
                    totalSoal;

            }


            if (progressElement) {

                const persen =
                    totalSoal > 0
                        ? (
                            terjawab /
                            totalSoal
                        ) * 100
                        : 0;


                progressElement.style.width =
                    persen + '%';

            }

        }



        /*
        |--------------------------------------------------------------------------
        | AUTOSAVE JAWABAN
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.jawaban-radio'
            )
            .forEach(
                function (radio) {

                    radio.addEventListener(
                        'change',
                        async function () {

                            const soalId =
                                this.dataset.soalId;

                            const jawaban =
                                this.value;


                            /*
                             * Tandai nomor soal
                             * sebagai sudah dijawab.
                             */

                            const tombolNavigasi =
                                document.querySelector(
                                    '.btn-soal[data-soal-id="' +
                                    soalId +
                                    '"]'
                                );


                            if (tombolNavigasi) {

                                tombolNavigasi
                                    .classList
                                    .remove(
                                        'btn-outline-secondary'
                                    );

                                tombolNavigasi
                                    .classList
                                    .add(
                                        'btn-success'
                                    );

                            }


                            updateProgress();


                            try {

                                const response =
                                    await fetch(
                                        "{{
                                            route(
                                                'cbt.siswa.pengerjaan.jawaban',
                                                $pengerjaan
                                            )
                                        }}",
                                        {

                                            method:
                                                'POST',

                                            headers: {

                                                'Content-Type':
                                                    'application/json',

                                                'Accept':
                                                    'application/json',

                                                'X-CSRF-TOKEN':
                                                    document
                                                        .querySelector(
                                                            'meta[name="csrf-token"]'
                                                        )
                                                        .content,

                                            },


                                            body:
                                                JSON.stringify({

                                                    soal_id:
                                                        soalId,

                                                    jawaban:
                                                        jawaban,

                                                }),

                                        }
                                    );


                                const data =
                                    await response.json();


                                /*
                                 * Waktu sudah habis.
                                 */

                                if (data.expired) {

                                    submitOtomatis();

                                    return;

                                }


                                if (!response.ok) {

                                    throw new Error(
                                        data.message ||
                                        'Jawaban gagal disimpan.'
                                    );

                                }

                            }
                            catch (error) {

                                console.error(
                                    error
                                );


                                alert(
                                    'Jawaban belum berhasil disimpan. Periksa koneksi internet Anda.'
                                );

                            }

                        }
                    );

                }
            );



        /*
        |--------------------------------------------------------------------------
        | TIMER
        |--------------------------------------------------------------------------
        */

        const batasWaktu =
            new Date(
                "{{
                    $pengerjaan
                        ->batas_waktu
                        ->toIso8601String()
                }}"
            ).getTime();


        const timerElement =
            document.getElementById(
                'timer'
            );


        const timerContainer =
            document.getElementById(
                'timer-container'
            );


        function updateTimer() {

            const sekarang =
                new Date()
                    .getTime();


            const selisih =
                batasWaktu -
                sekarang;


            /*
             * Waktu habis.
             */

            if (selisih <= 0) {

                timerElement.textContent =
                    '00:00:00';


                submitOtomatis();

                return;

            }


            const jam =
                Math.floor(
                    selisih /
                    (
                        1000 *
                        60 *
                        60
                    )
                );


            const menit =
                Math.floor(
                    (
                        selisih %
                        (
                            1000 *
                            60 *
                            60
                        )
                    )
                    /
                    (
                        1000 *
                        60
                    )
                );


            const detik =
                Math.floor(
                    (
                        selisih %
                        (
                            1000 *
                            60
                        )
                    )
                    /
                    1000
                );


            timerElement.textContent =

                String(jam)
                    .padStart(
                        2,
                        '0'
                    )

                + ':'

                + String(menit)
                    .padStart(
                        2,
                        '0'
                    )

                + ':'

                + String(detik)
                    .padStart(
                        2,
                        '0'
                    );


            /*
             * Peringatan 5 menit.
             */

            if (
                selisih <=
                5 * 60 * 1000
            ) {

                timerContainer
                    .classList
                    .add(
                        'border-danger',
                        'text-danger'
                    );

            }

        }


        updateTimer();


        const timerInterval =
            setInterval(
                updateTimer,
                1000
            );



        /*
        |--------------------------------------------------------------------------
        | SUBMIT OTOMATIS
        |--------------------------------------------------------------------------
        */

        function submitOtomatis() {

            if (sedangSubmit) {
                return;
            }


            sedangSubmit = true;


            clearInterval(
                timerInterval
            );


            document
                .getElementById(
                    'form-selesai'
                )
                .submit();

        }



        /*
        |--------------------------------------------------------------------------
        | SUBMIT MANUAL
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.btn-buka-submit'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const terjawab =
                                document
                                    .querySelectorAll(
                                        '.jawaban-radio:checked'
                                    )
                                    .length;


                            const belumDijawab =
                                totalSoal -
                                terjawab;


                            let pesan =
                                'Apakah Anda yakin ingin menyelesaikan ujian?';


                            if (belumDijawab > 0) {

                                pesan =

                                    'Masih ada ' +

                                    belumDijawab +

                                    ' soal yang belum dijawab.\n\n' +

                                    'Apakah Anda tetap ingin menyelesaikan ujian?';

                            }


                            if (confirm(pesan)) {

                                submitOtomatis();

                            }

                        }
                    );

                }
            );


        /*
         * Sesuaikan progress awal.
         */

        updateProgress();

    }
);

</script>

@endpush