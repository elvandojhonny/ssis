@extends('layouts.app')

@section('title', 'Peminjaman Buku')

@section('content')

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Peminjaman Buku
        </h3>

        <small class="text-muted">
            Scan QR siswa kemudian pilih buku yang akan dipinjam.
        </small>

    </div>

    <a href="{{ route('perpustakaan.peminjaman.index') }}"
        class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>

        Kembali

    </a>

</div>

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<form
    id="formPeminjaman"
    action="{{ route('perpustakaan.peminjaman.store') }}"
    method="POST">

    @csrf

    @include('perpustakaan.peminjaman._form')

</form>

</div>

@endsection