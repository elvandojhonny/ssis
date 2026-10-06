<div class="row">

    {{-- ========================================================= --}}
    {{-- JENIS BUKU --}}
    {{-- ========================================================= --}}

    <div class="col-md-12 mb-4">

        <label class="form-label fw-semibold">
            Jenis Buku <span class="text-danger">*</span>
        </label>

        <div class="row g-2">

            {{-- KHUSUS KELAS --}}
            <div class="col-md-6">

                <label
                    class="form-selectgroup-item w-100"
                >

                    <input
                        type="radio"
                        name="jenis_buku"
                        value="kelas"
                        class="form-selectgroup-input"
                        id="jenisKelas"
                        {{ old(
                            'jenis_buku',
                            isset($buku)
                                ? ($buku->is_umum ? 'umum' : 'kelas')
                                : 'kelas'
                        ) === 'kelas'
                            ? 'checked'
                            : ''
                        }}
                    >

                    <span
                        class="
                            form-selectgroup-label
                            d-flex
                            align-items-center
                            gap-3
                            p-3
                            h-100
                        "
                    >

                        <span class="avatar bg-blue-lt text-blue">
                            <i class="ti ti-school"></i>
                        </span>

                        <span>

                            <span class="d-block fw-semibold">
                                Untuk Kelas Tertentu
                            </span>

                            <span class="text-secondary small">
                                Buku hanya dapat dipinjam siswa
                                dari kelas yang dipilih.
                            </span>

                        </span>

                    </span>

                </label>

            </div>


            {{-- UMUM --}}
            <div class="col-md-6">

                <label
                    class="form-selectgroup-item w-100"
                >

                    <input
                        type="radio"
                        name="jenis_buku"
                        value="umum"
                        class="form-selectgroup-input"
                        id="jenisUmum"
                        {{ old(
                            'jenis_buku',
                            isset($buku)
                                ? ($buku->is_umum ? 'umum' : 'kelas')
                                : 'kelas'
                        ) === 'umum'
                            ? 'checked'
                            : ''
                        }}
                    >

                    <span
                        class="
                            form-selectgroup-label
                            d-flex
                            align-items-center
                            gap-3
                            p-3
                            h-100
                        "
                    >

                        <span class="avatar bg-green-lt text-green">
                            <i class="ti ti-world"></i>
                        </span>

                        <span>

                            <span class="d-block fw-semibold">
                                Untuk Umum
                            </span>

                            <span class="text-secondary small">
                                Buku dapat dipinjam oleh siswa
                                kelas X, XI, maupun XII.
                            </span>

                        </span>

                    </span>

                </label>

            </div>

        </div>

        @error('jenis_buku')
            <div class="text-danger small mt-2">
                {{ $message }}
            </div>
        @enderror

    </div>



    {{-- ========================================================= --}}
    {{-- KELAS --}}
    {{-- ========================================================= --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Kelas Buku

            <span
                id="labelWajibKelas"
                class="text-danger"
            >
                *
            </span>

        </label>


        <select
            name="kelas_id"
            id="kelas_id"
            class="form-select @error('kelas_id') is-invalid @enderror"
        >

            <option value="">
                -- Pilih Kelas --
            </option>

            @foreach ($kelas as $item)

                <option
                    value="{{ $item->id }}"
                    {{ old(
                        'kelas_id',
                        $buku->kelas_id ?? ''
                    ) == $item->id
                        ? 'selected'
                        : ''
                    }}
                >
                    {{ $item->tingkat }}
                </option>

            @endforeach

        </select>


        <div
            id="infoKelasUmum"
            class="form-hint mt-2 d-none"
        >
            Buku umum dapat dipinjam oleh semua tingkat kelas.
        </div>


        @error('kelas_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>



    {{-- ========================================================= --}}
    {{-- NAMA BUKU --}}
    {{-- ========================================================= --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Nama Buku

            <span class="text-danger">*</span>

        </label>


        <input
            type="text"
            name="nama_buku"
            class="form-control @error('nama_buku') is-invalid @enderror"
            value="{{ old(
                'nama_buku',
                $buku->nama_buku ?? ''
            ) }}"
            placeholder="Masukkan nama buku"
            required
        >


        @error('nama_buku')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>



    {{-- ========================================================= --}}
    {{-- NAMA PENULIS --}}
    {{-- ========================================================= --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Nama Penulis
        </label>


        <input
            type="text"
            name="nama_penulis"
            class="form-control @error('nama_penulis') is-invalid @enderror"
            value="{{ old(
                'nama_penulis',
                $buku->nama_penulis ?? ''
            ) }}"
            placeholder="Masukkan nama penulis"
        >


        @error('nama_penulis')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>



    {{-- ========================================================= --}}
    {{-- TAHUN TERBIT --}}
    {{-- ========================================================= --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Tahun Terbit
        </label>


        <input
            type="number"
            name="tahun_terbit"
            class="form-control @error('tahun_terbit') is-invalid @enderror"
            value="{{ old(
                'tahun_terbit',
                $buku->tahun_terbit ?? ''
            ) }}"
            min="1000"
            max="{{ date('Y') }}"
            placeholder="Contoh: 2024"
        >


        @error('tahun_terbit')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>



    {{-- ========================================================= --}}
    {{-- JUMLAH BUKU --}}
    {{-- ========================================================= --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Jumlah Buku

            <span class="text-danger">*</span>

        </label>


        <input
            type="number"
            min="1"
            name="jumlah"
            class="form-control @error('jumlah') is-invalid @enderror"
            value="{{ old(
                'jumlah',
                $buku->jumlah ?? ''
            ) }}"
            placeholder="Masukkan jumlah buku"
            required
        >


        @error('jumlah')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>



    {{-- ========================================================= --}}
    {{-- JUMLAH TERSEDIA --}}
    {{-- ========================================================= --}}

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
                value="{{ old(
                    'jumlah_tersedia',
                    $buku->jumlah_tersedia
                ) }}"
            >


            @error('jumlah_tersedia')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

    @endisset



    {{-- ========================================================= --}}
    {{-- STATUS --}}
    {{-- ========================================================= --}}

    @isset($buku)

        <div class="col-md-12 mb-3">

            <div class="form-check form-switch">

                <input
                    class="form-check-input"
                    type="checkbox"
                    name="is_active"
                    value="1"
                    id="is_active"
                    {{ old(
                        'is_active',
                        $buku->is_active
                    )
                        ? 'checked'
                        : ''
                    }}
                >


                <label
                    class="form-check-label"
                    for="is_active"
                >
                    Buku Aktif
                </label>

            </div>

        </div>

    @endisset

</div>



{{-- ============================================================= --}}
{{-- STYLE --}}
{{-- ============================================================= --}}

<style>

    .form-selectgroup-item {
        margin: 0;
        display: block;
    }


    .form-selectgroup-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }


    .form-selectgroup-label {
        display: flex;
        border: 1px solid var(--tblr-border-color);
        border-radius: 8px;
        cursor: pointer;
        transition: all .15s ease;
        background: var(--tblr-bg-surface);
    }


    .form-selectgroup-label:hover {
        border-color: var(--tblr-primary);
        background: rgba(var(--tblr-primary-rgb), .03);
    }


    .form-selectgroup-input:checked
    + .form-selectgroup-label {
        border-color: var(--tblr-primary);
        background: rgba(var(--tblr-primary-rgb), .06);
        box-shadow:
            0 0 0 2px
            rgba(var(--tblr-primary-rgb), .08);
    }


    #kelas_id:disabled {
        background-color: var(--tblr-bg-surface-secondary);
        cursor: not-allowed;
    }

</style>



{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const radioKelas =
            document.getElementById(
                'jenisKelas'
            );


        const radioUmum =
            document.getElementById(
                'jenisUmum'
            );


        const selectKelas =
            document.getElementById(
                'kelas_id'
            );


        const infoKelasUmum =
            document.getElementById(
                'infoKelasUmum'
            );


        const labelWajibKelas =
            document.getElementById(
                'labelWajibKelas'
            );


        function updateJenisBuku()
        {

            if (
                radioUmum &&
                radioUmum.checked
            ) {

                /*
                 * Buku umum tidak memiliki
                 * kelas tertentu.
                 */

                selectKelas.value = '';

                selectKelas.disabled = true;

                selectKelas.removeAttribute(
                    'required'
                );


                labelWajibKelas
                    .classList
                    .add('d-none');


                infoKelasUmum
                    .classList
                    .remove('d-none');

            } else {

                /*
                 * Buku khusus kelas.
                 */

                selectKelas.disabled = false;

                selectKelas.setAttribute(
                    'required',
                    'required'
                );


                labelWajibKelas
                    .classList
                    .remove('d-none');


                infoKelasUmum
                    .classList
                    .add('d-none');

            }

        }


        if (radioKelas) {

            radioKelas.addEventListener(
                'change',
                updateJenisBuku
            );

        }


        if (radioUmum) {

            radioUmum.addEventListener(
                'change',
                updateJenisBuku
            );

        }


        updateJenisBuku();

    }

);

</script>