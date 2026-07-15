<div class="row">

    {{-- Nama Siswa --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">
            Nama Siswa
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="nama"
            value="{{ old('nama', $siswa->nama ?? '') }}"
            class="form-control @error('nama') is-invalid @enderror"
            placeholder="Masukkan nama lengkap siswa"
            required
        >

        @error('nama')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Kelas --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">
            Kelas
            <span class="text-danger">*</span>
        </label>

        <select
            name="kelas_id"
            class="form-select @error('kelas_id') is-invalid @enderror"
            required
        >
            <option value="">
                Pilih Kelas
            </option>

            @foreach($kelas as $item)
                <option
                    value="{{ $item->id }}"
                    @selected(
                        old(
                            'kelas_id',
                            $siswa->kelas_id ?? null
                        ) == $item->id
                    )
                >
                    {{ $item->nama }}
                    — {{ $item->tahunAjaran->nama }}
                </option>
            @endforeach

        </select>

        @error('kelas_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        @if($kelas->isEmpty())
            <div class="form-hint text-danger">
                Belum ada kelas aktif. Tambahkan kelas terlebih dahulu.
            </div>
        @endif
    </div>

</div>


<div class="row">

    {{-- NIS --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">
            NIS
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="nis"
            value="{{ old('nis', $siswa->nis ?? '') }}"
            class="form-control @error('nis') is-invalid @enderror"
            placeholder="Masukkan NIS"
            required
        >

        @error('nis')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- NISN --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">
            NISN
        </label>

        <input
            type="text"
            name="nisn"
            value="{{ old('nisn', $siswa->nisn ?? '') }}"
            class="form-control @error('nisn') is-invalid @enderror"
            placeholder="Masukkan NISN"
        >

        @error('nisn')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

</div>


<div class="row">

    {{-- Jenis Kelamin --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">
            Jenis Kelamin
        </label>

        <select
            name="jenis_kelamin"
            class="form-select @error('jenis_kelamin') is-invalid @enderror"
        >
            <option value="">
                Pilih Jenis Kelamin
            </option>

            <option
                value="L"
                @selected(
                    old(
                        'jenis_kelamin',
                        $siswa->jenis_kelamin ?? ''
                    ) === 'L'
                )
            >
                Laki-laki
            </option>

            <option
                value="P"
                @selected(
                    old(
                        'jenis_kelamin',
                        $siswa->jenis_kelamin ?? ''
                    ) === 'P'
                )
            >
                Perempuan
            </option>

        </select>

        @error('jenis_kelamin')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

</div>


{{-- Alamat --}}
<div class="mb-4">

    <label class="form-label">
        Alamat
    </label>

    <textarea
        name="alamat"
        class="form-control @error('alamat') is-invalid @enderror"
        rows="3"
        placeholder="Masukkan alamat siswa"
    >{{ old('alamat', $siswa->alamat ?? '') }}</textarea>

    @error('alamat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>


<hr class="my-4">

<div class="mb-4">
    <h3 class="card-title mb-1">
        Akun Login
    </h3>

    <div class="text-secondary">
        Akun ini digunakan siswa untuk masuk ke dalam sistem.
    </div>
</div>


<div class="row">

    {{-- Username --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Username
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="username"
            value="{{ old(
                'username',
                isset($siswa) ? $siswa->user->username : ''
            ) }}"
            class="form-control @error('username') is-invalid @enderror"
            placeholder="Masukkan username"
            required
        >

        @error('username')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Email --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Email
        </label>

        <input
            type="email"
            name="email"
            value="{{ old(
                'email',
                isset($siswa) ? $siswa->user->email : ''
            ) }}"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="Masukkan email"
        >

        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>


<div class="row">

    {{-- Password --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Password

            @if(!isset($siswa))
                <span class="text-danger">*</span>
            @endif

        </label>

        <input
            type="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="{{ isset($siswa)
                ? 'Kosongkan jika tidak ingin mengubah'
                : 'Minimal 8 karakter'
            }}"
            @required(!isset($siswa))
        >

        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        @isset($siswa)
            <div class="form-hint">
                Kosongkan password jika tidak ingin mengubahnya.
            </div>
        @endisset

    </div>


    {{-- Konfirmasi Password --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Konfirmasi Password

            @if(!isset($siswa))
                <span class="text-danger">*</span>
            @endif
        </label>

        <input
            type="password"
            name="password_confirmation"
            class="form-control"
            placeholder="Masukkan ulang password"
            @required(!isset($siswa))
        >

    </div>

</div>


{{-- Status --}}
<div class="mb-3">

    <label class="form-check">

        <input
            type="checkbox"
            name="is_active"
            value="1"
            class="form-check-input"
            @checked(
                old(
                    'is_active',
                    $siswa->is_active ?? true
                )
            )
        >

        <span class="form-check-label">
            Akun siswa aktif
        </span>

    </label>

    <div class="form-hint">
        Siswa dengan akun tidak aktif tidak dapat masuk ke sistem.
    </div>

</div>
