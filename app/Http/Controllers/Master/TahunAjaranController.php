<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::latest()->paginate(10);

        return view('master.tahun-ajaran.index', compact('tahunAjarans'));
    }

    public function create()
    {
        return view('master.tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:20',
                'unique:tahun_ajarans,nama',
            ],
            'tanggal_mulai' => [
                'nullable',
                'date',
            ],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $isActive = (bool) ($validated['is_active'] ?? false);

            if ($isActive) {
                TahunAjaran::where('is_active', true)
                    ->update(['is_active' => false]);
            }

            TahunAjaran::create([
                ...$validated,
                'is_active' => $isActive,
            ]);
        });

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view(
            'master.tahun-ajaran.edit',
            compact('tahunAjaran')
        );
    }

    public function update(
        Request $request,
        TahunAjaran $tahunAjaran
    ) {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tahun_ajarans', 'nama')
                    ->ignore($tahunAjaran->id),
            ],
            'tanggal_mulai' => [
                'nullable',
                'date',
            ],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        DB::transaction(function () use ($validated, $tahunAjaran) {
            $isActive = (bool) ($validated['is_active'] ?? false);

            if ($isActive) {
                TahunAjaran::whereKeyNot($tahunAjaran->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $tahunAjaran->update([
                ...$validated,
                'is_active' => $isActive,
            ]);
        });

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->kelas()->exists()) {
            return back()->with(
                'error',
                'Tahun ajaran tidak dapat dihapus karena sudah memiliki data kelas.'
            );
        }

        $tahunAjaran->delete();

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}