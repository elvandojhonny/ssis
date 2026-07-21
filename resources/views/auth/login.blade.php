<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Login | SSIS</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>

        /* =========================================================
           PAGE
        ========================================================= */

        html,
        body {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        .login-page {
            position: relative;

            display: flex;
            align-items: center;
            justify-content: center;

            min-height: 100vh;
            min-height: 100dvh;

            padding: 40px 20px;

            overflow: hidden;
        }

        .login-container {
            position: relative;
            z-index: 2;

            width: 100%;
            max-width: 430px;
        }



        /* =========================================================
           BRAND AREA
        ========================================================= */

        .login-brand {
            margin-bottom: 28px;
            text-align: center;
        }

        .login-school-name {
            margin: 8px 0 5px;

            font-size:
                clamp(
                    1.55rem,
                    4vw,
                    2rem
                );

            font-weight: 700;
            letter-spacing: -0.035em;
        }

        .login-school-system {
            margin: 0;

            color:
                var(
                    --tblr-secondary-color,
                    #667382
                );

            font-size: 0.875rem;
            line-height: 1.6;
        }



        /* =========================================================
           FLOATING LOGO
        ========================================================= */

        .login-logo-scene {
            position: relative;

            display: flex;
            align-items: center;
            justify-content: center;

            width: 145px;
            height: 125px;

            margin: 0 auto 8px;

            perspective: 900px;
        }

        .login-logo-orbit {
            position: relative;
            z-index: 5;

            display: flex;
            align-items: center;
            justify-content: center;

            width: 110px;
            height: 110px;

            transform-style: preserve-3d;

            animation:
                logoFloat
                4s
                ease-in-out
                infinite;
        }

        .login-logo-orbit::before {
            content: "";

            position: absolute;

            top: 50%;
            left: 50%;

            width: 125px;
            height: 125px;

            border-radius: 50%;

            background:
                radial-gradient(
                    circle,

                    rgba(
                        32,
                        107,
                        196,
                        0.18
                    )
                    0%,

                    rgba(
                        32,
                        107,
                        196,
                        0.06
                    )
                    45%,

                    transparent
                    72%
                );

            transform:
                translate(
                    -50%,
                    -50%
                );

            filter: blur(10px);

            pointer-events: none;
        }

        .login-logo {
            position: relative;
            z-index: 2;

            display: block;

            width: 100px;
            height: auto;

            filter:
                drop-shadow(
                    0
                    14px
                    18px
                    rgba(
                        0,
                        0,
                        0,
                        0.15
                    )
                );

            transition:
                transform
                300ms
                ease,

                filter
                300ms
                ease;
        }

        .login-logo-scene:hover
        .login-logo {
            transform: scale(1.04);

            filter:
                drop-shadow(
                    0
                    18px
                    24px
                    rgba(
                        32,
                        107,
                        196,
                        0.22
                    )
                );
        }

        @keyframes logoFloat {

            0%,
            100% {
                transform:
                    translateY(0)
                    rotateY(-3deg);
            }

            50% {
                transform:
                    translateY(-7px)
                    rotateY(3deg);
            }

        }



        /* =========================================================
           LOGIN CARD
        ========================================================= */

        @property --login-border-angle {
            syntax: "<angle>";
            initial-value: 0deg;
            inherits: false;
        }

        .login-card {
            position: relative;
            isolation: isolate;

            padding: 1px;

            overflow: hidden;

            border: none;
            border-radius: 21px;

            background:
                rgba(
                    98,
                    105,
                    118,
                    0.15
                );

            box-shadow:
                0
                24px
                70px
                rgba(
                    24,
                    36,
                    51,
                    0.09
                ),

                0
                4px
                15px
                rgba(
                    24,
                    36,
                    51,
                    0.04
                );
        }



        /* =========================================================
           ROTATING BLUE BORDER
        ========================================================= */

        .login-card::before {
            content: "";

            position: absolute;
            z-index: -2;

            inset: -100%;

            background:
                conic-gradient(

                    from
                    var(
                        --login-border-angle
                    ),

                    transparent
                    0deg,

                    transparent
                    275deg,

                    rgba(
                        32,
                        107,
                        196,
                        0.05
                    )
                    292deg,

                    rgba(
                        32,
                        107,
                        196,
                        0.25
                    )
                    310deg,

                    #206bc4
                    328deg,

                    #8fc5ff
                    339deg,

                    #ffffff
                    343deg,

                    #206bc4
                    350deg,

                    transparent
                    360deg
                );

            animation:
                loginBorderRotate
                3.5s
                linear
                infinite;
        }

        .login-card::after {
            content: "";

            position: absolute;
            z-index: -1;

            inset: 1px;

            border-radius: 20px;

            background:
                var(
                    --tblr-bg-surface,
                    #ffffff
                );
        }

        @keyframes loginBorderRotate {

            from {
                --login-border-angle:
                    0deg;
            }

            to {
                --login-border-angle:
                    360deg;
            }

        }



        /* =========================================================
           CARD BODY
        ========================================================= */

        .login-card-body {
            position: relative;
            z-index: 2;

            padding:
                34px
                36px
                36px;

            border-radius: 20px;

            background:
                var(
                    --tblr-bg-surface,
                    #ffffff
                );
        }



        /* =========================================================
           CARD HEADER
        ========================================================= */

        .login-card-header {
            margin-bottom: 30px;

            text-align: center;
        }

        .login-card-eyebrow {
            display: inline-flex;
            align-items: center;

            gap: 7px;

            margin-bottom: 10px;

            color:
                var(
                    --tblr-primary,
                    #206bc4
                );

            font-size: 0.7rem;
            font-weight: 700;

            letter-spacing: 0.1em;

            text-transform: uppercase;
        }

        .login-card-eyebrow::before {
            content: "";

            width: 5px;
            height: 5px;

            border-radius: 50%;

            background: currentColor;

            box-shadow:
                0
                0
                0
                4px
                rgba(
                    32,
                    107,
                    196,
                    0.1
                );
        }

        .login-title {
            margin: 0 0 8px;

            font-size: 1.6rem;
            font-weight: 700;

            letter-spacing: -0.035em;
        }

        .login-description {
            max-width: 290px;

            margin: 0 auto;

            color:
                var(
                    --tblr-secondary-color,
                    #667382
                );

            font-size: 0.825rem;

            line-height: 1.65;
        }



        /* =========================================================
           FORM
        ========================================================= */

        .login-form-group {
            margin-bottom: 20px;
        }

        .login-form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 8px;

            font-size: 0.8rem;
            font-weight: 600;
        }

        .login-input {
            min-height: 48px;

            padding:
                0.75rem
                0.95rem;

            border-radius: 10px;

            transition:
                border-color
                200ms
                ease,

                box-shadow
                200ms
                ease,

                transform
                200ms
                ease;
        }

        .login-input:hover {
            border-color:
                rgba(
                    32,
                    107,
                    196,
                    0.4
                );
        }

        .login-input:focus {
            border-color:
                var(
                    --tblr-primary,
                    #206bc4
                );

            box-shadow:
                0
                0
                0
                3px
                rgba(
                    32,
                    107,
                    196,
                    0.1
                );
        }



        /* =========================================================
           OPTIONS
        ========================================================= */

        .login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            margin:
                5px
                0
                25px;
        }

        .login-remember {
            margin: 0;
        }

        .login-forgot {
            font-size: 0.8rem;
            font-weight: 500;

            text-decoration: none;

            transition:
                opacity
                200ms
                ease;
        }

        .login-forgot:hover {
            opacity: 0.7;
        }



        /* =========================================================
           LOGIN BUTTON
        ========================================================= */

        .login-button {
            position: relative;

            min-height: 49px;

            overflow: hidden;

            border-radius: 10px;

            font-weight: 600;

            letter-spacing: -0.01em;

            box-shadow:
                0
                8px
                20px
                rgba(
                    32,
                    107,
                    196,
                    0.18
                );

            transition:
                transform
                200ms
                ease,

                box-shadow
                200ms
                ease;
        }

        .login-button:hover {
            transform:
                translateY(-1px);

            box-shadow:
                0
                12px
                25px
                rgba(
                    32,
                    107,
                    196,
                    0.24
                );
        }

        .login-button:active {
            transform:
                translateY(0);
        }



        /* =========================================================
           FOOTER NOTE
        ========================================================= */

        .login-footer-note {
            display: flex;
            align-items: center;
            justify-content: center;

            gap: 7px;

            margin-top: 22px;

            color:
                var(
                    --tblr-secondary-color,
                    #667382
                );

            font-size: 0.7rem;
        }

        .login-footer-dot {
            width: 5px;
            height: 5px;

            flex-shrink: 0;

            border-radius: 50%;

            background: #2fb344;

            box-shadow:
                0
                0
                0
                3px
                rgba(
                    47,
                    179,
                    68,
                    0.1
                );
        }



        /* =========================================================
           MOBILE
        ========================================================= */

        @media (
            max-width:
            575.98px
        ) {

            .login-page {
                align-items: flex-start;

                padding:
                    24px
                    15px
                    35px;
            }

            .login-container {
                max-width: 100%;
            }

            .login-brand {
                margin-bottom: 20px;
            }

            .login-logo-scene {
                width: 120px;
                height: 105px;

                margin-bottom: 5px;
            }

            .login-logo-orbit {
                width: 90px;
                height: 90px;
            }

            .login-logo-orbit::before {
                width: 105px;
                height: 105px;
            }

            .login-logo {
                width: 82px;
            }

            .login-school-name {
                font-size: 1.5rem;
            }

            .login-school-system {
                font-size: 0.8rem;
            }

            .login-card {
                border-radius: 17px;
            }

            .login-card::after {
                border-radius: 16px;
            }

            .login-card-body {
                padding:
                    28px
                    22px
                    26px;

                border-radius: 16px;
            }

            .login-card-header {
                margin-bottom: 25px;
            }

            .login-title {
                font-size: 1.4rem;
            }

            .login-description {
                max-width: 260px;
            }

            .login-input {
                min-height: 47px;

                font-size: 16px;
            }

            .login-options {
                gap: 10px;
            }

            .login-forgot {
                white-space: nowrap;
            }

        }



        /* =========================================================
           VERY SMALL MOBILE
        ========================================================= */

        @media (
            max-width:
            360px
        ) {

            .login-page {
                padding-left: 12px;
                padding-right: 12px;
            }

            .login-card-body {
                padding:
                    25px
                    18px;
            }

            .login-options {
                align-items: flex-start;

                flex-direction: column;
            }

        }



        /* =========================================================
           REDUCED MOTION
        ========================================================= */

        @media (
            prefers-reduced-motion:
            reduce
        ) {

            .login-logo-orbit,
            .login-card::before {
                animation: none;
            }

        }

    </style>

</head>


<body>


    <main class="login-page">


        <div class="login-container">


            <!-- =====================================================
                 SCHOOL IDENTITY
            ====================================================== -->

            <div class="login-brand">


                <!-- LOGO -->

                <div class="login-logo-scene">


                    <div class="login-logo-orbit">


                        <img
                            src="{{ asset('images/logo SMAN 6.png') }}"
                            alt="Logo SMA Negeri 6"
                            class="login-logo"
                        >


                    </div>


                </div>



                <h1 class="login-school-name">
                    SMA Negeri 6
                </h1>


                <p class="login-school-system">
                    Smart School Information System
                </p>


            </div>




            <!-- =====================================================
                 LOGIN CARD
            ====================================================== -->

            <div class="card login-card">


                <div class="login-card-body">


                    <!-- HEADER -->

                    <div class="login-card-header">


                        <div class="login-card-eyebrow">
                            Portal SSIS
                        </div>


                        <h2 class="login-title">
                            Selamat Datang
                        </h2>


                        <p class="login-description">
                            Masuk menggunakan akun Anda untuk mengakses
                            sistem informasi sekolah.
                        </p>


                    </div>




                    <!-- FORM -->

                    <form
                        action="{{ route('login.process') }}"
                        method="POST"
                    >


                        @csrf



                        <!-- USERNAME -->

                        <div class="login-form-group">


                            <label
                                for="username"
                                class="form-label login-form-label"
                            >
                                Username
                            </label>


                            <input
                                id="username"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                class="
                                    form-control
                                    login-input
                                    @error('username')
                                        is-invalid
                                    @enderror
                                "
                                placeholder="Masukkan username"
                                autocomplete="username"
                                autofocus
                                required
                            >


                            @error('username')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror


                        </div>




                        <!-- PASSWORD -->

                        <div class="login-form-group">


                            <label
                                for="password"
                                class="form-label login-form-label"
                            >
                                Password
                            </label>


                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="
                                    form-control
                                    login-input
                                "
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required
                            >


                        </div>




                        <!-- OPTIONS -->

                        <div class="login-options">


                            <label
                                class="
                                    form-check
                                    login-remember
                                "
                            >


                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="form-check-input"
                                >


                                <span class="form-check-label">
                                    Ingat saya
                                </span>


                            </label>



                            <a
                                href="{{ route('password.request') }}"
                                class="login-forgot"
                            >
                                Lupa Password?
                            </a>


                        </div>




                        <!-- SUBMIT -->

                        <button
                            type="submit"
                            class="
                                btn
                                btn-primary
                                login-button
                                w-100
                            "
                        >
                            Masuk ke Sistem
                        </button>


                    </form>


                </div>


            </div>




            <!-- =====================================================
                 FOOTER
            ====================================================== -->

            <div class="login-footer-note">


                <span
                    class="login-footer-dot"
                ></span>


                <span>
                    Sistem informasi sekolah terintegrasi
                </span>


            </div>


        </div>


    </main>


</body>

</html>
```
