@extends('layouts.app')

@section('title', 'Mulai Ujian')


@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | MODAL KONFIRMASI MULAI UJIAN
    |--------------------------------------------------------------------------
    */

    .exam-confirm-overlay {
        position: fixed;
        inset: 0;

        z-index: 9999;

        display: none;
        align-items: center;
        justify-content: center;

        padding: 20px;

        background:
            rgba(
                15,
                23,
                42,
                0.65
            );

        backdrop-filter:
            blur(5px);

        -webkit-backdrop-filter:
            blur(5px);
    }


    .exam-confirm-modal {
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
            examModalShow
            0.2s
            ease-out;
    }


    .exam-confirm-icon {
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
                245,
                159,
                0,
                0.14
            );

        color:
            #f59f00;

        font-size: 34px;
    }


    .exam-confirm-title {
        margin-bottom: 10px;

        font-size: 24px;
        font-weight: 700;
    }


    .exam-confirm-description {
        margin-bottom: 22px;

        color:
            var(
                --tblr-secondary,
                #626976
            );

        line-height: 1.6;
    }


    .exam-confirm-warning {
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


    .exam-confirm-actions {
        display: flex;
        gap: 12px;
    }


    @keyframes examModalShow {

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

        .exam-confirm-modal {
            padding: 24px 20px;
        }


        .exam-confirm-actions {
            flex-direction: column-reverse;
        }

    }

</style>

@endpush


@section('content')

<div class="row justify-content-center">

    <div class="col-lg-8 col-xl-7">

        <div class="card">

            <div class="card-body text-center py-5 px-4">


                {{-- ICON --}}

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


                {{-- JUDUL --}}

                <h2 class="mb-2">

                    Token Berhasil Diverifikasi

                </h2>


                <p class="text-secondary mb-4">

                    Anda telah mendapatkan akses ke ujian

                    <strong class="text-body">

                        {{ $ujian->judul }}

                    </strong>.

                </p>


                {{-- INFORMASI UJIAN --}}

                <div class="row g-3 text-start mb-4">


                    {{-- DURASI --}}

                    <div class="col-md-6">

                        <div
                            class="
                                border
                                rounded
                                p-3
                                h-100
                            "
                        >

                            <div
                                class="
                                    text-secondary
                                    small
                                    mb-1
                                "
                            >

                                Durasi Ujian

                            </div>


                            <div class="fw-bold">

                                <i
                                    class="
                                        ti
                                        ti-clock
                                        me-1
                                    "
                                ></i>

                                {{ $ujian->durasi_menit }}
                                menit

                            </div>

                        </div>

                    </div>


                    {{-- KELAS --}}

                    <div class="col-md-6">

                        <div
                            class="
                                border
                                rounded
                                p-3
                                h-100
                            "
                        >

                            <div
                                class="
                                    text-secondary
                                    small
                                    mb-1
                                "
                            >

                                Kelas

                            </div>


                            <div class="fw-bold">

                                <i
                                    class="
                                        ti
                                        ti-school
                                        me-1
                                    "
                                ></i>

                                {{
                                    $ujian
                                        ->kelas
                                        ->nama
                                    ?? '-'
                                }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- PERINGATAN --}}

                <div class="alert alert-warning text-start">

                    <div class="d-flex">

                        <div class="me-2">

                            <i
                                class="
                                    ti
                                    ti-alert-triangle
                                "
                            ></i>

                        </div>


                        <div>

                            Setelah menekan tombol

                            <strong>
                                Mulai Ujian
                            </strong>,

                            waktu pengerjaan akan langsung berjalan.

                            Pastikan koneksi internet dan perangkat
                            Anda dalam kondisi siap.

                        </div>

                    </div>

                </div>


                {{-- FORM MULAI UJIAN --}}

                <form
                    id="formMulaiUjian"
                    action="{{
                        route(
                            'cbt.siswa.pengerjaan.mulai',
                            $ujian
                        )
                    }}"
                    method="POST"
                    class="mt-4"
                >

                    @csrf


                    {{--
                        Tombol menggunakan type="button"
                        agar form tidak langsung terkirim.

                        Form baru dikirim setelah siswa
                        mengonfirmasi melalui modal.
                    --}}

                    <button
                        type="button"
                        id="btnBukaKonfirmasi"
                        class="
                            btn
                            btn-primary
                            btn-lg
                            w-100
                        "
                    >

                        <i
                            class="
                                ti
                                ti-player-play
                                me-2
                            "
                        ></i>

                        Mulai Ujian

                    </button>

                </form>


                {{-- KEMBALI --}}

                <div class="mt-3">

                    <a
                        href="{{
                            route(
                                'cbt.siswa.index'
                            )
                        }}"
                        class="
                            btn
                            btn-link
                            text-secondary
                        "
                    >

                        <i
                            class="
                                ti
                                ti-arrow-left
                                me-1
                            "
                        ></i>

                        Kembali ke Daftar Ujian

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- POPUP KONFIRMASI MULAI UJIAN --}}
{{-- ========================================================= --}}

<div
    id="modalKonfirmasiMulai"
    class="exam-confirm-overlay"
    aria-hidden="true"
>

    <div
        class="exam-confirm-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="judulKonfirmasiMulai"
    >


        {{-- ICON --}}

        <div class="exam-confirm-icon">

            <i class="ti ti-alert-triangle"></i>

        </div>


        {{-- JUDUL --}}

        <h2
            id="judulKonfirmasiMulai"
            class="exam-confirm-title"
        >

            Mulai Ujian Sekarang?

        </h2>


        {{-- DESKRIPSI --}}

        <p class="exam-confirm-description">

            Anda akan memulai ujian

            <strong class="text-body">

                {{ $ujian->judul }}

            </strong>.

            Waktu pengerjaan akan langsung berjalan
            setelah ujian dimulai.

        </p>


        {{-- PERINGATAN --}}

        <div class="exam-confirm-warning">

            <div class="d-flex">

                <div class="me-3">

                    <i
                        class="
                            ti
                            ti-shield-lock
                            fs-2
                            text-warning
                        "
                    ></i>

                </div>


                <div>

                    <div class="fw-bold mb-1">

                        Pastikan Anda sudah siap

                    </div>


                    <div class="text-secondary small">

                        Selama ujian berlangsung,
                        meninggalkan halaman ujian,
                        berpindah tab, berpindah aplikasi,
                        atau keluar dari Mode Ujian
                        dapat tercatat sebagai pelanggaran.

                    </div>

                </div>

            </div>

        </div>


        {{-- TOMBOL --}}

        <div class="exam-confirm-actions">

            <button
                type="button"
                id="btnBatalMulai"
                class="
                    btn
                    btn-outline-secondary
                    flex-fill
                "
            >

                <i class="ti ti-x me-1"></i>

                Belum Siap

            </button>


            <button
                type="button"
                id="btnKonfirmasiMulai"
                class="
                    btn
                    btn-primary
                    flex-fill
                "
            >

                <i
                    class="
                        ti
                        ti-player-play
                        me-1
                    "
                ></i>

                Ya, Mulai Ujian

            </button>

        </div>

    </div>

</div>

@endsection



@push('scripts')

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
                'formMulaiUjian'
            );


        const modal =
            document.getElementById(
                'modalKonfirmasiMulai'
            );


        const btnBuka =
            document.getElementById(
                'btnBukaKonfirmasi'
            );


        const btnBatal =
            document.getElementById(
                'btnBatalMulai'
            );


        const btnKonfirmasi =
            document.getElementById(
                'btnKonfirmasiMulai'
            );


        /*
        |--------------------------------------------------------------------------
        | BUKA POPUP
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
        | TUTUP POPUP
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
        | TOMBOL MULAI UJIAN
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
        | TOMBOL BATAL
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
        | KLIK AREA LUAR POPUP
        |--------------------------------------------------------------------------
        */

        modal?.addEventListener(
            'click',
            function (event) {

                /*
                 * Modal hanya ditutup jika siswa
                 * menekan area gelap di luar card.
                 */
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
                    event.key ===
                    'Escape'
                ) {

                    if (
                        modal &&
                        modal.style.display ===
                        'flex'
                    ) {

                        tutupModal();

                    }

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | KONFIRMASI MULAI UJIAN
        |--------------------------------------------------------------------------
        */

        btnKonfirmasi?.addEventListener(
            'click',
            function () {

                /*
                 * Pastikan form tersedia.
                 */
                if (! form) {
                    return;
                }


                /*
                 * Cegah siswa menekan tombol
                 * berkali-kali.
                 */
                btnKonfirmasi.disabled =
                    true;


                btnBatal.disabled =
                    true;


                btnKonfirmasi.innerHTML =
                    '<span ' +
                    'class="spinner-border spinner-border-sm me-2"' +
                    '></span>' +
                    'Memulai Ujian...';


                /*
                 * Submit form ke server.
                 */
                form.submit();

            }
        );

    }
);

</script>

@endpush