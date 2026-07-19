@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')


{{-- ========================================================= --}}
{{-- PAGE HEADER --}}
{{-- ========================================================= --}}

<div class="page-header mb-4">

    <div>

        <div class="page-pretitle">
            Akun Saya
        </div>

        <h2 class="page-title">
            Profil Saya
        </h2>

        <div class="text-secondary mt-1">
            Informasi akun dan identitas pengguna.
        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- PROFILE HEADER --}}
{{-- ========================================================= --}}

<div class="card mb-4">

    <div class="card-body">

        <div
            class="
                d-flex
                flex-column
                flex-md-row
                align-items-md-center
                gap-3
            "
        >

            {{-- Avatar --}}
            <span
                class="
                    avatar
                    avatar-xl
                    bg-primary-lt
                    flex-shrink-0
                "
            >

                {{
                    strtoupper(
                        substr(
                            $user->name,
                            0,
                            1
                        )
                    )
                }}

            </span>


            {{-- Informasi --}}
            <div class="flex-grow-1">

                <h2 class="mb-1">

                    {{ $user->name }}

                </h2>


                <div
                    class="
                        d-flex
                        flex-wrap
                        align-items-center
                        gap-2
                    "
                >

                    <span class="badge bg-blue-lt text-capitalize">

                        {{ $user->role }}

                    </span>


                    @if($user->is_active)

                        <span class="badge bg-success-lt">

                            <i class="ti ti-circle-check me-1"></i>

                            Akun Aktif

                        </span>

                    @else

                        <span class="badge bg-secondary-lt">

                            Akun Tidak Aktif

                        </span>

                    @endif

                </div>

            </div>

            {{-- Action Button --}}

            <div
                class="
                    d-flex
                    flex-column
                    flex-sm-row
                    gap-2
                    ms-md-auto
                "
            >

                @if($user->role === 'operator')

                    <a
                        href="{{ route('profile.edit') }}"
                        class="btn btn-outline-primary"
                    >
                        <i class="ti ti-edit me-1"></i>
                        Edit Profil
                    </a>

                @endif


                <a
                    href="{{ route('profile.password.edit') }}"
                    class="btn btn-primary"
                >
                    <i class="ti ti-lock me-1"></i>
                    Ubah Password
                </a>

            </div>

                    </div>

                </div>

            </div>



<div class="row row-cards">


    {{-- ===================================================== --}}
    {{-- INFORMASI AKUN --}}
    {{-- ===================================================== --}}

    <div class="col-12 col-lg-5">

        <div class="card h-100">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="ti ti-user-circle me-2"></i>

                    Informasi Akun

                </h3>

            </div>


            <div class="card-body">


                {{-- Username --}}

                <div class="mb-4">

                    <div class="text-secondary small mb-1">
                        Username
                    </div>

                    <div class="fw-bold">

                        {{ $user->username ?? '-' }}

                    </div>

                </div>



                {{-- Email --}}

                <div class="mb-4">

                    <div class="text-secondary small mb-1">
                        Email
                    </div>

                    <div class="fw-bold">

                        {{ $user->email ?? '-' }}

                    </div>

                </div>



                {{-- Role --}}

                <div>

                    <div class="text-secondary small mb-1">
                        Hak Akses
                    </div>

                    <div class="fw-bold text-capitalize">

                        {{ $user->role }}

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- IDENTITAS BERDASARKAN ROLE --}}
    {{-- ===================================================== --}}

    <div class="col-12 col-lg-7">

        <div class="card h-100">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="ti ti-id me-2"></i>

                    Identitas

                </h3>

            </div>


            <div class="card-body">


                {{-- ========================================= --}}
                {{-- SISWA --}}
                {{-- ========================================= --}}

                @if($user->role === 'siswa')

                    <div class="row g-4">


                        <div class="col-12 col-md-6">

                            <div class="text-secondary small mb-1">
                                Nama Lengkap
                            </div>

                            <div class="fw-bold">

                                {{
                                    $user
                                        ->siswa
                                        ?->nama
                                    ?? $user->name
                                }}

                            </div>

                        </div>


                        <div class="col-12 col-md-6">

                            <div class="text-secondary small mb-1">
                                NIS
                            </div>

                            <div class="fw-bold">

                                {{
                                    $user
                                        ->siswa
                                        ?->nis
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        <div class="col-12 col-md-6">

                            <div class="text-secondary small mb-1">
                                NISN
                            </div>

                            <div class="fw-bold">

                                {{
                                    $user
                                        ->siswa
                                        ?->nisn
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        <div class="col-12 col-md-6">

                            <div class="text-secondary small mb-1">
                                Jenis Kelamin
                            </div>

                            <div class="fw-bold">

                                @if(
                                    $user
                                        ->siswa
                                        ?->jenis_kelamin === 'L'
                                )

                                    Laki-laki

                                @elseif(
                                    $user
                                        ->siswa
                                        ?->jenis_kelamin === 'P'
                                )

                                    Perempuan

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        <div class="col-12 col-md-6">

                            <div class="text-secondary small mb-1">
                                Kelas
                            </div>

                            <div class="fw-bold">

                                {{
                                    $user
                                        ->siswa
                                        ?->kelas
                                        ?->nama
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        <div class="col-12 col-md-6">

                            <div class="text-secondary small mb-1">
                                Tahun Ajaran
                            </div>

                            <div class="fw-bold">

                                {{
                                    $user
                                        ->siswa
                                        ?->kelas
                                        ?->tahunAjaran
                                        ?->nama
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-secondary small mb-1">
                                Alamat
                            </div>

                            <div class="fw-bold">

                                {{
                                    $user
                                        ->siswa
                                        ?->alamat
                                    ?? '-'
                                }}

                            </div>

                        </div>

                    </div>



                {{-- ========================================= --}}
                {{-- GURU --}}
                {{-- ========================================= --}}

                @elseif($user->role === 'guru')

                    <div class="row g-4">


                        <div class="col-12 col-md-6">

                            <div class="text-secondary small mb-1">
                                Nama Lengkap
                            </div>

                            <div class="fw-bold">

                                {{
                                    $user
                                        ->guru
                                        ?->nama
                                    ?? $user->name
                                }}

                            </div>

                        </div>


                        <div class="col-12 col-md-6">

                            <div class="text-secondary small mb-1">
                                NIP
                            </div>

                            <div class="fw-bold">

                                {{
                                    $user
                                        ->guru
                                        ?->nip
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        <div class="col-12 col-md-6">

                            <div class="text-secondary small mb-1">
                                Jenis Kelamin
                            </div>

                            <div class="fw-bold">

                                @if(
                                    $user
                                        ->guru
                                        ?->jenis_kelamin === 'L'
                                )

                                    Laki-laki

                                @elseif(
                                    $user
                                        ->guru
                                        ?->jenis_kelamin === 'P'
                                )

                                    Perempuan

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        <div class="col-12 col-md-6">

                            <div class="text-secondary small mb-1">
                                Nomor HP
                            </div>

                            <div class="fw-bold">

                                {{
                                    $user
                                        ->guru
                                        ?->no_hp
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-secondary small mb-1">
                                Alamat
                            </div>

                            <div class="fw-bold">

                                {{
                                    $user
                                        ->guru
                                        ?->alamat
                                    ?? '-'
                                }}

                            </div>

                        </div>

                    </div>



                {{-- ========================================= --}}
                {{-- OPERATOR --}}
                {{-- ========================================= --}}

                @elseif($user->role === 'operator')

                    <div class="row g-4">

                        <div class="col-12 col-md-6">

                            <div class="text-secondary small mb-1">
                                Nama Operator
                            </div>

                            <div class="fw-bold">

                                {{ $user->name }}

                            </div>

                        </div>


                        <div class="col-12 col-md-6">

                            <div class="text-secondary small mb-1">
                                Jabatan Sistem
                            </div>

                            <div class="fw-bold">
                                Operator
                            </div>

                        </div>


                        <div class="col-12">

                            <div
                                class="
                                    alert
                                    alert-info
                                    mb-0
                                "
                            >

                                <i class="ti ti-info-circle me-2"></i>

                                Operator memiliki akses untuk
                                mengelola data utama dan administrasi
                                sistem.

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection