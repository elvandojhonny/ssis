@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')

<div class="page-header mb-4">
    <div>
        <h2 class="page-title">Tambah Guru</h2>
        <div class="text-secondary mt-1">
            Tambahkan data sekaligus akun login guru.
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <form action="{{ route('guru.store') }}" method="POST">
            @csrf

            @include('master.guru._form')

            <div class="mt-4">
                <button class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i>
                    Simpan
                </button>

                <a
                    href="{{ route('guru.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>

@endsection