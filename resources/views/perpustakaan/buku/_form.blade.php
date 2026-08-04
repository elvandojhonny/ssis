<div class="row">

    {{-- Kelas --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">
            Kelas <span class="text-danger">*</span>
        </label>

        <select
            name="kelas_id"
            class="form-select @error('kelas_id') is-invalid @enderror"
            required
        >
            <option value="">-- Pilih Kelas --</option>

            @foreach ($kelas as $item)
                <option
                    value="{{ $item->id }}"
                    {{ old('kelas_id', $buku->kelas_id ?? '') == $item->id ? 'selected' : '' }}
                >
                    {{ $item->tingkat }}
                </option>
            @endforeach

        </select>

        @error('kelas_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Nama Buku --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">
            Nama Buku <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="nama_buku"
            class="form-control @error('nama_buku') is-invalid @enderror"
            value="{{ old('nama_buku', $buku->nama_buku ?? '') }}"
            placeholder="Masukkan nama buku"
            required
        >

        @error('nama_buku')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Jumlah Buku --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">
            Jumlah Buku <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            min="1"
            name="jumlah"
            class="form-control @error('jumlah') is-invalid @enderror"
            value="{{ old('jumlah', $buku->jumlah ?? '') }}"
            required
        >

        @error('jumlah')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Jumlah Tersedia --}}
    @isset($buku)
    <div class="col-md-6 mb-3">
        <label class="form-label">
            Jumlah Tersedia
        </label>

        <input
            type="number"
            min="0"
            name="jumlah_tersedia"
            class="form-control @error('jumlah_tersedia') is-invalid @enderror"
            value="{{ old('jumlah_tersedia', $buku->jumlah_tersedia) }}"
        >

        @error('jumlah_tersedia')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    @endisset


    {{-- Status --}}
    @isset($buku)
    <div class="col-md-12 mb-3">

        <div class="form-check form-switch">

            <input
                class="form-check-input"
                type="checkbox"
                name="is_active"
                value="1"
                id="is_active"
                {{ old('is_active', $buku->is_active) ? 'checked' : '' }}
            >

            <label class="form-check-label" for="is_active">
                Buku Aktif
            </label>

        </div>

    </div>
    @endisset

</div>