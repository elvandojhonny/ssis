<div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Petugas</label>

        <input
            type="text"
            name="nama"
            value="{{ old('nama', $petugas->nama ?? '') }}"
            class="form-control @error('nama') is-invalid @enderror"
            required
        >

        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">NIP</label>

        <input
            type="text"
            name="nip"
            value="{{ old('nip', $petugas->nip ?? '') }}"
            class="form-control @error('nip') is-invalid @enderror"
        >

        @error('nip')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">Username</label>

        <input
            type="text"
            name="username"
            value="{{ old('username', $petugas->user->username ?? '') }}"
            class="form-control @error('username') is-invalid @enderror"
            required
        >

        @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>

        <input
            type="email"
            name="email"
            value="{{ old('email', $petugas->user->email ?? '') }}"
            class="form-control @error('email') is-invalid @enderror"
        >

        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

{{-- ========================================================= --}}
{{-- AKSES PETUGAS --}}
{{-- ========================================================= --}}

<div class="row">

    <div class="col-12 mb-3">

        <label class="form-label">
            Jenis Petugas
            <span class="text-danger">*</span>
        </label>

        <select
            name="role"
            class="form-select @error('role') is-invalid @enderror"
            required
        >

            <option value="">
                Pilih jenis petugas
            </option>

            <option
                value="petugas"
                @selected(
                    old(
                        'role',
                        $petugas->user->role ?? ''
                    ) === 'petugas'
                )
            >
                Petugas Perpustakaan
            </option>

            <option
                value="petugas_absensi"
                @selected(
                    old(
                        'role',
                        $petugas->user->role ?? ''
                    ) === 'petugas_absensi'
                )
            >
                Petugas Absensi
            </option>

        </select>

        @error('role')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

        <div class="form-hint">

            Pilih akses sistem yang diberikan
            kepada petugas.

        </div>

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Password
            @isset($petugas)
                <span class="text-secondary">(kosongkan jika tidak diubah)</span>
            @endisset
        </label>

        <input
            type="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            @required(!isset($petugas))
        >

        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Konfirmasi Password
        </label>

        <input
            type="password"
            name="password_confirmation"
            class="form-control"
            @required(!isset($petugas))
        >

    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Jenis Kelamin
        </label>

        <select
            name="jenis_kelamin"
            class="form-select"
        >
            <option value="">Pilih</option>

            <option
                value="L"
                @selected(old('jenis_kelamin', $petugas->jenis_kelamin ?? '') == 'L')
            >
                Laki-laki
            </option>

            <option
                value="P"
                @selected(old('jenis_kelamin', $petugas->jenis_kelamin ?? '') == 'P')
            >
                Perempuan
            </option>

        </select>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Nomor HP
        </label>

        <input
            type="text"
            name="no_hp"
            value="{{ old('no_hp', $petugas->no_hp ?? '') }}"
            class="form-control"
        >

    </div>

</div>

<div class="mb-3">

    <label class="form-label">
        Alamat
    </label>

    <textarea
        name="alamat"
        rows="3"
        class="form-control"
    >{{ old('alamat', $petugas->alamat ?? '') }}</textarea>

</div>

<div class="mb-3">

    <label class="form-check">

        <input
            type="checkbox"
            name="is_active"
            value="1"
            class="form-check-input"
            @checked(old('is_active', $petugas->is_active ?? true))
        >

        <span class="form-check-label">
            Akun Petugas Aktif
        </span>

    </label>

</div>