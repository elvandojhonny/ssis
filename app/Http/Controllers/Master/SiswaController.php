<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with(['user', 'kelas.tahunAjaran'])
            ->latest()
            ->paginate(10);

        return view('master.siswa.index', compact('siswas'));
    }

    public function create()
    {
        $kelas = Kelas::with('tahunAjaran')
            ->where('is_active', true)
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view('master.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        DB::transaction(function () use ($validated) {
            $isActive = (bool) ($validated['is_active'] ?? false);

            $user = User::create([
                'name' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
                'password' => $validated['password'],
                'role' => 'siswa',
                'is_active' => $isActive,
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'kelas_id' => $validated['kelas_id'],
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'] ?? null,
                'nama' => $validated['nama'],
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'is_active' => $isActive,
            ]);
        });

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data dan akun siswa berhasil dibuat.');
    }

    public function edit(Siswa $siswa)
    {
        $siswa->load('user');

        $kelas = Kelas::with('tahunAjaran')
            ->where('is_active', true)
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view(
            'master.siswa.edit',
            compact('siswa', 'kelas')
        );
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $this->validateData(
            $request,
            $siswa
        );

        DB::transaction(function () use ($validated, $siswa) {
            $isActive = (bool) ($validated['is_active'] ?? false);

            $userData = [
                'name' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
                'is_active' => $isActive,
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }

            $siswa->user->update($userData);

            $siswa->update([
                'kelas_id' => $validated['kelas_id'],
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'] ?? null,
                'nama' => $validated['nama'],
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'is_active' => $isActive,
            ]);
        });

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        DB::transaction(function () use ($siswa) {
            $siswa->user->delete();
        });

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data dan akun siswa berhasil dihapus.');
    }

    private function validateData(
        Request $request,
        ?Siswa $siswa = null
    ): array {
        return $request->validate([
            'kelas_id' => [
                'required',
                'exists:kelas,id',
            ],

            'nis' => [
                'required',
                'string',
                'max:50',
                Rule::unique('siswa', 'nis')
                    ->ignore($siswa?->id),
            ],

            'nisn' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('siswa', 'nisn')
                    ->ignore($siswa?->id),
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')
                    ->ignore($siswa?->user_id),
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($siswa?->user_id),
            ],

            'password' => [
                $siswa ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'jenis_kelamin' => [
                'nullable',
                Rule::in(['L', 'P']),
            ],

            'alamat' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);
    }
}