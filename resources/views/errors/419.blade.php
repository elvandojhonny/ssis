@extends('layouts.app')

@section('title', 'Sesi Kedaluwarsa')

@section('content')

<div class="container-tight py-5">

    <div class="text-center py-5">

        <div class="display-1 fw-bold text-warning mb-3">
            419
        </div>

        <h1 class="mb-3">
            Sesi Kedaluwarsa
        </h1>

        <p class="text-secondary mb-4">
            Sesi Anda telah berakhir atau halaman
            terlalu lama tidak digunakan.
            Silakan muat ulang halaman dan coba kembali.
        </p>

        <div class="d-flex justify-content-center gap-2 flex-wrap">

            <button
                type="button"
                class="btn btn-primary"
                onclick="window.location.reload()"
            >
                <i class="ti ti-refresh me-1"></i>
                Muat Ulang
            </button>

            <a
                href="{{ route('dashboard') }}"
                class="btn btn-outline-secondary"
            >
                <i class="ti ti-home me-1"></i>
                Dashboard
            </a>

        </div>

    </div>

</div>

@endsection