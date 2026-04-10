<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function username()
    {
        return 'username';
    }


    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required|min:8',
        ]);

        $login = $request->login;
        $password = $request->password;

        // Cek apakah login adalah NPM (cari di tabel mahasiswa)
        $userFromNpm = Mahasiswa::where('npm', $login)->first();

        if ($userFromNpm) {
            // Login menggunakan user_id yang ditemukan
            $credentials = ['id' => $userFromNpm->user_id, 'password' => $password];
        } else {
            // Cek apakah login berupa email atau username
            $loginType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $credentials = [$loginType => $login, 'password' => $password];
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            // redirect sesuai role
            switch ($user->role) {
                case 'mahasiswa':
                    return redirect('/mahasiswa/dashboard');
                case 'dosen_wali':
                    return redirect('/dosen/dashboard');
                case 'kaprodi':
                    return redirect('/kaprodi/dashboard');
                case 'admin':
                    return redirect('/admin/dashboard');
                default:
                    Auth::logout();
                    return redirect('/login');
            }
        }

        return back()->withErrors([
            'login' => 'NPM/Username/Email atau Password Salah',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
