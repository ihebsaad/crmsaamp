<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\RendezVous;
use App\Models\CompteClient;
use App\Models\Contact;
use App\Models\RetourClient;
use App\Models\Tache;
use App\Models\Agence;
use App\Services\PhoneService;
use App\Services\GEDService;
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
		$representants = DB::table('representant')->get();
		$etats = DB::table('etat_client')->get();

		return view('clients.show',compact('client','agences','representants','etats'));
	}


	public function update(Request $request, $id)
    {
        $request->validate([
            'Nom' => 'required',
            'adresse1' => 'required',
            'ville' => 'required',
            'zip' => 'required',
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
            'adresse1' => 'required',
            'ville' => 'required',
            'zip' => 'required',
        ]);

        $client=CompteClient::create($request->all());
		return redirect()->route('fiche', $client->id)
		->with('success','Client ajouté');


	}


	public function fiche($id)
	{
		$client=CompteClient::find($id);
		$contacts=$retours =$taches=array();
		$representants = DB::table('representant')->get();

		$commercial=$support='';
		$rep_comm= DB::table('representant')->find($client->commercial);
		if(isset($rep_comm))
			$commercial=$rep_comm->prenom .' '. $rep_comm->nom;

		$rep_supp= DB::table('representant')->find($client->commercial_support);
		if(isset($rep_supp))
			$support=$rep_supp->prenom .' '. $rep_supp->nom;

		//if($client->Client_Prospect!='COMPTE PROSPECT'){
			if($client->cl_ident >0)
				$contacts=Contact::where('cl_ident',$client->cl_ident)
				->get();
			else
				$contacts=Contact::where('mycl_ident',$client->id)
				->get();

			$retours=RetourClient::where('cl_id',$client->cl_ident)->get();
		//}
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
		/*
		if($client->Id_Salesforce!='')
			$taches=Tache::where('ID_Compte',$client->Id_Salesforce)->get();
		else
			$taches=Tache::where('ID_Compte',$client->id)->get();
		*/

		//$taches=Tache::where('ID_Compte',$client->id)->get();
		$taches=Tache::where('mycl_id',$client->cl_ident)->get();



		//$taches=Tache::where('mycl_id',$client->cl_ident)->get();

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
		return view('clients.fiche',compact('client','contacts','retours','Proch_rendezvous','Anc_rendezvous','taches','stats','commandes','agence_name','commercial','support'));
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

		$type = $request->get('type');
		$print = $request->get('print');
		if ($type == 2) {
			$query->where('etat_id',2);
		} elseif ($type == 1) {
			$query->where('etat_id',  1);
		}

		if ($request->has('Nom') && $request->Nom) {
			$query->where('Nom', 'like', '%'. $request->Nom . '%');
		}

		if ($request->has('adresse1') && $request->adresse1) {
			$query->where('adresse1', 'like', '%' . $request->adresse1 . '%');
		}

		if ($request->has('ville') && $request->ville) {
			$query->where('ville', 'like', '%' . $request->ville . '%');
		}

		if ($request->has('zip') && $request->zip) {
			$query->where('zip', 'like', $request->zip. '%' );
		}

		$tri = $request->get('tri');
		if ($tri == 1) {
			$query->orderBy('Nom');
		} elseif ($tri == 2) {
			$query->orderBy('pays_code')->orderBy('ville');
		}

		$clients = $query->get()->take(1000);

		$agences = Agence::pluck('agence_lib', 'agence_ident')->toArray();
		if($print)
			return view('clients.print', compact('clients','request','agences'));
		else
			return view('clients.search', compact('clients','request','agences'));

	}

	public function ouverture(Request $request)
	{
		$type= $request->get('type');
		$cl_ident= $request->get('cl_ident');
		$id= $request->get('id');

		$result=GEDService::Account($cl_ident,$type,$id);
		return $result;
	}





	/** GED  **/


	public function folder($id)
	{
		$client=CompteClient::find($id);

		try{
			$clientId=$client->cl_ident;

			$files=false;
			$parent=null;
			if (isset($clientId)) {
				$folders=GEDService::getFolders($clientId);
				//dd($folders);

			}
			return view('clients.folder',compact('client','folders','files'));


		} catch (\Exception $e) {
			\Log::info(' erreur GED '.$e->getMessage());
			return "Erreur : " . $e->getMessage();
		}



	}

	public function folderContent($folderId,$folderName,$parent=null,$client_id)
	{
		try{
			//$clientId=auth()->user()->client_id;

			//if (isset($clientId)) {
				$folders=GEDService::getFolderList($folderId);
				$folderContent=GEDService::getFolderContent($folderId);
				$files=false;
				if(!$folders){
					$folders=GEDService::getFolderList($parent);
					$files=true;
					//dd($parent);
				}
			//}

		} catch (\Exception $e) {
			\Log::info(' erreur GED '.$e->getMessage());
			return "Erreur : " . $e->getMessage();
		}
		finally {
			\Log::info('GED folder show ' );
		}
		return view('clients.folders',compact('folders','folderName','folderContent','parent','files','folderId','client_id'));
	}

	public function download($id)
	{
		try{
			//$clientId=auth()->user()->client_id;

			//if (isset($clientId)) {
				GEDService::downloadItem($id);
			//}

		} catch (\Exception $e) {
			\Log::info(' erreur GED '.$e->getMessage());
			return "Erreur : " . $e->getMessage();
		}
	}

	public function view($id)
	{
		try{
			//$clientId=auth()->user()->client_id;

			//if (isset($clientId)) {
				$result = GEDService::getItem($id);

				if ($result) {
					return response($result, 200)
						->header('Content-Type', 'application/pdf');
				}
			//}

		} catch (\Exception $e) {
			\Log::info(' erreur GED '.$e->getMessage());
			return "Erreur : " . $e->getMessage();
		}

		return "Document not found or access denied.";

	}

	/** VIEW **/
	public function edit_file($item,$id,$name)
	{
		$client= CompteClient::find($id);
		return view('clients.edit_file',compact('client','item','id','name'));

	}


	public function editFile(Request $request)
	{
		$itemId= $request->get('item_id');
		$attachment=$request->file('file');
		$id=$request->get('id');

		try{
			$result = GEDService::editItem($itemId, $attachment, $id,'client');
			return $result ;
		} catch (\Exception $e) {
			\Log::info(' erreur GED replacement '.$e->getMessage());
			return "Erreur modification de fichier : " . $e->getMessage();
		}
	}




} // end class
