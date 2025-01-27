<?php

namespace App\Console\Commands;

use App\Models\Offre;
use App\Models\User;
use App\Services\SendMail;
use DB;
use Illuminate\Console\Command;

class SendOffersPoint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:offers-point';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer les point des offres aux agents';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hists= DB::table('historique_offres')->whereDate('date_point', '<=', now())->where('sent',0)->get();
        //
        foreach($hists as  $hist){
            $offre=Offre::find($hist->offre);
            $user=User::find($offre->user_id);
            $message="Bonjour $user->name $user->lastname,<br><br>Rappel pour faire le point concernant l'offre de prix <a href='https://crm.mysaamp.com/offres/show/$offre->id'>$offre->Nom_offre</a><br><br>";
            $message.="<b>Client:</b> ".$offre->nom_compte ."<br>";
            $message.="<b>Date de création:</b> ".date('d/m/Y', strtotime($offre->Date_creation)) ."<br>";
            $message.="<b>Type:</b>  $offre->type <br>";
            $message.="<b>Produit:</b>  $offre->Produit_Service <br>";
            $message.="<b>Statut:</b>  $offre->Statut <br>";
            $message.="<b>Description:</b>  $offre->Description <br>";
            $message.="<b>Commentaire:</b>  $offre->commentaire <br><br><br>";
            $message.="<i>Cordialement<br>";
            $message.="L'équipe CRM SAAMP</i>";

            SendMail::send($user->email,"Rappel pour faire le point concernant l'offre de prix $offre->id ",$message);

            DB::table('historique_offres')->where('id',$hist->id)->update(['sent'=>1]);

            \Log::info("Point pour l'offre $offre->Nom_offre envoyée à $user->name $user->lastname  - $user->email ");

        }
    }
}
