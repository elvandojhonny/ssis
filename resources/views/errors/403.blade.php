@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')

<div class="container-tight py-5">

    <div class="text-center">

        <div
            class="display-1 fw-bold text-primary mb-3"
        >
            403
        </div>

        <h1 class="mb-3">
            Akses Ditolak
        </h1>

        <p class="text-secondary mb-4">
            Anda tidak memiliki hak akses
            untuk membuka halaman ini.
        </p>

        <div class="d-flex justify-content-center gap-2">

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