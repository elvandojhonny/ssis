<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Lupa Password | SSIS</title>

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

                        <i class="ti ti-lock-question"></i>

                    </span>


                    <h2 class="h2 mb-2">
                        Lupa Password?
                    </h2>


                    <div class="text-secondary">

                        Masukkan email yang terdaftar pada akun Anda.
                        Kami akan mengirimkan link untuk membuat
                        password baru.

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- SUCCESS --}}
                {{-- ================================================= --}}

                @if(session('success'))

                    <div
                        class="
                            alert
                            alert-success
                            alert-dismissible
                        "
                        role="alert"
                    >

                        <div class="d-flex">

                            <i
                                class="
                                    ti
                                    ti-circle-check
                                    me-2
                                "
                            ></i>


                            <div>

                                {{ session('success') }}

                            </div>

                        </div>


                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                        ></button>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- FORM --}}
                {{-- ================================================= --}}

                <form
                    action="{{ route('password.email') }}"
                    method="POST"
                >

                    @csrf


                    <div class="mb-3">

                        <label class="form-label">

                            Email

                        </label>


                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="
                                form-control
                                @error('email')
                                    is-invalid
                                @enderror
                            "
                            placeholder="Masukkan email akun"
                            autocomplete="email"
                            autofocus
                            required
                        >


                        @error('email')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>



                    <div class="form-footer">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            <i class="ti ti-send me-1"></i>

                            Kirim Link Reset Password

                        </button>

                    </div>

                </form>

            </div>



            {{-- ================================================= --}}
            {{-- FOOTER --}}
            {{-- ================================================= --}}

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