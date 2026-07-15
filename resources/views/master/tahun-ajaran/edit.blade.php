@extends('layouts.app')

@section('title', 'Edit Tahun Ajaran')

@section('content')

<div class="page-header mb-4">

    <h2 class="page-title">
        Edit Tahun Ajaran
    </h2>

</div>

<div class="card">

    <div class="card-body">

        <form
            action="{{ route(
                'tahun-ajaran.update',
                $tahunAjaran
            ) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            @include('master.tahun-ajaran._form')

            <div class="mt-4">

                <button class="btn btn-primary">
                    Simpan Perubahan
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