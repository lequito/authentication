<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use function Laravel\Prompts\password;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function authenticate(Request $request){
        // validate form
        $credentials = $request->validate(
            [
                'username' => 'required | min:3 | max:30',
                'password' => 'required | min:8 | max:32 | regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            [
                'username.required' => 'O nome do usuário é obrigatório',
                'username.min'      => 'O usuário deve ter no mínimo :min caracteres',
                'username.max'      => 'O usuário deve ter no mínimo :max caracteres',
                'password.required' => 'A senha é obrigatória',
                'password.min'      => 'A senha deve ter no mínimo :min caracteres',
                'password.max'      => 'A senha deve ter no máximo :max caracteres',
                'password.regex'    => 'A senha deve ter pelo menos uma letra maiúscula, uma minúscula e um número'
            ]
        );

        // verify if user exisits
        $user = User::where('username', $credentials['username'])
            ->where('active', true)
            ->where(function ($query) {
                $query->whereNull('blocked_until')
                    ->orWhere('blocked_until', '<=', now());
            })
            ->whereNotNull('email_verified_at')
            ->whereNull('deleted_at')
            ->first();
        if (!$user) {
            return back()->withInput()->with([
                'invalide_login' => 'Login inválido.'
            ]);
        }
         
        // verify if the password is valid
        if(!password_verify($credentials['password'], $user->password)){
            return back()->withInput()->with([
                'invalide_login' => 'Login inválido.'
            ]);
        }

        // update the last login
        $user->last_login_at = now();
        $user->blocked_until = null;
        $user->save();

        // login
        $request->session()->regenerate();
        Auth::login($user);

        //redirect
        return redirect()->intended(route('home'));


    }
}
