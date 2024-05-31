<?php

namespace App\Http\Controllers\BackOffice;

use App\Addresse;
use App\Devis;
use App\Http\Controllers\Controller;
use App\Livraison;
use App\Mail\DateIndisponible;
use App\Mail\DemandeRepondu;
use App\Mail\ReductionEnvoyer;
use App\Objet;
use App\Utilisateur;
use Carbon\Carbon;
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
                        'date_devis' => $detailsDuDevis->created_at,
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
                $equipesAvecDateDifferent = [];
                if ($equipe->isNotEmpty()) {
                    foreach ($equipe as $e) {
                        $dispo = Livraison::where('id_equipe', $e->id)
                            ->where('date_livraison', '=', $utilisateur->date_demenagement)
                            ->get();
                        if ($dispo->isEmpty()) {
                            $equipesAvecDateDifferent[] = $e;
                        }
                    }
                    if (count($equipesAvecDateDifferent) > 0) {
                        return view('devis.assignation', [
                            'reduction' => $reduction,
                            'utilisateur' => $utilisateur,
                            'objets' => $objet,
                            'sum' => $sum,
                            'equipes' => $equipesAvecDateDifferent,
                            'title' => 'Assignation d\'équipe',
                            'equipeDisponible' => []
                        ]);
                    } else {
                        // Aucune équipe disponible, définissez un message ou une variable pour l'indiquer
                        // $aucuneEquipeDisponible = true;
                        $disponible = [];
                        $aucuneEquipeDisponible = true;
                        $equipe_dispo = DB::table('v_list_equipe_categorie')
                            ->where('poids_total', '>=', $sum->somme_poids)
                            ->get();

                        // $daty = $utilisateur->date_demenagement;
                        $daty = Carbon::createFromFormat('Y-m-d', $utilisateur->date_demenagement);
                        while (true) {
                            $daty->addDay();
                            if ($daty->isSunday()) {
                                continue;
                            }
                            foreach ($equipe_dispo as $ed) {
                                $dispo = Livraison::where('id_equipe', $ed->id)
                                    ->where('date_livraison', '=', $daty)
                                    ->first();
                                if ($dispo === null) {
                                    $equipesAvecDateDifferent = $ed;
                                    $date_d = $daty;
                                    break 2;
                                } else {
                                    continue;
                                }
                            }
                            if ($dispo != null) {
                                continue;
                            }
                        }

                        // Passez la variable à la vue
                        return view('devis.assignation', [
                            'reduction' => $reduction,
                            'utilisateur' => $utilisateur,
                            'objets' => $objet,
                            'sum' => $sum,
                            'equipeDisponible' => $equipesAvecDateDifferent,
                            'daty' => $date_d,
                            'title' => 'Assignation d\'équipe',
                            'equipes' => []
                        ]);
                    }
                } else {
                    $disponible = [];
                    $aucuneEquipeDisponible = true;
                    $equipe_dispo = DB::table('v_list_equipe_categorie')
                        ->where('poids_total', '>=', $sum->somme_poids)
                        ->get();
                    // $daty = $utilisateur->date_demenagement;
                    $daty = Carbon::createFromFormat('Y-m-d', $utilisateur->date_demenagement);
                    while (true) {
                        $daty->addDay();
                        if ($daty->isSunday()) {
                            continue;
                        }
                        foreach ($equipe_dispo as $ed) {
                            $dispo = Livraison::where('id_equipe', $ed->id)
                                ->where('date_livraison', '=', $daty)
                                ->first();
                            if ($dispo === null) {
                                $disponible = $ed;
                                $date_d = $daty;
                                break 2;
                            } else {
                                continue;
                            }
                        }
                        // if ($dispo != null) {
                        //     continue;
                        // }
                    }
                    return view('devis.assignation', [
                        'reduction' => $reduction,
                        'utilisateur' => $utilisateur,
                        'objets' => $objet,
                        'sum' => $sum,
                        'equipeDisponible' => $disponible,
                        'daty' => $date_d,
                        'title' => 'Assignation d\'équipe',
                        'equipes' => []
                    ]);
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function askDateChange(Request $request)
    {
        try {
            $request->validate([
                'id_equipe' => 'required|integer',
                'id_devis' => 'required|integer',
            ]);

            $devis = Devis::findOrFail($request->id_devis);
            $addresses = DB::table('addresses')
                ->where('id_devis', $request->id_devis)
                ->first();

            $detail = [
                'date_demenagement' => $addresses->date_demenagement,
                'date_dispo' => $request->date_livraison
            ];

            $devis->update([
                'etat' => 5,
                'accept' => 0
            ]);

            $insert = Livraison::create([
                'id_devis' => $request->id_devis,
                'id_equipe' => $request->id_equipe,
                'date_livraison' => $request->date_livraison,
                'etat' => 5
            ]);

            if ($insert) {
                Mail::to($request->email)->send(new DateIndisponible($detail));
                return redirect()->route('devisb.allDevisAccepter')->with('send_date', 'Date disponible envoyer.');
            } else {
                return redirect()->route('devisb.allDevisAccepter')->with('error_send', 'Une erreur est survenue.');
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
