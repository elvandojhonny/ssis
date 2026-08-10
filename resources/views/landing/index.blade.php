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

    {{-- Favicon Sekolah --}}
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

        :root{

            --primary:#206BC4;
            --primary-dark:#174A87;
            --success:#2FB344;
            --warning:#F59F00;
            --light:#F4F6FB;
            --dark:#1F2937;
            --muted:#6B7280;
            --radius:22px;

        }

        *{

            margin:0;
            padding:0;
            box-sizing:border-box;

        }

        html{

            scroll-behavior:smooth;

        }

        body{

            font-family:'Poppins',sans-serif;
            background:var(--light);
            color:var(--dark);
            overflow-x:hidden;

        }

        a{

            text-decoration:none;

        }

        section{

            padding:90px 0;

        }

        .section-badge{

            display:inline-block;
            background:#E7F1FF;
            color:var(--primary);
            padding:8px 18px;
            border-radius:50px;
            font-size:.9rem;
            font-weight:600;
            margin-bottom:18px;

        }

        .section-title{

            font-size:2.5rem;
            font-weight:700;
            margin-bottom:20px;

        }

        .section-subtitle{

            color:var(--muted);
            max-width:700px;
            margin:auto;
            line-height:1.9;

        }

        /* ===========================
            NAVBAR
        ============================ */

        .navbar{

            background:rgba(255,255,255,.92);
            backdrop-filter:blur(12px);
            transition:.35s;
            padding:16px 0;
            z-index:999;

        }

        .navbar.scrolled{

            box-shadow:0 12px 30px rgba(0,0,0,.08);

        }

        .navbar-brand{

            display:flex;
            align-items:center;
            gap:12px;
            font-weight:700;
            color:var(--dark);

        }

        .navbar-brand img{

            width:52px;

        }

        .navbar-brand small{

            display:block;
            color:var(--muted);
            font-size:.78rem;

        }

        .nav-link{

            color:var(--dark);
            font-weight:500;
            margin-left:15px;
            transition:.3s;

        }

        .nav-link:hover{

            color:var(--primary);

        }

        .btn-login{

            padding:11px 24px;
            border-radius:12px;
            font-weight:600;

        }

        .btn-guide{

            border-radius:12px;
            font-weight:600;
            padding:11px 22px;

        }

        /* ===========================
            HERO
        ============================ */

        .hero{
    min-height:100vh;
    display:flex;
    align-items:center;
    padding-top:calc(80px + 2rem);

    background:
        radial-gradient(circle at top right,#DCEBFF 0%,transparent 35%),
        radial-gradient(circle at bottom left,#EDF5FF 0%,transparent 30%),
        #F8FAFD;
}

        .hero h1{

            font-size:3.4rem;
            font-weight:800;
            line-height:1.2;

        }

        .hero h1 span{

            color:var(--primary);

        }

        .hero p{

            color:var(--muted);
            margin-top:22px;
            line-height:2;
            font-size:1.08rem;

        }

        .hero-buttons{

            margin-top:40px;
            display:flex;
            gap:15px;
            flex-wrap:wrap;

        }

        .btn-primary-custom{

            background:var(--primary);
            color:#fff;
            border:none;
            padding:15px 30px;
            border-radius:15px;
            transition:.3s;
            font-weight:600;

        }

        .btn-primary-custom:hover{

            background:var(--primary-dark);
            color:#fff;
            transform:translateY(-3px);

        }

        .btn-outline-custom{

            background:#fff;
            color:var(--primary);
            border:2px solid var(--primary);
            padding:15px 30px;
            border-radius:15px;
            transition:.3s;
            font-weight:600;

        }

        .btn-outline-custom:hover{

            background:var(--primary);
            color:#fff;

        }

        @media(max-width:992px){

            .hero{

                text-align:center;
                padding-top:130px;

            }

            .hero h1{

                font-size:2.3rem;

            }

            .hero-buttons{

                justify-content:center;

            }

        }

    </style>

</head>

<body>

<nav class="navbar navbar-expand-lg fixed-top">

    <div class="container">

        <a
            href="#"
            class="navbar-brand">

            <img
                src="{{ asset('images/logo SMAN 6.png') }}"
                alt="Logo SSIS">

            <div>

                SSIS

                <small>

                    SMA Negeri 6 Malinau

                </small>

            </div>

        </a>

        <button
            class="navbar-toggler"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu">

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

                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">

                    <button
                        class="btn btn-outline-primary btn-guide"
                        data-bs-toggle="modal"
                        data-bs-target="#panduanModal">

                        <i class="bi bi-book me-2"></i>

                        Panduan

                    </button>

                </li>

                <li class="nav-item ms-lg-2 mt-3 mt-lg-0">

                    <a
                        href="{{ route('login') }}"
                        class="btn btn-primary btn-login">

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

                <span class="section-badge">

                    <i class="bi bi-mortarboard-fill me-2"></i>

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
                    dalam satu platform modern untuk mendukung
                    proses belajar mengajar yang lebih efektif,
                    efisien, dan transparan.

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

                <div class="row mt-5 g-3">

                    <div class="col-6">

                        <div class="card border-0 shadow-sm h-100">

                            <div class="card-body">

                                <i class="bi bi-qr-code-scan fs-2 text-primary"></i>

                                <h6 class="fw-bold mt-3 mb-1">

                                    QR Attendance

                                </h6>

                                <small class="text-muted">

                                    Presensi cepat dan realtime.

                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="col-6">

                        <div class="card border-0 shadow-sm h-100">

                            <div class="card-body">

                                <i class="bi bi-laptop fs-2 text-success"></i>

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

                        <div class="card border-0 shadow-sm h-100">

                            <div class="card-body">

                                <i class="bi bi-book-half fs-2 text-warning"></i>

                                <h6 class="fw-bold mt-3 mb-1">

                                    Digital Library

                                </h6>

                                <small class="text-muted">

                                    Kelola koleksi buku sekolah.

                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="col-6">

                        <div class="card border-0 shadow-sm h-100">

                            <div class="card-body">

                                <i class="bi bi-shield-check fs-2 text-info"></i>

                                <h6 class="fw-bold mt-3 mb-1">

                                    Aman

                                </h6>

                                <small class="text-muted">

                                    Data tersimpan dengan aman.

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="position-relative">

                    <img
                        src="{{ asset('images/sekolahsma.jpeg') }}"
                        class="img-fluid rounded-4 shadow-lg"
                        alt="SMA Negeri 6 Malinau">

                    <div
                        class="position-absolute bg-white shadow rounded-4 p-3"
                        style="top:20px;left:-20px;">

                        <div class="d-flex align-items-center">

                            <i class="bi bi-people-fill fs-2 text-primary me-3"></i>

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

                    <div
                        class="position-absolute bg-white shadow rounded-4 p-3"
                        style="bottom:20px;right:-20px;">

                        <div class="d-flex align-items-center">

                            <i class="bi bi-check-circle-fill fs-2 text-success me-3"></i>

                            <div>

                                <strong>

                                    Sistem Aktif

                                </strong>

                                <br>

                                <small class="text-muted">

                                    Siap digunakan siswa sekolah

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

        <div class="text-center mb-5">

            <span class="section-badge">

                Dashboard SSIS

            </span>

            <h2 class="section-title">

                Statistik Sistem

            </h2>

            <p class="section-subtitle">

                Informasi berikut ditampilkan secara otomatis
                berdasarkan data yang tersimpan pada database SSIS.

            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-4">

                <div class="card border-0 shadow-sm text-center h-100">

                    <div class="card-body py-5">

                        <i class="bi bi-laptop display-4 text-primary"></i>

                        <h2 class="fw-bold mt-3">

                            {{ number_format($ujianAktif) }}

                        </h2>

                        <p class="text-muted mb-0">

                            Ujian Sedang Berjalan

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card border-0 shadow-sm text-center h-100">

                    <div class="card-body py-5">

                        <i class="bi bi-book display-4 text-success"></i>

                        <h2 class="fw-bold mt-3">

                            {{ number_format($stokBuku) }}

                        </h2>

                        <p class="text-muted mb-0">

                            Total Stok Buku

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card border-0 shadow-sm text-center h-100">

                    <div class="card-body py-5">

                        <i class="bi bi-calendar-check display-4 text-warning"></i>

                        <h2 class="fw-bold mt-3">

                            {{ number_format($absensiAktif) }}

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
| TENTANG SSIS
========================================================== --}}

<section
    id="tentang"
    class="py-5">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <img
                    src="{{ asset('images/sekolah.jpeg') }}"
                    class="img-fluid rounded-4 shadow-lg"
                    alt="Tentang SSIS">

            </div>

            <div class="col-lg-6">

                <span class="section-badge">

                    Tentang SSIS

                </span>

                <h2 class="section-title">

                    Smart School Information System

                </h2>

                <p class="section-subtitle text-start m-0">

                    SSIS merupakan sistem informasi sekolah yang
                    dikembangkan untuk mendukung digitalisasi
                    administrasi dan proses pembelajaran di
                    <strong>SMA Negeri 6 Malinau</strong>.

                    Seluruh layanan sekolah diintegrasikan ke
                    dalam satu aplikasi sehingga lebih mudah,
                    cepat, aman, dan efisien digunakan oleh
                    guru, siswa, maupun petugas sekolah.

                </p>

                <div class="row mt-4">

                    <div class="col-6 mb-3">

                        <div class="d-flex">

                            <i class="bi bi-check-circle-fill text-success fs-4 me-2"></i>

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

                    <div class="col-6 mb-3">

                        <div class="d-flex">

                            <i class="bi bi-shield-lock-fill text-primary fs-4 me-2"></i>

                            <div>

                                <strong>

                                    Aman

                                </strong>

                                <br>

                                <small class="text-muted">

                                    Hak akses sesuai pengguna.

                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="col-6">

                        <div class="d-flex">

                            <i class="bi bi-lightning-charge-fill text-warning fs-4 me-2"></i>

                            <div>

                                <strong>

                                    Cepat

                                </strong>

                                <br>

                                <small class="text-muted">

                                    Data diproses realtime.

                                </small>

                            </div>

                        </div>

                    </div>

                    <div class="col-6">

                        <div class="d-flex">

                            <i class="bi bi-cloud-check-fill text-info fs-4 me-2"></i>

                            <div>

                                <strong>

                                    Terintegrasi

                                </strong>

                                <br>

                                <small class="text-muted">

                                    Semua modul dalam satu sistem.

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
| CALL TO ACTION
========================================================== --}}

<section class="py-5">

    <div class="container">

        <div
            class="rounded-4 p-5 text-center text-white"
            style="
                background:
                linear-gradient(
                    135deg,
                    #206BC4,
                    #4F8FEF
                );
            ">

            <h2 class="fw-bold mb-3">

                Siap Menggunakan SSIS?

            </h2>

            <p class="mb-4 opacity-75">

                Masuk ke sistem dan mulai
                menggunakan seluruh layanan
                Smart School Information System.

            </p>

            <a
                href="{{ route('login') }}"
                class="btn btn-light btn-lg px-5">

                <i class="bi bi-box-arrow-in-right me-2"></i>

                Login Sekarang

            </a>

        </div>

    </div>

</section>

{{-- ==========================================================
| FOOTER
========================================================== --}}

<footer
    class="py-4 border-top bg-white">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-md-6">

                <div class="fw-bold">

                    SSIS

                </div>

                <small class="text-muted">

                    Smart School Information System

                    <br>

                    SMA Negeri 6 Malinau

                </small>

            </div>

            <div class="col-md-6 text-md-end mt-3 mt-md-0">

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
    tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-book me-2"></i>

                    Buku Panduan SSIS

                </h5>

                <button
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body p-0">

                <iframe
                    src="{{ asset('panduan/Buku Panduan SSIS Siswa V2.pdf') }}"
                    width="100%"
                    height="650"
                    style="border:none">
                </iframe>

            </div>

            <div class="modal-footer">

                <a
                    href="{{ asset('panduan/Buku Panduan SSIS Siswa V2.pdf') }}"
                    target="_blank"
                    class="btn btn-outline-primary">

                    <i class="bi bi-box-arrow-up-right me-2"></i>

                    Buka PDF

                </a>

                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>

{{-- Bootstrap JS --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

    const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', function () {
    if (window.scrollY > 40) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

document.querySelectorAll('.nav-link[href^="#"]').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();

        const target = document.querySelector(this.getAttribute('href'));

        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        const menu = document.querySelector('#navbarMenu');
        const bsCollapse = bootstrap.Collapse.getInstance(menu);

        if (bsCollapse) {
            bsCollapse.hide();
        }
    });
});

</script>

</body>

</html>
