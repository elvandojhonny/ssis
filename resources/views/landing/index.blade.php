<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        SSIS - SMA Negeri 6 Malinau
    </title>

    <meta
        name="description"
        content="SSIS SMA Negeri 6 Malinau adalah sistem informasi sekolah berbasis web untuk mendukung administrasi, akademik, absensi, perpustakaan, CBT, dan layanan digital sekolah."
    >

    <meta
        name="author"
        content="SMA Negeri 6 Malinau"
    >

    <meta
        name="keywords"
        content="SSIS, SMA Negeri 6 Malinau, Sistem Informasi Sekolah, Smart School Information System, sekolah Malinau"
    >

    <meta
        property="og:type"
        content="website"
    >

    <meta
        property="og:title"
        content="SSIS SMA Negeri 6 Malinau | Smart School Information System"
    >

    <meta
        property="og:description"
        content="Sistem informasi sekolah SMA Negeri 6 Malinau untuk mendukung administrasi, akademik, absensi, perpustakaan, CBT, dan layanan digital sekolah."
    >

    <meta
        property="og:url"
        content="https://ssis-production.up.railway.app/"
    >

    <meta
        property="og:site_name"
        content="SSIS SMA Negeri 6 Malinau"
    >

    <meta
        property="og:image"
        content="{{ asset('images/logo SMAN 6.png') }}"
    >

    <meta
        name="robots"
        content="index, follow"
    >

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/logo SMAN 6.png') }}"
    >

    <link
        rel="apple-touch-icon"
        href="{{ asset('images/logo SMAN 6.png') }}"
    >

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    {{-- Google Font --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "SSIS SMA Negeri 6 Malinau",
        "alternateName": "SSIS",
        "url": "https://ssis-production.up.railway.app/"
    }
    </script>

    <style>

        :root {

            --primary: #206BC4;
            --primary-dark: #174A87;
            --primary-light: #EAF3FF;

            --success: #2FB344;
            --warning: #F59F00;
            --info: #0EA5E9;

            --light: #F5F8FC;
            --dark: #172033;
            --muted: #6B7280;

            --radius: 22px;

        }


        /* ==========================================================
           GLOBAL
        ========================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
        }


        body {

            font-family: 'Poppins', sans-serif;

            background: var(--light);

            color: var(--dark);

            overflow-x: hidden;

        }


        body::selection {

            background: var(--primary);

            color: #fff;

        }


        a {
            text-decoration: none;
        }


        section {
            padding: 100px 0;
        }


        .section-badge {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            background: var(--primary-light);

            color: var(--primary);

            padding: 8px 18px;

            border-radius: 50px;

            font-size: .85rem;

            font-weight: 600;

            margin-bottom: 18px;

        }


        .section-title {

            font-size: 2.5rem;

            font-weight: 700;

            margin-bottom: 20px;

            letter-spacing: -.5px;

        }


        .section-subtitle {

            color: var(--muted);

            max-width: 700px;

            margin: auto;

            line-height: 1.9;

        }


        /* ==========================================================
           SCROLLBAR
        ========================================================== */

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {

            background: var(--primary);

            border-radius: 20px;

        }


        /* ==========================================================
           NAVBAR
        ========================================================== */

        .navbar {

            background: rgba(255, 255, 255, .88);

            backdrop-filter: blur(18px);

            -webkit-backdrop-filter: blur(18px);

            padding: 13px 0;

            z-index: 9999;

            transition: all .35s ease;

        }


        .navbar.scrolled {

            background: rgba(255, 255, 255, .97);

            box-shadow: 0 12px 35px rgba(31, 41, 55, .10);

            padding: 9px 0;

        }


        .navbar-brand {

            display: flex;

            align-items: center;

            gap: 11px;

            color: var(--dark);

            font-weight: 800;

            font-size: 1.15rem;

        }


        .navbar-brand:hover {
            color: var(--primary);
        }


        .navbar-brand img {

            width: 48px;

            height: 48px;

            object-fit: contain;

            transition: .3s;

        }


        .navbar.scrolled .navbar-brand img {

            width: 42px;

            height: 42px;

        }


        .navbar-brand small {

            display: block;

            color: var(--muted);

            font-size: .68rem;

            font-weight: 500;

            margin-top: 1px;

        }


        .nav-link {

            position: relative;

            color: var(--dark);

            font-weight: 500;

            margin-left: 15px;

            transition: .3s;

        }


        .nav-link::after {

            content: "";

            position: absolute;

            left: 0;

            bottom: 2px;

            width: 0;

            height: 2px;

            background: var(--primary);

            border-radius: 10px;

            transition: .3s;

        }


        .nav-link:hover {

            color: var(--primary);

        }


        .nav-link:hover::after {

            width: 100%;

        }


        .navbar-toggler {

            border: 0;

            padding: 7px;

            box-shadow: none !important;

        }


        .navbar-toggler:focus {

            box-shadow: none;

        }


        .btn-login {

            padding: 10px 22px;

            border-radius: 12px;

            font-weight: 600;

            transition: .3s;

        }


        .btn-login:hover {

            transform: translateY(-2px);

        }


        .btn-guide {

            border-radius: 12px;

            font-weight: 600;

            padding: 10px 20px;

            transition: .3s;

        }


        .btn-guide:hover {

            transform: translateY(-2px);

        }


        /* ==========================================================
           HERO
        ========================================================== */

        .hero {

            position: relative;

            min-height: 100vh;

            display: flex;

            align-items: center;

            overflow: hidden;

            padding-top: 120px;

            padding-bottom: 80px;

            background:

                radial-gradient(
                    circle at 85% 10%,
                    rgba(32, 107, 196, .16),
                    transparent 30%
                ),

                radial-gradient(
                    circle at 5% 90%,
                    rgba(14, 165, 233, .10),
                    transparent 30%
                ),

                linear-gradient(
                    135deg,
                    #F8FBFF,
                    #EEF5FF
                );

        }


        .hero::before {

            content: "";

            position: absolute;

            width: 450px;

            height: 450px;

            border-radius: 50%;

            background: rgba(32, 107, 196, .06);

            top: -200px;

            right: -150px;

            animation: floatCircle 8s ease-in-out infinite;

        }


        .hero::after {

            content: "";

            position: absolute;

            width: 300px;

            height: 300px;

            border-radius: 50%;

            background: rgba(47, 179, 68, .05);

            bottom: -160px;

            left: -100px;

            animation: floatCircle 10s ease-in-out infinite reverse;

        }


        .hero .container {

            position: relative;

            z-index: 2;

        }


        .hero-content {

            animation: fadeUp .8s ease both;

        }


        .hero h1 {

            font-size: clamp(2.3rem, 5vw, 3.8rem);

            font-weight: 800;

            line-height: 1.18;

            letter-spacing: -1px;

        }


        .hero h1 span {

            color: var(--primary);

            position: relative;

        }


        .hero h1 span::after {

            content: "";

            position: absolute;

            left: 0;

            bottom: -7px;

            width: 70%;

            height: 5px;

            border-radius: 20px;

            background: linear-gradient(
                90deg,
                var(--primary),
                transparent
            );

        }


        .hero p {

            color: var(--muted);

            margin-top: 25px;

            line-height: 1.95;

            font-size: 1.03rem;

            max-width: 650px;

        }


        .hero-buttons {

            margin-top: 35px;

            display: flex;

            gap: 13px;

            flex-wrap: wrap;

        }


        .btn-primary-custom {

            background: var(--primary);

            color: #fff;

            border: none;

            padding: 14px 27px;

            border-radius: 15px;

            transition: .3s;

            font-weight: 600;

            box-shadow: 0 10px 25px rgba(32, 107, 196, .22);

        }


        .btn-primary-custom:hover {

            background: var(--primary-dark);

            color: #fff;

            transform: translateY(-4px);

            box-shadow: 0 15px 30px rgba(32, 107, 196, .30);

        }


        .btn-outline-custom {

            background: rgba(255,255,255,.8);

            color: var(--primary);

            border: 2px solid var(--primary);

            padding: 12px 27px;

            border-radius: 15px;

            transition: .3s;

            font-weight: 600;

        }


        .btn-outline-custom:hover {

            background: var(--primary);

            color: #fff;

            transform: translateY(-3px);

        }


        /* ==========================================================
           HERO FEATURE CARDS
        ========================================================== */

        .hero-feature {

            border: 1px solid rgba(32,107,196,.07);

            border-radius: 18px;

            background: rgba(255,255,255,.90);

            backdrop-filter: blur(10px);

            transition: .35s;

            overflow: hidden;

        }


        .hero-feature:hover {

            transform: translateY(-7px);

            box-shadow: 0 18px 35px rgba(31,41,55,.10) !important;

            border-color: rgba(32,107,196,.15);

        }


        .hero-feature .card-body {

            padding: 20px;

        }


        .hero-feature-icon {

            width: 48px;

            height: 48px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 14px;

            background: var(--primary-light);

        }


        /* ==========================================================
           HERO IMAGE
        ========================================================== */

        .hero-image-wrapper {

            position: relative;

            animation: fadeRight .9s ease both;

        }


        .hero-image {

            width: 100%;

            height: 500px;

            object-fit: cover;

            border-radius: 30px;

            box-shadow: 0 30px 70px rgba(31,41,55,.18);

            transition: transform .5s ease;

        }


        .hero-image-wrapper:hover .hero-image {

            transform: scale(1.015);

        }


        .floating-card {

            position: absolute;

            background: rgba(255,255,255,.95);

            backdrop-filter: blur(15px);

            box-shadow: 0 18px 40px rgba(31,41,55,.15);

            border-radius: 18px;

            padding: 15px 18px;

            z-index: 5;

            animation: floating 4s ease-in-out infinite;

        }


        .floating-card.top-card {

            top: 25px;

            left: -35px;

        }


        .floating-card.bottom-card {

            bottom: 25px;

            right: -35px;

            animation-delay: 1s;

        }


        .floating-icon {

            width: 45px;

            height: 45px;

            border-radius: 13px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 1.25rem;

        }


        /* ==========================================================
           STATISTICS
        ========================================================== */

        .stat-card {

            position: relative;

            border: 0;

            border-radius: 22px;

            overflow: hidden;

            transition: .35s;

            background: #fff;

        }


        .stat-card::before {

            content: "";

            position: absolute;

            width: 100%;

            height: 4px;

            left: 0;

            top: 0;

            background: linear-gradient(
                90deg,
                var(--primary),
                #65A7FF
            );

        }


        .stat-card:hover {

            transform: translateY(-8px);

            box-shadow: 0 20px 45px rgba(31,41,55,.12) !important;

        }


        .stat-icon {

            width: 70px;

            height: 70px;

            margin: auto;

            border-radius: 20px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 2rem;

        }


        .stat-number {

            font-size: 2.5rem;

            font-weight: 800;

            margin-top: 18px;

        }


        /* ==========================================================
           ABOUT
        ========================================================== */

        .about-image-wrapper {

            position: relative;

        }


        .about-image-wrapper img {

            width: 100%;

            max-height: 480px;

            object-fit: cover;

            border-radius: 28px;

            transition: .4s;

        }


        .about-image-wrapper:hover img {

            transform: translateY(-5px);

        }


        .about-badge {

            position: absolute;

            right: -20px;

            bottom: 25px;

            background: #fff;

            padding: 18px;

            border-radius: 18px;

            box-shadow: 0 15px 35px rgba(31,41,55,.15);

        }


        .about-item {

            padding: 14px;

            border-radius: 15px;

            transition: .3s;

        }


        .about-item:hover {

            background: #fff;

            box-shadow: 0 10px 25px rgba(31,41,55,.07);

            transform: translateX(4px);

        }


        /* ==========================================================
           CTA
        ========================================================== */

        .cta-box {

            position: relative;

            overflow: hidden;

            border-radius: 28px;

            padding: 70px 30px;

            background:
                radial-gradient(
                    circle at top right,
                    rgba(255,255,255,.22),
                    transparent 30%
                ),
                linear-gradient(
                    135deg,
                    #206BC4,
                    #4F8FEF
                );

            box-shadow: 0 25px 60px rgba(32,107,196,.20);

        }


        .cta-box::before {

            content: "";

            position: absolute;

            width: 220px;

            height: 220px;

            border: 35px solid rgba(255,255,255,.07);

            border-radius: 50%;

            right: -60px;

            top: -80px;

        }


        .cta-box::after {

            content: "";

            position: absolute;

            width: 150px;

            height: 150px;

            border: 25px solid rgba(255,255,255,.07);

            border-radius: 50%;

            left: -50px;

            bottom: -70px;

        }


        .cta-content {

            position: relative;

            z-index: 2;

        }


        /* ==========================================================
           FOOTER
        ========================================================== */

        footer {

            background: #fff;

        }


        /* ==========================================================
           MODAL
        ========================================================== */

        .modal-content {

            border-radius: 20px;

            overflow: hidden;

        }


        .modal-header {

            padding: 18px 22px;

        }


        .pdf-frame {

            width: 100%;

            height: 650px;

            border: none;

            display: block;

        }


        /* ==========================================================
           SCROLL ANIMATION
        ========================================================== */

        .reveal {

            opacity: 0;

            transform: translateY(35px);

            transition:
                opacity .7s ease,
                transform .7s ease;

        }


        .reveal.show {

            opacity: 1;

            transform: translateY(0);

        }


        .delay-1 {
            transition-delay: .1s;
        }

        .delay-2 {
            transition-delay: .2s;
        }

        .delay-3 {
            transition-delay: .3s;
        }


        /* ==========================================================
           ANIMATION
        ========================================================== */

        @keyframes fadeUp {

            from {

                opacity: 0;

                transform: translateY(30px);

            }

            to {

                opacity: 1;

                transform: translateY(0);

            }

        }


        @keyframes fadeRight {

            from {

                opacity: 0;

                transform: translateX(40px);

            }

            to {

                opacity: 1;

                transform: translateX(0);

            }

        }


        @keyframes floating {

            0%,
            100% {

                transform: translateY(0);

            }

            50% {

                transform: translateY(-10px);

            }

        }


        @keyframes floatCircle {

            0%,
            100% {

                transform: translate(0,0);

            }

            50% {

                transform: translate(-20px,20px);

            }

        }


        /* ==========================================================
           TABLET
        ========================================================== */

        @media (max-width: 991.98px) {

            section {

                padding: 80px 0;

            }


            .navbar-collapse {

                margin-top: 12px;

                padding: 15px;

                border-radius: 18px;

                background: rgba(255,255,255,.98);

                box-shadow: 0 15px 35px rgba(31,41,55,.10);

            }


            .nav-link {

                margin: 0;

                padding: 11px 10px !important;

            }


            .nav-link::after {

                display: none;

            }


            .hero {

                padding-top: 130px;

                padding-bottom: 70px;

            }


            .hero-content {

                text-align: center;

            }


            .hero p {

                margin-left: auto;

                margin-right: auto;

            }


            .hero-buttons {

                justify-content: center;

            }


            .hero-image-wrapper {

                margin-top: 20px;

            }


            .hero-image {

                height: 430px;

            }


            .floating-card.top-card {

                left: 15px;

            }


            .floating-card.bottom-card {

                right: 15px;

            }


            .about-badge {

                right: 15px;

            }

        }


        /* ==========================================================
           MOBILE
        ========================================================== */

        @media (max-width: 767.98px) {

            section {

                padding: 65px 0;

            }


            .section-title {

                font-size: 1.9rem;

            }


            .section-subtitle {

                font-size: .9rem;

                line-height: 1.8;

            }


            .navbar {

                padding: 9px 0;

            }


            .navbar-brand img {

                width: 42px;

                height: 42px;

            }


            .navbar-brand {

                font-size: 1rem;

            }


            .navbar-brand small {

                font-size: .59rem;

            }


            .hero {

                min-height: auto;

                padding-top: 115px;

                padding-bottom: 60px;

            }


            .hero h1 {

                font-size: 2.15rem;

                letter-spacing: -.5px;

            }


            .hero h1 span::after {

                height: 3px;

                bottom: -4px;

            }


            .hero p {

                font-size: .9rem;

                line-height: 1.8;

                margin-top: 20px;

            }


            .hero-buttons {

                flex-direction: column;

                gap: 10px;

                margin-top: 28px;

            }


            .hero-buttons a,
            .hero-buttons button {

                width: 100%;

                justify-content: center;

            }


            .hero-feature .card-body {

                padding: 16px;

            }


            .hero-feature h6 {

                font-size: .88rem;

            }


            .hero-feature small {

                font-size: .72rem;

            }


            .hero-feature-icon {

                width: 42px;

                height: 42px;

                font-size: 1.15rem;

            }


            .hero-image {

                height: 300px;

                border-radius: 22px;

            }


            .floating-card {

                padding: 10px 12px;

                border-radius: 14px;

                max-width: 190px;

            }


            .floating-card.top-card {

                top: 12px;

                left: 10px;

            }


            .floating-card.bottom-card {

                bottom: 12px;

                right: 10px;

            }


            .floating-icon {

                width: 35px;

                height: 35px;

                font-size: .95rem;

            }


            .floating-card strong {

                font-size: .78rem;

            }


            .floating-card small {

                font-size: .62rem;

            }


            .stat-card .card-body {

                padding: 35px 20px !important;

            }


            .stat-icon {

                width: 58px;

                height: 58px;

                border-radius: 16px;

                font-size: 1.6rem;

            }


            .stat-number {

                font-size: 2rem;

            }


            .about-image-wrapper img {

                max-height: 320px;

                border-radius: 20px;

            }


            .about-badge {

                padding: 12px;

                bottom: 15px;

                right: 10px;

            }


            .about-item {

                padding: 10px;

            }


            .about-item strong {

                font-size: .85rem;

            }


            .about-item small {

                font-size: .7rem;

            }


            .cta-box {

                padding: 50px 20px;

                border-radius: 22px;

            }


            .cta-box h2 {

                font-size: 1.65rem;

            }


            .cta-box p {

                font-size: .85rem;

                line-height: 1.8;

            }


            .pdf-frame {

                height: 70vh;

                min-height: 420px;

            }


            .modal-dialog {

                margin: 10px;

            }


            .modal-footer {

                flex-direction: column;

            }


            .modal-footer a,
            .modal-footer button {

                width: 100%;

            }


            footer {

                text-align: center;

            }

        }


        /* ==========================================================
           SMALL MOBILE
        ========================================================== */

        @media (max-width: 400px) {

            .hero h1 {

                font-size: 1.9rem;

            }


            .hero-image {

                height: 250px;

            }


            .floating-card {

                transform: scale(.88);

            }


            .floating-card.top-card {

                transform-origin: top left;

            }


            .floating-card.bottom-card {

                transform-origin: bottom right;

            }


            .hero-feature .card-body {

                padding: 14px 10px;

            }


            .hero-feature-icon {

                width: 38px;

                height: 38px;

            }


            .hero-feature h6 {

                font-size: .78rem;

            }


            .hero-feature small {

                font-size: .65rem;

            }

        }


        /* ==========================================================
           REDUCED MOTION
        ========================================================== */

        @media (prefers-reduced-motion: reduce) {

            * {

                animation-duration: .01ms !important;

                animation-iteration-count: 1 !important;

                scroll-behavior: auto !important;

                transition-duration: .01ms !important;

            }

        }

    </style>

</head>


<body>


{{-- ==========================================================
| NAVBAR
========================================================== --}}

<nav class="navbar navbar-expand-lg fixed-top">

    <div class="container">

        <a
            href="#beranda"
            class="navbar-brand">

            <img
                src="{{ asset('images/logo SMAN 6.png') }}"
                alt="Logo SMA Negeri 6 Malinau">

            <div>

                SSIS

                <small>
                    SMA Negeri 6 Malinau
                </small>

            </div>

        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
            aria-controls="navbarMenu"
            aria-expanded="false"
            aria-label="Buka menu">

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="navbarMenu">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">

                    <a
                        href="#beranda"
                        class="nav-link">

                        Beranda

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        href="#statistik"
                        class="nav-link">

                        Statistik

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        href="#tentang"
                        class="nav-link">

                        Tentang

                    </a>

                </li>


                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">

                    <button
                        type="button"
                        class="btn btn-outline-primary btn-guide w-100"
                        data-bs-toggle="modal"
                        data-bs-target="#panduanModal">

                        <i class="bi bi-book me-2"></i>

                        Panduan

                    </button>

                </li>


                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">

                    <a
                        href="{{ route('login') }}"
                        class="btn btn-primary btn-login w-100">

                        <i class="bi bi-box-arrow-in-right me-2"></i>

                        Login

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>



{{-- ==========================================================
| HERO
========================================================== --}}

<section
    id="beranda"
    class="hero">

    <div class="container">

        <div class="row align-items-center gy-5">

            <div class="col-lg-6">

                <div class="hero-content">

                    <span class="section-badge">

                        <i class="bi bi-mortarboard-fill"></i>

                        Smart School Information System

                    </span>


                    <h1>

                        Digitalisasi

                        <span>
                            SMA Negeri 6 Malinau
                        </span>

                    </h1>


                    <p>

                        SSIS merupakan sistem informasi sekolah terpadu
                        yang mengintegrasikan
                        <strong>Absensi Digital</strong>,
                        <strong>Computer Based Test (CBT)</strong>,
                        dan
                        <strong>Perpustakaan Digital</strong>
                        dalam satu platform modern.

                        Semua layanan sekolah menjadi lebih
                        <strong>mudah, cepat, dan terintegrasi.</strong>

                    </p>


                    <div class="hero-buttons">

                        <a
                            href="{{ route('login') }}"
                            class="btn btn-primary-custom">

                            <i class="bi bi-box-arrow-in-right me-2"></i>

                            Masuk ke Sistem

                        </a>


                        <button
                            type="button"
                            class="btn btn-outline-custom"
                            data-bs-toggle="modal"
                            data-bs-target="#panduanModal">

                            <i class="bi bi-book me-2"></i>

                            Buku Panduan

                        </button>

                    </div>


                    {{-- FEATURE CARDS --}}

                    <div class="row mt-4 g-3">

                        <div class="col-6">

                            <div class="card hero-feature border-0 shadow-sm h-100">

                                <div class="card-body">

                                    <div class="hero-feature-icon">

                                        <i class="bi bi-qr-code-scan text-primary fs-5"></i>

                                    </div>

                                    <h6 class="fw-bold mt-3 mb-1">

                                        QR Attendance

                                    </h6>

                                    <small class="text-muted">

                                        Presensi cepat & realtime.

                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="card hero-feature border-0 shadow-sm h-100">

                                <div class="card-body">

                                    <div
                                        class="hero-feature-icon"
                                        style="background:#EAF8EE;">

                                        <i class="bi bi-laptop text-success fs-5"></i>

                                    </div>

                                    <h6 class="fw-bold mt-3 mb-1">

                                        CBT Online

                                    </h6>

                                    <small class="text-muted">

                                        Ujian berbasis komputer.

                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="card hero-feature border-0 shadow-sm h-100">

                                <div class="card-body">

                                    <div
                                        class="hero-feature-icon"
                                        style="background:#FFF4DE;">

                                        <i class="bi bi-book-half text-warning fs-5"></i>

                                    </div>

                                    <h6 class="fw-bold mt-3 mb-1">

                                        Digital Library

                                    </h6>

                                    <small class="text-muted">

                                        Koleksi buku sekolah.

                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="card hero-feature border-0 shadow-sm h-100">

                                <div class="card-body">

                                    <div
                                        class="hero-feature-icon"
                                        style="background:#E7F7FC;">

                                        <i class="bi bi-shield-check text-info fs-5"></i>

                                    </div>

                                    <h6 class="fw-bold mt-3 mb-1">

                                        Aman

                                    </h6>

                                    <small class="text-muted">

                                        Hak akses terkontrol.

                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- HERO IMAGE --}}

            <div class="col-lg-6">

                <div class="hero-image-wrapper">

                    <img
                        src="{{ asset('images/sekolahsma.jpeg') }}"
                        class="hero-image"
                        alt="SMA Negeri 6 Malinau"
                        loading="eager">


                    {{-- Floating Card Atas --}}

                    <div class="floating-card top-card">

                        <div class="d-flex align-items-center">

                            <div
                                class="floating-icon bg-primary-subtle text-primary me-3">

                                <i class="bi bi-grid-1x2-fill"></i>

                            </div>

                            <div>

                                <strong>
                                    Multi Modul
                                </strong>

                                <br>

                                <small class="text-muted">
                                    Absensi • CBT • Perpustakaan
                                </small>

                            </div>

                        </div>

                    </div>


                    {{-- Floating Card Bawah --}}

                    <div class="floating-card bottom-card">

                        <div class="d-flex align-items-center">

                            <div
                                class="floating-icon bg-success-subtle text-success me-3">

                                <i class="bi bi-check-circle-fill"></i>

                            </div>

                            <div>

                                <strong>
                                    Sistem Aktif
                                </strong>

                                <br>

                                <small class="text-muted">
                                    Siap digunakan
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- ==========================================================
| STATISTIK
========================================================== --}}

<section
    id="statistik"
    class="bg-white">

    <div class="container">

        <div class="text-center mb-5 reveal">

            <span class="section-badge">

                <i class="bi bi-bar-chart-fill"></i>

                Dashboard SSIS

            </span>


            <h2 class="section-title">

                Statistik Sistem

            </h2>


            <p class="section-subtitle">

                Data berikut ditampilkan secara otomatis
                berdasarkan informasi yang tersimpan
                pada database SSIS.

            </p>

        </div>


        <div class="row g-4">


            {{-- UJIAN --}}

            <div class="col-md-4">

                <div class="card stat-card shadow-sm h-100 reveal delay-1">

                    <div class="card-body text-center py-5">

                        <div
                            class="stat-icon bg-primary-subtle text-primary">

                            <i class="bi bi-laptop"></i>

                        </div>


                        <h2
                            class="stat-number counter"
                            data-target="{{ $ujianAktif }}">

                            0

                        </h2>


                        <p class="text-muted mb-0">

                            Ujian Sedang Berjalan

                        </p>

                    </div>

                </div>

            </div>


            {{-- BUKU --}}

            <div class="col-md-4">

                <div class="card stat-card shadow-sm h-100 reveal delay-2">

                    <div class="card-body text-center py-5">

                        <div
                            class="stat-icon"
                            style="
                                background:#EAF8EE;
                                color:#2FB344;
                            ">

                            <i class="bi bi-book"></i>

                        </div>


                        <h2
                            class="stat-number counter"
                            data-target="{{ $stokBuku }}">

                            0

                        </h2>


                        <p class="text-muted mb-0">

                            Total Stok Buku

                        </p>

                    </div>

                </div>

            </div>


            {{-- ABSENSI --}}

            <div class="col-md-4">

                <div class="card stat-card shadow-sm h-100 reveal delay-3">

                    <div class="card-body text-center py-5">

                        <div
                            class="stat-icon"
                            style="
                                background:#FFF4DE;
                                color:#F59F00;
                            ">

                            <i class="bi bi-calendar-check"></i>

                        </div>


                        <h2
                            class="stat-number counter"
                            data-target="{{ $absensiAktif }}">

                            0

                        </h2>


                        <p class="text-muted mb-0">

                            Absensi Sedang Berjalan

                        </p>

                    </div>

                </div>

            </div>


        </div>

    </div>

</section>



{{-- ==========================================================
| TENTANG
========================================================== --}}

<section id="tentang">

    <div class="container">

        <div class="row align-items-center g-5">


            <div class="col-lg-6">

                <div class="about-image-wrapper reveal">

                    <img
                        src="{{ asset('images/sekolah.jpeg') }}"
                        class="img-fluid shadow-lg"
                        alt="Tentang SSIS"
                        loading="lazy">


                    <div class="about-badge">

                        <div class="d-flex align-items-center">

                            <div
                                class="floating-icon bg-primary-subtle text-primary me-3">

                                <i class="bi bi-mortarboard-fill"></i>

                            </div>

                            <div>

                                <strong>
                                    SMA Negeri 6
                                </strong>

                                <br>

                                <small class="text-muted">
                                    Malinau
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-lg-6">

                <div class="reveal">

                    <span class="section-badge">

                        <i class="bi bi-info-circle-fill"></i>

                        Tentang SSIS

                    </span>


                    <h2 class="section-title">

                        Smart School
                        <span class="text-primary">
                            Information System
                        </span>

                    </h2>


                    <p
                        class="text-muted"
                        style="line-height:1.9;">

                        SSIS merupakan sistem informasi sekolah
                        yang dikembangkan untuk mendukung
                        digitalisasi administrasi dan proses
                        pembelajaran di
                        <strong>SMA Negeri 6 Malinau</strong>.

                        Seluruh layanan sekolah diintegrasikan
                        ke dalam satu aplikasi sehingga lebih
                        mudah, cepat, aman, dan efisien digunakan
                        oleh guru, siswa, maupun petugas sekolah.

                    </p>


                    <div class="row mt-4 g-2">


                        <div class="col-sm-6">

                            <div class="about-item">

                                <div class="d-flex">

                                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>

                                    <div>

                                        <strong>
                                            Mudah Digunakan
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            Antarmuka sederhana.
                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="col-sm-6">

                            <div class="about-item">

                                <div class="d-flex">

                                    <i class="bi bi-shield-lock-fill text-primary fs-4 me-3"></i>

                                    <div>

                                        <strong>
                                            Aman
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            Hak akses pengguna.
                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="col-sm-6">

                            <div class="about-item">

                                <div class="d-flex">

                                    <i class="bi bi-lightning-charge-fill text-warning fs-4 me-3"></i>

                                    <div>

                                        <strong>
                                            Cepat
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            Proses data efisien.
                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="col-sm-6">

                            <div class="about-item">

                                <div class="d-flex">

                                    <i class="bi bi-cloud-check-fill text-info fs-4 me-3"></i>

                                    <div>

                                        <strong>
                                            Terintegrasi
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            Semua modul satu sistem.
                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- ==========================================================
| CTA
========================================================== --}}

<section class="py-5">

    <div class="container">

        <div class="cta-box text-center text-white reveal">

            <div class="cta-content">

                <span
                    class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3">

                    <i class="bi bi-stars me-1"></i>

                    SSIS SMA Negeri 6 Malinau

                </span>


                <h2 class="fw-bold mb-3">

                    Siap Menggunakan SSIS?

                </h2>


                <p class="mb-4 opacity-75">

                    Masuk ke sistem dan mulai menggunakan
                    seluruh layanan Smart School Information System.

                </p>


                <a
                    href="{{ route('login') }}"
                    class="btn btn-light btn-lg px-5 rounded-3 fw-semibold">

                    <i class="bi bi-box-arrow-in-right me-2"></i>

                    Login Sekarang

                </a>

            </div>

        </div>

    </div>

</section>



{{-- ==========================================================
| FOOTER
========================================================== --}}

<footer class="py-4 border-top">

    <div class="container">

        <div class="row align-items-center gy-3">

            <div class="col-md-6">

                <div class="fw-bold">

                    <i class="bi bi-mortarboard-fill text-primary me-1"></i>

                    SSIS

                </div>

                <small class="text-muted">

                    Smart School Information System

                    <br>

                    SMA Negeri 6 Malinau

                </small>

            </div>


            <div class="col-md-6 text-md-end">

                <small class="text-muted">

                    © {{ date('Y') }}

                    SMA Negeri 6 Malinau

                    <br>

                    All Rights Reserved.

                </small>

            </div>

        </div>

    </div>

</footer>



{{-- ==========================================================
| MODAL PANDUAN
========================================================== --}}

<div
    class="modal fade"
    id="panduanModal"
    tabindex="-1"
    aria-labelledby="panduanModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="panduanModalLabel">

                    <i class="bi bi-book text-primary me-2"></i>

                    Buku Panduan SSIS

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup">
                </button>

            </div>


            <div class="modal-body p-0">

                <iframe
                    src="{{ asset('panduan/buku-panduan-v2.pdf') }}"
                    class="pdf-frame"
                    title="Buku Panduan SSIS">
                </iframe>

            </div>


            <div class="modal-footer">

                <a
                    href="{{ asset('panduan/buku-panduan-v2.pdf') }}"
                    target="_blank"
                    class="btn btn-outline-primary">

                    <i class="bi bi-box-arrow-up-right me-2"></i>

                    Buka PDF

                </a>


                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>



{{-- ==========================================================
| BOOTSTRAP JS
========================================================== --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /* ==========================================================
       NAVBAR SCROLL
    ========================================================== */

    const navbar = document.querySelector('.navbar');

    function updateNavbar() {

        if (window.scrollY > 40) {

            navbar.classList.add('scrolled');

        } else {

            navbar.classList.remove('scrolled');

        }

    }

    updateNavbar();

    window.addEventListener(
        'scroll',
        updateNavbar,
        { passive: true }
    );


    /* ==========================================================
       SMOOTH NAVIGATION
    ========================================================== */

    document
        .querySelectorAll('.nav-link[href^="#"]')
        .forEach(function (link) {

            link.addEventListener('click', function (e) {

                const target =
                    document.querySelector(
                        this.getAttribute('href')
                    );

                if (!target) {
                    return;
                }

                e.preventDefault();

                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });


                const menu =
                    document.querySelector('#navbarMenu');

                const bsCollapse =
                    bootstrap.Collapse.getInstance(menu);

                if (
                    bsCollapse &&
                    window.innerWidth < 992
                ) {

                    bsCollapse.hide();

                }

            });

        });


    /* ==========================================================
       REVEAL ANIMATION
    ========================================================== */

    const revealElements =
        document.querySelectorAll('.reveal');


    const revealObserver =
        new IntersectionObserver(
            function (entries, observer) {

                entries.forEach(function (entry) {

                    if (entry.isIntersecting) {

                        entry.target.classList.add('show');

                        observer.unobserve(
                            entry.target
                        );

                    }

                });

            },
            {
                threshold: 0.12
            }
        );


    revealElements.forEach(function (element) {

        revealObserver.observe(element);

    });


    /* ==========================================================
       COUNTER STATISTIK
    ========================================================== */

    const counters =
        document.querySelectorAll('.counter');


    let counterStarted = false;


    function startCounters() {

        if (counterStarted) {
            return;
        }

        counterStarted = true;


        counters.forEach(function (counter) {

            const target =
                Number(counter.dataset.target) || 0;

            const duration = 1200;

            const startTime =
                performance.now();


            function updateCounter(currentTime) {

                const progress =
                    Math.min(
                        (currentTime - startTime) / duration,
                        1
                    );


                const eased =
                    1 - Math.pow(
                        1 - progress,
                        3
                    );


                const current =
                    Math.floor(
                        eased * target
                    );


                counter.textContent =
                    current.toLocaleString('id-ID');


                if (progress < 1) {

                    requestAnimationFrame(
                        updateCounter
                    );

                } else {

                    counter.textContent =
                        target.toLocaleString('id-ID');

                }

            }


            requestAnimationFrame(
                updateCounter
            );

        });

    }


    const statistik =
        document.querySelector('#statistik');


    if (statistik) {

        const statisticObserver =
            new IntersectionObserver(
                function (entries, observer) {

                    if (
                        entries[0].isIntersecting
                    ) {

                        startCounters();

                        observer.disconnect();

                    }

                },
                {
                    threshold: .25
                }
            );


        statisticObserver.observe(statistik);

    }


    /* ==========================================================
       CLOSE MOBILE NAVBAR AFTER MODAL
    ========================================================== */

    const modal =
        document.querySelector('#panduanModal');


    if (modal) {

        modal.addEventListener(
            'shown.bs.modal',
            function () {

                document.body.classList.add(
                    'modal-open'
                );

            }
        );

    }


});

</script>


</body>

</html>