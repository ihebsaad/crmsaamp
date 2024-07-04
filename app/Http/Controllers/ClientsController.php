<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\Contact;
use App\Models\RetourClient;
use App\Models\Tache;
use App\Services\PhoneService;
use Illuminate\Support\Facades\DB;


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
		return view('clients.create');
	}

	public function show($id)
	{
		$client=CompteClient::find($id);

		return view('clients.show',compact('client'));
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
		$contacts=Contact::where('cl_ident',$client->cl_ident)->get();
		$retours=RetourClient::where('cl_id',$client->cl_ident)->get();

		DB::select("SET @p0='$client->cl_ident'  ;");
		$stats=  DB::select('call `sp_stats_mois_pleins`(@p0); ');

		if($client->Id_Salesforce!='')
			$taches=Tache::where('ID_Compte',$client->Id_Salesforce)->get();
		else
			$taches=Tache::where('ID_Compte',$client->id)->get();
		//$appels=array();
		$callData=PhoneService::data($client->token_phone);

		DB::select("SET @p0='$client->cl_ident'  ;");
		$commandes =  DB::select(" CALL `sp_accueil_liste_commandes`(@p0); ");

		$tous_appels=$callData['incoming'] ?? array();
		$phone=$client->phone;
		$appels = array_filter($tous_appels, function($appel) use ($phone) {
			return $appel['number'] === $phone;
		});

	/*
		$callData=PhoneService::data($client->token_phone);
		$appels=$callData['incoming'] ?? array();
*/
		return view('clients.fiche',compact('client','contacts','retours','appels','taches','stats','commandes'));
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



	public function search(Request $request)
	{
		$query = CompteClient::query();

		// Application du filtre pour le type de client/prospect
		$type = $request->get('type');
		if ($type == 1) {
			$query->where('Client_Prospect', 'like', '%CLIENT%');
		} elseif ($type == 2) {
			$query->where('Client_Prospect', 'like', '%PROSPECT%');
		}

		// Application des filtres pour les autres champs
		if ($request->has('Nom') && $request->Nom) {
			$query->where('Nom', 'like', '%' . $request->Nom . '%');
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
