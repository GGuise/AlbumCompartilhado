<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->user();

            // Verifica se o usuário já existe no nosso banco pelo e-mail do Google
            $finduser = User::where('email', $googleUser->email)->first();

            if($finduser){
                // Se existe, faz o login dele
                Auth::login($finduser);
                return redirect('/admin'); // Redireciona para o painel
            }else{
                // Se não existe, cria um novo usuário no banco
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt('my-google-login-'.time()) 
                ]);

                // Atribui o cargo de Visitante
                $newUser->attachRole('visitante');

                Auth::login($newUser);
                return redirect('/admin'); 
            }

        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Ocorreu um erro ao fazer login com o Google: ' . $e->getMessage());
        }
    }
}
