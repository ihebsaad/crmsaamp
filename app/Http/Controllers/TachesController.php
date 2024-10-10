<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RetourClient;
use App\Models\Tache;
use App\Models\Agence;
use App\Services\PhoneService;
use Illuminate\Support\Facades\DB;


class TachesController extends Controller
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

	/*
	public function index()
	{
		//$taches=Tache::where('id','<>',null)->limit(1000)->orderBy('id','desc')->get();
		$taches=Tache::limit(1000)->orderBy('id','desc')->get();

		$agences = DB::table('agence')->get();

		return view('taches.list',compact('taches','agences'));
	}
	*/
/*
	public function index()
	{
		// Récupérer les tâches
		$tasks = Tache::orderBy('id', 'desc')->limit(1000)->get();

		// Récupérer les données de prise_contact_as400 avec jointures pour récupérer les données nécessaires
		$prises = DB::table('prise_contact_as400')
			->join('client', 'prise_contact_as400.cl_ident', '=', 'client.cl_ident')
			->join('agence', 'prise_contact_as400.agence_id', '=', 'agence.agence_ident')
			->join('sujet', 'prise_contact_as400.id_sujet', '=', 'sujet.sujet_ident')
			->join('type_contact', 'prise_contact_as400.id_type_contact', '=', 'type_contact.type_contact_ident')
			->select(
				'client.id as ID_Compte',
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
						WHEN sujet.titre_sujet IS NOT NULL
						THEN CONCAT(", Sujet : ", sujet.titre_sujet)
						ELSE ""
					END,
					CASE
						WHEN type_contact.titre_type_contact IS NOT NULL
						THEN CONCAT(", Type de contact : ", type_contact.titre_type_contact)
						ELSE ""
					END,
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
			->toArray(); // Convertir en tableau

		// Fusionner les tâches avec les prises de contact
		$tasks = collect($tasks)->map(function ($task) {
			$task = (object) $task; // Convertir en stdClass
			$task->as400 = 0; // Ajouter un attribut pour indiquer que cela vient de Tache
			return $task;
		});

		$prises = collect($prises);

		$taches = $tasks->merge($prises);

		$agences = DB::table('agence')->get();
		$title="Suivi d'activité";

		return view('taches.list', compact('taches', 'agences','title'));
	}
*/
	public function index()
	{
		// Récupérer les filtres
		$nom = request()->input('nom');
		$cl_ident = request()->input('cl_ident');
		// Récupérer les tâches
		$tasks = Tache::orderBy('id', 'desc')->limit(1000)
			->when($nom, function ($query, $nom) {
				return $query->where('Nom_de_compte', 'LIKE', "%{$nom}%");
			})
			->when($cl_ident, function ($query, $cl_ident) {
				$client=Client::where('cl_ident',$cl_ident)->first();
				return $query->where('ID_Compte', '=', $client->id);
			})
			->get();

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
					"Prise de contact AS400",
					CASE
						WHEN sujet.titre_sujet IS NOT NULL
						THEN CONCAT(", Sujet : ", sujet.titre_sujet)
						ELSE ""
					END,
					CASE
						WHEN type_contact.titre_type_contact IS NOT NULL
						THEN CONCAT(", Type de contact : ", type_contact.titre_type_contact)
						ELSE ""
					END,
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
				DB::raw('1 as as400')
			)
			->when($nom, function ($query, $nom) {
				return $query->where('client.Nom', 'LIKE', "%{$nom}%");
			})
			->when($cl_ident, function ($query, $cl_ident) {
				return $query->where('client.cl_ident', '=', $cl_ident);
			})
			->orderBy('prise_contact_as400.id', 'desc')
			->limit(1000)
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

		$agences = DB::table('agence')->get();
		$title = "Suivi d'activité";

		return view('taches.list', compact('taches', 'agences', 'title','nom','cl_ident'));
	}

/*
	public function mestaches()
	{
		// Récupérer l'utilisateur connecté
		$user = auth()->user();

		$title="Suivi d'activité";
		// Vérifier si l'utilisateur est dans la table 'representant'
		$isRepresentant = DB::table('representant')
			->where('users_id', $user->id)
			->exists();

		// Commencer la construction de la requête pour les tâches
		$tasks = Tache::orderBy('CRM_Tache.id', 'desc')
			->join('client', 'CRM_Tache.mycl_id', '=', 'client.cl_ident')
			->join('agence', 'CRM_Tache.Agence', '=', 'agence.agence_lib')
			->select(
				'client.id as ID_Compte',
				'CRM_Tache.id',
				'CRM_Tache.DateTache',
				'CRM_Tache.heure_debut',
				'CRM_Tache.Status',
				'CRM_Tache.Priority',
				'CRM_Tache.Type',
				'client.Nom as Nom_de_compte',
				'CRM_Tache.mycl_id',
				'CRM_Tache.Description',
				'agence.agence_lib as Agence',
				'CRM_Tache.Subject',
				DB::raw('0 as as400') // Indicateur que les données viennent de Tache
			);

		if ($isRepresentant) {
			$id=DB::table('representant')
			->where('users_id', $user->id)->first()->id;
			// Si l'utilisateur est dans la table 'representant'
			$tasks->where(function($q) use ($id) {
				$q->where('client.commercial', '=', $id)
				->orWhere('client.commercial_support', '=', $id);
			});
			$title='Activités de mes clients';
		} else {
			// Si l'utilisateur n'est pas dans 'representant', filtrer par agence
			$tasks->where(function($q) use ($user) {
				$q->where('agence.agence_ident', '=', $user->agence_ident);
			});
		}

		// Exécuter la requête
		$tasks = $tasks->get();

		// Récupérer les prises de contact de la table 'prise_contact_as400' avec la même logique
		$prises = DB::table('prise_contact_as400')
			->join('client', 'prise_contact_as400.cl_ident', '=', 'client.cl_ident')
			->join('agence', 'prise_contact_as400.agence_id', '=', 'agence.agence_ident')
			->join('sujet', 'prise_contact_as400.id_sujet', '=', 'sujet.sujet_ident')
			->join('type_contact', 'prise_contact_as400.id_type_contact', '=', 'type_contact.type_contact_ident')
			->select(
				'client.id as ID_Compte',
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
						WHEN sujet.titre_sujet IS NOT NULL
						THEN CONCAT(", Sujet : ", sujet.titre_sujet)
						ELSE ""
					END,
					CASE
						WHEN type_contact.titre_type_contact IS NOT NULL
						THEN CONCAT(", Type de contact : ", type_contact.titre_type_contact)
						ELSE ""
					END,
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
			);

		if ($isRepresentant ) {
			$id=DB::table('representant')
			->where('users_id', $user->id)->first()->id;
			// Si l'utilisateur est dans 'representant', appliquer les mêmes filtres pour les prises de contact
			$prises->where(function($q) use ($id) {
				$q->where('client.commercial', '=', $id)
				->orWhere('client.commercial_support', '=', $id);
			});
		} else {
			// Sinon, filtrer par agence
			$prises->where(function($q) use ($user) {
				$q->where('prise_contact_as400.agence_id', '=', $user->agence_ident);
			});
		}

		$prises = $prises->get();

		// Fusionner les tâches et les prises de contact
		$taches = collect($tasks)->merge($prises);



		$agences = DB::table('agence')->get();

		return view('taches.list', compact('taches', 'agences','title'));
	}
*/

	public function mestaches()
	{
		$user = auth()->user();
		$nom = request()->input('nom');
		$cl_ident = request()->input('cl_ident');
		$title = "Suivi d'activité";

		$isRepresentant = DB::table('representant')
			->where('users_id', $user->id)
			->exists();

		$tasks = Tache::orderBy('CRM_Tache.id', 'desc')
			->join('client', 'CRM_Tache.mycl_id', '=', 'client.cl_ident')
			->join('agence', 'CRM_Tache.Agence', '=', 'agence.agence_lib')
			->select(
				'client.id as ID_Compte',
				'CRM_Tache.id',
				'CRM_Tache.DateTache',
				'CRM_Tache.heure_debut',
				'CRM_Tache.Status',
				'CRM_Tache.Priority',
				'CRM_Tache.Type',
				'client.Nom as Nom_de_compte',
				'CRM_Tache.mycl_id',
				'CRM_Tache.Description',
				'agence.agence_lib as Agence',
				'CRM_Tache.Subject',
				DB::raw('0 as as400')
			)
			->when($nom, function ($query, $nom) {
				return $query->where('Nom_de_compte', 'LIKE', "%{$nom}%");
			})
			->when($cl_ident, function ($query, $cl_ident) {
				$client=Client::where('cl_ident',$cl_ident)->first();
				return $query->where('ID_Compte', '=', $client->id);
			});

		if ($isRepresentant) {
			$id = DB::table('representant')
				->where('users_id', $user->id)
				->first()->id;

			$tasks->where(function ($q) use ($id) {
				$q->where('client.commercial', '=', $id)
					->orWhere('client.commercial_support', '=', $id);
			});
			$title = 'Activités de mes clients';
		} else {
			$tasks->where(function ($q) use ($user) {
				$q->where('agence.agence_ident', '=', $user->agence_ident);
			});
		}

		$tasks = $tasks->get();

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
					"Prise de contact AS400",
					CASE
						WHEN sujet.titre_sujet IS NOT NULL
						THEN CONCAT(", Sujet : ", sujet.titre_sujet)
						ELSE ""
					END,
					CASE
						WHEN type_contact.titre_type_contact IS NOT NULL
						THEN CONCAT(", Type de contact : ", type_contact.titre_type_contact)
						ELSE ""
					END,
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
				DB::raw('1 as as400')
			)
			->when($nom, function ($query, $nom) {
				return $query->where('client.Nom', 'LIKE', "%{$nom}%");
			})
			->when($cl_ident, function ($query, $cl_ident) {
				return $query->where('client.cl_ident', '=', $cl_ident);
			})
			->orderBy('prise_contact_as400.id', 'desc')
			->get()
			->toArray();

		// Combine the tasks and prises into a single collection
		$tasks = collect($tasks)->map(function ($task) {
			$task = (object) $task;
			$task->as400 = 0;
			return $task;
		});

		$prises = collect($prises);

		$taches = $tasks->merge($prises);

		return view('taches.list', compact('taches', 'title','nom','cl_ident'));
	}

	public function create($id)
	{
		$client = CompteClient::find($id);
		$agences = DB::table('agence')->get();

		return view('taches.create', compact('client', 'agences'));
	}

	public function client_list($id)
	{
		$client = CompteClient::find($id);
		$taches = Tache::where('mycl_id', $client->cl_ident)->get();
		return view('taches.list', compact('taches', 'client'));
	}

	public function show($id)
	{
		$tache = Tache::find($id);
		$client = CompteClient::find($tache->ID_Compte);
		$agences = DB::table('agence')->get();

		return view('taches.show', compact('tache', 'client', 'agences'));
	}


	public function update(Request $request, $id)
	{
		$request->validate([
			'Subject' => 'required',
		]);

		$tache = Tache::find($id);
		$tache->update($request->all());


		$tache->save();

		return redirect()->route('taches.show', $id)
			->with('success', 'Tâche modifiée');
	}

	public function store(Request $request)
	{
		$request->validate([
			'Subject' => 'required',

		]);

		$tache = Tache::create($request->all());

		/*
		$client=CompteClient::find($tache->ID_Compte);
		$agence_id=$client->agence_ident;
		$agence_name=Agence::where('agence_ident',$agence_id)->first()->agence_lib ?? '';
		$tache->Nom_de_compte=$client->Nom;
		$tache->Agence=$agence_name;
*/

		$tache->save();

		if ($tache->ID_Compte > 0)
			return redirect()->route('fiche', ['id' => $tache->ID_Compte])->with(['success' => "Tâche ajoutée "]);

		return redirect()->route('taches.show', $tache->id)
			->with('success', 'Tache ajoutée');
	}


	public function destroy($id)
	{
		$tache = Tache::find($id);

		if ($tache) {
			if (intval($tache->ID_Compte) > 0)
				$client_id = $tache->ID_Compte;
			else
				$client_id = Client::where('cl_ident', $tache->mycl_id)->first();

			$tache->delete();

			$previousUrl = url()->previous();

			if (str_contains($previousUrl, '/show/' . $id)) {
				return redirect()->route('fiche', $client_id)->with('success', 'Supprimée avec succès');
			}
		}

		return back()->with('success', 'Supprimée avec succès');
	}
	/*
	public function list_as400()
	{
		try{
			$server = config('as400.server');
			$db = config('as400.db');
			$user = config('as400.user');
			$pass = config('as400.pass');

			$dsn = "DRIVER={iSeries Access ODBC Driver};SYSTEM=$server;DBNAME=$db;UID=$user;PWD=$pass;charset=utf8";

 			$pdo = new \PDO("odbc:$dsn", $user, $pass);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$query = "
			SELECT
				SIAGPR AS agence,
				SISUPR AS sujet,
				SITYPR AS type,
				SICDPR AS code,
				SICLPR AS numero_client,
				SINOM AS nom_client,
				SINAT AS nature_lot,
				SIPDS AS poids,
				SIESS AS metal,
				SIMT AS montant,
				SIDTPR AS date,
				SINUPR AS numero_document,
				CREHEU AS heure
			FROM
				specif1.siprcop1
			WHERE
				SIAGPR <> ''
				AND
				SIAGPR <> 'WEB'
			ORDER BY
				SIDTPR DESC,
				CREHEU DESC
		";

		// Préparation de la requête
		$stmt = $pdo->prepare($query);

		// Exécution de la requête
		$stmt->execute();

		// Récupération des résultats
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$result='';
		    // Affichage des résultats
			if (count($results) > 0) {
				$result.= "<h1>Données de la table siprop1 (concernant les prises de contact faites dans l'AS400)</h1>";
				$result.= "<table border='1'>";

				// Afficher les en-têtes du tableau
				$result.= "<tr>";
				foreach (array_keys($results[0]) as $header) {
					$result.= "<th>" . htmlspecialchars($header) . "</th>";
				}
				$result.= "</tr>";

				// Affichage des lignes de résultats
				foreach ($results as $row) {
					$result.= "<tr>";
					// Formatage des données date
					$date = $row['DATE'];
					if ($date !== null) {
						// DD/MM/YYYY à partir de YYMMDD
						$formattedDate = substr($date, 4, 2) . '/' . substr($date, 2, 2) . '/' . ('20' . substr($date, 0, 2));
					} else {
						$formattedDate = 'N/A';
					}

					// Formatage de l'heure au format HH:MM:SS
					$hour = $row['HEURE'];
					if ($hour !== null) {
						$formattedHour = sprintf('%02d:%02d:%02d', floor($hour / 10000), floor(($hour / 100) % 100), $hour % 100);
					} else {
						$formattedHour = 'N/A';
					}

					$result.= "<td>" . htmlspecialchars($row['AGENCE']) . "</td>";
					$result.= "<td>" . htmlspecialchars($row['SUJET']) . "</td>";
					$result.= "<td>" . htmlspecialchars($row['TYPE']) . "</td>";
					$result.= "<td>" . htmlspecialchars($row['CODE']) . "</td>";
					$result.= "<td>" . htmlspecialchars($row['NUMERO_CLIENT']) . "</td>";
					$result.= "<td>" . htmlspecialchars($row['NOM_CLIENT']) . "</td>";
					$result.= "<td>" . htmlspecialchars($row['NATURE_LOT']) . "</td>";
					$result.= "<td>" . htmlspecialchars($row['POIDS']) . "</td>";
					$result.= "<td>" . htmlspecialchars($row['METAL']) . "</td>";
					$result.= "<td>" . htmlspecialchars($row['MONTANT']) . "</td>";
					$result.= "<td>" . htmlspecialchars($formattedDate) . "</td>";
					$result.= "<td>" . htmlspecialchars($row['NUMERO_DOCUMENT']) . "</td>";
					$result.= "<td>" . htmlspecialchars($formattedHour) . "</td>";
					$result.= "</tr>";
				}
				$result.= "</table>";
			} else {
				$result.= "Aucun résultat trouvé.";
			}

		} catch (\Exception $e) {
			\Log::info(' erreur insert as400 '.$e->getMessage());
			return "Erreur : " . $e->getMessage();
		}
		finally {

		}
		return $result;
	}
*/
} // end class
