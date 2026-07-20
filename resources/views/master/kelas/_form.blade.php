<div class="mb-3">

    <label class="form-label">
        Tahun Ajaran
        <span class="text-danger">*</span>
    </label>

    <select
        name="tahun_ajaran_id"
        class="form-select @error('tahun_ajaran_id') is-invalid @enderror"
        required
    >

        <option value="">
            Pilih Tahun Ajaran
        </option>

        @foreach($tahunAjarans as $tahun)

            <option
                value="{{ $tahun->id }}"
                @selected(
                    old(
                        'tahun_ajaran_id',
                        $kelas->tahun_ajaran_id ?? null
                    ) == $tahun->id
                )
            >

                {{ $tahun->nama }}

                @if($tahun->is_active)
                    (Aktif)
                @endif

            </option>

        @endforeach

    </select>

    @error('tahun_ajaran_id')

        <div class="invalid-feedback">
            {{ $message }}
        </div>

    @enderror

    <div class="form-hint">
        Pilih tahun ajaran tempat kelas ini digunakan.
    </div>

</div>


{{-- TINGKAT --}}
<div class="mb-3">

    <label class="form-label">
        Tingkat
        <span class="text-danger">*</span>
    </label>

    <select
        name="tingkat"
        class="form-select @error('tingkat') is-invalid @enderror"
        required
    >

        <option value="">
            Pilih Tingkat
        </option>

        @foreach(['X', 'XI', 'XII'] as $tingkat)

            <option
                value="{{ $tingkat }}"
                @selected(
                    old(
                        'tingkat',
                        $kelas->tingkat ?? ''
                    ) === $tingkat
                )
            >
                Tingkat {{ $tingkat }}
            </option>

        @endforeach

    </select>

    @error('tingkat')

        <div class="invalid-feedback">
            {{ $message }}
        </div>

    @enderror

    <div class="form-hint">
        Pilih tingkat kelas X, XI, atau XII.
    </div>

</div>


{{-- NAMA KELAS --}}
<div class="mb-3">

    <label class="form-label">
        Nama Kelas
        <span class="text-danger">*</span>
    </label>

    <input
        type="text"
        name="nama"
        class="form-control @error('nama') is-invalid @enderror"
        value="{{ old('nama', $kelas->nama ?? '') }}"
        placeholder="Contoh: X-1"
        maxlength="50"
        required
    >

    @error('nama')

        <div class="invalid-feedback">
            {{ $message }}
        </div>

    @enderror

    <div class="form-hint">
        Masukkan nama kelas sesuai penamaan sekolah,
        misalnya X-1, X-2, XI-1, atau XII-1.
    </div>

</div>


{{-- STATUS KELAS --}}
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
                    $kelas->is_active ?? true
                )
            )
        >

        <span class="form-check-label">
            Kelas aktif
        </span>

    </label>

    <div class="form-hint">
        Kelas aktif dapat digunakan untuk data siswa dan fitur sistem lainnya.
    </div>

</div>