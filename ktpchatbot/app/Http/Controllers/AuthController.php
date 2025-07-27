<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterFormUser()
    {
        return view('auth.register-user');
    }

    public function showRegisterFormAdmin()
    {
        return view('auth.register-admin');
    }

    public function register(Request $request)
    {
        // Cek apakah ini admin atau user
        $isAdmin = $request->routeIs('admin.register.store');

        $rules = [
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];

        $messages = [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];

        // Jika bukan admin, wajib semua field
        if (!$isAdmin) {
            $rules = array_merge($rules, [
                'nik' => ['required', 'unique:users,nik'],
                'name' => ['required', 'string', 'max:255', 'unique:users,name'],
                'phone' => ['required', 'string', 'min:11', 'max:13', 'unique:users,phone'],
            ]);

            $messages = array_merge($messages, [
                'nik.required' => 'NIK wajib diisi.',
                'nik.unique' => 'NIK sudah terdaftar.',
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.max' => 'Nama maksimal 255 karakter.',
                'name.unique' => 'Nama sudah digunakan.',
                'phone.required' => 'Nomor HP wajib diisi.',
                'phone.min' => 'Nomor HP minimal 11 karakter.',
                'phone.max' => 'Nomor HP maksimal 13 karakter.',
                'phone.unique' => 'Nomor HP sudah digunakan.',
            ]);
        }

        $validated = $request->validate($rules, $messages);

        User::create([
            'nik' => $isAdmin ? '' : $validated['nik'],
            'name' => $validated['name'] ?? 'Admin',
            'email' => $validated['email'],
            'phone' => $isAdmin ? '' : $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $isAdmin ? 'admin' : 'user',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->username;
        $password = $request->password;

        // Jika username berupa email
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $username, 'password' => $password];

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                $user = Auth::user();

                if ($user->role === 'admin') {
                    return redirect()->intended('/panel-admin/dashboard');
                } elseif ($user->role === 'user') {
                    return redirect()->intended('/user/dashboard');
                } else {
                    Auth::logout();
                    return back()->withErrors([
                        'username' => 'Role tidak dikenali.',
                    ])->onlyInput('username');
                }
            }

            return back()->withErrors([
                'username' => 'Email atau password salah.',
            ])->onlyInput('username');
        }

        // Jika input bukan email, coba login user dengan NIK
        $credentials = ['nik' => $username, 'password' => $password];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'user') {
                return redirect()->intended('/user/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'NIK hanya digunakan untuk login user.',
                ])->onlyInput('username');
            }
        }

        return back()->withErrors([
            'username' => 'Email/NIK atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
