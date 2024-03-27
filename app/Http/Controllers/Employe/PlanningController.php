<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanningController extends Controller
{
    public function index($id)
    {
        $planning = DB::table('v_list_planning_equipe')
            ->where('id_equipe', $id)
            ->where('date_livraison', '>=', now())
            ->where('etat', 1)
            ->get();

        $data = [
            'planning' => $planning
        ];

        return response()->json($data);
    }

    public function getDetails($id_devis){
        $detail = DB::table('v_list_devis')
        ->where('id', $id_devis)
        ->first();

        return response()->json(['detail' => $detail]);
    }
}
