@extends('layouts.app')

@section('title', 'Kelas')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">
            <h2 class="page-title">
                Data Kelas
            </h2>

            <div class="text-secondary mt-1">
                Kelola kelas yang digunakan dalam sistem.
            </div>
        </div>

        <div class="col-auto">
            <a
                href="{{ route('kelas.create') }}"
                class="btn btn-primary"
            >
                <i class="ti ti-plus me-1"></i>
                Tambah Kelas
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
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Tahun Ajaran</th>
                    <th>Jumlah Siswa</th>
                    <th>Status</th>
                    <th class="w-1">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($kelas as $item)

                <tr>

                    <td class="fw-bold">
                        {{ $item->nama }}
                    </td>

                    <td>
                        {{ $item->tingkat }}
                    </td>

                    <td>
                        {{ $item->tahunAjaran->nama }}
                    </td>

                    <td>
                        {{ $item->siswa_count }} siswa
                    </td>

                    <td>
                        @if($item->is_active)
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
                                href="{{ route('kelas.edit', $item) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('kelas.destroy', $item) }}"
                                method="POST"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Hapus kelas ini?')"
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
                        colspan="6"
                        class="text-center text-secondary py-5"
                    >
                        Belum ada data kelas.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    @if($kelas->hasPages())
        <div class="card-footer">
            {{ $kelas->links() }}
        </div>
    @endif

</div>

@endsection