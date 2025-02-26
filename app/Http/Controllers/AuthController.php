<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller{
    public function login(): View{
        return view('auth.login');
    }

    public function authenticate(Request $request): void{
        echo 'authencating';
    }
}
