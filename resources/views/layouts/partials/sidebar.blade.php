<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">

        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('dashboard') }}" class="text-decoration-none">
                <span class="ssis-brand text-white">
                    SSIS
                </span>
            </a>
        </h1>

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


    {{-- MENU GURU --}}
    @if(auth()->user()->isguru())

        <li class="nav-item">

            <a class="nav-link" href="#">

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
    @if(auth()->user()->isSiswa())

        <li class="nav-item">

            <a class="nav-link" href="#">

                <span class="nav-link-icon">
                    <i class="ti ti-scan"></i>
                </span>

                <span class="nav-link-title">
                    Absensi Saya
                </span>

            </a>

        </li>

    @endif

</ul>

        </div>
    </div>
</aside>