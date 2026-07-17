<?php

namespace App\Http\Controllers\CBT;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\Kelas;
use App\Models\Ujian;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    /*
     * Daftar seluruh ujian.
     */
    public function index()
    {
        $ujians = Ujian::with([
                'bankSoal.soals',
                'bankSoal.guru',
                'kelas.tahunAjaran',
                'pembuat',
            ])
            ->latest()
            ->paginate(9);

        return view(
            'cbt.ujian.index',
            compact('ujians')
        );
    }


    /*
     * Form membuat ujian.
     */
    public function create()
    {
        /*
         * Hanya bank soal yang sudah siap.
         */
        $bankSoals = BankSoal::with([
                'guru',
            ])
            ->withCount('soals')
            ->where('status', 'siap')
            ->latest()
            ->get();

        /*
         * Hanya kelas aktif.
         */
        $kelas = Kelas::with(
                'tahunAjaran'
            )
            ->where(
                'is_active',
                true
            )
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view(
            'cbt.ujian.create',
            compact(
                'bankSoals',
                'kelas'
            )
        );
    }


    /*
     * Simpan ujian sebagai draft.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_soal_id' => [
                'required',
                'exists:bank_soals,id',
            ],

            'kelas_id' => [
                'required',
                'exists:kelas,id',
            ],

            'judul' => [
                'required',
                'string',
                'max:255',
            ],

            'deskripsi' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'waktu_mulai' => [
                'required',
                'date',
            ],

            'waktu_selesai' => [
                'required',
                'date',
                'after:waktu_mulai',
            ],

            'durasi_menit' => [
                'required',
                'integer',
                'min:1',
                'max:600',
            ],
        ]);

        /*
         * Jangan hanya percaya exists.
         * Pastikan Bank Soal memang siap.
         */
        $bankSoal = BankSoal::query()
            ->whereKey(
                $validated['bank_soal_id']
            )
            ->where(
                'status',
                'siap'
            )
            ->firstOrFail();

        /*
         * Pastikan kelas masih aktif.
         */
        $kelas = Kelas::query()
            ->whereKey(
                $validated['kelas_id']
            )
            ->where(
                'is_active',
                true
            )
            ->firstOrFail();

        $ujian = Ujian::create([
            'bank_soal_id' =>
                $bankSoal->id,

            'kelas_id' =>
                $kelas->id,

            'dibuat_oleh' =>
                auth()->id(),

            'judul' =>
                $validated['judul'],

            'deskripsi' =>
                $validated['deskripsi']
                ?? null,

            'waktu_mulai' =>
                $validated['waktu_mulai'],

            'waktu_selesai' =>
                $validated['waktu_selesai'],

            'durasi_menit' =>
                $validated['durasi_menit'],

            /*
             * Belum langsung tampil ke siswa.
             */
            'status' => 'draft',
        ]);

        return redirect()
            ->route(
                'cbt.ujian.show',
                $ujian
            )
            ->with(
                'success',
                'Ujian berhasil dibuat sebagai draft.'
            );
    }


    /*
     * Detail ujian.
     */
    public function show(Ujian $ujian)
    {
        $ujian->load([
            'bankSoal.guru',
            'bankSoal.soals',
            'kelas.tahunAjaran',
            'pembuat',
        ]);

        return view(
            'cbt.ujian.show',
            compact('ujian')
        );
    }

    public function publish(Ujian $ujian)
{
    if ($ujian->status !== 'draft') {

        return back()->with(
            'error',
            'Ujian ini sudah dipublikasikan atau telah selesai.'
        );
    }


    $ujian->load([
        'bankSoal.soals',
        'kelas',
    ]);


    if ($ujian->bankSoal->status !== 'siap') {

        return back()->with(
            'error',
            'Bank soal belum siap digunakan.'
        );
    }


    if ($ujian->bankSoal->soals->isEmpty()) {

        return back()->with(
            'error',
            'Bank soal tidak memiliki soal.'
        );
    }


    if (! $ujian->kelas->is_active) {

        return back()->with(
            'error',
            'Kelas tujuan sudah tidak aktif.'
        );
    }


    $ujian->update([

        'status' => 'dipublikasi',

        'token' =>
            $ujian->token
            ?? Ujian::generateUniqueToken(),

    ]);


    return redirect()
        ->route(
            'cbt.ujian.show',
            $ujian
        )
        ->with(
            'success',
            'Ujian berhasil dipublikasikan.'
        );
}
}