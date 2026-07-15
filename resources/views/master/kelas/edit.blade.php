@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')

<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">
                Edit Kelas
            </h2>

            <div class="text-secondary mt-1">
                Perbarui informasi kelas.
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <form
            action="{{ route('kelas.update', $kelas) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            @include('master.kelas._form')

            <div class="mt-4">
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="ti ti-device-floppy me-1"></i>
                    Simpan Perubahan
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