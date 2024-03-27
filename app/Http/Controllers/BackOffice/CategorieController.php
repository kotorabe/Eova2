<?php

namespace App\Http\Controllers\BackOffice;

use App\Categorie;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategorieController extends Controller
{

    //vers liste
    public function redirectionCategorie()
    {
        $title = 'Ajout Catégorie';
        $categorie = Categorie::all();
        return view('categorie.ajout', [
            'title' => $title,
            'categories' => $categorie
        ]);
    }


    //ajout categorie
    public function storeCategorie(Request $request)
    {
        try {
            $request->validate([
                'nom' => 'required|string|min:5|unique:categories,nom',
                'poids_total' => 'required|integer|min:1000'
            ]);
            $nom = $request->nom;
            $poids_total = $request->poids_total;
            $insert = Categorie::create([
                'nom' => $nom,
                'poids_total' => $poids_total
            ]);
            if ($insert) {
                return redirect()->back()->with('add_success', 'Catégorie ajouté');
            } else {
                return redirect()->back()->with('failed_add', 'Une erreur est survenue');
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    //vers mofif
    public function getCategorie($id)
    {
        try {
            $categorie = Categorie::findOrFail($id);
            $title = 'Modification catégorie';
            return view('categorie.modif', [
                'title' => $title,
                'categorie' => $categorie
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    //modif categorie
    public function updtCategorie(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'nom' => 'required|string|min:5',
                'poids_total' => 'required|integer|min:1000'
            ]);
            $id = $request->id;
            $nom = $request->nom;
            $poids_total = $request->poids_total;
            $categorie = Categorie::findOrFail($id);
            $categorie->update([
                'nom' => $nom,
                'poids_total' => $poids_total
            ]);
            return redirect()->route('categorie.redirection')->with('updt_success', 'Catégorie Modifié');

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteCategorie($id){
        try {
            $categorie = Categorie::findOrFail($id);
            $categorie->delete();
            return redirect()->back()->with('dlt_success', 'Catégorie supprimé');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
