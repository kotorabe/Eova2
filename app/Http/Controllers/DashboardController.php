<?php

namespace App\Http\Controllers;

use App\Devis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $demande = Devis::where('etat', 2)
            ->where('accept', 0)
            ->where('fini', 0)
            ->count();
        $accepter = Devis::where('etat', 3)
            ->where('accept', 1)
            ->where('fini', 0)
            ->count();
        $reduction = Devis::where('etat', 0)
            ->where('accept', 0)
            ->where('fini', 0)
            ->count();
        $refuser = Devis::where('etat', 0)
            ->where('accept', 0)
            ->where('fini', 1)
            ->count();
        $title = 'Dashboard';
        return view('dashboard', [
            'title' => $title,
            'demande' => $demande,
            'accepter' => $accepter,
            'reduction' => $reduction,
            'refuser' => $refuser
        ]);
    }

    public function purgeDevis()
    {
        try {
            $devis_exeed = DB::table('v_list_devis')
                ->where('etat', 0)
                ->where('accept', 0)
                ->where('fini', 1)
                ->get();
            $isa = 0;
            if ($devis_exeed != null) {
                foreach ($devis_exeed as $devis) {
                    $joursIntervalle = Carbon::now()->diffInDays($devis->date_demenagement);
                    if ($joursIntervalle <= 4) {
                        // echo $joursIntervalle;
                        // die();
                        $d = Devis::find($devis->id);
                        $d->delete();
                        $isa++;
                    }
                }
            } else {
                return redirect()->route('dashboard')->with('success_null', 'Aucun devis purger.');
            }
            if ($isa != 0) {
                return redirect()->route('dashboard')->with('success_purge', 'Devis Refuser purger.');
            } else {
                return redirect()->route('dashboard')->with('success_null', 'Aucun devis purger.');
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
