<?php

namespace App\Http\Controllers\server\auth;

use App\Models\Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::check()){
            return redirect()->route('PannelLoader');
        }
        return view('server.login.login');
    }
    public function submitLogin(Request $request)
    {
        $user = Admin::where('username', $request->username)->first();
        if($user){
            if(Hash::check($request->password,$user->password))
            {
                if($user->is_block){
                    $request->session()->flash('Error', 'شما بلاک شده اید و نمیتوانید وارد شوید .');
                    return back();
                }
                Auth::Login($user);
                return redirect()->route('PannelLoader');
            } else {
                $request->session()->flash('Error', 'رمز عبور وارد شده صحیح نمی باشد .');
                return back();
            }
        } else {
            $request->session()->flash('Error', ' نام کاربری وارد شده اشتباه است');
            return back();
        }
        return redirect()->route('PannelLoader');
    }
    public function LogOut()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}