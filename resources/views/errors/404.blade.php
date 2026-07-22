@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')

<div class="container-tight py-5">

    <div class="text-center py-5">

        <div class="display-1 fw-bold text-primary mb-3">
            404
        </div>

        <h1 class="mb-3">
            Halaman Tidak Ditemukan
        </h1>

        <p class="text-secondary mb-4">
            Halaman yang Anda cari tidak tersedia,
            telah dipindahkan, atau sudah dihapus.
        </p>

        <div class="d-flex justify-content-center gap-2 flex-wrap">

            <a
                href="{{ route('dashboard') }}"
                class="btn btn-primary"
            >
                <i class="ti ti-home me-1"></i>
                Kembali ke Dashboard
            </a>

            <button
                type="button"
                class="btn btn-outline-secondary"
                onclick="history.back()"
            >
                <i class="ti ti-arrow-left me-1"></i>
                Kembali
            </button>

        </div>

    </div>

</div>

@endsection