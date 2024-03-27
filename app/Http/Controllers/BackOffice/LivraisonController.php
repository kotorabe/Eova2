<?php

namespace App\Http\Controllers\BackOffice;

use App\Devis;
use App\Http\Controllers\Controller;
use App\Livraison;
use Illuminate\Http\Request;

class LivraisonController extends Controller
{
    public function assignationEquipe(Request $request){
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
            if($insert){
                echo 'Assigner';
            } else{
                echo 'Erreur';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
