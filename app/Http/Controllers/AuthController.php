<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan Form Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses Login
  public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    // Cari user berdasarkan username
    $user = User::where('username', $request->username)->first();

    if ($user) {
        $dbPassword = $user->password ?? $user->passwd;
        $isMatch = false;

        // 1. Cek MD5 khas SLiMS duluan (supaya gak error Bcrypt)
        if (md5($request->password) === $dbPassword || $request->password === $dbPassword) {
            $isMatch = true;
        } 
        // 2. Baru cek Bcrypt kalau password di DB diawali $2y$ (format Hash Laravel)
        elseif (str_starts_with($dbPassword, '$2y$') && Hash::check($request->password, $dbPassword)) {
            $isMatch = true;
        }

        if ($isMatch) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')
                ->with('success', 'Selamat datang, ' . ($user->realname ?? $user->username) . '!');
        }
    }

    return back()->with('error', 'Username atau password salah!')->withInput();
}

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout!');
    }
}