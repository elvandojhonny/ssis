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


@endphp


<div class="row g-4">

    {{-- ===================================================== --}}
    {{-- AREA SOAL --}}
    {{-- ===================================================== --}}

    <div class="col-lg-9 order-2 order-lg-1">

        {{-- HEADER UJIAN --}}
<div class="card mb-3 exam-info-card">

    <div class="card-body">

        <div
            class="
                d-flex
                justify-content-between
                align-items-center
                gap-3
            "
        >

            <div class="min-w-0">

                <div class="page-pretitle">
                    Computer Based Test
                </div>

                <h2 class="mb-1 exam-title">
                    {{ $ujian->judul }}
                </h2>

                <div class="text-secondary exam-meta">

                    {{ $ujian->kelas->nama ?? '-' }}

                    <span class="mx-1">•</span>

                    {{ $soals->count() }} Soal

                </div>

            </div>


            {{-- TIMER DESKTOP --}}
            <div
                id="timer-container"
                class="
                    d-none
                    d-lg-block
                    border
                    rounded
                    px-3
                    py-2
                    text-center
                    flex-shrink-0
                "
            >

                <div class="text-secondary small">
                    Sisa Waktu
                </div>

                <div
                    id="timer"
                    class="fw-bold fs-3"
                >
                    --:--:--
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

                        @if($soal->gambar_pertanyaan)
                            <div class="soal-gambar mt-4 text-center">

                                <img
                                    src="{{ asset('storage/' . $soal->gambar_pertanyaan) }}"
                                    alt="Gambar soal"
                                    class="img-fluid rounded gambar-ujian-clickable"
                                    data-gambar="{{ asset('storage/' . $soal->gambar_pertanyaan) }}"
                                    data-judul="Gambar Soal {{ $index + 1 }}"
                                    loading="lazy"
                                >

                            </div>
                        @endif

                    </div>


                    {{-- ================================================= --}}
{{-- PILIHAN JAWABAN --}}
{{-- ================================================= --}}

@if($soal->tipe === 'pilihan_ganda')

    <div class="jawaban-list">

        {{-- ================================================= --}}
        {{-- PILIHAN A --}}
        {{-- ================================================= --}}

        @if(
            (
                !is_null($soal->pilihan_a) &&
                trim((string) $soal->pilihan_a) !== ''
            )
            ||
            !empty($soal->gambar_a)
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
                    value="A"
                    data-soal-id="{{ $soal->id }}"

                    @checked(
                        strtoupper(
                            trim(
                                (string) $jawabanAsli
                            )
                        ) === 'A'
                    )
                >

                <span
                    class="
                        jawaban-huruf
                        fw-bold
                    "
                >
                    A.
                </span>


                <span class="flex-fill">

                    <div class="jawaban-teks">

                        {!! nl2br(
                            e($soal->pilihan_a)
                        ) !!}

                    </div>


                    {{-- GAMBAR PILIHAN A --}}

                    @if(!empty($soal->gambar_a))

                        <div class="jawaban-gambar mt-3">

                            <img
                                src="{{ asset('storage/' . $soal->gambar_a) }}"
                                alt="Gambar pilihan A"
                                class="
                                    img-fluid
                                    rounded
                                    border
                                    gambar-ujian-clickable
                                "
                                data-gambar="{{ asset('storage/' . $soal->gambar_a) }}"
                                data-judul="Gambar Pilihan A"
                                loading="lazy"
                            >

                        </div>

                    @endif

                </span>

            </label>

        @endif


        {{-- ================================================= --}}
        {{-- PILIHAN B --}}
        {{-- ================================================= --}}

        @if(
            (
                !is_null($soal->pilihan_b) &&
                trim((string) $soal->pilihan_b) !== ''
            )
            ||
            !empty($soal->gambar_b)
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
                    value="B"
                    data-soal-id="{{ $soal->id }}"

                    @checked(
                        strtoupper(
                            trim(
                                (string) $jawabanAsli
                            )
                        ) === 'B'
                    )
                >

                <span
                    class="
                        jawaban-huruf
                        fw-bold
                    "
                >
                    B.
                </span>


                <span class="flex-fill">

                    <div class="jawaban-teks">

                        {!! nl2br(
                            e($soal->pilihan_b)
                        ) !!}

                    </div>


                    {{-- GAMBAR PILIHAN B --}}

                    @if(!empty($soal->gambar_b))

                        <div class="jawaban-gambar mt-3">

                            <img
                                src="{{ asset('storage/' . $soal->gambar_b) }}"
                                alt="Gambar pilihan B"
                                class="
                                    img-fluid
                                    rounded
                                    border
                                    gambar-ujian-clickable
                                "
                                data-gambar="{{ asset('storage/' . $soal->gambar_b) }}"
                                data-judul="Gambar Pilihan B"
                                loading="lazy"
                            >

                        </div>

                    @endif

                </span>

            </label>

        @endif


        {{-- ================================================= --}}
        {{-- PILIHAN C --}}
        {{-- ================================================= --}}

        @if(
            (
                !is_null($soal->pilihan_c) &&
                trim((string) $soal->pilihan_c) !== ''
            )
            ||
            !empty($soal->gambar_c)
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
                    value="C"
                    data-soal-id="{{ $soal->id }}"

                    @checked(
                        strtoupper(
                            trim(
                                (string) $jawabanAsli
                            )
                        ) === 'C'
                    )
                >

                <span
                    class="
                        jawaban-huruf
                        fw-bold
                    "
                >
                    C.
                </span>


                <span class="flex-fill">

                    <div class="jawaban-teks">

                        {!! nl2br(
                            e($soal->pilihan_c)
                        ) !!}

                    </div>


                    {{-- GAMBAR PILIHAN C --}}

                    @if(!empty($soal->gambar_c))

                        <div class="jawaban-gambar mt-3">

                            <img
                                src="{{ asset('storage/' . $soal->gambar_c) }}"
                                alt="Gambar pilihan C"
                                class="
                                    img-fluid
                                    rounded
                                    border
                                    gambar-ujian-clickable
                                "
                                data-gambar="{{ asset('storage/' . $soal->gambar_c) }}"
                                data-judul="Gambar Pilihan C"
                                loading="lazy"
                            >

                        </div>

                    @endif

                </span>

            </label>

        @endif


        {{-- ================================================= --}}
        {{-- PILIHAN D --}}
        {{-- ================================================= --}}

        @if(
            (
                !is_null($soal->pilihan_d) &&
                trim((string) $soal->pilihan_d) !== ''
            )
            ||
            !empty($soal->gambar_d)
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
                    value="D"
                    data-soal-id="{{ $soal->id }}"

                    @checked(
                        strtoupper(
                            trim(
                                (string) $jawabanAsli
                            )
                        ) === 'D'
                    )
                >

                <span
                    class="
                        jawaban-huruf
                        fw-bold
                    "
                >
                    D.
                </span>


                <span class="flex-fill">

                    <div class="jawaban-teks">

                        {!! nl2br(
                            e($soal->pilihan_d)
                        ) !!}

                    </div>


                    {{-- GAMBAR PILIHAN D --}}

                    @if(!empty($soal->gambar_d))

                        <div class="jawaban-gambar mt-3">

                            <img
                                src="{{ asset('storage/' . $soal->gambar_d) }}"
                                alt="Gambar pilihan D"
                                class="
                                    img-fluid
                                    rounded
                                    border
                                    gambar-ujian-clickable
                                "
                                data-gambar="{{ asset('storage/' . $soal->gambar_d) }}"
                                data-judul="Gambar Pilihan D"
                                loading="lazy"
                            >

                        </div>

                    @endif

                </span>

            </label>

        @endif


        {{-- ================================================= --}}
        {{-- PILIHAN E --}}
        {{-- ================================================= --}}

        @if(
            (
                !is_null($soal->pilihan_e) &&
                trim((string) $soal->pilihan_e) !== ''
            )
            ||
            !empty($soal->gambar_e)
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
                    value="E"
                    data-soal-id="{{ $soal->id }}"

                    @checked(
                        strtoupper(
                            trim(
                                (string) $jawabanAsli
                            )
                        ) === 'E'
                    )
                >

                <span
                    class="
                        jawaban-huruf
                        fw-bold
                    "
                >
                    E.
                </span>


                <span class="flex-fill">

                    <div class="jawaban-teks">

                        {!! nl2br(
                            e($soal->pilihan_e)
                        ) !!}

                    </div>


                    {{-- GAMBAR PILIHAN E --}}

                    @if(!empty($soal->gambar_e))

                        <div class="jawaban-gambar mt-3">

                            <img
                                src="{{ asset('storage/' . $soal->gambar_e) }}"
                                alt="Gambar pilihan E"
                                class="
                                    img-fluid
                                    rounded
                                    border
                                    gambar-ujian-clickable
                                "
                                data-gambar="{{ asset('storage/' . $soal->gambar_e) }}"
                                data-judul="Gambar Pilihan E"
                                loading="lazy"
                            >

                        </div>

                    @endif

                </span>

            </label>

        @endif

    </div>

@else

                    <div class="mt-3">

                        <label class="form-label fw-semibold">

                            Jawaban Essay

                        </label>

                        <textarea
                            class="form-control jawaban-essay"
                            rows="8"
                            data-soal-id="{{ $soal->id }}"
                            placeholder="Tuliskan jawaban Anda..."
                        >{{ $jawabanTersimpan->get($soal->id)?->jawaban_text }}</textarea>

                    </div>

                    @endif

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

    <div class="col-lg-3 order-1 order-lg-2">

        <div class="card exam-navigation-card">

    <div class="card-body">

        {{-- MOBILE: INFORMASI + TIMER --}}
        <div class="d-lg-none mb-3">

            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-start
                    gap-3
                "
            >

                <div class="min-w-0">

                    <div class="page-pretitle">
                        Computer Based Test
                    </div>

                    <div class="fw-bold exam-mobile-title">
                        {{ $ujian->judul }}
                    </div>

                    <div class="text-secondary small mt-1">

                        {{ $ujian->kelas->nama ?? '-' }}

                        <span class="mx-1">•</span>

                        {{ $soals->count() }} Soal

                    </div>

                </div>


                <div
                    id="timer-container-mobile"
                    class="
                        exam-mobile-timer
                        text-end
                        flex-shrink-0
                    "
                >

                    <div class="text-secondary small">

                        <i class="ti ti-clock"></i>

                        Waktu

                    </div>

                    <div
                        id="timer-mobile"
                        class="fw-bold"
                    >
                        --:--:--
                    </div>

                </div>

            </div>

        </div>


        {{-- JUDUL NAVIGASI DESKTOP --}}
        <h3 class="card-title d-none d-lg-block mb-3">
            Navigasi Soal
        </h3>


        {{-- NOMOR SOAL --}}
        <div
            id="navigasi-soal"
            class="exam-number-grid"
        >

            @foreach($soals as $index => $soal)

                @php

                    $sudahDijawab =
                        $jawabanTersimpan
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


        {{-- KETERANGAN DESKTOP --}}
        <div class="d-none d-lg-block">

            <hr class="my-4">

            <div class="small">

                <div class="d-flex align-items-center mb-2">

                    <span class="badge bg-success me-2">
                        &nbsp;
                    </span>

                    Sudah dijawab

                </div>

                <div class="d-flex align-items-center">

                    <span class="badge bg-secondary me-2">
                        &nbsp;
                    </span>

                    Belum dijawab

                </div>

            </div>

        </div>


        <hr class="my-3">


        {{-- PROGRESS --}}
        <div>

            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-center
                    mb-2
                "
            >

                <span class="text-secondary small">
                    Terjawab
                </span>

                <strong
                    id="jumlah-terjawab"
                    class="small"
                >

                    {{ $jawabanTersimpan->count() }}
                    /
                    {{ $soals->count() }}

                </strong>

            </div>


            <div
                class="progress"
                style="height: 6px;"
            >

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


        {{-- SELESAI HANYA DESKTOP --}}
        <button
            type="button"
            class="
                btn
                btn-success
                w-100
                mt-4
                btn-buka-submit
                d-none
                d-lg-block
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

{{-- ========================================================= --}}
{{-- MODAL KONFIRMASI SELESAI UJIAN --}}
{{-- ========================================================= --}}

<div
    class="modal modal-blur fade"
    id="modalSelesaiUjian"
    tabindex="-1"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
>
    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-status bg-success"></div>

            <div class="modal-body text-center py-4">

                <span class="avatar avatar-xl bg-success-lt mb-3">
                    <i class="ti ti-circle-check"></i>
                </span>

                <h3 class="mb-2">
                    Selesaikan Ujian?
                </h3>

                <p
                    id="pesanKonfirmasiSelesai"
                    class="text-secondary mb-0"
                >
                    Pastikan seluruh jawaban Anda sudah benar.
                </p>

            </div>

            <div class="modal-footer">

                <div class="w-100">

                    <div class="row g-2">

                        <div class="col">

                            <button
                                type="button"
                                class="btn w-100"
                                data-bs-dismiss="modal"
                            >
                                Batal
                            </button>

                        </div>

                        <div class="col">

                            <button
                                type="button"
                                id="btnKonfirmasiSelesai"
                                class="btn btn-success w-100"
                            >
                                <i class="ti ti-circle-check me-1"></i>

                                Ya, Selesai
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

{{-- ========================================================= --}}
{{-- MODAL INFORMASI CBT --}}
{{-- ========================================================= --}}

<div
    class="modal modal-blur fade"
    id="modalInformasiCbt"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            <div
                id="statusModalInformasiCbt"
                class="modal-status bg-danger"
            ></div>

            <div class="modal-body text-center py-4">

                <span
                    id="iconModalInformasiCbt"
                    class="avatar avatar-xl bg-danger-lt mb-3"
                >
                    <i class="ti ti-alert-triangle"></i>
                </span>

                <h3
                    id="judulModalInformasiCbt"
                    class="mb-2"
                >
                    Terjadi Kesalahan
                </h3>

                <p
                    id="pesanModalInformasiCbt"
                    class="text-secondary mb-0"
                ></p>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    id="btnTutupModalInformasiCbt"
                    class="btn btn-primary w-100"
                >
                    Mengerti
                </button>

            </div>

        </div>

    </div>
</div>

{{-- ========================================================= --}}
{{-- MODAL IMAGE VIEWER CBT --}}
{{-- ========================================================= --}}

<div
    id="modalPreviewGambar"
    class="modal modal-blur fade"
    tabindex="-1"
    aria-hidden="true"
>

    <div
        class="
            modal-dialog
            modal-dialog-centered
            modal-xl
        "
    >

        <div class="modal-content image-viewer-modal">

            {{-- HEADER --}}
            <div class="modal-header">

                <h3
                    id="judulPreviewGambar"
                    class="modal-title"
                >
                    Gambar Soal
                </h3>

                <button
                    type="button"
                    class="btn-close"
                    id="btnTutupPreviewGambar"
                    aria-label="Tutup"
                ></button>

            </div>


            {{-- TOOLBAR --}}
            <div class="image-viewer-toolbar">

                <div class="d-flex align-items-center gap-2">

                    {{-- ZOOM OUT --}}
                    <button
                        type="button"
                        id="btnZoomOut"
                        class="btn btn-outline-secondary"
                        title="Perkecil"
                    >

                        <i class="ti ti-minus"></i>

                    </button>


                    {{-- ZOOM PERCENTAGE --}}
                    <button
                        type="button"
                        id="btnZoomReset"
                        class="btn btn-outline-secondary zoom-reset-btn"
                        title="Reset zoom"
                    >

                        <i class="ti ti-refresh me-1"></i>

                        <span id="zoomPersentase">
                            100%
                        </span>

                    </button>


                    {{-- ZOOM IN --}}
                    <button
                        type="button"
                        id="btnZoomIn"
                        class="btn btn-outline-secondary"
                        title="Perbesar"
                    >

                        <i class="ti ti-plus"></i>

                    </button>

                </div>


                <div class="image-viewer-help">

                    <i class="ti ti-arrows-move me-1"></i>

                    Geser gambar untuk melihat bagian lain

                </div>

            </div>


            {{-- IMAGE VIEWPORT --}}
            <div
                id="previewGambarContainer"
                class="image-viewer-viewport"
            >

                <div
                    id="imageViewerStage"
                    class="image-viewer-stage"
                >

                    <img
                        id="gambarPreview"
                        src=""
                        alt="Preview gambar ujian"
                        class="image-viewer-image"
                        draggable="false"
                    >

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="modal-footer">

                <div
                    class="
                        w-100
                        d-flex
                        justify-content-between
                        align-items-center
                        gap-3
                    "
                >

                    <div
                        class="
                            text-secondary
                            small
                            d-none
                            d-md-block
                        "
                    >

                        <i class="ti ti-info-circle me-1"></i>

                        Scroll untuk zoom • Drag untuk melihat gambar

                    </div>


                    <button
                        type="button"
                        id="btnTutupPreviewGambarFooter"
                        class="btn btn-primary"
                    >

                        <i class="ti ti-x me-1"></i>

                        Tutup

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

    .jawaban-essay:focus{

    border-color:var(--tblr-primary);

    box-shadow:0 0 0 .2rem rgba(var(--tblr-primary-rgb),.15);

}

    /*
    * Nomor soal yang sedang aktif
    */

    .btn-soal.soal-aktif {
        background-color: var(--tblr-primary) !important;
        border-color: var(--tblr-primary) !important;
        color: #ffffff !important;

        box-shadow:
            0 0 0 3px
            rgba(
                var(--tblr-primary-rgb),
                .15
            );
    }

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

    /* =========================================================
   EXAM LAYOUT
========================================================= */

.exam-navigation-card {
    position: sticky;
    top: 90px;
}

.exam-number-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.5rem;
}


/* =========================================================
   MOBILE EXAM
========================================================= */

@media (max-width: 991.98px) {

    /*
     * Jarak antar area dibuat lebih rapat.
     */

    .row.g-4 {
        --tblr-gutter-y: 0.75rem;
    }


    /*
     * Header ujian desktop tidak diperlukan
     * karena informasi sudah masuk navigasi.
     */

    .exam-info-card {
        display: none;
    }


    /*
     * Navigasi tidak sticky pada HP/tablet.
     */

    .exam-navigation-card {
        position: static !important;
        margin-bottom: 0;
    }


    .exam-navigation-card .card-body {
        padding: 1rem;
    }


    /*
     * Informasi ujian.
     */

    .exam-mobile-title {
        font-size: 1.05rem;
        line-height: 1.3;
    }


    /*
     * Timer lebih kecil dan berada
     * di samping informasi ujian.
     */

    #timer-container-mobile {
        width: auto !important;
        min-width: 90px;
    }

    #timer-mobile {
        font-size: 1.2rem !important;
        line-height: 1.2;
        margin-top: 3px;
    }


    /*
     * Nomor soal.
     */

    .exam-number-grid {
        grid-template-columns:
            repeat(5, minmax(0, 1fr));

        gap: 0.45rem;
    }

    .btn-soal {
        min-height: 38px;
        padding: 0.35rem;
    }


    /*
     * Card soal.
     */

    .soal-container .card-header {
        min-height: auto;
        padding: 0.85rem 1rem;
    }

    .soal-container .card-body {
        padding: 1rem;
    }

    .soal-container .card-footer {
        padding: 0.85rem 1rem;
    }


    /*
     * Pertanyaan.
     */

    .soal-pertanyaan {
        font-size: 1.05rem !important;
        line-height: 1.55;
        margin-bottom: 1rem !important;
    }


    /*
     * Pilihan jawaban lebih compact.
     */

    .jawaban-option {
        padding: 0.75rem !important;
        margin-bottom: 0.65rem !important;
        gap: 0.75rem !important;
        align-items: center !important;
    }

    .jawaban-option .form-check-input {
        margin-top: 0 !important;
    }


    /*
     * Navigasi bawah soal.
     */

    .soal-container .card-footer .btn {
        padding-left: 0.8rem;
        padding-right: 0.8rem;
    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 575.98px) {

    .exam-navigation-card .card-body {
        padding: 0.85rem;
    }

    .exam-mobile-title {
        font-size: 1rem;
    }

    #timer-mobile {
        font-size: 1.1rem !important;
    }

    .exam-number-grid {
        gap: 0.35rem;
    }

    .btn-soal {
        min-height: 36px;
        font-size: 0.85rem;
    }

    .soal-container .card-header,
    .soal-container .card-body,
    .soal-container .card-footer {
        padding-left: 0.85rem;
        padding-right: 0.85rem;
    }

    .jawaban-option {
        min-height: 48px;
    }

}

/* =========================================================
   GAMBAR SOAL
   ---------------------------------------------------------
   Gambar hanya disesuaikan saat ditampilkan di pengerjaan.
   File asli tidak diubah.
========================================================= */

.soal-gambar {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}


/*
 * Gambar soal otomatis menyesuaikan ukuran layar.
 *
 * width: auto
 * height: auto
 *
 * menjaga rasio asli gambar.
 */
.soal-gambar img {
    display: block;

    width: auto;
    height: auto;

    /*
     * Jangan pernah keluar dari card.
     */
    max-width: 100%;

    /*
     * Batas tinggi supaya gambar besar
     * tidak memenuhi seluruh halaman.
     */
    max-height: 500px;

    /*
     * Rasio gambar tetap asli.
     */
    object-fit: contain;

    /*
     * Tampilan lebih rapi.
     */
    border-radius: 0.5rem;

    /*
     * Gambar kecil tidak dipaksa membesar.
     */
}


/* =========================================================
   GAMBAR PILIHAN JAWABAN
========================================================= */

.jawaban-gambar {
    width: 100%;

    display: flex;

    justify-content: flex-start;

    align-items: center;

    overflow: hidden;
}


/*
 * Ukuran gambar pilihan dibuat lebih kecil
 * daripada gambar pertanyaan.
 */
.jawaban-gambar img {
    display: block;

    width: auto;
    height: auto;

    /*
     * Tidak boleh melebihi lebar
     * area jawaban.
     */
    max-width: 100%;

    /*
     * Supaya satu gambar pilihan
     * tidak terlalu memenuhi jawaban.
     */
    max-height: 300px;

    /*
     * Rasio asli tetap dipertahankan.
     */
    object-fit: contain;

    border-radius: 0.5rem;

    border: 1px solid
        var(--tblr-border-color);
}


/* =========================================================
   TEKS PILIHAN
========================================================= */

.jawaban-teks {
    line-height: 1.6;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 991.98px) {

    .soal-gambar img {
        max-height: 430px;
    }

    .jawaban-gambar img {
        max-height: 270px;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 575.98px) {

    /*
     * Gambar soal pada HP
     */
    .soal-gambar {
        margin-top: 1rem;
    }


    .soal-gambar img {
        max-width: 100%;
        max-height: 320px;
    }


    /*
     * Gambar pilihan pada HP
     */
    .jawaban-gambar {
        margin-top: 0.75rem;
    }


    .jawaban-gambar img {
        max-width: 100%;
        max-height: 220px;
    }

}


/* =========================================================
   HP SANGAT KECIL
========================================================= */

@media (max-width: 380px) {

    .soal-gambar img {
        max-height: 280px;
    }

    .jawaban-gambar img {
        max-height: 190px;
    }

}

/* =========================================================
   IMAGE VIEWER CBT
========================================================= */

.image-viewer-modal {
    overflow: hidden;
}


/* =========================================================
   TOOLBAR
========================================================= */

.image-viewer-toolbar {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 1rem;

    padding: .65rem 1rem;

    border-bottom:
        1px solid
        var(--tblr-border-color);

    background:
        var(--tblr-bg-surface);

}


.zoom-reset-btn {
    min-width: 90px;
}


/* =========================================================
   HELP
========================================================= */

.image-viewer-help {

    color:
        var(--tblr-secondary);

    font-size:
        .8rem;

}


/* =========================================================
   VIEWPORT
========================================================= */

.image-viewer-viewport {

    position: relative;

    width: 100%;

    height: 70vh;

    min-height: 400px;

    overflow: hidden;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        #f5f7fb;

    cursor: grab;

    /*
     * Sangat penting untuk touch/pinch.
     */
    touch-action: none;

    user-select: none;

    -webkit-user-select: none;
}


.image-viewer-viewport.dragging {

    cursor: grabbing;

}


/* =========================================================
   STAGE
========================================================= */

.image-viewer-stage {

    position: absolute;

    left: 0;

    top: 0;

    width: 0;

    height: 0;

    transform:
        translate3d(0, 0, 0);

}


/* =========================================================
   IMAGE
========================================================= */

.image-viewer-image {

    position: absolute;

    left: 0;

    top: 0;

    display: block;

    width: auto;

    height: auto;

    max-width: none;

    max-height: none;

    user-select: none;

    -webkit-user-select: none;

    -webkit-user-drag: none;

    pointer-events: none;

    transform-origin:
        0 0;

    will-change:
        transform;

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 575.98px) {

    .image-viewer-toolbar {

        padding:
            .5rem .65rem;

    }


    .image-viewer-help {

        display: none;

    }


    .image-viewer-viewport {

        height: 65vh;

        min-height: 280px;

    }


    .zoom-reset-btn {

        min-width: 75px;

    }

}

/* =========================================================
   GAMBAR SOAL
========================================================= */

.soal-gambar {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.soal-gambar img {
    width: auto;
    max-width: 100%;
    max-height: 500px;
    object-fit: contain;
    display: block;
}


/* =========================================================
   GAMBAR PILIHAN JAWABAN
========================================================= */

.jawaban-gambar {
    width: 100%;
    display: flex;
    justify-content: flex-start;
    align-items: center;
}

.jawaban-gambar img {
    width: auto;
    max-width: 350px;
    max-height: 300px;
    object-fit: contain;
    display: block;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767.98px) {

    .soal-gambar img {
        max-width: 100%;
        max-height: 350px;
    }

    .jawaban-gambar img {
        max-width: 100%;
        max-height: 250px;
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

            /*
                * Hapus warna aktif
                * dari semua nomor soal.
                */

                tombolSoal.forEach(
                    function (button) {

                        button
                            .classList
                            .remove(
                                'soal-aktif'
                            );

                    }
                );


                /*
                * Beri warna pada
                * nomor soal yang sedang dibuka.
                */

                const tombolAktif =
                    document.querySelector(
                        '.btn-soal[data-index="' +
                        index +
                        '"]'
                    );


                if (tombolAktif) {

                    tombolAktif
                        .classList
                        .add(
                            'soal-aktif'
                        );

                }


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

        function updateProgress(){

    const radio =
        document.querySelectorAll(
            '.jawaban-radio:checked'
        ).length;

    const essay =
        Array.from(
            document.querySelectorAll(
                '.jawaban-essay'
            )
        ).filter(
            item => item.value.trim() !== ''
        ).length;

    const terjawab =
        radio + essay;

    const jumlahElement =
        document.getElementById(
            'jumlah-terjawab'
        );

    const progressElement =
        document.getElementById(
            'progress-jawaban'
        );

    if(jumlahElement){

        jumlahElement.textContent =
            terjawab +
            ' / ' +
            totalSoal;

    }

    if(progressElement){

        progressElement.style.width =
            (
                terjawab /
                totalSoal
            ) * 100 + '%';

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


                                window.tampilkanInformasiCbt(
                                    'Jawaban Belum Tersimpan',
                                    'Jawaban belum berhasil disimpan. ' +
                                    'Periksa koneksi internet Anda, ' +
                                    'kemudian pilih kembali jawaban tersebut.'
                                );

                            }

                        }
                    );

                }
            );


            /*
|--------------------------------------------------------------------------
| AUTOSAVE ESSAY
|--------------------------------------------------------------------------
*/

document
.querySelectorAll(
    '.jawaban-essay'
)
.forEach(function(textarea){

    let timer;

    textarea.addEventListener(
        'input',
        function(){

            clearTimeout(timer);

            timer = setTimeout(async()=>{

                const soalId =
                    this.dataset.soalId;

                const jawaban =
                    this.value;

                /*
                 * Update progress.
                 */
                updateProgress();

                /*
                 * Update warna navigasi.
                 */
                const tombol =
                    document.querySelector(
                        '.btn-soal[data-soal-id="' +
                        soalId +
                        '"]'
                    );

                if(tombol){

                    if(jawaban.trim() !== ''){

                        tombol.classList.remove(
                            'btn-outline-secondary'
                        );

                        tombol.classList.add(
                            'btn-success'
                        );

                    }else{

                        tombol.classList.remove(
                            'btn-success'
                        );

                        tombol.classList.add(
                            'btn-outline-secondary'
                        );

                    }

                }

                try{

                    const response =
                    await fetch(

                        "{{ route(
                            'cbt.siswa.pengerjaan.jawaban',
                            $pengerjaan
                        ) }}",

                        {

                            method:'POST',

                            headers:{

                                'Content-Type':'application/json',

                                'Accept':'application/json',

                                'X-CSRF-TOKEN':
                                document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).content

                            },

                            body:JSON.stringify({

                                soal_id:soalId,

                                jawaban_text:jawaban

                            })

                        }

                    );

                    const data =
                        await response.json();

                    if(data.blocked){

                        window.location.reload();

                        return;

                    }

                    if(data.expired){

                        submitOtomatis();

                        return;

                    }

                }catch(error){

                    console.error(error);

                }

            },700);

        }

    );

});



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

        const timerMobileElement =
            document.getElementById(
                'timer-mobile'
            );


        const timerMobileContainer =
            document.getElementById(
                'timer-container-mobile'
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
            |--------------------------------------------------------------------------
            | WAKTU HABIS
            |--------------------------------------------------------------------------
            */

            if (selisih <= 0) {

                if (timerElement) {

                    timerElement.textContent =
                        '00:00:00';

                }


                if (timerMobileElement) {

                    timerMobileElement.textContent =
                        '00:00:00';

                }


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


            const waktuTersisa =

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


            if (timerElement) {

                timerElement.textContent =
                    waktuTersisa;

            }


            if (timerMobileElement) {

                timerMobileElement.textContent =
                    waktuTersisa;

            }


            /*
             * Peringatan 5 menit.
             */

            if (
                selisih <=
                5 * 60 * 1000
            ) {

                if (timerContainer) {

                    timerContainer
                        .classList
                        .add(
                            'border-danger',
                            'text-danger'
                        );

                }


                if (timerMobileContainer) {

                    timerMobileContainer
                        .classList
                        .add(
                            'border-danger',
                            'text-danger'
                        );

                }

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

const modalSelesaiElement =
    document.getElementById(
        'modalSelesaiUjian'
    );

const pesanKonfirmasiSelesai =
    document.getElementById(
        'pesanKonfirmasiSelesai'
    );

const btnKonfirmasiSelesai =
    document.getElementById(
        'btnKonfirmasiSelesai'
    );


/*
|--------------------------------------------------------------------------
| BUKA MODAL SELESAI UJIAN
|--------------------------------------------------------------------------
*/

function bukaModalSelesai()
{
    if (! modalSelesaiElement) {
        return;
    }


    modalSelesaiElement
        .classList
        .add('show');


    modalSelesaiElement.style.display =
        'block';


    modalSelesaiElement.removeAttribute(
        'aria-hidden'
    );


    modalSelesaiElement.setAttribute(
        'aria-modal',
        'true'
    );


    document.body
        .classList
        .add('modal-open');
}


/*
|--------------------------------------------------------------------------
| TUTUP MODAL SELESAI UJIAN
|--------------------------------------------------------------------------
*/

function tutupModalSelesai()
{
    if (! modalSelesaiElement) {
        return;
    }


    modalSelesaiElement
        .classList
        .remove('show');


    modalSelesaiElement.style.display =
        'none';


    modalSelesaiElement.setAttribute(
        'aria-hidden',
        'true'
    );


    modalSelesaiElement.removeAttribute(
        'aria-modal'
    );


    document.body
        .classList
        .remove('modal-open');
}


/*
|--------------------------------------------------------------------------
| TOMBOL BUKA KONFIRMASI
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

                    const radio =
                    document.querySelectorAll(
                    '.jawaban-radio:checked'
                    ).length;

                    const essay =
                    Array.from(
                    document.querySelectorAll(
                    '.jawaban-essay'
                    ))
                    .filter(
                    item => item.value.trim() !== ''
                    ).length;

                    const terjawab =
                    radio + essay;


                    const belumDijawab =
                        totalSoal -
                        terjawab;


                    if (
                        pesanKonfirmasiSelesai
                    ) {

                        if (
                            belumDijawab === 0
                        ) {

                            pesanKonfirmasiSelesai
                                .innerHTML =

                                'Semua soal telah dijawab. ' +

                                'Pastikan kembali jawaban Anda ' +

                                'sebelum <strong>' +

                                'menyelesaikan ujian' +

                                '</strong>.';

                        } else {

                            pesanKonfirmasiSelesai
                                .innerHTML =

                                'Masih ada ' +

                                '<strong class="text-danger">' +

                                belumDijawab +

                                ' soal</strong> ' +

                                'yang belum dijawab. ' +

                                'Apakah Anda tetap ingin ' +

                                'menyelesaikan ujian?';

                        }

                    }


                    /*
                     * Matikan pengawasan sementara
                     * agar modal tidak dianggap
                     * sebagai pelanggaran.
                     */

                    bukaModalSelesai();

                }
            );

        }
    );


/*
|--------------------------------------------------------------------------
| TOMBOL BATAL
|--------------------------------------------------------------------------
*/

const btnBatalSelesai =
    modalSelesaiElement
        ?.querySelector(
            '[data-bs-dismiss="modal"]'
        );


if (btnBatalSelesai) {

    btnBatalSelesai.addEventListener(
        'click',
        function () {

            tutupModalSelesai();

        }
    );

}


/*
|--------------------------------------------------------------------------
| KONFIRMASI SELESAI
|--------------------------------------------------------------------------
*/

if (btnKonfirmasiSelesai) {

    btnKonfirmasiSelesai.addEventListener(
        'click',
        function () {

            if (sedangSubmit) {
                return;
            }


            btnKonfirmasiSelesai.disabled =
                true;


            btnKonfirmasiSelesai.innerHTML =

                '<span ' +

                'class="spinner-border ' +

                'spinner-border-sm me-2">' +

                '</span>' +

                'Menyelesaikan...';


            submitOtomatis();

        }
    );

}


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

    /*
|--------------------------------------------------------------------------
| BACK BUTTON GUARD
|--------------------------------------------------------------------------
|
| Mencegah tombol Back browser / Android
| meninggalkan halaman ujian.
|
*/

let backGuardAktif = false;


/*
|--------------------------------------------------------------------------
| PASANG HISTORY GUARD
|--------------------------------------------------------------------------
*/

function pasangBackGuard()
{
    if (backGuardAktif) {
        return;
    }

    /*
     * Tambahkan history khusus untuk halaman ujian.
     */
    history.pushState(
        {
            cbtUjian: true
        },
        '',
        window.location.href
    );


    backGuardAktif = true;
}


/*
|--------------------------------------------------------------------------
| CEGAH BACK
|--------------------------------------------------------------------------
*/

window.addEventListener(
    'popstate',
    function () {

        /*
         * Jika siswa menekan Back ketika
         * popup gambar sedang terbuka,
         * cukup tutup popup.
         */

        if (
            modalPreviewGambar &&
            modalPreviewGambar.style.display === 'block'
        ) {

            tutupPreviewGambar();


            /*
             * Pasang kembali history guard.
             */
            history.pushState(
                {
                    cbtUjian: true
                },
                '',
                window.location.href
            );


            return;
        }


        /*
         * Jika siswa sedang dalam ujian,
         * jangan biarkan halaman keluar.
         */

        if (ujianAktif) {

            history.pushState(
                {
                    cbtUjian: true
                },
                '',
                window.location.href
            );


            return;
        }


        /*
         * Jika overlay mode ujian masih aktif,
         * jangan keluar juga.
         */

        if (
            overlayModeUjian &&
            overlayModeUjian.style.display !== 'none'
        ) {

            history.pushState(
                {
                    cbtUjian: true
                },
                '',
                window.location.href
            );

        }

    }
);

    let sedangMasukFullscreen = false;

    let waktuPelanggaranTerakhir = 0;

    let pelanggaranTertunda = null;

    /*
|--------------------------------------------------------------------------
| IMAGE VIEWER CBT
|--------------------------------------------------------------------------
|
| Fitur:
|
| - Zoom berdasarkan posisi kursor
| - Zoom in / out
| - Mouse wheel zoom
| - Drag / pan
| - Double click zoom
| - Pinch zoom pada HP
| - Reset zoom
|
*/


const modalPreviewGambar =
    document.getElementById(
        'modalPreviewGambar'
    );


const previewGambarContainer =
    document.getElementById(
        'previewGambarContainer'
    );


const imageViewerStage =
    document.getElementById(
        'imageViewerStage'
    );


const gambarPreview =
    document.getElementById(
        'gambarPreview'
    );


const judulPreviewGambar =
    document.getElementById(
        'judulPreviewGambar'
    );


const btnTutupPreviewGambar =
    document.getElementById(
        'btnTutupPreviewGambar'
    );


const btnTutupPreviewGambarFooter =
    document.getElementById(
        'btnTutupPreviewGambarFooter'
    );


const btnZoomIn =
    document.getElementById(
        'btnZoomIn'
    );


const btnZoomOut =
    document.getElementById(
        'btnZoomOut'
    );


const btnZoomReset =
    document.getElementById(
        'btnZoomReset'
    );


const zoomPersentase =
    document.getElementById(
        'zoomPersentase'
    );


/*
|--------------------------------------------------------------------------
| KONFIGURASI
|--------------------------------------------------------------------------
*/

const IMAGE_ZOOM_MIN =
    0.25;


const IMAGE_ZOOM_MAX =
    5;


const IMAGE_ZOOM_STEP =
    0.25;


let imageZoom =
    1;


let imageX =
    0;


let imageY =
    0;


let imageWidth =
    0;


let imageHeight =
    0;


/*
|--------------------------------------------------------------------------
| DRAG
|--------------------------------------------------------------------------
*/

let imageDragging =
    false;


let imageDragStartX =
    0;


let imageDragStartY =
    0;


let imageStartX =
    0;


let imageStartY =
    0;


/*
|--------------------------------------------------------------------------
| TOUCH
|--------------------------------------------------------------------------
*/

let touchStartDistance =
    null;


let touchStartZoom =
    1;


/*
|--------------------------------------------------------------------------
| UPDATE TRANSFORM
|--------------------------------------------------------------------------
*/

function updateImageTransform()
{

    if (!gambarPreview) {
        return;
    }


    gambarPreview.style.transform =
        `
        translate3d(
            ${imageX}px,
            ${imageY}px,
            0
        )
        scale(
            ${imageZoom}
        )
        `;


    if (zoomPersentase) {

        zoomPersentase.textContent =
            Math.round(
                imageZoom * 100
            ) + '%';

    }

}


/*
|--------------------------------------------------------------------------
| GET VIEWPORT CENTER
|--------------------------------------------------------------------------
*/

function getViewportCenter()
{

    if (!previewGambarContainer) {

        return {
            x: 0,
            y: 0
        };

    }


    return {

        x:
            previewGambarContainer
                .clientWidth / 2,

        y:
            previewGambarContainer
                .clientHeight / 2

    };

}


/*
|--------------------------------------------------------------------------
| FIT IMAGE
|--------------------------------------------------------------------------
|
| Gambar pertama kali disesuaikan
| agar seluruh gambar terlihat.
|
*/

function fitImage()
{

    if (
        !gambarPreview ||
        !previewGambarContainer
    ) {

        return;

    }


    const viewportWidth =
        previewGambarContainer
            .clientWidth;


    const viewportHeight =
        previewGambarContainer
            .clientHeight;


    const naturalWidth =
        gambarPreview.naturalWidth;


    const naturalHeight =
        gambarPreview.naturalHeight;


    if (
        !naturalWidth ||
        !naturalHeight
    ) {

        return;

    }


    /*
     * Ukuran maksimum yang diinginkan
     * ketika pertama kali dibuka.
     */

    const maxWidth =
        viewportWidth * 0.9;


    const maxHeight =
        viewportHeight * 0.9;


    const scaleX =
        maxWidth /
        naturalWidth;


    const scaleY =
        maxHeight /
        naturalHeight;


    /*
     * Gunakan ukuran yang paling kecil
     * supaya gambar seluruhnya terlihat.
     */

    const fitScale =
        Math.min(
            scaleX,
            scaleY,
            1
        );


    imageZoom =
        fitScale;


    imageWidth =
        naturalWidth;


    imageHeight =
        naturalHeight;


    /*
     * Posisikan gambar tepat di tengah.
     */

    imageX =
        (
            viewportWidth -
            naturalWidth *
            imageZoom
        ) / 2;


    imageY =
        (
            viewportHeight -
            naturalHeight *
            imageZoom
        ) / 2;


    updateImageTransform();

}


/*
|--------------------------------------------------------------------------
| RESET
|--------------------------------------------------------------------------
*/

function resetImageZoom()
{

    fitImage();

}


/*
|--------------------------------------------------------------------------
| ZOOM KE TITIK TERTENTU
|--------------------------------------------------------------------------
|
| Ini bagian penting.
|
| Zoom tidak lagi berpusat ke tengah.
|
| Titik yang berada di bawah cursor
| akan tetap berada di bawah cursor
| setelah zoom.
|
*/

function zoomAtPoint(
    newZoom,
    pointX,
    pointY
)
{

    if (
        !gambarPreview ||
        !previewGambarContainer
    ) {

        return;

    }


    /*
     * Batasi zoom.
     */

    newZoom =
        Math.max(
            IMAGE_ZOOM_MIN,
            Math.min(
                IMAGE_ZOOM_MAX,
                newZoom
            )
        );


    /*
     * Jika tidak berubah,
     * tidak perlu melakukan apa-apa.
     */

    if (
        newZoom === imageZoom
    ) {

        return;

    }


    /*
     * Titik gambar yang sedang
     * berada di bawah cursor.
     *
     * Rumus:
     *
     * imagePoint =
     * (screenPoint - imagePosition)
     * / oldZoom
     */

    const imagePointX =
        (
            pointX -
            imageX
        ) /
        imageZoom;


    const imagePointY =
        (
            pointY -
            imageY
        ) /
        imageZoom;


    /*
     * Ubah zoom.
     */

    imageZoom =
        newZoom;


    /*
     * Hitung ulang posisi.
     *
     * imagePoint harus tetap berada
     * di pointX / pointY.
     */

    imageX =
        pointX -
        imagePointX *
        imageZoom;


    imageY =
        pointY -
        imagePointY *
        imageZoom;


    updateImageTransform();

}


/*
|--------------------------------------------------------------------------
| ZOOM IN
|--------------------------------------------------------------------------
*/

function zoomIn()
{

    const center =
        getViewportCenter();


    zoomAtPoint(

        imageZoom +
        IMAGE_ZOOM_STEP,

        center.x,

        center.y

    );

}


/*
|--------------------------------------------------------------------------
| ZOOM OUT
|--------------------------------------------------------------------------
*/

function zoomOut()
{

    const center =
        getViewportCenter();


    zoomAtPoint(

        imageZoom -
        IMAGE_ZOOM_STEP,

        center.x,

        center.y

    );

}


/*
|--------------------------------------------------------------------------
| MOUSE WHEEL ZOOM
|--------------------------------------------------------------------------
*/

if (previewGambarContainer) {

    previewGambarContainer.addEventListener(
        'wheel',
        function (event) {

            event.preventDefault();


            /*
             * Posisi cursor relatif
             * terhadap viewport.
             */

            const rect =
                previewGambarContainer
                    .getBoundingClientRect();


            const cursorX =
                event.clientX -
                rect.left;


            const cursorY =
                event.clientY -
                rect.top;


            /*
             * Scroll ke atas:
             * zoom in
             *
             * Scroll ke bawah:
             * zoom out
             */

            const arah =
                event.deltaY < 0
                    ? 1
                    : -1;


            const newZoom =
                imageZoom +
                (
                    IMAGE_ZOOM_STEP *
                    arah
                );


            zoomAtPoint(

                newZoom,

                cursorX,

                cursorY

            );

        },
        {
            passive: false
        }
    );

}


/*
|--------------------------------------------------------------------------
| MOUSE DOWN
|--------------------------------------------------------------------------
*/

if (previewGambarContainer) {

    previewGambarContainer.addEventListener(
        'mousedown',
        function (event) {

            /*
             * Hanya drag ketika
             * gambar sudah diperbesar.
             */

            if (
                imageZoom <=
                1
            ) {

                return;

            }


            imageDragging =
                true;


            previewGambarContainer
                .classList
                .add(
                    'dragging'
                );


            imageDragStartX =
                event.clientX;


            imageDragStartY =
                event.clientY;


            imageStartX =
                imageX;


            imageStartY =
                imageY;


            event.preventDefault();

        }
    );

}


/*
|--------------------------------------------------------------------------
| MOUSE MOVE
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'mousemove',
    function (event) {

        if (
            !imageDragging
        ) {

            return;

        }


        imageX =
            imageStartX +
            (
                event.clientX -
                imageDragStartX
            );


        imageY =
            imageStartY +
            (
                event.clientY -
                imageDragStartY
            );


        updateImageTransform();

    }
);


/*
|--------------------------------------------------------------------------
| MOUSE UP
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'mouseup',
    function () {

        if (
            !imageDragging
        ) {

            return;

        }


        imageDragging =
            false;


        if (previewGambarContainer) {

            previewGambarContainer
                .classList
                .remove(
                    'dragging'
                );

        }

    }
);


/*
|--------------------------------------------------------------------------
| DOUBLE CLICK
|--------------------------------------------------------------------------
*/

if (gambarPreview) {

    gambarPreview.addEventListener(
        'dblclick',
        function (event) {

            event.preventDefault();


            const rect =
                previewGambarContainer
                    .getBoundingClientRect();


            const clickX =
                event.clientX -
                rect.left;


            const clickY =
                event.clientY -
                rect.top;


            /*
             * Jika masih kecil:
             * zoom ke 200%.
             */

            if (
                imageZoom < 2
            ) {

                zoomAtPoint(

                    2,

                    clickX,

                    clickY

                );

            } else {

                /*
                 * Jika sudah zoom,
                 * kembali ke ukuran fit.
                 */

                resetImageZoom();

            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| TOUCH START
|--------------------------------------------------------------------------
*/

if (previewGambarContainer) {

    previewGambarContainer.addEventListener(
        'touchstart',
        function (event) {

            /*
             * Dua jari = pinch zoom.
             */

            if (
                event.touches.length === 2
            ) {

                const touch1 =
                    event.touches[0];


                const touch2 =
                    event.touches[1];


                touchStartDistance =
                    Math.hypot(

                        touch2.clientX -
                        touch1.clientX,

                        touch2.clientY -
                        touch1.clientY

                    );


                touchStartZoom =
                    imageZoom;

            }

        },
        {
            passive: true
        }
    );

}


/*
|--------------------------------------------------------------------------
| TOUCH MOVE
|--------------------------------------------------------------------------
*/

if (previewGambarContainer) {

    previewGambarContainer.addEventListener(
        'touchmove',
        function (event) {

            if (
                event.touches.length !== 2 ||
                touchStartDistance === null
            ) {

                return;

            }


            const touch1 =
                event.touches[0];


            const touch2 =
                event.touches[1];


            const currentDistance =
                Math.hypot(

                    touch2.clientX -
                    touch1.clientX,

                    touch2.clientY -
                    touch1.clientY

                );


            const ratio =
                currentDistance /
                touchStartDistance;


            const newZoom =
                touchStartZoom *
                ratio;


            /*
             * Titik tengah dua jari.
             */

            const rect =
                previewGambarContainer
                    .getBoundingClientRect();


            const midpointX =
                (
                    touch1.clientX +
                    touch2.clientX
                ) / 2 -
                rect.left;


            const midpointY =
                (
                    touch1.clientY +
                    touch2.clientY
                ) / 2 -
                rect.top;


            zoomAtPoint(

                newZoom,

                midpointX,

                midpointY

            );


            event.preventDefault();

        },
        {
            passive: false
        }
    );

}


/*
|--------------------------------------------------------------------------
| TOUCH END
|--------------------------------------------------------------------------
*/

if (previewGambarContainer) {

    previewGambarContainer.addEventListener(
        'touchend',
        function (event) {

            if (
                event.touches.length < 2
            ) {

                touchStartDistance =
                    null;

            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| BUKA IMAGE VIEWER
|--------------------------------------------------------------------------
*/

function bukaPreviewGambar(
    sumberGambar,
    judul
)
{

    if (
        !modalPreviewGambar ||
        !gambarPreview
    ) {

        return;

    }


    /*
     * Jangan dianggap pelanggaran.
     */

    modalSedangTerbuka =
        true;


    /*
     * Judul.
     */

    if (judulPreviewGambar) {

        judulPreviewGambar.textContent =
            judul ||
            'Gambar Ujian';

    }


    /*
     * Reset posisi.
     */

    imageZoom =
        1;

    imageX =
        0;

    imageY =
        0;


    /*
     * Tampilkan modal.
     */

    modalPreviewGambar
        .classList
        .add(
            'show'
        );


    modalPreviewGambar.style.display =
        'block';


    modalPreviewGambar.removeAttribute(
        'aria-hidden'
    );


    modalPreviewGambar.setAttribute(
        'aria-modal',
        'true'
    );


    document.body
        .classList
        .add(
            'modal-open'
        );


    /*
     * Set sumber gambar.
     */

    gambarPreview.onload =
        function () {

            /*
             * Tunggu viewport selesai
             * dirender.
             */

            requestAnimationFrame(
                function () {

                    fitImage();

                }
            );

        };


    gambarPreview.src =
        sumberGambar;

}


/*
|--------------------------------------------------------------------------
| TUTUP IMAGE VIEWER
|--------------------------------------------------------------------------
*/

function tutupPreviewGambar()
{

    if (
        !modalPreviewGambar
    ) {

        return;

    }


    modalPreviewGambar
        .classList
        .remove(
            'show'
        );


    modalPreviewGambar.style.display =
        'none';


    modalPreviewGambar.setAttribute(
        'aria-hidden',
        'true'
    );


    modalPreviewGambar.removeAttribute(
        'aria-modal'
    );


    document.body
        .classList
        .remove(
            'modal-open'
        );


    if (gambarPreview) {

        gambarPreview.src =
            '';

    }


    /*
     * Reset viewer.
     */

    imageZoom =
        1;

    imageX =
        0;

    imageY =
        0;


    /*
     * Popup sudah tidak aktif.
     */

    modalSedangTerbuka =
        false;


    /*
     * Pastikan history guard tetap ada.
     */

    if (backGuardAktif) {

        history.pushState(
            {
                cbtUjian: true
            },
            '',
            window.location.href
        );

    }


    /*
     * Pastikan pengawasan kembali aktif.
     */

    if (
        !pengerjaanDiblokir &&
        sedangFullscreen()
    ) {

        ujianAktif =
            true;

    }

}


/*
|--------------------------------------------------------------------------
| TOMBOL ZOOM IN
|--------------------------------------------------------------------------
*/

if (btnZoomIn) {

    btnZoomIn.addEventListener(
        'click',
        function () {

            zoomIn();

        }
    );

}


/*
|--------------------------------------------------------------------------
| TOMBOL ZOOM OUT
|--------------------------------------------------------------------------
*/

if (btnZoomOut) {

    btnZoomOut.addEventListener(
        'click',
        function () {

            zoomOut();

        }
    );

}


/*
|--------------------------------------------------------------------------
| RESET ZOOM
|--------------------------------------------------------------------------
*/

if (btnZoomReset) {

    btnZoomReset.addEventListener(
        'click',
        function () {

            resetImageZoom();

        }
    );

}


/*
|--------------------------------------------------------------------------
| TOMBOL TUTUP
|--------------------------------------------------------------------------
*/

if (btnTutupPreviewGambar) {

    btnTutupPreviewGambar
        .addEventListener(
            'click',
            tutupPreviewGambar
        );

}


if (btnTutupPreviewGambarFooter) {

    btnTutupPreviewGambarFooter
        .addEventListener(
            'click',
            tutupPreviewGambar
        );

}


/*
|--------------------------------------------------------------------------
| KLIK GAMBAR SOAL
|--------------------------------------------------------------------------
*/

document
    .querySelectorAll(
        '.gambar-ujian-clickable'
    )
    .forEach(
        function (gambar) {

            gambar.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();

                    event.stopPropagation();


                    const sumberGambar =
                        this.dataset.gambar ||
                        this.src;


                    const judul =
                        this.dataset.judul ||
                        'Gambar Ujian';


                    bukaPreviewGambar(

                        sumberGambar,

                        judul

                    );

                }
            );

        }
    );


/*
|--------------------------------------------------------------------------
| ESC
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'keydown',
    function (event) {

        if (
            event.key === 'Escape' &&
            modalPreviewGambar &&
            modalPreviewGambar.style.display === 'block'
        ) {

            tutupPreviewGambar();

        }

    }
);

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
| MODAL INFORMASI CBT
|--------------------------------------------------------------------------
*/

const modalInformasiCbt =
    document.getElementById(
        'modalInformasiCbt'
    );

const judulModalInformasiCbt =
    document.getElementById(
        'judulModalInformasiCbt'
    );

const pesanModalInformasiCbt =
    document.getElementById(
        'pesanModalInformasiCbt'
    );

const btnTutupModalInformasiCbt =
    document.getElementById(
        'btnTutupModalInformasiCbt'
    );


window.tampilkanInformasiCbt =
    function (
        judul,
        pesan
    ) {

    if (! modalInformasiCbt) {
        return;
    }


    judulModalInformasiCbt.textContent =
        judul;


    pesanModalInformasiCbt.textContent =
        pesan;


    modalInformasiCbt
        .classList
        .add('show');


    modalInformasiCbt.style.display =
        'block';


    modalInformasiCbt.removeAttribute(
        'aria-hidden'
    );


    modalInformasiCbt.setAttribute(
        'aria-modal',
        'true'
    );


    document.body
        .classList
        .add('modal-open');

}


function tutupInformasiCbt() {

    if (! modalInformasiCbt) {
        return;
    }


    modalInformasiCbt
        .classList
        .remove('show');


    modalInformasiCbt.style.display =
        'none';


    modalInformasiCbt.setAttribute(
        'aria-hidden',
        'true'
    );


    modalInformasiCbt.removeAttribute(
        'aria-modal'
    );


    document.body
        .classList
        .remove('modal-open');

}


if (btnTutupModalInformasiCbt) {

    btnTutupModalInformasiCbt
        .addEventListener(
            'click',
            tutupInformasiCbt
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


        pasangBackGuard();


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


                    window.tampilkanInformasiCbt(
                        'Mode Ujian Gagal Diaktifkan',
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


                    window.tampilkanInformasiCbt(
                        'Mode Ujian Diperlukan',
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