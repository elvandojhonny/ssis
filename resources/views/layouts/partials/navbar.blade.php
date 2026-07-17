<header class="ssis-navbar d-print-none">

    <div class="ssis-navbar-inner">

        {{-- =====================================================
            MOBILE SIDEBAR TOGGLE
        ====================================================== --}}
        <button
            class="navbar-toggler ssis-menu-toggle"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu"
            aria-expanded="false"
            aria-label="Buka menu navigasi"
        >
            <span class="navbar-toggler-icon"></span>
        </button>


        {{-- =====================================================
            JUDUL APLIKASI
        ====================================================== --}}
        <div class="ssis-navbar-title">

            <span class="ssis-navbar-title-main">
                Smart School Information System
            </span>

        </div>


        {{-- =====================================================
            USER PROFILE
        ====================================================== --}}
        <div class="dropdown">

            <button
                class="ssis-user-menu"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >

                {{-- Avatar --}}
                <span class="avatar ssis-user-avatar">

                    {{
                        strtoupper(
                            substr(
                                auth()->user()->name,
                                0,
                                1
                            )
                        )
                    }}

                </span>


                {{-- Nama & Role --}}
                <span class="ssis-user-info">

                    <span class="ssis-user-name">
                        {{ auth()->user()->name }}
                    </span>

                    <span class="ssis-user-role">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>

                </span>


                {{-- Dropdown Icon --}}
                <i
                    class="
                        ti
                        ti-chevron-down
                        ssis-user-arrow
                    "
                ></i>

            </button>


            {{-- =================================================
                PROFILE DROPDOWN
            ================================================== --}}
            <div
                class="
                    dropdown-menu
                    dropdown-menu-end
                    ssis-profile-dropdown
                "
            >

                {{-- Informasi User --}}
                <div class="ssis-dropdown-user">

                    <span class="avatar ssis-user-avatar">

                        {{
                            strtoupper(
                                substr(
                                    auth()->user()->name,
                                    0,
                                    1
                                )
                            )
                        }}

                    </span>


                    <div class="ssis-dropdown-user-info">

                        <div class="fw-bold">
                            {{ auth()->user()->name }}
                        </div>

                        <div
                            class="
                                small
                                text-secondary
                                text-capitalize
                            "
                        >
                            {{ auth()->user()->role }}
                        </div>

                    </div>

                </div>


                <div class="dropdown-divider"></div>


                {{-- Logout --}}
                <form
                    action="{{ route('logout') }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="submit"
                        class="
                            dropdown-item
                            text-danger
                            ssis-logout-item
                        "
                    >

                        <i class="ti ti-logout me-2"></i>

                        Keluar dari Sistem

                    </button>

                </form>

            </div>

        </div>

    </div>

</header>