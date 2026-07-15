@extends('layouts.app')

@section('title', 'Tahun Ajaran')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">

            <h2 class="page-title">
                Tahun Ajaran
            </h2>

        </div>

        <div class="col-auto">

            <a
                href="{{ route('tahun-ajaran.create') }}"
                class="btn btn-primary"
            >
                <i class="ti ti-plus me-1"></i>
                Tambah
            </a>

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

<div class="card">

    <div class="table-responsive">

        <table class="table table-vcenter card-table">

            <thead>

                <tr>
                    <th>Tahun Ajaran</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th class="w-1"></th>
                </tr>

            </thead>

            <tbody>

            @forelse($tahunAjarans as $tahun)

                <tr>

                    <td class="fw-bold">
                        {{ $tahun->nama }}
                    </td>

                    <td>

                        {{ $tahun->tanggal_mulai?->format('d/m/Y') ?? '-' }}

                        —

                        {{ $tahun->tanggal_selesai?->format('d/m/Y') ?? '-' }}

                    </td>

                    <td>

                        @if($tahun->is_active)

                            <span class="badge bg-success-lt">
                                Aktif
                            </span>

                        @else

                            <span class="badge bg-secondary-lt">
                                Tidak Aktif
                            </span>

                        @endif

                    </td>

                    <td>

                        <div class="d-flex gap-2">

                            <a
                                href="{{ route(
                                    'tahun-ajaran.edit',
                                    $tahun
                                ) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route(
                                    'tahun-ajaran.destroy',
                                    $tahun
                                ) }}"
                                method="POST"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm(
                                        'Hapus tahun ajaran ini?'
                                    )"
                                >
                                    Hapus
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="4"
                        class="text-center text-secondary py-5"
                    >
                        Belum ada tahun ajaran.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    @if($tahunAjarans->hasPages())

        <div class="card-footer">

            {{ $tahunAjarans->links() }}

        </div>

    @endif

</div>

@endsection