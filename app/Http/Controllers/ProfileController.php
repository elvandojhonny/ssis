<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'guru') {
            $user->load('guru');
        }

        if ($user->role === 'siswa') {
            $user->load([
                'siswa.kelas.tahunAjaran',
            ]);
        }

        return view(
            'profile.show',
            compact('user')
        );
    }


    public function edit(Request $request)
    {
        $user = $request->user();

        // Edit profil hanya tersedia untuk operator.
        abort_unless(
            $user->role === 'operator',
            403
        );

        return view(
            'profile.edit',
            compact('user')
        );
    }


    public function update(Request $request)
    {
        $user = $request->user();

        // Edit profil hanya tersedia untuk operator.
        abort_unless(
            $user->role === 'operator',
            403
        );


        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:50',

                Rule::unique(
                    'users',
                    'username'
                )->ignore($user->id),
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',

                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),
            ],

        ]);


        $user->update([
            'name' => $validated['name'],

            'username' =>
                $validated['username'],

            'email' =>
                $validated['email'] ?? null,
        ]);


        return redirect()
            ->route('profile.show')
            ->with(
                'success',
                'Profil berhasil diperbarui.'
            );
    }

    public function editPassword(Request $request)
{
    return view('profile.password', [
        'user' => $request->user(),
    ]);
}


public function updatePassword(Request $request)
{
    $validated = $request->validate([
        'current_password' => [
            'required',
            'current_password',
        ],

        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
        ],
    ], [
        'current_password.required' =>
            'Password saat ini wajib diisi.',

        'current_password.current_password' =>
            'Password saat ini tidak sesuai.',

        'password.required' =>
            'Password baru wajib diisi.',

        'password.min' =>
            'Password baru minimal 8 karakter.',

        'password.confirmed' =>
            'Konfirmasi password baru tidak sesuai.',
    ]);


    $request->user()->update([
        'password' => $validated['password'],
    ]);


    return redirect()
        ->route('profile.show')
        ->with(
            'success',
            'Password berhasil diperbarui.'
        );
}
}