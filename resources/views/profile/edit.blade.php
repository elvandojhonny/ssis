@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')


{{-- ========================================================= --}}
{{-- PAGE HEADER --}}
{{-- ========================================================= --}}

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Akun Saya
            </div>

            <h2 class="page-title">
                Edit Profil
            </h2>

            <div class="text-secondary mt-1">
                Perbarui informasi profil dan akun operator.
            </div>

        </div>


        <div class="col-12 col-md-auto">

            <a
                href="{{ route('profile.show') }}"
                class="btn btn-outline-secondary w-100"
            >
                <i class="ti ti-arrow-left me-1"></i>

                Kembali
            </a>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- FORM EDIT PROFIL --}}
{{-- ========================================================= --}}

<div class="row justify-content-center">

    <div class="col-12 col-lg-8">

        <form
            action="{{ route('profile.update') }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div class="card">

                {{-- HEADER CARD --}}
                <div class="card-header">

                    <div>

                        <h3 class="card-title">

                            <i class="ti ti-user-edit me-2"></i>

                            Informasi Profil

                        </h3>

                        <div class="text-secondary small mt-1">

                            Informasi ini digunakan sebagai
                            identitas akun operator di dalam sistem.

                        </div>

                    </div>

                </div>


                {{-- BODY --}}
                <div class="card-body">


                    {{-- ================================================= --}}
                    {{-- NAMA --}}
                    {{-- ================================================= --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Nama Operator

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input
                            type="text"
                            name="name"
                            value="{{
                                old(
                                    'name',
                                    $user->name
                                )
                            }}"
                            class="
                                form-control
                                @error('name')
                                    is-invalid
                                @enderror
                            "
                            placeholder="Masukkan nama operator"
                            required
                        >


                        @error('name')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>



                    <div class="row">


                        {{-- ================================================= --}}
                        {{-- USERNAME --}}
                        {{-- ================================================= --}}

                        <div class="col-12 col-md-6 mb-3">

                            <label class="form-label">

                                Username

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                name="username"
                                value="{{
                                    old(
                                        'username',
                                        $user->username
                                    )
                                }}"
                                class="
                                    form-control
                                    @error('username')
                                        is-invalid
                                    @enderror
                                "
                                placeholder="Masukkan username"
                                required
                            >


                            @error('username')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror


                            <div class="form-hint">

                                Username digunakan untuk
                                masuk ke dalam sistem.

                            </div>

                        </div>



                        {{-- ================================================= --}}
                        {{-- EMAIL --}}
                        {{-- ================================================= --}}

                        <div class="col-12 col-md-6 mb-3">

                            <label class="form-label">

                                Email

                            </label>


                            <input
                                type="email"
                                name="email"
                                value="{{
                                    old(
                                        'email',
                                        $user->email
                                    )
                                }}"
                                class="
                                    form-control
                                    @error('email')
                                        is-invalid
                                    @enderror
                                "
                                placeholder="Masukkan email operator"
                            >


                            @error('email')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror


                            <div class="form-hint">

                                Email akan digunakan untuk
                                pemulihan akun jika lupa password.

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- FOOTER --}}
                {{-- ================================================= --}}

                <div class="card-footer">

                    <div
                        class="
                            d-flex
                            flex-column-reverse
                            flex-sm-row
                            justify-content-end
                            gap-2
                        "
                    >

                        <a
                            href="{{ route('profile.show') }}"
                            class="btn btn-outline-secondary"
                        >
                            Batal
                        </a>


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i
                                class="
                                    ti
                                    ti-device-floppy
                                    me-1
                                "
                            ></i>

                            Simpan Perubahan

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection