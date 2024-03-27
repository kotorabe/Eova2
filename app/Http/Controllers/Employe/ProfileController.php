<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index($id)
    {
        $equipe = DB::table('v_list_equipe_categorie')
            ->where('id', $id)
            ->first();

        $data = [
            'profile' => $equipe,
        ];

        return response()->json($data);
    }
}
