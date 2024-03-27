<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Utilisateur;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UtilisateurController extends Controller
{


    public function landing()
    {
        if(Session::get('id_devis') != null){
            Session::forget('id_devis');
        }
        return view('front.eova_trano.landing', [
            'title' => 'Bienvenue sur E-ova Trano',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        //dd($credentials);
        if (Auth::guard('utilisateur')->attempt($credentials)) {
            $utilisateur = Auth::guard('utilisateur')->user();
            //dd($utilisateur);
            Session::put('id_utilisateur', $utilisateur->id);
            return redirect('/utilisateur/landing');
        }

        return back()->withErrors(['email' => 'Veuillez vérifier vos identifiants!']);
    }
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|min:4',
            'prenom' => 'required|string|min:4',
            'number' => 'required|min:10|max:10',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'numero' => $request->number,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // Connectez automatiquement le nouvel employé
        Auth::guard('utilisateur')->login($utilisateur);
        $utilisateur = Auth::guard('utilisateur')->user();
        Session::put('id_utilisateur', $utilisateur->id);
        // Rediriger l'employé vers la page souhaitée après l'inscription
        return redirect('/utilisateur/landing');
    }

    public function logout()
    {
        Auth::guard('utilisateur')->logout();
        //dd(Auth::guard('utilisateur'));
        Session::flush();
        //dd(10);
        // Redirigez les employés après la déconnexion
        return redirect('/');
    }
}
