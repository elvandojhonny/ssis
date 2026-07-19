<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Dashboard') | SSIS
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')

</head>


<body class="ssis-body">

<div class="page ssis-app">

    {{-- ===================================================== --}}
    {{-- SIDEBAR --}}
    {{-- ===================================================== --}}

    @include('layouts.partials.sidebar')


    {{-- ===================================================== --}}
    {{-- MAIN WRAPPER --}}
    {{-- ===================================================== --}}

    <div class="page-wrapper ssis-wrapper">


        {{-- ================================================= --}}
        {{-- NAVBAR --}}
        {{-- ================================================= --}}

        @include('layouts.partials.navbar')


        {{-- ================================================= --}}
        {{-- MAIN CONTENT --}}
        {{-- ================================================= --}}

        <main class="page-body ssis-content">

            <div class="container-xl">

                @yield('content')

            </div>

        </main>


        {{-- ================================================= --}}
        {{-- FOOTER --}}
        {{-- ================================================= --}}

        @include('layouts.partials.footer')

    </div>

</div>


{{-- ========================================================= --}}
{{-- MOBILE SIDEBAR OVERLAY --}}
{{-- ========================================================= --}}

<div
    id="sidebar-overlay"
    class="ssis-sidebar-overlay"
></div>


@stack('scripts')

{{-- ========================================================= --}}
{{-- GLOBAL DELETE CONFIRMATION MODAL --}}
{{-- ========================================================= --}}

<div
    class="modal modal-blur fade"
    id="globalDeleteModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Konfirmasi Hapus
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>
            </div>


            <div class="modal-body text-center py-4">

                <span class="avatar avatar-xl bg-danger-lt mb-3">
                    <i class="ti ti-trash"></i>
                </span>

                <h3 class="mb-2">
                    Hapus Data?
                </h3>

                <p class="text-secondary mb-0">
                    Apakah Anda yakin ingin menghapus
                    <strong
                        class="text-body"
                        id="globalDeleteName"
                    ></strong>?
                </p>


                <div
                    class="alert alert-warning text-start mt-4 mb-0"
                    id="globalDeleteWarningContainer"
                >
                    <div class="d-flex">

                        <div class="me-2">
                            <i class="ti ti-alert-triangle"></i>
                        </div>

                        <div
                            class="small"
                            id="globalDeleteWarning"
                        >
                            Pastikan data yang dipilih sudah benar.
                        </div>

                    </div>
                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>


                <form
                    id="globalDeleteForm"
                    method="POST"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        <i class="ti ti-trash me-1"></i>
                        Ya, Hapus
                    </button>
                </form>

            </div>

        </div>

    </div>
</div>


{{-- ========================================================= --}}
{{-- GLOBAL DELETE SCRIPT --}}
{{-- ========================================================= --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modalElement =
        document.getElementById('globalDeleteModal');

    const deleteForm =
        document.getElementById('globalDeleteForm');

    const deleteName =
        document.getElementById('globalDeleteName');

    const deleteWarning =
        document.getElementById('globalDeleteWarning');


    if (
        !modalElement ||
        !deleteForm ||
        !deleteName ||
        !deleteWarning
    ) {
        return;
    }


    modalElement.addEventListener(
        'show.bs.modal',
        function (event) {

            const button = event.relatedTarget;

            if (!button) {
                return;
            }


            const action =
                button.getAttribute('data-delete-action');

            const name =
                button.getAttribute('data-delete-name');

            const warning =
                button.getAttribute('data-delete-warning');


            /*
            |--------------------------------------------------------------------------
            | URL Delete
            |--------------------------------------------------------------------------
            */

            deleteForm.action = action || '';


            /*
            |--------------------------------------------------------------------------
            | Nama Data
            |--------------------------------------------------------------------------
            */

            deleteName.textContent =
                name || 'data ini';


            /*
            |--------------------------------------------------------------------------
            | Pesan Peringatan
            |--------------------------------------------------------------------------
            */

            deleteWarning.textContent =
                warning ||
                'Data yang sudah terhubung dengan data lain mungkin tidak dapat dihapus.';

        }
    );

});
</script>

</body>

</html>