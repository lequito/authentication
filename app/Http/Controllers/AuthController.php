<?php

namespace App\Http\Controllers;

use App\Mail\NewUserConfirmation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Support\Str;

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
                'invalid_login' => 'Login inválido.'
            ]);
        }
         
        // verify if the password is valid
        if(!password_verify($credentials['password'], $user->password)){
            return back()->withInput()->with([
                'invalid_login' => 'Login inválido.'
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

    public function logout() : RedirectResponse {
        Auth::logout();
        return redirect()->route('login');
    }

    public function register(): View{
       return view('auth.register');
    }

    public function storeUser(Request $request): RedirectResponse | View{
         // validate form
         $request->validate(
            [
                'username'               => 'required|min:3|max:30|unique:users,username',
                'email'                  => 'required|email|unique:users,email',
                'password'               => 'required|min:8|max:32|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'password_confirmation'  => 'required|same:password'
            ],
            [
                'username.required'              => 'O nome do usuário é obrigatório',
                'username.min'                   => 'O usuário deve ter no mínimo :min caracteres',
                'username.max'                   => 'O usuário deve ter no mínimo :max caracteres',
                'username.unique'                => 'Este nome não pode ser usado',
                'email.unique'                   => 'Este Email não pode ser usado',
                'email.email'                    => 'O email deve ser um endereço de email válido',
                'email.unique'                   => 'Este email não pode ser usado',
                'password.required'              => 'A senha é obrigatória',
                'password.min'                   => 'A senha deve ter no mínimo :min caracteres',
                'password.max'                   => 'A senha deve ter no máximo :max caracteres',
                'password.regex'                 => 'A senha deve ter pelo menos uma letra maiúscula, uma minúscula e um número',
                'password_confirmation_required' => 'A confirmação de senha é obrigatória',
                'password_confirmation_same'     => 'A confirmação de senha deve ser igual à senha'
            ]
         );
         
         //creating a new user and setting an email verification token
         $user = new User();
         $user->username = $request->username;
         $user->email    = $request->email;
         $user->password = bcrypt($request->password);
         $user->token    = Str::random(64);

         // generate link
         $confirmation_link = route('new_user_confirmation', ['token' => $user->token]);

         //send email
         $result = Mail::to($user->email)->send(new NewUserConfirmation($user->username, $confirmation_link));

         //check if the email was sent
         if(!$result){
            return back()->withInput()->with([
                'server_error' => 'Ocorreu um erro ao enviar o email de confirmação.'
            ]);
         }

         //create user in database
         $user->save();

         // display creation success view
         return view('auth.email_sent', ['email' => $user->email]);
    }
    
    public function new_user_confirmation($token){
        echo 'new user confirmation';
    }
}
