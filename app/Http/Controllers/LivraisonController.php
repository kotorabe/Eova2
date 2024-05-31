<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LivraisonController extends Controller
{
    public function index()
    {
        $id_utilisateur = Session::get('id_utilisateur');
        $livraison = DB::table('v_list_planning_equipe')
            ->where('id_utilisateur', $id_utilisateur)
            ->where(function ($query) {
                $query->where('etat', 1)
                    ->orWhere('etat', 2)
                    ->orWhere('etat', 3);
            })
            ->first();
        $objets = DB::table('v_list_objet')
            ->where('id_devis', $livraison->id_devis)
            ->get();
        $utilisateur = DB::table('v_list_devis')
            ->where('id', $livraison->id_devis)
            ->first();
        $sum = DB::table('v_list_objet')
            ->where('id_devis', $livraison->id_devis)
            ->selectRaw('SUM(total) as somme_total, SUM(quantite * kilo) as somme_poids')
            ->first();
        $reduction = $utilisateur->reduction / 100;
        $title = "Livraison";
        return view('front.livraison.liste', [
            'title' => $title,
            'livraison' => $livraison,
            'objets' => $objets,
            'utilisateur' => $utilisateur,
            'reduction' => $reduction,
            'sum' => $sum
        ]);
    }
}
