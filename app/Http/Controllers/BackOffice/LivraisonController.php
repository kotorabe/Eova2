<?php

namespace App\Http\Controllers\BackOffice;

use App\Devis;
use App\Http\Controllers\Controller;
use App\Livraison;
use App\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LivraisonController extends Controller
{
    public function assignationEquipe(Request $request)
    {
        try {
            $request->validate([
                'id_devis' => 'required|integer',
                'id_equipe' => 'required|integer',
                'date_livraison' => 'required|date'
            ]);
            $devis = Devis::findOrFail($request->id_devis);
            $devis->update([
                'etat' => 4
            ]);
            $insert = Livraison::create([
                'id_devis' => $request->id_devis,
                'id_equipe' => $request->id_equipe,
                'date_livraison' => $request->date_livraison
            ]);
            if ($insert) {
                echo 'Assigner';
            } else {
                echo 'Erreur';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function index()
    {
        $planifier = DB::table('v_list_planning_equipe')
            ->where('etat', 1)
            ->get();
        $title = "Livraison";
        return view('livraison.liste', [
            'title' => $title,
            'planifier' => $planifier
        ]);
    }

    public function getDetailPlanifier($id)
    {
        $title = "Details";
        $detail = DB::table('v_list_planning_equipe')
            ->where('id', $id)
            ->first();
        $objets = DB::table('v_list_objet')
            ->where('id_devis', $detail->id_devis)
            ->get();
        $coordonnee = DB::table('v_list_devis')
            ->where('id', $detail->id_devis)
            ->first();
        $detailUtilisateur = Utilisateur::find($detail->id_utilisateur);
        $sum = DB::table('v_list_objet')
            ->where('id_devis', $detail->id_devis)
            ->selectRaw('SUM(total) as somme_total, SUM(quantite * kilo) as somme_poids')
            ->first();
        return view('livraison.detailPlan', [
            'title' => $title,
            'detail' => $detail,
            'objets' => $objets,
            'coordonnee' => $coordonnee,
            'detailUtilisateur' => $detailUtilisateur,
            'sum' => $sum
        ]);
    }
}
