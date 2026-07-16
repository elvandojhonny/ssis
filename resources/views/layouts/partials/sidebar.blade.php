@php
    $user = auth()->user();
@endphp

<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">

        <div class="ssis-sidebar-brand">

    <a
        href="{{ route('dashboard') }}"
        class="ssis-brand-link"
    >

        {{-- Logo Oval --}}
        <div class="ssis-logo">

            {{-- Untuk sementara pakai icon --}}
            <i class="ti ti-school"></i>

            {{-- Jika sudah punya logo sekolah, ganti dengan:

            <img
                src="{{ asset('images/logo-sekolah.png') }}"
                alt="Logo Sekolah"
            >

            --}}

        </div>


        {{-- Nama Sistem --}}
        <div class="ssis-brand-content">

            <div class="ssis-brand-name">
                SSIS
            </div>

            <div class="ssis-brand-subtitle">
                Smart School
            </div>

        </div>

    </a>

</div>

        <div class="collapse navbar-collapse" id="sidebar-menu">

            <ul class="navbar-nav pt-lg-3">

    {{-- Dashboard --}}
    <li class="nav-item">

        <a
            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            href="{{ route('dashboard') }}"
        >

            <span class="nav-link-icon">
                <i class="ti ti-layout-dashboard"></i>
            </span>

            <span class="nav-link-title">
                Dashboard
            </span>

        </a>

    </li>


    {{-- MENU OPERATOR --}}
    @if(auth()->user()->isOperator())

        <li class="nav-item mt-3">

            <div class="nav-link disabled">

                <span class="nav-link-title text-secondary">
                    MASTER DATA
                </span>

            </div>

        </li>



        <li class="nav-item">

            <a
                class="nav-link {{ request()->routeIs('tahun-ajaran.*') ? 'active' : '' }}"
                href="{{ route('tahun-ajaran.index') }}"
            >

                <span class="nav-link-icon">
                    <i class="ti ti-calendar"></i>
                </span>

                <span class="nav-link-title">
                    Tahun Ajaran
                </span>

            </a>

        </li>


        <li class="nav-item">

            <a
                class="nav-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}"
                href="{{ route('kelas.index') }}"
            >

                <span class="nav-link-icon">
                    <i class="ti ti-school"></i>
                </span>

                <span class="nav-link-title">
                    Kelas
                </span>

            </a>

        </li>


        <li class="nav-item">

            <a
                class="nav-link {{ request()->routeIs('guru.*') ? 'active' : '' }}"
                href="{{ route('guru.index') }}"
            >

                <span class="nav-link-icon">
                    <i class="ti ti-user"></i>
                </span>

                <span class="nav-link-title">
                    Guru
                </span>

            </a>

        </li>


        <li class="nav-item">

            <a
                class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}"
                href="{{ route('siswa.index') }}"
            >

                <span class="nav-link-icon">
                    <i class="ti ti-users"></i>
                </span>

                <span class="nav-link-title">
                    Siswa
                </span>

            </a>

        </li>

    @endif

    {{-- MENU ABSENSI OPERATOR --}}
@if($user->isOperator())

<li class="nav-item mt-3">
    <div class="nav-link disabled">
        <span class="nav-link-title text-secondary">
            ABSENSI
        </span>
    </div>
</li>

<li class="nav-item">
    <a
        class="nav-link {{ request()->routeIs('absensi.sesi.*') ? 'active' : '' }}"
        href="{{ route('absensi.sesi.index') }}"
    >
        <span class="nav-link-icon">
            <i class="ti ti-qrcode"></i>
        </span>

        <span class="nav-link-title">
            Sesi Absensi
        </span>
    </a>
</li>

<li class="nav-item">

    <a
        class="nav-link {{
            request()->routeIs('absensi.rekap.*')
                ? 'active'
                : ''
        }}"
        href="{{ route('absensi.rekap.index') }}"
    >

        <span class="nav-link-icon">

            <i class="ti ti-report-analytics"></i>

        </span>

        <span class="nav-link-title">

            Rekap Absensi

        </span>

    </a>

</li>

@endif


{{-- MENU GURU --}}
@if($user->isGuru())

<li class="nav-item mt-3">
    <div class="nav-link disabled">
        <span class="nav-link-title text-secondary">
            ABSENSI
        </span>
    </div>
</li>

<li class="nav-item">
    <a
        class="nav-link {{ request()->routeIs('absensi.sesi.*') ? 'active' : '' }}"
        href="{{ route('absensi.sesi.index') }}"
    >
        <span class="nav-link-icon">
            <i class="ti ti-qrcode"></i>
        </span>

        <span class="nav-link-title">
            Absensi
        </span>
    </a>
</li>

@endif


{{-- MENU SISWA --}}
@if($user->isSiswa())

<li class="nav-item mt-3">
    <div class="nav-link disabled">
        <span class="nav-link-title text-secondary">
            ABSENSI
        </span>
    </div>
</li>

<li class="nav-item">
    <a
        href="{{ route('absensi.siswa.index') }}"
        class="nav-link {{ request()->routeIs('absensi.siswa.*') ? 'active' : '' }}"
    >
        <span class="nav-link-icon">
            <i class="ti ti-scan"></i>
        </span>

        <span class="nav-link-title">
            Absensi Saya
        </span>
    </a>
</li>

@endif

        </div>
    </div>
</aside>