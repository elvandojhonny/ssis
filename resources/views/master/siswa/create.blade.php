@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <h2 class="page-title">
                Tambah Siswa
            </h2>

            <div class="text-secondary mt-1">
                Tambahkan data siswa sekaligus membuat akun login.
            </div>

        </div>

    </div>

</div>


<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Data Siswa
        </h3>

    </div>

    <div class="card-body">

        <form
            action="{{ route('siswa.store') }}"
            method="POST"
        >

            @csrf

            @include('master.siswa._form')


            <div class="mt-4">

                <button
                    type="submit"
                    class="btn btn-primary"
                    @disabled($kelas->isEmpty())
                >

                    <i class="ti ti-device-floppy me-1"></i>

                    Simpan Siswa

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