@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')

<div class="page-header mb-4">
    <div>
        <h2 class="page-title">Edit Guru</h2>

        <div class="text-secondary mt-1">
            Perbarui data dan akun guru.
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <form
            action="{{ route('guru.update', $guru) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            @include('master.guru._form')

            <div class="mt-4">
                <button class="btn btn-primary">
                    Simpan Perubahan
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