@extends('layouts.app')

@section('title', 'Tambah Tahun Ajaran')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <h2 class="page-title">
                Tambah Tahun Ajaran
            </h2>

        </div>

    </div>

</div>

<div class="card">

    <div class="card-body">

        <form
            action="{{ route('tahun-ajaran.store') }}"
            method="POST"
        >

            @csrf

            @include('master.tahun-ajaran._form')

            <div class="mt-4">

                <button class="btn btn-primary">
                    Simpan
                </button>

                <a
                    href="{{ route('tahun-ajaran.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection