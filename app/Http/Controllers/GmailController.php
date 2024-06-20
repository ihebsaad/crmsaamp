<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Gmail;

class GmailController extends Controller
{

    public function access()
    {
        return view('gmail.access');

    }
    public function listMessages()
    {
        $client = new Google_Client();
        $client->setAccessToken(session('google_token'));

        if ($client->isAccessTokenExpired()) {
            // Rafraîchir le token si nécessaire
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            session(['google_token' => $client->getAccessToken()]);
        }

        $service = new Google_Service_Gmail($client);
        $messages = $service->users_messages->listUsersMessages('me');

        return view('gmail.messages', compact('messages'));
    }
}
