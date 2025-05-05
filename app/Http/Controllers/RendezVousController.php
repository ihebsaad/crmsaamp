<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
use App\Models\GoogleToken;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use App\Models\Consultation;
use Illuminate\Support\Facades\Log;

class RendezVousController extends Controller
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
		//$rendezvous=RendezVous::orderBy('id','desc')->limit(50)->get();

		$rendezvous = RendezVous::where('Attribue_a', auth()->user()->name . ' ' . auth()->user()->lastname)
			->orWhere('user_id', auth()->user()->id)
			->orderBy('id', 'desc')->get();

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Liste des rendez vous"]);

		return view('rendezvous.list', compact('rendezvous'));
	}

	public function mesrendezvous()
	{
		//$rendezvous=RendezVous::orderBy('id','desc')->limit(50)->get();

		$rendezvous = RendezVous::where('Attribue_a', auth()->user()->name . ' ' . auth()->user()->lastname)
			->orWhere('user_id', auth()->user()->id)
			->orderBy('id', 'desc')->get();

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Mes rendez vous"]);

		return view('rendezvous.list', compact('rendezvous'));
	}

	public function create($id)
	{
		if ($id > 0)
			$client = CompteClient::find($id);
		else
			$client = null;

		$userToken = GoogleToken::where('user_id', auth()->id())->first();
		$clients = CompteClient::get();
		$users = User::where('user_type', '<>', '')->get();

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Création du rendez vous"]);

		return view('rendezvous.create', compact('client', 'users','userToken','clients'));
	}

	public function show($id)
	{
		$rendezvous = RendezVous::find($id);
		$files = File::where('parent', 'rendezvous')->where('parent_id', $rendezvous->id)->get();
		if ($rendezvous->AccountId > 0) {
			$client = CompteClient::where('id', $rendezvous->AccountId)->first();
			$adresse1 = $client->adresse1 ?? '';
			$zip = $client->zip ?? '';
			$adresse = $adresse1 . ' - ' . $zip;

			if ($client && $client->id == 1 && $id != 1) {
				$client = DB::table('CRM_CompteCLient')->where('Id_Salesforce', $rendezvous->AccountId)->first();
				$rue = $client->Rue ?? '';
				$zip = $client->postalCode ?? '';
				$adresse = $rue . ' ' . $zip;
			}
		} else {
			$client = null;
			$adresse = '';
		}
        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Affichage du rendez vous"]);

		return view('rendezvous.show', compact('rendezvous', 'client', 'adresse', 'files'));
	}

	public function print($id)
	{
		$rendezvous = RendezVous::find($id);
		$user=User::find($rendezvous->created_by);
		if ($rendezvous->AccountId > 0) {
			$client = CompteClient::find($rendezvous->AccountId);
		} else {
			$client = null;
		}

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Impression du rendez vous"]);

		return view('rendezvous.print', compact('rendezvous', 'client','user'));
	}


	public function store(Request $request)
	{
		$request->validate([
			'Subject' => 'required',
			'AccountId' => 'required',
		]);

		if($request->input('AccountId')>0){
			$client = CompteClient::find($request->input('AccountId'));
			$Account_Name=$client->Nom;
		}else{
			$Account_Name=$request->input('Account_Name');
		}
		
		//$rendezvous=RendezVous::create($request->all());

		$rendezvous = RendezVous::create([
			'AccountId' => $request->input('AccountId') ?? 0,
			'created_by' => $request->input('created_by'),
			'mycl_id' => $request->input('mycl_id'),
			'user_id' => $request->input('user_id'),
			'Account_Name' => $Account_Name,
			'Started_at' => $request->input('Started_at'),
			'heure_debut' => $request->input('heure_debut'),
			'End_AT' => $request->input('End_AT'),
			'heure_fin' => $request->input('heure_fin'),
			'Type' => $request->input('Type'),
			'Location' => $request->input('Location'),
			'Subject' => $request->input('Subject'),
			'Description' => $request->input('Description'),
			'mode_de_rdv' => $request->input('mode_de_rdv'),
			'statut' => $request->input('statut'),
		]);

		$rendezvous->save();

		

		// Synchroniser avec Google Calendar
		$googleEventId = $this->addToGoogleCalendar($rendezvous);
		if ($googleEventId) {
			$rendezvous->google_event_id = $googleEventId;
			$rendezvous->save();
		}

		/*
		if ($request->hasFile('files')) {
			$fichiers = $request->file('files');
			$fileNames = [];

			foreach ($fichiers as $fichier) {
				$name = $fichier->getClientOriginalName();
				$path = public_path() . "/fichiers";
				$fichier->move($path, $name);
				$fileNames[] = $name;
			}

			// Serialize the filenames array
			$rendezvous->fichier = serialize($fileNames);
			$rendezvous->save();
		}
*/
		if ($request->hasFile('files')) {
			$fichiers = $request->file('files');

			foreach ($fichiers as $fichier) {
				$name = $fichier->getClientOriginalName();
				$path = public_path("fichiers/rendezvous");
				$fichier->move($path, $name);

				// Store each file in the files table
				File::create([
					'name' => $name,
					'parent_id' => $rendezvous->id,
					'parent' => 'rendezvous'
				]);
			}
		}

		if ($rendezvous->AccountId > 0)
			return redirect()->route('fiche', ['id' => $rendezvous->AccountId])->with(['success' => "Rendez Vous ajouté "]);

		return redirect()->route('rendezvous.show', $rendezvous->id)
			->with('success', 'Rendez vous ajouté');
	}


	public function update(Request $request, $id)
	{
		/*
        $request->validate([
            'Subject' => 'required',
         ]);
*/
		$rendezvous = RendezVous::find($id);
		$rendezvous->update($request->all());

		if ($request->hasFile('files')) {
			$fichiers = $request->file('files');

			foreach ($fichiers as $fichier) {
				$name = $fichier->getClientOriginalName();
				$path = public_path("fichiers/rendezvous");
				$fichier->move($path, $name);

				// Store each file in the files table
				File::create([
					'name' => $name,
					'parent_id' => $rendezvous->id,
					'parent' => 'rendezvous'
				]);
			}
		}


		// Synchroniser avec Google Calendar
		if ($rendezvous->google_event_id) {

			$startDateTime = Carbon::parse($rendezvous->Started_at);

			if ($rendezvous->heure_debut) {
				$startDateTime->setTimeFromTimeString($rendezvous->heure_debut);
			}

			// Configuration de la date de fin
			if ($rendezvous->End_at) {
				$endDateTime = Carbon::parse($rendezvous->End_at);
			} else {
				$endDateTime = Carbon::parse($rendezvous->Started_at); // Utiliser Started_at si End_at est vide
			}

			if ($rendezvous->heure_fin) {
				$endDateTime->setTimeFromTimeString($rendezvous->heure_fin);
			}

			$this->updateGoogleCalendar($rendezvous);
		} else {
			$googleEventId = $this->addToGoogleCalendar($rendezvous);
			if ($googleEventId) {
				$rendezvous->google_event_id = $googleEventId;
				$rendezvous->save();
			}
		}


		return redirect()->route('rendezvous.show', $id)
			->with('success', 'Rendez vous modifié');
	}

	public function deleteFile(Request $request, $id)
	{
		// Find the rendezvous by ID
		$rendezvous = Rendezvous::find($id);

		// Deserialize the stored file names
		$fileNames = unserialize($rendezvous->fichier);

		// Get the file name to delete
		$fileToDelete = $request->input('file_name');

		// Remove the file from the list
		if (($key = array_search($fileToDelete, $fileNames)) !== false) {
			unset($fileNames[$key]);
		}

		// Re-serialize the array and save the updated list
		$rendezvous->fichier = serialize(array_values($fileNames));
		$rendezvous->save();

		// Delete the file from the filesystem
		$filePath = public_path() . "/fichiers/redezvous/" . $fileToDelete;
		if (file_exists($filePath)) {
			unlink($filePath);  // Delete the file
		}

		return redirect()->back()->with('success', 'Fichier supprimé avec succès.');
	}

	public function destroy($id)
	{
		$rv = RendezVous::find($id);

		$previousUrl = url()->previous();

		if ($rv) {

			if ($rv->google_event_id) {
				$this->deleteFromGoogleCalendar($rv->google_event_id);
			}

			$client_id = $rv->AccountId;
			$rv->delete();

			if (str_contains($previousUrl, '/show/' . $id) && $client_id > 0 || str_contains($previousUrl, 'fiche' ) ) {
				return redirect()->route('fiche', $client_id)->with('success', 'Supprimé avec succès');
			}
		}

		if (str_contains($previousUrl, 'exterieurs') || $client_id == 0) {
			return redirect()->route('agenda')->with('success', 'Supprimé avec succès');
		}

		return redirect()->route('agenda')->with('success', 'Supprimé avec succès');
		//return back()->with('success', 'Supprimé avec succès');
	}

	/*
	function syncToGoogleCalendar()
	{

		$userToken = GoogleToken::where('user_id', auth()->id())->first();

		if (!$userToken) {
			return false; // L'utilisateur n'a pas de compte Google lié
		}

		$client = new Google_Client();
		$client->setAccessToken([
			'access_token' => $userToken->access_token,
			'refresh_token' => $userToken->refresh_token,
			'expires_in' => $userToken->expires_in,
		]);

		// Rafraîchir le token si nécessaire
		if ($client->isAccessTokenExpired()) {
			$newToken = $client->fetchAccessTokenWithRefreshToken($userToken->refresh_token);
			$userToken->update([
				'access_token' => $newToken['access_token'],
				'expires_in' => $newToken['expires_in'],
			]);
		}
	}
*/


	private function addToGoogleCalendar($rendezvous)
	{
		$userToken = GoogleToken::where('user_id', $rendezvous->user_id)->first();
		if (!$userToken) {
			Log::error("Aucun token Google pour l'utilisateur ID: " . $rendezvous->user_id);
			return null;
		}

		$client = $this->getGoogleClient($userToken);
		$service = new Google_Service_Calendar($client);

 		$event = new Google_Service_Calendar_Event([
			'summary' => $rendezvous->Subject,
			'location' => $rendezvous->Location,
			'description' => $rendezvous->Description,
			'start' => [
				'dateTime' => $this->combineDateTime($rendezvous->Started_at, $rendezvous->heure_debut),
				'timeZone' => 'Europe/Paris',
			],
			'end' => [
				'dateTime' => $this->combineDateTime($rendezvous->End_AT, $rendezvous->heure_fin),
				'timeZone' => 'Europe/Paris',
			],
			'colorId' => 11, // Couleur personnalisée
			'reminders' => [
				'useDefault' => false, // Désactiver les rappels par défaut
				'overrides' => [
					['method' => 'popup', 'minutes' => 60], // Notification 1 heure avant
					//['method' => 'email', 'minutes' => 60]  // Email 1 heure avant (optionnel)
				],
			],
		]);

		$calendarId = 'primary'; // Peut être modifié si nécessaire
		$createdEvent = $service->events->insert($calendarId, $event);

		return $createdEvent->id ?? null;
	}


	private function updateGoogleCalendar($rendezvous)
	{
		$userToken = GoogleToken::where('user_id', $rendezvous->user_id)->first();
		if (!$userToken) {
			Log::error("Aucun token Google pour l'utilisateur ID: " . $rendezvous->user_id);
			return false;
		}

		$client = $this->getGoogleClient($userToken);
		$service = new Google_Service_Calendar($client);

		try {
			$event = $service->events->get('primary', $rendezvous->google_event_id);
			$event->setSummary($rendezvous->Subject);
			$event->setLocation($rendezvous->Location);
			$event->setDescription($rendezvous->Description);

			// Vérifiez et combinez les dates/heures
			if (empty($rendezvous->End_AT)) {
				$rendezvous->End_AT = $rendezvous->Started_at;
			}

			$startDateTime = $this->combineDateTime($rendezvous->Started_at, $rendezvous->heure_debut);
			$endDateTime = $this->combineDateTime($rendezvous->End_AT, $rendezvous->heure_fin);

			if (!$startDateTime || !$endDateTime || $startDateTime >= $endDateTime) {
				Log::error("L'heure de début ($startDateTime) est postérieure ou égale à l'heure de fin ($endDateTime).");
				return false;
			}

			$start = new Google_Service_Calendar_EventDateTime();
			$start->setDateTime($startDateTime);
			$start->setTimeZone('Europe/Paris');

			$end = new Google_Service_Calendar_EventDateTime();
			$end->setDateTime($endDateTime);
			$end->setTimeZone('Europe/Paris');

			$event->setStart($start);
			$event->setEnd($end);

			$updatedEvent = $service->events->update('primary', $event->id, $event);

			Log::info("Événement Google mis à jour avec succès : " . $updatedEvent->id);
			return $updatedEvent->id ?? null;
		} catch (\Google_Service_Exception $e) {
			Log::error("Erreur Google Calendar : " . $e->getMessage());
			return false;
		} catch (\Exception $e) {
			Log::error("Erreur inattendue : " . $e->getMessage());
			return false;
		}
	}
	/*
	// Mettre à jour un événement sur Google Calendar
	public function updateGoogleCalendar($eventId, $title, $startDateTime, $endDateTime)
	{

		try {
			// Initialiser le client Google
			$client = new Google_Client();
			$client->setAccessToken(auth()->user()->googleToken->access_token);

			$service = new Google_Service_Calendar($client);

			// Récupérer l'événement existant
			$event = $service->events->get('primary', $eventId);

			// Mettre à jour les détails de l'événement
			$event->setSummary($title);

			// Créer des instances de EventDateTime pour 'start' et 'end'
			$start = new Google_Service_Calendar_EventDateTime();
			$start->setDateTime($startDateTime);
			$start->setTimeZone('Europe/Paris'); // Remplacez par le fuseau horaire nécessaire

			$end = new Google_Service_Calendar_EventDateTime();
			$end->setDateTime($endDateTime);
			$end->setTimeZone('Europe/Paris'); // Remplacez par le fuseau horaire nécessaire

			$event->setStart($start);
			$event->setEnd($end);

			// Enregistrer l'événement mis à jour
			$updatedEvent = $service->events->update('primary', $event->getId(), $event);

			return $updatedEvent;
		} catch (\Exception $e) {
			\Log::error('Erreur lors de la mise à jour de l\'événement Google Calendar : ' . $e->getMessage());
			return false;
		}
	}

*/
	// Supprimer un événement de Google Calendar
	private function deleteFromGoogleCalendar($googleEventId)
	{
		$userToken = GoogleToken::where('user_id', auth()->id())->first();
		if (!$userToken) {
			Log::error("Aucun token Google pour l'utilisateur ID: " . auth()->id());
			return false;
		}

		$client = $this->getGoogleClient($userToken);
		$service = new Google_Service_Calendar($client);

		$service->events->delete('primary', $googleEventId);
		return true;
	}

	// Obtenir un client Google configuré
	private function getGoogleClient($userToken)
	{
		$client = new Google_Client();
		$client->setClientId(env('GOOGLE_CLIENT_ID'));
		$client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
		$client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

		$client->setAccessToken([
			'access_token' => $userToken->access_token,
			'refresh_token' => $userToken->refresh_token,
			'expires_in' => $userToken->expires_in,
		]);

		// Rafraîchir le token si nécessaire
		if ($client->isAccessTokenExpired()) {
			$newToken = $client->fetchAccessTokenWithRefreshToken();
			$userToken->update([
				'access_token' => $newToken['access_token'],
				'expires_in' => $newToken['expires_in'],
			]);
		}

		return $client;
	}
/*
	// Combiner date et heure en format ISO 8601
	private function combineDateTime($date, $time)
	{
		$dateTime = Carbon::parse($date);
		if ($time) {
			$dateTime->setTimeFromTimeString($time);
		}
		return $dateTime->toIso8601String();
	}*/
	private function combineDateTime($date, $time)
	{
		if (!$date || !$time) {
			Log::error("Date ou heure invalide : date = $date, time = $time");
			return null;
		}

		try {
			return Carbon::parse("$date $time")->toIso8601String();
		} catch (\Exception $e) {
			Log::error("Erreur lors de la combinaison de la date et de l'heure : " . $e->getMessage());
			return null;
		}
	}
} // end class
