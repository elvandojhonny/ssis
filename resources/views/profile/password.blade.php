@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Keamanan Akun
            </div>

            <h2 class="page-title">
                Ubah Password
            </h2>

            <div class="text-secondary mt-1">
                Perbarui password untuk menjaga keamanan akun Anda.
            </div>

        </div>


        <div class="col-12 col-md-auto">

            <a
                href="{{ route('profile.show') }}"
                class="btn btn-outline-secondary w-100"
            >
                <i class="ti ti-arrow-left me-1"></i>
                Kembali
            </a>

        </div>

    </div>

</div>


<div class="row justify-content-center">

    <div class="col-12 col-lg-7">

        <form
            action="{{ route('profile.password.update') }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div class="card">

                <div class="card-header">

                    <div>

                        <h3 class="card-title">

                            <i class="ti ti-lock me-2"></i>

                            Keamanan Password

                        </h3>


                        <div class="text-secondary small mt-1">

                            Masukkan password saat ini sebelum
                            membuat password baru.

                        </div>

                    </div>

                </div>


                <div class="card-body">


                    {{-- PASSWORD SAAT INI --}}

                    <div class="mb-4">

                        <label class="form-label">

                            Password Saat Ini

                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">

                            <input
                                type="password"
                                name="current_password"
                                id="currentPassword"
                                class="
                                    form-control
                                    @error('current_password')
                                        is-invalid
                                    @enderror
                                "
                                autocomplete="current-password"
                                required
                            >


                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="
                                    togglePassword(
                                        'currentPassword',
                                        this
                                    )
                                "
                            >
                                <i class="ti ti-eye"></i>
                            </button>


                            @error('current_password')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    <hr class="my-4">


                    {{-- PASSWORD BARU --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Password Baru

                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">

                            <input
                                type="password"
                                name="password"
                                id="newPassword"
                                class="
                                    form-control
                                    @error('password')
                                        is-invalid
                                    @enderror
                                "
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                required
                            >


                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="
                                    togglePassword(
                                        'newPassword',
                                        this
                                    )
                                "
                            >
                                <i class="ti ti-eye"></i>
                            </button>


                            @error('password')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- KONFIRMASI PASSWORD --}}

                    <div class="mb-3">

                        <label class="form-label">

                            Konfirmasi Password Baru

                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">

                            <input
                                type="password"
                                name="password_confirmation"
                                id="confirmPassword"
                                class="form-control"
                                autocomplete="new-password"
                                placeholder="Masukkan ulang password baru"
                                required
                            >


                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="
                                    togglePassword(
                                        'confirmPassword',
                                        this
                                    )
                                "
                            >
                                <i class="ti ti-eye"></i>
                            </button>

                        </div>

                    </div>


                    <div class="alert alert-info mt-4 mb-0">

                        <div class="d-flex">

                            <i
                                class="
                                    ti
                                    ti-info-circle
                                    me-2
                                    mt-1
                                "
                            ></i>


                            <div>

                                Gunakan password minimal 8 karakter
                                dan hindari menggunakan password yang
                                mudah ditebak.

                            </div>

                        </div>

                    </div>

                </div>


                <div class="card-footer">

                    <div
                        class="
                            d-flex
                            flex-column-reverse
                            flex-sm-row
                            justify-content-end
                            gap-2
                        "
                    >

                        <a
                            href="{{ route('profile.show') }}"
                            class="btn btn-outline-secondary"
                        >
                            Batal
                        </a>


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="ti ti-lock me-1"></i>

                            Ubah Password
                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection


@push('scripts')

<script>

function togglePassword(inputId, button)
{
    const input = document.getElementById(inputId);

    const icon = button.querySelector('i');


    if (input.type === 'password') {

        input.type = 'text';

        icon.classList.remove('ti-eye');

        icon.classList.add('ti-eye-off');

    } else {

        input.type = 'password';

        icon.classList.remove('ti-eye-off');

        icon.classList.add('ti-eye');

    }
}

</script>

@endpush