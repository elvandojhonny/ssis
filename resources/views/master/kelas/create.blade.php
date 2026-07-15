@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')

<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">
                Tambah Kelas
            </h2>

            <div class="text-secondary mt-1">
                Tambahkan kelas baru ke dalam sistem.
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <form
            action="{{ route('kelas.store') }}"
            method="POST"
        >
            @csrf

            @include('master.kelas._form')

            <div class="mt-4">
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="ti ti-device-floppy me-1"></i>
                    Simpan
                </button>

                <a
                    href="{{ route('kelas.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Batal
                </a>
            </div>
        </form>

    </div>
</div>

@endsection