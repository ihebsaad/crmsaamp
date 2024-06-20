<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Google;
use Google_Client;
use Google_Service_Gmail;

class GoogleController extends Controller
{
    private $googleClient;

    public function __construct()
    {
        $this->googleClient = new Google_Client();
        $this->googleClient->setClientId(config('services.google.client_id'));
        $this->googleClient->setClientSecret(config('services.google.client_secret'));
        $this->googleClient->setRedirectUri(config('services.google.redirect'));
        $this->googleClient->addScope(Google_Service_Gmail::MAIL_GOOGLE_COM);
        $this->googleClient->setAccessType('offline');
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->googleClient->createAuthUrl();
        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $this->googleClient->authenticate($request->input('code'));
        $token = $this->googleClient->getAccessToken();

        // Stocker le token dans la session ou la base de données
        session(['google_token' => $token]);

        return redirect()->route('home')->with('success', 'Vous êtes maintenant connecté à Gmail');
    }
}
