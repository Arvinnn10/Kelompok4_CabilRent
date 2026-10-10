<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     * Jika sudah login, redirect sesuai role (admin -> dashboard, customer -> home).
     */
    public function showLoginForm()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user) {
            return $user->isAdmin() ? redirect('/dashboard') : redirect('/');
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi login pengguna.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username atau email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        // Cari user berdasarkan username atau email
        $user = User::where('username', $request->username)
                    ->orWhere('email', $request->username)
                    ->first();

        // Cek kecocokan password hash
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->intended('/dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'login' => 'Username atau password salah',
        ])->withInput();
    }

    /**
     * Menampilkan form pendaftaran (register).
     */
    public function showRegisterForm()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user) {
            return $user->isAdmin() ? redirect('/dashboard') : redirect('/');
        }

        return view('auth.register');
    }

    /**
     * Memproses pendaftaran user baru (role customer).
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|max:100',
            'username'     => 'required|max:50|unique:users,username',
            'email'        => 'required|email|max:100|unique:users,email',
            'no_telp'      => 'required|max:20|unique:users,no_telp',
            'password'     => 'required|min:4',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'username.required'     => 'Username wajib diisi',
            'username.unique'       => 'Username sudah terdaftar',
            'email.required'        => 'Email wajib diisi',
            'email.email'           => 'Format email tidak valid',
            'email.unique'          => 'Email sudah terdaftar',
            'no_telp.required'      => 'Nomor telepon wajib diisi',
            'no_telp.unique'        => 'Nomor telepon sudah terdaftar',
            'password.required'     => 'Password wajib diisi',
            'password.min'          => 'Password minimal 4 karakter',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'no_telp'      => $request->no_telp,
            'password'     => Hash::make($request->password),
            'role'         => 'customers',
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    /**
     * Memproses logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
