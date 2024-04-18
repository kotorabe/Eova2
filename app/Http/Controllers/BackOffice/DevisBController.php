<?php

namespace App\Http\Controllers\BackOffice;

use App\Devis;
use App\Http\Controllers\Controller;
use App\Livraison;
use App\Mail\DemandeRepondu;
use App\Mail\ReductionEnvoyer;
use App\Objet;
use App\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class DevisBController extends Controller
{

    ///Attente
    public function devisAttente()
    {
        try {
            $attente = DB::table('v_list_devis')
                ->where('etat', 2)
                ->where('accept', 0)
                ->where('fini', 0)
                ->orderBy('updated_at', 'desc')
                ->get();

            return view('devis.attente', [
                'attentes' => $attente,
                'title' => 'Devis en attente',
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //Liste objet devis pas encore envoyer
    public function getAllObjetAttente($id, $id_utilisateur)
    {
        try {
            $check = Devis::where('id', $id)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('accept', 0)
                ->where('fini', 0)
                ->where('etat', 2)
                ->first();
            if ($check != null) {
                $utilisateur = DB::table('v_list_devis')
                    ->where('id', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->first();
                $objet = DB::table('v_list_objet')
                    ->where('id_devis', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->orderByRaw('CASE WHEN prix = 0 THEN 0 ELSE 1 END')
                    ->orderBy('id_taille')
                    ->get();
                $sum = DB::table('v_list_objet')
                    ->where('id_devis', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->selectRaw('SUM(total) as somme_total, SUM(quantite * kilo) as somme_poids')
                    ->first();
                $detailUtilisateur = Utilisateur::find($id_utilisateur);
                return view('devis.listeObjetDevisAttente', [
                    'id_devis' => $id,
                    'utilisateur' => $utilisateur,
                    'sum' => $sum,
                    'objets' => $objet,
                    'detailUtilisateur' => $detailUtilisateur,
                    'title' => 'Liste des objets en attente',
                ]);
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //liste objet devis envoyer
    public function getAllObjetEnvoyer($id, $id_utilisateur)
    {
        try {
            $check = Devis::where('id', $id)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('accept', 0)
                ->where('fini', 0)
                ->where('etat', 3)
                ->first();
            if ($check != null) {
                $utilisateur = DB::table('v_list_devis')
                    ->where('id', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->first();
                $objet = DB::table('v_list_objet')
                    ->where('id_devis', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->orderBy('id_taille')
                    ->get();
                $sum = DB::table('v_list_objet')
                    ->where('id_devis', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->selectRaw('SUM(total) as somme_total, SUM(quantite * kilo) as somme_poids')
                    ->first();
                // return view('devis.listeObjetDevisEnvoyer', [
                //     'id_devis' => $id,
                //     'utilisateur' => $utilisateur,
                //     'sum' => $sum,
                //     'objets' => $objet,
                //     'title' => 'Liste des objets devis envoyé',
                // ]);
                if ($utilisateur->reduction == 0) {
                    return view('devis.listeObjetDevisEnvoyer', [
                        'id_devis' => $id,
                        'objets' => $objet,
                        'sum' => $sum,
                        'utilisateur' => $utilisateur,
                        'title' => 'Liste des objets devis envoyé',
                    ]);
                } else {
                    $reduction = $utilisateur->reduction / 100;
                    return view('devis.listeObjetDevisEnvoyer', [
                        'id_devis' => $id,
                        'objets' => $objet,
                        'reduction' => $reduction,
                        'sum' => $sum,
                        'utilisateur' => $utilisateur,
                        'title' => 'Liste des objets devis envoyé',
                    ]);
                }
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getObjetAttente($id)
    {
        try {
            $objet = DB::table('v_list_objet')
                ->where('id', $id)
                ->first();
            $utilisateur = DB::table('v_list_devis')
                ->where('id', $objet->id_devis)
                ->first();
            return view('devis.addPriceToObjet', [
                'utilisateur' => $utilisateur,
                'objet' => $objet,
                'title' => 'Ajout prix',
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function addPriceToObjet(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'prix' => 'required|integer',
            ]);

            $id = $request->id;
            $prix = $request->prix;

            $objet = Objet::findOrFail($id);
            if ($objet->prix == 0) {
                $objet->update([
                    'prix' => $prix,
                    'total' => $prix * $objet->quantite,
                ]);
                $devis = Devis::where('id', $objet->id_devis)
                    ->first();
                return redirect()->route('devisb.listeObjetAttente', ['id' => $objet->id_devis, 'id_utilisateur' => $devis->id_utilisateur])->with('success_add_price', 'Prix ajouter.');
            } else {
                $objet->update([
                    'prix' => $prix,
                    'total' => $prix * $objet->quantite,
                ]);
                $devis = Devis::where('id', $objet->id_devis)
                    ->first();
                return redirect()->route('devisb.listeObjetAttente', ['id' => $objet->id_devis, 'id_utilisateur' => $devis->id_utilisateur])->with('success_updt_price', 'Prix Modifier.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'insertion.');
        }
    }

    ///Reponse devis attente
    public function sendDevis($id_devis, $id_utilisateur)
    {
        try {
            $check_devis = Devis::where('id', $id_devis)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('etat', 2)
                ->where('accept', 0)
                ->where('fini', 0)
                ->first();
            if ($check_devis != null) {
                $check = Objet::where('id_devis', $id_devis)
                    ->where('prix', 0)
                    ->count();
                if ($check == 0) {
                    $devis = Devis::findOrFail($id_devis);
                    $devis->update([
                        'etat' => 3,
                    ]);
                    $client = Utilisateur::find($id_utilisateur);
                    $detailsDuDevis = DB::table('v_list_devis')
                        ->where('id_utilisateur', $id_utilisateur)
                        ->where('etat', 3)
                        ->where('accept', 0)
                        ->where('fini', 0)
                        ->first();
                    $detail = [
                        'date_devis' => $detailsDuDevis->updated_at,
                        'demenagement' => $detailsDuDevis->date_demenagement,
                        'nom' => $client->nom,
                        'prenom' => $client->prenom
                    ];
                    Mail::to($client->email)->send(new DemandeRepondu($detail));
                    return redirect()->route('devisb.attente')->with('success_send_devis', 'Devis Bien Envoyer.');
                } else {
                    return redirect()->back()->with('null_prix', 'Un ou plusieurs objet(s) n\'a(ont) pas de prix');
                }
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }


        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //Devis repondu
    public function devisRepondu()
    {
        try {
            $accept = DB::table('v_list_devis')
                ->where('etat', 3)
                ->where('fini', 0)
                ->where('accept', 1)
                ->whereNull('deleted_at')
                ->get();
            $attente = DB::table('v_list_devis')
                ->where('etat', 0)
                ->where('fini', 0)
                ->where('accept', 0)
                ->whereNull('deleted_at')
                ->get();
            $envoyer = DB::table('v_list_devis')
                ->where('etat', 3)
                ->where('fini', 0)
                ->where('accept', 0)
                ->whereNull('deleted_at')
                ->get();
            $refus = DB::table('v_list_devis')
                ->where('etat', 0)
                ->where('fini', 1)
                ->where('accept', 0)
                ->whereNull('deleted_at')
                ->get();
            return view('devis.repondu', [
                'accepter' => $accept,
                'attentes' => $attente,
                'envoyers' => $envoyer,
                'refuser' => $refus,
                'title' => 'Devis repondu',
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //Devis attente reduction
    public function getAllObjetAttenteReduction($id_devis, $id_utilisateur)
    {
        try {
            $check_devis = Devis::where('id', $id_devis)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('etat', 0)
                ->where('accept', 0)
                ->where('fini', 0)
                ->first();
            if ($check_devis != null) {
                $objet = DB::table('v_list_objet')
                    ->where('id_devis', $id_devis)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->orderByRaw('CASE WHEN prix = 0 THEN 0 ELSE 1 END')
                    ->orderBy('id_taille')
                    ->get();
                $utilisateur = DB::table('v_list_devis')
                    ->where('id', $id_devis)
                    ->first();
                $sum = DB::table('v_list_objet')
                    ->where('id_devis', $id_devis)
                    ->selectRaw('SUM(total) as somme_total, SUM(quantite * kilo) as somme_poids')
                    ->first();
                if ($utilisateur->reduction == 0) {
                    return view('devis.listeObjetAttenteReduction', [
                        'objets' => $objet,
                        'sum' => $sum,
                        'utilisateur' => $utilisateur,
                        'title' => 'Devis en attente de réduction',
                    ]);
                } else {
                    $reduction = $utilisateur->reduction / 100;
                    return view('devis.listeObjetAttenteReduction', [
                        'objets' => $objet,
                        'reduction' => $reduction,
                        'sum' => $sum,
                        'utilisateur' => $utilisateur,
                        'title' => 'Devis en attente de réduction',
                    ]);
                }

            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //objet pour modif de prix
    public function getObjetForReduction($id)
    {
        try {
            $objet = DB::table('v_list_objet')
                ->where('id', $id)
                ->first();
            $utilisateur = DB::table('v_list_devis')
                ->where('id', $objet->id_devis)
                ->first();
            return view('devis.updtPriceToObjet', [
                'utilisateur' => $utilisateur,
                'objet' => $objet,
                'title' => 'Modif de prix',
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    //Ajouter Reduction
    public function ajouterReduction(Request $request, $id)
    {
        try {
            $request->validate([
                'reduction' => 'required|integer|min:1|max:25',
            ]);
            $devis = Devis::findOrFail($id);
            $devis->update([
                "reduction" => $request->reduction
            ]);
            if ($devis->reduction != 0) {
                return redirect()->back()->with('reduction_add', 'Réduction ajouter');
            } else {
                return redirect()->back()->with('reduction_updt', 'Réduction modifier');
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //Modif prix pour reduction
    public function updtPriceToObjet(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'prix' => 'required|integer',
            ]);

            $id = $request->id;
            $prix = $request->prix;

            $objet = Objet::findOrFail($id);
            $id_devis = $objet->id_devis;
            $objet->update([
                'prix' => $prix,
                'total' => $prix * $objet->quantite,
            ]);
            $devis = Devis::where('id', $id_devis)
                ->first();
            return redirect()->route('devisb.allObjetReduction', ['id_devis' => $objet->id_devis, 'id_utilisateur' => $devis->id_utilisateur])->with('success_updt_price', 'Prix Modifier.');

        } catch (\Exception $e) {
        }
    }

    //Envoie reduction
    public function sendReduction($id_devis, $id_utilisateur)
    {
        try {
            $check_devis = Devis::where('id', $id_devis)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('etat', 0)
                ->where('accept', 0)
                ->where('fini', 0)
                ->first();
            if ($check_devis != null) {
                $check = Objet::where('id_devis', $id_devis)
                    ->where('prix', 0)
                    ->count();
                if ($check == 0) {
                    $devis = Devis::findOrFail($id_devis);
                    $devis->update([
                        'etat' => 3,
                    ]);
                    $client = Utilisateur::find($id_utilisateur);
                    $detailsDuDevis = DB::table('v_list_devis')
                        ->where('id_utilisateur', $id_utilisateur)
                        ->where('etat', 3)
                        ->where('accept', 0)
                        ->where('fini', 0)
                        ->first();
                    $detail = [
                        'reduction' => $detailsDuDevis->reduction,
                        'date_devis' => $detailsDuDevis->updated_at,
                        'demenagement' => $detailsDuDevis->date_demenagement,
                        'nom' => $client->nom,
                        'prenom' => $client->prenom
                    ];
                    Mail::to($client->email)->send(new ReductionEnvoyer($detail));
                    return redirect()->route('devisb.repondu')->with('success_send_reduction', 'Devis Modifier Bien Envoyer.');
                } else {
                    return redirect()->back()->with('null_prix', 'Un ou plusieurs objet(s) n\'a(ont) pas de prix');
                }
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }


        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllDevisAccepter()
    {
        $accept = DB::table('v_list_devis')
            ->where('etat', 3)
            ->where('fini', 0)
            ->where('accept', 1)
            ->get();
        $title = 'Liste devis accepté';
        return view('devis.allDevisaccepter', [
            'accepter' => $accept,
            'title' => $title,
        ]);
    }

    //Redirection vers la page d'assignation d'equipe
    public function redirectionToAssignation($id)
    {
        try {
            // $devis = Devis::findOrFail($id);
            $check = Devis::where('id', $id)
                ->where('etat', 3)
                ->where('fini', 0)
                ->where('accept', 1)
                ->first();
            if ($check) {
                $utilisateur = DB::table('v_list_devis')
                    ->where('id', $id)
                    ->first();
                $objet = DB::table('v_list_objet')
                    ->where('id_devis', $id)
                    ->orderByRaw('CASE WHEN prix = 0 THEN 0 ELSE 1 END')
                    ->orderBy('id_taille')
                    ->get();
                $sum = DB::table('v_list_objet')
                    ->where('id_devis', $id)
                    ->selectRaw('SUM(total) as somme_total, SUM(quantite * kilo) as somme_poids')
                    ->first();
                $equipe = DB::table('v_list_equipe_categorie')
                    ->where('poids_total', '>=', $sum->somme_poids)
                    ->get();
                $reduction = $utilisateur->reduction / 100;
                if ($equipe != null) {
                    foreach ($equipe as $e) {
                        $dispo = Livraison::where('id_equipe', $e->id)
                            ->where('date_livraison', '=', $utilisateur->date_demenagement)
                            ->get();
                        if ($dispo->isEmpty()) {
                            // Ajoutez cette équipe à la liste finale
                            $equipesAvecDateDifferent[] = $e;
                        }
                    }
                    if (count($equipesAvecDateDifferent) > 0) {
                        // Passer les équipes à la vue
                        return view('devis.assignation', [
                            'reduction' => $reduction,
                            'utilisateur' => $utilisateur,
                            'objets' => $objet,
                            'sum' => $sum,
                            'equipes' => $equipesAvecDateDifferent,
                            'title' => 'Assignation d\'équipe'
                        ]);
                    } else {
                        // Aucune équipe disponible, définissez un message ou une variable pour l'indiquer
                        $aucuneEquipeDisponible = true;

                        // Passez la variable à la vue
                        return view('devis.assignation', [
                            'reduction' => $reduction,
                            'utilisateur' => $utilisateur,
                            'objets' => $objet,
                            'sum' => $sum,
                            'aucuneEquipeDisponible' => $aucuneEquipeDisponible,
                            'title' => 'Assignation d\'équipe'
                        ]);
                    }
                } else {
                    $aucuneEquipeDisponible = true;
                    return view('devis.assignation', [
                        'reduction' => $reduction,
                        'utilisateur' => $utilisateur,
                        'objets' => $objet,
                        'sum' => $sum,
                        'aucuneEquipeDisponible' => $aucuneEquipeDisponible,
                        'title' => 'Assignation d\'équipe'
                    ]);
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
