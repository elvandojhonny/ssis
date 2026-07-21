@extends('layouts.app')

@section('title', 'QR Siswa')

@section('content')

<div class="page-header mb-4 d-print-none">

    <div class="row align-items-center">

        <div class="col">

            <div class="text-secondary mb-1">
                Data Siswa
            </div>

            <h2 class="page-title">
                QR Absensi Siswa
            </h2>

        </div>

        <div class="col-auto">

            <a
                href="{{ route('siswa.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="ti ti-arrow-left me-1"></i>
                Kembali
            </a>

        </div>

    </div>

</div>


@if(session('success'))

    <div class="alert alert-success d-print-none">
        {{ session('success') }}
    </div>

@endif


<div class="row justify-content-center">

    <div class="col-lg-6">

        <div class="card">

            <div class="card-body text-center p-4">

                <div class="mb-3">

                    <div class="avatar avatar-xl">
                        {{
                            strtoupper(
                                substr(
                                    $siswa->user->name,
                                    0,
                                    1
                                )
                            )
                        }}
                    </div>

                </div>


                <h2 class="mb-1">
                    {{ $siswa->user->name }}
                </h2>

                <div class="text-secondary">
                    NISN: {{ $siswa->nisn }}
                </div>

                <div class="text-secondary">
                    Kelas:
                    {{ $siswa->kelas->tingkat }}
                </div>


                <div class="my-4">

                    <div
                        class="d-inline-block bg-white border rounded p-3"
                    >

                        <img
                            src="data:image/svg+xml;base64,{{ $qr }}"
                            alt="QR Absensi {{ $siswa->user->name }}"
                            style="
                                width: 280px;
                                max-width: 100%;
                            "
                        >

                    </div>

                </div>


                <div class="alert alert-info text-start d-print-none">

                    <i class="ti ti-info-circle me-1"></i>

                    QR ini merupakan identitas absensi siswa.
                    Cetak dan simpan QR dengan baik.

                </div>


                <div
                    class="
                        d-flex
                        justify-content-center
                        gap-2
                        flex-wrap
                        d-print-none
                    "
                >

                    <button
                        type="button"
                        class="btn btn-primary"
                        onclick="window.print()"
                    >

                        <i class="ti ti-printer me-1"></i>

                        Cetak QR

                    </button>


                    <form
                        action="{{
                            route(
                                'siswa.qr.regenerate',
                                $siswa
                            )
                        }}"
                        method="POST"
                        onsubmit="
                            return confirm(
                                'Buat ulang QR siswa? QR lama tidak akan dapat digunakan lagi.'
                            )
                        "
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-outline-danger"
                        >

                            <i class="ti ti-refresh me-1"></i>

                            Regenerate QR

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection