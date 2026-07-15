@extends('layouts.app')

@section('title', 'Siswa')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <h2 class="page-title">
                Data Siswa
            </h2>

            <div class="text-secondary mt-1">
                Kelola data dan akun siswa.
            </div>

        </div>


        <div class="col-auto">

            <a
                href="{{ route('siswa.create') }}"
                class="btn btn-primary"
            >

                <i class="ti ti-plus me-1"></i>

                Tambah Siswa

            </a>

        </div>

    </div>

</div>


{{-- Alert Success --}}
@if(session('success'))

    <div
        class="alert alert-success alert-dismissible"
        role="alert"
    >

        <div class="d-flex">

            <div>
                <i class="ti ti-circle-check me-2"></i>
            </div>

            <div>
                {{ session('success') }}
            </div>

        </div>

        <a
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="close"
        ></a>

    </div>

@endif


{{-- Alert Error --}}
@if(session('error'))

    <div
        class="alert alert-danger"
        role="alert"
    >

        {{ session('error') }}

    </div>

@endif


<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Daftar Siswa
        </h3>

    </div>


    <div class="table-responsive">

        <table class="table table-vcenter card-table">

            <thead>

                <tr>

                    <th>Siswa</th>

                    <th>
                        NIS / NISN
                    </th>

                    <th>
                        Kelas
                    </th>

                    <th>
                        Tahun Ajaran
                    </th>

                    <th>
                        Username
                    </th>

                    <th>
                        Status
                    </th>

                    <th class="w-1">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

            @forelse($siswas as $siswa)

                <tr>

                    {{-- Nama --}}
                    <td>

                        <div class="d-flex align-items-center">

                            <span class="avatar avatar-sm me-3">

                                {{ strtoupper(
                                    substr(
                                        $siswa->nama,
                                        0,
                                        1
                                    )
                                ) }}

                            </span>


                            <div>

                                <div class="fw-bold">

                                    {{ $siswa->nama }}

                                </div>


                                <div class="text-secondary small">

                                    @if($siswa->jenis_kelamin === 'L')

                                        Laki-laki

                                    @elseif($siswa->jenis_kelamin === 'P')

                                        Perempuan

                                    @else

                                        -

                                    @endif

                                </div>

                            </div>

                        </div>

                    </td>


                    {{-- NIS --}}
                    <td>

                        <div>

                            {{ $siswa->nis }}

                        </div>

                        <div class="text-secondary small">

                            NISN:
                            {{ $siswa->nisn ?? '-' }}

                        </div>

                    </td>


                    {{-- Kelas --}}
                    <td>

                        <span class="badge bg-blue-lt">

                            {{ $siswa->kelas->nama }}

                        </span>

                    </td>


                    {{-- Tahun Ajaran --}}
                    <td>

                        {{ $siswa->kelas->tahunAjaran->nama }}

                    </td>


                    {{-- Username --}}
                    <td>

                        <i class="ti ti-user me-1 text-secondary"></i>

                        {{ $siswa->user->username }}

                    </td>


                    {{-- Status --}}
                    <td>

                        @if($siswa->is_active)

                            <span class="badge bg-success-lt">

                                Aktif

                            </span>

                        @else

                            <span class="badge bg-secondary-lt">

                                Tidak Aktif

                            </span>

                        @endif

                    </td>


                    {{-- Aksi --}}
                    <td>

                        <div class="d-flex gap-2">

                            <a
                                href="{{ route(
                                    'siswa.edit',
                                    $siswa
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                                title="Edit"
                            >

                                <i class="ti ti-edit"></i>

                            </a>


                            <form
                                action="{{ route(
                                    'siswa.destroy',
                                    $siswa
                                ) }}"
                                method="POST"
                            >

                                @csrf

                                @method('DELETE')


                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Hapus"
                                    onclick="return confirm(
                                        'Yakin ingin menghapus siswa {{ $siswa->nama }} beserta akun loginnya?'
                                    )"
                                >

                                    <i class="ti ti-trash"></i>

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="7"
                        class="text-center py-5"
                    >

                        <div class="text-secondary">

                            <i
                                class="ti ti-users"
                                style="font-size: 40px;"
                            ></i>

                            <div class="mt-2">

                                Belum ada data siswa.

                            </div>

                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    @if($siswas->hasPages())

        <div class="card-footer">

            {{ $siswas->links() }}

        </div>

    @endif

</div>

@endsection