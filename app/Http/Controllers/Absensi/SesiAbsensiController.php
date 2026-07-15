<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SesiAbsensiController extends Controller
{
    public function index()
    {
        $sesiAktif = SesiAbsensi::with([
                'kelas.tahunAjaran',
                'pembuka',
            ])
            ->whereDate('tanggal', today())
            ->where('status', 'aktif')
            ->latest()
            ->get();

        $riwayatSesi = SesiAbsensi::with([
                'kelas.tahunAjaran',
                'pembuka',
            ])
            ->withCount('absensis')
            ->latest('tanggal')
            ->latest('id')
            ->paginate(10);

        return view(
            'absensi.sesi.index',
            compact('sesiAktif', 'riwayatSesi')
        );
    }

    public function create()
    {
        $kelas = Kelas::with('tahunAjaran')
            ->where('is_active', true)
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view(
            'absensi.sesi.create',
            compact('kelas')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => [
                'required',
                'exists:kelas,id',
            ],

            'jenis' => [
                'required',
                Rule::in(['pagi', 'siang']),
            ],

            'waktu_mulai' => [
                'required',
                'date_format:H:i',
            ],

            'batas_terlambat' => [
                'nullable',
                'date_format:H:i',
                'after_or_equal:waktu_mulai',
            ],

            'waktu_selesai' => [
                'required',
                'date_format:H:i',
                'after:waktu_mulai',
            ],
        ]);

        $kelas = Kelas::whereKey($validated['kelas_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $sudahAda = SesiAbsensi::where(
                'kelas_id',
                $kelas->id
            )
            ->whereDate('tanggal', today())
            ->where('jenis', $validated['jenis'])
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Sesi absensi '
                    . $validated['jenis']
                    . ' untuk kelas tersebut sudah dibuat hari ini.'
                );
        }

        $sesi = DB::transaction(function () use ($validated) {
            return SesiAbsensi::create([
                'kelas_id' => $validated['kelas_id'],

                'dibuka_oleh' => auth()->id(),

                'tanggal' => today(),

                'jenis' => $validated['jenis'],

                'waktu_mulai' =>
                    $validated['waktu_mulai'],

                'batas_terlambat' =>
                    $validated['batas_terlambat'] ?? null,

                'waktu_selesai' =>
                    $validated['waktu_selesai'],

                'status' => 'aktif',
            ]);
        });

        return redirect()
            ->route('absensi.sesi.show', $sesi)
            ->with(
                'success',
                'Sesi absensi berhasil dibuka.'
            );
    }

    public function show(SesiAbsensi $sesi)
    {
        $sesi->load([
            'kelas.tahunAjaran',
            'pembuka',
        ]);

        $sesi->loadCount('absensis');

        return view(
            'absensi.sesi.show',
            compact('sesi')
        );
    }

    public function tutup(SesiAbsensi $sesi)
    {
        if ($sesi->status === 'selesai') {
            return back()->with(
                'error',
                'Sesi absensi sudah ditutup.'
            );
        }

        $sesi->update([
            'status' => 'selesai',
        ]);

        return redirect()
            ->route('absensi.sesi.index')
            ->with(
                'success',
                'Sesi absensi berhasil ditutup.'
            );
    }
}