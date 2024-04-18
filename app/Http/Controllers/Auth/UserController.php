<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        //dd($credentials);
        if (Auth::guard()->attempt($credentials)) {
            $user = Auth::guard()->user();
            //dd($user);
            //Session::put('id_utilisateur', $utilisateur->id);
            //return redirect('/utilisateur/landing');
            return redirect('admin123456/home');
        }

        return back()->withErrors(['email' => 'Veuillez vérifier vos identifiants!']);
    }

    public function register(Request $request)
    {
        $utilisateur = User::create([
            'name' => $request->nom,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // Connectez automatiquement le nouvel employé
        Auth::guard()->login($utilisateur);
        //$utilisateur = Auth::guard()->user();
        //Session::put('id_utilisateur', $utilisateur->id);
        // Rediriger l'employé vers la page souhaitée après l'inscription
        //return redirect('/utilisateur/landing');
        return redirect('admin123456/home');
    }

    public function logout()
    {
        Auth::guard()->logout();
        //dd(Auth::guard('utilisateur'));
        Session::flush();
        //dd(10);
        // Redirigez les employés après la déconnexion
        return redirect('/admin123456');
    }
}
