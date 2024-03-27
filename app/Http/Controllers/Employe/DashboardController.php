<?php

namespace App\Http\Controllers\Employe;

use App\Equipe;
use App\Http\Controllers\Controller;
use App\Livraison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{

    public function login(Request $request)
    {
        // Validation manuelle des données de la requête
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Tentative de connexion manuelle
        $equipe = Equipe::where('email', $request->input('email'))->first();

        if ($equipe && \Hash::check($request->input('password'), $equipe->password)) {
            // Connexion réussie
            $token = $equipe->createToken($equipe->nom)->accessToken;
            return response()->json(['token' => $token, 'equipe' => $equipe]);
        } else {
            // Identifiants incorrects
            return response()->json(['error' => 'Identifiants incorrects'], 401);
        }
    }


    //Dashboard mobile
    public function index($id)
    {
        $en_cours = Livraison::where('id_equipe', $id)
            ->where('etat', 1)
            ->count();

        $data = [
            'count' => $en_cours,
        ];

        return response()->json($data);
    }
}
