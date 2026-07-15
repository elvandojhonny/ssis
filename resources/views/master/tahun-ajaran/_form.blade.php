<div class="mb-3">
    <label class="form-label">
        Tahun Ajaran
    </label>

    <input
        type="text"
        name="nama"
        class="form-control @error('nama') is-invalid @enderror"
        value="{{ old('nama', $tahunAjaran->nama ?? '') }}"
        placeholder="Contoh: 2026/2027"
        required
    >

    @error('nama')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="row">

    <div class="col-md-6">

        <div class="mb-3">

            <label class="form-label">
                Tanggal Mulai
            </label>

            <input
                type="date"
                name="tanggal_mulai"
                class="form-control"
                value="{{ old(
                    'tanggal_mulai',
                    isset($tahunAjaran) && $tahunAjaran->tanggal_mulai
                        ? $tahunAjaran->tanggal_mulai->format('Y-m-d')
                        : ''
                ) }}"
            >

        </div>

    </div>

    <div class="col-md-6">

        <div class="mb-3">

            <label class="form-label">
                Tanggal Selesai
            </label>

            <input
                type="date"
                name="tanggal_selesai"
                class="form-control"
                value="{{ old(
                    'tanggal_selesai',
                    isset($tahunAjaran) && $tahunAjaran->tanggal_selesai
                        ? $tahunAjaran->tanggal_selesai->format('Y-m-d')
                        : ''
                ) }}"
            >

        </div>

    </div>

</div>

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
                    $tahunAjaran->is_active ?? false
                )
            )
        >

        <span class="form-check-label">
            Jadikan tahun ajaran aktif
        </span>

    </label>

</div>