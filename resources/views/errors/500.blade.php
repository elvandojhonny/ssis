@extends('layouts.app')

@section('title', 'Terjadi Kesalahan')

@section('content')

<div class="container-tight py-5">

    <div class="text-center py-5">

        <div class="display-1 fw-bold text-danger mb-3">
            500
        </div>

        <h1 class="mb-3">
            Terjadi Kesalahan
        </h1>

        <p class="text-secondary mb-4">
            Sistem mengalami kesalahan saat memproses
            permintaan Anda.
            Silakan coba kembali beberapa saat lagi.
        </p>

        <div class="d-flex justify-content-center gap-2 flex-wrap">

            <button
                type="button"
                class="btn btn-primary"
                onclick="window.location.reload()"
            >
                <i class="ti ti-refresh me-1"></i>
                Coba Lagi
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