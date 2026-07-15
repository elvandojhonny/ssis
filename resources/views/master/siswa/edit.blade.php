@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <h2 class="page-title">
                Edit Siswa
            </h2>

            <div class="text-secondary mt-1">
                Perbarui data dan akun login siswa.
            </div>

        </div>

    </div>

</div>


<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            {{ $siswa->nama }}
        </h3>

    </div>

    <div class="card-body">

        <form
            action="{{ route('siswa.update', $siswa) }}"
            method="POST"
        >

            @csrf

            @method('PUT')


            @include('master.siswa._form')


            <div class="mt-4">

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="ti ti-device-floppy me-1"></i>

                    Simpan Perubahan

                </button>


                <a
                    href="{{ route('siswa.index') }}"
                    class="btn btn-outline-secondary"
                >

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection