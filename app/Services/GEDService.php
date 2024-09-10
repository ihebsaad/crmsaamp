<?php

namespace App\Services;

use DB;
use Illuminate\Support\Facades\Http;


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
		curl_setopt_array(
			$curlAuth,
			array(
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


	public static function curlExecute($apiUrl)
	{
		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);

		// Initialisation de cURL
		$curl = curl_init();

		// Configuration de cURL
		curl_setopt_array(
			$curl,
			array(
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
			return $response;
		}
	}


	public static function getItem($itemId)
	{

		// URL de l'API pour visualiser l'élément
		$apiUrl = "https://ged.maileva.com/api/document/$itemId/stream";

		// Exécution de la requête cURL
		$response = self::curlExecute($apiUrl);

		header('Content-Type: application/pdf');
		return $response;
	}

	public static function editItem($itemId, $attachment, $id,$type)
	{

		// Récupérer le contenu du fichier et le nom du fichier
		$fileName = $attachment->getClientOriginalName();

		$headers = [
			'Content-Type: multipart/form-data',
			'Auth-Token: ' . self::getToken()
		];

		// URL de l'API pour remplacer l'élément
		$apiUrl = "https://ged.maileva.com/api/document/{$itemId}/replace";

		// Construction des données pour l'envoi de fichier
		$postFields = [
			'attachment' => new \CURLFile($attachment->getPathname(), $attachment->getMimeType(), $fileName)
		];

		// Initialisation de cURL
		$curl = curl_init();

		// Configuration de cURL
		curl_setopt_array($curl, [
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_URL => $apiUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $postFields,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_SSL_VERIFYPEER => false,
		]);

		// Exécution de la requête cURL
		$response = curl_exec($curl);
		$err = curl_error($curl);

		// Fermeture de la session cURL
		curl_close($curl);

		// Vérification des erreurs cURL
		if ($err) {
			echo "Erreur cURL : " . $err;
		} else {
			// Traitement de la réponse de l'API
			$data = json_decode($response, true);
			if ($data && $data['success'] === true) {
				if($type=='client')
					return redirect()->route('compte_client.folder', ['id' => $id])->with(['success' => "Le document a été mis à jour avec succès. "]);
				else
					return redirect()->route('offres.show',$id)->with(['success' => "Le document a été mis à jour avec succès. "]);

			} else {

				if($type=='client')
					return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur lors de la mise à jour du document."]);
				else
					return redirect()->route('offres.show',$id)->withErrors(['msg' => "Erreur lors de la mise à jour du document."]);

			}
		}
	}


	public static function getFolderParent($clientDisplayName)
	{

		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/folder/name/" . $clientDisplayName;
		//$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OFFRES%20DE%20PRIX/18931/1176";

		//$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OUVERTURE%20DE%20COMPTE/". $clientDisplayName;

		$response = self::curlExecute($apiUrl);

		// Décodage de la réponse JSON
		$data = json_decode($response, true);

		if (empty($response)) {
			//dd('Aucun dossier trouvé pour le client.');
			return "<p class='aucun'>Aucun dossier trouvé pour le client.</p>";
		} else {
			$responseArray = json_decode($response, true);
			// Conversion JSON a réussi
			if ($responseArray !== null && $responseArray['success'] === true) {
				$parFolderId = $responseArray['data']['id'];

				$result = self::getFolderContent($parFolderId);
				//$result=self::getFolderList($parFolderId);
				return $result;
			}
		}
	}

	/*
	public static function getFolders($clientDisplayName){

		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/folder/name/" . $clientDisplayName;
		//$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OUVERTURE%20DE%20COMPTE/". $clientDisplayName;

		$response = self::curlExecute($apiUrl);

		// Décodage de la réponse JSON
		$data = json_decode($response, true);

		if (empty($response)) {
			//dd('Aucun dossier trouvé pour le client.');
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
*/


	public static function getFolders($clientId)
	{
		// URL de l'API pour récupérer les sous-dossiers
		//$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OUVERTURE%20DE%20COMPTE";
		$apiUrl = "https://ged.maileva.com/api/folder/find?parentId=1304415&name=$clientId";

		$response = self::curlExecute($apiUrl);
		$compteFolderId = null;
		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);
		// Décodage de la réponse JSON
		$data = json_decode($response, true);

		$clientFolderId = null;

		if ($data !== null && $data['success'] === true) {
			$compteFolderId = $data['data']['id'];
			//dd($data);
			//Vérifier si un dossier pour ce client nommé $id_client existe déjà dans "DOCUMENTS OUVERTURE DE COMPTE" sinon on le créer
			$apiUrl2 = "https://ged.maileva.com/api/folder/find?parent_id=$compteFolderId&name=$clientId";
			$response = self::curlExecute($apiUrl2);

			if ($data !== null && $data['success'] === true) {
				$clientFolderId = $data['data']['id'];
				$result = self::getFolderList($clientFolderId);
				return $result;
			}
		}
	}


	public static function getFolderList($folderId)
	{
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




	public static function getFolderContent($folderId)
	{

		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/document/childrenOf/$folderId?p=$folderId";
		$response = self::curlExecute($apiUrl);

		// Décodage de la réponse JSON
		$data = json_decode($response, true);
		if ($data !== null && $data['success'] === true) {
			\Log::info('getFolderContent ' . json_encode($response));

			return $data['data'];
		}
	}

	public static function downloadItem($itemId)
	{
		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);

		// URL de l'API pour visualiser l'élément
		$apiUrl = "https://ged.maileva.com/api/document/$itemId/download";

		// Initialisation de cURL
		$curl = curl_init();

		curl_setopt_array(
			$curl,
			array(
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






	// OUVERTURE DE COMPTE
	public static function Account($clientId, $type, $id)
	{

		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OUVERTURE%20DE%20COMPTE";
		$response = self::curlExecute($apiUrl);
		$compteFolderId = null;

		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);
		// Décodage de la réponse JSON
		$data = json_decode($response, true);

		if ($data !== null && $data['success'] === true) {
			$compteFolderId = $data['data']['id'];
		} else {
			\Log::error("Le dossier 'DOCUMENTS OUVERTURE DE COMPTE' n'existe pas ou n'a pas pu être trouvé.");
			return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Le dossier 'DOCUMENTS OUVERTURE DE COMPTE' n'existe pas ou n'a pas pu être trouvé."]);

			exit;
		}

		//Vérifier si un dossier pour ce client nommé $id_client existe déjà dans "DOCUMENTS OUVERTURE DE COMPTE" sinon on le créer
		$clientFolderId = null;
		$apiUrl2 = "https://ged.maileva.com/api/folder/find?parent_id=$compteFolderId&name=$clientId";
		$response = self::curlExecute($apiUrl2);

		if ($data !== null && $data['success'] === true) {
			$clientFolderId = $data['data']['id'];
		} else {
			// Si le dossier client n'existe pas, on le crée


			$apiUrl = "https://ged.maileva.com/api/folder/";
			$postData = json_encode(array(
				"name" => $clientId,
				"parentId" => $compteFolderId
			));
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_URL => $apiUrl,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => $postData,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_SSL_VERIFYPEER => false
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {

				\Log::error("Erreur cURL lors de la création du dossier client ");
				return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur cURL lors de la création du dossier client: " . $err]);
				exit;
			} else {
				$data = json_decode($response, true);
				if ($data !== null && $data['success'] === true) {
					$clientFolderId = $data['data']['id'];
				} else {
					\Log::error("Erreur lors de la création du dossier client ");
					//return back()->withErrors(['msg' => "Erreur lors de la création du dossier client"]);
					return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur lors de la création du dossier client"]);

					exit;
				}
			}
		}

		$typeDoc = '';

		switch ($type) {
			case 1:
				$typeDoc = "DOCUMENTS OUVERTURE DE COMPTE POIDS";
				break;
			case 2:
				$typeDoc = "PRINCIPES ET CODE DES PRATIQUES DU RJC ET DE SAAMP";
				break;
			case 3:
				$typeDoc = "DECLARATION DUE DILIGENCE";
				break;
			case 4:
				$typeDoc = "CNI OU PASSEPORT";
				break;
			case 5:
				$typeDoc = "KBIS DE MOINS DE 3 MOIS OU REPERTOIRE DES METIERS";
				break;
			case 6:
				$typeDoc = "DECLARATION DEXISTENCE AUPRES DE LA GARANTIE";
				break;
			case 7:
				$typeDoc = "LETTRE DE FUSION";
				break;
			case 8:
				$typeDoc = "RIB";
				break;
		}

		$subfolderPath = "DOCUMENTS OUVERTURE DE COMPTE/$clientId/$typeDoc"; //
		$apiUrl = "https://ged.maileva.com/api/folder/path";
		$postData = json_encode(array(
			"path" => $subfolderPath,
			"parent_id" => $compteFolderId
		));
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_URL => $apiUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $postData,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_SSL_VERIFYPEER => false
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			echo "Erreur cURL lors de la création du chemin de dossier : " . $err;
			return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur cURL lors de la création du chemin de dossier :" . $err]);

			exit;
		} else {
			$data = json_decode($response, true);
			if ($data !== null && $data['success'] === true) {
				$newFolderId = $data['data']['id'];
			} else {
				//echo "Erreur lors de la création du sous-dossier.";
				//dd($clientId);
				\Log::error("Erreur cURL lors de la création du sous-dossier");

				return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur lors de la création du sous-dossier "]);

				exit;
			}
		}

		// Téléchargement du fichier dans le sous-dossier nouvellement créé
		$Path = "DOCUMENTS OUVERTURE DE COMPTE/$clientId/$typeDoc";
		foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
			$fileName = $_FILES['files']['name'][$key];
			$fileType = $_FILES['files']['type'][$key];
			$filePath = $_FILES['files']['tmp_name'][$key];

			$file = new \CURLFile($filePath, $fileType, $fileName);
			$postFields = array(
				'file' => $file,
				'path' => $Path
			);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://ged.maileva.com/api/document/',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $postFields,
				CURLOPT_HTTPHEADER => array(
					'Auth-Token: ' . self::getToken()
				),
				CURLOPT_SSL_VERIFYPEER => false
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {
				//echo "Erreur cURL : " . $err;
				\Log::error("Erreur cURL de telechargement GED");
				//return back()->withErrors(['msg' => $err]);
				return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur cURL de telechargement GED"]);
			} else {
				//echo "Fichier téléchargé avec succès";
				//return back()->with('success', ' Fichier téléchargé avec succès');
				\Log::info(" telechargement GED avec succes");

				return redirect()->route('compte_client.folder', ['id' => $id])->with(['success' => "Fichier téléchargé avec succès "]);
			}
		}
	}



	// Ajout document offres
	public static function OffreDocs($clientId, $offreId, $id)
	{

		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OFFRES%20DE%20PRIX";

		$response = self::curlExecute($apiUrl);
		$commercialFolderId = null;

		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);
		// Décodage de la réponse JSON
		$data = json_decode($response, true);

		if ($data !== null && $data['success'] === true) {
			$commercialFolderId = $data['data']['id'];
		} else {
			\Log::error("Le dossier 'DOCUMENTS OFFRES DE PRIX' n'existe pas ou n'a pas pu être trouvé.");
			return redirect()->route('offres.client_list', ['id' => $id])->withErrors(['msg' => "Le dossier 'DOCUMENTS OFFRES DE PRIX' n'existe pas ou n'a pas pu être trouvé."]);

			exit;
		}

		//Vérifier si un dossier pour ce client nommé $id_client existe déjà dans "DOCUMENTS OUVERTURE DE COMPTE" sinon on le créer
		$clientFolderId = null;
		$apiUrl2 = "https://ged.maileva.com/api/folder/find?parent_id=$commercialFolderId&name=$clientId";
		$response = self::curlExecute($apiUrl2);

		if ($data !== null && $data['success'] === true) {
			$clientFolderId = $data['data']['id'];
		} else {
			// Si le dossier client n'existe pas, on le crée


			$apiUrl = "https://ged.maileva.com/api/folder/";
			$postData = json_encode(array(
				"name" => $clientId,
				"parentId" => $commercialFolderId
			));
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_URL => $apiUrl,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => $postData,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_SSL_VERIFYPEER => false
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {

				\Log::error("Erreur cURL lors de la création du dossier client ");
				return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur cURL lors de la création du dossier client: " . $err]);
				exit;
			} else {
				$data = json_decode($response, true);
				if ($data !== null && $data['success'] === true) {
					$clientFolderId = $data['data']['id'];
				} else {
					\Log::error("Erreur lors de la création du dossier client ");
					//return back()->withErrors(['msg' => "Erreur lors de la création du dossier client"]);
					return redirect()->route('offres.client_list.folder', ['id' => $id])->withErrors(['msg' => "Erreur lors de la création du dossier client"]);

					exit;
				}
			}
		}


		$subfolderPath = "DOCUMENTS OUVERTURE DE COMPTE/$clientId/$offreId"; //
		$apiUrl = "https://ged.maileva.com/api/folder/path";
		$postData = json_encode(array(
			"path" => $subfolderPath,
			"parent_id" => $commercialFolderId
		));
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_URL => $apiUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $postData,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_SSL_VERIFYPEER => false
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			echo "Erreur cURL lors de la création du chemin de dossier : " . $err;
			return redirect()->route('offres.client_list', ['id' => $id])->withErrors(['msg' => "Erreur cURL lors de la création du chemin de dossier :" . $err]);

			exit;
		} else {
			$data = json_decode($response, true);
			if ($data !== null && $data['success'] === true) {
				$newFolderId = $data['data']['id'];
			} else {
				//echo "Erreur lors de la création du sous-dossier.";
				//dd($clientId);
				\Log::error("Erreur cURL lors de la création du sous-dossier");

				return redirect()->route('offres.client_list', ['id' => $id])->withErrors(['msg' => "Erreur lors de la création du sous-dossier "]);

				exit;
			}
		}

		// Téléchargement du fichier dans le sous-dossier nouvellement créé
		$Path = "DOCUMENTS OFFRES DE PRIX/".$clientId."/".$offreId;
		foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
			$fileName = $_FILES['files']['name'][$key];
			$fileType = $_FILES['files']['type'][$key];
			$filePath = $_FILES['files']['tmp_name'][$key];

			$file = new \CURLFile($filePath, $fileType, $fileName);
			$postFields = array(
				'file' => $file,
				'path' => $Path
			);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://ged.maileva.com/api/document/',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $postFields,
				CURLOPT_HTTPHEADER => array(
					'Auth-Token: ' . self::getToken()
				),
				CURLOPT_SSL_VERIFYPEER => false
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {
				//echo "Erreur cURL : " . $err;
				\Log::error("Erreur cURL de telechargement GED". $err);
				//return back()->withErrors(['msg' => $err]);
				return redirect()->route('offres.client_list', ['id' => $id])->withErrors(['msg' => "Erreur cURL de telechargement GED". $err]);
			} else {
				//echo "Fichier téléchargé avec succès";
				//return back()->with('success', ' Fichier téléchargé avec succès');
				\Log::info(" telechargement GED offre avec succes".$Path);

				//return redirect()->route('offres.folder', ['id' => $id])->with(['success' => "Fichier téléchargé avec succès "]);
			}
		}
	}

}
