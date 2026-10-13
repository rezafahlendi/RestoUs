<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Tampilkan Halaman Login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.reservations.index');
            }
            return redirect()->route('customer.home');
        }

        return view('auth.login');
    }

    /**
     * Proses Autentikasi Login (Mendukung Username atau Email)
     */
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Username atau Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginInput = $request->input('login');
        $password = $request->input('password');

        // Cek login via username atau email
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType => $loginInput,
            'password'  => $password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.reservations.index'))
                    ->with('success', 'Selamat datang di Panel Super Admin, ' . $user->name . '!');
            }

            return redirect()->intended(route('customer.home'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withInput($request->only('login'))
            ->withErrors(['login' => 'Username/Email atau Password yang Anda masukkan salah.']);
    }

    /**
     * Tampilkan Halaman Register Customer Baru
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('customer.home');
        }

        return view('auth.register');
    }

    /**
     * Proses Pendaftaran Akun Customer Baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|max:50|unique:users,username',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'username.unique' => 'Username ini sudah digunakan, silakan pilih yang lain.',
            'email.unique'    => 'Alamat email ini sudah terdaftar.',
            'password.min'    => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => strtolower($request->username),
            'email'    => strtolower($request->email),
            'phone'    => $request->phone,
            'role'     => 'customer',
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('customer.home')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di RestoUs, ' . $user->name . '.');
    }

    /**
     * Masuk Menggunakan Akun Google (Simulasi Cepat Demo Google Sign-In)
     */
    public function loginGoogleDemo()
    {
        $user = User::firstOrCreate(
            ['email' => 'pengunjung.google@gmail.com'],
            [
                'name'      => 'Pengunjung Google RestoUs',
                'username'  => 'google_guest',
                'phone'     => '081299887766',
                'role'      => 'customer',
                'google_id' => 'google_demo_12345678',
                'password'  => Hash::make(Str::random(16)),
            ]
        );

        Auth::login($user);

        return redirect()->route('customer.home')
            ->with('success', 'Berhasil masuk dengan Akun Google (' . $user->email . ')!');
    }

    /**
     * Logout Keluar Akun
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.home')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
