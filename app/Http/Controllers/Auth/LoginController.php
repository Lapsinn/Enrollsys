<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show(): \Illuminate\View\View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'in:student,admin'],
        ]);

        $remember = $request->boolean('remember');

        // login.blade.php's student tab accepts either email or student number,
        // so try both columns.
        $loginField = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'student_number';

        $attempt = Auth::attempt([
            $loginField => $credentials['email'],
            'password' => $credentials['password'],
            'role' => $credentials['role'],
        ], $remember);

        if (! $attempt) {
            return back()
                ->withErrors(['email' => 'These credentials do not match our records, or this account does not have ' . $credentials['role'] . ' access.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return $credentials['role'] === 'admin'
            ? redirect()->intended('/admin/forms')
            : redirect()->intended('/forms');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}