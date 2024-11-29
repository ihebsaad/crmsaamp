<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use App\Models\GoogleToken;
use App\Models\CompteClient;
use App\Models\User;
use Auth;
use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use Google\Service\Calendar\Channel as GoogleChannel;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(route('google.auth.callback'));
        $client->setAccessType('offline'); // Demande un refresh_token
        $client->setPrompt('consent'); // Assure que l'écran de consentement est affiché
        $client->addScope('https://www.googleapis.com/auth/calendar');
        $client->addScope('https://www.googleapis.com/auth/calendar.events');

        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(route('google.auth.callback'));

        if ($request->has('code')) {
            $token = $client->fetchAccessTokenWithAuthCode($request->code);

            // Ajoutez un log pour voir la réponse complète
            \Log::info('Google Token Response', ['token' => $token]);

            if (isset($token['error'])) {
                return redirect()->route('dashboard')->withErrors('Erreur Google : ' . $token['error']);
            }

            // Vérifiez si la clé 'access_token' existe
            if (!isset($token['access_token'])) {
                redirect()->route('dashboard')->withErrors('Token d\'accès non reçu de Google.');
            }

            if (isset($token['refresh_token'])) {
                \Log::info('Refresh Token récupéré', ['refresh_token' => $token['refresh_token']]);
            } else {
                \Log::warning('Refresh Token manquant');
            }

            // Enregistrer les tokens pour l'utilisateur connecté
            $user = Auth::user();
            GoogleToken::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'access_token' => $token['access_token'],
                    'refresh_token' => $token['refresh_token'] ?? GoogleToken::where('user_id', $user->id)->value('refresh_token'),
                    'expires_in' => $token['expires_in'] ?? null,
                ]
            );

            return redirect()->route('dashboard')->with('status', 'Compte Google connecté avec succès.');
        }

        return redirect()->route('dashboard')->withErrors('Impossible de se connecter à Google.');
    }








        public function subscribeToGoogleCalendar()
    {
        try {
        // Initialiser le client Google
        $client = new GoogleClient();
        //$client->setAuthConfig(storage_path('app/credentials.json')); // Chemin vers le fichier credentials.json
        //$client->setAuthConfig(config('google-calendar.auth_profiles.oauth.credentials_json')); // Chemin vers le fichier credentials.json
        $client->setAuthConfig(storage_path('app/google-calendar/client_secret_722057498712-sil0l4pf60uug2131f528m9nfl2d4ko4.apps.googleusercontent.com.json')); // Chemin vers le fichier credentials.json
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        //$client->addScope(GoogleCalendar::CALENDAR);
        $client->addScope('https://www.googleapis.com/auth/calendar');
        $client->addScope('https://www.googleapis.com/auth/calendar.events');

        // Initialiser le service Google Calendar
        $service = new GoogleCalendar($client);

        // Configurer le canal (webhook)
        $channel = new GoogleChannel();
        $channel->setId(uniqid()); // Identifiant unique pour le canal
        $channel->setType('web_hook');
        $channel->setAddress('https://crm.mysaamp.com/google-calendar/webhook'); // URL de votre webhook Laravel

        // Demander à Google de suivre les événements du calendrier

            $watchResponse = $service->events->watch('primary', $channel); // "primary" = calendrier principal de l'utilisateur
            return response()->json(['status' => 'Webhook configured', 'response' => $watchResponse]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 500);
        }


    }



        public function webhook(Request $request)
    {
        // Vérifiez les données entrantes
        $data = $request->all();

        \Log::info('Google Calendar Webhook Triggered', $data);

        if (isset($data['id'])) {
            // Initialisez le client Google API
            $client = new \Google_Client();
            $client->setAuthConfig(storage_path('credentials.json'));
            $client->addScope(\Google_Service_Calendar::CALENDAR);
            $service = new \Google_Service_Calendar($client);

            try {
                // Récupérez l'événement depuis l'API Google Calendar
                $event = $service->events->get('primary', $data['id']);

                $user_id = 0;
                $user = User::where('email', $event->creator->email)->first();
                if (isset($user)) {
                    $user_id = $user->id;
                }

                // Extraire `heure_debut`, `heure_fin`, `date_debut`, et `date_fin`
                $start = $event->start->dateTime ?? $event->start->date; // Format dateTime ou date
                $end = $event->end->dateTime ?? $event->end->date;

                // Séparation des dates et heures
                $date_debut = substr($start, 0, 10); // Récupère la partie date (YYYY-MM-DD)
                $heure_debut = isset($event->start->dateTime) ? substr($start, 11, 5) : null; // Récupère l'heure si disponible (HH:MM)

                $date_fin = substr($end, 0, 10); // Récupère la partie date (YYYY-MM-DD)
                $heure_fin = isset($event->end->dateTime) ? substr($end, 11, 5) : null; // Récupère l'heure si disponible (HH:MM)

                // Enregistrez le rendez-vous dans la base de données
                RendezVous::create([
                    'user_id' => $user_id,
                    'Subject' => $event->summary ?? 'Sans titre', // Gérer le cas où le titre est null
                    'Started_at' => $date_debut,
                    'heure_debut' => $heure_debut,
                    'End_at' => $date_fin,
                    'heure_fin' => $heure_fin,
                    'Location' => $event->location ?? 'Non spécifié', // Gestion du champ null
                    'Description' => $event->description ?? '', // Description facultative
                ]);

            } catch (\Google_Service_Exception $e) {
                // Gérer les erreurs lors de la récupération de l'événement
                \Log::error('Erreur lors de la récupération de l\'événement : ' . $e->getMessage());
            }
        }


        return response()->json(['status' => 'success']);
    }



}
