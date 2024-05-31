<?php

namespace App\Http\Controllers\BackOffice;

use App\Categorie;
use App\Equipe;
use App\Http\Controllers\Controller;
use App\Mail\AjoutEquipe;
use App\Mail\ModifEquipe;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class EquipeController extends Controller
{

    //vers ajout equipes
    public function redirectionEquipe()
    {
        $categorie = Categorie::all();
        $title = 'Ajout équipe';
        return view('equipe.ajout', [
            'title' => $title,
            'categories' => $categorie
        ]);
    }

    public function storeEquipe(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|min:5|unique:equipes,nom',
            'email' => 'required|string|unique:equipes,email',
            'password' => 'required|string|min:5'
        ]);

        $insert = Equipe::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_categorie' => $request->categorie
        ]);
        if ($insert) {
            $categorie = Categorie::find($request->categorie);
            $detail = [
                'password' => $request->password,
                'nom' => $request->nom,
                'email' => $request->email,
                'categorie' => $categorie->nom
            ];
            Mail::to($request->email)->send(new AjoutEquipe($detail));
            return redirect()->back()->with('ajout_success', 'Equipe bien ajouté');
        } else {
            return redirect()->back()->with('error', 'Une erreur est survenu');
        }
    }

    public function getAllEquipe()
    {
        $categorie = Categorie::all();
        $title = 'Liste équipe';
        return view('equipe.allEquipe', [
            'title' => $title,
            'categories' => $categorie
        ]);
    }

    public function getEquipeCategorie(Request $request)
    {
        $categorie = $request->input('categorie');

        // Récupérez les données en fonction de la catégorie (utilisez votre logique)
        $equipes = Equipe::where('id_categorie', $categorie)->get();

        return response()->json($equipes);
    }

    //detail equipe
    public function getEquipe($id)
    {
        $equipe = Equipe::findOrFail($id);
        $categorie = Categorie::all();
        $title = 'Modif équipe';
        return view('equipe.getEquipe', [
            'title' => $title,
            'categories' => $categorie,
            'equipe' => $equipe
        ]);
    }

    public function updtEquipe(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'nom' => 'required|string|min:3',
            ]);
            $equipe = Equipe::findOrFail($request->id);
            $equipe->update([
                'nom' => $request->nom,
                'password' => Hash::make($request->password),
                'id_categorie' => $request->categorie
            ]);
            $categorie = Categorie::find($request->categorie);
            $detail = [
                'password' => $request->password,
                'nom' => $request->nom,
                'email' => $equipe->email,
                'categorie' => $categorie->nom
            ];
            Mail::to($equipe->email)->send(new ModifEquipe($detail));
            return redirect()->route('equipe.allEquipe')->with('updt_success', 'Equipe a été modifié');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function deleteEquipe(Request $request)
    {
        try {
            $equipe = Equipe::findOrFail($request->id);
            $equipe->delete();
            return redirect()->back()->with('success_dlt', 'Equipe supprimé');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
