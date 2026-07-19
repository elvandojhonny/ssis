<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }


    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],
        ], [
            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.exists' =>
                'Email tidak terdaftar di dalam sistem.',
        ]);


        $status = Password::sendResetLink(
    $request->only('email')
);

if ($status === Password::RESET_LINK_SENT) {
    return back()->with(
        'success',
        'Link reset password telah dikirim ke email Anda.'
    );
}

return back()->withErrors([
    'email' => __($status),
]);
    }
}