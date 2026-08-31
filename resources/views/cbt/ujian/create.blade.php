@extends('layouts.app')

@section('title', 'Buat Ujian')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Modul CBT
            </div>

            <h2 class="page-title">
                Buat Ujian
            </h2>

            <div class="text-secondary mt-1">
                Pilih bank soal dan tentukan pelaksanaan ujian.
            </div>

        </div>

        <div class="col-12 col-md-auto">

            <a
                href="{{ route('cbt.ujian.index') }}"
                class="btn btn-outline-secondary w-100"
            >
                <i class="ti ti-arrow-left me-1"></i>
                Kembali
            </a>

        </div>

    </div>

</div>


@if($errors->any())

    <div class="alert alert-danger">

        <div class="fw-bold mb-2">
            Data ujian belum dapat disimpan.
        </div>

        <ul class="mb-0">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


<form
    action="{{ route('cbt.ujian.store') }}"
    method="POST"
>

    @csrf


    <div class="row row-cards">


        {{-- ================================================= --}}
        {{-- BANK SOAL --}}
        {{-- ================================================= --}}

        <div class="col-lg-5">

            <div class="card h-100">

                <div class="card-header">

                    <h3 class="card-title">
                        Pilih Bank Soal
                    </h3>

                </div>


                <div class="card-body">

                    {{-- BANK SOAL --}}

<div class="mb-3">

    <label class="form-label required">
        Kode Bank Soal
    </label>

    <div class="input-group">

        <input
            type="text"
            id="kodeBankSoal"
            class="form-control"
            placeholder="Contoh: MTK-48291"
            autocomplete="off"
            style="text-transform: uppercase;"
        >

        <button
            type="button"
            id="btnCariBankSoal"
            class="btn btn-primary"
        >

            <i class="ti ti-search me-1"></i>

            Cari

        </button>

    </div>


    <div class="form-hint mt-2">

        Masukkan kode Bank Soal yang diberikan oleh guru.

    </div>


    {{-- ID BANK SOAL YANG AKAN DIKIRIM KE SERVER --}}

    <input
        type="hidden"
        name="bank_soal_id"
        id="bankSoalId"
        value="{{ old('bank_soal_id') }}"
        required
    >


    {{-- HASIL PENCARIAN --}}

    <div
        id="hasilBankSoal"
        class="mt-3"
        style="display: none;"
    >

        <div class="card border">

            <div class="card-body">

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-start
                        gap-3
                    "
                >

                    <div>

                        <div class="text-secondary small mb-1">

                            Bank Soal Ditemukan

                        </div>

                        <div
                            id="bankSoalJudul"
                            class="fw-bold"
                        ></div>

                        <div
                            id="bankSoalDetail"
                            class="text-secondary small mt-1"
                        ></div>

                    </div>


                    <span
                        class="badge bg-success-lt"
                    >

                        <i class="ti ti-circle-check me-1"></i>

                        Tersedia

                    </span>

                </div>


                <div class="row g-2 mt-3">

                    <div class="col-6">

                        <div class="text-secondary small">
                            Kode
                        </div>

                        <div
                            id="bankSoalKode"
                            class="fw-bold"
                        ></div>

                    </div>


                    <div class="col-6">

                        <div class="text-secondary small">
                            Jumlah Soal
                        </div>

                        <div
                            id="bankSoalJumlah"
                            class="fw-bold"
                        ></div>

                    </div>

                </div>


                <div class="mt-3">

                    <button
                        type="button"
                        id="btnGunakanBankSoal"
                        class="btn btn-success w-100"
                    >

                        <i class="ti ti-check me-1"></i>

                        Gunakan Bank Soal Ini

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- PESAN ERROR PENCARIAN --}}

    <div
        id="errorBankSoal"
        class="alert alert-danger mt-3 mb-0"
        style="display: none;"
    >

        <div class="d-flex">

            <i class="ti ti-alert-circle me-2"></i>

            <div id="errorBankSoalText"></div>

        </div>

    </div>


    {{-- BANK SOAL TERPILIH --}}

    <div
        id="bankSoalTerpilih"
        class="alert alert-primary mt-3 mb-0"
        style="display: none;"
    >

        <div class="d-flex align-items-start">

            <i class="ti ti-check me-2"></i>

            <div>

                <div class="fw-bold">
                    Bank Soal Terpilih
                </div>

                <div
                    id="bankSoalTerpilihNama"
                    class="small mt-1"
                ></div>

            </div>

        </div>

    </div>

</div>

{{-- ================================================= --}}
{{-- BANK SOAL TERBARU --}}
{{-- ================================================= --}}

<div class="mt-4">

    <div class="d-flex align-items-center justify-content-between mb-3">

        <div>
            <div class="fw-semibold">
                Bank Soal Terbaru
            </div>

            <div class="text-secondary small">
                Pilih langsung dari bank soal yang tersedia.
            </div>
        </div>

        @if($bankSoalTerbaru->count() > 0)

            <span class="badge bg-blue-lt">
                {{ $bankSoalTerbaru->count() }}
                tersedia
            </span>

        @endif

    </div>


    @forelse($bankSoalTerbaru as $bank)

        <div
            class="border rounded-3 p-3 mb-2 bank-soal-item"
            data-bank-id="{{ $bank->id }}"
        >

            <div class="d-flex align-items-start gap-3">

                {{-- ICON --}}

                <div
                    class="flex-shrink-0 bg-primary-lt rounded-circle d-flex align-items-center justify-content-center"
                    style="width:42px;height:42px;"
                >

                    <i class="ti ti-notebook text-primary"></i>

                </div>


                {{-- INFORMASI --}}

                <div class="flex-grow-1 min-width-0">

                    <div class="fw-semibold text-truncate">

                        {{ $bank->judul }}

                    </div>


                    <div class="text-secondary small mt-1">

                        {{ $bank->mata_pelajaran }}

                        <span class="mx-1">•</span>

                        Kelas {{ $bank->tingkat }}

                        @if($bank->tahunAjaran)

                            <span class="mx-1">•</span>

                            {{ $bank->tahunAjaran->nama }}

                        @endif

                    </div>


                    <div class="d-flex flex-wrap gap-2 mt-2">

                        <span class="badge bg-secondary-lt">

                            <i class="ti ti-list me-1"></i>

                            {{ $bank->soals_count }} soal

                        </span>


                        <span class="badge bg-azure-lt">

                            <i class="ti ti-key me-1"></i>

                            {{ $bank->kode }}

                        </span>


                        @if($bank->guru)

                            <span class="badge bg-green-lt">

                                <i class="ti ti-user me-1"></i>

                                {{ $bank->guru->nama }}

                            </span>

                        @endif

                    </div>

                </div>


                {{-- TOMBOL PILIH --}}

                <div class="flex-shrink-0">

                    <button
                        type="button"
                        class="btn btn-outline-primary btn-sm btn-pilih-bank-soal"
                        data-id="{{ $bank->id }}"
                        data-judul="{{ $bank->judul }}"
                        data-kode="{{ $bank->kode }}"
                    >

                        <i class="ti ti-check me-1"></i>

                        Pilih

                    </button>

                </div>

            </div>

        </div>

    @empty

        <div class="text-center py-4 text-secondary">

            <i
                class="ti ti-notebook-off"
                style="font-size:32px;"
            ></i>

            <div class="mt-2 fw-semibold">
                Belum ada Bank Soal
            </div>

            <div class="small">
                Bank Soal yang sudah siap akan muncul di sini.
            </div>

        </div>

    @endforelse

</div>


                    {{-- KELAS PESERTA --}}

<div class="mb-3">

    <label class="form-label required">
        Kelas Peserta
    </label>

    <select
        name="kelas_id"
        class="form-select @error('kelas_id') is-invalid @enderror"
        required
    >

        <option value="">
            Pilih kelas
        </option>

        @foreach(['X', 'XI', 'XII'] as $tingkat)

            @php
                $kelasTingkat = $kelas->where('tingkat', $tingkat);
            @endphp

            @if($kelasTingkat->isNotEmpty())

                <optgroup label="Tingkat {{ $tingkat }}">

                    @foreach($kelasTingkat as $item)

                        <option
                            value="{{ $item->id }}"
                            @selected(
                                old('kelas_id') == $item->id
                            )
                        >
                            {{ $item->nama }}
                            — {{ $item->tahunAjaran->nama }}
                        </option>

                    @endforeach

                </optgroup>

            @endif

        @endforeach

    </select>

    @error('kelas_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

    <div class="form-hint">
        Pilih kelas yang akan mengikuti ujian.
    </div>

</div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- PENGATURAN UJIAN --}}
        {{-- ================================================= --}}

        <div class="col-lg-7">

            <div class="card">

                <div class="card-header">

                    <h3 class="card-title">
                        Pengaturan Ujian
                    </h3>

                </div>


                <div class="card-body">

                    {{-- JUDUL --}}

                    <div class="mb-3">

                        <label class="form-label required">
                            Judul Ujian
                        </label>

                        <input
                            type="text"
                            name="judul"
                            class="form-control"
                            value="{{ old('judul') }}"
                            placeholder="Contoh: Ujian Tengah Semester Matematika"
                            required
                        >

                    </div>


                    {{-- DESKRIPSI --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Deskripsi
                        </label>

                        <textarea
                            name="deskripsi"
                            class="form-control"
                            rows="3"
                        >{{ old('deskripsi') }}</textarea>

                    </div>


                    {{-- JADWAL --}}

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label required">
                                Waktu Mulai
                            </label>

                            <input
                                type="datetime-local"
                                name="waktu_mulai"
                                class="form-control"
                                value="{{ old('waktu_mulai') }}"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label required">
                                Waktu Selesai
                            </label>

                            <input
                                type="datetime-local"
                                name="waktu_selesai"
                                class="form-control"
                                value="{{ old('waktu_selesai') }}"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label required">
                                Durasi Pengerjaan
                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    name="durasi_menit"
                                    class="form-control"
                                    value="{{
                                        old(
                                            'durasi_menit',
                                            90
                                        )
                                    }}"
                                    min="1"
                                    max="600"
                                    required
                                >

                                <span class="input-group-text">
                                    menit
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- PENGACAKAN SOAL --}}
                    {{-- ================================================= --}}

                    <hr class="my-4">


                    <div class="mb-3">

                        <div class="d-flex align-items-center gap-2 mb-1">

                            <i class="ti ti-arrows-shuffle text-primary"></i>

                            <label class="form-label mb-0">
                                Pengacakan Ujian
                            </label>

                        </div>

                        <div class="text-secondary small">

                            Atur pengacakan soal
                            untuk setiap peserta ujian.

                        </div>

                    </div>


                    {{-- ACAK SOAL --}}

                    <div class="border rounded p-3 mb-3">

                        <div
                            class="
                                d-flex
                                justify-content-between
                                align-items-center
                                gap-3
                            "
                        >

                            <div>

                                <div class="fw-bold">
                                    Acak Urutan Soal
                                </div>

                                <div class="text-secondary small mt-1">

                                    Setiap siswa mendapatkan urutan
                                    nomor soal yang berbeda.

                                </div>

                            </div>


                            <label class="form-check form-switch m-0">

                                <input
                                    type="hidden"
                                    name="acak_soal"
                                    value="0"
                                >

                                <input
                                    type="checkbox"
                                    name="acak_soal"
                                    value="1"
                                    class="form-check-input"
                                    @checked(
                                        old(
                                            'acak_soal',
                                            1
                                        )
                                    )
                                >

                            </label>

                        </div>

                    </div>


                    <div class="alert alert-info mt-3 mb-0">

                        <div class="d-flex">

                            <div class="me-2">

                                <i class="ti ti-info-circle"></i>

                            </div>

                            <div>

                                Urutan soal dapat berbeda untuk setiap
                                peserta. Setelah ujian dimulai, urutan
                                soal setiap peserta akan tetap sama
                                selama pengerjaan berlangsung.

                            </div>

                        </div>

                    </div>

                </div>


                {{-- FOOTER --}}

                <div class="card-footer">

                    <div
                        class="
                            d-flex
                            flex-column
                            flex-md-row
                            justify-content-between
                            align-items-md-center
                            gap-3
                        "
                    >

                        <div class="text-secondary small">

                            <i class="ti ti-info-circle me-1"></i>

                            Ujian akan disimpan sebagai draft
                            dan belum terlihat oleh siswa.

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="ti ti-device-floppy me-1"></i>

                            Simpan Draft Ujian

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</form>

@push('scripts')

<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        const kodeInput =
            document.getElementById(
                'kodeBankSoal'
            );

        const btnCari =
            document.getElementById(
                'btnCariBankSoal'
            );

        const bankSoalId =
            document.getElementById(
                'bankSoalId'
            );

        const hasil =
            document.getElementById(
                'hasilBankSoal'
            );

        const errorBox =
            document.getElementById(
                'errorBankSoal'
            );

        const errorText =
            document.getElementById(
                'errorBankSoalText'
            );

        const btnGunakan =
            document.getElementById(
                'btnGunakanBankSoal'
            );

        const terpilih =
            document.getElementById(
                'bankSoalTerpilih'
            );

        const terpilihNama =
            document.getElementById(
                'bankSoalTerpilihNama'
            );

            /*
|--------------------------------------------------------------------------
| Pilih Bank Soal dari Daftar Terbaru
|--------------------------------------------------------------------------
*/

const tombolPilihBank =
    document.querySelectorAll(
        '.btn-pilih-bank-soal'
    );


tombolPilihBank.forEach(
    function (button) {

        button.addEventListener(
            'click',
            function () {

                const id =
                    this.dataset.id;

                const judul =
                    this.dataset.judul;

                const kode =
                    this.dataset.kode;


                /*
                 * Simpan ID Bank Soal
                 */

                bankSoalId.value =
                    id;


                /*
                 * Tampilkan sebagai
                 * Bank Soal terpilih.
                 */

                terpilihNama.textContent =
                    judul
                    + ' — '
                    + kode;


                terpilih.style.display =
                    'block';


                /*
                 * Hilangkan hasil pencarian
                 * jika sedang terbuka.
                 */

                hasil.style.display =
                    'none';


                errorBox.style.display =
                    'none';


                /*
                 * Tandai pilihan aktif.
                 */

                document
                    .querySelectorAll(
                        '.bank-soal-item'
                    )
                    .forEach(
                        function (item) {

                            item.classList.remove(
                                'border-primary',
                                'bg-primary-lt'
                            );

                        }
                    );


                const item =
                    document.querySelector(
                        '.bank-soal-item[data-bank-id="'
                        + id
                        + '"]'
                    );


                if (item) {

                    item.classList.add(
                        'border-primary',
                        'bg-primary-lt'
                    );

                }

            }
        );

    }
);


        /*
        |--------------------------------------------------------------------------
        | Fungsi Reset
        |--------------------------------------------------------------------------
        */

        function resetHasil()
        {
            hasil.style.display =
                'none';

            errorBox.style.display =
                'none';

            terpilih.style.display =
                'none';

            bankSoalId.value =
                '';
        }


        /*
        |--------------------------------------------------------------------------
        | Cari Bank Soal
        |--------------------------------------------------------------------------
        */

        async function cariBankSoal()
        {

            const kode =
                kodeInput.value
                    .trim()
                    .toUpperCase();


            if (! kode) {

                resetHasil();

                errorText.textContent =
                    'Masukkan kode Bank Soal terlebih dahulu.';

                errorBox.style.display =
                    'block';

                kodeInput.focus();

                return;
            }


            /*
             * Bersihkan tampilan sebelumnya.
             */

            hasil.style.display =
                'none';

            errorBox.style.display =
                'none';

            terpilih.style.display =
                'none';

            bankSoalId.value =
                '';


            /*
             * Loading.
             */

            btnCari.disabled =
                true;

            btnCari.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1"></span> Mencari...';


            try {

                const response =
                    await fetch(
                        '{{ route("cbt.ujian.cari-bank-soal") }}?kode='
                        + encodeURIComponent(kode),
                        {
                            method: 'GET',

                            headers: {
                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            }
                        }
                    );


                const data =
                    await response.json();


                if (! response.ok || ! data.success) {

                    throw new Error(
                        data.message
                        ||
                        'Bank soal tidak ditemukan.'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Tampilkan hasil
                |--------------------------------------------------------------------------
                */

                document.getElementById(
                    'bankSoalJudul'
                ).textContent =
                    data.data.judul;


                document.getElementById(
                    'bankSoalDetail'
                ).textContent =
                    data.data.mata_pelajaran
                    + ' • Kelas '
                    + data.data.tingkat
                    + (
                        data.data.tahun_ajaran
                            ? ' • '
                            + data.data.tahun_ajaran
                            : ''
                    );


                document.getElementById(
                    'bankSoalKode'
                ).textContent =
                    data.data.kode;


                document.getElementById(
                    'bankSoalJumlah'
                ).textContent =
                    data.data.jumlah_soal
                    + ' soal';


                /*
                 * Simpan sementara ID.
                 */

                bankSoalId.value =
                    data.data.id;


                hasil.style.display =
                    'block';

            } catch (error) {

                errorText.textContent =
                    error.message;

                errorBox.style.display =
                    'block';

            } finally {

                btnCari.disabled =
                    false;

                btnCari.innerHTML =
                    '<i class="ti ti-search me-1"></i> Cari';

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Tombol Cari
        |--------------------------------------------------------------------------
        */

        btnCari.addEventListener(
            'click',
            cariBankSoal
        );


        /*
        |--------------------------------------------------------------------------
        | Enter pada Input
        |--------------------------------------------------------------------------
        */

        kodeInput.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Enter'
                ) {

                    event.preventDefault();

                    cariBankSoal();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Gunakan Bank Soal
        |--------------------------------------------------------------------------
        */

        btnGunakan.addEventListener(
            'click',
            function () {

                if (! bankSoalId.value) {

                    return;

                }


                const judul =
                    document.getElementById(
                        'bankSoalJudul'
                    ).textContent;


                const kode =
                    document.getElementById(
                        'bankSoalKode'
                    ).textContent;


                terpilihNama.textContent =
                    judul
                    + ' — '
                    + kode;


                terpilih.style.display =
                    'block';


                hasil.style.display =
                    'none';

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Jika validasi Laravel gagal
        |--------------------------------------------------------------------------
        */

        const oldBankSoalId =
            bankSoalId.value;


        /*
         * Tidak melakukan pencarian ulang
         * berdasarkan ID di sini.
         *
         * ID hanya dipertahankan agar
         * validasi form tidak kehilangan data.
         */

    }
);
</script>

@endpush

@endsection