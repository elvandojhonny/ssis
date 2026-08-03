@extends('layouts.app')

@section('title', 'Edit Petugas')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                Edit Petugas Perpustakaan
            </h5>

        </div>

        <form action="{{ route('petugas.update', $petugas->id) }}" method="POST">

            @csrf
            @method('PUT')

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
                    class="btn btn-warning"
                >
                    Update
                </button>

            </div>

        </form>

    </div>

</div>

@endsection