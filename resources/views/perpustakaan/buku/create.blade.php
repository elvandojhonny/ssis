@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">Tambah Buku</h4>
            <p class="text-muted mb-0">
                Tambahkan data buku perpustakaan.
            </p>
        </div>

        <a href="{{ route('perpustakaan.buku.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form action="{{ route('perpustakaan.buku.store') }}" method="POST">

                @csrf

                @include('perpustakaan.buku._form')

                <div class="mt-4">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i>
                        Simpan
                    </button>

                    <a href="{{ route('perpustakaan.buku.index') }}"
                        class="btn btn-light border">
                        Batal
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection