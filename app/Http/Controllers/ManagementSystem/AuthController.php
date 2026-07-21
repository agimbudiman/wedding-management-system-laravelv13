<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::guard('management')->check()) {
            return redirect()->route('management.dashboard');
        }
        return view('management_system.login.index');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'g-recaptcha-response' => [
                'required',
                function ($attribute, $value, $fail) {
                    $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => env('RECAPTCHA_SECRET_KEY'),
                        'response' => $value,
                        'remoteip' => request()->ip()
                    ]);
                    if (!$response->json('success')) {
                        $fail('Please complete the reCAPTCHA to proceed.');
                    }
                }
            ],
        ], [
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
        ]);

        unset($credentials['g-recaptcha-response']);

        if (Auth::guard('management')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('management-system/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('management')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/management-system/login');
    }
}
