@extends('layouts.app')

@section('title', 'Petugas Perpustakaan')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">Petugas Perpustakaan</h4>
            <p class="text-muted mb-0">
                Kelola data petugas perpustakaan.
            </p>
        </div>

        <a
            href="{{ route('petugas.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Petugas
        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card shadow-sm border-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="60">No</th>

                        <th>Nama</th>

                        <th>Username</th>

                        <th>Email</th>

                        <th>No HP</th>

                        <th>Status</th>

                        <th width="180" class="text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($petugas as $item)

                        <tr>

                            <td>
                                {{ $loop->iteration + ($petugas->firstItem() - 1) }}
                            </td>

                            <td>

                                <strong>
                                    {{ $item->nama }}
                                </strong>

                                @if($item->nip)

                                    <br>

                                    <small class="text-muted">
                                        {{ $item->nip }}
                                    </small>

                                @endif

                            </td>

                            <td>
                                {{ $item->user->username }}
                            </td>

                            <td>
                                {{ $item->user->email ?? '-' }}
                            </td>

                            <td>
                                {{ $item->no_hp ?? '-' }}
                            </td>

                            <td>

                                @if($item->is_active)

                                    <span class="badge bg-success">
                                        Aktif
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Nonaktif
                                    </span>

                                @endif

                            </td>

                            <td class="text-center">

                                <a
                                    href="{{ route('petugas.edit', $item) }}"
                                    class="btn btn-warning btn-sm"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form
                                    action="{{ route('petugas.destroy', $item) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus petugas ini?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-danger btn-sm"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center text-muted py-4"
                            >
                                Belum ada data petugas.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($petugas->hasPages())

            <div class="card-footer">

                {{ $petugas->links() }}

            </div>

        @endif

    </div>

</div>

@endsection