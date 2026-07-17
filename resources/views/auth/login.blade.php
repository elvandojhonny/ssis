<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login | SSIS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

        <div class="text-center mb-4">

            <!-- Logo -->
            <img
                src="{{ asset('images/logo SMAN 6.png') }}"
                alt="Logo SSIS"
                class="login-logo mb-3"
            >

                <h1 class="fw-bold mb-1">
                    SMA Negeri 6
                </h1>

                <p class="text-secondary mb-0">
                    Smart School Information System
                </p>

            </div>

        <div class="card card-md">

            <div class="card-body">

                <h2 class="h2 text-center mb-4">
                    Login
                </h2>

                <form action="{{ route('login.process') }}"
                      method="POST">

                    @csrf

                    <div class="mb-3">

                        <label class="form-label">
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="Masukkan username"
                            autofocus
                            required
                        >

                        @error('username')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-check">

                            <input
                                type="checkbox"
                                name="remember"
                                class="form-check-input"
                            >

                            <span class="form-check-label">
                                Ingat saya
                            </span>

                        </label>

                    </div>

                    <div class="form-footer">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Masuk
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>