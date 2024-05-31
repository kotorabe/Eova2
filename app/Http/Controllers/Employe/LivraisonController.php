<?php

namespace App\Http\Controllers\Employe;

use App\Equipe;
use App\Http\Controllers\Controller;
use App\Livraison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LivraisonController extends Controller
{
    public function getPosition(Request $request){
        $equipe = Equipe::findOrFail($request->equipeIdFromStorage);

        $equipe->update([
            'position' => $request->posit
        ]);
    }

    public function getLivraison($id)
    {
        $livraison = Livraison::where('id_equipe', $id)
            ->where('date_livraison', now())
            ->where(function ($query) {
                $query->where('etat', 1)
                    ->orWhere('etat', 2)
                    ->orWhere('etat', 3);
            })
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

    public function beginLivraison($id, $pos)
    {
        $livraison = Livraison::findOrFail($id);
        $equipe = Equipe::findOrFail($livraison->id_equipe);
        $updt1 = $livraison->update([
            'etat' => 2
        ]);
        $updt2 = $equipe->update([
            'etat' => 1,
            'position' => $pos
        ]);
        if ($updt1 && $updt2) {
            return response()->json(['message' => 'Success'], 200);
        } else {
            return response()->json(['message' => 'Photo  Error'], 404);
        }
    }


    public function goLivraison(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        $id = $request->id;

        $imagePath = $request->file('image')->store('photos/commence', 'public');

        $livraison = Livraison::findOrFail($id);
        $equipe = Equipe::findOrFail($livraison->id_equipe);
        $updt1 = $livraison->update([
            'img_recup' => $imagePath,
            'etat' => 3
        ]);
        $updt2 = $equipe->update([
            'etat' => 2,
            'position' => $request->position
        ]);
        if ($updt1 && $updt2) {
            return response()->json(['message' => 'Photo uploaded successfully', 'photo' => $imagePath], 201);
        } else {
            return response()->json(['message' => 'Photo  Error', 'photo' => $imagePath], 404);
        }
    }

    public function FiniLivraison(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        $id = $request->id;
        $imagePath = $request->file('image')->store('photos/fini', 'public');
        $livraison = Livraison::findOrFail($id);
        $equipe = Equipe::findOrFail($livraison->id_equipe);
        $updt1 = $livraison->update([
            'img_livr' => $imagePath,
            'etat' => 4
        ]);
        $updt2 = $equipe->update([
            'etat' => 0
        ]);
        if ($updt1 && $updt2) {
            return response()->json(['message' => 'Photo uploaded successfully', 'photo' => $imagePath], 201);
        } else {
            return response()->json(['message' => 'Photo  Error', 'photo' => $imagePath], 404);
        }
    }
}
