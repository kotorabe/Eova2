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

    public function dash($id, $mois)
    {
        $anneeActuelle = date('Y');
        $total = Livraison::where('id_equipe', $id)
            ->whereMonth('date_livraison', '=', $mois)
            ->whereYear('date_livraison', '=', $anneeActuelle)
            ->count();

        $effectuer = Livraison::where('id_equipe', $id)
            ->whereMonth('date_livraison', '=', $mois)
            ->whereYear('date_livraison', '=', $anneeActuelle)
            ->where('etat', 3)
            ->count();

        $reste = abs($total - $effectuer);

        $equipe = Equipe::where('id', $id)
            ->first();

        $data = [
            ['id' => 1, 'label' => 'Total', 'val' => $total, 'img' => './asset/img/checklist.png'],
            ['id' => 2, 'label' => 'Effectué', 'val' => $effectuer, 'img' => './asset/img/checklist.png'],
            ['id' => 3, 'label' => 'Reste', 'val' => $reste, 'img' => './asset/img/checklist.png']
        ];

        return response()->json($data);
    }

    public function getDataEquipe($id)
    {
        $equipe = Equipe::where('id', $id)
            ->first();

        $data = [
            'about' => $equipe,
        ];

        return response()->json($data);
    }
}
