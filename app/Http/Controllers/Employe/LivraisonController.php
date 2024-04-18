<?php

namespace App\Http\Controllers\Employe;

use App\Equipe;
use App\Http\Controllers\Controller;
use App\Livraison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LivraisonController extends Controller
{
    public function getLivraison($id)
    {
        $livraison = Livraison::where('id_equipe', $id)
            ->where('date_livraison', now())
            ->where('etat', 1)
            ->first();

        if ($livraison != null) {
            $details = DB::table('v_list_devis')
                ->where('id', $livraison->id_devis)
                ->first();

            $objets = DB::table('v_list_objet')
                ->where('id_devis', $livraison->id_devis)
                ->get();

            $sum = DB::table('v_list_objet')
                ->where('id_devis', $livraison->id_devis)
                ->selectRaw('SUM(quantite * kilo) as somme_poids')
                ->first();
        } else {
            $details = null;
            $objets = null;
            $sum = null;
        }

        $data = [
            'livraison' => $livraison,
            'details' => $details,
            'objets' => $objets,
            'sum' => $sum
        ];

        return response()->json($data);
    }


    public function beginLivraison(Request $request)
    {
        // dd($request);
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10000',
        ]);
        $id = $request->id;

        $imagePath = $request->file('image')->store('photos', 'public');

        $livraison = Livraison::findOrFail($id);
        $equipe = Equipe::findOrFail($livraison->id_equipe);
        $updt1 = $livraison->update([
            'img_recup' => $imagePath
        ]);
        $updt2 = $equipe->update([
            'etat' => 1
        ]);
        if ($updt1 && $updt2) {
            return response()->json(['message' => 'Photo uploaded successfully', 'photo' => $imagePath], 201);
        }else{
            return response()->json(['message' => 'Photo  Error', 'photo' => $imagePath], 404);
        }
        // return response()->json($request->id);
    }
}
