<?php

namespace App\Console\Commands;

use App\Models\Offre;
use App\Models\User;
use App\Services\SendMail;

use Illuminate\Console\Command;

class SendOffersReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:offers-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer les relances des offres aux agents';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $offres= Offre::whereDate('date_relance', '<=', now())->where('relance_envoye',0)->get();
        //
        foreach($offres as  $offre){
            $user=User::find($offre->user_id);
            $message="Bonjour $user->name $user->lastname,<br><br>Relance pour l'offre de prix <a href='https://crm.mysaamp.com/offres/show/$offre->id'>$offre->Nom_offre</a><br><br>";
            $message.="<b>Client:</b> ".$offre->nom_compte ."<br>";
            $message.="<b>Date de création:</b> ".date('d/m/Y', strtotime($offre->Date_creation)) ."<br>";
            $message.="<b>Type:</b>  $offre->type <br>";
            $message.="<b>Produit:</b>  $offre->Produit_Service <br>";
            $message.="<b>Statut:</b>  $offre->Statut <br>";
            $message.="<b>Description:</b>  $offre->Description <br>";
            $message.="<b>Commentaire:</b>  $offre->commentaire <br><br><br>";
            $message.="<i>Cordialement<br>";
            $message.="L'équipe CRM SAAMP</i>";


            SendMail::send($user->email,"Relance pour l'offre de prix $offre->id ",$message);
            $offre->relance_envoye=1;
            $offre->save();
            \Log::info("Relance pour l'offre $offre->id envoyée à $user->name $user->lastname  - $user->email ");

        }
    }
}
