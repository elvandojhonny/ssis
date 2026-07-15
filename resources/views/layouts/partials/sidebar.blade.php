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

                <li class="nav-item">
                    <a class="nav-link active"
                       href="{{ route('dashboard') }}">

                        <span class="nav-link-icon">
                            <i class="ti ti-layout-dashboard"></i>
                        </span>

                        <span class="nav-link-title">
                            Dashboard
                        </span>

                    </a>
                </li>

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

            </ul>

        </div>
    </div>
</aside>