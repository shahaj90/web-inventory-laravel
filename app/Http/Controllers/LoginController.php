<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use auth;
use App\Confiq;

class LoginController extends Controller {

    public function login() {
        $company_data = Confiq::first();
        return view('login', ['data' => $company_data]);
    }

    public function checkLogin(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password'], 'is_active' => 0])) {
            return redirect('login')->with('loginError', 'Your Account is not Approved');
        } elseif (Auth::attempt(['email' => $request['email'], 'password' => $request['password'], 'is_active' => 1])) {
            return redirect()->intended('dashboard');
        } elseif (Auth::attempt(['email' => $request['email'], 'password' => $request['password'], 'is_active' => 2])) {
            return redirect('login')->with('loginError', 'Your Account is Deactive');
        } elseif (Auth::attempt(['email' => $request['email'], 'password' => $request['password'], 'is_active' => 3])) {
            return redirect('login')->with('loginError', 'Your Account is Suspend');
        } else {
            return redirect('login')->with('loginError', 'Email or Password Error!');
        }
    }

}
