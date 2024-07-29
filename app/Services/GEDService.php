<?php

namespace App\Services;

use DB;


class GEDService
{

	public static function getToken()
	{

		$authUrl = config('ged.url');
		$user = config('ged.user');
		$pass = config('ged.pass');

		// Données d'authentification
		$authData = json_encode(
			array(
				'login' => $user,
				'password' => $pass
			)
		);

		// Initialisation de cURL pour l'authentification
		$curlAuth = curl_init();

		// Configuration de cURL pour l'authentification
		curl_setopt_array($curlAuth, array(
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_URL => $authUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $authData,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
			CURLOPT_SSL_VERIFYPEER => false
		)
		);

		// Exécution de la requête cURL pour l'authentification
		$authResponse = curl_exec($curlAuth);
		$authErr = curl_error($curlAuth);

		// Fermeture de la session cURL pour l'authentification
		curl_close($curlAuth);

		// Vérification des erreurs cURL pour l'authentification
		if ($authErr) {
			\Log::error("Erreur auth GED cURL : " . $authErr);
		} else {
			// Décodage de la réponse d'authentification
			$authResponseData = json_decode($authResponse, true);

			// Vérification de l'authentification réussie et récupération du token
			if (isset($authResponseData['data']['authToken'])) {
				$authToken = $authResponseData['data']['authToken'];
				return $authToken;
			} else {
				\Log::error("Erreur GED : Authentification échouée, token non reçu.");
			}
		}

	}


	public static function curlExecute($apiUrl){
		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);

		// Initialisation de cURL
		$curl = curl_init();

		// Configuration de cURL
		curl_setopt_array($curl, array(
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_URL => $apiUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_SSL_VERIFYPEER => false
		)
		);

		// Exécution de la requête cURL
		$response = curl_exec($curl);
		$err = curl_error($curl);

		// Fermeture de la session cURL
		curl_close($curl);

		// Vérification des erreurs cURL
		if ($err) {
			\Log::error("Erreur GED  : " . $err);
		} else {
			return $response ;
		}

	}


	public static function getItem($itemId){

		// URL de l'API pour visualiser l'élément
		$apiUrl = "https://ged.maileva.com/api/document/$itemId/stream";

 		// Exécution de la requête cURL
		$response = self::curlExecute($apiUrl);

		header('Content-Type: application/pdf');
		return $response;

	}



	public static function getFolderParent($clientDisplayName){

		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/folder/name/" . $clientDisplayName;
		$response = self::curlExecute($apiUrl);

		// Décodage de la réponse JSON
		$data = json_decode($response, true);
		if (empty($response)) {
			return "<p class='aucun'>Aucun dossier trouvé pour le client.</p>";
		} else {
			$responseArray = json_decode($response, true);
			// Conversion JSON a réussi
			if ($responseArray !== null && $responseArray['success'] === true) {
				$parFolderId = $responseArray['data']['id'];

				$result=self::getFolderList($parFolderId);
				return $result;

			}
		}

	}
/*
	public static function getFolderList($folderId){

		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/folders/list/$folderId";
		$response = self::curlExecute($apiUrl);

		// Décodage de la réponse JSON
		$data = json_decode($response, true);
		\Log::info('getFolderList '.json_encode($response));
		if ($data !== null && $data['success'] === true) {
			return $data['data'];
		}
	}
*/
	public static function getFolderList($folderId) {
		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/folders/list/$folderId";
		$response = self::curlExecute($apiUrl);

		// Décodage de la réponse JSON
		$data = json_decode($response, true);
		\Log::info('getFolderList ' . json_encode($response));

		if ($data !== null && $data['success'] === true) {
			$folders = $data['data'];

			// Trier les dossiers par nom
			usort($folders, function ($a, $b) {
				return strcmp($a['name'], $b['name']);
			});

			return $folders;
		}

		return [];
	}




	public static function getFolderContent($folderId){

		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/document/childrenOf/$folderId?p=$folderId";
		$response = self::curlExecute($apiUrl);

		// Décodage de la réponse JSON
		$data = json_decode($response, true);
		if ($data !== null && $data['success'] === true) {
			\Log::info('getFolderContent '.json_encode($response));

			return $data['data'];
		}
	}

	public static function downloadItem($itemId){
		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);

		// URL de l'API pour visualiser l'élément
		$apiUrl = "https://ged.maileva.com/api/document/$itemId/download";

		// Initialisation de cURL
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_URL => $apiUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HEADER => true
		)
		);

		// Exécution de la requête cURL
		$response = curl_exec($curl);
		$err = curl_error($curl);

		// Fermeture de la session cURL
		curl_close($curl);

		// Vérification des erreurs cURL
		if ($err) {
			\Log::error("Erreur GED download : " . $err);
		} else {
			// Séparation de l'en-tête et du corps de la réponse
			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			$header = substr($response, 0, $header_size);
			$body = substr($response, $header_size);

			// Extraction du nom du fichier à partir des en-têtes
			if (preg_match('/filename="([^"]+)"/', $header, $matches)) {
				$fileName = $matches[1];
			} else {
				$fileName = "downloaded_file";
			}

			// Téléchargement du fichier
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $fileName . '"');
			header('Content-Length: ' . strlen($body));
			echo $body;
		}
	}



}
