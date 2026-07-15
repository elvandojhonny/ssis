<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('tahunAjaran')
            ->withCount('siswa')
            ->latest()
            ->paginate(10);

        return view('master.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $tahunAjarans = TahunAjaran::orderByDesc('is_active')
            ->orderByDesc('nama')
            ->get();

        return view('master.kelas.create', compact('tahunAjarans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => [
                'required',
                'exists:tahun_ajarans,id',
            ],
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kelas', 'nama')
                    ->where(fn ($query) => $query->where(
                        'tahun_ajaran_id',
                        $request->tahun_ajaran_id
                    )),
            ],
            'tingkat' => [
                'required',
                Rule::in(['X', 'XI', 'XII']),
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        Kelas::create([
            ...$validated,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()
            ->route('kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        $tahunAjarans = TahunAjaran::orderByDesc('is_active')
            ->orderByDesc('nama')
            ->get();

        return view(
            'master.kelas.edit',
            compact('kelas', 'tahunAjarans')
        );
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => [
                'required',
                'exists:tahun_ajarans,id',
            ],
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kelas', 'nama')
                    ->where(fn ($query) => $query->where(
                        'tahun_ajaran_id',
                        $request->tahun_ajaran_id
                    ))
                    ->ignore($kelas->id),
            ],
            'tingkat' => [
                'required',
                Rule::in(['X', 'XI', 'XII']),
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $kelas->update([
            ...$validated,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()
            ->route('kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        if ($kelas->siswa()->exists()) {
            return back()->with(
                'error',
                'Kelas tidak dapat dihapus karena masih memiliki siswa.'
            );
        }

        $kelas->delete();

        return redirect()
            ->route('kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}