<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>SMA 6 Malinau | SMA Negeri 6 Malinau - SSIS</title>

<meta
    name="description"
    content="SMA Negeri 6 Malinau (SMA 6 Malinau) di Sungai Boh. Website dan Sistem Informasi Sekolah (SSIS) untuk layanan akademik, absensi, CBT, perpustakaan, dan informasi sekolah."
>

<meta
    name="author"
    content="SMA Negeri 6 Malinau"
>

<meta
    name="keywords"
    content="SMA 6, SMA 6 Malinau, SMA Negeri 6 Malinau, SMA Sungai Boh, SMA 6 Sungai Boh, sekolah Sungai Boh, sekolah Malinau, SMA Malinau, SSIS SMA 6 Malinau, Sistem Informasi SMA Negeri 6 Malinau"
>

<meta
    name="robots"
    content="index, follow, max-image-preview:large"
>

<link
    rel="canonical"
    href="https://ssis-sma6.up.railway.app/"
>

<meta
    property="og:type"
    content="website"
>

<meta
    property="og:title"
    content="SMA 6 Malinau | SMA Negeri 6 Malinau - SSIS"
>

<meta
    property="og:description"
    content="SMA Negeri 6 Malinau di Sungai Boh. Informasi sekolah dan Sistem Informasi Sekolah untuk akademik, absensi, CBT, perpustakaan, dan layanan digital."
>

<meta
    property="og:url"
    content="https://ssis-sma6.up.railway.app/"
>

<meta
    property="og:site_name"
    content="SMA Negeri 6 Malinau"
>

<meta
    property="og:image"
    content="{{ asset('images/logo SMAN 6.png') }}"
>

<meta
    property="og:image:alt"
    content="Logo SMA Negeri 6 Malinau"
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
    "@@graph": [

        {
            "@@type": "WebSite",
            "@@id": "https://ssis-sma6.up.railway.app/#website",
            "name": "SMA Negeri 6 Malinau",
            "alternateName": [
                "SMA 6 Malinau",
                "SMA 6",
                "SMA Sungai Boh",
                "SSIS SMA Negeri 6 Malinau"
            ],
            "url": "https://ssis-sma6.up.railway.app/"
        },

        {
            "@@type": "School",
            "@@id": "https://ssis-sma6.up.railway.app/#school",
            "name": "SMA Negeri 6 Malinau",
            "alternateName": [
                "SMA 6 Malinau",
                "SMA 6",
                "SMA Sungai Boh"
            ],
            "url": "https://ssis-sma6.up.railway.app/",
            "logo": "{{ asset('images/logo SMAN 6.png') }}",
            "image": "{{ asset('images/Sma62.png') }}",
            "description": "SMA Negeri 6 Malinau di Sungai Boh dengan Sistem Informasi Sekolah (SSIS) untuk mendukung layanan akademik dan administrasi sekolah."
        }

    ]
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

    animation:
        fadeUp
        1s
        cubic-bezier(.22,1,.36,1)
        .15s
        both;

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

    animation:
        fadeRight
        1.1s
        cubic-bezier(.22,1,.36,1)
        .3s
        both;

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

    /* ==========================================================
   CINEMATIC OPENING FINAL
========================================================== */

.cinematic-opening {

    position: fixed;

    inset: 0;

    z-index: 999999;

    display: flex;

    align-items: center;

    justify-content: center;

    overflow: hidden;

    background: #f7faff;

    opacity: 1;

    visibility: visible;

    transition:
        opacity 1s cubic-bezier(.22,1,.36,1),
        visibility 1s ease;

}


.cinematic-opening.hide {

    opacity: 0;

    visibility: hidden;

    pointer-events: none;

}


/* ==========================================================
   BACKDROP
========================================================== */

.opening-backdrop {

    position: absolute;

    inset: 0;

    background:

        radial-gradient(
            circle at 50% 45%,
            rgba(255,255,255,.95),
            rgba(238,246,255,.92) 36%,
            rgba(224,237,255,.96) 100%
        );

}


.opening-backdrop::before {

    content: "";

    position: absolute;

    inset: -30%;

    background:
        conic-gradient(
            from 0deg,
            transparent,
            rgba(32,107,196,.045),
            transparent,
            rgba(14,165,233,.04),
            transparent
        );

    animation:
        backdropRotate
        12s
        linear
        infinite;

}


/* ==========================================================
   AURA
========================================================== */

.opening-aura {

    position: absolute;

    width: 420px;

    height: 420px;

    border-radius: 50%;

    filter: blur(80px);

    opacity: .35;

    pointer-events: none;

}


.aura-one {

    background:
        rgba(32,107,196,.18);

    animation:
        auraOne
        5s
        ease-in-out
        infinite;

}


.aura-two {

    width: 320px;

    height: 320px;

    background:
        rgba(14,165,233,.12);

    animation:
        auraTwo
        6s
        ease-in-out
        infinite;

}


/* ==========================================================
   CENTER
========================================================== */

.opening-center {

    position: relative;

    z-index: 10;

    display: flex;

    align-items: center;

    flex-direction: column;

    text-align: center;

}


/* ==========================================================
   LOGO STAGE
========================================================== */

.opening-logo-stage {

    position: relative;

    width: 220px;

    height: 220px;

    display: flex;

    align-items: center;

    justify-content: center;

}


/* ==========================================================
   LOGO FINAL
========================================================== */

.opening-logo-final {

    position: relative;

    z-index: 5;

    width: 150px;

    height: 150px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background:
        rgba(255,255,255,.92);

    box-shadow:
        0 25px 70px rgba(32,107,196,.18),
        0 0 0 1px rgba(32,107,196,.07);

    opacity: 0;

    transform:
        scale(.45);

    animation:
        logoReveal
        1.1s
        cubic-bezier(.16,1,.3,1)
        1.15s
        forwards;

}


.opening-logo-final img {

    width: 118px;

    height: 118px;

    object-fit: contain;

    filter:
        drop-shadow(
            0 10px 20px
            rgba(32,107,196,.16)
        );

}


/* ==========================================================
   SHINE
========================================================== */

.logo-shine {

    position: absolute;

    top: -20%;

    left: -70%;

    width: 35%;

    height: 140%;

    background:
        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.95),
            transparent
        );

    transform:
        rotate(20deg)
        skewX(-12deg);

    opacity: 0;

    animation:
        logoShine
        .9s
        ease-out
        2.05s
        forwards;

}


/* ==========================================================
   SPARKS
========================================================== */

.logo-spark {

    position: absolute;

    z-index: 3;

    width: 8px;

    height: 8px;

    border-radius: 50%;

    background:
        var(--primary);

    box-shadow:
        0 0 15px rgba(32,107,196,.45);

    opacity: 0;

}


/* kiri atas */

.spark-1 {

    left: 5%;

    top: 18%;

    animation:
        sparkOne
        1.6s
        cubic-bezier(.16,1,.3,1)
        .05s
        forwards;

}


/* kanan atas */

.spark-2 {

    right: 5%;

    top: 15%;

    animation:
        sparkTwo
        1.55s
        cubic-bezier(.16,1,.3,1)
        .16s
        forwards;

}


/* kiri tengah */

.spark-3 {

    left: 0;

    top: 48%;

    animation:
        sparkThree
        1.65s
        cubic-bezier(.16,1,.3,1)
        .24s
        forwards;

}


/* kanan tengah */

.spark-4 {

    right: 0;

    top: 52%;

    animation:
        sparkFour
        1.65s
        cubic-bezier(.16,1,.3,1)
        .32s
        forwards;

}


/* kiri bawah */

.spark-5 {

    left: 15%;

    bottom: 5%;

    animation:
        sparkFive
        1.6s
        cubic-bezier(.16,1,.3,1)
        .40s
        forwards;

}


/* kanan bawah */

.spark-6 {

    right: 17%;

    bottom: 4%;

    animation:
        sparkSix
        1.6s
        cubic-bezier(.16,1,.3,1)
        .46s
        forwards;

}


/* atas */

.spark-7 {

    left: 48%;

    top: -2%;

    animation:
        sparkSeven
        1.55s
        cubic-bezier(.16,1,.3,1)
        .12s
        forwards;

}


/* bawah */

.spark-8 {

    left: 52%;

    bottom: -2%;

    animation:
        sparkEight
        1.6s
        cubic-bezier(.16,1,.3,1)
        .52s
        forwards;

}


/* ==========================================================
   BRAND
========================================================== */

.opening-brand {

    margin-top: 2px;

    opacity: 0;

    transform:
        translateY(25px);

    animation:
        brandReveal
        .9s
        cubic-bezier(.16,1,.3,1)
        1.55s
        forwards;

}


.opening-ssis {

    font-size: 3rem;

    font-weight: 800;

    line-height: 1;

    letter-spacing: 2px;

    color: var(--dark);

}


.opening-name {

    margin-top: 8px;

    font-size: .86rem;

    font-weight: 500;

    letter-spacing: .25px;

    color: var(--primary);

}


.opening-divider {

    width: 120px;

    height: 2px;

    margin:
        14px
        auto
        10px;

    border-radius: 50px;

    overflow: hidden;

    background:
        rgba(32,107,196,.08);

}


.opening-divider span {

    display: block;

    width: 40%;

    height: 100%;

    background:
        linear-gradient(
            90deg,
            transparent,
            var(--primary),
            transparent
        );

    animation:
        dividerSweep
        1.2s
        ease-in-out
        infinite;

}


.opening-school {

    font-size: .75rem;

    color: var(--muted);

}


/* ==========================================================
   LOADING
========================================================== */

.opening-loading {

    display: flex;

    gap: 6px;

    margin-top: 21px;

    opacity: 0;

    animation:
        loadingReveal
        .5s
        ease
        1.75s
        forwards;

}


.opening-loading span {

    width: 5px;

    height: 5px;

    border-radius: 50%;

    background:
        var(--primary);

    animation:
        loadingDot
        1s
        ease-in-out
        infinite;

}


.opening-loading span:nth-child(2) {

    animation-delay: .14s;

}


.opening-loading span:nth-child(3) {

    animation-delay: .28s;

}


/* ==========================================================
   ORBIT
========================================================== */

.opening-orbit {

    position: absolute;

    width: 420px;

    height: 420px;

    border:
        1px solid
        rgba(32,107,196,.055);

    border-radius: 50%;

    opacity: 0;

    animation:
        orbitReveal
        1s
        ease
        .7s
        forwards;

}


.orbit-one {

    transform:
        rotate(25deg)
        scale(.7);

}


.orbit-two {

    width: 320px;

    height: 320px;

    transform:
        rotate(-30deg)
        scale(.7);

    animation-delay:
        .95s;

}


/* ==========================================================
   PARTICLES
========================================================== */

.opening-particles {

    position: absolute;

    inset: 0;

}


.opening-particles i {

    position: absolute;

    width: 3px;

    height: 3px;

    border-radius: 50%;

    background:
        rgba(32,107,196,.55);

    opacity: 0;

    animation:
        particleFloat
        3s
        ease-out
        infinite;

}


.opening-particles i:nth-child(1) {
    left: 8%;
    top: 30%;
    animation-delay: .15s;
}

.opening-particles i:nth-child(2) {
    left: 14%;
    top: 65%;
    animation-delay: .7s;
}

.opening-particles i:nth-child(3) {
    left: 23%;
    top: 18%;
    animation-delay: 1.2s;
}

.opening-particles i:nth-child(4) {
    left: 31%;
    top: 77%;
    animation-delay: .4s;
}

.opening-particles i:nth-child(5) {
    left: 39%;
    top: 10%;
    animation-delay: 1.4s;
}

.opening-particles i:nth-child(6) {
    left: 46%;
    top: 86%;
    animation-delay: .8s;
}

.opening-particles i:nth-child(7) {
    left: 55%;
    top: 8%;
    animation-delay: .35s;
}

.opening-particles i:nth-child(8) {
    left: 63%;
    top: 90%;
    animation-delay: 1.1s;
}

.opening-particles i:nth-child(9) {
    left: 70%;
    top: 17%;
    animation-delay: .6s;
}

.opening-particles i:nth-child(10) {
    left: 78%;
    top: 72%;
    animation-delay: 1.5s;
}

.opening-particles i:nth-child(11) {
    left: 88%;
    top: 27%;
    animation-delay: .2s;
}

.opening-particles i:nth-child(12) {
    left: 93%;
    top: 58%;
    animation-delay: .9s;
}

.opening-particles i:nth-child(13) {
    left: 5%;
    top: 48%;
    animation-delay: 1.3s;
}

.opening-particles i:nth-child(14) {
    left: 19%;
    top: 44%;
    animation-delay: .5s;
}

.opening-particles i:nth-child(15) {
    left: 84%;
    top: 46%;
    animation-delay: 1.6s;
}

.opening-particles i:nth-child(16) {
    left: 73%;
    top: 84%;
    animation-delay: .75s;
}

.opening-particles i:nth-child(17) {
    left: 28%;
    top: 90%;
    animation-delay: 1.7s;
}

.opening-particles i:nth-child(18) {
    left: 58%;
    top: 25%;
    animation-delay: .25s;
}

.opening-particles i:nth-child(19) {
    left: 35%;
    top: 35%;
    animation-delay: 1.25s;
}

.opening-particles i:nth-child(20) {
    left: 66%;
    top: 40%;
    animation-delay: .55s;
}

.opening-particles i:nth-child(21) {
    left: 11%;
    top: 82%;
    animation-delay: 1.45s;
}

.opening-particles i:nth-child(22) {
    left: 90%;
    top: 82%;
    animation-delay: .85s;
}

.opening-particles i:nth-child(23) {
    left: 49%;
    top: 4%;
    animation-delay: 1.05s;
}

.opening-particles i:nth-child(24) {
    left: 51%;
    top: 95%;
    animation-delay: .45s;
}


/* ==========================================================
   KEYFRAMES
========================================================== */

@keyframes backdropRotate {

    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }

}


@keyframes auraOne {

    0%,
    100% {
        transform:
            translate(-90px,-20px)
            scale(.85);
    }

    50% {
        transform:
            translate(70px,45px)
            scale(1.15);
    }

}


@keyframes auraTwo {

    0%,
    100% {
        transform:
            translate(70px,45px)
            scale(1);
    }

    50% {
        transform:
            translate(-55px,-35px)
            scale(.82);
    }

}


@keyframes logoReveal {

    0% {

        opacity: 0;

        transform:
            scale(.42)
            rotate(-8deg);

        filter:
            blur(10px);

    }

    60% {

        opacity: 1;

        transform:
            scale(1.08)
            rotate(1deg);

        filter:
            blur(0);

    }

    100% {

        opacity: 1;

        transform:
            scale(1)
            rotate(0);

        filter:
            blur(0);

    }

}


@keyframes logoShine {

    0% {

        left: -70%;

        opacity: 0;

    }

    15% {

        opacity: .9;

    }

    100% {

        left: 145%;

        opacity: 0;

    }

}


@keyframes brandReveal {

    from {

        opacity: 0;

        transform:
            translateY(25px)
            scale(.96);

    }

    to {

        opacity: 1;

        transform:
            translateY(0)
            scale(1);

    }

}


@keyframes dividerSweep {

    0% {
        transform: translateX(-170%);
    }

    100% {
        transform: translateX(380%);
    }

}


@keyframes loadingReveal {

    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }

}


@keyframes loadingDot {

    0%,
    100% {

        opacity: .2;

        transform:
            translateY(0)
            scale(.8);

    }

    50% {

        opacity: 1;

        transform:
            translateY(-4px)
            scale(1);

    }

}


@keyframes orbitReveal {

    from {

        opacity: 0;

    }

    to {

        opacity: 1;

    }

}


@keyframes particleFloat {

    0% {

        opacity: 0;

        transform:
            translateY(18px)
            scale(.4);

    }

    20% {

        opacity: .8;

    }

    70% {

        opacity: .45;

    }

    100% {

        opacity: 0;

        transform:
            translateY(-45px)
            scale(1.15);

    }

}


/* ==========================================================
   SPARK KEYFRAMES
========================================================== */

@keyframes sparkOne {

    from {
        opacity: 0;
        transform:
            translate(-95px,-85px)
            scale(.2);
    }

    55% {
        opacity: 1;
    }

    to {
        opacity: .85;
        transform:
            translate(5px,5px)
            scale(.8);
    }

}


@keyframes sparkTwo {

    from {
        opacity: 0;
        transform:
            translate(95px,-80px)
            scale(.2);
    }

    55% {
        opacity: 1;
    }

    to {
        opacity: .85;
        transform:
            translate(-5px,5px)
            scale(.8);
    }

}


@keyframes sparkThree {

    from {
        opacity: 0;
        transform:
            translate(-105px,0)
            scale(.2);
    }

    55% {
        opacity: 1;
    }

    to {
        opacity: .85;
        transform:
            translate(7px,0)
            scale(.8);
    }

}


@keyframes sparkFour {

    from {
        opacity: 0;
        transform:
            translate(105px,0)
            scale(.2);
    }

    55% {
        opacity: 1;
    }

    to {
        opacity: .85;
        transform:
            translate(-7px,0)
            scale(.8);
    }

}


@keyframes sparkFive {

    from {
        opacity: 0;
        transform:
            translate(-80px,85px)
            scale(.2);
    }

    55% {
        opacity: 1;
    }

    to {
        opacity: .85;
        transform:
            translate(5px,-5px)
            scale(.8);
    }

}


@keyframes sparkSix {

    from {
        opacity: 0;
        transform:
            translate(85px,85px)
            scale(.2);
    }

    55% {
        opacity: 1;
    }

    to {
        opacity: .85;
        transform:
            translate(-5px,-5px)
            scale(.8);
    }

}


@keyframes sparkSeven {

    from {
        opacity: 0;
        transform:
            translate(0,-105px)
            scale(.2);
    }

    55% {
        opacity: 1;
    }

    to {
        opacity: .85;
        transform:
            translate(0,8px)
            scale(.8);
    }

}


@keyframes sparkEight {

    from {
        opacity: 0;
        transform:
            translate(0,105px)
            scale(.2);
    }

    55% {
        opacity: 1;
    }

    to {
        opacity: .85;
        transform:
            translate(0,-8px)
            scale(.8);
    }

}


/* ==========================================================
   MOBILE
========================================================== */

@media (max-width: 575.98px) {

    .opening-logo-stage {

        width: 180px;

        height: 180px;

    }


    .opening-logo-final {

        width: 122px;

        height: 122px;

    }


    .opening-logo-final img {

        width: 96px;

        height: 96px;

    }


    .opening-ssis {

        font-size: 2.4rem;

    }


    .opening-name {

        font-size: .74rem;

    }


    .opening-school {

        font-size: .68rem;

    }


    .opening-orbit {

        width: 330px;

        height: 330px;

    }


    .orbit-two {

        width: 260px;

        height: 260px;

    }

}


/* ==========================================================
   REDUCED MOTION
========================================================== */

@media (prefers-reduced-motion: reduce) {

    .cinematic-opening *,
    .cinematic-opening::before,
    .cinematic-opening::after {

        animation: none !important;

    }

}

/* ==========================================================
   TRANSITION TO LANDING PAGE
========================================================== */

.opening-flash {

    position: absolute;

    left: 50%;

    top: 50%;

    width: 20px;

    height: 20px;

    border-radius: 50%;

    background: #ffffff;

    transform:
        translate(-50%, -50%)
        scale(0);

    opacity: 0;

    z-index: 50;

    pointer-events: none;

}


/*
|--------------------------------------------------------------------------
| Saat opening ditutup
|--------------------------------------------------------------------------
*/

.cinematic-opening.hide .opening-flash {

    animation:
        openingFlash
        1s
        cubic-bezier(.16,1,.3,1)
        forwards;

}


@keyframes openingFlash {

    0% {

        opacity: 0;

        transform:
            translate(-50%, -50%)
            scale(0);

    }

    12% {

        opacity: .9;

        transform:
            translate(-50%, -50%)
            scale(3);

    }

    45% {

        opacity: 1;

        transform:
            translate(-50%, -50%)
            scale(25);

    }

    70% {

        opacity: .95;

        transform:
            translate(-50%, -50%)
            scale(65);

    }

    100% {

        opacity: 0;

        transform:
            translate(-50%, -50%)
            scale(95);

    }

}

/* ==========================================================
   LIVING HERO BACKGROUND
========================================================== */

.hero {

    isolation: isolate;

}


.hero-living-bg {

    position: absolute;

    inset: 0;

    overflow: hidden;

    pointer-events: none;

    z-index: 0;

}


/* ==========================================================
   ORBS
========================================================== */

.living-orb {

    position: absolute;

    border-radius: 50%;

    filter: blur(55px);

    opacity: .45;

}


.orb-blue {

    width: 420px;

    height: 420px;

    background:
        rgba(32,107,196,.12);

    top: -120px;

    right: -80px;

    animation:
        livingOrbBlue
        12s
        ease-in-out
        infinite;

}


.orb-cyan {

    width: 300px;

    height: 300px;

    background:
        rgba(14,165,233,.09);

    bottom: -100px;

    left: 10%;

    animation:
        livingOrbCyan
        10s
        ease-in-out
        infinite;

}


.orb-soft {

    width: 220px;

    height: 220px;

    background:
        rgba(47,179,68,.055);

    top: 35%;

    left: -90px;

    animation:
        livingOrbSoft
        14s
        ease-in-out
        infinite;

}


/* ==========================================================
   SMALL FLOATING DOTS
========================================================== */

.living-dot {

    position: absolute;

    width: 5px;

    height: 5px;

    border-radius: 50%;

    background:
        rgba(32,107,196,.35);

    box-shadow:
        0 0 14px rgba(32,107,196,.18);

    animation:
        livingDot
        7s
        ease-in-out
        infinite;

}


.dot-1 {
    left: 9%;
    top: 24%;
    animation-delay: 0s;
}

.dot-2 {
    left: 21%;
    top: 70%;
    animation-delay: 1s;
}

.dot-3 {
    left: 39%;
    top: 18%;
    animation-delay: 2s;
}

.dot-4 {
    right: 22%;
    top: 29%;
    animation-delay: 1.5s;
}

.dot-5 {
    right: 10%;
    top: 72%;
    animation-delay: 3s;
}

.dot-6 {
    left: 52%;
    bottom: 10%;
    animation-delay: 2.5s;
}


/* ==========================================================
   KEYFRAMES
========================================================== */

@keyframes livingOrbBlue {

    0%,
    100% {

        transform:
            translate(0,0)
            scale(1);

    }

    50% {

        transform:
            translate(-70px,45px)
            scale(1.15);

    }

}


@keyframes livingOrbCyan {

    0%,
    100% {

        transform:
            translate(0,0)
            scale(1);

    }

    50% {

        transform:
            translate(80px,-50px)
            scale(1.12);

    }

}


@keyframes livingOrbSoft {

    0%,
    100% {

        transform:
            translate(0,0)
            scale(1);

    }

    50% {

        transform:
            translate(65px,-35px)
            scale(1.1);

    }

}


@keyframes livingDot {

    0%,
    100% {

        opacity: .2;

        transform:
            translate(0,0)
            scale(.8);

    }

    50% {

        opacity: 1;

        transform:
            translate(
                20px,
                -25px
            )
            scale(1.25);

    }

}

.hero-image-wrapper {

    will-change: transform;

    transition:
        transform .25s
        cubic-bezier(.22,1,.36,1);

}

/* ==========================================================
   CURSOR GLOW
========================================================== */

.cursor-glow {

    position: fixed;

    width: 180px;

    height: 180px;

    border-radius: 50%;

    pointer-events: none;

    z-index: 1;

    background:
        radial-gradient(
            circle,
            rgba(32,107,196,.10),
            rgba(32,107,196,.035) 40%,
            transparent 72%
        );

    transform:
        translate(-50%,-50%);

    opacity: 0;

    transition:
        opacity .3s ease;

    mix-blend-mode: multiply;

}


@media (hover:hover) {

    .cursor-glow {

        opacity: 1;

    }

}


@media (max-width: 991.98px) {

    .cursor-glow {

        display: none;

    }

}

/* ==========================================================
   LIVE STATUS
========================================================== */

.system-live {

    display: inline-flex;

    align-items: center;

    gap: 8px;

}


.system-live-dot {

    width: 8px;

    height: 8px;

    border-radius: 50%;

    background: #2FB344;

    box-shadow:
        0 0 0 0
        rgba(47,179,68,.35);

    animation:
        livePulse
        1.8s
        infinite;

}


@keyframes livePulse {

    0% {

        box-shadow:
            0 0 0 0
            rgba(47,179,68,.35);

    }

    70% {

        box-shadow:
            0 0 0 9px
            rgba(47,179,68,0);

    }

    100% {

        box-shadow:
            0 0 0 0
            rgba(47,179,68,0);

    }

}

.live-clock {

    margin-top: 5px;

    font-size: .68rem;

    font-weight: 600;

    color: var(--primary);

    font-variant-numeric:
        tabular-nums;

}

</style>

</head>


<body>

{{-- ==========================================================
| CINEMATIC OPENING FINAL
========================================================== --}}

<div
    id="cinematicOpening"
    class="cinematic-opening"
>

    <div class="opening-backdrop"></div>

    <div class="opening-flash"></div>

    <div class="opening-aura aura-one"></div>
    <div class="opening-aura aura-two"></div>


    {{-- PARTIKEL --}}

    <div class="opening-particles">

        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>

    </div>


    {{-- GARIS CAHAYA --}}

    <div class="opening-orbit orbit-one"></div>
    <div class="opening-orbit orbit-two"></div>


    {{-- CENTER --}}

    <div class="opening-center">


        {{-- LOGO --}}

        <div class="opening-logo-stage">

            {{-- pecahan cahaya --}}

            <span class="logo-spark spark-1"></span>
            <span class="logo-spark spark-2"></span>
            <span class="logo-spark spark-3"></span>
            <span class="logo-spark spark-4"></span>
            <span class="logo-spark spark-5"></span>
            <span class="logo-spark spark-6"></span>
            <span class="logo-spark spark-7"></span>
            <span class="logo-spark spark-8"></span>


            {{-- LOGO ASLI --}}

            <div class="opening-logo-final">

                <img
                    src="{{ asset('images/logo SMAN 6.png') }}"
                    alt="Logo SMA Negeri 6 Malinau"
                >

                <span class="logo-shine"></span>

            </div>

        </div>


        {{-- BRAND --}}

        <div class="opening-brand">

            <div class="opening-ssis">
                SSIS
            </div>

            <div class="opening-name">
                Smart School Information System
            </div>

            <div class="opening-divider">

                <span></span>

            </div>

            <div class="opening-school">
                SMA Negeri 6 Malinau
            </div>

        </div>


        {{-- LOADING --}}

        <div class="opening-loading">

            <span></span>
            <span></span>
            <span></span>

        </div>

    </div>

</div>


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

    <div class="hero-living-bg">

        <span class="living-orb orb-blue"></span>
        <span class="living-orb orb-cyan"></span>
        <span class="living-orb orb-soft"></span>

        <span class="living-dot dot-1"></span>
        <span class="living-dot dot-2"></span>
        <span class="living-dot dot-3"></span>
        <span class="living-dot dot-4"></span>
        <span class="living-dot dot-5"></span>
        <span class="living-dot dot-6"></span>

    </div>

    <div class="container">

        <div class="row align-items-center gy-5">

            <div class="col-lg-6">

                <div class="hero-content">

                    <span class="section-badge">

                        <i class="bi bi-mortarboard-fill"></i>

                        Smart School Information System

                    </span>


                    <h1>
                        Sistem Informasi
                        <span>
                            SMA Negeri 6 Malinau
                        </span>
                    </h1>


                    <p>
                        <strong>SMA Negeri 6 Malinau</strong> atau
                        <strong>SMA 6 Malinau</strong> yang berada di
                        <strong>Sungai Boh</strong> menggunakan SSIS
                        (<strong>Smart School Information System</strong>) untuk
                        mendukung layanan sekolah secara digital.

                        Sistem ini mengintegrasikan
                        <strong>Absensi Digital</strong>,
                        <strong>Computer Based Test (CBT)</strong>,
                        dan
                        <strong>Perpustakaan Digital</strong>
                        dalam satu platform.

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
                        src="{{ asset('images/Sma62.png') }}"
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

                                <div class="system-live">

    <span class="system-live-dot"></span>

    <strong>
        Sistem Aktif
    </strong>

</div>

<br>

<small class="text-muted">
    SSIS siap digunakan
</small>

<div
    class="live-clock"
    id="liveClock">

    00:00:00 WITA

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
                        src="{{ asset('images/Sma6.png') }}"
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

<div
    id="cursorGlow"
    class="cursor-glow">
</div>


{{-- ==========================================================
| BOOTSTRAP JS
========================================================== --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /* ==========================================================
   CINEMATIC OPENING FINAL
========================================================== */

/* ==========================================================
   CINEMATIC OPENING FINAL
========================================================== */

const cinematicOpening =
    document.getElementById(
        'cinematicOpening'
    );


if (cinematicOpening) {

    /*
    |--------------------------------------------------------------------------
    | LOGO SELESAI TERBENTUK
    |--------------------------------------------------------------------------
    |
    | Potongan logo selesai berkumpul sekitar 2.5 - 3 detik.
    |
    */

    setTimeout(
        function () {

            /*
            |--------------------------------------------------------------------------
            | Tahan logo + teks selama 1.5 detik
            |--------------------------------------------------------------------------
            |
            | Jadi pengguna benar-benar sempat melihat
            | logo dan identitas sekolah.
            |
            */

            setTimeout(
                function () {

                    cinematicOpening.classList.add(
                        'hide'
                    );

                },
                1500
            );

        },
        3000
    );


    /*
    |--------------------------------------------------------------------------
    | Hapus setelah seluruh transisi selesai
    |--------------------------------------------------------------------------
    */

    setTimeout(
        function () {

            cinematicOpening.remove();

        },
        4500
    );

}

/* ==========================================================
   HERO PARALLAX
========================================================== */

const heroImageWrapper =
    document.querySelector(
        '.hero-image-wrapper'
    );


if (
    heroImageWrapper &&
    window.matchMedia(
        '(pointer:fine)'
    ).matches
) {

    document.addEventListener(
        'mousemove',
        function (event) {

            const x =
                (
                    event.clientX /
                    window.innerWidth
                ) - .5;

            const y =
                (
                    event.clientY /
                    window.innerHeight
                ) - .5;


            heroImageWrapper.style.transform =
                `
                translate(
                    ${x * 10}px,
                    ${y * 8}px
                )
                `;

        }
    );

}

/* ==========================================================
   CURSOR GLOW
========================================================== */

const cursorGlow =
    document.getElementById(
        'cursorGlow'
    );


if (
    cursorGlow &&
    window.matchMedia(
        '(pointer:fine)'
    ).matches
) {

    document.addEventListener(
        'mousemove',
        function (event) {

            cursorGlow.style.left =
                event.clientX + 'px';

            cursorGlow.style.top =
                event.clientY + 'px';

        }
    );

}

/* ==========================================================
   LIVE CLOCK
========================================================== */

const liveClock =
    document.getElementById(
        'liveClock'
    );


function updateLiveClock() {

    if (! liveClock) {
        return;
    }


    const now =
        new Date();


    const time =
        now.toLocaleTimeString(
            'id-ID',
            {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
                timeZone: 'Asia/Makassar'
            }
        );


    liveClock.textContent =
        time + ' WITA';

}


updateLiveClock();


setInterval(
    updateLiveClock,
    1000
);


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
