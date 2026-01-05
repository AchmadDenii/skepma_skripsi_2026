<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(){
        return view('auth.login');
    }

    public function username()
    {
        return 'username';
    }


    public function login(Request $request){
        $request->validate([
            'login' => 'required',
            'password' => 'required|min:8',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL)
        ? 'email' : 'username';
        
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            //LOGIKA LOGIN MULTI ROLE
            switch ($user->role){
                case 'mahasiswa':
                    return redirect('/mahasiswa/dashboard');
                case 'dosen':
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
            'login'=>'Username/Email atau Password Salah',
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
