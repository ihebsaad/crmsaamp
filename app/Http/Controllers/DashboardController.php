<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Agence;
use App\Models\User;
use App\Models\CompteClient;
use App\Models\Offre;
use App\Models\Consultation;
use App\Models\RendezVous;
use App\Models\RetourClient;
use App\Models\Tache;
use App\Models\GoogleToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\StatsExportService;

class DashboardController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware(['auth']);
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */


	public function adminhome()
	{
		if (auth()->user()->user_role ==1 || auth()->user()->user_role == 2|| auth()->user()->user_role == 5) {

			$stats_spot=self::stats_spot('jour');
			$offres = Offre::where('statut', null)->get();
			$now = Carbon::now();
			$representants = DB::table("representant")->get();
			$users = DB::table("users")->where('username', 'like', '%@saamp.com')->orderBy('lastname', 'asc')->get();
			$prospects = CompteClient::where('agence_ident', auth()->user()->agence_ident)->where('etat_id', 1)->get();

			$rendezvous = RendezVous::where('Started_at', '>=', $now)
				->orderBy('Started_at', 'asc')
				->orderBy('heure_debut', 'asc')
				->get();

			$retours = RetourClient::where('Date_cloture', '0000-00-00')
				->orWhere('Date_cloture', null)
				//->limit(20)
				->orderBy('name', 'desc')->get();

			//taches=Tache::where('DateTache','like',date('Y-m-d').'%' )->orderBy('heure_debut','asc')->get();

			// Récupérer les filtres
			$nom = request()->input('nom');
			$cl_ident = request()->input('cl_ident');
			// Récupérer les tâches
			$tasks = Tache::where('DateTache', 'like', date('Y-m-d') . '%')->orderBy('heure_debut', 'asc')->get();

			// Récupérer les données de prise_contact_as400
			$prises = DB::table('prise_contact_as400')
				->join('client', 'prise_contact_as400.cl_ident', '=', 'client.cl_ident')
				->join('agence', 'prise_contact_as400.agence_id', '=', 'agence.agence_ident')
				->join('sujet', 'prise_contact_as400.id_sujet', '=', 'sujet.sujet_ident')
				->join('type_contact', 'prise_contact_as400.id_type_contact', '=', 'type_contact.type_contact_ident')
				->select(
					'client.id as ID_Compte',
					'prise_contact_as400.date_pr as DateTache',
					DB::raw('NULL as heure_debut'),
					DB::raw('NULL as Status'),
					DB::raw('NULL as Priority'),
					'type_contact.titre_type_contact as Type',
					'client.Nom as Nom_de_compte',
					'client.cl_ident as mycl_id',
					DB::raw('CONCAT(
							CASE
								WHEN sujet.titre_sujet IS NOT NULL
								THEN CONCAT("Sujet: ", sujet.titre_sujet)
								ELSE ""
							END,
							CASE
								WHEN type_contact.titre_type_contact IS NOT NULL
								THEN CONCAT(", Type: ", type_contact.titre_type_contact)
								ELSE ""
							END
						) as Description'),
					'agence.agence_lib as Agence',
					'sujet.titre_sujet as Subject',
					DB::raw('1 as as400')
				)
				->where('prise_contact_as400.date_pr', 'like', date('Y-m-d') . '%')
				->orderBy('prise_contact_as400.id', 'desc')
				->get()
				->toArray();

			// Fusionner les tâches avec les prises de contact
			$tasks = collect($tasks)->map(function ($task) {
				$task = (object) $task;
				$task->as400 = 0;
				return $task;
			});

			$prises = collect($prises);

			$taches = $tasks->merge($prises);

			$query = "SELECT COUNT(DISTINCT cl_ident) as total FROM Statistiques WHERE agence_ident = ? AND annee = YEAR(CURDATE())";

			$userToken = GoogleToken::where('user_id', auth()->id())->first();

			$stats = DB::select('call `sp_stats_agence_reception_poids`();');
			$stats_mois = DB::select('call `sp_stats_agence_reception_mois`();');



			$totaux_clients = self::totaux_clients();/*
			if(auth()->id()==1) 
				return view('dashboard.adminhome2', compact(	'retours','rendezvous','taches','representants','offres','userToken','users','stats','stats_mois','prospects','totaux_clients','stats_spot'));
			else */
				return view('dashboard.adminhome', compact(	'retours','rendezvous','taches','representants','offres','userToken','users','stats','stats_mois','prospects','totaux_clients','stats_spot'));
		} else {
			$rendezvous = RendezVous::where('Attribue_a', auth()->user()->name . ' ' . auth()->user()->lastname)
				->orWhere('user_id', auth()->user()->id)
				->orderBy('id', 'desc')->get();

			$rendezvous = RendezVous::get();


			return view('dashboard.dashboard', compact('rendezvous'));
		}
	}


	public static function totaux_clients()
	{

		$query = "SELECT COUNT(DISTINCT cl_ident) as total FROM Statistiques WHERE agence_ident = ? AND annee = YEAR(CURDATE())";

		$totaux_clients['total_clients_1'] = CompteClient::where('etat_id', 2)->where('agence_ident', 40)->count(); //Paris
		$totaux_clients['total1'] = DB::select($query, [40]);
		$totaux_clients['total_1'] = $totaux_clients['total1'][0]->total;
		$totaux_clients['total_clients_2'] = CompteClient::where('etat_id', 2)->where('agence_ident', 42)->count(); // Lyon
		$totaux_clients['total2'] = DB::select($query, [42]);
		$totaux_clients['total_2'] = $totaux_clients['total2'][0]->total;
		$totaux_clients['total_clients_3'] = CompteClient::where('etat_id', 2)->where('agence_ident', 43)->count(); //Marseille
		$totaux_clients['total3'] = DB::select($query, [43]);
		$totaux_clients['total_3'] = $totaux_clients['total3'][0]->total;
		$totaux_clients['total_clients_4'] = CompteClient::where('etat_id', 2)->where('agence_ident', 10)->count(); //AUBAGNE
		$totaux_clients['total4'] = DB::select($query, [10]);
		$totaux_clients['total_4'] = $totaux_clients['total4'][0]->total;
		$totaux_clients['total_clients_5'] = CompteClient::where('etat_id', 2)->where('agence_ident', 200)->count(); //
		$totaux_clients['total5'] = DB::select($query, [200]);
		$totaux_clients['total_5'] = $totaux_clients['total5'][0]->total;
		$totaux_clients['total_clients_6'] = CompteClient::where('etat_id', 2)->where('agence_ident', 100)->count(); //
		$totaux_clients['total6'] = DB::select($query, [100]);
		$totaux_clients['total_6'] = $totaux_clients['total6'][0]->total;
		$totaux_clients['total_clients_7'] = CompteClient::where('etat_id', 2)->where('agence_ident', 201)->count(); //
		$totaux_clients['total7'] = DB::select($query, [201]);
		$totaux_clients['total_7'] = $totaux_clients['total7'][0]->total;
		$totaux_clients['total_clients_8'] = CompteClient::where('etat_id', 2)->where('agence_ident', 202)->count(); //
		$totaux_clients['total8'] = DB::select($query, [202]);
		$totaux_clients['total_8'] = $totaux_clients['total8'][0]->total;
		$totaux_clients['total_clients_9'] = CompteClient::where('etat_id', 2)->where('agence_ident', 203)->count(); //
		$totaux_clients['total9'] = DB::select($query, [203]);
		$totaux_clients['total_9'] = $totaux_clients['total9'][0]->total;

		return $totaux_clients;
	}


	public function dashboard()
	{
		$agence_id = auth()->user()->agence_ident;
		$agence = Agence::where('agence_ident', $agence_id)->first();
		$now = Carbon::now();
		$prospects = array();
		$commerciaux = array();
		$customers = array();
		$retours = array();
		$clients = array();
		$offres = array();
		$total_clients = $total_1 = 0;
		$totaux_clients = array();
		$rendezvous = array();
 		//rendez vous
		$rendezvous_passes = RendezVous::where('Started_at', '<=', $now)
			->where('user_id', auth()->id())
			->where('statut', 1)
			->orderBy('Started_at', 'asc')
			->orderBy('heure_debut', 'asc')
			->get();

		switch (auth()->user()->user_role) {

			case 1:
				return redirect()->route('adminhome');
				break;

			case 2:
				return redirect()->route('adminhome');
				break;

			case 3:			// Supervision
				$retours = DB::table('CRM_RetourClient as rc')
					->join('client as c', 'rc.cl_id', '=', 'c.cl_ident')
					->where(function ($query) {
						$query->where('rc.Date_cloture', '0000-00-00')
							->orWhereNull('rc.Date_cloture');
					})
					->where('c.agence_ident', auth()->user()->agence_ident)
					->select('rc.*', 'c.agence_ident') // Select fields as needed
					->orderBy('rc.name', 'desc') // Adjust as needed; 'name' should be in `rc` or `c`
					->get();

				//$commerciaux = DB::table("representant")->where('type', 'Commercial terrain')->pluck('id');

				//if (auth()->user()->id == 10) {
					$commerciaux = DB::table("representant")->where('type', 'Commercial terrain')->orWhere('id', 26)->pluck('id');
				//}

				foreach ($commerciaux as $commercial) {
					$rep = DB::table("representant")->find($commercial);
					$user = User::find($rep->users_id);

					if ($user->agence_ident == auth()->user()->agence_ident /*|| auth()->user()->id==10*/) {
						DB::select("SET @p0='$commercial' ;");
						$customers[$commercial] =  DB::select("  CALL `sp_stats_commercial_client_top5`(@p0); ");
						//}

						$query = "
						SELECT COUNT(DISTINCT s.cl_ident) AS total_clients
						FROM Statistiques s
						WHERE
							(s.Commercial = ? OR s.Commercial_support = ?)
							AND s.Mois < (CASE WHEN 1 THEN MONTH(CURDATE()) ELSE 13 END)
							AND s.cl_ident <> 0
						";

						$result = DB::select($query, [$commercial, $commercial]);
						$total_1 = $result[0]->total_clients;
						$total_clients += $total_1;
						// sebastien
						if (auth()->user()->id == 10) {
							unset($customers[201]);
							unset($customers[510]);
						}
					}
				}
				$total_clients = CompteClient::where('etat_id', 2)->where('agence_ident', auth()->user()->agence_ident)->count(); //Paris
				$prospects = CompteClient::where('etat_id', 1)->get();
				if(auth()->id()==10){
					$offres = Offre::where('type', 'Hors TG - Affinage')->where('statut', null)->get();
				}
				else{
					$offres = Offre::where('type', 'Hors TG - Apprêts/Bij/DP')->where('statut', null)->get();
				}

				break;
			case 4:			//respAG

				$retours = DB::table('CRM_RetourClient as rc')
					->join('client as c', 'rc.cl_id', '=', 'c.cl_ident')
					->where(function ($query) {
						$query->where('rc.Date_cloture', '0000-00-00')
							->orWhereNull('rc.Date_cloture');
					})
					->where('c.agence_ident', auth()->user()->agence_ident)
					->select('rc.*', 'c.agence_ident') // Select fields as needed
					->orderBy('rc.name', 'desc') // Adjust as needed; 'name' should be in `rc` or `c`
					->get();

				$commerciaux = DB::table("representant")->where('type', 'Commercial terrain')->whereRaw("FIND_IN_SET(?, agence)", [auth()->user()->agence_ident])->pluck('id');

				if (auth()->user()->id == 10) {
					$commerciaux = DB::table("representant")->where('type', 'Commercial terrain')->orWhere('id', 26)->pluck('id');
				}

				foreach ($commerciaux as $commercial) {
					$rep = DB::table("representant")->find($commercial);
					$user = User::find($rep->users_id);

					//if ($user->agence_ident == auth()->user()->agence_ident /*|| auth()->user()->id==10*/) {
						DB::select("SET @p0='$commercial' ;");
						$customers[$commercial] =  DB::select("  CALL `sp_stats_commercial_client_top5`(@p0); ");
						//}

						$query = "
						SELECT COUNT(DISTINCT s.cl_ident) AS total_clients
						FROM Statistiques s
						WHERE
							(s.Commercial = ? OR s.Commercial_support = ?)
							AND s.Mois < (CASE WHEN 1 THEN MONTH(CURDATE()) ELSE 13 END)
							AND s.cl_ident <> 0
						";

						$result = DB::select($query, [$commercial, $commercial]);
						$total_1 = $result[0]->total_clients;
						$total_clients += $total_1;
						// sebastien
						if (auth()->user()->id == 10) {
							unset($customers[201]);
							unset($customers[510]);
						}
					//}
				}

				$total_clients = CompteClient::where('etat_id', 2)->where('agence_ident', auth()->user()->agence_ident)->count(); //Paris
				$prospects = CompteClient::where('etat_id', 1)->where('agence_ident', auth()->user()->agence_ident)->get();

/*
				$client_ids = CompteClient::where('adv', auth()->user()->id)->pluck('id');

				$rendezvous = RendezVous::whereIn('mycl_id', $client_ids)
					->where('Started_at', '>=', $now)
					->orderBy('Started_at', 'desc')
					->orderBy('id', 'desc')
					->get();
*/
				$users_id= DB::table('representant')->whereRaw("FIND_IN_SET(?, agence)", [auth()->user()->agence_ident])->pluck('users_id');
				$users=DB::table("users")->whereIn('id',$users_id)->orderBy('lastname','asc')->get();
				$rendezvous = RendezVous::whereIn('user_id', $users_id)
					->where('Started_at', '>=', $now)
					->orderBy('Started_at', 'desc')
					->orderBy('id', 'desc')
					->get();

				break;
			case 5 :		 ///Qualité

				$retours = DB::table('CRM_RetourClient as rc')
					->join('client as c', 'rc.cl_id', '=', 'c.cl_ident')
					->where(function ($query) {
						$query->where('rc.Date_cloture', '0000-00-00')
							->orWhereNull('rc.Date_cloture');
					})
					//->where('c.agence_ident', auth()->user()->agence_ident)
					->select('rc.*', 'c.agence_ident') // Select fields as needed
					->orderBy('rc.name', 'desc') // Adjust as needed; 'name' should be in `rc` or `c`
					->get();


				$totaux_clients = self::totaux_clients();

 				break;

			case 6:               /// ADV
				$retours = DB::table('CRM_RetourClient as rc')
					->join('client as c', 'rc.cl_id', '=', 'c.cl_ident')
					->where(function ($query) {
						$query->where('rc.Date_cloture', '0000-00-00')
							->orWhereNull('rc.Date_cloture');
					})
					->where('c.agence_ident', auth()->user()->agence_ident)
					->select('rc.*', 'c.agence_ident') // Select fields as needed
					->orderBy('rc.name', 'desc') // Adjust as needed; 'name' should be in `rc` or `c`
					->get();

				//$commerciaux = DB::table("representant")->where('type', 'Commercial terrain')->pluck('id');
				$commerciaux = DB::table("representant")->where('type', 'Commercial terrain')->whereRaw("FIND_IN_SET(?, agence)", [auth()->user()->agence_ident])->pluck('id');

				if (auth()->user()->id == 10) {
					$commerciaux = DB::table("representant")->where('type', 'Commercial terrain')->orWhere('id', 26)->pluck('id');
				}

				foreach ($commerciaux as $commercial) {
					$rep = DB::table("representant")->find($commercial);
					$user = User::find($rep->users_id);

					//if ($user->agence_ident == auth()->user()->agence_ident /*|| auth()->user()->id==10*/) {
						DB::select("SET @p0='$commercial' ;");
						$customers[$commercial] =  DB::select("  CALL `sp_stats_commercial_client_top5`(@p0); ");
						//}

						$query = "
							SELECT COUNT(DISTINCT s.cl_ident) AS total_clients
							FROM Statistiques s
							WHERE
								(s.Commercial = ? OR s.Commercial_support = ?)
								AND s.Mois < (CASE WHEN 1 THEN MONTH(CURDATE()) ELSE 13 END)
								AND s.cl_ident <> 0
							";

						$result = DB::select($query, [$commercial, $commercial]);
						$total_1 = $result[0]->total_clients;
						$total_clients += $total_1;
						// sebastien
						if (auth()->user()->id == 10) {
							unset($customers[201]);
							unset($customers[510]);
						}
					//}
				}

				$total_clients = CompteClient::where('etat_id', 2)->where('agence_ident', auth()->user()->agence_ident)->count(); //

				$prospects = CompteClient::where('etat_id', 1)->where('agence_ident', auth()->user()->agence_ident)->get();
/*
				$client_ids = CompteClient::where('adv', auth()->user()->id)->pluck('id');

				$rendezvous = RendezVous::whereIn('mycl_id', $client_ids)
					->where('Started_at', '>=', $now)
					->orderBy('Started_at', 'desc')
					->orderBy('id', 'desc')
					->get();
*/

				$users_id= DB::table('representant')->whereRaw("FIND_IN_SET(?, agence)", [auth()->user()->agence_ident])->pluck('users_id');
				$users=DB::table("users")->whereIn('id',$users_id)->orderBy('lastname','asc')->get();
				$rendezvous = RendezVous::where('Started_at', '>=', $now)
					->where(function ($Query) use($users_id) {
						$Query->whereIn('user_id', $users_id)
							->orWhere('user_id',auth()->id());
					})
					->orderBy('Started_at', 'desc')
					->orderBy('id', 'desc')
					->get();

				break;

			case 7  :

				//$commerciaux = DB::table("representant")->where('type', 'Commercial terrain')->pluck('id');
				$commerciaux = DB::table("representant")->where('type', 'Commercial terrain')->whereRaw("FIND_IN_SET(?, agence)", [auth()->user()->agence_ident])->pluck('id');

				foreach ($commerciaux as $commercial) {
					$rep = DB::table("representant")->find($commercial);
					$user = User::find($rep->users_id);

					//if ($user->agence_ident == auth()->user()->agence_ident /*|| auth()->user()->id==10*/) {
						DB::select("SET @p0='$commercial' ;");
						$customers[$commercial] =  DB::select("  CALL `sp_stats_commercial_client_top5`(@p0); ");
						//}

						$query = "
						SELECT COUNT(DISTINCT s.cl_ident) AS total_clients
						FROM Statistiques s
						WHERE
							(s.Commercial = ? OR s.Commercial_support = ?)
							AND s.Mois < (CASE WHEN 1 THEN MONTH(CURDATE()) ELSE 13 END)
							AND s.cl_ident <> 0
						";

						$result = DB::select($query, [$commercial, $commercial]);
						$total_1 = $result[0]->total_clients;
						$total_clients += $total_1;
						// sebastien
						if (auth()->user()->id == 10) {
							unset($customers[201]);
							unset($customers[510]);
						}
					//}
				}
				$client_ids = CompteClient::where('adv', auth()->user()->id)->pluck('id');

				$rendezvous = RendezVous::whereIn('mycl_id', $client_ids)
				->where('Started_at', '>=', $now)
				->orderBy('Started_at', 'desc')
				->orderBy('id', 'desc')
				->get();

				//$total_clients = CompteClient::where('etat_id', 2)->where('commercial', auth()->user()->agence_ident)->count(); //Paris
				$Rep = DB::table('representant')->where('users_id',auth()->id())->first();
				$total_clients=CompteClient::where('etat_id', 2)->where(function ($q) use ($Rep) {
					$q->orWhere('commercial', $Rep->id)
					->orWhere('commercial_support', $Rep->id);
				})->count();
				break;

				case 8  :

					$commerciaux = DB::table("representant")->where('type', 'Commercial terrain')->pluck('id');
					$commerciaux = DB::table("representant")->where('type', 'Commercial terrain')->whereRaw("FIND_IN_SET(?, agence)", [auth()->user()->agence_ident])->pluck('id');


					foreach ($commerciaux as $commercial) {
						$rep = DB::table("representant")->find($commercial);
						$user = User::find($rep->users_id);

						//if ($user->agence_ident == auth()->user()->agence_ident /*|| auth()->user()->id==10*/) {
							DB::select("SET @p0='$commercial' ;");
							$customers[$commercial] =  DB::select("  CALL `sp_stats_commercial_client_top5`(@p0); ");
							//}

							$query = "
							SELECT COUNT(DISTINCT s.cl_ident) AS total_clients
							FROM Statistiques s
							WHERE
								(s.Commercial = ? OR s.Commercial_support = ?)
								AND s.Mois < (CASE WHEN 1 THEN MONTH(CURDATE()) ELSE 13 END)
								AND s.cl_ident <> 0
							";

							$result = DB::select($query, [$commercial, $commercial]);
							$total_1 = $result[0]->total_clients;
							$total_clients += $total_1;
							// sebastien
							if (auth()->user()->id == 10) {
								unset($customers[201]);
								unset($customers[510]);
							}
						//}
					}

					$rendezvous = RendezVous::where('Started_at', '>=', $now)
					->orderBy('Started_at', 'desc')
					->orderBy('id', 'desc')
					->get();

					$total_clients = CompteClient::where('etat_id', 2)->count(); //Paris
					break;
		}


		/*
		//retours
		if(auth()->user()->role=='adv'|| auth()->user()->role=='admin' || auth()->user()->role=='respAG'  ){
			$retours = DB::table('CRM_RetourClient as rc')
			->join('client as c', 'rc.cl_id', '=', 'c.cl_ident')
			->where(function ($query) {
				$query->where('rc.Date_cloture', '0000-00-00')
					->orWhereNull('rc.Date_cloture');
			})
			->where('c.agence_ident', auth()->user()->agence_ident)
			->select('rc.*', 'c.agence_ident') // Select fields as needed
			->orderBy('rc.name', 'desc') // Adjust as needed; 'name' should be in `rc` or `c`
			->get();
		}


		$query = "SELECT COUNT(DISTINCT cl_ident) as total FROM Statistiques WHERE agence_ident = ? AND annee = YEAR(CURDATE())";

		$total_clients= CompteClient::where('etat_id',2)->where('agence_ident',$agence_id)->count();
		$total1 = DB::select($query, [$agence_id]);
		$total_1=$total1[0]->total;

 		$rep=DB::table("representant")->where('users_id',auth()->user()->id)->first();
		if(isset($rep)){
			$rep_id=$rep->id;
			//$rep_id=10;

			DB::select("SET @p0='$rep_id' ;");
			$clients =  DB::select("  CALL `sp_stats_commercial_client_top5`(@p0); ");

			$query = "
			SELECT COUNT(DISTINCT s.cl_ident) AS total_clients
			FROM Statistiques s
			WHERE
				(s.Commercial = ? OR s.Commercial_support = ?)
				AND s.Mois < (CASE WHEN 1 THEN MONTH(CURDATE()) ELSE 13 END)
				AND s.cl_ident <> 0";

			$result = DB::select($query, [$rep_id, $rep_id]);
			$total_clients = $result[0]->total_clients;

		}else{

			DB::select("SET @p0='".$agence_id."' ;");
			$clients =  DB::select("  CALL `sp_stats_commercial_client_top5`(@p0); ");

			$query = "SELECT COUNT(DISTINCT cl_ident) as total FROM Statistiques WHERE agence_ident = ? AND annee = YEAR(CURDATE())";

			$total_clients= CompteClient::where('etat_id',2)->where('agence_ident',$agence_id)->count();
			//$total1 = DB::select($query, [$agence_id]);
			//$total_1=$total1[0]->total;
		}


		//if(auth()->user()->role=='adv'    ){

			$client_ids = CompteClient::where('adv',auth()->user()->id)->pluck('id');

			$rendezvous=RendezVous::whereIn('mycl_id',$client_ids)
			->where('Started_at', '>=', $now)
			->orderBy('Started_at', 'desc')
			->orderBy('id','desc')
			->get();

			DB::select("SET @p0='".$agence_id."' ;");
			$clients =  DB::select("  CALL `sp_stats_agence_client_top5`(@p0); ");
			$query = "SELECT COUNT(DISTINCT cl_ident) as total FROM Statistiques WHERE agence_ident = ? AND annee = YEAR(CURDATE())";

			$total_clients= CompteClient::where('etat_id',2)->where('agence_ident',$agence_id)->count();
			//$total1 = DB::select($query, [$agence_id]);
			//$total_1=$total1[0]->total;
		//}else{

			if(auth()->user()->role=='commercial'){

				$rep=DB::table("representant")->where('users_id',auth()->user()->id)->first();
				if(isset($rep)){
					$rep_id=$rep->id;
					//$rep_id=10;

					DB::select("SET @p0='$rep_id' ;");
					$clients =  DB::select("  CALL `sp_stats_commercial_client_top5`(@p0); ");

					$total_clients = CompteClient::where(function ($Query) use ($rep_id) {
						$Query->where('commercial', $rep_id)
							->orWhere('commercial_support', $rep_id);
					})->where('etat_id',2)->count();

				}
			}

			//if(auth()->user()->role=='respAG'){
			if(auth()->user()->user_role==3 || auth()->user()->user_role==4 || auth()->user()->user_role==6 || auth()->user()->user_role== 7 || auth()->user()->user_role== 8 ){
				// here
				$users_ids = User::where('agence_ident',auth()->user()->agence_ident)->pluck('id');

				$rendezvous=RendezVous:://where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
				whereIn('user_id',$users_ids)
				->where('Started_at', '>=', $now)
				->orderBy('Started_at', 'desc')
				->orderBy('id','desc')
				->get();

				//$prospects
				$prospects=CompteClient::where('agence_ident',auth()->user()->agence_ident)->where('etat_id',1)->get();

				$commerciaux= DB::table("representant")->where('type','Commercial terrain')->pluck('id');

				if(auth()->user()->id==10){
					$commerciaux= DB::table("representant")->where('type','Commercial terrain')->orWhere('id',26)->pluck('id');

				}
				foreach($commerciaux as $commercial){
					$rep=DB::table("representant")->find($commercial);
					$user= User::find($rep->users_id);

					if($user->agence_ident==auth()->user()->agence_ident  ){
						DB::select("SET @p0='$commercial' ;");
						$customers[$commercial] =  DB::select("  CALL `sp_stats_commercial_client_top5`(@p0); ");
						//}

						$query = "
						SELECT COUNT(DISTINCT s.cl_ident) AS total_clients
						FROM Statistiques s
						WHERE
							(s.Commercial = ? OR s.Commercial_support = ?)
							AND s.Mois < (CASE WHEN 1 THEN MONTH(CURDATE()) ELSE 13 END)
							AND s.cl_ident <> 0
						";

						$result = DB::select($query, [$commercial, $commercial]);
						$total_c= $result[0]->total_clients;
						$total_clients+=$total_c;
						// sebastien
						if(auth()->user()->id==10){
							unset($customers[201]);
							unset($customers[510]);
						}
					}

				}

				$total_clients= CompteClient::where('etat_id',2)->where('agence_ident',auth()->user()->agence_ident)->count(); //Paris

			}else{
				$rendezvous=RendezVous:://where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
				where('user_id',auth()->user()->id)
				->where('Started_at', '>=', $now)
				->orderBy('Started_at', 'desc')
				->orderBy('id','desc')
				->get()->take(5);

			}

		//}

		if(auth()->user()->id==10 )
			$offres=Offre::where('type','Hors TG - Affinage')->where('statut',null)->get();
		elseif(auth()->user()->id==39 || auth()->user()->id ==1 )
			$offres=Offre::where('type','Hors TG - Apprêts/Bij/DP')->where('statut',null)->get();
		else
			$offres=Offre::where('statut',null)->get();
		*/
		$userToken = GoogleToken::where('user_id', auth()->id())->first();

        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Tableau de bord"]);

		return view('dashboard.dashboard', compact('totaux_clients', 'rendezvous', 'clients', 'total_clients', 'total_1','offres', 'retours', 'agence', 'prospects', 'commerciaux', 'customers', 'userToken', 'rendezvous_passes'));
	}
    public function check()
    {
		return auth()->user()->popup_accepted ;
    }

    public function accept(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user->popup_accepted = true;
            $user->save();
			return 1;
        }
		
        
    }

	function stats_spot($type)
	{
		// Récupérer les métaux sélectionnés depuis la requête
		$metals = request()->get('metals', 'OR,ARGENT,PLATINE,PALLADIUM'); // Valeur par défaut si non spécifié
		
		DB::select("SET @p0='$type';");
		DB::select("SET @p1='$metals';");
		$result = DB::select("CALL `sp_stats_spot_operations_v3`(@p0, @p1);");
		return $result;
	}


	public function exportMetalStats(Request $request)
	{
		// Récupérer les paramètres
		$type = $request->get('type', 'jour');
		$metals = $request->get('metals', []);
		
		// Utilisez votre service d'exportation
		return app(StatsExportService::class)->exportMetalStats($type, $metals);
	}
} // end class
