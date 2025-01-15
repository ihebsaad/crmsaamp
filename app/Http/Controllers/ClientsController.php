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
		return view('clients.create', compact('agences'));
	}

	public function show($id)
	{
		$client = CompteClient::find($id);
		$agences = DB::table('agence')->get();
		$representants = DB::table('representant')->get();
		$etats = DB::table('etat_client')->get();

		return view('clients.show', compact('client', 'agences', 'representants', 'etats'));
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

		return redirect()->route('fiche', ['id' =>  $id])
			->with('success', 'Client modifié');
	}


	public function update_finances(Request $request, $id)
	{
		$client = CompteClient::find($id);

		$client->update($request->all());

		return redirect()->route('fiche', ['id' =>  $id])
			->with('success', 'Client modifié');
	}


	public function store(Request $request)
	{
		$request->validate([
			'Nom' => 'required',
			'adresse1' => 'required',
			'ville' => 'required',
			'zip' => 'required',
			'Code_siret' => 'required|string', //|max:14
		]);
		/*
		if(CompteClient::where('siret',$request->input('siret'))->exists()  ){
			return back()->withErrors(['msg' => "stocker siret dans la colonne Code siret"]);
		}*/

		$siret = trim($request->input('Code_siret'));
		$client = CompteClient::create([
			'Nom' => $request->input('Nom'),
			'adresse1' => $request->input('adresse1'),
			'ville' => $request->input('ville'),
			'zip' => $request->input('zip'),
			'siret' => $siret,
			'Pays' => $request->input('Pays'),
			'pays_code' => $request->input('pays_code'),
			'latitude' => $request->input('latitude'),
			'longitude' => $request->input('longitude'),
			'Commentaire' => $request->input('Commentaire'),
			'Code_siret' => $siret,
			'Tel' => $request->input('Tel'),
			'email' => $request->input('email'),
			'url' => $request->input('url'),
			'etat_id' => 1,
			'Code_siren' => substr($siret, 0, 9),
		]);

		$client->save();

		$contact = Contact::create([
			'Nom' => $request->input('nom_contact'),
			'Prenom' => $request->input('prenom_contact'),
			'email' => $request->input('email_contact'),
			'mycl_ident' => $client->id,
		]);

		$contact->save();

		return redirect()->route('fiche', $client->id)
			->with('success', 'Client ajouté');
	}


	public function fiche($id)
	{
		$client = CompteClient::find($id);
		$commentaires = DB::table('commentaire_client')->where('client',$id)->get();
		$login = '';

		if ($client->cl_ident > 0) {
			$users_id = DB::table('users')->where('client_id', $client->cl_ident)->get();

			$users_ids = DB::table('users')
				->where('client_id', $client->cl_ident)
				->pluck('id'); // Get only user IDs

			// Get the latest login time from the user_logins table for these users
			$lastLogin = DB::table('user_logins')
				->whereIn('user_id', $users_ids)
				->orderBy('login_at', 'desc') // Order by login_at descending
				->first(); // Get the most recent record

			if ($lastLogin) {
				$login = __('msg.Last login to MySaamp') . ": " .  date('d/m/Y H:i', strtotime($lastLogin->login_at));
			} else {
				$login = __('msg.No connection to MySaamp');
			}
		} else {

		}


		$contacts = $retours = $taches = array();
		$representants = DB::table('representant')->get();

		$commercial = $support = '';
		$rep_comm = DB::table('representant')->find($client->commercial);
		if (isset($rep_comm))
			$commercial = $rep_comm->prenom . ' ' . $rep_comm->nom;

		$rep_supp = DB::table('representant')->find($client->commercial_support);
		if (isset($rep_supp))
			$support = $rep_supp->prenom . ' ' . $rep_supp->nom;

		//if($client->Client_Prospect!='COMPTE PROSPECT'){
		if ($client->cl_ident > 0) {
			$contacts = Contact::where('cl_ident', $client->cl_ident)->get();
			$taches = self::getClientTasks($client->cl_ident);
		} else {
			$contacts = Contact::where('mycl_ident', $client->id)->get();
			$taches = Tache::where('ID_Compte', $client->id)->get();
		}

		$retours = RetourClient::where('cl_id', $client->cl_ident)->get();
		//}
		$agence_name = '';
		$agence = DB::table('agence')->where('agence_ident', $client->agence_ident)->first();

		if (isset($agence))
			$agence_name = $agence->agence_lib;

		$stats = $commandes = null;

		if ($client->cl_ident != '') {
			//$taches=Tache::where('mycl_id',$client->cl_ident)->get();
			try {

				DB::select("SET @p0=$client->cl_ident ;");
				DB::select("SET @p1=1  ;");
				$stats =  DB::select('call `sp_stats_client`(@p0,@p1); ');


				DB::select("SET @p0='$client->cl_ident'  ;");
				$commandes =  DB::select(" CALL `sp_accueil_liste_commandes`(@p0); ");
			} catch (\Exception $e) {
				\Log::error($e->getMessage());
			}
		}
		/*
		if($client->Id_Salesforce!='')
			$taches=Tache::where('ID_Compte',$client->Id_Salesforce)->get();
		else
			$taches=Tache::where('ID_Compte',$client->id)->get();
		*/

		//$taches=Tache::where('ID_Compte',$client->id)->get();



		//$taches=Tache::where('mycl_id',$client->cl_ident)->get();

		//$appels=array();
		//$callData=PhoneService::data($client->token_phone);


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
		$rendezvous = RendezVous::where('Account_Name', $client->Nom)
			->orWhere('mycl_id', $client->id)
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
		/*
		$Proch_rendezvous = RendezVous::where('Account_Name', $client->Nom)
		->where('Started_at', '>=', $now)
		->orderBy('Started_at', 'desc')
		->get();
*/
		$Proch_rendezvous = RendezVous::where(function ($query) use ($client) {
			$query->where('Account_Name', $client->Nom)
				->orWhere('mycl_id', $client->id);
		})
			->where('Started_at', '>=', $now)
			->orderBy('Started_at', 'desc')
			->get();
		/*
		$Anc_rendezvous = RendezVous::where('Account_Name', $client->Nom)
		->where('Started_at', '<', $now)
		->orderBy('Started_at', 'desc')
		->get();*/

		$Anc_rendezvous = RendezVous::where(function ($query) use ($client) {
			$query->where('Account_Name', $client->Nom)
				->orWhere('mycl_id', $client->id);
		})
			->where('Started_at', '<', $now)
			->orderBy('Started_at', 'desc')
			->get();
		return view('clients.fiche', compact('client', 'contacts', 'retours', 'Proch_rendezvous', 'Anc_rendezvous', 'taches', 'stats', 'commandes', 'agence_name', 'commercial', 'support', 'login','commentaires'));
	}


	public function finances($id)
	{
		$client = CompteClient::find($id);
		return view('clients.finances', compact('client'));
	}


	public function phone($id)
	{
		$client = CompteClient::find($id);
		//$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiI1NTk4ODM2IiwiYXVkIjoiKiIsImlzcyI6InR2eCIsImlhdCI6MTcxNjU0NjU5MCwianRpIjoiMTg5OTQ0NjYifQ.4_0fCiH0KqsKHbtI3xnp1VkrRamENo_qf7Uecs_0b4WhczutEMUJZlHzhm4HZqHgKBbCxxyv3E8mX5nl-JQm4Q';
		$token = $client->token_phone;
		$callData = PhoneService::data($token);
		return view('clients.phone', compact('client', 'callData', 'token'));
	}


	public function search(Request $request)
	{
		$query = CompteClient::query();

		$type = $request->get('type');
		$client_id = $request->get('client_id');
		$print = $request->get('print');

		if ($request->has('client_id') && $request->client_id) {
			$query->where('cl_ident', 'like', '%' . $request->client_id . '%');
		}
		if ($type == 2) {
			$query->where('etat_id', 2);
		} elseif ($type == 1) {
			$query->where('etat_id',  1);
		}

		if ($request->has('Nom') && $request->Nom) {
			$query->where('Nom', 'like', '%' . $request->Nom . '%');
		}

		if ($request->has('adresse1') && $request->adresse1) {
			$query->where('adresse1', 'like', '%' . $request->adresse1 . '%');
		}

		if ($request->has('ville') && $request->ville) {
			$query->where('ville', 'like', '%' . $request->ville . '%');
		}

		if ($request->has('zip') && $request->zip) {
			$query->whereRaw("TRIM(zip) LIKE ?", [trim($request->zip) . '%']);
		}

		if ($request->has('agence') && $request->agence) {
            $query->where('agence_ident',  $request->agence);
        }

		/*
		$tri = $request->get('tri');
		if ($tri == 1) {
			$query->orderBy('Nom');
		} elseif ($tri == 2) {
			$query->orderBy('pays_code')->orderBy('ville');
		}
*/
		$sort = $request->get('sort', 'Nom'); // Default sort by 'Nom'
		$direction = $request->get('direction', 'asc'); // Default direction 'asc'

		// Map request sort values to database column names
		switch ($sort) {
			case 'ville':
				$query->orderBy('ville', $direction);
				break;
			case 'agence':
				$query->orderBy('agence_ident', $direction);
				break;
			case 'etat_id':
				$query->orderBy('etat_id', $direction);
				break;
			default:
				$query->orderBy('Nom', $direction);
				break;
		}

		$clients = $query->get()->take(1000);

		$agences = Agence::pluck('agence_lib', 'agence_ident')->toArray();
		if ($print)
			return view('clients.print', compact('clients', 'request', 'agences'));
		else
			return view('clients.search', compact('clients', 'request', 'agences'));
	}

	public function prospects(Request $request)
	{
		$clients = CompteClient::where('agence_ident', auth()->user()->agence_ident)->where('etat_id', 1)->get();
		$agences = Agence::pluck('agence_lib', 'agence_ident')->toArray();
		return view('clients.print', compact('clients', 'request', 'agences'));
	}

	public function ouverture(Request $request)
	{
		$type = $request->get('type');
		$cl_ident = $request->get('cl_ident');
		$id = $request->get('id');
		$files = $request->file('files');

		$result = GEDService::Account($cl_ident, $type, $id, $files);
		return $result;
	}





	/** GED  **/


	public function folder($id)
	{
		$client = CompteClient::find($id);

		try {
			$clientId = $client->cl_ident;

			$files = false;
			$parent = null;
			if (isset($clientId)) {
				$folders = GEDService::getFolders($clientId);
				//dd($folders);

			}
			return view('clients.folder', compact('client', 'folders', 'files'));
		} catch (\Exception $e) {
			\Log::info(' erreur GED ' . $e->getMessage());
			return "Erreur : " . $e->getMessage();
		}
	}

	public function folderContent($folderId, $folderName, $parent = null, $client_id)
	{
		try {
			//$clientId=auth()->user()->client_id;

			//if (isset($clientId)) {
			$folders = GEDService::getFolderList($folderId);
			$folderContent = GEDService::getFolderContent($folderId);
			$files = false;
			if (!$folders) {
				$folders = GEDService::getFolderList($parent);
				$files = true;
				//dd($parent);
			}
			//}

		} catch (\Exception $e) {
			\Log::info(' erreur GED ' . $e->getMessage());
			return "Erreur : " . $e->getMessage();
		} finally {
			\Log::info('GED folder show ');
		}
		return view('clients.folders', compact('folders', 'folderName', 'folderContent', 'parent', 'files', 'folderId', 'client_id'));
	}


	public function getClientTasks($client_id)
	{
		// Get the current date
		$currentDate = now();

		// Get tasks from Tache within the last 15 days
		$tasks = Tache::where('mycl_id', $client_id)
			->where('DateTache', '>=', $currentDate->subDays(7)) // Filter for the last 15 days
			->get();

		/*
			$tasks = Tache::where(function ($query) use ($client_id) {
				$query->where('ID_Compte', $client_id)
					->orWhere('mycl_id', $client_id);
			})
			->where('DateTache', '>=', $currentDate->subDays(7)) // Filter for the last 15 days
			->get();
*/
		// Reset the date calculation
		$currentDate = now();

		// Get records from prise_contact_as400 within the last 7 days
		$prises = DB::table('prise_contact_as400')
			->where('prise_contact_as400.cl_ident', $client_id)
			->where('prise_contact_as400.date_pr', '>=', $currentDate->subDays(7)) // Filter for the last 7 days
			->join('client', 'prise_contact_as400.cl_ident', '=', 'client.cl_ident')
			->join('agence', 'prise_contact_as400.agence_id', '=', 'agence.agence_ident')
			->join('sujet', 'prise_contact_as400.id_sujet', '=', 'sujet.sujet_ident')
			->join('type_contact', 'prise_contact_as400.id_type_contact', '=', 'type_contact.type_contact_ident')
			->select(
				'client.id as ID_Compte',
				'prise_contact_as400.montant',
				'prise_contact_as400.poids',
				'prise_contact_as400.date_pr as DateTache',
				DB::raw('NULL as heure_debut'), // Pas de champ dans prise_contact_as400
				DB::raw('NULL as Status'), // Status vide pour prise_contact_as400
				DB::raw('NULL as Priority'), // Priority vide pour prise_contact_as400
				'type_contact.titre_type_contact as Type',
				'client.Nom as Nom_de_compte',
				'client.cl_ident as mycl_id',
				DB::raw('CONCAT(
					"Prise de contact AS400",
					CASE
						WHEN prise_contact_as400.nature_lot IS NOT NULL AND prise_contact_as400.nature_lot != 0
						THEN CONCAT(", Nature du lot : ", prise_contact_as400.nature_lot)
						ELSE ""
					END,
					CASE
						WHEN prise_contact_as400.poids IS NOT NULL AND prise_contact_as400.poids != 0
						THEN CONCAT(", Poids : ", prise_contact_as400.poids)
						ELSE ""
					END,
					CASE
						WHEN prise_contact_as400.essai IS NOT NULL AND prise_contact_as400.essai != 0
						THEN CONCAT(", Essai : ", prise_contact_as400.essai)
						ELSE ""
					END,
					CASE
						WHEN prise_contact_as400.montant IS NOT NULL AND prise_contact_as400.montant != 0
						THEN CONCAT(", Montant : ", prise_contact_as400.montant)
						ELSE ""
					END
				) as Description'),
				'agence.agence_lib as Agence',
				'sujet.titre_sujet as Subject',
				DB::raw('1 as as400') // Indicateur que les données viennent de prise_contact_as400
			)
			->orderBy('prise_contact_as400.id', 'desc')->limit(1000)
			->get()
			->toArray(); // Convert to array

		// Convert tasks to collection and map 'as400' flag
		$tasks = collect($tasks)->map(function ($task) {
			$task = (object) $task; // Convert to stdClass
			$task->as400 = 0; // Add 'as400' attribute for tasks
			return $task;
		});

		// Convert prises to collection
		$prises = collect($prises);

		// Merge tasks and prises
		$taches = $tasks->merge($prises);

		return $taches;
	}


	public function download($id)
	{
		try {
			//$clientId=auth()->user()->client_id;

			//if (isset($clientId)) {
			GEDService::downloadItem($id);
			//}

		} catch (\Exception $e) {
			\Log::info(' erreur GED ' . $e->getMessage());
			return "Erreur : " . $e->getMessage();
		}
	}

	public function view($id)
	{
		try {
			//$clientId=auth()->user()->client_id;

			//if (isset($clientId)) {
			$result = GEDService::getItem($id);

			if ($result) {
				return response($result, 200)
					->header('Content-Type', 'application/pdf');
			}
			//}

		} catch (\Exception $e) {
			\Log::info(' erreur GED ' . $e->getMessage());
			return "Erreur : " . $e->getMessage();
		}

		return "Document not found or access denied.";
	}

	public function delete_file($itemid)
	{
		$res = GEDService::deleteFile($itemid);
		return $res;
	}

	/** VIEW **/
	public function edit_file($item, $id, $name)
	{
		$client = CompteClient::find($id);
		return view('clients.edit_file', compact('client', 'item', 'id', 'name'));
	}


	public function editFile(Request $request)
	{
		$itemId = $request->get('item_id');
		$attachment = $request->file('file');
		$id = $request->get('id');

		try {
			$result = GEDService::editItem($itemId, $attachment, $id, 'client');
			return $result;
		} catch (\Exception $e) {
			\Log::info(' erreur GED replacement ' . $e->getMessage());
			return "Erreur modification de fichier : " . $e->getMessage();
		}
	}


	public function destroy($id)
	{
		$client = CompteClient::find($id);
		$client->delete();

		return redirect()->route('search')
			->with('success', 'Supprimé avec succès');
	}



	public function add_comment(Request $request)
    {

		DB::table('commentaire_client')->insert([
			'comment'=>$request->get('comment'),
			'client'=>$request->get('client'),
			'user'=>auth()->id(),
		]);

		$data=array();
		$data['date']=date('d/m/Y');
		$data['user']=auth()->user()->name.' '.auth()->user()->lastname;
		return $data;
	}

	public function delete_comment(Request $request)
    {
		DB::table('commentaire_client')->where(
			'id',$request->get('comment'),
		)->delete();

		return 1;
	}


} // end class
