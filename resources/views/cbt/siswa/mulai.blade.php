@extends('layouts.app')

@section('title', 'Mulai Ujian')

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-8 col-xl-7">

        <div class="card">

            <div class="card-body text-center py-5 px-4">

                {{-- Icon --}}
                <span class="avatar avatar-xl bg-success-lt mb-3">

                    <i class="ti ti-circle-check"></i>

                </span>


                {{-- Judul --}}
                <h2 class="mb-2">
                    Token Berhasil Diverifikasi
                </h2>


                <p class="text-secondary mb-4">

                    Anda telah mendapatkan akses ke ujian

                    <strong class="text-body">
                        {{ $ujian->judul }}
                    </strong>.

                </p>


                {{-- Informasi Ujian --}}
                <div class="row g-3 text-start mb-4">

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="text-secondary small mb-1">
                                Durasi Ujian
                            </div>

                            <div class="fw-bold">

                                <i class="ti ti-clock me-1"></i>

                                {{ $ujian->durasi_menit }} menit

                            </div>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="text-secondary small mb-1">
                                Kelas
                            </div>

                            <div class="fw-bold">

                                <i class="ti ti-school me-1"></i>

                                {{ $ujian->kelas->nama ?? '-' }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Peringatan --}}
                <div class="alert alert-warning text-start">

                    <div class="d-flex">

                        <div class="me-2">

                            <i class="ti ti-alert-triangle"></i>

                        </div>

                        <div>

                            Setelah menekan tombol
                            <strong>Mulai Ujian</strong>,
                            waktu pengerjaan akan langsung berjalan.

                            Pastikan koneksi internet dan perangkat
                            Anda dalam kondisi siap.

                        </div>

                    </div>

                </div>


                {{-- Tombol Mulai --}}
                <form
                    action="{{ route(
                        'cbt.siswa.pengerjaan.mulai',
                        $ujian
                    ) }}"
                    method="POST"
                    class="mt-4"
                >

                    @csrf


                    <button
                        type="submit"
                        class="btn btn-primary btn-lg w-100"
                        onclick="return confirm(
                            'Mulai ujian sekarang? Waktu akan langsung berjalan setelah ujian dimulai.'
                        )"
                    >

                        <i class="ti ti-player-play me-2"></i>

                        Mulai Ujian

                    </button>

                </form>


                {{-- Kembali --}}
                <div class="mt-3">

                    <a
                        href="{{ route('cbt.siswa.index') }}"
                        class="btn btn-link text-secondary"
                    >

                        <i class="ti ti-arrow-left me-1"></i>

                        Kembali ke Daftar Ujian

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection