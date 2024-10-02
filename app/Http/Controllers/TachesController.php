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


	public function index()
	{
		//$taches=Tache::where('id','<>',null)->limit(1000)->orderBy('id','desc')->get();

/*
		$taches=Tache::where(function ($query) {
			$query->where('type', 'Appel téléphonique')
				->orWhere('type', 'Envoyer email')
				->orWhere('type', 'Envoyer courrier');
		})->where('id','<>',null)
		->limit(1000)->orderBy('id','desc')->get();
		*/

		$taches=Tache::limit(1000)->orderBy('id','desc')->get();

		$agences = DB::table('agence')->get();

		return view('taches.list',compact('taches','agences'));
	}

	public function mestaches()
	{

		$taches=Tache::where('user_id',auth()->user()->id)
		->orderBy('id','desc')
		->get();


		$agences = DB::table('agence')->get();

		return view('taches.list',compact('taches','agences'));
	}


	public function create($id)
	{
		$client=CompteClient::find($id);
		$agences = DB::table('agence')->get();

		return view('taches.create',compact('client','agences'));
	}

	public function client_list($id)
	{
		$client=CompteClient::find($id);
		$taches=Tache::where('mycl_id',$client->cl_ident)->get();
		return view('taches.list',compact('taches','client'));
	}

	public function show($id)
	{
		$tache=Tache::find($id);
		$client=CompteClient::find($tache->ID_Compte);
		$agences = DB::table('agence')->get();

		return view('taches.show',compact('tache','client','agences'));
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

        $tache=Tache::create($request->all());

/*
		$client=CompteClient::find($tache->ID_Compte);
		$agence_id=$client->agence_ident;
		$agence_name=Agence::where('agence_ident',$agence_id)->first()->agence_lib ?? '';
		$tache->Nom_de_compte=$client->Nom;
		$tache->Agence=$agence_name;
*/

		$tache->save();

		if($tache->ID_Compte >0)
			return redirect()->route('fiche', ['id' => $tache->ID_Compte])->with(['success' => "Tâche ajoutée "]);

		return redirect()->route('taches.show', $tache->id)
		->with('success','Tache ajoutée');
	}


	public function destroy($id)
	{
 		$tache = Tache::find($id);

		if ($tache) {
			if(intval($tache->ID_Compte)>0)
				$client_id=$tache->ID_Compte;
			else
				$client_id=Client::where('cl_ident',$tache->mycl_id)->first();

			$tache->delete();

			$previousUrl = url()->previous();

			if (str_contains($previousUrl, '/show/' . $id)) {
				return redirect()->route('fiche',$client_id)->with('success', 'Supprimée avec succès');
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
