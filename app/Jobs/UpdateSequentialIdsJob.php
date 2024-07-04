<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\RendezVous;
use App\Models\CompteClient;
use DB;

class UpdateSequentialIdsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
/*
        $count = RendezVous::count();
        for ($i = 1; $i <= $count; $i++) {
            RendezVous::whereNull('id')->limit(1)->update(['id' => $i]);
        }
*/
/*
        $count = Tache::count();
        $start = Tache::where('id','<>',null)->count();
        $j=$start+1;
        for ($i = $j; $i <= $count; $i++) {
            Tache::where('id', null)->limit(1)->update(['id' => $i]);
        }
        */
        $adresses=DB::table('CRMClient_geocoded')->get();
        foreach($adresses as $adresse){
            CompteClient::where('Id_Salesforce', $adresse->Id_Salesforce)->update(
                [
                    'longitude' => $adresse->longitude,
                    'latitude' => $adresse->latitude
                ]
        );
        }

    }
}