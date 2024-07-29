<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\RendezVous;
use App\Models\CompteClient;
use App\Models\Contact;
use App\Models\RetourClient;
use App\Models\Tache;
use App\Services\PhoneService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ClientsController extends Controller
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



	public function create()
	{
		$agences = DB::table('agence')->get();
		return view('clients.create',compact('agences'));

	}

	public function show($id)
	{
		$client=CompteClient::find($id);
		$agences = DB::table('agence')->get();

		return view('clients.show',compact('client','agences'));
	}


	public function update(Request $request, $id)
    {
        $request->validate([
            'Nom' => 'required',
            'Rue' => 'required',
            'Client_Prospect' => 'required',
            'BillingAddress_city' => 'required',
            'Pays' => 'required',
            'CountryCode' => 'required',
            'postalCode' => 'required',
        ]);

		$client = CompteClient::find($id);

		$client->update($request->all());

		return redirect()->route('compte_client.show', $id)
				->with('success', 'Client modifié');

	}

	public function store(Request $request)
    {
        $request->validate([
            'Nom' => 'required',
            'cl_ident' => 'required',
            'cl_ident' => 'required',
            'Rue' => 'required',
            'Client_Prospect' => 'required',
            'BillingAddress_city' => 'required',
            'Pays' => 'required',
            'CountryCode' => 'required',
            'postalCode' => 'required',
        ]);

        $client=CompteClient::create($request->all());
		return redirect()->route('fiche', $client->id)
		->with('success','Client ajouté');


	}


	public function fiche($id)
	{
		$client=CompteClient::find($id);
		$contacts=$retours=array();
		if($client->Client_Prospect!='COMPTE PROSPECT'){
			$contacts=Contact::where('cl_ident',$client->cl_ident)->get();
			$retours=RetourClient::where('cl_id',$client->cl_ident)->get();
		}
		$agence_name='';
		$agence = DB::table('agence')->where('agence_ident',$client->agence_ident)->first();

		if(isset($agence))
			$agence_name=$agence->agence_lib;

		$stats=null;
		try{

			DB::select("SET @p0=$client->cl_ident ;");
			DB::select("SET @p1=1  ;");
			$stats=  DB::select('call `sp_stats_client`(@p0,@p1); ');

		}catch(\Exception $e){
			\Log::error($e->getMessage());
		}
		if($client->Id_Salesforce!='')
			$taches=Tache::where('ID_Compte',$client->Id_Salesforce)->get();
		else
			$taches=Tache::where('ID_Compte',$client->id)->get();
		//$appels=array();
		$callData=PhoneService::data($client->token_phone);

		DB::select("SET @p0='$client->cl_ident'  ;");
		$commandes =  DB::select(" CALL `sp_accueil_liste_commandes`(@p0); ");
/*
		$tous_appels=$callData['incoming'] ?? array();
		$phone=$client->phone;
		$appels = array_filter($tous_appels, function($appel) use ($phone) {
			return $appel['number'] === $phone;
		});
*/
	/*
		$callData=PhoneService::data($client->token_phone);
		$appels=$callData['incoming'] ?? array();


		view:
		<!--
                            @php $i=0; @endphp
                            @foreach($appels as $appel)
                            @if( str_replace(' ', '', $appel['number']) == str_replace(' ', '', $client->Phone ) )
                            @php $i++; $date= htmlspecialchars(date('d/m/Y H:i', strtotime($appel['datetime']))); @endphp
                            <tr>
                                <td>{{$date}}</td>
                                <td><i class="fas fa-phone-square-alt"></i> {{ htmlspecialchars($appel['number']) }}</td>
                            </tr>
                            @endif
                            @endforeach-->
*/
		$rendezvous=RendezVous::where('Account_Name',$client->Nom)
		->get();

		$now = Carbon::now();
/*
		$Proch_rendezvous = RendezVous::where(function ($query) use ($client) {
			$query->where('AccountId', $client->id)
				->orWhere('AccountId', $client->Id_Salesforce);
		})
		->where('Started_at', '>=', $now)
		->orderBy('Started_at', 'desc')
		->get();

		$Anc_rendezvous = RendezVous::where(function ($query) use ($client) {
			$query->where('AccountId', $client->id)
				->orWhere('AccountId', $client->Id_Salesforce);
		})
		->where('Started_at', '<', $now)
		->orderBy('Started_at', 'desc')
		->get();
*/

		$Proch_rendezvous = RendezVous::where('Account_Name', $client->Nom)
		->where('Started_at', '>=', $now)
		->orderBy('Started_at', 'desc')
		->get();

		$Anc_rendezvous = RendezVous::where('Account_Name', $client->Nom)
		->where('Started_at', '<', $now)
		->orderBy('Started_at', 'desc')
		->get();
		return view('clients.fiche',compact('client','contacts','retours','Proch_rendezvous','Anc_rendezvous','taches','stats','commandes','agence_name'));
	}


	public function finances($id)
	{
		$client=CompteClient::find($id);
		return view('clients.finances',compact('client'));
	}


	public function phone($id)
	{
		$client=CompteClient::find($id);
		//$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiI1NTk4ODM2IiwiYXVkIjoiKiIsImlzcyI6InR2eCIsImlhdCI6MTcxNjU0NjU5MCwianRpIjoiMTg5OTQ0NjYifQ.4_0fCiH0KqsKHbtI3xnp1VkrRamENo_qf7Uecs_0b4WhczutEMUJZlHzhm4HZqHgKBbCxxyv3E8mX5nl-JQm4Q';
		$token=$client->token_phone;
 		$callData=PhoneService::data($token);
		return view('clients.phone',compact('client','callData','token'));

	}


	public function folder($id)
	{
		$client=CompteClient::find($id);
		return view('clients.folder',compact('client'));

	}

	public function search(Request $request)
	{
		$query = CompteClient::query();

		// Application du filtre pour le type de client/prospect
		$type = $request->get('type');
		if ($type == 1) {
			$query->where('Client_Prospect', 'like', '%CLIENT SAAMP%');
		} elseif ($type == 2) {
			$query->where('Client_Prospect', 'like', '%PROSPECT%');
		}else{
			$query->where('Client_Prospect', '<>', 'CLIENT LFMP');
		}

		// Application des filtres pour les autres champs
		if ($request->has('Nom') && $request->Nom) {
			$query->where('Nom', 'like',  $request->Nom . '%');
		}

		if ($request->has('Rue') && $request->Rue) {
			$query->where('Rue', 'like', '%' . $request->Rue . '%');
		}

		if ($request->has('BillingAddress_city') && $request->BillingAddress_city) {
			$query->where('BillingAddress_city', 'like', '%' . $request->BillingAddress_city . '%');
		}

		if ($request->has('Departement') && $request->Departement) {
			$query->where('Departement',  intval($request->Departement) );
		}

		if ($request->has('Pays') && $request->Pays) {
			$query->where('Pays', 'like', '%' . $request->Pays . '%');
		}

		// Application du tri
		$tri = $request->get('tri');
		if ($tri == 1) {
			$query->orderBy('Nom');
		} elseif ($tri == 2) {
			$query->orderBy('Pays')->orderBy('BillingAddress_city');
		}

		// Exécution de la requête
		$clients = $query->get()->take(50);

		// Retourne la vue avec les résultats de la recherche
		return view('clients.search', compact('clients','request'));
	}

} // end class
