<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Reset Password | SSIS</title>


    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    <style>

        .login-logo {
            width: 100px;
            height: auto;
            transition: .3s;
        }

        .login-logo:hover {
            transform: scale(1.05);
        }

    </style>

</head>


<body class="d-flex flex-column">

<div class="page page-center">

    <div class="container container-tight py-4">


        {{-- ================================================= --}}
        {{-- LOGO --}}
        {{-- ================================================= --}}

        <div class="text-center mb-4">

            <img
                src="{{ asset('images/logo SMAN 6.png') }}"
                alt="Logo SMA Negeri 6"
                class="login-logo mb-3"
            >


            <h1 class="fw-bold mb-1">
                SMA Negeri 6
            </h1>


            <p class="text-secondary mb-0">
                Smart School Information System
            </p>

        </div>



        {{-- ================================================= --}}
        {{-- CARD --}}
        {{-- ================================================= --}}

        <div class="card card-md">

            <div class="card-body">


                <div class="text-center mb-4">

                    <span
                        class="
                            avatar
                            avatar-xl
                            bg-primary-lt
                            mb-3
                        "
                    >

                        <i class="ti ti-lock"></i>

                    </span>


                    <h2 class="h2 mb-2">

                        Buat Password Baru

                    </h2>


                    <div class="text-secondary">

                        Masukkan password baru yang akan
                        digunakan untuk masuk ke akun Anda.

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- ERROR --}}
                {{-- ================================================= --}}

                @if($errors->any())

                    <div class="alert alert-danger">

                        <div class="d-flex">

                            <i
                                class="
                                    ti
                                    ti-alert-circle
                                    me-2
                                "
                            ></i>


                            <div>

                                Link tidak valid atau terdapat
                                kesalahan pada data yang dimasukkan.

                            </div>

                        </div>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- FORM --}}
                {{-- ================================================= --}}

                <form
                    action="{{ route('password.update') }}"
                    method="POST"
                >

                    @csrf


                    <input
                        type="hidden"
                        name="token"
                        value="{{ $token }}"
                    >



                    {{-- EMAIL --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>


                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $email) }}"
                            class="
                                form-control
                                @error('email')
                                    is-invalid
                                @enderror
                            "
                            autocomplete="email"
                            required
                        >


                        @error('email')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>



                    {{-- PASSWORD BARU --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Password Baru

                        </label>


                        <input
                            type="password"
                            name="password"
                            class="
                                form-control
                                @error('password')
                                    is-invalid
                                @enderror
                            "
                            placeholder="Minimal 8 karakter"
                            autocomplete="new-password"
                            required
                        >


                        @error('password')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>



                    {{-- KONFIRMASI PASSWORD --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Konfirmasi Password Baru

                        </label>


                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Masukkan ulang password baru"
                            autocomplete="new-password"
                            required
                        >

                    </div>



                    <div class="form-footer">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            <i
                                class="
                                    ti
                                    ti-device-floppy
                                    me-1
                                "
                            ></i>

                            Simpan Password Baru

                        </button>

                    </div>

                </form>

            </div>


            <div class="card-footer text-center">

                <a
                    href="{{ route('login') }}"
                    class="text-decoration-none"
                >

                    <i class="ti ti-arrow-left me-1"></i>

                    Kembali ke Login

                </a>

            </div>

        </div>

    </div>

</div>

</body>

</html>