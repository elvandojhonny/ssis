@extends('layouts.app')

@section('title', 'Guru')

@section('content')

<div class="page-header mb-4">

    <div class="row align-items-center">

        <div class="col">
            <h2 class="page-title">Data Guru</h2>

            <div class="text-secondary mt-1">
                Kelola data dan akun guru.
            </div>
        </div>

        <div class="col-auto">
            <a
                href="{{ route('guru.create') }}"
                class="btn btn-primary"
            >
                <i class="ti ti-plus me-1"></i>
                Tambah Guru
            </a>
        </div>

    </div>

</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">

    <div class="table-responsive">

        <table class="table table-vcenter card-table">

            <thead>
                <tr>
                    <th>Guru</th>
                    <th>NIP</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th class="w-1">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($gurus as $guru)

                <tr>

                    <td>
                        <div class="fw-bold">
                            {{ $guru->nama }}
                        </div>

                        <div class="text-secondary small">
                            {{ $guru->user->email ?? '-' }}
                        </div>
                    </td>

                    <td>
                        {{ $guru->nip ?? '-' }}
                    </td>

                    <td>
                        {{ $guru->user->username }}
                    </td>

                    <td>
                        @if($guru->is_active)
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
                                href="{{ route('guru.edit', $guru) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('guru.destroy', $guru) }}"
                                method="POST"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Hapus guru dan akun login ini?')"
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
                        colspan="5"
                        class="text-center text-secondary py-5"
                    >
                        Belum ada data guru.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    @if($gurus->hasPages())
        <div class="card-footer">
            {{ $gurus->links() }}
        </div>
    @endif

</div>

@endsection