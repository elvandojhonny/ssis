<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    /**
     * Menampilkan daftar petugas.
     */
    public function index()
    {
        $petugas = Petugas::with('user')
            ->latest()
            ->paginate(10);

        return view('master.petugas.index', compact('petugas'));
    }

    /**
     * Form tambah petugas.
     */
    public function create()
    {
        return view('master.petugas.create');
    }

    /**
     * Simpan petugas baru.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'nip' => ['nullable', 'string', 'max:50', 'unique:petugas,nip'],
        'nama' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:50', 'unique:users,username'],
        'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
        'no_hp' => ['nullable', 'string', 'max:20'],
        'alamat' => ['nullable', 'string'],
        'is_active' => ['nullable', 'boolean'],
    ]);

    DB::transaction(function () use ($validated) {

        $isActive = (bool) ($validated['is_active'] ?? false);

        $user = User::create([
            'name' => $validated['nama'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'password' => $validated['password'],
            'role' => 'petugas',
            'is_active' => $isActive,
        ]);

        Petugas::create([
            'user_id' => $user->id,
            'nip' => $validated['nip'] ?? null,
            'nama' => $validated['nama'],
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'is_active' => $isActive,
        ]);

    });

    return redirect()
        ->route('petugas.index')
        ->with(
            'success',
            'Data dan akun petugas berhasil dibuat.'
        );
}

    /**
     * Form edit petugas.
     */
    public function edit(Petugas $petugas)
    {
        $petugas->load('user');

        return view('master.petugas.edit', compact('petugas'));
    }

    /**
     * Update data petugas.
     */
    public function update(Request $request, Petugas $petugas)
{
    $validated = $request->validate([
        'nip' => [
            'nullable',
            'string',
            'max:50',
            Rule::unique('petugas', 'nip')->ignore($petugas->id),
        ],
        'nama' => ['required', 'string', 'max:255'],
        'username' => [
            'required',
            'string',
            'max:50',
            Rule::unique('users', 'username')->ignore($petugas->user_id),
        ],
        'email' => [
            'nullable',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($petugas->user_id),
        ],
        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
        'no_hp' => ['nullable', 'string', 'max:20'],
        'alamat' => ['nullable', 'string'],
        'is_active' => ['nullable', 'boolean'],
    ]);

    DB::transaction(function () use ($validated, $petugas) {

        $isActive = (bool) ($validated['is_active'] ?? false);

        $userData = [
            'name' => $validated['nama'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'is_active' => $isActive,
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = $validated['password'];
        }

        $petugas->user->update($userData);

        $petugas->update([
            'nip' => $validated['nip'] ?? null,
            'nama' => $validated['nama'],
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'is_active' => $isActive,
        ]);

    });

    return redirect()
        ->route('petugas.index')
        ->with('success', 'Data petugas berhasil diperbarui.');
}

    /**
     * Hapus petugas.
     */
    public function destroy(Petugas $petugas)
{
    $petugas->load('user');

    DB::transaction(function () use ($petugas) {

        $user = $petugas->user;

        $petugas->delete();

        if ($user) {
            $user->delete();
        }

    });

    return redirect()
        ->route('petugas.index')
        ->with(
            'success',
            'Data dan akun petugas berhasil dihapus.'
        );
}
}