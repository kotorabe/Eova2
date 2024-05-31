<?php

namespace App\Http\Controllers;

use App\Devis;
use App\Livraison;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $dateActuelle = Carbon::now()->locale('fr_FR')->isoFormat('DD/MM/YYYY');
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
        $planifier = Livraison::where('etat', 1)
            ->count();
        $reponse = Devis::where('etat', 3)
            ->where('accept', 0)
            ->where('fini', 0)
            ->count();
        $livraison = Livraison::where('date_livraison', now())
            ->count();
        $encours = Livraison::where(function ($query) {
            $query->where('etat', 2)
                ->orWhere('etat', 3);
        })
            ->count();
        $fini = Livraison::where('date_livraison', now())
            ->where('etat', 4)
            ->count();
        $currentYear = Carbon::now()->year;
        $demande_stat = Devis::withTrashed()
            ->select(DB::raw('EXTRACT(MONTH FROM created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->get();
        $realisation_stat = Livraison::select(DB::raw('EXTRACT(MONTH FROM date_livraison) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('date_livraison', $currentYear)
            ->where('etat', 4)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM date_livraison)'))
            ->get();
        $title = 'Dashboard';
        return view('dashboard', [
            'dateActuelle' => $dateActuelle,
            'title' => $title,
            'demande' => $demande,
            'accepter' => $accepter,
            'reduction' => $reduction,
            'refuser' => $refuser,
            'planifier' => $planifier,
            'reponse' => $reponse,
            'livraison' => $livraison,
            'encours' => $encours,
            'fini' => $fini,
            'demande_stat' => $demande_stat,
            'realisation_stat' => $realisation_stat
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
