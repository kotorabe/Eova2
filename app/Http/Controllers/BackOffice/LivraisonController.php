<?php

namespace App\Http\Controllers\BackOffice;

use App\Devis;
use App\Equipe;
use App\Http\Controllers\Controller;
use App\Livraison;
use App\Mail\DateAccepter;
use App\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LivraisonController extends Controller
{
    public function assignationEquipe(Request $request)
    {
        // dd($request);
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
            $client = Utilisateur::findOrFail($devis->id_utilisateur);
            $equipe = Equipe::findOrFail($request->id_equipe);
            $detail = [
                // 'date_devis' => $detailsDuDevis->created_at,
                'date_demenagement' => $request->date_livraison,
                'equipe' => $equipe->nom,
            ];
            if ($insert) {
                Mail::to($client->email)->send(new DateAccepter($detail));
                // echo 'Assigner';
                return redirect()->route('devisb.allDevisAccepter')->with('assigner', 'Déménagement assigner.');
            } else {
                // echo 'Erreur';
                return redirect()->route('devisb.allDevisAccepter')->with('error_assigne', 'Une erreur est survenue.');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function index()
    {
        $planifier = DB::table('v_list_planning_equipe')
            ->where('etat', 1)
            ->orderBy('date_livraison')
            ->get();
        $livraison = DB::table('v_list_planning_equipe')
            ->where('etat', 1)
            ->where('date_livraison', now())
            ->orderBy('id')
            ->get();
        $encours = DB::table('v_list_planning_equipe')
            ->where(function ($query) {
                $query->where('etat', 2)
                    ->orWhere('etat', 3);
            })
            ->get();
        $title = "Livraison";
        return view('livraison.liste', [
            'title' => $title,
            'planifier' => $planifier,
            'livraison' => $livraison,
            'encours' => $encours
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

    public function getSuiviLivraison($id)
    {
        $title = "Suivi livraison";
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

        return view('livraison.suiviLivraison', [
            'title' => $title,
            'detail' => $detail,
            'objets' => $objets,
            'coordonnee' => $coordonnee,
            'detailUtilisateur' => $detailUtilisateur,
            'sum' => $sum
        ]);
    }
}
