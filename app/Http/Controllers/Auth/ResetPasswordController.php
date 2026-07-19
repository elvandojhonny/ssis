<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function showResetForm(
        Request $request,
        string $token
    ) {
        return view(
            'auth.reset-password',
            [
                'token' => $token,
                'email' => $request->email,
            ]
        );
    }


    public function reset(Request $request)
    {
        $validated = $request->validate([
            'token' => [
                'required',
            ],

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        $status = Password::reset(
            $validated,

            function ($user, $password) {

                $user->forceFill([
                    'password' => $password,
                ]);

                $user->setRememberToken(
                    Str::random(60)
                );

                $user->save();


                event(
                    new PasswordReset($user)
                );
            }
        );


        if ($status === Password::PASSWORD_RESET) {

            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Password berhasil diubah. Silakan masuk menggunakan password baru.'
                );

        }


        return back()
            ->withErrors([
                'email' =>
                    'Link reset password tidak valid atau telah kedaluwarsa.',
            ]);
    }
}