@extends('layouts.app')

@section('title', 'Detail Ujian')


@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | MODAL KONFIRMASI PUBLIKASI
    |--------------------------------------------------------------------------
    */

    .publish-confirm-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;

        display: none;
        align-items: center;
        justify-content: center;

        padding: 20px;

        background: rgba(15, 23, 42, 0.65);

        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }


    .publish-confirm-modal {
        width: 100%;
        max-width: 480px;

        padding: 32px;

        background:
            var(
                --tblr-bg-surface,
                #ffffff
            );

        border-radius: 16px;

        box-shadow:
            0 24px 70px
            rgba(
                0,
                0,
                0,
                0.30
            );

        text-align: center;

        animation:
            publishModalShow
            0.2s
            ease-out;
    }


    .publish-confirm-icon {
        width: 72px;
        height: 72px;

        margin:
            0 auto
            20px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background:
            rgba(
                47,
                179,
                68,
                0.14
            );

        color: #2fb344;

        font-size: 34px;
    }


    .publish-confirm-title {
        margin-bottom: 10px;

        font-size: 24px;
        font-weight: 700;
    }


    .publish-confirm-description {
        margin-bottom: 22px;

        color:
            var(
                --tblr-secondary,
                #626976
            );

        line-height: 1.6;
    }


    .publish-confirm-warning {
        padding: 14px 16px;

        margin-bottom: 24px;

        border:
            1px solid
            rgba(
                245,
                159,
                0,
                0.25
            );

        border-radius: 10px;

        background:
            rgba(
                245,
                159,
                0,
                0.08
            );

        text-align: left;
    }


    .publish-confirm-actions {
        display: flex;
        gap: 12px;
    }


    @keyframes publishModalShow {

        from {
            opacity: 0;

            transform:
                translateY(12px)
                scale(0.97);
        }


        to {
            opacity: 1;

            transform:
                translateY(0)
                scale(1);
        }

    }


    @media (
        max-width: 575.98px
    ) {

        .publish-confirm-modal {
            padding: 24px 20px;
        }


        .publish-confirm-actions {
            flex-direction: column-reverse;
        }

    }

</style>

@endpush


@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Modul CBT
            </div>

            <h2 class="page-title">
                {{ $ujian->judul }}
            </h2>

            <div class="text-secondary mt-1">
                Detail dan pengaturan publikasi ujian.
            </div>

        </div>


        <div class="col-12 col-md-auto">

    <div class="d-flex gap-2">

        @if($ujian->status === 'draft')

            <a
                href="{{ route(
                    'cbt.ujian.edit',
                    $ujian
                ) }}"
                class="btn btn-primary"
            >

                <i class="ti ti-edit me-1"></i>

                Edit Ujian

            </a>

        @endif


        <a
            href="{{ route('cbt.ujian.index') }}"
            class="btn btn-outline-secondary"
        >

            <i class="ti ti-arrow-left me-1"></i>

            Kembali

        </a>

    </div>

</div>

    </div>

</div>


{{-- ALERT SUCCESS --}}

@if(session('success'))

    <div class="alert alert-success">

        <div class="d-flex align-items-center">

            <i class="ti ti-circle-check me-2"></i>

            <div>
                {{ session('success') }}
            </div>

        </div>

    </div>

@endif


{{-- ALERT ERROR --}}

@if(session('error'))

    <div class="alert alert-danger">

        <div class="d-flex align-items-center">

            <i class="ti ti-alert-circle me-2"></i>

            <div>
                {{ session('error') }}
            </div>

        </div>

    </div>

@endif


<div class="row row-cards">


    {{-- ===================================================== --}}
    {{-- INFORMASI UJIAN --}}
    {{-- ===================================================== --}}

    <div class="col-lg-8">

        <div class="card mb-4">

            <div class="card-header">

                <h3 class="card-title">
                    Informasi Ujian
                </h3>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    {{-- JUDUL UJIAN --}}

                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Judul Ujian
                        </div>

                        <div class="fw-bold mt-1">
                            {{ $ujian->judul }}
                        </div>

                    </div>


                    {{-- STATUS --}}

                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Status
                        </div>

                        <div class="mt-1">

                            @if($ujian->status === 'draft')

                                <span class="badge bg-secondary-lt">
                                    Draft
                                </span>

                            @elseif($ujian->status === 'dipublikasi')

                                <span class="badge bg-success-lt">
                                    Dipublikasi
                                </span>

                            @else

                                <span class="badge bg-blue-lt">
                                    Selesai
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- MATA PELAJARAN --}}

                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Mata Pelajaran
                        </div>

                        <div class="fw-bold mt-1">

                            {{
                                $ujian
                                    ->bankSoal
                                    ->mata_pelajaran
                            }}

                        </div>

                    </div>


                    {{-- KELAS --}}

                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Kelas
                        </div>

                        <div class="fw-bold mt-1">

                            {{ $ujian->kelas->nama }}

                        </div>

                        <div class="text-secondary small">

                            {{
                                $ujian
                                    ->kelas
                                    ->tahunAjaran
                                    ->nama
                            }}

                        </div>

                    </div>


                    {{-- WAKTU MULAI --}}

                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Waktu Mulai
                        </div>

                        <div class="fw-bold mt-1">

                            {{
                                $ujian
                                    ->waktu_mulai
                                    ->format('d/m/Y H:i')
                            }}

                        </div>

                    </div>


                    {{-- WAKTU SELESAI --}}

                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Waktu Selesai
                        </div>

                        <div class="fw-bold mt-1">

                            {{
                                $ujian
                                    ->waktu_selesai
                                    ->format('d/m/Y H:i')
                            }}

                        </div>

                    </div>


                    {{-- DURASI --}}

                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Durasi Pengerjaan
                        </div>

                        <div class="fw-bold mt-1">

                            {{ $ujian->durasi_menit }}
                            menit

                        </div>

                    </div>


                    {{-- JUMLAH SOAL --}}

                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Jumlah Soal
                        </div>

                        <div class="fw-bold mt-1">

                            {{
                                $ujian
                                    ->bankSoal
                                    ->soals
                                    ->count()
                            }}

                            soal

                        </div>

                    </div>

                </div>

                {{-- ACAK URUTAN SOAL --}}

            <div class="col-md-6">

                <div class="text-secondary small">
                    Acak Urutan Soal
                </div>

                <div class="mt-1">

                    @if($ujian->acak_soal)

                        <span class="badge bg-success-lt">

                            <i class="ti ti-check me-1"></i>

                            Aktif

                        </span>

                    @else

                        <span class="badge bg-secondary-lt">

                            <i class="ti ti-x me-1"></i>

                            Tidak Aktif

                        </span>

                    @endif

                </div>

            </div>


            {{-- ACAK PILIHAN JAWABAN --}}

            <div class="col-md-6">

                <div class="text-secondary small">
                    Acak Pilihan Jawaban
                </div>

                <div class="mt-1">

                    @if($ujian->acak_jawaban)

                        <span class="badge bg-success-lt">

                            <i class="ti ti-check me-1"></i>

                            Aktif

                        </span>

                    @else

                        <span class="badge bg-secondary-lt">

                            <i class="ti ti-x me-1"></i>

                            Tidak Aktif

                        </span>

                    @endif

                </div>

            </div>


                {{-- DESKRIPSI --}}

                @if($ujian->deskripsi)

                    <hr class="my-4">

                    <div>

                        <div class="text-secondary small mb-2">
                            Deskripsi
                        </div>

                        <div>
                            {{ $ujian->deskripsi }}
                        </div>

                    </div>

                @endif

            </div>

        </div>



        {{-- ================================================= --}}
        {{-- BANK SOAL --}}
        {{-- ================================================= --}}

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Bank Soal
                </h3>

            </div>


            <div class="card-body">

                <div class="d-flex align-items-start gap-3">

                    <span class="avatar avatar-lg bg-blue-lt">

                        <i class="ti ti-files"></i>

                    </span>


                    <div class="flex-fill">

                        <h3 class="mb-1">

                            {{ $ujian->bankSoal->judul }}

                        </h3>


                        <div class="text-secondary">

                            {{
                                $ujian
                                    ->bankSoal
                                    ->mata_pelajaran
                            }}

                            ·

                            {{
                                $ujian
                                    ->bankSoal
                                    ->soals
                                    ->count()
                            }}

                            soal

                        </div>


                        <div class="text-secondary mt-2">

                            Diunggah oleh:

                            {{
                                $ujian
                                    ->bankSoal
                                    ->guru
                                    ->nama
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- PUBLIKASI UJIAN --}}
    {{-- ===================================================== --}}

    <div class="col-lg-4">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Publikasi Ujian
                </h3>

            </div>


            <div class="card-body">


                {{-- STATUS DRAFT --}}

                @if($ujian->status === 'draft')


                    <div class="text-center py-3">

                        <span
                            class="
                                avatar
                                avatar-xl
                                bg-yellow-lt
                                mb-3
                            "
                        >

                            <i class="ti ti-lock"></i>

                        </span>


                        <h3>
                            Belum Dipublikasikan
                        </h3>


                        <p class="text-secondary mb-0">

                            Ujian masih berupa draft dan
                            belum dapat dilihat oleh siswa.

                        </p>

                    </div>


                    <div class="alert alert-warning mt-3">

                        <div class="d-flex">

                            <div class="me-2">

                                <i class="ti ti-alert-triangle"></i>

                            </div>


                            <div>

                                <div class="fw-bold mb-1">
                                    Periksa sebelum publikasi
                                </div>

                                <div>

                                    Setelah dipublikasikan,
                                    ujian akan tersedia untuk
                                    siswa pada kelas yang telah
                                    dipilih.

                                </div>

                            </div>

                        </div>

                    </div>


                    <form
                        id="formPublikasiUjian"
                        action="{{
                            route(
                                'cbt.ujian.publish',
                                $ujian
                            )
                        }}"
                        method="POST"
                    >

                        @csrf

                        @method('PATCH')


                        <button
                            type="button"
                            id="btnBukaKonfirmasiPublikasi"
                            class="btn btn-success w-100"
                        >

                            <i class="ti ti-send me-1"></i>

                            Publikasikan Ujian

                        </button>

                    </form>



                {{-- STATUS DIPUBLIKASI --}}

                @elseif($ujian->status === 'dipublikasi')


                    <div class="text-center py-3">

                        <span
                            class="
                                avatar
                                avatar-xl
                                bg-success-lt
                                mb-3
                            "
                        >

                            <i class="ti ti-circle-check"></i>

                        </span>


                        <h3>
                            Ujian Dipublikasikan
                        </h3>


                        <p class="text-secondary mb-0">

                            Ujian sudah tersedia untuk siswa
                            pada kelas yang ditentukan.

                        </p>

                    </div>



                    {{-- TOKEN UJIAN --}}

                    <div class="border rounded p-4 text-center mt-3">

                        <div class="mb-3">

                            <span class="avatar bg-blue-lt">

                                <i class="ti ti-key"></i>

                            </span>

                        </div>


                        <div class="text-secondary mb-2">

                            Token Ujian

                        </div>


                        <div
                            id="tokenUjian"
                            class="
                                fw-bold
                                font-monospace
                                text-primary
                            "
                            style="
                                font-size: 2rem;
                                letter-spacing: .25rem;
                                word-break: break-all;
                            "
                        >

                            {{ $ujian->token ?? '-' }}

                        </div>


                        @if($ujian->token)

                            <div class="mt-3">

                                <button
                                    type="button"
                                    id="btnSalinToken"
                                    class="btn btn-outline-primary"
                                    data-token="{{ $ujian->token }}"
                                >

                                    <i
                                        id="iconSalinToken"
                                        class="ti ti-copy me-1"
                                    ></i>

                                    <span id="textSalinToken">
                                        Salin Token
                                    </span>

                                </button>

                            </div>

                        @endif


                        <div class="text-secondary small mt-3">

                            Bagikan token ini kepada siswa
                            ketika ujian akan dimulai.

                        </div>

                    </div>


                    @push('scripts')

                    <script>
                    document.addEventListener('DOMContentLoaded', function () {

                        const button =
                            document.getElementById('btnSalinToken');

                        const text =
                            document.getElementById('textSalinToken');

                        const icon =
                            document.getElementById('iconSalinToken');


                        if (!button) {
                            return;
                        }


                        button.addEventListener('click', async function () {

                            const token =
                                this.dataset.token;


                            try {

                                await navigator.clipboard.writeText(token);


                                text.textContent =
                                    'Tersalin';


                                icon.className =
                                    'ti ti-check me-1';


                                button.classList.remove(
                                    'btn-outline-primary'
                                );


                                button.classList.add(
                                    'btn-success'
                                );


                                setTimeout(function () {

                                    text.textContent =
                                        'Salin Token';


                                    icon.className =
                                        'ti ti-copy me-1';


                                    button.classList.remove(
                                        'btn-success'
                                    );


                                    button.classList.add(
                                        'btn-outline-primary'
                                    );

                                }, 2000);


                            } catch (error) {

                                alert(
                                    'Token gagal disalin.'
                                );

                            }

                        });

                    });
                    </script>

                    @endpush



                    {{-- INFORMASI AKSES --}}

                    <div class="alert alert-info mt-3 mb-0">

                        <div class="d-flex">

                            <div class="me-2">

                                <i class="ti ti-info-circle"></i>

                            </div>


                            <div>

                                Siswa hanya dapat masuk ke ujian
                                menggunakan token ini selama
                                jadwal ujian masih berlangsung.

                            </div>

                        </div>

                    </div>



                {{-- STATUS SELESAI --}}

                @else


                    <div class="text-center py-4">

                        <span
                            class="
                                avatar
                                avatar-xl
                                bg-blue-lt
                                mb-3
                            "
                        >

                            <i class="ti ti-flag"></i>

                        </span>


                        <h3>
                            Ujian Selesai
                        </h3>


                        <p class="text-secondary mb-0">

                            Periode pelaksanaan ujian ini
                            telah selesai.

                        </p>

                    </div>


                    @if($ujian->token)

                        <div
                            class="
                                border
                                rounded
                                p-3
                                text-center
                                mt-3
                            "
                        >

                            <div class="text-secondary small mb-1">

                                Token Ujian

                            </div>


                            <div
                                class="
                                    fw-bold
                                    font-monospace
                                "
                                style="
                                    font-size: 1.25rem;
                                    letter-spacing: .15rem;
                                "
                            >

                                {{ $ujian->token }}

                            </div>

                        </div>

                    @endif


                @endif

            </div>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- MODAL KONFIRMASI PUBLIKASI --}}
{{-- ========================================================= --}}

@if($ujian->status === 'draft')

<div
    id="modalKonfirmasiPublikasi"
    class="publish-confirm-overlay"
    aria-hidden="true"
>

    <div
        class="publish-confirm-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="judulKonfirmasiPublikasi"
    >


        <div class="publish-confirm-icon">

            <i class="ti ti-send"></i>

        </div>


        <h2
            id="judulKonfirmasiPublikasi"
            class="publish-confirm-title"
        >

            Publikasikan Ujian?

        </h2>


        <p class="publish-confirm-description">

            Anda akan mempublikasikan ujian

            <strong class="text-body">
                {{ $ujian->judul }}
            </strong>.

            Setelah dipublikasikan, ujian akan tersedia
            untuk siswa pada kelas

            <strong class="text-body">
                {{ $ujian->kelas->nama }}
            </strong>.

        </p>


        <div class="publish-confirm-warning">

            <div class="d-flex">

                <div class="me-3">

                    <i
                        class="
                            ti
                            ti-alert-triangle
                            fs-2
                            text-warning
                        "
                    ></i>

                </div>


                <div>

                    <div class="fw-bold mb-1">

                        Pastikan data ujian sudah benar

                    </div>


                    <div class="text-secondary small">

                        Periksa kembali jadwal, kelas,
                        durasi pengerjaan, dan bank soal
                        sebelum ujian dipublikasikan.

                    </div>

                </div>

            </div>

        </div>


        <div class="publish-confirm-actions">

            <button
                type="button"
                id="btnBatalPublikasi"
                class="
                    btn
                    btn-outline-secondary
                    flex-fill
                "
            >

                <i class="ti ti-x me-1"></i>

                Batal

            </button>


            <button
                type="button"
                id="btnKonfirmasiPublikasi"
                class="
                    btn
                    btn-success
                    flex-fill
                "
            >

                <i class="ti ti-send me-1"></i>

                Ya, Publikasikan

            </button>

        </div>

    </div>

</div>

@endif

@endsection



@push('scripts')

@if($ujian->status === 'draft')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | ELEMENT
        |--------------------------------------------------------------------------
        */

        const form =
            document.getElementById(
                'formPublikasiUjian'
            );


        const modal =
            document.getElementById(
                'modalKonfirmasiPublikasi'
            );


        const btnBuka =
            document.getElementById(
                'btnBukaKonfirmasiPublikasi'
            );


        const btnBatal =
            document.getElementById(
                'btnBatalPublikasi'
            );


        const btnKonfirmasi =
            document.getElementById(
                'btnKonfirmasiPublikasi'
            );


        /*
        |--------------------------------------------------------------------------
        | BUKA MODAL
        |--------------------------------------------------------------------------
        */

        function bukaModal()
        {
            if (! modal) {
                return;
            }


            modal.style.display =
                'flex';


            modal.setAttribute(
                'aria-hidden',
                'false'
            );


            document.body.style.overflow =
                'hidden';
        }


        /*
        |--------------------------------------------------------------------------
        | TUTUP MODAL
        |--------------------------------------------------------------------------
        */

        function tutupModal()
        {
            if (! modal) {
                return;
            }


            modal.style.display =
                'none';


            modal.setAttribute(
                'aria-hidden',
                'true'
            );


            document.body.style.overflow =
                '';
        }


        /*
        |--------------------------------------------------------------------------
        | BUKA KONFIRMASI
        |--------------------------------------------------------------------------
        */

        btnBuka?.addEventListener(
            'click',
            function () {

                bukaModal();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | BATAL
        |--------------------------------------------------------------------------
        */

        btnBatal?.addEventListener(
            'click',
            function () {

                tutupModal();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | KLIK AREA LUAR MODAL
        |--------------------------------------------------------------------------
        */

        modal?.addEventListener(
            'click',
            function (event) {

                if (
                    event.target === modal
                ) {

                    tutupModal();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | TOMBOL ESC
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape' &&
                    modal &&
                    modal.style.display === 'flex'
                ) {

                    tutupModal();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | KONFIRMASI PUBLIKASI
        |--------------------------------------------------------------------------
        */

        btnKonfirmasi?.addEventListener(
            'click',
            function () {

                if (! form) {
                    return;
                }


                /*
                 * Cegah klik berulang.
                 */
                btnKonfirmasi.disabled =
                    true;


                btnBatal.disabled =
                    true;


                btnKonfirmasi.innerHTML =
                    '<span ' +
                    'class="spinner-border spinner-border-sm me-2"' +
                    '></span>' +
                    'Mempublikasikan...';


                /*
                 * Kirim form publikasi.
                 */
                form.submit();

            }
        );

    }
);

</script>

@endif

@endpush