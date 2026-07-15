<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')
            ->latest()
            ->paginate(10);

        return view('master.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('master.guru.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => ['nullable', 'string', 'max:50', 'unique:guru,nip'],
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
                'role' => 'guru',
                'is_active' => $isActive,
            ]);

            Guru::create([
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
            ->route('guru.index')
            ->with('success', 'Data dan akun guru berhasil dibuat.');
    }

    public function edit(Guru $guru)
    {
        $guru->load('user');

        return view('master.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nip' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('guru', 'nip')->ignore($guru->id),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($guru->user_id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($guru->user_id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $guru) {
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

            $guru->user->update($userData);

            $guru->update([
                'nip' => $validated['nip'] ?? null,
                'nama' => $validated['nama'],
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'is_active' => $isActive,
            ]);
        });

        return redirect()
            ->route('guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        DB::transaction(function () use ($guru) {
            // user_id menggunakan cascadeOnDelete,
            // jadi profil guru ikut terhapus.
            $guru->user->delete();
        });

        return redirect()
            ->route('guru.index')
            ->with('success', 'Data dan akun guru berhasil dihapus.');
    }
}