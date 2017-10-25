<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use auth;

class DashboardController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('dashboard');
    }

    public function logout() {
        Auth::logout();
        return redirect()->intended('login');
    }

}
