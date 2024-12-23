<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CompteClient;
use App\Models\Agence;
use App\Models\User;
use App\Models\Offre;
use App\Models\RendezVous;
use App\Models\RetourClient;
use DB;

class RecapController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }


    // Afficher le formulaire de création
    public function recap(Request $request)
    {
        // Vérification des permissions
        //if (auth()->user()->role != 'admin' &&  auth()->user()->role != 'respAG' && auth()->user()->role != 'dirQUA') {
        //	return view('welcome');
        //}

        $users = DB::table("users")
            ->where('username', 'like', '%@saamp.com')
            ->get();

        $user=auth()->user()->id;

        if($request->get('user')>0)
            $user = $request->get('user');
        $date_debut = $request->get('date_debut') ?? date('Y-m-01');
        $date_fin = $request->get('date_fin') ?? date('Y-m-t');
        $name = "";

/*
        // Validation des dates
        if (!$date_debut || !$date_fin) {
            return back()->with('error', __('msg.Please provide a valid date range.'));
        }
*/
        // Récupération des rendez-vous en fonction de l'utilisateur et de la plage de dates
        //if ($user > 0) {
            $User = User::find($user);
            $name = $User->name . ' ' . $User->lastname;
            $rendezvous = RendezVous::where('user_id', $user)
                ->whereBetween('Started_at', [$date_debut, $date_fin])
                ->orderBy('Started_at', 'asc')
                ->orderBy('heure_debut', 'asc')
                ->get();

                $offres=Offre::where('user_id',$user)
                ->whereBetween('Date_creation', [$date_debut, $date_fin])
                ->orderBy('Date_creation', 'asc')
                ->get();

                $retours=RetourClient::where('user_id',$user)
                ->whereBetween('Date_ouverture', [$date_debut, $date_fin])
                ->orderBy('Date_ouverture', 'asc')
                ->get();

/*

        } else {
            $rendezvous = RendezVous::where('user_id', auth()->user()->id)
                ->whereBetween('Started_at', [$date_debut, $date_fin])
                ->orderBy('Started_at', 'asc')
                ->orderBy('heure_debut', 'asc')
                ->get();

            $offres=Offre::where('user_id', auth()->user()->id)
            ->whereBetween('Date_creation', [$date_debut, $date_fin])
            ->orderBy('Date_creation', 'asc')
            ->get();

            $retours=RetourClient::where('user_id', auth()->user()->id)
            ->whereBetween('Date_ouverture', [$date_debut, $date_fin])
            ->orderBy('Date_ouverture', 'asc')
            ->get();
        }
*/
        $retours_positifs= $retours->where('Type_retour', 'Positif')->count();
        $retours_negatifs= $retours->where('Type_retour', 'Négatif')->count();
        $retours_infos= $retours->where('Type_retour', 'Information générale')->count();

        $rdvs_deplacement= $rendezvous->where('mode_de_rdv', 'Déplacement')->count();
        $rdvs_a_distance= $rendezvous->where('mode_de_rdv', 'À distance')->count();

        $offres_tg= $offres->where('type', 'TG')->count();
        $offres_hors_tg= $offres->where('type', 'Hors TG')->count();
        $offres_apprets= $offres->where('type', 'Apprêts/Bij/DP')->count();

        $offres_ok= $offres->where('statut', 'OK')->count();
        $offres_attente= $offres->whereNull('statut')->count();

        return view('dashboard.recap', compact('rendezvous', 'user', 'name', 'date_debut', 'date_fin','users','offres','retours','retours_infos','retours_positifs','retours_negatifs','rdvs_deplacement','rdvs_a_distance',
        'offres_tg','offres_hors_tg','offres_apprets','offres_ok','offres_attente',
        ));
    }
}
