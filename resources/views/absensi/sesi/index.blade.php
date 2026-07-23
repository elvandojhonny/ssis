@extends('layouts.app')

@section('title', 'Sesi Absensi')

@section('content')


{{-- ========================================================= --}}
{{-- PAGE HEADER --}}
{{-- ========================================================= --}}

<div class="page-header mb-4">

    <div class="row align-items-center g-3">

        <div class="col">

            <div class="page-pretitle">
                Absensi
            </div>

            <h2 class="page-title">
                Sesi Absensi
            </h2>

            <div class="text-secondary mt-1">
                Kelola sesi absensi pagi dan siang setiap kelas.
            </div>

        </div>


        <div class="col-12 col-md-auto">

            <a
                href="{{ route('absensi.sesi.create') }}"
                class="btn btn-primary w-100"
            >
                <i class="ti ti-plus me-1"></i>

                Buka Sesi Absensi
            </a>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- ALERT --}}
{{-- ========================================================= --}}

@if(session('success'))

    <div class="alert alert-success">

        <div class="d-flex align-items-center">

            <i class="ti ti-circle-check me-2"></i>

            <div>
                {{ session('success') }}
            </div>

        </div>

    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger">

        <div class="d-flex align-items-center">

            <i class="ti ti-alert-circle me-2"></i>

            <div>
                {{ session('error') }}
            </div>

        </div>

    </div>

@endif



{{-- ========================================================= --}}
{{-- SESI AKTIF HARI INI --}}
{{-- ========================================================= --}}

<div class="mb-3">

    <div class="d-flex align-items-center gap-2">

        <span class="avatar avatar-sm bg-success-lt">

            <i class="ti ti-activity"></i>

        </span>


        <div>

            <h3 class="page-title mb-0">
                Sesi Aktif Hari Ini
            </h3>

            <div class="text-secondary small mt-1">
                Sesi absensi yang sedang berlangsung.
            </div>

        </div>

    </div>

</div>



<div class="row row-cards mb-4">

    @forelse($sesiAktif as $sesi)

        <div class="col-12 col-md-6 col-xl-4">

            <div class="card h-100">

                <div class="card-status-top bg-success"></div>


                <div class="card-body">


                    {{-- HEADER --}}

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

                                <i class="ti ti-school me-1"></i>

                                Kelas {{ $sesi->tingkat ?? '-' }}

                            </div>


                            <h2 class="mb-0">

                                Absensi
                                {{ ucfirst($sesi->jenis) }}

                            </h2>

                        </div>


                        <span class="badge bg-success-lt">

                            <i class="ti ti-circle-filled me-1"></i>

                            Aktif

                        </span>

                    </div>



                    {{-- INFORMASI --}}

                    <div class="mt-4">


                        <div
                            class="
                                d-flex
                                align-items-center
                                mb-3
                            "
                        >

                            <span
                                class="
                                    avatar
                                    avatar-sm
                                    bg-blue-lt
                                    me-3
                                "
                            >

                                <i class="ti ti-clock"></i>

                            </span>


                            <div>

                                <div class="text-secondary small">
                                    Waktu Absensi
                                </div>

                                <div class="fw-bold mt-1">

                                    {{ $sesi->waktu_mulai }}

                                    <span class="text-secondary mx-1">
                                        -
                                    </span>

                                    {{ $sesi->waktu_selesai }}

                                </div>

                            </div>

                        </div>



                        <div
                            class="
                                d-flex
                                align-items-center
                            "
                        >

                            <span
                                class="
                                    avatar
                                    avatar-sm
                                    bg-secondary-lt
                                    me-3
                                "
                            >

                                <i class="ti ti-user"></i>

                            </span>


                            <div>

                                <div class="text-secondary small">
                                    Dibuka Oleh
                                </div>

                                <div class="fw-bold mt-1">

                                    {{
                                        $sesi
                                            ->pembuka
                                            ?->name
                                        ?? '-'
                                    }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- FOOTER --}}

                <div class="card-footer">

                    <a
                        href="{{
                            route(
                                'absensi.sesi.show',
                                $sesi
                            )
                        }}"
                        class="btn btn-primary w-100"
                    >

                        <i class="ti ti-scan me-1"></i>

                        Buka Sesi

                    </a>

                </div>

            </div>

        </div>


    @empty

        <div class="col-12">

            <div class="card">

                <div
                    class="
                        card-body
                        text-center
                        py-5
                    "
                >

                    <span
                        class="
                            avatar
                            avatar-xl
                            bg-secondary-lt
                            mb-3
                        "
                    >

                        <i class="ti ti-calendar-off"></i>

                    </span>


                    <h3 class="mb-1">
                        Belum Ada Sesi Aktif
                    </h3>


                    <div class="text-secondary">

                        Belum ada sesi absensi yang sedang
                        berlangsung hari ini.

                    </div>

                </div>

            </div>

        </div>

    @endforelse

</div>



{{-- ========================================================= --}}
{{-- RIWAYAT SESI TERBARU --}}
{{-- ========================================================= --}}

<div class="card">


    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}

    <div class="card-header">

        <div class="row align-items-center w-100 g-3">

            <div class="col">

                <h3 class="card-title mb-1">

                    <i class="ti ti-history me-2"></i>

                    Riwayat Sesi Terbaru

                </h3>


                <div class="text-secondary small">

                    Menampilkan sesi absensi
                    7 hari terakhir.

                </div>

            </div>


            <div class="col-12 col-sm-auto">

                <a
                    href="{{ route('absensi.sesi.arsip') }}"
                    class="btn btn-outline-secondary w-100"
                >

                    <i class="ti ti-archive me-1"></i>

                    Lihat Arsip

                </a>

            </div>

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- DESKTOP / LAPTOP --}}
    {{-- ===================================================== --}}

    <div class="d-none d-md-block">

        <div class="table-responsive">

            <table
                class="
                    table
                    table-vcenter
                    card-table
                    mb-0
                "
            >

                <thead>

                    <tr>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Kelas
                        </th>

                        <th>
                            Jenis
                        </th>

                        <th>
                            Waktu
                        </th>

                        <th>
                            Jumlah Absensi
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="w-1">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($riwayatSesi as $sesi)

                    <tr>


                        {{-- TANGGAL --}}

                        <td>

                            <div class="d-flex align-items-center">

                                <i
                                    class="
                                        ti
                                        ti-calendar
                                        me-2
                                        text-secondary
                                    "
                                ></i>


                                <span>

                                    {{
                                        $sesi
                                            ->tanggal
                                            ->format('d/m/Y')
                                    }}

                                </span>

                            </div>

                        </td>



                        {{-- KELAS --}}

                        <td>

                            <div class="fw-bold">

                                {{
                                    $sesi
                                        ->kelas
                                        ?->nama
                                    ?? '-'
                                }}

                            </div>


                            <div class="text-secondary small">

                                {{
                                    $sesi
                                        ->kelas
                                        ?->tahunAjaran
                                        ?->nama
                                    ?? '-'
                                }}

                            </div>

                        </td>



                        {{-- JENIS --}}

                        <td>

                            @if($sesi->jenis === 'pagi')

                                <span class="badge bg-yellow-lt">

                                    <i class="ti ti-sun me-1"></i>

                                    Pagi

                                </span>

                            @else

                                <span class="badge bg-blue-lt">

                                    <i class="ti ti-sunset me-1"></i>

                                    Siang

                                </span>

                            @endif

                        </td>



                        {{-- WAKTU --}}

                        <td>

                            <span class="text-nowrap">

                                <i
                                    class="
                                        ti
                                        ti-clock
                                        me-1
                                        text-secondary
                                    "
                                ></i>

                                {{ $sesi->waktu_mulai }}

                                -

                                {{ $sesi->waktu_selesai }}

                            </span>

                        </td>



                        {{-- JUMLAH ABSENSI --}}

                        <td>

                            <span class="text-nowrap">

                                <i
                                    class="
                                        ti
                                        ti-users
                                        me-1
                                        text-secondary
                                    "
                                ></i>

                                {{ $sesi->absensis_count }}

                                siswa

                            </span>

                        </td>



                        {{-- STATUS --}}

                        <td>

                            @if($sesi->status === 'aktif')

                                <span class="badge bg-success-lt">

                                    <i
                                        class="
                                            ti
                                            ti-circle-check
                                            me-1
                                        "
                                    ></i>

                                    Aktif

                                </span>

                            @else

                                <span class="badge bg-secondary-lt">

                                    <i
                                        class="
                                            ti
                                            ti-circle-check
                                            me-1
                                        "
                                    ></i>

                                    Selesai

                                </span>

                            @endif

                        </td>



                        {{-- AKSI --}}

                        <td>

                            <a
                                href="{{
                                    route(
                                        'absensi.sesi.show',
                                        $sesi
                                    )
                                }}"
                                class="
                                    btn
                                    btn-sm
                                    btn-outline-primary
                                "
                            >

                                <i class="ti ti-eye me-1"></i>

                                Detail

                            </a>

                        </td>

                    </tr>


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
                                    ti-history
                                    fs-1
                                    d-block
                                    mb-2
                                "
                            ></i>


                            Belum ada riwayat sesi absensi.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- MOBILE --}}
    {{-- ===================================================== --}}

    <div class="d-md-none">

        @forelse($riwayatSesi as $sesi)

            <div
                class="
                    p-3
                    border-bottom
                    ssis-session-mobile
                "
            >


                {{-- HEADER --}}

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-start
                        gap-3
                        mb-3
                    "
                >

                    <div class="min-w-0">


                        {{-- KELAS --}}

                        <div class="fw-bold fs-3">

                            {{
                                $sesi
                                    ->kelas
                                    ?->nama
                                ?? '-'
                            }}

                        </div>


                        {{-- TAHUN AJARAN --}}

                        <div
                            class="
                                text-secondary
                                small
                                mt-1
                            "
                        >

                            {{
                                $sesi
                                    ->kelas
                                    ?->tahunAjaran
                                    ?->nama
                                ?? '-'
                            }}

                        </div>

                    </div>



                    {{-- JENIS --}}

                    @if($sesi->jenis === 'pagi')

                        <span
                            class="
                                badge
                                bg-yellow-lt
                                flex-shrink-0
                            "
                        >

                            <i class="ti ti-sun me-1"></i>

                            Pagi

                        </span>

                    @else

                        <span
                            class="
                                badge
                                bg-blue-lt
                                flex-shrink-0
                            "
                        >

                            <i class="ti ti-sunset me-1"></i>

                            Siang

                        </span>

                    @endif

                </div>



                {{-- TANGGAL --}}

                <div
                    class="
                        d-flex
                        align-items-center
                        text-secondary
                        small
                        mb-3
                    "
                >

                    <i class="ti ti-calendar me-2"></i>


                    {{
                        $sesi
                            ->tanggal
                            ->format('d/m/Y')
                    }}

                </div>



                {{-- DETAIL --}}

                <div
                    class="
                        row
                        g-3
                        py-3
                        border-top
                        border-bottom
                    "
                >


                    {{-- WAKTU --}}

                    <div class="col-6">

                        <div
                            class="
                                text-secondary
                                small
                                mb-1
                            "
                        >
                            Waktu
                        </div>


                        <div class="fw-bold">

                            {{ $sesi->waktu_mulai }}

                            -

                            {{ $sesi->waktu_selesai }}

                        </div>

                    </div>



                    {{-- JUMLAH --}}

                    <div class="col-6">

                        <div
                            class="
                                text-secondary
                                small
                                mb-1
                            "
                        >
                            Jumlah Absensi
                        </div>


                        <div class="fw-bold">

                            <i
                                class="
                                    ti
                                    ti-users
                                    me-1
                                    text-secondary
                                "
                            ></i>

                            {{ $sesi->absensis_count }}

                            siswa

                        </div>

                    </div>

                </div>



                {{-- STATUS --}}

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-center
                        mt-3
                    "
                >

                    <div>

                        <div
                            class="
                                text-secondary
                                small
                                mb-1
                            "
                        >
                            Status Sesi
                        </div>


                        @if($sesi->status === 'aktif')

                            <span class="badge bg-success-lt">

                                <i
                                    class="
                                        ti
                                        ti-circle-check
                                        me-1
                                    "
                                ></i>

                                Aktif

                            </span>

                        @else

                            <span class="badge bg-secondary-lt">

                                <i
                                    class="
                                        ti
                                        ti-circle-check
                                        me-1
                                    "
                                ></i>

                                Selesai

                            </span>

                        @endif

                    </div>

                </div>



                {{-- AKSI --}}

                <a
                    href="{{
                        route(
                            'absensi.sesi.show',
                            $sesi
                        )
                    }}"
                    class="
                        btn
                        btn-outline-primary
                        w-100
                        mt-3
                    "
                >

                    <i class="ti ti-eye me-1"></i>

                    Lihat Detail Sesi

                </a>

            </div>


        @empty

            <div
                class="
                    text-center
                    text-secondary
                    py-5
                    px-3
                "
            >

                <span
                    class="
                        avatar
                        avatar-xl
                        bg-secondary-lt
                        mb-3
                    "
                >

                    <i class="ti ti-history"></i>

                </span>


                <div class="fw-bold text-body mb-1">

                    Belum Ada Riwayat

                </div>


                <div>

                    Belum ada riwayat sesi absensi
                    yang tersedia.

                </div>

            </div>

        @endforelse

    </div>



    {{-- ===================================================== --}}
    {{-- PAGINATION --}}
    {{-- ===================================================== --}}

    @if($riwayatSesi->hasPages())

        <div class="card-footer">

            {{ $riwayatSesi->links() }}

        </div>

    @endif

</div>

@endsection
