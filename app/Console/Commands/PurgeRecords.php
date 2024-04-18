<?php
namespace App\Console\Commands;

use App\Devis;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PurgeRecords extends Command
{
    protected $signature = 'purge:records';
    protected $description = 'Purge records with start date older than 3 days';

    public function handle()
    {
        $devis_exeed = DB::table('v_list_devis')
            ->get();
        foreach ($devis_exeed as $devis) {
            $joursIntervalle = Carbon::now()->diffInDays($devis->date_demenagement);
            if ($joursIntervalle >= 12) {
                // echo $joursIntervalle;
                // die();
                $d = Devis::find($devis->id);
                $d->delete();
            }
        }
        $this->info('Records purged successfully.');
    }
}

