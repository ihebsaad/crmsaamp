<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CompteClient;
use App\Models\Tache;
use App\Models\User;
use App\Models\Offre;
use App\Models\RendezVous;
use App\Models\RetourClient;
use DB;
use App\Models\Consultation;

class RecapController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }


    // Afficher le formulaire de création
    public function recap(Request $request)
    {
        $users = DB::table("users")
            ->where('username', 'like', '%@saamp.com')
            ->orderBy('lastname')
            ->get();

        $user = auth()->user()->id;
        if ($request->get('user') > 0) {
            $user = $request->get('user');
        }

        $date_debut = $request->get('date_debut') ?? date('Y-m-01');
        $date_fin = $request->get('date_fin') ?? date('Y-m-t');
        $affichage = $request->get('affichage');
        $name = "";

        $User = User::find($user);
        $name = $User->name . ' ' . $User->lastname;

        // Variables pour périodes précédentes
        $prev_date_debut = null;
        $prev_date_fin = null;

        if ($affichage == 1) {
            // Calcul des dates pour le mois précédent
            $prev_date_debut = date('Y-m-01', strtotime('first day of -1 month', strtotime($date_debut)));
            $prev_date_fin = date('Y-m-t', strtotime('last day of -1 month', strtotime($date_fin)));
        } elseif ($affichage == 2) {
            // Calcul des dates pour l'année précédente
            $prev_date_debut = date('Y-01-01', strtotime('-1 year', strtotime($date_debut)));
            $prev_date_fin = date('Y-12-31', strtotime('-1 year', strtotime($date_fin)));
        } elseif ($affichage == 3) {
            // Calcul des dates pour une période personnalisée
            $diff = (new \DateTime($date_debut))->diff(new \DateTime($date_fin));
            $prev_date_fin = (new \DateTime($date_debut))->modify('-1 day')->format('Y-m-d');
            $prev_date_debut = (new \DateTime($prev_date_fin))->modify("-{$diff->days} days")->format('Y-m-d');
        }

        // Récupération des données pour la période actuelle
        $rendezvous = RendezVous::where('user_id', $user)
            ->whereBetween('Started_at', [$date_debut, $date_fin])
            ->orderBy('Started_at', 'asc')
            ->orderBy('heure_debut', 'asc')
            ->get();

        $offres = Offre::where('user_id', $user)
            ->whereBetween('Date_creation', [$date_debut, $date_fin])
            ->orderBy('Date_creation', 'asc')
            ->get();

        $retours = RetourClient::where('user_id', $user)
            ->whereBetween('Date_ouverture', [$date_debut, $date_fin])
            ->orderBy('Date_ouverture', 'asc')
            ->get();

        $taches = Tache::where('user_id', $user)
            ->whereBetween('DateTache', [$date_debut, $date_fin])
            ->get();

        $clients=0; $clients_list=array();
        $rep=DB::table("representant")->where('users_id',$user)->first();
        if(isset($rep)){
            $clients_list = CompteClient::where('commercial', $rep->id)
            ->whereNotNull('cl_ident')->where('cl_ident','<>',0)
            ->whereBetween('created_at', [$date_debut, $date_fin])
            ->get();
            $clients=count($clients_list);
        }

        // Récupération des données pour la période précédente
        $prev_rendezvous = RendezVous::where('user_id', $user)
            ->whereBetween('Started_at', [$prev_date_debut, $prev_date_fin])
            ->orderBy('Started_at', 'asc')
            ->orderBy('heure_debut', 'asc')
            ->get();

        $prev_offres = Offre::where('user_id', $user)
            ->whereBetween('Date_creation', [$prev_date_debut, $prev_date_fin])
            ->orderBy('Date_creation', 'asc')
            ->get();

        $prev_retours = RetourClient::where('user_id', $user)
            ->whereBetween('Date_ouverture', [$prev_date_debut, $prev_date_fin])
            ->orderBy('Date_ouverture', 'asc')
            ->get();

        $prev_taches = Tache::where('user_id', $user)
            ->whereBetween('DateTache', [$prev_date_debut, $prev_date_fin])
            ->get();

        // Calcul des statistiques pour la période actuelle
        $retours_positifs = $retours->where('Type_retour', 'Positif')->count();
        $retours_negatifs = $retours->where('Type_retour', 'Négatif')->count();
        $retours_infos = $retours->where('Type_retour', 'Information générale')->count();

        $rdvs_deplacement = $rendezvous->where('mode_de_rdv', 'Déplacement')->count();
        //$rdvs_a_distance = $rendezvous->where('mode_de_rdv', 'À distance')->count();
        $rdvs_agence = $rendezvous->where('mode_de_rdv', 'En agence')->count();
        $rdvs_office = $rendezvous->where('mode_de_rdv', 'Home Office')->count();

        $offres_tg = $offres->where('type', 'TG')->count();
        $offres_hors_tg = $offres->where('type', 'Hors TG')->count();
        $offres_apprets = $offres->where('type', 'Apprêts/Bij/DP')->count();

        $offres_ok = $offres->where('statut', 'OK')->count();
        $offres_attente = $offres->whereNull('statut')->count();

        $appels = $taches->where('Type', 'Appel téléphonique')->count();
        $remises = $taches->where('Type', 'Remise de commande')->count();
        $suivis = $taches->where('Type', 'Suivi client')->count();
        $autres = $taches->where('Type', 'Autre')->count();


        // Calcul des statistiques pour la période précédente
        $prev_retours_positifs = $prev_retours->where('Type_retour', 'Positif')->count();
        $prev_retours_negatifs = $prev_retours->where('Type_retour', 'Négatif')->count();
        $prev_retours_infos = $prev_retours->where('Type_retour', 'Information générale')->count();

        $prev_rdvs_deplacement = $prev_rendezvous->where('mode_de_rdv', 'Déplacement')->count();
        //$prev_rdvs_a_distance = $prev_rendezvous->where('mode_de_rdv', 'À distance')->count();
        $prev_rdvs_agence = $prev_rendezvous->where('mode_de_rdv', 'En agence')->count();
        $prev_rdvs_office = $prev_rendezvous->where('mode_de_rdv', 'Home Office')->count();

        $prev_offres_tg = $prev_offres->where('type', 'TG')->count();
        $prev_offres_hors_tg = $prev_offres->where('type', 'Hors TG')->count();
        $prev_offres_apprets = $prev_offres->where('type', 'Apprêts/Bij/DP')->count();

        $prev_offres_ok = $prev_offres->where('statut', 'OK')->count();
        $prev_offres_attente = $prev_offres->whereNull('statut')->count();

        $prev_appels = $prev_taches->where('Type', 'Appel téléphonique')->count();
        $prev_remises = $prev_taches->where('Type', 'Remise de commande')->count();
        $prev_suivis = $prev_taches->where('Type', 'Suivi client')->count();
        $prev_autres = $prev_taches->where('Type', 'Autre')->count();

        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Récapitulatif"]);

        return view('dashboard.recap', compact(
            'rendezvous',
            'prev_rendezvous',
            'user',
            'name',
            'date_debut',
            'date_fin',
            'users',
            'clients',
            'offres',
            'retours',
            'retours_infos',
            'retours_positifs',
            'retours_negatifs',
            'rdvs_deplacement',
            'rdvs_agence',
            'rdvs_office',
            'offres_tg',
            'offres_hors_tg',
            'offres_apprets',
            'offres_ok',
            'offres_attente',
            'prev_retours',
            'prev_retours_positifs',
            'prev_retours_negatifs',
            'prev_retours_infos',
            'prev_rdvs_deplacement',
            'prev_rdvs_agence',
            'prev_rdvs_office',
            'prev_offres',
            'prev_offres_tg',
            'prev_offres_hors_tg',
            'prev_offres_apprets',
            'prev_offres_ok',
            'prev_offres_attente',
            'prev_date_debut',
            'prev_date_fin',
            'affichage',
            'taches',
            'prev_taches',
            'appels',
            'remises',
            'suivis',
            'autres',
            'prev_appels',
            'prev_remises',
            'prev_suivis',
            'prev_autres',
            'clients_list'
        ));
    }

}
