@php
    $user = auth()->user();
@endphp

<aside
    class="
        navbar
        navbar-vertical
        navbar-expand-lg
        ssis-sidebar
    "
    data-bs-theme="dark"
>

    <div class="container-fluid">

        {{-- =====================================================
            BRAND / LOGO
        ====================================================== --}}
        <div class="ssis-sidebar-brand">

            <a
                href="{{ route('dashboard') }}"
                class="ssis-brand-link"
            >
                {{-- Logo Sekolah --}}
                <div class="ssis-logo">
                    <img
                        src="{{ asset('images/logo SMAN 6.png') }}"
                        alt="Logo SMA Negeri 6"
                    >
                </div>

                {{-- Nama Sistem --}}
                <div class="ssis-brand-content">

                    <div class="ssis-brand-name">
                        SMA Negeri 6
                    </div>

                    <div class="ssis-brand-subtitle">
                        Smart School Information System
                    </div>

                </div>

            </a>

        </div>


        {{-- =====================================================
            SIDEBAR MENU
        ====================================================== --}}
        <div
            class="collapse navbar-collapse"
            id="sidebar-menu"
        >

            <ul class="navbar-nav pt-lg-3">

                {{-- =================================================
                    DASHBOARD
                ================================================== --}}
                <li class="nav-item">

                    <a
                        class="nav-link {{
                            request()->routeIs('dashboard')
                                ? 'active'
                                : ''
                        }}"
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


                {{-- =================================================
                    MENU OPERATOR
                ================================================== --}}
                @if($user->isOperator())

                    {{-- MASTER DATA --}}
                    <li class="nav-item mt-3">

                        <div class="nav-link disabled">

                            <span
                                class="
                                    nav-link-title
                                    text-secondary
                                "
                            >
                                MASTER DATA
                            </span>

                        </div>

                    </li>


                    {{-- Tahun Ajaran --}}
                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs(
                                    'tahun-ajaran.*'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                            href="{{
                                route('tahun-ajaran.index')
                            }}"
                        >

                            <span class="nav-link-icon">
                                <i class="ti ti-calendar"></i>
                            </span>

                            <span class="nav-link-title">
                                Tahun Ajaran
                            </span>

                        </a>

                    </li>


                    {{-- Kelas --}}
                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs('kelas.*')
                                    ? 'active'
                                    : ''
                            }}"
                            href="{{ route('kelas.index') }}"
                        >

                            <span class="nav-link-icon">
                                <i class="ti ti-door-enter"></i>
                            </span>

                            <span class="nav-link-title">
                                Kelas
                            </span>

                        </a>

                    </li>


                    {{-- Guru --}}
                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs('guru.*')
                                    ? 'active'
                                    : ''
                            }}"
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


                    {{-- Siswa --}}
                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs('siswa.*')
                                    ? 'active'
                                    : ''
                            }}"
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


                    {{-- =================================================
                        ABSENSI OPERATOR
                    ================================================== --}}

                    <li class="nav-item mt-3">

                        <div class="nav-link disabled">

                            <span
                                class="
                                    nav-link-title
                                    text-secondary
                                "
                            >
                                ABSENSI
                            </span>

                        </div>

                    </li>


                    {{-- Sesi Absensi --}}
                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs(
                                    'absensi.sesi.*'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                            href="{{
                                route('absensi.sesi.index')
                            }}"
                        >

                            <span class="nav-link-icon">
                                <i class="ti ti-qrcode"></i>
                            </span>

                            <span class="nav-link-title">
                                Sesi Absensi
                            </span>

                        </a>

                    </li>


                    {{-- Rekap Absensi --}}
                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs(
                                    'absensi.rekap.*'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                            href="{{
                                route('absensi.rekap.index')
                            }}"
                        >

                            <span class="nav-link-icon">
                                <i
                                    class="
                                        ti
                                        ti-report-analytics
                                    "
                                ></i>
                            </span>

                            <span class="nav-link-title">
                                Rekap Absensi
                            </span>

                        </a>

                    </li>

                    <li class="nav-item mt-3">

                        <div class="nav-link disabled">

                            <span class="nav-link-title text-secondary">
                                CBT
                            </span>

                        </div>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs('cbt.ujian.*')
                                    ? 'active'
                                    : ''
                            }}"
                            href="{{ route('cbt.ujian.index') }}"
                        >

                            <span class="nav-link-icon">
                                <i class="ti ti-file-pencil"></i>
                            </span>

                            <span class="nav-link-title">
                                Kelola Ujian
                            </span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs('cbt.rekap.*')
                                    ? 'active'
                                    : ''
                            }}"
                            href="{{ route('cbt.rekap.index') }}"
                        >

                            <span class="nav-link-icon">
                                <i class="ti ti-report-analytics"></i>
                            </span>

                            <span class="nav-link-title">
                                Rekap Hasil Ujian
                            </span>

                        </a>

                    </li>

                @endif


                {{-- =================================================
                    MENU GURU
                ================================================== --}}
                @if($user->isGuru())

                    <li class="nav-item mt-3">

                        <div class="nav-link disabled">

                            <span
                                class="
                                    nav-link-title
                                    text-secondary
                                "
                            >
                                ABSENSI
                            </span>

                        </div>

                    </li>


                    {{-- Absensi --}}
                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs(
                                    'absensi.sesi.*'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                            href="{{
                                route('absensi.sesi.index')
                            }}"
                        >

                            <span class="nav-link-icon">
                                <i class="ti ti-qrcode"></i>
                            </span>

                            <span class="nav-link-title">
                                Absensi
                            </span>

                        </a>

                    </li>

                    <li class="nav-item mt-3">

                        <div class="nav-link disabled">

                            <span class="nav-link-title text-secondary">
                                CBT
                            </span>

                        </div>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs('cbt.bank-soal.*')
                                    ? 'active'
                                    : ''
                            }}"
                            href="{{ route('cbt.bank-soal.index') }}"
                        >

                            <span class="nav-link-icon">

                                <i class="ti ti-file-text"></i>

                            </span>


                            <span class="nav-link-title">

                                Bank Soal

                            </span>

                        </a>

                    </li>

                @endif


                {{-- =================================================
                    MENU SISWA
                ================================================== --}}
                @if($user->isSiswa())

                    <li class="nav-item mt-3">

                        <div class="nav-link disabled">

                            <span
                                class="
                                    nav-link-title
                                    text-secondary
                                "
                            >
                                KEHADIRAN
                            </span>

                        </div>

                    </li>


                    {{-- Kehadiran Saya --}}
                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs(
                                    'absensi.siswa.*'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                            href="{{
                                route('absensi.siswa.index')
                            }}"
                        >

                            <span class="nav-link-icon">
                                <i
                                    class="
                                        ti
                                        ti-report-analytics
                                    "
                                ></i>
                            </span>

                            <span class="nav-link-title">
                                Kehadiran Saya
                            </span>

                        </a>

                    </li>

                    <li class="nav-item mt-3">

                        <div class="nav-link disabled">

                            <span class="nav-link-title text-secondary">
                                CBT
                            </span>

                        </div>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs('cbt.siswa.*')
                                    ? 'active'
                                    : ''
                            }}"
                            href="{{ route('cbt.siswa.index') }}"
                        >

                            <span class="nav-link-icon">

                                <i class="ti ti-file-pencil"></i>

                            </span>


                            <span class="nav-link-title">

                                Ujian Saya

                            </span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            class="nav-link {{
                                request()->routeIs(
                                    'cbt.siswa.riwayat'
                                )
                                    ||
                                request()->routeIs(
                                    'cbt.siswa.pengerjaan.hasil'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                            href="{{ route('cbt.siswa.riwayat') }}"
                        >

                            <span class="nav-link-icon">

                                <i class="ti ti-history"></i>

                            </span>

                            <span class="nav-link-title">

                                Riwayat Ujian

                            </span>

                        </a>

                    </li>


                @endif

            </ul>

        </div>

    </div>

</aside>