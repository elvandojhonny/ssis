@extends('layouts.app')

@section('title', 'Perpustakaan Saya')

@push('styles')

<style>

/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.perpus-header{
    margin-bottom:1.5rem;
}

/*
|--------------------------------------------------------------------------
| STAT CARD
|--------------------------------------------------------------------------
*/

.perpus-stat-card{
    border:0;
    overflow:hidden;
    transition:.2s;
    height:100%;
}

.perpus-stat-card:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 32px rgba(0,0,0,.08);
}

.perpus-stat-icon{
    width:58px;
    height:58px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:16px;
    font-size:1.5rem;
    flex-shrink:0;
}

.perpus-stat-title{
    color:var(--tblr-secondary);
    font-size:.875rem;
}

.perpus-stat-number{
    font-size:1.8rem;
    font-weight:700;
    line-height:1.2;
}

/*
|--------------------------------------------------------------------------
| CARD TRANSAKSI
|--------------------------------------------------------------------------
*/

.transaksi-card{
    border:0;
}

/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media(max-width:767.98px){

    .perpus-stat-card .card-body{
        padding:1rem;
    }

    .perpus-stat-icon{
        width:50px;
        height:50px;
        border-radius:14px;
        font-size:1.25rem;
    }

    .perpus-stat-number{
        font-size:1.45rem;
    }

}

</style>

@endpush

@section('content')

{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<div class="perpus-header">

    <div class="page-pretitle">
        Smart School Information System
    </div>

    <h2 class="page-title">
        Perpustakaan Saya
    </h2>

    <div class="text-secondary mt-1">
        Riwayat peminjaman buku perpustakaan.
    </div>

</div>

{{-- ========================================================= --}}
{{-- STATISTIK --}}
{{-- ========================================================= --}}

<div class="row row-cards mb-4">

    {{-- TOTAL TRANSAKSI --}}

    <div class="col-6 col-lg-3">

        <div class="card perpus-stat-card">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="perpus-stat-title">
                            Total Transaksi
                        </div>

                        <div class="perpus-stat-number">

                            {{ $totalTransaksi }}

                        </div>

                    </div>

                    <div class="perpus-stat-icon bg-blue-lt text-blue">

                        <i class="ti ti-books"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- DIPINJAM --}}

    <div class="col-6 col-lg-3">

        <div class="card perpus-stat-card">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="perpus-stat-title">
                            Sedang Dipinjam
                        </div>

                        <div class="perpus-stat-number">

                            {{ $dipinjam }}

                        </div>

                    </div>

                    <div class="perpus-stat-icon bg-orange-lt text-orange">

                        <i class="ti ti-book-upload"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- DIKEMBALIKAN --}}

    <div class="col-6 col-lg-3">

        <div class="card perpus-stat-card">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="perpus-stat-title">
                            Dikembalikan
                        </div>

                        <div class="perpus-stat-number">

                            {{ $dikembalikan }}

                        </div>

                    </div>

                    <div class="perpus-stat-icon bg-green-lt text-green">

                        <i class="ti ti-circle-check"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- TERLAMBAT --}}

    <div class="col-6 col-lg-3">

        <div class="card perpus-stat-card">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="perpus-stat-title">
                            Terlambat
                        </div>

                        <div class="perpus-stat-number">

                            {{ $terlambat }}

                        </div>

                    </div>

                    <div class="perpus-stat-icon bg-red-lt text-red">

                        <i class="ti ti-clock-exclamation"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- RIWAYAT TRANSAKSI --}}
{{-- ========================================================= --}}

<div class="card transaksi-card">

    <div class="card-header">

    <div class="d-flex justify-content-between align-items-center w-100 flex-wrap gap-2">

        <div class="d-flex align-items-center">

            <span class="avatar bg-primary-lt me-3">
                <i class="ti ti-history"></i>
            </span>

            <div>

                <h3 class="card-title mb-0">
                    Riwayat Peminjaman Buku
                </h3>

                <div class="text-secondary small">
                    Seluruh transaksi peminjaman buku Anda.
                </div>

            </div>

        </div>

        <a
            href="{{ route('perpustakaan.siswa.arsip') }}"
            class="btn btn-outline-primary"
        >
            <i class="ti ti-archive me-1"></i>
            Arsip
        </a>

    </div>

</div>

    <div class="card-body">

        {{-- ========================================================= --}}
{{-- TABEL TRANSAKSI --}}
{{-- ========================================================= --}}

<div class="table-responsive d-none d-md-block">

    <table class="table table-vcenter card-table">

        <thead>

            <tr>

                <th width="70">
                    No
                </th>

                <th>
                    Kode Peminjaman
                </th>

                <th>
                    Buku
                </th>

                <th width="140">
                    Tanggal Pinjam
                </th>

                <th width="140">
                    Jatuh Tempo
                </th>

                <th width="120" class="text-center">
                    Total
                </th>

                <th width="140" class="text-center">
                    Status
                </th>

                <th width="90" class="text-center">
                    Aksi
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($transaksi as $item)

                <tr>

                    {{-- NOMOR --}}

                    <td>

                        <span class="avatar avatar-sm bg-secondary-lt">

                            {{
                                ($transaksi->firstItem() ?? 1)
                                + $loop->index
                            }}

                        </span>

                    </td>


                    {{-- KODE --}}

                    <td>

                        <div class="fw-semibold">

                            {{ $item->kode_peminjaman }}

                        </div>

                    </td>


                    {{-- BUKU --}}

                    <td>

                        @foreach(
                            $item->detailPeminjaman
                            as $detail
                        )

                            <div class="mb-1">

                                <i class="ti ti-book text-primary me-1"></i>

                                {{ $detail->buku?->nama_buku }}

                                @if($detail->jumlah > 1)

                                    <span class="badge bg-blue-lt ms-1">

                                        x{{ $detail->jumlah }}

                                    </span>

                                @endif

                            </div>

                        @endforeach

                    </td>


                    {{-- PINJAM --}}

                    <td>

                        {{
                            optional(
                                $item->tanggal_pinjam
                            )->format('d M Y')
                        }}

                    </td>


                    {{-- JATUH TEMPO --}}

                    <td>

                        {{
                            optional(
                                $item->tanggal_jatuh_tempo
                            )->format('d M Y')
                        }}

                    </td>


                    {{-- TOTAL BUKU --}}

                    <td class="text-center">

                        <span class="badge bg-primary-lt">

                            {{
                                $item
                                ->detailPeminjaman
                                ->sum('jumlah')
                            }}

                            Buku

                        </span>

                    </td>


                    {{-- STATUS --}}

                    <td class="text-center">

                        @if($item->status=='dikembalikan')

                            <span class="badge bg-green-lt">

                                <i class="ti ti-circle-check me-1"></i>

                                Dikembalikan

                            </span>

                        @elseif($item->status=='terlambat')

                            <span class="badge bg-red-lt">

                                <i class="ti ti-clock me-1"></i>

                                Terlambat

                            </span>

                        @else

                            <span class="badge bg-orange-lt">

                                <i class="ti ti-book-upload me-1"></i>

                                Dipinjam

                            </span>

                        @endif

                    </td>

                    <td class="text-center">

                        <button
                            type="button"
                            class="btn btn-sm btn-primary btn-detail"

                            data-bs-toggle="modal"
                            data-bs-target="#modalDetailTransaksi"

                            data-kode="{{ $item->kode_peminjaman }}"

                            data-pinjam="{{ optional($item->tanggal_pinjam)->format('d M Y') }}"

                            data-tempo="{{ optional($item->tanggal_jatuh_tempo)->format('d M Y') }}"

                            data-kembali="{{ optional($item->tanggal_kembali)->format('d M Y') ?? '-' }}"

                            data-status="{{ ucfirst($item->status) }}"

                            data-catatan="{{ $item->catatan ?: 'Tidak ada catatan' }}"

                            data-buku="{{ base64_encode(json_encode(
                                $item->detailPeminjaman->map(function($detail){
                                    return [
                                        'nama'=>$detail->buku?->nama_buku,
                                        'jumlah'=>$detail->jumlah,
                                    ];
                                })->values()->toArray()
                            )) }}"
                        >

                            <i class="ti ti-eye"></i>

                        </button>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="8"
                        class="text-center py-5">

                        <span
                            class="
                                avatar
                                avatar-xl
                                bg-secondary-lt
                                mb-3
                            ">

                            <i class="ti ti-books fs-1"></i>

                        </span>

                        <h3 class="mb-1">

                            Belum Ada Transaksi

                        </h3>

                        <div class="text-secondary">

                            Anda belum pernah melakukan
                            peminjaman buku perpustakaan.

                        </div>

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>



{{-- ========================================================= --}}
{{-- MOBILE --}}
{{-- ========================================================= --}}

<div class="d-block d-md-none">

    @forelse($transaksi as $item)

        <div class="card border mb-3">

            <div class="card-body">

                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-start
                        mb-2
                    ">

                    <div>

                        <div class="fw-bold">

                            {{ $item->kode_peminjaman }}

                        </div>

                        <div class="text-secondary small">

                            {{
                                optional(
                                    $item->tanggal_pinjam
                                )->format('d M Y')
                            }}

                        </div>

                    </div>


                    @if($item->status=='dikembalikan')

                        <span class="badge bg-green-lt">

                            Dikembalikan

                        </span>

                    @elseif($item->status=='terlambat')

                        <span class="badge bg-red-lt">

                            Terlambat

                        </span>

                    @else

                        <span class="badge bg-orange-lt">

                            Dipinjam

                        </span>

                    @endif

                </div>


                <div class="mb-3">

                    @foreach(
                        $item->detailPeminjaman
                        as $detail
                    )

                        <div class="small mb-1">

                            <i
                                class="
                                    ti
                                    ti-book
                                    text-primary
                                    me-1
                                "
                            ></i>

                            {{ $detail->buku?->nama_buku }}

                            ({{ $detail->jumlah }})

                        </div>

                    @endforeach

                </div>


                <div class="row text-center">

                    <div class="col">

                        <div class="text-secondary small">

                            Jatuh Tempo

                        </div>

                        <div class="fw-semibold">

                            {{
                                optional(
                                    $item->tanggal_jatuh_tempo
                                )->format('d M Y')
                            }}

                        </div>

                    </div>


                    <div class="col">

                        <div class="text-secondary small">

                            Total Buku

                        </div>

                        <div class="fw-semibold">

                            {{
                                $item
                                ->detailPeminjaman
                                ->sum('jumlah')
                            }}

                        </div>

                    </div>

                </div>

                <div class="mt-3">

    <button
        type="button"
        class="btn btn-primary w-100 btn-detail"
        data-bs-toggle="modal"
        data-bs-target="#modalDetailTransaksi"

        data-kode="{{ $item->kode_peminjaman }}"
        data-pinjam="{{ optional($item->tanggal_pinjam)->format('d M Y') }}"
        data-tempo="{{ optional($item->tanggal_jatuh_tempo)->format('d M Y') }}"
        data-kembali="{{ optional($item->tanggal_kembali)->format('d M Y') }}"
        data-status="{{ $item->status }}"
        data-catatan="{{ $item->catatan ?? '-' }}"

        data-buku="{{ base64_encode(json_encode(
            $item->detailPeminjaman->map(function($detail){
                return [
                    'nama'=>$detail->buku?->nama_buku,
                    'jumlah'=>$detail->jumlah,
                ];
            })->values()->toArray()
        )) }}">

        <i class="ti ti-eye me-1"></i>
        Detail

    </button>

</div>

            </div>

        </div>

    @empty

    @endforelse

</div>



{{-- ========================================================= --}}
{{-- PAGINATION --}}
{{-- ========================================================= --}}

@if($transaksi->hasPages())

    <div class="mt-4">

        {{ $transaksi->links() }}

    </div>

@endif

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