@extends('layouts.app')

@section('title', 'Tambah Petugas')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                Tambah Petugas Perpustakaan
            </h5>

        </div>

        <form
            action="{{ route('petugas.store') }}"
            method="POST"
        >

            @csrf

            <div class="card-body">

                @include('master.petugas._form')

            </div>

            <div class="card-footer d-flex justify-content-end">

                <a
                    href="{{ route('petugas.index') }}"
                    class="btn btn-secondary me-2"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection