<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\StatsController;

use App\Models\User;
use App\Models\CompteClient;
use App\Services\PhoneService;
use Illuminate\Support\Facades\App;
use App\Services\SendMail;
use  PDO;

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
		if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'adv') {
			return view('adminhome');
		} else {
			StatsController::stats();
		}
	}

	public function dashboard()
	{
 		return view('dashboard');
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

			$stats = null;//self::stats_commercial($request);
            $stats2 = null;//self::stats_commercial_client($request);
            $stats3 = null;//self::stats_agence($request);
            $stats4 = null;//self::stats_agence_client($request);
            $stats5 = null;//self::stats_agences($request);
			return view('home',compact('agences','users','stats','stats2','stats3','stats4','stats5','representants','commercial'));
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
