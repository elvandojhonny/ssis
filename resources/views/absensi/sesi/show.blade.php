@extends('layouts.app')

@section('title', 'Detail Sesi Absensi')

@section('content')

<div class="page-header d-print-none mb-4">
    <div class="row align-items-center g-3">

        <div class="col">
            <div class="page-pretitle">
                {{ $sesi->kelas->nama }}
            </div>

            <h2 class="page-title">
                Absensi {{ ucfirst($sesi->jenis) }}
            </h2>

            <div class="text-secondary mt-1">
                {{ $sesi->tanggal->format('d/m/Y') }}
            </div>
        </div>

        <div class="col-auto">
            <div class="d-flex gap-2">

                <a
                    href="{{ route('absensi.sesi.index') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="ti ti-arrow-left me-1"></i>
                    Kembali
                </a>

                @if($sesi->status === 'aktif')

                    <form
                        action="{{ route('absensi.sesi.tutup', $sesi) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="btn btn-danger"
                            onclick="
                                return confirm(
                                    'Tutup sesi absensi ini? Siswa yang belum absen akan otomatis menjadi alpa.'
                                )
                            "
                        >
                            <i class="ti ti-lock me-1"></i>
                            Tutup Sesi
                        </button>
                    </form>

                @endif

            </div>
        </div>

    </div>
</div>


{{-- ALERT --}}
@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger">
        {{ session('error') }}
    </div>

@endif


{{-- ========================================================= --}}
{{-- SCANNER QR SISWA --}}
{{-- ========================================================= --}}

@if($sesi->status === 'aktif')

<div class="card mb-4">

    <div class="card-header">

        <div>
            <h3 class="card-title">
                <i class="ti ti-scan me-2"></i>
                Scanner Absensi Siswa
            </h3>

            <div class="text-secondary small mt-1">
                Scan QR permanen milik siswa untuk mencatat kehadiran.
            </div>
        </div>

    </div>

    <div class="card-body">

        <div class="row g-4">

            {{-- CAMERA --}}
            <div class="col-lg-7">

                <div class="scanner-wrapper">

                    <div
                        id="reader"
                        class="scanner-reader"
                    ></div>

                </div>

                <div class="text-secondary small mt-2">
                    <i class="ti ti-camera me-1"></i>
                    Izinkan akses kamera ketika diminta oleh browser.
                </div>

            </div>


            {{-- HASIL SCAN --}}
            <div class="col-lg-5">

                <div
                    id="scan-result"
                    class="alert alert-info"
                >
                    <div class="d-flex align-items-center">

                        <i class="ti ti-scan me-2"></i>

                        <span>
                            Scanner siap digunakan.
                        </span>

                    </div>
                </div>


                <div class="card bg-light">

                    <div class="card-body">

                        <div class="text-secondary small mb-2">
                            Hasil Scan Terakhir
                        </div>

                        <div class="d-flex align-items-center">

                            <div
                                id="last-student-avatar"
                                class="
                                    avatar
                                    avatar-lg
                                    me-3
                                    bg-primary-lt
                                "
                            >
                                -
                            </div>

                            <div>

                                <h3
                                    id="last-student-name"
                                    class="mb-1"
                                >
                                    Belum ada siswa
                                </h3>

                                <div
                                    id="last-student-nis"
                                    class="text-secondary"
                                >
                                    -
                                </div>

                            </div>

                        </div>


                        <div class="mt-4">

                            <div class="text-secondary small mb-2">
                                Status Kehadiran
                            </div>

                            <span
                                id="last-student-status"
                                class="badge bg-secondary-lt"
                            >
                                Belum ada scan
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@else

<div class="alert alert-secondary mb-4">

    <div class="d-flex align-items-center">

        <i class="ti ti-lock me-2"></i>

        <div>
            <strong>Sesi telah ditutup.</strong>

            Scanner tidak dapat digunakan lagi pada sesi ini.
        </div>

    </div>

</div>

@endif



{{-- ========================================================= --}}
{{-- INFORMASI DAN STATISTIK --}}
{{-- ========================================================= --}}

<div class="row row-cards mb-4">

    {{-- INFORMASI SESI --}}
    <div class="col-lg-5">

        <div class="card h-100">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="ti ti-info-circle me-2"></i>
                    Informasi Sesi
                </h3>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Kelas
                        </div>

                        <div class="fw-bold mt-1">
                            {{ $sesi->kelas->nama }}
                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Tahun Ajaran
                        </div>

                        <div class="fw-bold mt-1">
                            {{ $sesi->kelas->tahunAjaran->nama }}
                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Jenis Absensi
                        </div>

                        <div class="fw-bold mt-1">
                            {{ ucfirst($sesi->jenis) }}
                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Status Sesi
                        </div>

                        <div class="mt-1">

                            @if($sesi->status === 'aktif')

                                <span class="badge bg-success-lt">
                                    Aktif
                                </span>

                            @else

                                <span class="badge bg-secondary-lt">
                                    Selesai
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Tanggal
                        </div>

                        <div class="fw-bold mt-1">
                            {{ $sesi->tanggal->format('d/m/Y') }}
                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Waktu Absensi
                        </div>

                        <div class="fw-bold mt-1">

                            {{ $sesi->waktu_mulai }}

                            <span class="text-secondary">
                                -
                            </span>

                            {{ $sesi->waktu_selesai }}

                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Batas Terlambat
                        </div>

                        <div class="fw-bold mt-1">
                            {{ $sesi->batas_terlambat ?? '-' }}
                        </div>

                    </div>


                    <div class="col-sm-6">

                        <div class="text-secondary small">
                            Dibuka Oleh
                        </div>

                        <div class="fw-bold mt-1">
                            {{ $sesi->pembuka->name ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- STATISTIK --}}
    <div class="col-lg-7">

        <div class="card h-100">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="ti ti-chart-bar me-2"></i>
                    Statistik Kehadiran
                </h3>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- TOTAL --}}
                    <div class="col-sm-6 col-xl-3">

                        <div class="stat-card">

                            <div class="text-secondary small">
                                Total Siswa
                            </div>

                            <div
                                id="stat-total"
                                class="stat-number"
                            >
                                {{ $totalSiswa }}
                            </div>

                            <div class="text-secondary small">
                                siswa
                            </div>

                        </div>

                    </div>


                    {{-- HADIR --}}
                    <div class="col-sm-6 col-xl-3">

                        <div class="stat-card">

                            <div class="text-secondary small">
                                Hadir
                            </div>

                            <div
                                id="stat-hadir"
                                class="stat-number text-success"
                            >
                                {{ $hadir }}
                            </div>

                            <div class="text-secondary small">
                                siswa
                            </div>

                        </div>

                    </div>


                    {{-- TERLAMBAT --}}
                    <div class="col-sm-6 col-xl-3">

                        <div class="stat-card">

                            <div class="text-secondary small">
                                Terlambat
                            </div>

                            <div
                                id="stat-terlambat"
                                class="stat-number text-warning"
                            >
                                {{ $terlambat }}
                            </div>

                            <div class="text-secondary small">
                                siswa
                            </div>

                        </div>

                    </div>


                    {{-- BELUM --}}
                    <div class="col-sm-6 col-xl-3">

                        <div class="stat-card">

                            <div class="text-secondary small">
                                Belum Absen
                            </div>

                            <div
                                id="stat-belum"
                                class="stat-number text-secondary"
                            >
                                {{ $belumAbsen }}
                            </div>

                            <div class="text-secondary small">
                                siswa
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- DAFTAR KEHADIRAN --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-header">

        <div>

            <h3 class="card-title">
                <i class="ti ti-users me-2"></i>
                Daftar Kehadiran Siswa
            </h3>

            <div class="text-secondary small mt-1">
                Seluruh siswa aktif yang terdaftar pada kelas ini.
            </div>

        </div>

    </div>


    <div class="table-responsive">

        <table class="table table-vcenter card-table">

            <thead>

                <tr>
                    <th>Siswa</th>
                    <th>NIS</th>
                    <th>Waktu Absen</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th class="text-end">
                        Aksi
                    </th>
                </tr>

            </thead>


            <tbody>

            @forelse($daftarSiswa as $siswa)

                @php
                    $absensi = $siswa->data_absensi;
                @endphp

                <tr id="siswa-row-{{ $siswa->id }}">

                    {{-- SISWA --}}
                    <td>

                        <div class="d-flex align-items-center">

                            <span
                                class="
                                    avatar
                                    avatar-sm
                                    me-2
                                    bg-primary-lt
                                "
                            >
                                {{
                                    strtoupper(
                                        substr(
                                            $siswa->user->name ?? '?',
                                            0,
                                            1
                                        )
                                    )
                                }}
                            </span>

                            <div class="fw-bold">
                                {{ $siswa->user->name ?? '-' }}
                            </div>

                        </div>

                    </td>


                    {{-- NIS --}}
                    <td>
                        {{ $siswa->nis }}
                    </td>


                    {{-- WAKTU --}}
                    <td class="absensi-waktu">

                        {{
                            $absensi?->waktu_absen?->format('H:i:s')
                            ?? '-'
                        }}

                    </td>


                    {{-- METODE --}}
                    <td class="absensi-metode">

                        @if($absensi)

                            <span class="badge bg-secondary-lt">

                                {{ strtoupper($absensi->metode) }}

                            </span>

                        @else

                            <span class="text-secondary">
                                -
                            </span>

                        @endif

                    </td>


                    {{-- STATUS --}}
                    <td class="absensi-status">

                        @if(!$absensi)

                            <span class="badge bg-secondary-lt">
                                Belum Absen
                            </span>

                        @elseif($absensi->status === 'hadir')

                            <span class="badge bg-success-lt">
                                Hadir
                            </span>

                        @elseif($absensi->status === 'terlambat')

                            <span class="badge bg-warning-lt">
                                Terlambat
                            </span>

                        @elseif($absensi->status === 'izin')

                            <span class="badge bg-blue-lt">
                                Izin
                            </span>

                        @elseif($absensi->status === 'sakit')

                            <span class="badge bg-azure-lt">
                                Sakit
                            </span>

                        @elseif($absensi->status === 'alpa')

                            <span class="badge bg-danger-lt">
                                Alpa
                            </span>

                        @endif

                    </td>


                    {{-- KETERANGAN --}}
                    <td class="absensi-keterangan">

                        {{ $absensi?->keterangan ?? '-' }}

                    </td>


                    {{-- AKSI --}}
                    <td class="text-end">

                        @if($sesi->status === 'aktif')

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#statusModal{{ $siswa->id }}"
                            >
                                <i class="ti ti-edit me-1"></i>
                                Ubah Status
                            </button>

                        @else

                            <span class="text-secondary small">

                                <i class="ti ti-lock me-1"></i>

                                Sesi ditutup

                            </span>

                        @endif

                    </td>

                </tr>


                {{-- MODAL EDIT STATUS --}}
                @if($sesi->status === 'aktif')

                <div
                    class="modal modal-blur fade"
                    id="statusModal{{ $siswa->id }}"
                    tabindex="-1"
                    aria-hidden="true"
                >

                    <div
                        class="
                            modal-dialog
                            modal-dialog-centered
                        "
                        role="document"
                    >

                        <div class="modal-content">

                            <form
                                action="{{
                                    route(
                                        'absensi.sesi.status.update',
                                        [
                                            $sesi,
                                            $siswa
                                        ]
                                    )
                                }}"
                                method="POST"
                            >

                                @csrf
                                @method('PATCH')


                                <div class="modal-header">

                                    <h5 class="modal-title">
                                        Ubah Status Absensi
                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                    ></button>

                                </div>


                                <div class="modal-body">

                                    <div class="mb-4">

                                        <div class="text-secondary small">
                                            Siswa
                                        </div>

                                        <div class="fw-bold mt-1">
                                            {{ $siswa->user->name ?? '-' }}
                                        </div>

                                        <div class="text-secondary">
                                            NIS: {{ $siswa->nis }}
                                        </div>

                                    </div>


                                    <div class="mb-3">

                                        <label class="form-label">
                                            Status Kehadiran
                                        </label>

                                        <select
                                            name="status"
                                            class="form-select"
                                            required
                                        >

                                            <option
                                                value="hadir"
                                                @selected(
                                                    $absensi?->status
                                                    === 'hadir'
                                                )
                                            >
                                                Hadir
                                            </option>

                                            <option
                                                value="terlambat"
                                                @selected(
                                                    $absensi?->status
                                                    === 'terlambat'
                                                )
                                            >
                                                Terlambat
                                            </option>

                                            <option
                                                value="izin"
                                                @selected(
                                                    $absensi?->status
                                                    === 'izin'
                                                )
                                            >
                                                Izin
                                            </option>

                                            <option
                                                value="sakit"
                                                @selected(
                                                    $absensi?->status
                                                    === 'sakit'
                                                )
                                            >
                                                Sakit
                                            </option>

                                            <option
                                                value="alpa"
                                                @selected(
                                                    $absensi?->status
                                                    === 'alpa'
                                                )
                                            >
                                                Alpa
                                            </option>

                                        </select>

                                    </div>


                                    <div>

                                        <label class="form-label">
                                            Keterangan
                                        </label>

                                        <textarea
                                            name="keterangan"
                                            class="form-control"
                                            rows="3"
                                            placeholder="Tambahkan keterangan jika diperlukan..."
                                        >{{ $absensi?->keterangan }}</textarea>

                                    </div>

                                </div>


                                <div class="modal-footer">

                                    <button
                                        type="button"
                                        class="btn btn-link"
                                        data-bs-dismiss="modal"
                                    >
                                        Batal
                                    </button>

                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        <i class="ti ti-device-floppy me-1"></i>
                                        Simpan
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

                @endif

            @empty

                <tr>

                    <td
                        colspan="7"
                        class="
                            text-center
                            text-secondary
                            py-5
                        "
                    >

                        <i
                            class="
                                ti
                                ti-users-off
                                fs-1
                                d-block
                                mb-2
                            "
                        ></i>

                        Belum ada siswa aktif yang terdaftar
                        di kelas ini.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection



{{-- ========================================================= --}}
{{-- SCRIPT SCANNER --}}
{{-- ========================================================= --}}

@push('scripts')

@if($sesi->status === 'aktif')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const readerElement =
            document.getElementById('reader');

        if (
            !readerElement
            || typeof Html5Qrcode === 'undefined'
        ) {

            console.error(
                'Html5Qrcode tidak tersedia.'
            );

            return;
        }


        const scanner =
            new Html5Qrcode('reader');


        let sedangMemproses = false;


        const resultBox =
            document.getElementById(
                'scan-result'
            );

        const studentName =
            document.getElementById(
                'last-student-name'
            );

        const studentNis =
            document.getElementById(
                'last-student-nis'
            );

        const studentStatus =
            document.getElementById(
                'last-student-status'
            );

        const studentAvatar =
            document.getElementById(
                'last-student-avatar'
            );


        function tampilkanHasil(
            message,
            type = 'info'
        ) {

            resultBox.className =
                'alert alert-' + type;

            resultBox.innerHTML =
                '<div class="d-flex align-items-center">'
                + '<span>'
                + escapeHtml(message)
                + '</span>'
                + '</div>';

        }


        function escapeHtml(value) {

            const div =
                document.createElement('div');

            div.textContent =
                value ?? '';

            return div.innerHTML;

        }


        function updateSiswa(data) {

            if (!data) {
                return;
            }

            studentName.textContent =
                data.nama ?? '-';

            studentNis.textContent =
                data.nis
                    ? 'NIS: ' + data.nis
                    : '-';

            studentAvatar.textContent =
                data.nama
                    ? data.nama
                        .charAt(0)
                        .toUpperCase()
                    : '-';

            studentStatus.textContent =
                data.status
                    ? data.status.toUpperCase()
                    : '-';


            studentStatus.className =
                'badge';


            if (data.status === 'hadir') {

                studentStatus.classList.add(
                    'bg-success-lt'
                );

            }

            else if (
                data.status === 'terlambat'
            ) {

                studentStatus.classList.add(
                    'bg-warning-lt'
                );

            }

            else {

                studentStatus.classList.add(
                    'bg-secondary-lt'
                );

            }

        }


        function updateBarisAbsensi(siswa) {

            if (!siswa?.id) {
                return;
            }


            const row =
                document.getElementById(
                    'siswa-row-' + siswa.id
                );


            if (!row) {
                return;
            }


            const waktu =
                row.querySelector(
                    '.absensi-waktu'
                );

            const metode =
                row.querySelector(
                    '.absensi-metode'
                );

            const status =
                row.querySelector(
                    '.absensi-status'
                );


            if (waktu) {

                waktu.textContent =
                    siswa.waktu ?? '-';

            }


            if (metode) {

                metode.innerHTML =
                    '<span class="badge bg-secondary-lt">'
                    + 'QR'
                    + '</span>';

            }


            if (status) {

                let badgeClass =
                    'bg-secondary-lt';


                if (
                    siswa.status === 'hadir'
                ) {

                    badgeClass =
                        'bg-success-lt';

                }

                else if (
                    siswa.status
                    === 'terlambat'
                ) {

                    badgeClass =
                        'bg-warning-lt';

                }


                status.innerHTML =
                    '<span class="badge '
                    + badgeClass
                    + '">'
                    + escapeHtml(
                        siswa.status
                            ?.toUpperCase()
                            ?? '-'
                    )
                    + '</span>';

            }

        }


        function updateStatistik(status) {

            const hadirElement =
                document.getElementById(
                    'stat-hadir'
                );

            const terlambatElement =
                document.getElementById(
                    'stat-terlambat'
                );

            const belumElement =
                document.getElementById(
                    'stat-belum'
                );


            if (status === 'hadir') {

                hadirElement.textContent =
                    parseInt(
                        hadirElement.textContent
                    ) + 1;

            }


            if (
                status === 'terlambat'
            ) {

                terlambatElement.textContent =
                    parseInt(
                        terlambatElement.textContent
                    ) + 1;

            }


            const jumlahBelum =
                parseInt(
                    belumElement.textContent
                );


            if (jumlahBelum > 0) {

                belumElement.textContent =
                    jumlahBelum - 1;

            }

        }


        async function prosesScan(
            qrText
        ) {

            if (sedangMemproses) {
                return;
            }


            sedangMemproses = true;


            try {

                const response =
                    await fetch(

                        @json(
                            route(
                                'absensi.sesi.scan',
                                $sesi
                            )
                        ),

                        {

                            method: 'POST',


                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .content,

                            },


                            body:
                                JSON.stringify({

                                    qr:
                                        qrText,

                                }),

                        }

                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    tampilkanHasil(

                        data.message
                            ?? 'Absensi gagal.',

                        response.status === 409
                            ? 'warning'
                            : 'danger'

                    );


                    if (data.siswa) {

                        updateSiswa(
                            data.siswa
                        );

                    }


                    return;

                }


                tampilkanHasil(
                    data.message,
                    'success'
                );


                updateSiswa(
                    data.siswa
                );


                updateBarisAbsensi(
                    data.siswa
                );


                updateStatistik(
                    data.siswa.status
                );


            }

            catch (error) {

                console.error(
                    error
                );


                tampilkanHasil(

                    'Terjadi kesalahan saat memproses QR.',

                    'danger'

                );

            }

            finally {

                /*
                 * Jeda agar QR yang sama
                 * tidak diproses berulang.
                 */

                setTimeout(
                    function () {

                        sedangMemproses =
                            false;

                    },

                    2000

                );

            }

        }


        scanner.start(

            {
                facingMode:
                    'environment'
            },

            {
                fps: 10,

                qrbox: {
                    width: 250,
                    height: 250
                }
            },

            prosesScan,

            function () {

                /*
                 * Error frame scanner
                 * sengaja diabaikan.
                 */

            }

        )

        .catch(
            function (error) {

                console.error(
                    error
                );


                tampilkanHasil(

                    'Kamera tidak dapat dibuka. Pastikan izin kamera diberikan dan gunakan HTTPS saat mengakses dari perangkat lain.',

                    'danger'

                );

            }
        );

    }
);

</script>

@endif

@endpush



{{-- ========================================================= --}}
{{-- STYLE --}}
{{-- ========================================================= --}}

@push('styles')

<style>

.scanner-wrapper {

    width: 100%;

    max-width: 650px;

    margin: 0 auto;

    overflow: hidden;

    border: 1px solid
        var(--tblr-border-color);

    border-radius: 12px;

    background: #000;

}


.scanner-reader {

    width: 100%;

    min-height: 350px;

}


#reader video {

    width: 100% !important;

    border-radius: 12px;

}


.stat-card {

    height: 100%;

    padding: 20px;

    border: 1px solid
        var(--tblr-border-color);

    border-radius: 12px;

    background:
        var(--tblr-bg-surface);

}


.stat-number {

    margin-top: 8px;

    font-size: 2rem;

    font-weight: 700;

    line-height: 1;

}


@media (
    max-width: 768px
) {

    .scanner-reader {

        min-height: 280px;

    }


    .page-header
    .col-auto {

        width: 100%;

    }


    .page-header
    .col-auto
    .d-flex {

        width: 100%;

        flex-wrap: wrap;

    }

}

</style>

@endpush