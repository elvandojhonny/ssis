<?php

namespace App\Http\Controllers\Perpustakaan;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku
     */
    public function index(Request $request)
    {
        $query = Buku::with('kelas');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $query->where(
                'nama_buku',
                'like',
                '%' . $request->search . '%'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Filter Tab Kelas
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tingkat')) {

            $query->whereHas(
                'kelas',
                function ($q) use ($request) {

                    $q->where(
                        'tingkat',
                        $request->tingkat
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Urutan
        |--------------------------------------------------------------------------
        |
        | X → XI → XII → Umum
        |
        */

        $query->orderByRaw("
            CASE
                WHEN EXISTS (
                    SELECT 1
                    FROM kelas
                    WHERE kelas.id = buku.kelas_id
                    AND kelas.tingkat = 'X'
                ) THEN 1

                WHEN EXISTS (
                    SELECT 1
                    FROM kelas
                    WHERE kelas.id = buku.kelas_id
                    AND kelas.tingkat = 'XI'
                ) THEN 2

                WHEN EXISTS (
                    SELECT 1
                    FROM kelas
                    WHERE kelas.id = buku.kelas_id
                    AND kelas.tingkat = 'XII'
                ) THEN 3

                WHEN buku.is_umum = 1 THEN 4

                ELSE 5
            END
        ");

        $query->orderBy('nama_buku');


        $buku = $query
            ->paginate(10)
            ->withQueryString();


        return view(
            'perpustakaan.buku.index',
            compact('buku')
        );
    }


    /**
     * Form tambah buku
     */
    public function create()
    {
        $kelas = Kelas::orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view(
            'perpustakaan.buku.create',
            compact('kelas')
        );
    }


    /**
     * Simpan buku
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'jenis_buku' => [
                'required',
                Rule::in([
                    'kelas',
                    'umum',
                ]),
            ],

            'kelas_id' => [
                'nullable',
                'required_if:jenis_buku,kelas',
                'exists:kelas,id',
            ],

            'nama_buku' => [
                'required',
                'string',
                'max:255',
            ],

            'nama_penulis' => [
                'nullable',
                'string',
                'max:255',
            ],

            'tahun_terbit' => [
                'nullable',
                'integer',
                'min:1000',
                'max:' . date('Y'),
            ],

            'jumlah' => [
                'required',
                'integer',
                'min:1',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Tentukan jenis buku
        |--------------------------------------------------------------------------
        */

        $isUmum =
            $validated['jenis_buku'] === 'umum';


        /*
        |--------------------------------------------------------------------------
        | Simpan
        |--------------------------------------------------------------------------
        */

        Buku::create([

            'kelas_id' =>
                $isUmum
                    ? null
                    : $validated['kelas_id'],

            'nama_buku' =>
                $validated['nama_buku'],

            'nama_penulis' =>
                $validated['nama_penulis'] ?? null,

            'tahun_terbit' =>
                $validated['tahun_terbit'] ?? null,

            'jumlah' =>
                $validated['jumlah'],

            'jumlah_tersedia' =>
                $validated['jumlah'],

            'is_active' =>
                true,

            'is_umum' =>
                $isUmum,

        ]);


        return redirect()
            ->route(
                'perpustakaan.buku.index'
            )
            ->with(
                'success',
                'Data buku berhasil ditambahkan.'
            );
    }


    /**
     * Form edit buku
     */
    public function edit(Buku $buku)
    {
        $kelas = Kelas::orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view(
            'perpustakaan.buku.edit',
            compact(
                'buku',
                'kelas'
            )
        );
    }


    /**
     * Update buku
     */
    public function update(
        Request $request,
        Buku $buku
    ) {
        $validated = $request->validate([

            'jenis_buku' => [
                'required',
                Rule::in([
                    'kelas',
                    'umum',
                ]),
            ],

            'kelas_id' => [
                'nullable',
                'required_if:jenis_buku,kelas',
                'exists:kelas,id',
            ],

            'nama_buku' => [
                'required',
                'string',
                'max:255',
            ],

            'nama_penulis' => [
                'nullable',
                'string',
                'max:255',
            ],

            'tahun_terbit' => [
                'nullable',
                'integer',
                'min:1000',
                'max:' . date('Y'),
            ],

            'jumlah' => [
                'required',
                'integer',
                'min:1',
            ],

            'jumlah_tersedia' => [
                'required',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validasi jumlah tersedia
        |--------------------------------------------------------------------------
        */

        if (
            $validated['jumlah_tersedia']
            >
            $validated['jumlah']
        ) {

            return back()
                ->withErrors([
                    'jumlah_tersedia' =>
                        'Jumlah tersedia tidak boleh melebihi jumlah buku.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Tentukan jenis buku
        |--------------------------------------------------------------------------
        */

        $isUmum =
            $validated['jenis_buku'] === 'umum';


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $buku->update([

            'kelas_id' =>
                $isUmum
                    ? null
                    : $validated['kelas_id'],

            'nama_buku' =>
                $validated['nama_buku'],

            'nama_penulis' =>
                $validated['nama_penulis'] ?? null,

            'tahun_terbit' =>
                $validated['tahun_terbit'] ?? null,

            'jumlah' =>
                $validated['jumlah'],

            'jumlah_tersedia' =>
                $validated['jumlah_tersedia'],

            'is_active' =>
                $request->has('is_active'),

            'is_umum' =>
                $isUmum,

        ]);


        return redirect()
            ->route(
                'perpustakaan.buku.index'
            )
            ->with(
                'success',
                'Data buku berhasil diperbarui.'
            );
    }


    /**
     * Hapus buku
     */
    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()
            ->route(
                'perpustakaan.buku.index'
            )
            ->with(
                'success',
                'Data buku berhasil dihapus.'
            );
    }
}