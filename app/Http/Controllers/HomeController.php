<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\StatsController;

use App\Models\User;
use App\Models\CompteClient;
use App\Models\Offre;
use App\Models\RendezVous;
use App\Models\RetourClient;
use App\Models\Tache;
use App\Services\PhoneService;
use Illuminate\Support\Facades\App;
use App\Services\SendMail;
use  PDO;
use Carbon\Carbon;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware(['auth'])->except(['checkexiste', 'send_demand']);
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

	public function adminhome()
	{
		if (auth()->user()->user_type == 'admin') {

			$now = Carbon::now();
			$representants=DB::table("representant")->get();

			$rendezvous=RendezVous::where('Started_at', '>=', $now)
			->orderBy('Started_at', 'asc')
			->orderBy('heure_debut','asc')
			->get();

			$retours=RetourClient::where('Date_cloture','0000-00-00')
			->orWhere('Date_cloture',null)
			//->limit(20)
			->orderBy('name','desc')->get();

			$taches=Tache::where('DateTache','like',date('Y-m-d').'%' )->orderBy('heure_debut','asc')->get();
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


			return view('adminhome',compact('retours','rendezvous','taches','representants',
			'total_clients_1','total_clients_2','total_clients_3','total_clients_4','total_clients_5','total_clients_6','total_clients_7','total_clients_8','total_clients_9',
			'total_1','total_2','total_3','total_4','total_5','total_6','total_7','total_8','total_9'));

		} else {
			$rendezvous=RendezVous::where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
			->orWhere('user_id',auth()->user()->id)
			->orderBy('id','desc')->get();

			$rendezvous=RendezVous::get();


			return view('dashboard',compact('rendezvous'));
		}
	}

	public function agenda(Request $request)
	{
		$user=$request->get('user');
		$users=array();

		$role = auth()->user()->role;
		$user_id = auth()->user()->id;
		$agence_id = auth()->user()->agence_ident;

		if($role =='admin'|| $role =='respAG' || $role =='adv' ){
			//$representants=DB::table("representant")->get();
			$users=DB::table("users")->where('username','like','%@saamp.com')->get();
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
			if($role =='respAG' || $role =='adv' || $role =='admin'  ){
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
		$user=$request->get('user');
		$now = Carbon::now();
		$annee=$request->get('annee');
		$mois=$request->get('mois');
		$month=$annee.'-'.sprintf("%02d", $mois);
		$name="";

		if($user!= auth()->user()->id && auth()->user()->user_type!='admin' ){
			return view('welcome');
		}


		if($user>0){
			$User=User::find($user);
			$name=$User->name.' '.$User->lastname;
			$rendezvous=RendezVous::/*where(function($q) use ($now,$user,$User) {
				$q->where('user_id',$user)
				->orWhere('Attribue_a',$User->name.' '.$User->lastname);
			})*/
			where('user_id', $user)
			->where('Started_at', 'like',  $month. '%')
 			->orderBy('Started_at', 'asc')
			->orderBy('heure_debut','asc')
			->get();

		}else{
			$rendezvous=RendezVous::/*where(function($q) use ($now,$user,$User) {
				$q->where('user_id',$user)
				->orWhere('Attribue_a',$User->name.' '.$User->lastname);
			})*/
			where('user_id', auth()->user()->id)
			->where('Started_at', 'like',  $month. '%')
 			->orderBy('Started_at', 'asc')
			->orderBy('heure_debut','asc')
			->get();
		}

		return view('rendezvous.print_list',compact('rendezvous','user','name','mois','annee'));

	}


	public function rendesvous_ext(Request $request)
	{
		$representants=DB::table("representant")->get();
		$user=$request->get('user');

		$rendezvous=RendezVous:://where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
		where('user_id',auth()->user()->id)
		->where('AccountId',0)
		->orderBy('id','desc')->get();

		if($user>0){
			$User=User::find($user);
			$rendezvous=RendezVous:://where('Attribue_a',$User->name.' '.$User->lastname)
			where('user_id',$user)
			->where('AccountId',0)
			->orderBy('id','desc')
			->get();
		}else{
			$rendezvous=RendezVous:://where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
			where('user_id',auth()->user()->id)
			->where('AccountId',0)
			->orderBy('id','desc')
			->get();
		}


		return view('agenda',compact('rendezvous','representants','user'));

	}

	public function dashboard()
	{
		//$rendezvous=RendezVous::get();
		$now = Carbon::now();

		$rendezvous=RendezVous:://where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
		where('user_id',auth()->user()->id)
		->where('Started_at', '>=', $now)
		->orderBy('Started_at', 'desc')
		->orderBy('id','desc')
		->get()->take(5);


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
				AND s.cl_ident <> 0
		";

			$result = DB::select($query, [$rep_id, $rep_id]);
			$total_clients = $result[0]->total_clients;

		}else{
			$clients = array();
			$total_clients=0;
		}

		if(auth()->user()->id==10 )
			$offres=Offre::where('type','Hors TG')->where('statut',null)->get();
		elseif(auth()->user()->id==39 || auth()->user()->id ==1 )
			$offres=Offre::where('type','Apprêts/Bij/DP')->where('statut',null)->get();
		else
			$offres=array();



 		return view('dashboard',compact('rendezvous','clients','total_clients','offres'));
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

			return view('home',compact('agences','users','stats','stats2','stats3','stats4','stats5','stats6','representants','commercial'));
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
