<?php

namespace App\Console\Commands;

use App\Models\Offre;
use App\Models\User;
use App\Services\SendMail;
use App\Models\CompteClient;
use App\Models\File;

use DB;

use Illuminate\Console\Command;

class SendCommunications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:communications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer les communications planifiées';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $comms = DB::table('communications')->where('statut', 3)->where('date_envoi', '<=', now())->get();
        //
        foreach ($comms as  $comm) {

            // S'assurer que les destinataires sont sous forme de tableau PHP natif
            if (is_string($comm->destinataires)) {
                $destinatairesInput = json_decode($comm->destinataires, true); // Décoder en tableau associatif
            }

            // Vérification de la structure
            if (!is_array($comm->destinataires)) {
                $destinatairesInput = [$comm->destinataires]; // Convertir en tableau si un seul objet
            }
            $destinatairesIds = array_column($destinatairesInput, 'id'); // Extraire les IDs

            // Récupérer les emails des clients
            $emails = CompteClient::whereIn('id', $destinatairesIds)
                ->whereNotNull('email')
                ->pluck('email')
                ->toArray();

            $user_id = $comm->par;
            $user = User::find($user_id);
            array_push($emails, $user->email);
            // Objet et contenu de l'email
            $objet = $comm->objet;
            $contenu = $comm->corps_message;

            $attachmentPaths = [];

            $attachements = File::where('parent_id', $comm->id)->where('parent', 'communication')->get();
            foreach ($attachements as $attach) {
                $attachmentPaths[] = public_path("fichiers/communications") . '/' . $attach->name;
            }

            SendMail::send($emails, $objet, $contenu, $attachmentPaths, $user->email);

            DB::table('communications')->where('id',$comm->id)->update(['statut'=>4]);

            \Log::info("Communication envoyée  $comm->id  ( $user->name $user->lastname  - $user->email ) ");
        }
    }
}
