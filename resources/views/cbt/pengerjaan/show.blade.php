@extends('layouts.exam')

@section('title', 'Pengerjaan Ujian')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | DATA UJIAN
    |--------------------------------------------------------------------------
    |
    | Variabel $soals sudah dikirim dari controller
    | berdasarkan urutan_soal milik pengerjaan siswa.
    |
    | Jangan gunakan lagi:
    |
    | $soals = $ujian->bankSoal->soals;
    |
    | karena itu akan mengembalikan urutan asli.
    |
    */

    $ujian = $pengerjaan->ujian;


    /*
     * Jawaban siswa yang sudah tersimpan.
     */
    $jawabanTersimpan =
        $pengerjaan
            ->jawabans
            ->keyBy('soal_id');


    /*
     * Urutan pilihan jawaban permanen
     * milik siswa ini.
     */
    $urutanJawaban =
        $pengerjaan
            ->urutan_jawaban
        ?? [];

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

                /*
                |--------------------------------------------------------------------------
                | JAWABAN YANG SUDAH TERSIMPAN
                |--------------------------------------------------------------------------
                |
                | Jawaban yang disimpan tetap menggunakan
                | huruf ASLI dari bank soal.
                |
                */

                $jawabanAsli =
                    $jawabanTersimpan
                        ->get($soal->id)
                        ?->jawaban;


                /*
                |--------------------------------------------------------------------------
                | AMBIL URUTAN JAWABAN SISWA
                |--------------------------------------------------------------------------
                */

                $urutanPilihan =
                    $urutanJawaban[
                        (string) $soal->id
                    ]
                    ?? [
                        'A',
                        'B',
                        'C',
                        'D',
                        'E',
                    ];


                /*
                 * Huruf yang ditampilkan kepada siswa.
                 */
                $hurufTampilan = [
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                ];


                /*
                |--------------------------------------------------------------------------
                | SUSUN PILIHAN JAWABAN
                |--------------------------------------------------------------------------
                |
                | Contoh urutan:
                |
                | ['C', 'A', 'D', 'B']
                |
                | Maka:
                |
                | Tampilan A = pilihan C asli
                | Tampilan B = pilihan A asli
                | Tampilan C = pilihan D asli
                | Tampilan D = pilihan B asli
                |
                */

                $pilihanJawaban = [];


                foreach (
                    $urutanPilihan
                    as $indexPilihan => $hurufAsli
                ) {

                    /*
                     * Pastikan index huruf tampilan tersedia.
                     */
                    if (
                        ! isset(
                            $hurufTampilan[
                                $indexPilihan
                            ]
                        )
                    ) {
                        continue;
                    }


                    $hurufDisplay =
                        $hurufTampilan[
                            $indexPilihan
                        ];


                    /*
                     * Contoh:
                     *
                     * hurufAsli = C
                     *
                     * menjadi:
                     *
                     * pilihan_c
                     */
                    $kolom =
                        'pilihan_' .
                        strtolower(
                            $hurufAsli
                        );


                    /*
                     * Lewati pilihan kosong.
                     */
                    if (
                        is_null(
                            $soal->{$kolom}
                        )
                        ||
                        trim(
                            (string)
                            $soal->{$kolom}
                        ) === ''
                    ) {
                        continue;
                    }


                    $pilihanJawaban[] = [

                        /*
                         * Huruf yang terlihat siswa.
                         */
                        'huruf_tampilan' =>
                            $hurufDisplay,


                        /*
                         * Huruf asli dari database.
                         *
                         * Ini yang dikirim ke server.
                         */
                        'huruf_asli' =>
                            $hurufAsli,


                        /*
                         * Isi jawaban.
                         */
                        'teks' =>
                            $soal->{$kolom},

                    ];

                }

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

                            {{-- Nomor mengikuti urutan acak --}}
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


                    {{-- ================================================= --}}
                    {{-- PILIHAN JAWABAN YANG SUDAH DIACAK --}}
                    {{-- ================================================= --}}

                    <div class="jawaban-list">

                        @foreach(
                            $pilihanJawaban
                            as $pilihan
                        )

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

                                    value="{{
                                        $pilihan[
                                            'huruf_asli'
                                        ]
                                    }}"

                                    data-soal-id="{{
                                        $soal->id
                                    }}"

                                    @checked(
                                        $jawabanAsli ===
                                        $pilihan[
                                            'huruf_asli'
                                        ]
                                    )
                                >


                                {{-- Huruf yang terlihat siswa --}}
                                <span
                                    class="
                                        jawaban-huruf
                                        fw-bold
                                    "
                                >

                                    {{
                                        $pilihan[
                                            'huruf_tampilan'
                                        ]
                                    }}.

                                </span>


                                {{-- Isi pilihan --}}
                                <span class="flex-fill">

                                    {{
                                        $pilihan[
                                            'teks'
                                        ]
                                    }}

                                </span>

                            </label>

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


                        @if(
                            $index <
                            $soals->count() - 1
                        )

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

                    @foreach(
                        $soals
                        as $index => $soal
                    )

                        @php

                            $sudahDijawab =
                                $jawabanTersimpan
                                    ->has(
                                        $soal->id
                                    );

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


{{-- ========================================================= --}}
{{-- OVERLAY MASUK MODE UJIAN --}}
{{-- ========================================================= --}}

<div
    id="overlayModeUjian"
    class="
        position-fixed
        top-0
        start-0
        w-100
        h-100
        bg-white
    "
    style="
        z-index: 99998;
        display:
        {{
            $pengerjaan->status === 'diblokir'
                ? 'none'
                : 'flex'
        }};
    "
>

    <div
        class="
            d-flex
            align-items-center
            justify-content-center
            w-100
            h-100
            p-4
        "
    >

        <div
            class="text-center"
            style="max-width: 560px;"
        >

            <span
                class="
                    avatar
                    avatar-xl
                    bg-blue-lt
                    mb-4
                "
            >

                <i class="ti ti-shield-lock"></i>

            </span>


            <h1 class="mb-3">
                Mode Ujian
            </h1>


            <p class="text-secondary">

                Sebelum memulai pengerjaan,
                aktifkan Mode Ujian.

                Halaman akan masuk ke layar penuh
                dan sistem pengawasan akan diaktifkan.

            </p>


            <div
                class="
                    alert
                    alert-warning
                    text-start
                    mt-4
                "
            >

                <div class="fw-bold mb-2">
                    Selama ujian berlangsung:
                </div>


                <ul class="mb-0 ps-3">

                    <li>
                        Jangan berpindah tab.
                    </li>

                    <li>
                        Jangan membuka aplikasi lain.
                    </li>

                    <li>
                        Jangan keluar dari layar penuh.
                    </li>

                    <li>
                        Maksimal 3 pelanggaran.
                    </li>

                </ul>

            </div>


            <div
                class="
                    alert
                    alert-danger
                    text-start
                "
            >

                Setelah mencapai 3 pelanggaran,
                ujian akan diblokir dan hanya operator
                yang dapat membuka blokir pengerjaan.

            </div>


            <button
                type="button"
                id="btnMasukModeUjian"
                class="
                    btn
                    btn-primary
                    btn-lg
                    w-100
                    mt-3
                "
            >

                <i class="ti ti-maximize me-2"></i>

                Masuk Mode Ujian

            </button>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL PERINGATAN PELANGGARAN --}}
{{-- ========================================================= --}}

<div
    class="modal modal-blur fade"
    id="modalPelanggaran"
    tabindex="-1"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h3 class="modal-title text-danger">

                    <i class="ti ti-alert-triangle me-2"></i>

                    Peringatan Ujian

                </h3>

            </div>


            <div class="modal-body text-center py-4">

                <span
                    class="
                        avatar
                        avatar-xl
                        bg-danger-lt
                        mb-3
                    "
                >

                    <i class="ti ti-shield-exclamation"></i>

                </span>


                <h2 id="judulPelanggaran">

                    Pelanggaran Terdeteksi

                </h2>


                <p
                    class="text-secondary"
                    id="pesanPelanggaran"
                >

                    Anda terdeteksi meninggalkan
                    halaman ujian.

                </p>


                <div class="alert alert-warning mt-3">

                    <strong id="jumlahPelanggaran">

                        Peringatan 1 dari 3

                    </strong>


                    <div
                        class="small mt-1"
                        id="sisaPelanggaran"
                    >

                        Anda masih memiliki 2 kesempatan.

                    </div>

                </div>


                <p class="text-secondary small mb-0">

                    Jangan berpindah tab,
                    meminimalkan browser,
                    atau keluar dari mode layar penuh
                    selama ujian.

                </p>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-danger w-100"
                    id="btnLanjutUjian"
                >

                    <i class="ti ti-player-play me-2"></i>

                    Saya Mengerti, Lanjutkan Ujian

                </button>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- OVERLAY BLOKIR UJIAN --}}
{{-- ========================================================= --}}

<div
    id="overlayBlokir"
    class="
        position-fixed
        top-0
        start-0
        w-100
        h-100
        bg-white
    "
    style="
        z-index: 99999;
        display:
        {{
            $pengerjaan->status === 'diblokir'
                ? 'block'
                : 'none'
        }};
    "
>

    <div
        class="
            d-flex
            align-items-center
            justify-content-center
            h-100
            p-4
        "
    >

        <div
            class="text-center"
            style="max-width: 520px;"
        >

            <span
                class="
                    avatar
                    avatar-xl
                    bg-danger-lt
                    mb-4
                "
            >

                <i class="ti ti-lock"></i>

            </span>


            <h1 class="mb-3">
                Ujian Diblokir
            </h1>


            <p class="text-secondary">

                Anda telah mencapai batas maksimal
                pelanggaran selama ujian.

            </p>


            <div class="alert alert-danger mt-4">

                Pengerjaan ujian telah dikunci.

                Hubungi operator untuk membuka blokir
                agar Anda dapat melanjutkan ujian.

            </div>


            <a
                href="{{ route('cbt.siswa.index') }}"
                class="btn btn-outline-secondary mt-3"
            >

                Kembali ke Daftar Ujian

            </a>

        </div>

    </div>

</div>

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
                                    * Pengerjaan telah diblokir
                                    * oleh sistem.
                                    */
                                    if (
                                        data.blocked === true
                                    ) {

                                        window.location.reload();

                                        return;

                                    }


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

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | KONFIGURASI
    |--------------------------------------------------------------------------
    */

    const pelanggaranUrl = @js(
        route(
            'cbt.siswa.pengerjaan.pelanggaran',
            $pengerjaan
        )
    );

    const csrfToken = @js(csrf_token());

    const pengerjaanDiblokir = @js(
        $pengerjaan->status === 'diblokir'
    );


    /*
    |--------------------------------------------------------------------------
    | STATUS PENGAWASAN
    |--------------------------------------------------------------------------
    */

    let ujianAktif = false;

    let sedangMengirim = false;

    let modalSedangTerbuka = false;

    let sedangMasukFullscreen = false;

    let waktuPelanggaranTerakhir = 0;

    let pelanggaranTertunda = null;

    /*
     * Digunakan untuk mencegah satu aktivitas
     * menghasilkan beberapa pelanggaran sekaligus.
     *
     * Contoh:
     * Alt + Tab dapat memicu:
     * - blur
     * - visibilitychange
     * - fullscreenchange
     */
    const cooldownPelanggaran = 1500;


    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

    const overlayModeUjian =
        document.getElementById(
            'overlayModeUjian'
        );

    const btnMasukModeUjian =
        document.getElementById(
            'btnMasukModeUjian'
        );

    const modalElement =
        document.getElementById(
            'modalPelanggaran'
        );

    const overlayBlokir =
        document.getElementById(
            'overlayBlokir'
        );

    const jumlahElement =
        document.getElementById(
            'jumlahPelanggaran'
        );

    const sisaElement =
        document.getElementById(
            'sisaPelanggaran'
        );

    const pesanElement =
        document.getElementById(
            'pesanPelanggaran'
        );

    const btnLanjut =
        document.getElementById(
            'btnLanjutUjian'
        );


    function bukaModalPelanggaran()
{
    if (! modalElement) {
        return;
    }

    modalElement.classList.add('show');

    modalElement.style.display =
        'block';

    modalElement.removeAttribute(
        'aria-hidden'
    );

    modalElement.setAttribute(
        'aria-modal',
        'true'
    );

    document.body.classList.add(
        'modal-open'
    );
}

/*
|--------------------------------------------------------------------------
MODAL PELANGGARAN TERTUTUP
|--------------------------------------------------------------------------
*/


function tutupModalPelanggaran()
{
    if (! modalElement) {
        return;
    }

    modalElement.classList.remove(
        'show'
    );

    modalElement.style.display =
        'none';

    modalElement.setAttribute(
        'aria-hidden',
        'true'
    );

    modalElement.removeAttribute(
        'aria-modal'
    );

    document.body.classList.remove(
        'modal-open'
    );
}


    /*
    |--------------------------------------------------------------------------
    | CEK FULLSCREEN
    |--------------------------------------------------------------------------
    */

    function sedangFullscreen()
    {
        return !!(
            document.fullscreenElement ||
            document.webkitFullscreenElement
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS AWAL PENGERJAAN
    |--------------------------------------------------------------------------
    */

    if (pengerjaanDiblokir) {

        ujianAktif = false;

        if (overlayModeUjian) {

            overlayModeUjian.style.display =
                'none';

        }

        if (overlayBlokir) {

            overlayBlokir.style.display =
                'flex';

        }

        document.body.style.overflow =
            'hidden';

    } else {

        /*
         * Sebelum siswa menekan tombol
         * Masuk Mode Ujian.
         */
        document.body.style.overflow =
            'hidden';

    }


    /*
    |--------------------------------------------------------------------------
    | MASUK FULLSCREEN
    |--------------------------------------------------------------------------
    */

    async function masukFullscreen()
    {
        /*
         * Jika sudah fullscreen,
         * tidak perlu request lagi.
         */
        if (sedangFullscreen()) {

            return true;

        }


        const element =
            document.documentElement;


        sedangMasukFullscreen =
            true;


        try {

            /*
             * Chrome, Edge, Firefox.
             */
            if (element.requestFullscreen) {

                await element.requestFullscreen();

                return true;

            }


            /*
             * Safari lama.
             */
            if (element.webkitRequestFullscreen) {

                element.webkitRequestFullscreen();

                return true;

            }


            sedangMasukFullscreen =
                false;


            return false;

        } catch (error) {

            sedangMasukFullscreen =
                false;


            console.error(
                'Gagal masuk fullscreen:',
                error
            );


            return false;

        }
    }


    /*
    |--------------------------------------------------------------------------
    | AKTIFKAN MODE UJIAN
    |--------------------------------------------------------------------------
    */

    function aktifkanModeUjian()
    {
        /*
         * Hilangkan halaman pembuka.
         */
        if (overlayModeUjian) {

            overlayModeUjian.style.display =
                'none';

        }


        /*
         * Aktifkan scroll halaman soal.
         */
        document.body.style.overflow =
            '';


        /*
         * Tunggu event fullscreen awal selesai.
         */
        setTimeout(
            function () {

                sedangMasukFullscreen =
                    false;


                /*
                 * Pelanggaran pertama harus
                 * langsung bisa tercatat.
                 */
                waktuPelanggaranTerakhir =
                    0;


                ujianAktif =
                    true;


                console.log(
                    'Pengawasan CBT aktif.'
                );

            },
            500
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TOMBOL MASUK MODE UJIAN
    |--------------------------------------------------------------------------
    */

    if (btnMasukModeUjian) {

        btnMasukModeUjian.addEventListener(
            'click',
            async function () {

                if (pengerjaanDiblokir) {

                    return;

                }


                btnMasukModeUjian.disabled =
                    true;


                btnMasukModeUjian.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Mengaktifkan Mode Ujian...';


                /*
                 * Fullscreen harus dipanggil
                 * langsung dari aksi klik siswa.
                 */
                const berhasil =
                    await masukFullscreen();


                if (! berhasil) {

                    btnMasukModeUjian.disabled =
                        false;


                    btnMasukModeUjian.innerHTML =
                        '<i class="ti ti-maximize me-2"></i>' +
                        'Masuk Mode Ujian';


                    alert(
                        'Mode layar penuh tidak dapat diaktifkan. ' +
                        'Pastikan browser mengizinkan fullscreen.'
                    );


                    return;

                }


                aktifkanModeUjian();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PESAN BERDASARKAN JENIS PELANGGARAN
    |--------------------------------------------------------------------------
    */

    function getPesanPelanggaran(jenis)
    {
        switch (jenis) {

            case 'pindah_tab':

                return 'Anda terdeteksi meninggalkan tab halaman ujian.';


            case 'kehilangan_fokus':

                return 'Anda terdeteksi berpindah ke jendela atau aplikasi lain.';


            case 'keluar_fullscreen':

                return 'Anda terdeteksi keluar dari Mode Ujian atau layar penuh.';


            default:

                return 'Aktivitas yang tidak diperbolehkan terdeteksi.';

        }
    }


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN MODAL PERINGATAN
    |--------------------------------------------------------------------------
    */

    function tampilkanPeringatan(data)
    {
        /*
         * Matikan pengawasan sementara.
         *
         * Ini mencegah modal sendiri
         * menghasilkan pelanggaran baru.
         */
        ujianAktif =
            false;


        modalSedangTerbuka =
            true;


        if (jumlahElement) {

            jumlahElement.textContent =
                'Peringatan ' +
                data.jumlah_pelanggaran +
                ' dari 3';

        }


        if (sisaElement) {

            if (
                Number(
                    data.sisa_pelanggaran
                ) === 1
            ) {

                sisaElement.textContent =
                    'Ini adalah peringatan terakhir Anda.';

            } else {

                sisaElement.textContent =
                    'Anda masih memiliki ' +
                    data.sisa_pelanggaran +
                    ' kesempatan.';

            }

        }


        if (pesanElement) {

            pesanElement.textContent =
                getPesanPelanggaran(
                    data.jenis
                );

        }


        bukaModalPelanggaran();
    }


    /*
    |--------------------------------------------------------------------------
    | PROSES PELANGGARAN TERTUNDA
    |--------------------------------------------------------------------------
    |
    | Ketika siswa pergi dari halaman:
    |
    | 1. Pelanggaran langsung dikirim ke server.
    | 2. Response disimpan.
    | 3. Ketika siswa kembali ke halaman,
    |    modal peringatan ditampilkan.
    |
    */

    function prosesPelanggaranTertunda()
    {
        if (! pelanggaranTertunda) {

            return;

        }


        /*
         * Jangan tampilkan modal
         * selama halaman masih tersembunyi.
         */
        if (document.hidden) {

            return;

        }


        const data =
            pelanggaranTertunda;


        pelanggaranTertunda =
            null;


        setTimeout(
            function () {

                /*
                 * Pastikan siswa masih berada
                 * pada halaman ujian.
                 */
                if (document.hidden) {

                    pelanggaranTertunda =
                        data;

                    return;

                }


                tampilkanPeringatan(
                    data
                );

            },
            250
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BLOKIR UJIAN
    |--------------------------------------------------------------------------
    */

    function blokirUjian()
    {
        ujianAktif =
            false;


        modalSedangTerbuka =
            true;


        pelanggaranTertunda =
            null;


        tutupModalPelanggaran();


        if (overlayModeUjian) {

            overlayModeUjian.style.display =
                'none';

        }


        if (overlayBlokir) {

            overlayBlokir.style.display =
                'flex';

        }


        document.body.style.overflow =
            'hidden';
    }


    /*
    |--------------------------------------------------------------------------
    | KIRIM PELANGGARAN KE SERVER
    |--------------------------------------------------------------------------
    */

    async function catatPelanggaran(jenis)
{
    if (! ujianAktif) {
        return;
    }


    if (modalSedangTerbuka) {
        return;
    }


    /*
     * Cegah blur + visibilitychange +
     * fullscreenchange dihitung terpisah.
     */
    const sekarang =
        Date.now();


    if (
        sekarang -
        waktuPelanggaranTerakhir
        <
        cooldownPelanggaran
    ) {
        return;
    }


    /*
     * Kunci waktu langsung saat event pertama masuk.
     */
    waktuPelanggaranTerakhir =
        sekarang;


    if (sedangMengirim) {
        return;
    }


    sedangMengirim =
        true;


    console.log(
        'Pelanggaran terdeteksi:',
        jenis
    );


    // LANJUTKAN try { ... } MILIK KAMU


        try {

            const response =
                await fetch(
                    pelanggaranUrl,
                    {

                        method:
                            'POST',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                csrfToken,

                        },

                        body:
                            JSON.stringify({

                                jenis:
                                    jenis,

                            }),

                        /*
                         * Membantu request tetap dikirim
                         * ketika halaman kehilangan fokus.
                         */
                        keepalive:
                            true,

                    }
                );


            /*
             * Session / CSRF habis.
             */
            if (
                response.status === 419
            ) {

                window.location.reload();

                return;

            }


            if (! response.ok) {

                const errorText =
                    await response.text();


                console.error(
                    'Gagal mencatat pelanggaran:',
                    response.status,
                    errorText
                );


                return;

            }


            const data =
                await response.json();


            console.log(
                'Pelanggaran berhasil dicatat:',
                data
            );


            /*
             * Pelanggaran ketiga.
             */
            if (
                data.status ===
                'diblokir'
            ) {

                /*
                 * Jika siswa masih di luar halaman,
                 * overlay akan tetap tampil ketika
                 * halaman dibuka kembali.
                 */
                blokirUjian();


                return;

            }


            /*
             * Pelanggaran pertama / kedua.
             */
            if (
                data.status ===
                'peringatan'
            ) {

                /*
                 * Jika halaman sedang tersembunyi,
                 * simpan peringatannya.
                 */
                if (document.hidden) {

                    pelanggaranTertunda =
                        data;


                    return;

                }


                /*
                 * Jika halaman masih terlihat,
                 * tampilkan langsung.
                 */
                tampilkanPeringatan(
                    data
                );

            }

        } catch (error) {

            console.error(
                'Terjadi kesalahan saat mencatat pelanggaran:',
                error
            );

        } finally {

            sedangMengirim =
                false;

        }
    }


    /*
    |--------------------------------------------------------------------------
    | SISWA MENINGGALKAN / KEMBALI KE TAB
    |--------------------------------------------------------------------------
    |
    | Desktop:
    | - pindah tab
    | - membuka tab baru
    | - minimize browser
    |
    | Mobile:
    | - pindah aplikasi
    | - menekan Home
    | - berpindah tab browser
    |
    */

    /*
|--------------------------------------------------------------------------
| DETEKSI PINDAH TAB
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'visibilitychange',
    function () {

        console.log(
            'Visibility:',
            document.visibilityState
        );


        /*
         * Siswa meninggalkan tab.
         */
        if (
            document.visibilityState ===
            'hidden'
        ) {

            catatPelanggaran(
                'pindah_tab'
            );


            return;

        }


        /*
         * Siswa kembali ke tab ujian.
         */
        if (
            document.visibilityState ===
            'visible'
        ) {

            prosesPelanggaranTertunda();

        }

    }
);

    /*
|--------------------------------------------------------------------------
| WINDOW KEHILANGAN FOKUS
|--------------------------------------------------------------------------
|
| Mendeteksi:
|
| - Alt + Tab
| - klik aplikasi lain
| - pindah window browser
| - browser kehilangan fokus
|
*/

window.addEventListener(
    'blur',
    function () {

        console.log(
            'Window kehilangan fokus.'
        );

        catatPelanggaran(
            'kehilangan_fokus'
        );

    }
);


/*
|--------------------------------------------------------------------------
| WINDOW KEMBALI AKTIF
|--------------------------------------------------------------------------
*/

window.addEventListener(
    'focus',
    function () {

        console.log(
            'Window kembali aktif.'
        );


        prosesPelanggaranTertunda();

    }
);


    /*
    |--------------------------------------------------------------------------
    | KELUAR FULLSCREEN
    |--------------------------------------------------------------------------
    */

    function handleFullscreenChange()
    {
        /*
         * Abaikan perubahan ketika sistem
         * sedang memasukkan siswa ke fullscreen.
         */
        if (sedangMasukFullscreen) {

            return;

        }


        if (
            ! ujianAktif ||
            modalSedangTerbuka
        ) {

            return;

        }


        /*
         * Siswa keluar fullscreen.
         */
        if (! sedangFullscreen()) {

            catatPelanggaran(
                'keluar_fullscreen'
            );

        }
    }


    document.addEventListener(
        'fullscreenchange',
        handleFullscreenChange
    );


    document.addEventListener(
        'webkitfullscreenchange',
        handleFullscreenChange
    );


    /*
    |--------------------------------------------------------------------------
    | LANJUTKAN UJIAN SETELAH PERINGATAN
    |--------------------------------------------------------------------------
    */

    if (btnLanjut) {

        btnLanjut.addEventListener(
            'click',
            async function () {

                btnLanjut.disabled =
                    true;


                btnLanjut.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Mengaktifkan Mode Ujian...';


                /*
                 * Siswa harus masuk fullscreen
                 * kembali sebelum melanjutkan.
                 */
                const berhasil =
                    await masukFullscreen();


                if (! berhasil) {

                    btnLanjut.disabled =
                        false;


                    btnLanjut.innerHTML =
                        '<i class="ti ti-player-play me-2"></i>' +
                        'Saya Mengerti, Lanjutkan Ujian';


                    alert(
                        'Anda harus kembali ke Mode Ujian untuk melanjutkan.'
                    );


                    return;

                }


                /*
                 * Tutup modal.
                 */
                tutupModalPelanggaran();


                btnLanjut.disabled =
                    false;


                btnLanjut.innerHTML =
                    '<i class="ti ti-player-play me-2"></i>' +
                    'Saya Mengerti, Lanjutkan Ujian';


                /*
                 * Tunggu fullscreen stabil.
                 */
                setTimeout(
                    function () {

                        modalSedangTerbuka =
                            false;


                        sedangMasukFullscreen =
                            false;


                        /*
                         * Reset agar pelanggaran berikutnya
                         * dapat langsung tercatat.
                         */
                        waktuPelanggaranTerakhir =
                            0;


                        ujianAktif =
                            true;


                        console.log(
                            'Pengawasan CBT aktif kembali.'
                        );

                    },
                    500
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DEBUG
    |--------------------------------------------------------------------------
    */

    console.log(
        'Sistem pengawasan CBT berhasil dimuat.'
    );

});
</script>

@endpush