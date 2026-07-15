<div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Guru</label>

        <input
            type="text"
            name="nama"
            value="{{ old('nama', $guru->nama ?? '') }}"
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
            value="{{ old('nip', $guru->nip ?? '') }}"
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
            value="{{ old('username', $guru->user->username ?? '') }}"
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
            value="{{ old('email', $guru->user->email ?? '') }}"
            class="form-control @error('email') is-invalid @enderror"
        >

        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">
            Password
            @isset($guru)
                <span class="text-secondary">
                    (kosongkan jika tidak diubah)
                </span>
            @endisset
        </label>

        <input
            type="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            @required(!isset($guru))
        >

        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Konfirmasi Password</label>

        <input
            type="password"
            name="password_confirmation"
            class="form-control"
            @required(!isset($guru))
        >
    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">Jenis Kelamin</label>

        <select
            name="jenis_kelamin"
            class="form-select"
        >
            <option value="">Pilih</option>

            <option
                value="L"
                @selected(old('jenis_kelamin', $guru->jenis_kelamin ?? '') === 'L')
            >
                Laki-laki
            </option>

            <option
                value="P"
                @selected(old('jenis_kelamin', $guru->jenis_kelamin ?? '') === 'P')
            >
                Perempuan
            </option>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Nomor HP</label>

        <input
            type="text"
            name="no_hp"
            value="{{ old('no_hp', $guru->no_hp ?? '') }}"
            class="form-control"
        >
    </div>

</div>

<div class="mb-3">
    <label class="form-label">Alamat</label>

    <textarea
        name="alamat"
        class="form-control"
        rows="3"
    >{{ old('alamat', $guru->alamat ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-check">

        <input
            type="checkbox"
            name="is_active"
            value="1"
            class="form-check-input"
            @checked(old('is_active', $guru->is_active ?? true))
        >

        <span class="form-check-label">
            Akun guru aktif
        </span>

    </label>
</div>