<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sidebar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Judul -->
        <div class="navbar-nav">
            <div class="nav-item">
                <span class="nav-link fw-bold">
                    Smart School Information System
                </span>
            </div>
        </div>

        <!-- User -->
        <div class="navbar-nav flex-row order-md-last align-items-center">

            <div class="nav-item d-flex align-items-center">

                <span class="avatar avatar-sm me-2">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>

                <div class="d-none d-xl-block me-3">
                    <div class="fw-bold">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="small text-secondary text-capitalize">
                        {{ auth()->user()->role }}
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="ti ti-logout me-1"></i>
                        Logout
                    </button>
                </form>

            </div>

        </div>

    </div>
</header>