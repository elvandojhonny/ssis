@extends('layouts.app')

@section('title','Arsip Peminjaman')

@section('content')

<div class="page-header mb-4">

    <div>

        <div class="page-pretitle">

            Smart School Information System

        </div>

        <h2 class="page-title">

            Arsip Peminjaman Buku

        </h2>

        <div class="text-secondary">

            Seluruh riwayat buku yang telah dikembalikan.

        </div>

    </div>

    <div>

        <a href="{{ route('perpustakaan.siswa.index') }}"
           class="btn btn-primary">

            <i class="ti ti-arrow-left me-1"></i>

            Kembali

        </a>

    </div>

</div>


<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            Daftar Arsip

        </h3>

    </div>

    <div class="table-responsive">

        <table class="table table-vcenter card-table">

            <thead>

            <tr>

                <th>No</th>

                <th>Kode</th>

                <th>Buku</th>

                <th>Tanggal Pinjam</th>

                <th>Tanggal Kembali</th>

                <th>Total</th>

                <th>Status</th>

                <th>Aksi</th>

            </tr>

            </thead>

            <tbody>

            @forelse($arsip as $item)

                <tr>

                    <td>

                        {{ ($arsip->firstItem() ?? 1) + $loop->index }}

                    </td>

                    <td>

                        <strong>

                            {{ $item->kode_peminjaman }}

                        </strong>

                    </td>

                    <td>

                        @foreach($item->detailPeminjaman as $detail)

                            <div>

                                {{ $detail->buku?->nama_buku }}

                            </div>

                        @endforeach

                    </td>

                    <td>

                        {{ optional($item->tanggal_pinjam)->format('d M Y') }}

                    </td>

                    <td>

                        {{ optional($item->tanggal_kembali)->format('d M Y') }}

                    </td>

                    <td>

                        <span class="badge bg-primary-lt">

                            {{ $item->detailPeminjaman->sum('jumlah') }}

                            Buku

                        </span>

                    </td>

                    <td>

                        <span class="badge bg-green-lt">

                            Dikembalikan

                        </span>

                    </td>

                    <td>

                        <button
                            class="btn btn-primary btn-sm btn-detail"

                            data-bs-toggle="modal"

                            data-bs-target="#modalDetailTransaksi"

                            data-kode="{{ $item->kode_peminjaman }}"

                            data-pinjam="{{ optional($item->tanggal_pinjam)->format('d M Y') }}"

                            data-tempo="{{ optional($item->tanggal_jatuh_tempo)->format('d M Y') }}"

                            data-kembali="{{ optional($item->tanggal_kembali)->format('d M Y') }}"

                            data-status="{{ ucfirst($item->status) }}"

                            data-catatan="{{ $item->catatan ?? '-' }}"

                            data-buku="{{ base64_encode(json_encode(
                                $item->detailPeminjaman->map(function($d){
                                    return[
                                        'nama'=>$d->buku?->nama_buku,
                                        'jumlah'=>$d->jumlah
                                    ];
                                })
                            )) }}">

                            <i class="ti ti-eye"></i>

                        </button>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="8" class="text-center py-5">

                        Belum ada arsip.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer">

        {{ $arsip->links() }}

    </div>

</div>

{{-- ========================================================= --}}
{{-- MODAL DETAIL TRANSAKSI --}}
{{-- ========================================================= --}}

<div
    class="modal modal-blur fade"
    id="modalDetailTransaksi"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <div class="d-flex align-items-center gap-3">

                    <span class="avatar bg-blue-lt">
                        <i class="ti ti-book"></i>
                    </span>

                    <div>

                        <h3 class="modal-title">
                            Detail Peminjaman
                        </h3>

                        <div
                            id="detailKode"
                            class="text-secondary small"
                        >
                            -
                        </div>

                    </div>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body">

                {{-- STATUS --}}

                <div class="mb-4">

                    <span
                        id="detailStatus"
                        class="badge bg-secondary-lt"
                    >
                        -
                    </span>

                </div>


                {{-- TANGGAL --}}

                <div class="row g-3 mb-4">

                    <div class="col-md-4">

                        <div class="border rounded-3 p-3">

                            <div class="text-secondary small mb-1">
                                Tanggal Pinjam
                            </div>

                            <div id="detailPinjam" class="fw-semibold">
                                -
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="border rounded-3 p-3">

                            <div class="text-secondary small mb-1">
                                Jatuh Tempo
                            </div>

                            <div id="detailTempo" class="fw-semibold">
                                -
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="border rounded-3 p-3">

                            <div class="text-secondary small mb-1">
                                Tanggal Kembali
                            </div>

                            <div id="detailKembali" class="fw-semibold">
                                -
                            </div>

                        </div>

                    </div>

                </div>


                {{-- DAFTAR BUKU --}}

                <div class="mb-4">

                    <div class="fw-semibold mb-2">
                        Daftar Buku
                    </div>

                    <div class="border rounded-3 overflow-hidden">

                        <div class="table-responsive">

                            <table class="table table-vcenter mb-0">

                                <thead>

                                    <tr>

                                        <th>Buku</th>

                                        <th width="120" class="text-center">
                                            Jumlah
                                        </th>

                                    </tr>

                                </thead>

                                <tbody id="detailDaftarBuku">

                                    <tr>

                                        <td colspan="2" class="text-center">
                                            -
                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>


                {{-- CATATAN --}}

                <div>

                    <div class="fw-semibold mb-2">

                        Catatan

                    </div>

                    <div
                        id="detailCatatan"
                        class="bg-light rounded-3 p-3 text-secondary"
                    >

                        Tidak ada catatan.

                    </div>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const detailKode = document.getElementById('detailKode');
    const detailPinjam = document.getElementById('detailPinjam');
    const detailTempo = document.getElementById('detailTempo');
    const detailKembali = document.getElementById('detailKembali');
    const detailStatus = document.getElementById('detailStatus');
    const detailCatatan = document.getElementById('detailCatatan');
    const detailDaftarBuku = document.getElementById('detailDaftarBuku');

    document.querySelectorAll('.btn-detail').forEach(function(btn){

        btn.addEventListener('click', function(){

            detailKode.textContent = btn.dataset.kode;
            detailPinjam.textContent = btn.dataset.pinjam;
            detailTempo.textContent = btn.dataset.tempo;
            detailKembali.textContent = btn.dataset.kembali || '-';
            detailCatatan.textContent = btn.dataset.catatan || '-';

            const status = btn.dataset.status.toLowerCase();

            if(status === 'dipinjam'){

                detailStatus.textContent = 'Dipinjam';
                detailStatus.className = 'badge bg-orange-lt';

            }else if(status === 'terlambat'){

                detailStatus.textContent = 'Terlambat';
                detailStatus.className = 'badge bg-red-lt';

            }else{

                detailStatus.textContent = 'Dikembalikan';
                detailStatus.className = 'badge bg-green-lt';

            }

            let buku = [];

            try{

                buku = JSON.parse(atob(btn.dataset.buku));

            }catch(e){

                buku = [];

            }

            detailDaftarBuku.innerHTML = '';

            if(buku.length === 0){

                detailDaftarBuku.innerHTML = `
                    <tr>
                        <td colspan="2" class="text-center">
                            Tidak ada data buku
                        </td>
                    </tr>
                `;

                return;
            }

            buku.forEach(function(item){

                detailDaftarBuku.innerHTML += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm bg-azure-lt me-2">
                                    <i class="ti ti-book"></i>
                                </span>
                                ${item.nama}
                            </div>
                        </td>

                        <td class="text-center">
                            <span class="badge bg-blue-lt">
                                ${item.jumlah}
                            </span>
                        </td>
                    </tr>
                `;
            });

        });

    });

});

</script>

@endpush