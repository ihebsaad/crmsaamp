<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\StatsController;

use App\Models\Agence;
use App\Models\User;
use App\Models\CompteClient;
use App\Models\Offre;
use App\Models\RendezVous;
use App\Models\RetourClient;
use App\Models\Tache;
use App\Models\GoogleToken;
use App\Services\PhoneService;
use Illuminate\Support\Facades\App;
use App\Services\SendMail;
use  PDO;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware(['auth'])->except(['checkexiste', 'send_demand','confid','regles']);
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */




	public function welcome()
	{
		return view('welcome');
	}

	public function confid()
	{
		return view('calendar.confid');
	}

	public function regles()
	{
		return view('calendar.regles');
	}

	public function adminhome()
	{
		if (auth()->user()->user_type == 'admin' || auth()->user()->role == 'admin' || auth()->user()->role == 'dirQUA' ) {

			$offres=Offre::where('statut',null)->get();
			$now = Carbon::now();
			$representants=DB::table("representant")->get();
			$users=DB::table("users")->where('username','like','%@saamp.com')->orderBy('lastname','asc')->get();
			$prospects=CompteClient::where('agence_ident',auth()->user()->agence_ident)->where('etat_id',1)->get();

			$rendezvous=RendezVous::where('Started_at', '>=', $now)
			->orderBy('Started_at', 'asc')
			->orderBy('heure_debut','asc')
			->get();

			$retours=RetourClient::where('Date_cloture','0000-00-00')
			->orWhere('Date_cloture',null)
			//->limit(20)
			->orderBy('name','desc')->get();

			//taches=Tache::where('DateTache','like',date('Y-m-d').'%' )->orderBy('heure_debut','asc')->get();

				// Récupérer les filtres
				$nom = request()->input('nom');
				$cl_ident = request()->input('cl_ident');
				// Récupérer les tâches
				$tasks = Tache::where('DateTache','like',date('Y-m-d').'%' )->orderBy('heure_debut','asc')->get();

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
					->where('prise_contact_as400.date_pr','like',date('Y-m-d').'%' )
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

			$total_clients_1= CompteClient::where('etat_id',2)->where('agence_ident',40)->count(); //Paris
			$total1 = DB::select($query, [40]);
			$total_1=$total1[0]->total;
			$total_clients_2= CompteClient::where('etat_id',2)->where('agence_ident',42)->count(); // Lyon
			$total2 = DB::select($query, [42]);
			$total_2=$total2[0]->total;
			$total_clients_3= CompteClient::where('etat_id',2)->where('agence_ident',43)->count(); //Marseille
			$total3 = DB::select($query, [43]);
			$total_3=$total3[0]->total;
			$total_clients_4= CompteClient::where('etat_id',2)->where('agence_ident',10)->count(); //AUBAGNE
			$total4 = DB::select($query, [10]);
			$total_4=$total4[0]->total;
			$total_clients_5= CompteClient::where('etat_id',2)->where('agence_ident',200)->count(); //
			$total5 = DB::select($query, [200]);
			$total_5=$total5[0]->total;
			$total_clients_6= CompteClient::where('etat_id',2)->where('agence_ident',100)->count(); //
			$total6 = DB::select($query, [100]);
			$total_6=$total6[0]->total;
			$total_clients_7= CompteClient::where('etat_id',2)->where('agence_ident',201)->count(); //
			$total7 = DB::select($query, [201]);
			$total_7=$total7[0]->total;
			$total_clients_8= CompteClient::where('etat_id',2)->where('agence_ident',202)->count(); //
			$total8 = DB::select($query, [202]);
			$total_8=$total8[0]->total;
			$total_clients_9= CompteClient::where('etat_id',2)->where('agence_ident',203)->count(); //
			$total9 = DB::select($query, [203]);
			$total_9=$total9[0]->total;

			$userToken = GoogleToken::where('user_id', auth()->id())->first();

			$stats = DB::select('call `sp_stats_agence_nature_poids`();');

			return view('dashboard.adminhome',compact('retours','rendezvous','taches','representants','offres','userToken','users','stats','prospects',
			'total_clients_1','total_clients_2','total_clients_3','total_clients_4','total_clients_5','total_clients_6','total_clients_7','total_clients_8','total_clients_9',
			'total_1','total_2','total_3','total_4','total_5','total_6','total_7','total_8','total_9'));

		} else {
			$rendezvous=RendezVous::where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
			->orWhere('user_id',auth()->user()->id)
			->orderBy('id','desc')->get();

			$rendezvous=RendezVous::get();


			return view('dashboard.dashboard',compact('rendezvous'));
		}
	}
/*
	public function agenda(Request $request)
	{
		$user=$request->get('user');
		$users=array();

		$role = auth()->user()->role;
		$user_id = auth()->user()->id;
		$agence_id = auth()->user()->agence_ident;

		if($role =='admin' || $role =='respAG' || $role =='adv' || $role =='compta' ){
			//$representants=DB::table("representant")->get();
			$users=DB::table("users")->where('username','like','%@saamp.com')->orderBy('lastname','asc')->get();
		}
		if($role =='respAG' || $role =='adv' ){
			$users=DB::table("users")
			->where('username','like','%@saamp.com')
			->where('agence_ident',$agence_id)
			//->where('role','commercial')
			//->whereIn('role', ['commercial', 'user'])
			->get();
		}

		if($user>0){
			if($role =='respAG' || $role =='adv' || $role =='admin' || $role =='compta' ){
			$User=User::find($user);
			$rendezvous=RendezVous::where('Attribue_a',$User->name.' '.$User->lastname)
			->orWhere('user_id',$user)
			//->where('AccountId','>',0)
			->orderBy('id','desc')
			->get();
			}else{
				return view('welcome');
			}
		}else{
			$rendezvous=RendezVous::where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
			->orWhere('user_id',auth()->user()->id)
			//->where('AccountId','>',0)
			->orderBy('id','desc')
			->get();
		}


		return view('agenda',compact('rendezvous','user','users'));

	}

	public function print_agenda(Request $request)
	{
		$user = $request->get('user');
		$date_debut = $request->get('date_debut');
		$date_fin = $request->get('date_fin');
		$name = "";

		// Validation des dates
		if (!$date_debut || !$date_fin) {
			return back()->with('error', __('msg.Please provide a valid date range.'));
		}
		// Récupération des rendez-vous en fonction de l'utilisateur et de la plage de dates
		if ($user > 0) {
			$User = User::find($user);
			$name = $User->name . ' ' . $User->lastname;
			$rendezvous = RendezVous::where('user_id', $user)
				->whereBetween('Started_at', [$date_debut, $date_fin])
				->orderBy('Started_at', 'asc')
				->orderBy('heure_debut', 'asc')
				->get();
		} else {
			$rendezvous = RendezVous::where('user_id', auth()->user()->id)
				->whereBetween('Started_at', [$date_debut, $date_fin])
				->orderBy('Started_at', 'asc')
				->orderBy('heure_debut', 'asc')
				->get();
		}

		return view('rendezvous.print_list', compact('rendezvous', 'user', 'name', 'date_debut', 'date_fin'));
	}

	public function pdf_agenda(Request $request)
	{
		$user = $request->get('user');
		$date_debut = $request->get('date_debut');
		$date_fin = $request->get('date_fin');
		$name = "";

		// Validation des dates
		if (!$date_debut || !$date_fin) {
			return back()->with('error', __('msg.Please provide a valid date range.'));
		}
		// Récupération des rendez-vous en fonction de l'utilisateur et de la plage de dates
		if ($user > 0) {
			$User = User::find($user);
			$name = $User->name . ' ' . $User->lastname;
			$rendezvous = RendezVous::where('user_id', $user)
				->whereBetween('Started_at', [$date_debut, $date_fin])
				->orderBy('Started_at', 'asc')
				->orderBy('heure_debut', 'asc')
				->get();
		} else {
			$rendezvous = RendezVous::where('user_id', auth()->user()->id)
				->whereBetween('Started_at', [$date_debut, $date_fin])
				->orderBy('Started_at', 'asc')
				->orderBy('heure_debut', 'asc')
				->get();
		}

		$date=date('d_m_Y_H_i');
		$pdf = PDF::loadView('rendezvous.pdf_list', compact('rendezvous', 'user', 'name', 'date_debut', 'date_fin'));
		return $pdf->stream('rendezvous-' . $name . '-'.$date.'.pdf');
	}

*/

	public function dashboard()
	{
		$agence_id = auth()->user()->agence_ident;
		$agence =Agence::where('agence_ident',$agence_id)->first();
		$now = Carbon::now();
		$prospects=array();
		$commerciaux=array();
		$customers=array();
		$retours = array();

		//rendez vous
		$rendezvous_passes=RendezVous::where('Started_at', '<=', $now)
		->where('user_id', auth()->id())
		->where('statut', 1)
		->orderBy('Started_at', 'asc')
		->orderBy('heure_debut','asc')
		->get();

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


		if(auth()->user()->role=='adv'    ){

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
		}else{

			if(auth()->user()->role=='commercial'){
/*

				DB::select("SET @p0='".$agence_id."' ;");
				$clients =  DB::select("  CALL `sp_stats_commercial_client_top5`(@p0); ");

				$query = "SELECT COUNT(DISTINCT cl_ident) as total FROM Statistiques WHERE agence_ident = ? AND annee = YEAR(CURDATE())";

				$total_clients= CompteClient::where('etat_id',2)->where('agence_ident',$agence_id)->count();
				//$total1 = DB::select($query, [$agence_id]);
				//$total_1=$total1[0]->total;
*/

				$rep=DB::table("representant")->where('users_id',auth()->user()->id)->first();
				if(isset($rep)){
					$rep_id=$rep->id;
					//$rep_id=10;

					DB::select("SET @p0='$rep_id' ;");
					$clients =  DB::select("  CALL `sp_stats_commercial_client_top5`(@p0); ");
/*
					$query = "
					SELECT COUNT(DISTINCT s.cl_ident) AS total_clients
					FROM Statistiques s
					WHERE
						(s.Commercial = ? OR s.Commercial_support = ?)
						AND s.Mois < (CASE WHEN 1 THEN MONTH(CURDATE()) ELSE 13 END)
						AND s.cl_ident <> 0";

					$result = DB::select($query, [$rep_id, $rep_id]);
					$total_clients = $result[0]->total_clients;

					$query = "SELECT COUNT(DISTINCT cl_ident) as total FROM Statistiques WHERE agence_ident = ? AND annee = YEAR(CURDATE())";

					*/
					$total_clients = CompteClient::where(function ($Query) use ($rep_id) {
						$Query->where('commercial', $rep_id)
							->orWhere('commercial_support', $rep_id);
					})->where('etat_id',2)->count();

				}
			}

			if(auth()->user()->role=='respAG'){
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

					if($user->agence_ident==auth()->user()->agence_ident /*|| auth()->user()->id==10*/){
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

		}

		if(auth()->user()->id==10 )
			$offres=Offre::where('type','Hors TG - Affinage')->where('statut',null)->get();
		elseif(auth()->user()->id==39 || auth()->user()->id ==1 )
			$offres=Offre::where('type','Hors TG - Apprêts/Bij/DP')->where('statut',null)->get();
		else
			$offres=Offre::where('statut',null)->get();

			$userToken = GoogleToken::where('user_id', auth()->id())->first();


 		return view('dashboard.dashboard',compact('rendezvous','clients','total_clients','total_1','offres','retours','agence','prospects','commerciaux','customers','userToken','rendezvous_passes'));
	}

	public function help()
	{
 		return view('help');
	}


	public function index()
	{
		if (auth()->user()->user_type == 'visitor') {
			return view('visitor');
		} else {
			//StatsController::stats();
			$agences= DB::table('agence')->get();
			$users=DB::table("users")->where('username','like','%@saamp.com')->orderBy('lastname','asc')->get();
			$representants= DB::table('representant')->whereNull('remplace_par')->orderBy('nom','asc')->get();

			if(auth()->user()->user_role == 4 )
			{
				$representants= DB::table('representant')->whereRaw("FIND_IN_SET(?, agence)", [auth()->user()->agence_ident])->get();
				//$agences = explode(',', $Rep->agence);
				//$users=DB::table("users")->whereIn('id',$users_id)->orderBy('lastname','asc')->get();
			}


			$commercial=false;
			foreach($representants as $rep){
				if(auth()->user()->id==$rep->users_id )
				{
					$commercial=true;break;
				}
			}

			$stats=$stats2=$stats3=$stats4=$stats5=$stats6= null;

			return view('home',compact('agences','users','stats','stats2','stats3','stats4','stats5','stats6','representants','commercial'));
		}
	}


		public function stats_tasks(Request $request)
	{
		if (auth()->user()->user_type == 'visitor') {
			return view('visitor');
		} else {
			$agences= DB::table('agence')->get();

			$debut = $request->get('debut') ??  date('Y-m-01') ;
			$fin = $request->get('fin') ??  date('Y-m-d');
			$agence= $request->get('agence') ?? 1;

			$stats=$stats2=null;

			return view('stats_tasks',compact('agences','stats','stats2','debut','fin','agence'));
		}
	}

	public function statistiques()
	{
		if (auth()->user()->user_type == 'visitor') {
			return view('visitor');
		} else {
			//StatsController::stats();
			$agences= DB::table('agence')->get();
			$users= DB::table('users')->where('user_type','<>','')->get();
			$representants= DB::table('representant')->orderBy('nom','asc')->get();

			$commercial=false;
			foreach($representants as $rep){
				if(auth()->user()->id==$rep->users_id )
				{
					$commercial=true;break;
				}
			}

			$stats=$stats2=$stats3=$stats4=$stats5=$stats6= null;

			return view('stats',compact('agences','users','stats','stats2','stats3','stats4','stats5','stats6','representants','commercial'));
		}
	}

	public function send_demand(Request $request)
	{
		$email = $request->get('email');
		$siret = $request->get('siret');

		SendMail::send(env('Admin_Email'), "Demande d'inscription", "Bonjour,<br>Nouvelle demande d'inscription<br><br><b>SIRET</b>:" . $siret . "<br><b>Email</b>:" . $email . "<br><br><i>L'équipe SAAMP</i>");
	}


	public function checkexiste(Request $request)
	{

		$val = trim($request->get('val'));
		$type = $request->get('type');

		if (trim($type) == 'email') {
			$contact = DB::table('contact')->where('email', $val)->first();
			if ($contact != null) {
				return $contact->cl_ident;
			} else {
				return 0;
			}
		}

		if (trim($type) == 'siret') {
			$client = DB::table('client')->where('siret', $val)->first();
			if ($client != null) {
				return $client->cl_ident;
			} else {
				return 0;
			}
		}
	}

	public function setlanguage(Request $request)
	{
		$user = $request->get('user');
		$lg =  $request->get('lg');

		User::where('id', $user)->update(array('lg' => $lg));
		app()->setLocale($lg);
	}

	public function updating(Request $request)
	{
		$id = $request->get('user');
		$champ = strval($request->get('champ'));
		if ($champ == 'password') {
			$val = bcrypt(trim($request->get('val')));
		} else {
			$val = $request->get('val');
		}

		User::where('id', $id)->update(array($champ => $val));
	}

	public function agence(Request $request)
	{
		$id = $request->get('id');
		$agence =  DB::table('agence')->where('agence_ident', $id)->first();
		return json_encode($agence);
	}


	public function phone()
	{

		$client=CompteClient::where('cl_ident',auth()->user()->client_id)->first();

		$token=$client->token_phone ?? 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiI1NTk4ODM2IiwiYXVkIjoiKiIsImlzcyI6InR2eCIsImlhdCI6MTcxNjU0NjU5MCwianRpIjoiMTg5OTQ0NjYifQ.4_0fCiH0KqsKHbtI3xnp1VkrRamENo_qf7Uecs_0b4WhczutEMUJZlHzhm4HZqHgKBbCxxyv3E8mX5nl-JQm4Q';
		$appels=PhoneService::data($client->token_phone);
/*
		$tous_appels=$callData['incoming'] ?? array();
		//dd($tous_appels);
		$phone=$client->phone;
		$appels = array_filter($tous_appels, function($appel) use ($phone) {
			return $appel['number'] === $phone;
		});
*/
		return view('clients.phone',compact('client','appels','token'));

	}



	public function commande($id)
	{
		$commande=self::detailscommandeprod($id);
		$commandes=self::detailscommandeprod($id);
		return view('products.commande',compact('commande','commandes'));
	}

	public static function detailscommandeprod($id_cmd)
	{
		DB::select("SET @p0='$id_cmd' ;");
		$result =  DB::select("  CALL `sp_produit_cmde_detail`(@p0); ");
		if ($result != null) {
			return  $result;
		}
	}

	public static function adresse($client_id)
	{
		DB::select("SET @p0='$client_id' ;");
		$result =  DB::select("  CALL `sp_liste_adresse_livraison`(@p0); ");

		if ($result != null) {
			return  $result;
		} else {
			return  'Error';
		}
	}

	public static function adresse2($client_id)
	{
		DB::select("SET @p0='$client_id' ;");

		$result =  DB::select("  CALL `sp_liste_adresse_livraison`(@p0); ");

		if ($result != null) {
			return  $result;
		} else {
			return  'Error';
		}
	}
} // end class
