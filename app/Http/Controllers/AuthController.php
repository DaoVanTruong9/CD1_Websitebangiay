<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(){
    if(Auth::check()){
        $role = Auth::user()->role;

        if($role == 'admin'){
            return redirect('/dashboard');
        }

        if($role == 'staff'){
            return redirect('/staff/dashboard');
        }

        return redirect('/');
    }

    return view('auth.login');
    }

    public function showRegister(){
        return view('auth.register');
    }

    public function login(Request $request){

    if(Auth::attempt([
        'email' => $request->email,
        'password' => $request->password
    ])){
        $request->session()->regenerate();

        // PHÂN QUYỀN
        $role = Auth::user()->role;

        if($role == 'admin'){
            return redirect('/admin/dashboard');
        }

        if($role == 'staff'){
            return redirect('/staff/dashboard');
        }

        if($role == 'user'){
            return redirect('/');
        }
    }

    return back()->with('error','Sai tài khoản hoặc mật khẩu');
}

    public function register(Request $request){

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6'
    ]);

    //PHÂN QUYỀN
    $role = $request->email === 'admin@gmail.com' ? 'admin' : 'user';

    \App\Models\User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>bcrypt($request->password),
        'role'=>$role
    ]);

    return redirect('/login')->with('success','Đăng ký thành công!');
}

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}