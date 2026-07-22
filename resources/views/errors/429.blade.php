@extends('layouts.app')

@section('title', 'Terlalu Banyak Permintaan')

@section('content')

<div class="container-tight py-5">

    <div class="text-center py-5">

        <div class="display-1 fw-bold text-warning mb-3">
            429
        </div>

        <h1 class="mb-3">
            Terlalu Banyak Permintaan
        </h1>

        <p class="text-secondary mb-4">
            Anda melakukan terlalu banyak permintaan
            dalam waktu singkat.
            Silakan tunggu sebentar lalu coba kembali.
        </p>

        <div class="d-flex justify-content-center gap-2 flex-wrap">

            <button
                type="button"
                class="btn btn-primary"
                onclick="history.back()"
            >
                <i class="ti ti-arrow-left me-1"></i>
                Kembali
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