@extends('layouts.app')

@section('title', 'Edit Ujian')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Modul CBT
            </div>

            <h2 class="page-title">
                Edit Ujian
            </h2>

            <div class="text-secondary mt-1">
                Ubah pengaturan ujian sebelum dipublikasikan.
            </div>

        </div>

        <div class="col-12 col-md-auto">

            <a
                href="{{ route('cbt.ujian.show', $ujian) }}"
                class="btn btn-outline-secondary w-100"
            >
                <i class="ti ti-arrow-left me-1"></i>
                Kembali
            </a>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- ERROR VALIDASI --}}
{{-- ========================================================= --}}

@if($errors->any())

    <div class="alert alert-danger">

        <div class="fw-bold mb-2">
            Data ujian belum dapat diperbarui.
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


{{-- ========================================================= --}}
{{-- INFORMASI DRAFT --}}
{{-- ========================================================= --}}

<div class="alert alert-info">

    <div class="d-flex">

        <div class="me-2">

            <i class="ti ti-info-circle"></i>

        </div>

        <div>

            <div class="fw-bold">
                Ujian masih berstatus draft
            </div>

            <div class="mt-1">

                Seluruh pengaturan ujian masih dapat diubah.
                Setelah ujian dipublikasikan, pengaturan ujian
                tidak dapat diedit kembali.

            </div>

        </div>

    </div>

</div>


<form
    action="{{ route('cbt.ujian.update', $ujian) }}"
    method="POST"
>

    @csrf
    @method('PUT')


    <div class="row row-cards">


        {{-- ================================================= --}}
        {{-- BANK SOAL DAN KELAS --}}
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
        Bank Soal
    </label>


    {{-- Bank Soal yang sedang digunakan --}}

    <div
        id="bankSoalSaatIni"
        class="card border mb-3"
    >

        <div class="card-body">

            <div class="d-flex align-items-start gap-3">

                <span class="avatar bg-blue-lt">

                    <i class="ti ti-file-text"></i>

                </span>


                <div class="flex-fill">

                    <div class="text-secondary small">
                        Bank Soal Saat Ini
                    </div>

                    <div
                        id="bankSoalSaatIniJudul"
                        class="fw-bold mt-1"
                    >

                        {{ $ujian->bankSoal->judul }}

                    </div>


                    <div
                        id="bankSoalSaatIniDetail"
                        class="text-secondary small mt-1"
                    >

                        {{ $ujian->bankSoal->mata_pelajaran }}

                        <span class="mx-1">
                            •
                        </span>

                        Kelas {{ $ujian->bankSoal->tingkat }}

                        @if($ujian->bankSoal->kode)

                            <span class="mx-1">
                                •
                            </span>

                            <strong>
                                {{ $ujian->bankSoal->kode }}
                            </strong>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ID Bank Soal --}}

    <input
        type="hidden"
        name="bank_soal_id"
        id="bankSoalId"
        value="{{ old('bank_soal_id', $ujian->bank_soal_id) }}"
        required
    >


    {{-- PENCARIAN BANK SOAL BARU --}}

    <div class="mt-3">

        <label class="form-label">

            Ganti Bank Soal

            <span class="text-secondary">
                (opsional)
            </span>

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

            Masukkan kode Bank Soal baru jika ingin
            mengganti soal yang digunakan.

        </div>

    </div>


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


                    <span class="badge bg-success-lt">

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


    {{-- ERROR --}}

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


    {{-- BANK SOAL BARU TERPILIH --}}

    <div
        id="bankSoalTerpilih"
        class="alert alert-primary mt-3 mb-0"
        style="display: none;"
    >

        <div class="d-flex align-items-start">

            <i class="ti ti-check me-2"></i>

            <div>

                <div class="fw-bold">
                    Bank Soal Baru Terpilih
                </div>

                <div
                    id="bankSoalTerpilihNama"
                    class="small mt-1"
                ></div>

            </div>

        </div>

    </div>


    <div class="form-hint mt-2">

        Hanya Bank Soal dari tahun ajaran aktif,
        berstatus siap, dan tidak diarsipkan yang dapat digunakan.

    </div>

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
                                old(
                                    'kelas_id',
                                    $ujian->kelas_id
                                ) == $item->id
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


                    {{-- INFORMASI --}}

                    <div class="alert alert-warning mb-0">

                        <div class="d-flex">

                            <div class="me-2">

                                <i class="ti ti-alert-triangle"></i>

                            </div>

                            <div>

                                Mengganti bank soal akan mengubah
                                soal yang digunakan pada ujian ini.

                            </div>

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

                            value="{{
                                old(
                                    'judul',
                                    $ujian->judul
                                )
                            }}"

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
                        >{{ old(
                            'deskripsi',
                            $ujian->deskripsi
                        ) }}</textarea>

                    </div>



                    {{-- ================================================= --}}
                    {{-- WAKTU UJIAN --}}
                    {{-- ================================================= --}}

                    <div class="row g-3">


                        {{-- WAKTU MULAI --}}

                        <div class="col-md-6">

                            <label class="form-label required">
                                Waktu Mulai
                            </label>

                            <input
                                type="datetime-local"
                                name="waktu_mulai"
                                class="form-control"

                                value="{{
                                    old(
                                        'waktu_mulai',
                                        $ujian
                                            ->waktu_mulai
                                            ?->format(
                                                'Y-m-d\TH:i'
                                            )
                                    )
                                }}"

                                required
                            >

                        </div>


                        {{-- WAKTU SELESAI --}}

                        <div class="col-md-6">

                            <label class="form-label required">
                                Waktu Selesai
                            </label>

                            <input
                                type="datetime-local"
                                name="waktu_selesai"
                                class="form-control"

                                value="{{
                                    old(
                                        'waktu_selesai',
                                        $ujian
                                            ->waktu_selesai
                                            ?->format(
                                                'Y-m-d\TH:i'
                                            )
                                    )
                                }}"

                                required
                            >

                        </div>


                        {{-- DURASI --}}

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
                                            $ujian->durasi_menit
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
                    {{-- PENGACAKAN --}}
                    {{-- ================================================= --}}

                    <hr class="my-4">


                    <div class="mb-3">

                        <div
                            class="
                                d-flex
                                align-items-center
                                gap-2
                                mb-1
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-arrows-shuffle
                                    text-primary
                                "
                            ></i>

                            <label class="form-label mb-0">
                                Pengacakan Ujian
                            </label>

                        </div>


                        <div class="text-secondary small">

                            Atur pengacakan soal
                            jawaban untuk setiap peserta ujian.

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- ACAK SOAL --}}
                    {{-- ================================================= --}}

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

                                <div
                                    class="
                                        text-secondary
                                        small
                                        mt-1
                                    "
                                >

                                    Setiap siswa mendapatkan
                                    urutan nomor soal yang berbeda.

                                </div>

                            </div>


                            <label
                                class="
                                    form-check
                                    form-switch
                                    m-0
                                "
                            >

                                {{-- Nilai jika switch mati --}}
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
                                        (bool)
                                        old(
                                            'acak_soal',
                                            $ujian->acak_soal
                                        )
                                    )
                                >

                            </label>

                        </div>

                    </div>



                    {{-- INFORMASI PENGACAKAN --}}

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



                {{-- ================================================= --}}
                {{-- FOOTER --}}
                {{-- ================================================= --}}

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

                            Perubahan hanya dapat dilakukan
                            selama ujian masih berstatus draft.

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i
                                class="
                                    ti
                                    ti-device-floppy
                                    me-1
                                "
                            ></i>

                            Simpan Perubahan

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
        | Cari Bank Soal
        |--------------------------------------------------------------------------
        */

        async function cariBankSoal()
        {

            const kode =
                kodeInput.value
                    .trim()
                    .toUpperCase();


            if (!kode) {

                errorText.textContent =
                    'Masukkan kode Bank Soal terlebih dahulu.';

                errorBox.style.display =
                    'block';

                hasil.style.display =
                    'none';

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Reset tampilan
            |--------------------------------------------------------------------------
            */

            errorBox.style.display =
                'none';

            hasil.style.display =
                'none';

            terpilih.style.display =
                'none';


            /*
            |--------------------------------------------------------------------------
            | Loading
            |--------------------------------------------------------------------------
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


                if (
                    !response.ok ||
                    !data.success
                ) {

                    throw new Error(
                        data.message
                        ||
                        'Bank Soal tidak ditemukan.'
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
                |--------------------------------------------------------------------------
                | Simpan ID sementara
                |--------------------------------------------------------------------------
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
        | Enter pada input
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
        | Gunakan Bank Soal Baru
        |--------------------------------------------------------------------------
        */

        btnGunakan.addEventListener(
            'click',
            function () {

                if (!bankSoalId.value) {
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


                /*
                |--------------------------------------------------------------------------
                | Informasi bank soal saat ini
                |--------------------------------------------------------------------------
                */

                const currentBox =
                    document.getElementById(
                        'bankSoalSaatIni'
                    );

                if (currentBox) {

                    currentBox.style.display =
                        'none';

                }

            }

        );

    }
);
</script>

@endpush

@endsection