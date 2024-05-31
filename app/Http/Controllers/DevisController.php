<?php

namespace App\Http\Controllers;

use App\Addresse;
use App\Devis;
use App\Equipe;
use App\Livraison;
use App\Mail\DateAccepter;
use App\Mail\DateRefuser;
use App\Mail\DevisAccepte;
use App\Mail\DevisAttenteReduction;
use App\Mail\RefusDevis;
use App\Mail\SupprimerDevis;
use App\Objet;
use App\Taille;
use App\Type_objet;
use App\Utilisateur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Stmt\TryCatch;

class DevisController extends Controller
{

    public function new_addresse()
    {
        if (Session::get('id_devis') != null) {
            Session::forget('id_devis');
        }
        if (Session::get('recuperation') != null) {
            Session::forget('recuperation');
            Session::forget('livraison');
            Session::forget('coord_recup');
            Session::forget('acces_recup');
            Session::forget('acces_livr');
            Session::forget('coord_livr');
            Session::forget('date_demenagement');
        }
        $check = DB::table('devis')
            ->where('id_utilisateur', Session::get('id_utilisateur'))
            ->where('fini', 0)
            ->first();
        if ($check == null) {
            return view('front.devis.address', [
                'title' => 'New address'
            ]);
        } else {
            return redirect()->back()->with('exist_devis', 'Vous avez déja une demande en cours !');
        }


    }
    public function redirection(Request $request)
    {
        // $rq = $request;
        $recuperation = $request->recuperation;
        $acces_recup = $request->boolean('access_recup');
        $coord_recup = $request->coordinates_recup;
        $livraison = $request->livraison;
        $acces_livr = $request->boolean('acces_livr');
        $coord_livr = $request->coordinates;
        $date = $request->date_demenagement;
        $today = Carbon::now();
        $datePlus4Days = $today->copy()->addDays(6);
        if ($date < $datePlus4Days) {
            return redirect()->back()->with('erreur_date', 'La date entrée est trop proche ou déja passée');
        } else {
            $check = DB::table('devis')
                ->where('id_utilisateur', Session::get('id_utilisateur'))
                ->where('etat', 1)
                ->where('accept', 0)
                ->where('fini', 0)
                ->first();
            if ($check == null) {
                $insert_devis = Devis::insertGetId([
                    'id_utilisateur' => Session::get('id_utilisateur'),
                    'created_at' => now()
                ]);
                if ($insert_devis) {
                    Session::put('id_devis', $insert_devis);
                    Session::put('recuperation', $recuperation);
                    Session::put('acces_recup', $acces_recup);
                    Session::put('coord_recup', $coord_recup);
                    Session::put('livraison', $livraison);
                    Session::put('acces_livr', $acces_livr);
                    Session::put('coord_livr', $coord_livr);
                    Session::put('date_demenagement', $date);
                    $taille = Taille::all();
                    $type = Type_objet::all();
                    return view('front.devis.new', [
                        'tailles' => $taille,
                        'types' => $type,
                        'title' => 'Insertion d\' objet',
                    ]);
                } else {
                    return redirect()->back()->with('erreur_devis', 'Un problème est survenu');
                }
            } else {
                $id = $check->id;
                Session::put('id_devis', $id);
                Session::put('recuperation', $recuperation);
                Session::put('acces_recup', $acces_recup);
                Session::put('coord_recup', $coord_recup);
                Session::put('livraison', $livraison);
                Session::put('acces_livr', $acces_livr);
                Session::put('coord_livr', $coord_livr);
                Session::put('date_demenagement', $date);
                $taille = Taille::all();
                $type = Type_objet::all();
                return view('front.devis.new', [
                    'tailles' => $taille,
                    'types' => $type,
                    'title' => 'Insertion d\' objet',
                ]);
            }
        }
        //dd($request->boolean('access_recup'));

    }


    public function CreateDevis(Request $request)
    {
        try {
            DB::beginTransaction();
            Addresse::create([
                'id_devis' => Session::get('id_devis'),
                'recuperation' => Session::get('recuperation'),
                'livraison' => Session::get('livraison'),
                'coord_recup' => Session::get('coord_recup'),
                'acces_recup' => Session::get('acces_recup'),
                'acces_livr' => Session::get('acces_livr'),
                'coord_livr' => Session::get('coord_livr'),
                'date_demenagement' => Session::get('date_demenagement'),
            ]);

            $request->validate([
                'type' => 'required|array',
                'objet' => 'required|array',
                'quantite' => 'required|array',
                'taille' => 'required|array',
                'poids' => 'required|array',
            ]);

            $types = $request->input('type');
            $objets = $request->input('objet');
            $quantites = $request->input('quantite');
            $tailles = $request->input('taille');
            $poids = $request->input('poids');

            foreach ($types as $key => $type) {
                Objet::create([
                    'id_devis' => Session::get('id_devis'),
                    'id_taille' => $tailles[$key],
                    'id_type' => $type,
                    'nom' => $objets[$key],
                    'quantite' => $quantites[$key],
                    'kilo' => $poids[$key]
                ]);
            }
            $devis = Devis::findOrFail(Session::get('id_devis'));
            $devis->update([
                'created_at' => now(),
                'etat' => 2
            ]);
            DB::commit();
            Session::forget('id_devis');
            Session::forget('recuperation');
            Session::forget('livraison');
            Session::forget('acces_recup');
            Session::forget('acces_livr');
            Session::forget('date_demenagement');

            // Redirection avec un message de succès
            return redirect()->route('utilisateur.landing')->with('success', 'Demande de devis bien envoyé.');
        } catch (\Exception $e) {
            // En cas d'erreur, annule la transaction
            DB::rollBack();

            // Vous pouvez également journaliser l'erreur ou la gérer d'une autre manière
            return redirect()->route('utilisateur.landing')->with('error', 'Une erreur est survenue lors de l\'insertion.');
        }
    }

    public function ToaddObjet($id)
    {
        try {
            $check = Devis::where('id', $id)
                ->where('id_utilisateur', Session::get('id_utilisateur'))
                ->where('accept', 0)
                ->where('fini', 0)
                ->where('etat', 2)
                ->first();
            if ($check != null) {
                $taille = Taille::all();
                $type = Type_objet::all();
                return view('front.devis.ajoutObjet', [
                    'tailles' => $taille,
                    'types' => $type,
                    'id_devis' => $check->id,
                    'title' => 'Ajout d\'objet',
                ]);
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function addObjet(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|array',
                'objet' => 'required|array',
                'quantite' => 'required|array',
                'taille' => 'required|array',
                'poids' => 'required|array',
            ]);

            // Récupérer les données du formulaire
            $types = $request->input('type');
            $objets = $request->input('objet');
            $quantites = $request->input('quantite');
            $tailles = $request->input('taille');
            $poids = $request->input('poids');

            // Parcourir les données et les enregistrer dans la base de données
            foreach ($types as $key => $type) {
                Objet::create([
                    'id_devis' => $request->id_devis,
                    'id_taille' => $tailles[$key],
                    'id_type' => $type,
                    'nom' => $objets[$key],
                    'quantite' => $quantites[$key],
                    'kilo' => $poids[$key],
                    // Ajoutez d'autres champs si nécessaire
                ]);
            }

            return redirect()->route('devis.listeObjetAttente', ['id' => $request->id_devis])->with('success_add', 'Objet(s) Ajouté(s).');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function UpdateObjet(Request $request)
    {
        try {
            $objet = Objet::findOrFail($request->id);
            $objet->update([
                'id_taille' => $request->taille,
                'id_type' => $request->type,
                'nom' => $request->objet,
                'quantite' => $request->quantite,
                'kilo' => $request->poids
            ]);
            return redirect()->route('devis.listeObjetAttente', ['id' => $objet->id_devis])->with('success_update', 'Objet modifier.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteObjet($id)
    {
        try {
            $objet = Objet::findOrFail($id);
            $id_devis = $objet->id_devis;
            $objet->delete();
            return redirect()->route('devis.listeObjetAttente', ['id' => $id_devis])->with('success_delete', 'Objet Supprimer.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllObjetAttente($id)
    {
        try {
            $check = Devis::where('id', $id)
                ->where('id_utilisateur', Session::get('id_utilisateur'))
                ->where('accept', 0)
                ->where('fini', 0)
                ->where('etat', 2)
                ->first();
            if ($check != null) {
                $objet = DB::table('v_list_objet')
                    ->where('id_devis', $id)
                    ->orderByRaw('CASE WHEN prix = 0 THEN 0 ELSE 1 END')
                    ->orderBy('id_taille')
                    ->get();
                $sum = DB::table('v_list_objet')
                    ->where('id_devis', $id)
                    ->where('id_utilisateur', Session::get('id_utilisateur'))
                    ->selectRaw('SUM(total) as somme_total, SUM(quantite * kilo) as somme_poids')
                    ->first();
                return view('front.devis.listeObjetAttente', [
                    'id_devis' => $id,
                    'objets' => $objet,
                    'sum' => $sum,
                    'title' => 'Liste des objets en attente',
                ]);
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
            $taille = Taille::all();
            $type = Type_objet::all();
            $objet = DB::table('v_list_objet')
                ->where('id', $id)
                ->first();
            return view('front.devis.modifObjet', [
                'tailles' => $taille,
                'types' => $type,
                'objet' => $objet,
                'title' => 'Modification',
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function devisAttente()
    {
        try {
            if (Session::get('id_devis') != null) {
                Session::forget('id_devis');
            }
            if (Session::get('recuperation') != null) {
                Session::forget('recuperation');
                Session::forget('livraison');
                Session::forget('acces_recup');
                Session::forget('acces_livr');
                Session::forget('date_demenagement');
            }
            $attente = DB::table('v_list_devis')
                ->where('id_utilisateur', Session::get('id_utilisateur'))
                ->where('etat', 2)
                ->where('accept', 0)
                ->where('fini', 0)
                ->first();
            return view('front.devis.attente', [
                'attente' => $attente,
                'title' => 'Devis en attente',
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    //Devis répondu
    public function devisRepondu()
    {
        try {
            $devis_recu = DB::table('v_list_devis')
                ->where('id_utilisateur', Session::get('id_utilisateur'))
                ->where('etat', 3)
                ->where('fini', 0)
                ->where('accept', 0)
                ->first();
            $attente = DB::table('v_list_devis')
                ->where('id_utilisateur', Session::get('id_utilisateur'))
                ->where('etat', 0)
                ->where('fini', 0)
                ->where('accept', 0)
                ->first();
            $refus = DB::table('v_list_devis')
                ->where('id_utilisateur', Session::get('id_utilisateur'))
                ->where('etat', 0)
                ->where('fini', 1)
                ->where('accept', 0)
                ->first();
            return view('front.devis.repondu', [
                'devis_recu' => $devis_recu,
                'attente' => $attente,
                'refuser' => $refus,
                'title' => 'Devis repondu',
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getDevisRecu($id)
    {
        try {
            $id_utilisateur = Session::get('id_utilisateur');
            $check = Devis::where('id', $id)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('accept', 0)
                ->where('fini', 0)
                ->where('etat', 3)
                ->first();
            if ($check != null) {
                $utilisateur = DB::table('v_list_devis')
                    ->where('id', $id)
                    ->where('etat', 3)
                    ->where('fini', 0)
                    ->where('accept', 0)
                    ->first();
                $objet = DB::table('v_list_objet')
                    ->where('id_devis', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->where('etat', 3)
                    ->where('fini', 0)
                    ->where('accept', 0)
                    ->get();
                $sum = DB::table('v_list_objet')
                    ->where('id_devis', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->selectRaw('SUM(total) as somme_total, SUM(quantite * kilo) as somme_poids')
                    ->first();
                $date_demenagement = $utilisateur->date_demenagement;
                if ($utilisateur->reduction == 0) {
                    return view('front.devis.recu', [
                        'date_demenagement' => $date_demenagement,
                        'objets' => $objet,
                        'sum' => $sum,
                        'utilisateur' => $utilisateur,
                        'title' => 'Devis reçu',
                    ]);
                } else {
                    $reduction = $utilisateur->reduction / 100;
                    return view('front.devis.recu', [
                        'date_demenagement' => $date_demenagement,
                        'objets' => $objet,
                        'reduction' => $reduction,
                        'sum' => $sum,
                        'utilisateur' => $utilisateur,
                        'title' => 'Devis reçu',
                    ]);
                }
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    //liste objet attente reduction devis
    public function getAllObjetAttenteReduction($id_devis)
    {
        try {
            $id_utilisateur = Session::get('id_utilisateur');
            $check_devis = Devis::where('id', $id_devis)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('etat', 0)
                ->where('accept', 0)
                ->where('fini', 0)
                ->first();
            if ($check_devis != null) {
                $objet = DB::table('v_list_objet')
                    ->where('id_devis', $id_devis)
                    ->get();
                $utilisateur = DB::table('v_list_devis')
                    ->where('id', $id_devis)
                    ->first();
                $sum = DB::table('v_list_objet')
                    ->where('id_devis', $id_devis)
                    ->selectRaw('SUM(total) as somme_total, SUM(quantite * kilo) as somme_poids')
                    ->first();
                return view('front.devis.listeObjetAttenteReduction', [
                    'objets' => $objet,
                    'sum' => $sum,
                    'utilisateur' => $utilisateur,
                    'title' => 'Devis en attente de réduction',
                ]);
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //liste objet devis refuser
    public function getAllObjetAttenteRefuser($id_devis)
    {
        try {
            $id_utilisateur = Session::get('id_utilisateur');
            $check_devis = Devis::where('id', $id_devis)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('etat', 0)
                ->where('accept', 0)
                ->where('fini', 1)
                ->first();
            if ($check_devis != null) {
                $objet = DB::table('v_list_objet')
                    ->where('id_devis', $id_devis)
                    ->get();
                $utilisateur = DB::table('v_list_devis')
                    ->where('id', $id_devis)
                    ->first();
                $sum = DB::table('v_list_objet')
                    ->where('id_devis', $id_devis)
                    ->selectRaw('SUM(total) as somme_total, SUM(quantite * kilo) as somme_poids')
                    ->first();
                return view('front.devis.listeObjetAttenteRefuser', [
                    'objets' => $objet,
                    'sum' => $sum,
                    'utilisateur' => $utilisateur,
                    'title' => 'Objet devis refuser',
                ]);
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //reponse devis
    //accepter
    public function accepteDevis($id)
    {
        try {
            $id_utilisateur = Session::get('id_utilisateur');
            $check = Devis::where('id', $id)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('accept', 0)
                ->where('fini', 0)
                ->where('etat', 3)
                ->first();
            if ($check != null) {
                $devis = Devis::findOrFail($id);
                $devis->update([
                    'accept' => 1
                ]);
                $detailsDuDevis = DB::table('v_list_devis')
                    ->where('id_utilisateur', $id_utilisateur)
                    ->where('etat', 3)
                    ->where('accept', 1)
                    ->where('fini', 0)
                    ->first();
                $client = Utilisateur::find($id_utilisateur);
                $detail = [
                    'date_devis' => $detailsDuDevis->created_at,
                    'demenagement' => $detailsDuDevis->date_demenagement,
                    'nom' => $client->nom,
                    'prenom' => $client->prenom
                ];
                Mail::to($client->email)->send(new DevisAccepte($detail));
                return redirect()->route('utilisateur.landing')->with('success_accept', 'Devis Accepter. Un email vous a été envoyer.');
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    //accepte Devis refuser
    public function accepteDevisRefuser($id)
    {
        try {
            $id_utilisateur = Session::get('id_utilisateur');
            $check = Devis::where('id', $id)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('accept', 0)
                ->where('fini', 1)
                ->where('etat', 0)
                ->first();
            if ($check != null) {
                $devis = Devis::findOrFail($id);
                $devis->update([
                    'etat' => 3,
                    'accept' => 1,
                    'fini' => 0
                ]);
                $detailsDuDevis = DB::table('v_list_devis')
                    ->where('id_utilisateur', $id_utilisateur)
                    ->where('etat', 3)
                    ->where('accept', 1)
                    ->where('fini', 0)
                    ->first();
                $client = Utilisateur::find($id_utilisateur);
                $detail = [
                    'date_devis' => $detailsDuDevis->created_at,
                    'demenagement' => $detailsDuDevis->date_demenagement,
                    'nom' => $client->nom,
                    'prenom' => $client->prenom
                ];
                Mail::to($client->email)->send(new DevisAccepte($detail));
                return redirect()->route('utilisateur.landing')->with('success_accept', 'Devis Accepter. Un email vous a été envoyer.');
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //En attente de reduction
    public function reductionDevis($id)
    {
        try {
            $id_utilisateur = Session::get('id_utilisateur');
            $check = Devis::where('id', $id)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('accept', 0)
                ->where('fini', 0)
                ->where('etat', 3)
                ->first();
            if ($check != null) {
                $devis = Devis::findOrFail($id);
                $devis->update([
                    'etat' => 0
                ]);
                $detailsDuDevis = DB::table('v_list_devis')
                    ->where('id', $id)
                    ->where('etat', 0)
                    ->where('accept', 0)
                    ->where('fini', 0)
                    ->first();
                $client = Utilisateur::find($id_utilisateur);
                $detail = [
                    'date_devis' => $detailsDuDevis->created_at,
                    'demenagement' => $detailsDuDevis->date_demenagement,
                    'nom' => $client->nom,
                    'prenom' => $client->prenom
                ];
                Mail::to($client->email)->send(new DevisAttenteReduction($detail));
                return redirect()->route('utilisateur.landing')->with('success_attente', 'Votre demande de réduction a bien été envoyé. Un email vous a été envoyer.');
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //Refuser devis
    public function refuserDevis($id)
    {
        try {
            $id_utilisateur = Session::get('id_utilisateur');
            $check = Devis::where('id', $id)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('accept', 0)
                ->where('fini', 0)
                ->where('etat', 3)
                ->first();
            if ($check != null) {
                $devis = Devis::findOrFail($id);
                $devis->update([
                    'etat' => 0,
                    'fini' => 1
                ]);
                $detailsDuDevis = DB::table('v_list_devis')
                    ->where('id', $id)
                    ->where('etat', 0)
                    ->where('accept', 0)
                    ->where('fini', 1)
                    ->first();
                $client = Utilisateur::findOrFail($id_utilisateur);
                $detail = [
                    'date_devis' => $detailsDuDevis->created_at,
                    'demenagement' => $detailsDuDevis->date_demenagement,
                    'nom' => $client->nom,
                    'prenom' => $client->prenom
                ];
                Mail::to($client->email)->send(new RefusDevis($detail));
                return redirect()->route('utilisateur.landing')->with('success_refus', 'Devis Refuser.');
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function supprimerDevis($id_devis)
    {
        try {
            $id_utilisateur = Session::get('id_utilisateur');
            $check_devis = Devis::where('id', $id_devis)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('etat', 0)
                ->where('accept', 0)
                ->where('fini', 1)
                ->first();
            if ($check_devis != null) {
                $devis = Devis::findOrFail($id_devis);
                $devis->delete();

                $client = Utilisateur::findOrFail($id_utilisateur);
                $detailsDuDevis = DB::table('v_list_devis')
                    ->where('id', $id_devis)
                    ->where('deleted_at', '!=', null)
                    ->first();
                $detail = [
                    'date_devis' => $detailsDuDevis->created_at,
                    'demenagement' => $detailsDuDevis->date_demenagement,
                    'nom' => $client->nom,
                    'prenom' => $client->prenom
                ];
                Mail::to($client->email)->send(new SupprimerDevis($detail));
                return redirect()->route('utilisateur.landing')->with('success_suppr', 'Devis Supprimer.');
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function devisAccepter()
    {
        $accepter = DB::table('v_list_devis')
            ->where('id_utilisateur', Session::get('id_utilisateur'))
            ->where('etat', 3)
            ->where('fini', 0)
            ->where('accept', 1)
            ->first();

        $indispo = DB::table('v_list_devis')
            ->where('id_utilisateur', Session::get('id_utilisateur'))
            ->where('etat', 5)
            ->where('fini', 0)
            ->where('accept', 0)
            ->first();

        $title = 'Devis accepté';

        if ($indispo === null) {
            return view('front.devis.devisAccepter', [
                'accepter' => $accepter,
                'title' => $title,
                'indispo' => 1,
                'dispo' => []
            ]);
        } else {
            $dispo = Livraison::where('id_devis', $indispo->id)
                ->first();
            return view('front.devis.devisAccepter', [
                'accepter' => $indispo,
                'title' => $title,
                'indispo' => 2,
                'dispo' => $dispo
            ]);
        }


    }

    public function getObjetDevisAccepter($id)
    {
        try {
            $id_utilisateur = Session::get('id_utilisateur');
            $check = Devis::where('id', $id)
                ->where('id_utilisateur', $id_utilisateur)
                ->where('accept', 1)
                ->where('fini', 0)
                ->where('etat', 3)
                ->first();
            if ($check != null) {
                $utilisateur = DB::table('v_list_devis')
                    ->where('id', $id)
                    ->where('etat', 3)
                    ->where('fini', 0)
                    ->where('accept', 1)
                    ->first();
                $objet = DB::table('v_list_objet')
                    ->where('id_devis', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->where('etat', 3)
                    ->where('fini', 0)
                    ->where('accept', 1)
                    ->get();
                $sum = DB::table('v_list_objet')
                    ->where('id_devis', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->selectRaw('SUM(total) as somme_total, SUM(quantite * kilo) as somme_poids')
                    ->first();
                $date_demenagement = $utilisateur->date_demenagement;
                if ($utilisateur->reduction == 0) {
                    return view('front.devis.objetAccepter', [
                        'date_demenagement' => $date_demenagement,
                        'objets' => $objet,
                        'sum' => $sum,
                        'utilisateur' => $utilisateur,
                        'title' => 'Objet devis accepter',
                    ]);
                } else {
                    $reduction = $utilisateur->reduction / 100;
                    return view('front.devis.objetAccepter', [
                        'date_demenagement' => $date_demenagement,
                        'objets' => $objet,
                        'reduction' => $reduction,
                        'sum' => $sum,
                        'utilisateur' => $utilisateur,
                        'title' => 'Objet devis accepter',
                    ]);
                }
            } else {
                return response()->json(['message' => 'Forbidden.'], 404);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function acceptDateDispo(Request $request)
    {
        try {
            $id_devis = $request->id_devis;
            $id_livraison = $request->id_livraison;

            // dd($request);
            $devis = Devis::findOrFail($id_devis);
            $livraison = Livraison::findOrFail($id_livraison);

            $updt1 = $devis->update([
                'etat' => 4,
                'accept' => 1
            ]);

            $updt2 = $livraison->update([
                'etat' => 1
            ]);
            if ($updt1 && $updt2) {
                $equipe = Equipe::findOrFail($livraison->id_equipe);
                $client = Utilisateur::findOrFail($devis->id_utilisateur);
                $detail = [
                    // 'date_devis' => $detailsDuDevis->created_at,
                    'date_demenagement' => $livraison->date_livraison,
                    'equipe' => $equipe->nom,
                ];
                Mail::to($client->email)->send(new DateAccepter($detail));
                return redirect()->back()->with('date_accept', 'Déménagement planifié, une email vous a été envoyer');
            }else{
                return redirect()->back()->with('error', 'Une erreur est survenue');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function RefusDateDispo(Request $request)
    {
        try {
            $id_devis = $request->id_devis;
            $id_livraison = $request->id_livraison;

            // dd($request);
            $devis = Devis::findOrFail($id_devis);
            $livraison = Livraison::findOrFail($id_livraison);

            $dlt1 = $devis->delete();

            $dlt2 = $livraison->delete();
            if ($dlt1 && $dlt2) {
                $client = Utilisateur::findOrFail($devis->id_utilisateur);
                Mail::to($client->email)->send(new DateRefuser());
                return redirect()->route('utilisateur.landing')->with('success_suppr_date', 'planification annulé');
            }else{
                return redirect()->back()->with('error', 'Une erreur est survenue');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
