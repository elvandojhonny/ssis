<header class="ssis-navbar d-print-none">

    <div class="ssis-navbar-inner">

        {{-- =====================================================
            MOBILE SIDEBAR TOGGLE
        ====================================================== --}}
        <button
            id="ssisMenuToggle"
            class="navbar-toggler ssis-menu-toggle"
            type="button"
            aria-label="Buka menu navigasi"
            aria-expanded="false"
        >
            <span class="navbar-toggler-icon"></span>
        </button>


        {{-- =====================================================
            JUDUL APLIKASI
        ====================================================== --}}

        <div class="ssis-navbar-title">

            <span class="ssis-navbar-title-main">
                SMA Negeri 6 Malinau
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
                <a
                        href="{{ route('profile.show') }}"
                        class="
                            ssis-dropdown-user
                            text-decoration-none
                            text-reset
                        "
                    >

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


                        <div class="ssis-dropdown-user-info flex-grow-1">

                            <div class="fw-bold">
                                {{ auth()->user()->name }}
                            </div>

                            <div class="small text-secondary text-capitalize">
                                {{ auth()->user()->role }}
                            </div>

                        </div>


                        <i
                            class="
                                ti
                                ti-chevron-right
                                text-secondary
                            "
                        ></i>

                    </a>


                <div class="dropdown-divider"></div>


                {{-- Logout --}}
                <form
                    action="{{ route('logout') }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="button"
                        class="dropdown-item text-danger ssis-logout-item"
                        data-bs-toggle="modal"
                        data-bs-target="#modalLogout"
                    >
                        <i class="ti ti-logout me-2"></i>

                        Keluar dari Sistem
                    </button>

                </form>

            </div>

        </div>

    </div>

</header>

{{-- ========================================================= --}}
{{-- MODAL KONFIRMASI LOGOUT --}}
{{-- ========================================================= --}}

<div
    class="modal modal-blur fade"
    id="modalLogout"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            {{-- Garis Status --}}
            <div class="modal-status bg-danger"></div>


            {{-- Isi Modal --}}
            <div class="modal-body text-center py-4">

                <span class="avatar avatar-xl bg-danger-lt mb-3">

                    <i class="ti ti-logout"></i>

                </span>


                <h3 class="mb-2">

                    Keluar dari Sistem?

                </h3>


                <div class="text-secondary">

                    Apakah Anda yakin ingin keluar dari akun

                    <strong>
                        {{ auth()->user()->name }}
                    </strong>?

                </div>

            </div>


            {{-- Footer --}}
            <div class="modal-footer">

                <div class="w-100">

                    <div class="row g-2">

                        {{-- Batal --}}
                        <div class="col">

                            <button
                                type="button"
                                class="btn w-100"
                                data-bs-dismiss="modal"
                            >

                                Batal

                            </button>

                        </div>


                        {{-- Logout --}}
                        <div class="col">

                            <form
                                action="{{ route('logout') }}"
                                method="POST"
                            >

                                @csrf


                                <button
                                    type="submit"
                                    class="btn btn-danger w-100"
                                >

                                    <i class="ti ti-logout me-1"></i>

                                    Ya, Keluar

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>