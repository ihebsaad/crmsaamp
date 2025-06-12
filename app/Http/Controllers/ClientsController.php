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
use App\Services\CreditSafeService;
use App\Services\GEDService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\CompteClientsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Consultation;

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
		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Création Client"]);

		return view('clients.create', compact('agences'));
	}

	public function show($id)
	{
		$client = CompteClient::find($id);
		$agences = DB::table('agence')->get();
		$representants = DB::table('representant')->get();
		$etats = DB::table('etat_client')->get();

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Affichage Client $client->Nom - $client->cl_ident"]);

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
			'origine' => $request->input('origine'),
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
		$commentaires = DB::table('commentaire_client')->where('client', $id)->get();
		$login = '';
		$complet=0;
		$averageRessenti = intval(
			RendezVous::where('ressenti_client', '>', 0)
				->where('mycl_id', $client->id)
				->orderBy('id', 'desc') 
				->limit(3)
				->pluck('ressenti_client') 
				->avg() // Calcule la moyenne
		);

		$ressenti='';
		
		switch ($averageRessenti) {
			case 1:
				$ressenti='😠 Très mauvais';
				break;
			case 2:
				$ressenti='🙁 Mauvais';
				break;
			case 3:
				$ressenti='😐 Neutre';
				break;
			case 4:
				$ressenti='🙂 Bon';
				break;
			case 5:
				$ressenti='😄 Très bon';
				break;				
		}

		if ($client->cl_ident > 0) {
			$users_id = DB::table('users')->where('client_id', $client->cl_ident)->get();

			if($client->dossier_complet==0)
				$complet=GEDService::checkFolder( $client->cl_ident);
			 else 
				$complet=1;
			
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
			//$taches = Tache::where('mycl_id', $client->cl_ident)->orderBy('id','desc')->get();
			$taches = self::getClientTasks($client->cl_ident,1);
		} else {
			$contacts = Contact::where('mycl_ident', $client->id)->get();
			$taches = Tache::where('ID_Compte', $client->id)->get();
		}

		$retours = RetourClient::where('cl_id', $client->cl_ident)->orWhere('idclient', $client->id)->get();
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

			$companyInfo=null;
			if($client->siret!=''){
				$credit = new creditSafeService();
				$companyInfo = $credit->getCompanyInfoBySiret($client->siret);
			}
			
			Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Fiche Client $client->Nom -  $client->cl_ident"]);

		return view('clients.fiche', compact('client', 'contacts', 'retours', 'Proch_rendezvous', 'Anc_rendezvous', 'taches', 'stats', 'commandes', 'agence_name', 'commercial', 'support', 'login', 'commentaires','ressenti','complet','companyInfo'));
	}


	public function finances($id)
	{
		$client = CompteClient::find($id);
		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Finances du Client $client->Nom"]);

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
		$excel = $request->get('excel');

		$representants = DB::table('representant')->orderBy('nom', 'asc')->get();

		$clients_ids = DB::table('client')->where('cl_ident','<>','')->pluck('cl_ident');
		$clients_ids=$clients_ids->unique();


		if ($request->has('contact_name') && $request->contact_name) {
            $query->whereHas('contacts', function ($q)  {
                $q->where('contact.Nom', 'like', '%' . request('contact_name') . '%')
				  ->orWhere('contact.Prenom', 'like', '%' . request('contact_name') . '%');
            });
		}


		if ($request->has('client_id') && $request->client_id) {
			$query->where('cl_ident', 'like', '%' . $request->client_id . '%');
		}

		if ($type == 2) {
			$query->where('etat_id', 2);
		} elseif ($type == 1) {
			$query->where('etat_id',  1);
		}

		$query=self::filter_search($query,$request);

		//RespAG
		if( auth()->user()->user_role==4)
			$query->where('agence_ident', auth()->user()->agence_ident);

		//ADV
		//clients de son agence, ainsi qu’aux clients pour lesquels l’utilisateur est désigné comme ADV
		if( auth()->user()->user_role==6){
			$query->where(function ($q)  {
				$q->orWhere('agence_ident', auth()->user()->agence_ident)
				->orWhere('ADV', auth()->user()->name.' '.auth()->user()->lastname);
			});
		}

		//commercial
		//  Peut voir ses propres clients (Où il est indiqué comme commercial ou commercial support)
		// + Peut voir également les clients rattachés à ses agences (via champ agence dans la table representant).
		//if( auth()->user()->agence_ident==7)
		if( auth()->user()->user_role==7){

			$Rep = DB::table('representant')->where('users_id',auth()->id())->first();
/*
			$query->where(function ($q) use ($Rep) {
				$q->orWhere('commercial', $Rep->id)
				->orWhere('commercial_support', $Rep->id)
				->orWhere("agence_ident", $Rep->agence);
			});
*/

			$query->where(function ($q) use ($Rep) {
				$q->orWhere('commercial', $Rep->id)
				  ->orWhere('commercial_support', $Rep->id);

				// Gestion des agences multiples
				$agences = explode(',', $Rep->agence);
				$q->orWhere(function ($subQuery) use ($agences) {
					foreach ($agences as $agence) {
						$subQuery->orWhere('agence_ident', trim($agence));
					}
				});
			});
		}
/*
		if ($request->has('representant')  && $request->representant ) {
			$rep = $request->representant;

			$query->where('etat_id', 1)->where(function ($q) use ($rep) {
				$q->where('commercial', $rep)
				  ->orWhere('commercial_support', $rep);
			});
		}else{

			$Rep = DB::table('representant')->where('users_id',auth()->id())->first();


		}
*/
/*
		if(auth()->id() == 141) {  //patricia delmas
			$Rep = DB::table('representant')->where('users_id',auth()->id())->first();

			$query->where(function ($q) use ($Rep) {
				$q->where(function ($q1) use ($Rep) {
					$q1->where('etat_id', 2)
						->where(function ($q2) use ($Rep) {
							$q2->where('commercial', $Rep->id)
								->orWhere('commercial_support', $Rep->id);
						});
				})
				->orWhere('etat_id', 1)
				->orWhere(function ($q3) {
					$q3->where('etat_id', 2)
						->where(function ($q4) {
							$q4->where('agence_ident', 200)
								->orWhere('agence_ident', 203);
						});
				});
			});

			$query=self::filter_search($query,$request);


		}

		if(auth()->id()==9872){  // Jacek

			$query = self::filter_search($query, $request);
			$query->where('etat_id', 1)->orWhere(function ($q) use ($Rep) {
				$q->where('agence_ident', 200)
					->orWhere('commercial', $Rep->id)
					->orWhere('commercial_support', $Rep->id);
			});

			$query=self::filter_search($query,$request);

		}

		if(auth()->id()==142){  // Isabelle Jallabert

			$query->where('etat_id', 1)->orWhere(function ($q) use ($Rep) {
				$q->where('agence_ident', 43)
					->orWhere('agence_ident', 201)
					->orWhere('commercial', $Rep->id)
					->orWhere('commercial_support', $Rep->id);
			});

			$query=self::filter_search($query,$request);

		}

		if(auth()->id()==143){  // Reynald

			$query->where('etat_id', 1)->orWhere(function ($q) use ($Rep) {
				$q->where('agence_ident', 40)
					->orWhere('commercial', $Rep->id)
					->orWhere('commercial_support', $Rep->id);
			});
		}

		if(auth()->id()==35){  // ADAMA

			$rep = DB::table('representant')->where('users_id',auth()->id())->first();
			$query->where('etat_id', 2);
			$query->where('commercial', $rep->id);

			$query=self::filter_search($query,$request);

		}

*/
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

		$agences = Agence::orderBy('agence_lib', 'asc')->pluck('agence_lib', 'agence_ident')->toArray();
		if ($print){
			Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Impression liste des clients"]);

			return view('clients.print', compact('clients', 'request', 'agences', 'representants','clients_ids'));
		}
		if($excel){
			Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Export excel liste des clients"]);
			return Excel::download(new CompteClientsExport($query), 'clients.xlsx');
		}
			Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Recherche des clients"]);

			return view('clients.search', compact('clients', 'request', 'agences', 'representants','clients_ids'));
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
				$expDates=GEDService::expireDates($clientId);

			}

			Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "GED Client $client->Nom -  $client->cl_ident"]);

			return view('clients.folder', compact('client', 'folders', 'files','expDates'));
		} catch (\Exception $e) {
			\Log::info(' erreur GED ' . $e->getMessage());
			return "Erreur : " . $e->getMessage();
		}
	}

	public function folderContent($folderId, $folderName, $parent = null, $client_id)
	{
		try {
			//$clientId=auth()->user()->client_id;
			$page       = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$search = isset($_GET['search']) ? trim($_GET['search']) : '';
			$month     = isset($_GET['month']) ? $_GET['month'] : '';
			//if (isset($clientId)) {
			$folders = GEDService::getFolderList($folderId);
			//$folderContent = GEDService::getFolderContent($folderId);
			$result=GEDService::getFolderContent($folderId,20,$page,$search,$month);
			$folderContent=$result ?? [] ;
			$files = false;
			$expDates=GEDService::expireDates($client_id);

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
		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "GED dossier $folderId"]);

		return view('clients.folders', compact('folders', 'folderName', 'folderContent', 'parent', 'files', 'folderId', 'client_id','expDates','page','month','search'));
	}


	public function getClientTasks($client_id,$last_24 = null)
	{
		// Get the current date
		$currentDate = now();
		
		// Get tasks from Tache within the last 15 days
		$tasks = Tache::where('mycl_id', $client_id)
			->where('DateTache', '>=', $currentDate->subDays(14)) // Filter for the last 15 days
			->get();

			// Déterminer la date limite selon $last_24
			if ($last_24) {
				$dateLimit = $currentDate->copy()->subDay()->format('Y-m-d'); // Hier
				$today = $currentDate->copy()->format('Y-m-d'); // Aujourd'hui
			} else {
				$dateLimit = $currentDate->copy()->subDays(14)->format('Y-m-d');
			}
		
			$prisesQuery = DB::table('prise_contact_as400')
				->where('prise_contact_as400.cl_ident', $client_id);
		
			if ($last_24) {
				// On récupère uniquement les enregistrements de la date d’aujourd’hui
				$prisesQuery->where('prise_contact_as400.date_pr', '=', $today);
			} else {
				$prisesQuery->where('prise_contact_as400.date_pr', '>=', $dateLimit);
			}
		
			$prises = $prisesQuery
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
				return response($result['body'], 200)
					->header('Content-Type', $result['type']);
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
			'comment' => $request->get('comment'),
			'client' => $request->get('client'),
			'user' => auth()->id(),
		]);

		$data = array();
		$data['date'] = date('d/m/Y');
		$data['user'] = auth()->user()->name . ' ' . auth()->user()->lastname;
		return $data;
	}

	public function delete_comment(Request $request)
	{
		DB::table('commentaire_client')->where(
			'id',
			$request->get('comment'),
		)->delete();

		return 1;
	}

	public function activites_client(Request $request)
	{
		if ($request->cl_ident > 0) {
			$taches = self::getClientTasks($request->cl_ident);
		} else {
			$taches = Tache::where('ID_Compte', $request->id)->get();
		}
		$result = '';
		$result .= '<div class="table-container">';
		$result .= '<table class="table table-bordered table-striped mb-40">';
		$result .= '<thead>
				<tr id="headtable">
					<th>Type</th>
					<th>Date</th>
					<th>' . __('msg.Subject') . '</th>
					<th>' . __('msg.Amount') . '</th>
					<th>' . __('msg.Weight') . '</th>
				</tr>
			</thead>
			<tbody>';
		foreach ($taches as $tache) {
			/*
						$color='';
						switch ( $tache->Status ) {
						case 'Not Started':
						$color = '#82e2e8';$statut='Pas commencée';
						break;
						case 'Waiting on someone e':
						$color = '#ea922b';$statut='En attente  de quelqu\'un';
						break;
						case 'In Progress':
						$color = '#5f9fff';$statut='En cours';
						break;
						case 'Deferred':
						$color = '#a778c9';$statut='Reportée';
						break;
						case 'Completed':
						$color = '#40c157';$statut='Terminée';
						break;
						default:
						$color = '';
						}

						$class='';
						switch ( $tache->Priority ) {
						case 'Normal':
						$class = 'primary';$priority='Normale';
						break;
						case 'High':
						$class = 'danger';$priority='Haute';
						break;
						case 'Low':
						$class = 'info';$priority='Basse';
						break;

						default:
						$class = 'primary';$priority='Normale';
						}

						$icon='';
						switch ( $tache->Type ) {
						case 'Acompte / Demande de paiement':
						$icon = 'img/invoice.png';
						break;
						case 'Appel téléphonique':
						$icon = 'img/call.png';
						break;
						case 'Envoyer email':
						$icon = 'img/email.png';
						break;

						case 'Envoyer courrier':
						$icon = 'img/mail.png';
						break;


						default:
						$class = '';
						}
*/
			$result .= '
					<tr>
						<td>' . $tache->Type . '</td>
						<td>' . date('d/m/Y', strtotime($tache->DateTache)) . ' ' . $tache->heure_debut . '</td>';
			$result .= '<td>';
			if ($tache->as400 == 0) {
				$result .= '<a href="' . route('taches.show', ['id' => $tache->id]) . '">' . $tache->Subject . '</a>';
			} else {
				$result .= $tache->Subject;
			}

			$result .= '</td>
						<td style="padding-left:2px!important" class="text-center">
						' . $tache->montant .' €';
			$result .= '</td>
						<td class="text-center">
							' . $tache->poids . ' g';

			$result .= '</td>
					</tr>';
		}
		$result .= '
			</tbody>
		</table>
	</div>';


		return $result;
	}



	function filter_search($query,$request){

		if ($request->has('etat_id') && $request->etat_id != 0) {
			$query->where('etat_id', $request->etat_id);
		}

		if ($request->has('type_client') && $request->type_client != 0) {
			if($request->type_client=='clients'){
				$query->where('etat_id', 2);
			}
			elseif($request->type_client=='prospects'){
				$query->where('etat_id', 1);
			}
			elseif($request->type_client=='fournisseurs'){
				$query->where('etat_id', 6);
			}			
			else{
				$query->where('couleur_html', $request->type_client);
			}
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

		if ($request->has('pays_code') && $request->pays_code) {
			$query->where('pays_code',  $request->pays_code);
		}

		if ($request->has('representant') && $request->representant) {
			$query->where('commercial',  $request->representant);
		}

		return $query;
	}

} // end class
