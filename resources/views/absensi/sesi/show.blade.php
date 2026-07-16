@extends('layouts.app')

@section('title', 'Detail Sesi Absensi')

@section('content')

<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">

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
                    class="d-inline"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('Tutup sesi absensi ini?')"
                    >
                        <i class="ti ti-lock me-1"></i>
                        Tutup Sesi
                    </button>

                </form>

            @endif

        </div>

    </div>
</div>

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


<div class="row row-cards">

    {{-- CARD QR --}}
    <div class="col-lg-8">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    QR Absensi

                </h3>

            </div>

            <div class="card-body text-center py-5">

                @if($sesi->status === 'aktif')

                    <div
                        id="qr-loading"
                        class="py-5"
                    >

                        <div
                            class="spinner-border text-primary"
                            role="status"
                        ></div>

                        <div class="text-secondary mt-3">

                            Memuat QR Absensi...

                        </div>

                    </div>

                    <div
                        id="qr-container"
                        class="d-none"
                    >

                        <div class="mb-4">

                            <img
                                id="qr-image"
                                src=""
                                alt="QR Absensi"
                                class="img-fluid border rounded p-3 bg-white"
                                style="max-width:320px;"
                            >

                        </div>

                        <h2 class="mb-2">

                            Scan QR Absensi

                        </h2>

                        <div class="text-secondary">

                            QR Code berubah otomatis setiap 15 detik.

                        </div>

                        <div class="mt-3">

                            <span class="badge bg-blue-lt">

                                QR diperbarui dalam

                                <span id="qr-countdown">

                                    15

                                </span>

                                detik

                            </span>

                        </div>

                    </div>

                    <div
                        id="qr-error"
                        class="d-none py-5"
                    >

                        <i
                            class="ti ti-alert-circle text-danger"
                            style="font-size:60px;"
                        ></i>

                        <h3 class="mt-3">

                            QR gagal dimuat

                        </h3>

                        <div
                            id="qr-error-message"
                            class="text-secondary"
                        ></div>

                    </div>

                @else

                    <i
                        class="ti ti-lock"
                        style="font-size:80px;"
                    ></i>

                    <h2 class="mt-3">

                        Sesi Telah Ditutup

                    </h2>

                    <div class="text-secondary">

                        QR tidak tersedia lagi.

                    </div>

                @endif

            </div>

        </div>

    </div>

        {{-- CARD INFORMASI --}}
    <div class="col-lg-4">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    Informasi Sesi

                </h3>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <div class="text-secondary">

                        Kelas

                    </div>

                    <div class="fw-bold">

                        {{ $sesi->kelas->nama }}

                    </div>

                </div>

                <div class="mb-3">

                    <div class="text-secondary">

                        Tahun Ajaran

                    </div>

                    <div class="fw-bold">

                        {{ $sesi->kelas->tahunAjaran->nama }}

                    </div>

                </div>

                <div class="mb-3">

                    <div class="text-secondary">

                        Jenis

                    </div>

                    <div class="fw-bold">

                        {{ ucfirst($sesi->jenis) }}

                    </div>

                </div>

                <div class="mb-3">

                    <div class="text-secondary">

                        Status

                    </div>

                    @if($sesi->status == 'aktif')

                        <span class="badge bg-success-lt">

                            Aktif

                        </span>

                    @else

                        <span class="badge bg-secondary-lt">

                            Ditutup

                        </span>

                    @endif

                </div>

                <div class="mb-3">

                    <div class="text-secondary">

                        Tanggal

                    </div>

                    <div class="fw-bold">

                        {{ $sesi->tanggal->format('d F Y') }}

                    </div>

                </div>

                <div class="mb-3">

                    <div class="text-secondary">

                        Waktu

                    </div>

                    <div class="fw-bold">

                        {{ $sesi->waktu_mulai }}
                        -
                        {{ $sesi->waktu_selesai }}

                    </div>

                </div>

                <div class="mb-3">

                    <div class="text-secondary">

                        Batas Terlambat

                    </div>

                    <div class="fw-bold">

                        {{ $sesi->batas_terlambat ?? '-' }}

                    </div>

                </div>

                <div class="mb-3">

                    <div class="text-secondary">

                        Dibuka Oleh

                    </div>

                    <div class="fw-bold">

                        {{ $sesi->pembuka->name }}

                    </div>

                </div>

                <div>

                    <div class="text-secondary">

                        Jumlah Kehadiran

                    </div>

                    <div class="display-6">

                        {{ $sesi->absensis_count }}

                    </div>

                    <div class="text-secondary">

                        siswa

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card mt-4">

    <div class="card-header">

        <div>

            <h3 class="card-title">
                Daftar Kehadiran Siswa
            </h3>

            <div class="text-secondary mt-1">
                Kelola status kehadiran siswa pada sesi ini.
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
                    <th>Aksi</th>
                </tr>

            </thead>


            <tbody>

            @forelse($daftarSiswa as $siswa)

                @php
                    $absensi = $siswa->data_absensi;
                @endphp

                <tr>

                    <td>

                        <div class="fw-bold">
                            {{ $siswa->user->name }}
                        </div>

                    </td>


                    <td>

                        {{ $siswa->nis }}

                    </td>


                    <td>

                        {{
                            $absensi
                                ?->waktu_absen
                                ?->format('H:i:s')
                            ?? '-'
                        }}

                    </td>


                    <td>

                        @if($absensi)

                            <span class="badge bg-secondary-lt">

                                {{
                                    strtoupper(
                                        $absensi->metode
                                    )
                                }}

                            </span>

                        @else

                            -

                        @endif

                    </td>


                    <td>

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

                        @else

                            <span class="badge bg-danger-lt">
                                Alpa
                            </span>

                        @endif

                    </td>


                    <td>

                        {{
                            $absensi?->keterangan
                            ?? '-'
                        }}

                    </td>


                    <td>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#statusModal{{ $siswa->id }}"
                        >
                            Ubah Status
                        </button>

                    </td>

                </tr>


                {{-- MODAL STATUS --}}

                <div
                    class="modal modal-blur fade"
                    id="statusModal{{ $siswa->id }}"
                    tabindex="-1"
                >

                    <div
                        class="modal-dialog modal-dialog-centered"
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
                                    ></button>

                                </div>


                                <div class="modal-body">

                                    <div class="mb-3">

                                        <label class="form-label">
                                            Siswa
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            value="{{ $siswa->user->name }}"
                                            disabled
                                        >

                                    </div>


                                    <div class="mb-3">

                                        <label class="form-label">
                                            Status
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
                                            placeholder="Opsional"
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
                                        Simpan
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            @empty

                <tr>

                    <td
                        colspan="7"
                        class="text-center text-secondary py-5"
                    >
                        Belum ada siswa aktif di kelas ini.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

@endsection

@if($sesi->status === 'aktif')

<script>

document.addEventListener('DOMContentLoaded', () => {

    const qrUrl = @json(route('absensi.sesi.qr', $sesi));

    const loading = document.getElementById('qr-loading');
    const container = document.getElementById('qr-container');
    const errorBox = document.getElementById('qr-error');

    const qrImage = document.getElementById('qr-image');
    const errorMessage = document.getElementById('qr-error-message');
    const countdownText = document.getElementById('qr-countdown');

    let countdown = 15;

    async function loadQr() {

        try {

            loading.classList.remove('d-none');
            container.classList.add('d-none');
            errorBox.classList.add('d-none');

            const response = await fetch(qrUrl, {
                headers: {
                    Accept: 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message ?? 'QR tidak dapat dimuat.'
                );
            }

            qrImage.src =
                'data:image/svg+xml;base64,' + data.qr;

            countdown = data.expires_in ?? 15;

            countdownText.textContent = countdown;

            loading.classList.add('d-none');
            container.classList.remove('d-none');

        }

        catch (err) {

            loading.classList.add('d-none');
            container.classList.add('d-none');

            errorBox.classList.remove('d-none');

            errorMessage.textContent = err.message;

        }

    }

    loadQr();

    setInterval(() => {

        countdown--;

        if (countdown <= 0) {

            loadQr();
            return;

        }

        countdownText.textContent = countdown;

    }, 1000);

});

</script>

@endif

<style>

    /* Card QR */

    #qr-container img{

        display:block;

        margin:auto;

        max-width:320px;

        width:100%;

        height:auto;

        background:#fff;

        border:1px solid #dee2e6;

        border-radius:12px;

        padding:16px;

        box-shadow:0 4px 12px rgba(0,0,0,.08);

    }

    #qr-loading,
    #qr-error,
    #qr-container{

        min-height:420px;

        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;

    }

    .card{

        height:100%;

    }

    .card-body{

        display:flex;
        flex-direction:column;
        justify-content:center;

    }

    @media (max-width:992px){

        #qr-loading,
        #qr-error,
        #qr-container{

            min-height:320px;

        }

        #qr-container img{

            max-width:250px;

        }

    }

</style>