<div class="mb-3">
    <label class="form-label">
        Tahun Ajaran
    </label>

    <select
        name="tahun_ajaran_id"
        class="form-select @error('tahun_ajaran_id') is-invalid @enderror"
        required
    >
        <option value="">Pilih Tahun Ajaran</option>

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
                {{ $tahun->is_active ? '(Aktif)' : '' }}
            </option>
        @endforeach
    </select>

    @error('tahun_ajaran_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">
        Tingkat
    </label>

    <select
        name="tingkat"
        class="form-select @error('tingkat') is-invalid @enderror"
        required
    >
        <option value="">Pilih Tingkat</option>

        @foreach(['X', 'XI', 'XII'] as $tingkat)
            <option
                value="{{ $tingkat }}"
                @selected(
                    old('tingkat', $kelas->tingkat ?? '') === $tingkat
                )
            >
                {{ $tingkat }}
            </option>
        @endforeach
    </select>

    @error('tingkat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">
        Nama Kelas
    </label>

    <input
        type="text"
        name="nama"
        class="form-control @error('nama') is-invalid @enderror"
        value="{{ old('nama', $kelas->nama ?? '') }}"
        placeholder="Contoh: X IPA 1"
        required
    >

    @error('nama')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-check">
        <input
            type="checkbox"
            name="is_active"
            value="1"
            class="form-check-input"
            @checked(
                old('is_active', $kelas->is_active ?? true)
            )
        >

        <span class="form-check-label">
            Kelas aktif
        </span>
    </label>
</div>